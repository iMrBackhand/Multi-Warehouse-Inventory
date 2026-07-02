<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;

class DashboardController extends Controller
{

    public function index()
    {

        // default monthly data
        $monthlyTotals = array_fill(0,12,0);

        $purchases = Purchase::where('status','Received')->get();

        foreach($purchases as $purchase){
            $month = date('n', strtotime($purchase->purchase_date));
            $monthlyTotals[$month-1] += (float)$purchase->grand_total;
        }

        // trend ng Total Purchase (this month vs last month), base sa "Received" status
        $thisMonthTotal = Purchase::where('status','Received')
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('grand_total');

        $lastMonthDate = now()->subMonth();

        $lastMonthTotal = Purchase::where('status','Received')
            ->whereMonth('purchase_date', $lastMonthDate->month)
            ->whereYear('purchase_date', $lastMonthDate->year)
            ->sum('grand_total');

        $purchaseTrend = 0;

        if ($lastMonthTotal > 0) {
            $purchaseTrend = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
        } elseif ($thisMonthTotal > 0) {
            $purchaseTrend = 100;
        }

        // available years para sa filter
        $availableYears = Purchase::selectRaw('YEAR(purchase_date) as year')
            ->groupBy('year')
            ->orderBy('year','desc')
            ->pluck('year');

        // status table
        $statuses = Purchase::select('status')
            ->distinct()
            ->pluck('status');

        $purchasesByStatus = $statuses->map(function($status){
            return (object)[
                'status'=>$status,
                'total_count'=>Purchase::where('status',$status)->count(),
                'total_amount'=>Purchase::where('status',$status)->sum('grand_total'),
            ];
        })
        ->sortByDesc('total_count')
        ->values();

        $maxStatusCount = $purchasesByStatus->max('total_count') ?: 1;

        $statusColors = [
            'Received'=>'success',
            'Pending'=>'warning',
            'Ordered'=>'primary',
            'Partial'=>'info',
            'Cancelled'=>'danger',
        ];

        $purchasesByStatus = $purchasesByStatus->map(function($item) use($maxStatusCount,$statusColors){
            $item->percentage = round(($item->total_count / $maxStatusCount) * 100, 2);
            $item->color = $statusColors[$item->status] ?? 'secondary';
            return $item;
        });

        // Recent Purchases (pinaka-huling 6 na records)
        $recentPurchases = Purchase::with('warehouse')
            ->orderBy('purchase_date', 'desc')
            ->limit(6)
            ->get();

        // Low Stock Alert (products na <= 10 na lang ang quantity)
        $lowStockProducts = Product::where('product_quantity', '<=', 10)
            ->orderBy('product_quantity', 'asc')
            ->limit(6)
            ->get();

        $lowStockCount = Product::where('product_quantity', '<=', 10)->count();

        return view('admin.index',[

            'totalUsers'=>User::count(),
            'totalPurchase'=>Purchase::where('status','Received')->sum('grand_total'),
            'pendingPurchase'=>Purchase::where('status','Pending')->sum('grand_total'),
            'totalSuppliers'=>Supplier::count(),
            'totalBrands'=>Brand::count(),
            'totalWarehouses'=>Warehouse::count(),
            'monthlyTotals'=>$monthlyTotals,
            'purchasesByStatus'=>$purchasesByStatus,
            'availableYears'=>$availableYears,
            'purchaseTrend'=>$purchaseTrend,
            'recentPurchases'=>$recentPurchases,
            'lowStockProducts'=>$lowStockProducts,
            'lowStockCount'=>$lowStockCount,

        ]);

    }


    // AJAX chart data
    public function purchaseChart($year)
    {
        $monthly = Purchase::where('status','Received')
            ->whereYear('purchase_date',$year)
            ->selectRaw('MONTH(purchase_date) month, SUM(grand_total) total')
            ->groupBy('month')
            ->pluck('total','month');

        $data=[];

        for($i=1;$i<=12;$i++){
            $data[] = $monthly[$i] ?? 0;
        }

        return response()->json($data);
    }

    public function purchaseSummary($year)
    {
        $totalPurchase = Purchase::whereYear('purchase_date', $year)
            ->sum('grand_total');

        return response()->json([
            'totalPurchase' => number_format($totalPurchase, 2)
        ]);
    }

}
