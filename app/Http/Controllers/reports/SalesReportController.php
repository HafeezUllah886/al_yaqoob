<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\Products;
use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\product_units;
use Symfony\Component\HttpFoundation\Request;

class SalesReportController extends Controller
{
    public function index()
    {
        return view('reports.sales.index');
    }

    public function details(Request $request)
    {
        $branch = $request->branch;
        $customer = $request->customer ?? 'all';
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($branch == 'all') {
            if ($customer == 'all') {
                $branch_ids = auth()->user()->branch_ids();
                $customers = accounts::customer()->whereIn('branch_id', $branch_ids)->pluck('id')->toArray();
            } else {
                $customers = $customer;
            }
        } elseif ($branch != 'all') {
            if ($customer == 'all') {

                $customers = accounts::customer()->where('branch_id', $branch)->pluck('id')->toArray();
            } else {
                $customers = $customer;
            }
        }

        $products = Products::active()->get();

        $data = [];

        foreach ($customers as $customer) {
            $customer_sales = SaleDetail::whereHas('sale', function ($query) use ($customer) {
                $query->where('customer_id', $customer);
            })->whereBetween('date', [$start_date, $end_date])
                ->selectRaw('product_id, sum(qty * unit_value) as total_qty, sum(amount) as total_amount')
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');
                if($customer_sales->isEmpty()){
                    continue;
                }
            foreach ($products as $product) {
                if($customer_sales->get($product->id)){
                    $data[$customer]['products'][] = $customer_sales->get($product->id);
                }
            }
            $discounts = sale::whereBetween('date', [$start_date, $end_date])->where('customer_id', $customer)->sum('discount');
            $data[$customer]['discounts'] = $discounts;
            $data[$customer]['details'] = accounts::with('branch')->find($customer);
        }

        return view('reports.sales.details', compact('data', 'branch', 'customer', 'start_date', 'end_date'));
    }
}
