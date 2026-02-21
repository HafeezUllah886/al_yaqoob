<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\Branches;
use App\Models\Product_units;
use App\Models\Products;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\stock;
use App\Models\transactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\createStock;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? now()->toDateString();
        $end = $request->end ?? now()->toDateString();
        $branch_id = $request->branch_id ?? 'All';

        $sales = Sale::whereBetween('date', [$start, $end])->orderby('id', 'desc');
        if ($branch_id != 'All') {
            $sales = $sales->where('branch_id', $branch_id);
        } else {
            $sales = $sales->currentBranches();
        }
        $sales = $sales->get();

        return view('sale.index', compact('sales', 'start', 'end', 'branch_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Products::orderby('name', 'asc')->get();

        $branch = Branches::find($request->branch_id);
        $customers = accounts::Customer()->where('branch_id', $request->branch_id)->get();

        return view('sale.create', compact('products', 'customers', 'branch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $sale = Sale::create(
                [
                    'customer_id' => $request->customerID,
                    'branch_id' => $request->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'total' => 0,
                    'discount' => $request->discount ?? 0,
                    'refID' => $ref,
                ]
            );

            $ids = $request->id;

            $total = 0;
            foreach ($ids as $key => $id) {
                $unit = Product_units::find($request->unit[$key]);
                $qty = $request->qty[$key];
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                SaleDetail::create(
                    [
                        'sale_id' => $sale->id,
                        'product_id' => $id,
                        'price' => $price,
                        'qty' => $qty,
                        'amount' => $amount,
                        'date' => $request->date,
                        'unit_id' => $unit->id,
                        'unit_value' => $unit->value,
                        'refID' => $ref,
                    ]
                );
                $qty_with_unit = $qty * $unit->value;
                // Stock Out: db = $qty_with_unit, cr = 0
                createStock($id, 0, $qty_with_unit, $request->date, 'Sold in Sale No. '.$sale->id.' Notes: '.$request->notes, $ref, $request->branch_id);
            }

            $net_total = $total - ($request->discount ?? 0);

            $sale->update(
                [
                    'total' => $net_total,
                ]
            );

            // Transaction for Customer: db = $net_total, cr = 0 (Customer owes us money)
            createTransaction($request->customerID, $request->date, $net_total, 0, "Pending Amount of Sale No. $sale->id", $ref);

            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }
            DB::commit();

            return back()->with('success', 'Sale Created');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sale.view', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Products::orderby('name', 'asc')->get();

        $branch = Branches::find($sale->branch_id);
        $customers = accounts::Customer()->where('branch_id', $sale->branch_id)->get();

        return view('sale.edit', compact('products', 'customers', 'branch', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = $sale->refID;

            // Delete old details, stock, and transactions
            SaleDetail::where('sale_id', $sale->id)->delete();
            stock::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();

            $sale->update(
                [
                    'customer_id' => $request->customerID,
                    'branch_id' => $request->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'discount' => $request->discount ?? 0,
                ]
            );

            $ids = $request->id;
            $total = 0;

            foreach ($ids as $key => $id) {
                $unit = Product_units::find($request->unit[$key]);
                $qty = $request->qty[$key];
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                SaleDetail::create(
                    [
                        'sale_id' => $sale->id,
                        'product_id' => $id,
                        'price' => $price,
                        'qty' => $qty,
                        'amount' => $amount,
                        'date' => $request->date,
                        'unit_id' => $unit->id,
                        'unit_value' => $unit->value,
                        'refID' => $ref,
                    ]
                );
                $qty_with_unit = $qty * $unit->value;
                // Stock Out: db = $qty_with_unit, cr = 0
                createStock($id, 0, $qty_with_unit, $request->date, 'Updated in Sale No. '.$sale->id.' Notes: '.$request->notes, $ref, $request->branch_id);
            }

            $net_total = $total - ($request->discount ?? 0);

            $sale->update(
                [
                    'total' => $net_total,
                ]
            );

            // Transaction for Customer: db = $net_total, cr = 0
            createTransaction($request->customerID, $request->date, $net_total, 0, "Pending Amount of Sale No. $sale->id", $ref);

            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }

            DB::commit();

            return redirect()->route('sale.index')->with('success', 'Sale Updated');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $sale = Sale::find($id);
            stock::where('refID', $sale->refID)->delete();
            transactions::where('refID', $sale->refID)->delete();
            $sale->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('sale.index')->with('success', 'Sale Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('sale.index')->with('error', $e->getMessage());
        }
    }
}
