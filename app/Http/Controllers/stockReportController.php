<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\products;
use Illuminate\Http\Request;

class stockReportController extends Controller
{
    public function index()
    {
        $products = products::all();

        foreach($products as $product)
        {
            $product->stock = getStock($product->id);
            $product->value = productStockValue($product->id);
        }

        return view('reports.stock_report.details', compact('products'));
    }
}
