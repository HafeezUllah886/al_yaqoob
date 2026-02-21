<?php

namespace App\Http\Controllers;

use App\Models\Product_units;
use App\Models\products;
use App\Models\stock;
use App\Models\stockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\createStock;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('Stock Adjustments');
        $adjustments = stockAdjustment::orderBy('id', 'desc')->get();
        $products = products::all();

        return view('stock.adjustment.index', compact('adjustments', 'products'));
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

        try {
            DB::beginTransaction();
            $ref = getRef();
            $unit = Product_units::findOrFail($request->unitID);
            $unit_value = $unit->value;

            stockAdjustment::create(
                [
                    'product_id' => $request->productID,
                    'date' => $request->date,
                    'type' => $request->type,
                    'qty' => $request->qty,
                    'unit_id' => $request->unitID,
                    'unit_value' => $unit_value,
                    'user_id' => auth()->id(),
                    'branch_id' => $request->branchID,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            $base_qty = $request->qty * $unit_value;

            if ($request->type == 'Stock-In') {
                createStock($request->productID, $base_qty, 0, $request->date, "Stock-In: $request->notes", $ref, $request->branchID);
            } else {
                createStock($request->productID, 0, $base_qty, $request->date, "Stock-Out: $request->notes", $ref, $request->branchID);
            }

            DB::commit();

            return back()->with('success', 'Stock Adjustment Created');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(stockAdjustment $stockAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stockAdjustment $stockAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stockAdjustment $stockAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        $this->authorize('Delete Stock Adjustments');
        try {
            DB::beginTransaction();
            stockAdjustment::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('stockAdjustments.index')->with('success', 'Stock Adjustment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('stockAdjustments.index')->with('error', $e->getMessage());
        }
    }
}
