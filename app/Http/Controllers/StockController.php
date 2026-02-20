<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Product_units;
use App\Models\products;
use App\Models\stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = products::all();
        $user_branches = auth()->user()->branch_ids();

        foreach ($products as $product) {
            $product->stock = stock::where('product_id', $product->id)->whereIn('branch_id', $user_branches)->sum('cr') - stock::where('product_id', $product->id)->whereIn('branch_id', $user_branches)->sum('db');
            $first_unit = $product->units()->first();
            $product->stock = $product->stock / $first_unit->value;
            $product->unit = $first_unit->unit_name;
        }

        return view('stock.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $from = $request->from;
        $to = $request->to;
        $unit = $request->unit;
        $branch = $request->branch;
        $product = products::find($id);

        if ($branch == 'all') {
            $user_branches = auth()->user()->branch_ids();
            $stocks = stock::where('product_id', $id)->whereBetween('date', [$from, $to])->whereIn('branch_id', $user_branches)->get();

            $pre_cr = stock::where('product_id', $id)->whereDate('date', '<', $from)->whereIn('branch_id', $user_branches)->sum('cr');
            $pre_db = stock::where('product_id', $id)->whereDate('date', '<', $from)->whereIn('branch_id', $user_branches)->sum('db');

            $cur_cr = stock::where('product_id', $id)->sum('cr');
            $cur_db = stock::where('product_id', $id)->sum('db');
        } else {

            $stocks = stock::where('product_id', $id)->whereBetween('date', [$from, $to])->where('branch_id', $branch)->get();

            $pre_cr = stock::where('product_id', $id)->whereDate('date', '<', $from)->where('branch_id', $branch)->sum('cr');
            $pre_db = stock::where('product_id', $id)->whereDate('date', '<', $from)->where('branch_id', $branch)->sum('db');

            $cur_cr = stock::where('product_id', $id)->where('branch_id', $branch)->sum('cr');
            $cur_db = stock::where('product_id', $id)->where('branch_id', $branch)->sum('db');
            $branch = Branches::find($branch)->name;
        }

        $pre_balance = $pre_cr - $pre_db;
        $cur_balance = $cur_cr - $cur_db;

        $unit = Product_units::find($unit);

        return view('stock.details', compact('product', 'pre_balance', 'cur_balance', 'stocks', 'from', 'to', 'unit', 'branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stock $stock)
    {
        //
    }
}
