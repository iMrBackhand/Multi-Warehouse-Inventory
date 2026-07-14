<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GcashPaymentController extends Controller
{
    protected function secretKey()
    {
        return base64_encode(config('services.paymongo.secret_key') . ':');
    }

    // Step 1: Gumawa ng GCash source at i-redirect sa checkout
    public function create(Sale $sale)
    {
        $amount = (int) round($sale->due_amount * 100); // centavos

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->secretKey(),
            'Content-Type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/sources', [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'redirect' => [
                        'success' => route('gcash.success', ['sale' => $sale->id]),
                        'failed'  => route('gcash.failed', ['sale' => $sale->id]),
                    ],
                    'type' => 'gcash',
                    'currency' => 'PHP',
                ],
            ],
        ]);

        if ($response->failed()) {
            Log::error('PayMongo source creation failed', $response->json() ?? []);
            return back()->with('error', 'Hindi magawa ang GCash payment. Subukan ulit.');
        }

        $data = $response->json('data');

        PaymentTransaction::create([
            'sale_id'   => $sale->id,
            'source_id' => $data['id'],
            'amount'    => $sale->due_amount,
            'status'    => 'pending',
        ]);

        return redirect($data['attributes']['redirect']['checkout_url']);
    }

    // Step 2: Landing pages pagkatapos mag-attempt magbayad
    public function success(Request $request)
    {
        return view('admin.due.payment-success')
            ->with('message', 'Salamat! Kinukumpirma pa ang bayad mo.');
    }

    public function failed(Request $request)
    {
        return view('admin.due.payment-failed')
            ->with('message', 'Hindi natuloy ang bayad. Subukan ulit.');
    }

    // Step 3: WEBHOOK - dito lang dapat kinukumpirma na TALAGA namang nabayaran
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload, true);

        $eventType = $event['data']['attributes']['type'] ?? null;

        if ($eventType === 'source.chargeable') {
            $sourceData = $event['data']['attributes']['data'];
            $sourceId = $sourceData['id'];
            $amount = $sourceData['attributes']['amount'];

            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->secretKey(),
                'Content-Type' => 'application/json',
            ])->post('https://api.paymongo.com/v1/payments', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'source' => [
                            'id' => $sourceId,
                            'type' => 'source',
                        ],
                        'currency' => 'PHP',
                    ],
                ],
            ]);

            $paymentData = $paymentResponse->json('data');
            $paid = $paymentResponse->successful()
                && ($paymentData['attributes']['status'] ?? null) === 'paid';

            $transaction = PaymentTransaction::where('source_id', $sourceId)->first();

            if ($transaction) {
                $transaction->update([
                    'payment_id' => $paymentData['id'] ?? null,
                    'status' => $paid ? 'paid' : 'failed',
                ]);

                if ($paid) {
                    DB::transaction(function () use ($transaction) {
                        $sale = Sale::with('saleItems')
                            ->lockForUpdate()
                            ->find($transaction->sale_id);

                        if (!$sale) {
                            return;
                        }

                        $wasAlreadySale = $sale->status === 'Sale';

                        $sale->due_amount = max(0, $sale->due_amount - $transaction->amount);
                        $sale->paid_amount = ($sale->paid_amount ?? 0) + $transaction->amount;

                        // Kapag fully paid na at hindi pa naging "Sale" dati, mag-deduct ng stock
                        if ($sale->due_amount <= 0 && !$wasAlreadySale) {
                            $sale->status = 'Sale';

                            foreach ($sale->saleItems as $item) {
                                $product = Product::lockForUpdate()->find($item->product_id);

                                if (!$product) {
                                    continue;
                                }

                                $product->product_quantity = max(0, $product->product_quantity - $item->quantity);
                                $product->save();
                            }
                        }

                        $sale->save();
                    });
                }
            }

            Log::info('PayMongo webhook processed', ['source_id' => $sourceId, 'paid' => $paid]);
        }

        return response()->json(['received' => true]);
    }
}
