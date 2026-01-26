<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\sale_details;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customerWiseSaleReportController extends Controller
{
    public function index()
    {
        $customers = accounts::customer()->get();
        return view('reports.customer_wise_sale_report.index', compact('customers'));
    }

    public function data(Request $request)
    {
       
        $from = $request->from;
        $to = $request->to;

        $customers = accounts::customer();

        if($request->customer)
        {
            $customers = $customers->whereIn('id', $request->customer);
        }

        $customers = $customers->get();
        $data= [];

        foreach ($customers as $customer) {
            $sales = sales::where('customerID', $customer->id)
                ->whereBetween('date', [$from, $to])
                ->pluck('id');

                $saleDetails = sale_details::with('product')->whereIn('salesID', $sales)->get();

                $products = [];

                foreach($saleDetails as $product)
                {
                    $productID = $product->productID;
                    $productName = $product->product->name;
                    $productCategory = $product->product->category->name;
                    $productQty = $product->qty;
                    $productAmount = $product->amount;
                
                    if (!isset($products[$productID])) {
                        $products[$productID] = [
                            'name' => $productName,
                            'category' => $productCategory,
                            'sold_qty' => 0,
                            'total_amount' => 0,
                        ];
                    }
                
                    $products[$productID]['sold_qty'] += $productQty;
                    $products[$productID]['total_amount'] += $productAmount;
                }
                
                // Calculate avg_price for each product
                foreach ($products as $pid => $prod) {
                    $sold_qty = $prod['sold_qty'];
                    $total_amount = $prod['total_amount'];
                    $products[$pid]['avg_price'] = $sold_qty > 0 ? round($total_amount / $sold_qty, 2) : 0;
                }

                $customer->products = $products;
            $data[] = ['customer' => $customer, 'products' => $products];
        }

    return view('reports.customer_wise_sale_report.details', compact('data', 'from', 'to'));
    }
}
