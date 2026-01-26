<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\sale_details;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customerWiseSaleReportController_2 extends Controller
{
    public function index()
    {
        $customers = accounts::customer()->get();
        return view('reports.customer_wise_sale_report2.index', compact('customers'));
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
                    $productPrice = $product->price;
                    $productQty = $product->qty;
                    $productAmount = $product->amount;
                    $productDate = $product->date;
                
                   $products[] = [
                        'name' => $productName,
                        'category' => $productCategory,
                        'sold_qty' => $productQty,
                        'total_amount' => $productAmount,
                        'price' => $productPrice,
                        'date' => $productDate,
                        ];
                }
            

                $customer->products = $products;
            $data[] = ['customer' => $customer, 'products' => $products];
        }

    return view('reports.customer_wise_sale_report2.details', compact('data', 'from', 'to'));
    }
}
