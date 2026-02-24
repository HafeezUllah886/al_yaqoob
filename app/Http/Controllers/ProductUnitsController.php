<?php

namespace App\Http\Controllers;

use App\Models\Product_units;
use App\Models\Products;
use App\Models\purchase_details;
use App\Models\SaleDetail;
use App\Models\stockAdjustment;
use App\Models\StockTransfer;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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

        $unit = Unit::find($request->unit);
        Product_units::create(
            [
                'product_id' => $request->product_id,
                'unit_name' => $unit->name,
                'value' => $unit->value,
            ]
        );

        return redirect()->back()->with('success', 'Unit Added');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product_units = Product_units::where('product_id', $id)->get();
        $product = Products::find($id);

        $units = Unit::all();

        return view('products.product_units', compact('product_units', 'product', 'units'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Product_units::where('id', $id)->update(
            [
                'unit_name' => $request->unit_name,
                'value' => $request->value,
            ]
        );

        return redirect()->back()->with('success', 'Unit Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            $product_unit = Product_units::find($id);
            $check_sale = SaleDetail::where('unit_id', $product_unit->id)->first();
            $check_purchase = purchase_details::where('unit_id', $product_unit->id)->first();
            $check_stock_adj = stockAdjustment::where('unit_id', $product_unit->id)->first();
            $check_stock_transfer = StockTransfer::where('unit_id', $product_unit->id)->first();
            if ($check_sale || $check_purchase || $check_stock_adj || $check_stock_transfer) {
                return to_route('product.index')->with('error', 'Unit Already Used');
            }
            $product_unit->delete();

            return to_route('product.index')->with('success', 'Unit Deleted');
        } catch (\Exception $e) {
            return to_route('product.index')->with('error', 'Unit Already Used '.$e->getMessage());
        }
    }
}
