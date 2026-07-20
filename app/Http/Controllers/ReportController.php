<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReturnPurchase;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleReturn;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $sales = Sale::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15, ['*'], 'sales_page');

        $purchases = Purchase::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15, ['*'], 'purchases_page');

        $saleReturns = SaleReturn::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15, ['*'], 'sale_returns_page');

        $purchaseReturns = ReturnPurchase::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15, ['*'], 'purchase_returns_page');

        $totalSales = Sale::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->sum('grand_total');

        $totalPurchases = Purchase::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->sum('grand_total');

        $totalSaleReturns = SaleReturn::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->sum('grand_total');

        $totalPurchaseReturns = ReturnPurchase::query()
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->sum('grand_total');

        return view('admin.reports.report', compact(
            'sales', 'purchases', 'saleReturns', 'purchaseReturns',
            'totalSales', 'totalPurchases', 'totalSaleReturns', 'totalPurchaseReturns'
        ));
    }

    public function exportSales(Request $request)
    {
        $sales = Sale::with('customer')
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->get();

        return $this->downloadCsv('sales_report.csv', [
            ['Invoice No.', 'Customer', 'Date', 'Payment Method', 'Status', 'Amount'],
        ], $sales, function ($sale) {
            return [
                '#' . $sale->id,
                optional($sale->customer)->customer_name,
                optional($sale->created_at)->format('Y-m-d'),
                $sale->payment_method ?? 'Cash',
                $sale->status,
                $sale->grand_total,
            ];
        });
    }

    public function exportPurchase(Request $request)
    {
        $purchases = Purchase::query()
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->get();

        return $this->downloadCsv('purchase_report.csv', [
            ['Reference No.', 'Supplier', 'Date', 'Payment Method', 'Status', 'Amount'],
        ], $purchases, function ($purchase) {
            return [
                $purchase->reference_no ?? $purchase->id,
                $purchase->supplier_name ?? optional($purchase->supplier)->name,
                optional($purchase->created_at)->format('Y-m-d'),
                $purchase->payment_method,
                $purchase->status,
                $purchase->amount ?? $purchase->grand_total,
            ];
        });
    }

    public function exportSaleReturn(Request $request)
    {
        $returns = SaleReturn::query()
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->get();

        return $this->downloadCsv('sale_return_report.csv', [
            ['Return No.', 'Customer', 'Original Invoice', 'Date', 'Reason', 'Amount'],
        ], $returns, function ($return) {
            return [
                $return->return_no ?? $return->id,
                $return->customer_name ?? optional($return->customer)->name,
                $return->sale_invoice_no ?? $return->sale_id,
                optional($return->created_at)->format('Y-m-d'),
                $return->reason,
                $return->amount ?? $return->grand_total,
            ];
        });
    }

    public function exportPurchaseReturn(Request $request)
    {
        $returns = ReturnPurchase::query()
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->get();

        return $this->downloadCsv('purchase_return_report.csv', [
            ['Return No.', 'Supplier', 'Original Reference', 'Date', 'Reason', 'Amount'],
        ], $returns, function ($return) {
            return [
                $return->return_no ?? $return->id,
                $return->supplier_name ?? optional($return->supplier)->name,
                $return->purchase_reference_no ?? $return->purchase_id,
                optional($return->created_at)->format('Y-m-d'),
                $return->reason,
                $return->amount ?? $return->grand_total,
            ];
        });
    }


    private function downloadCsv(string $filename, array $headerRows, $rows, callable $mapRow)
    {
        return response()->streamDownload(function () use ($headerRows, $rows, $mapRow) {
            $handle = fopen('php://output', 'w');

            foreach ($headerRows as $header) {
                fputcsv($handle, $header);
            }

            foreach ($rows as $row) {
                fputcsv($handle, $mapRow($row));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
