<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\categories;
use App\Models\products;
use App\Models\sale_details;
use Illuminate\Http\Request;

class productWiseSaleReportController extends Controller
{
    public function index()
    {
        $products = products::all();
        $categories = categories::all();
        return view('reports.product_wise_sale_report.index', compact('products', 'categories'));
    }

    public function data(Request $request)
    {

        $products = products::query();
        $from = $request->from;
        $to = $request->to;
        
        
        if($request->products)
        {
            $products->whereIn('id', $request->products);
        }
        if($request->categories)
        {
            $products->whereIn('catID', $request->categories);
        }
        $products = $products->get();

       

        foreach($products as $product)
        {
            $sales = sale_details::where('productID', $product->id)->whereBetween('date', [$from, $to]);

            $product->sold = $sales->sum('qty');
            $product->amount = $sales->sum('amount');
            if($product->amount > 0)
            {
                $product->avg_price = $product->amount / $product->sold;
            }
            else
            {
                $product->avg_price = 0;
            }
           
        }
        
       
        return view('reports.product_wise_sale_report.details', compact('products', 'from', 'to'));
    }
}
