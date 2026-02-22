<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\Branches;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\Product_units;
use App\Models\Products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\stock;
use App\Models\transactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\createStock;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? now()->toDateString();
        $end = $request->end ?? now()->toDateString();
        $branch_id = $request->branch_id ?? 'All';

        $purchases = purchase::whereBetween('date', [$start, $end])->orderby('id', 'desc');
        if ($branch_id != 'All') {
            $purchases = $purchases->where('branch_id', $branch_id);
        } else {
            $purchases = $purchases->currentBranches();
        }
        $purchases = $purchases->get();

        return view('purchase.index', compact('purchases', 'start', 'end', 'branch_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Products::orderby('name', 'asc')->get();

        $branch = Branches::find($request->branch_id);
        $expense_categories = expenseCategories::all();
        $vendors = accounts::vendor()->where('branch_id', $branch->id)->get();
        $accounts = accounts::business()->where('branch_id', $branch->id)->get();

        return view('purchase.create', compact('products', 'vendors', 'branch', 'expense_categories', 'accounts'));
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
            $purchase = purchase::create(
                [
                    'vendor_id' => $request->vendorID,
                    'branch_id' => $request->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'total' => 0,
                    'inv' => $request->inv,
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

                purchase_details::create(
                    [
                        'purchase_id' => $purchase->id,
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
                $qty = $qty * $unit->value;
                createStock($id, $qty, 0, $request->date, 'Purchased in Inv No. '.$request->inv.' Notes: '.$request->notes, $ref, $request->branch_id);
            }

            $purchase->update(
                [
                    'total' => $total,
                ]
            );

            createTransaction($request->vendorID, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id", $ref);

            $categories = $request->category;
            if ($categories) {
                foreach ($categories as $key => $category) {
                    expenses::create(
                        [
                            'account_id' => $request->expense_account[$key],
                            'category_id' => $category,
                            'date' => $request->date,
                            'amount' => $request->expense_amount[$key],
                            'notes' => $request->expense_notes[$key],
                            'branch_id' => $request->branch_id,
                            'source' => 'Purchase',
                            'refID' => $ref,
                        ]
                    );

                    $notes = $request->expense_notes[$key];

                    createTransaction($request->expense_account[$key], $request->date, 0, $request->expense_amount[$key], "Expense of Purchase No. $purchase->id Notes: $notes", $ref);
                }
            }
            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }
            DB::commit();

            return back()->with('success', 'Purchase Created');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase $purchase)
    {
        $products = Products::orderby('name', 'asc')->get();
        $vendors = accounts::vendor()->get();
        $branch = Branches::find($purchase->branch_id);
        $expense_categories = expenseCategories::all();
        $accounts = accounts::business()->where('branch_id', $branch->id)->get();
        $purchase_expenses = expenses::where('refID', $purchase->refID)->get();

        return view('purchase.edit', compact('products', 'vendors', 'branch', 'expense_categories', 'accounts', 'purchase', 'purchase_expenses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = $purchase->refID;

            // Delete old details, stock, expenses and transactions
            purchase_details::where('purchase_id', $purchase->id)->delete();
            stock::where('refID', $ref)->delete();
            expenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();

            $purchase->update(
                [
                    'vendor_id' => $request->vendorID,
                    'branch_id' => $request->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'inv' => $request->inv,
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

                purchase_details::create(
                    [
                        'purchase_id' => $purchase->id,
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
                createStock($id, $qty_with_unit, 0, $request->date, 'Updated in Inv No. '.$request->inv.' Notes: '.$request->notes, $ref, $request->branch_id);
            }

            $purchase->update(
                [
                    'total' => $total,
                ]
            );

            createTransaction($request->vendorID, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id", $ref);

            $categories = $request->category;
            if ($categories) {
                foreach ($categories as $key => $category) {
                    expenses::create(
                        [
                            'account_id' => $request->expense_account[$key],
                            'category_id' => $category,
                            'date' => $request->date,
                            'amount' => $request->expense_amount[$key],
                            'notes' => $request->expense_notes[$key],
                            'branch_id' => $request->branch_id,
                            'source' => 'Purchase',
                            'refID' => $ref,
                        ]
                    );

                    $notes = $request->expense_notes[$key];
                    createTransaction($request->expense_account[$key], $request->date, 0, $request->expense_amount[$key], "Expense of Purchase No. $purchase->id Notes: $notes", $ref);
                }
            }

            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }

            DB::commit();

            return redirect()->route('purchase.index')->with('success', 'Purchase Updated');

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
            $purchase = purchase::find($id);
            foreach ($purchase->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            expenses::where('refID', $purchase->refID)->delete();
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('success', 'Purchase Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = Products::with('units')->find($id);

        return $product;
    }
}
