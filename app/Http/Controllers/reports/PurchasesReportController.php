<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\Products;
use App\Models\purchase_details;
use Symfony\Component\HttpFoundation\Request;

class PurchasesReportController extends Controller
{
    public function index()
    {
        $vendors = accounts::vendor()->get();

        return view('reports.purchases.index', compact('vendors'));
    }

    public function details(Request $request)
    {
        $vendor = $request->vendor ?? 'all';
        $branch = $request->branch ?? 'all';
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($vendor == 'all') {
            $vendors = accounts::vendor()->pluck('id')->toArray();
        } else {
            $vendors = (array) $vendor;
        }

        $products = Products::active()->get();

        $data = [];

        foreach ($vendors as $v_id) {
            if ($branch == 'all') {
                $vendor_purchases = purchase_details::whereHas('purchases', function ($query) use ($v_id) {
                    $query->where('vendor_id', $v_id);
                })->whereBetween('date', [$start_date, $end_date])
                    ->selectRaw('product_id, sum(qty * unit_value) as total_qty, sum(amount) as total_amount')
                    ->groupBy('product_id')
                    ->get()
                    ->keyBy('product_id');
            } else {
                $vendor_purchases = purchase_details::whereHas('purchases', function ($query) use ($v_id, $branch) {
                    $query->where('vendor_id', $v_id)->where('branch_id', $branch);
                })->whereBetween('date', [$start_date, $end_date])
                    ->selectRaw('product_id, sum(qty * unit_value) as total_qty, sum(amount) as total_amount')
                    ->groupBy('product_id')
                    ->get()
                    ->keyBy('product_id');
            }

            if ($vendor_purchases->isEmpty()) {
                continue;
            }

            foreach ($products as $product) {
                if ($vendor_purchases->get($product->id)) {
                    $data[$v_id]['products'][] = $vendor_purchases->get($product->id);
                }
            }
            $data[$v_id]['details'] = accounts::with('branch')->find($v_id);
        }

        return view('reports.purchases.details', compact('data', 'vendor', 'start_date', 'end_date', 'branch'));
    }
}
