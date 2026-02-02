<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\Branches;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\Product_units;
use App\Models\products;
use App\Models\Products as ModelsProducts;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_expense_categories;
use App\Models\purchase_expenses;
use App\Models\purchase_payments;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
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

        $purchases = purchase::whereBetween('date', [$start, $end])->orderby('id', 'desc')->get();

        return view('purchase.index', compact('purchases', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = ModelsProducts::orderby('name', 'asc')->get();
        $vendors = accounts::vendor()->get();
        $branches = Branches::currentUser()->get();
        $expense_categories = expenseCategories::all();
        $accounts = accounts::business()->get();

        return view('purchase.create', compact('products', 'vendors', 'branches', 'expense_categories', 'accounts'));
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
                    'branch_id' => $request->branchID,
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
                createStock($id, $qty, 0, $request->date, 'Purchased in Inv No. '.$request->inv.' Notes: '.$request->notes, $ref, $request->branchID);
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
                            'refID' => $ref,
                        ]
                    );

                    $notes = $request->expense_notes[$key];

                    createTransaction($request->expense_account[$key], $request->date, 0, $request->expense_amount[$key], "Expense of Purchase No. $purchase->id Notes: $notes", $ref);
                }
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
        $products = products::orderby('name', 'asc')->get();
        $units = units::all();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->get();
        $warehouses = warehouses::all();
        $expense_categories = purchase_expense_categories::all();

        return view('purchase.edit', compact('products', 'units', 'vendors', 'accounts', 'purchase', 'warehouses', 'expense_categories'));
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
            foreach ($purchase->payments as $payment) {
                transactions::where('refID', $payment->refID)->delete();
                $payment->delete();
            }
            foreach ($purchase->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            foreach ($purchase->expenses as $expense) {
                transactions::where('refID', $expense->refID)->delete();
                $expense->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [
                    'vendorID' => $request->vendorID,
                    'warehouseID' => $request->warehouseID,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'discount' => $request->discount,
                    'fright' => $request->fright,
                    'fright1' => $request->fright1,
                    'wh' => $request->whTax,
                    'inv' => $request->inv,
                ]
            );

            $ids = $request->id;

            $total = 0;
            $ref = $purchase->refID;
            dashboard();
            foreach ($ids as $key => $id) {
                $unit = units::find($request->unit[$key]);
                $qty = ($request->qty[$key] * $unit->value) + $request->bonus[$key];
                $qty1 = $request->qty[$key] * $unit->value;
                $pprice = $request->pprice[$key];
                $price = $request->price[$key];
                $wsprice = $request->wsprice[$key];
                $tp = $request->tp[$key];
                $amount = $pprice * $qty1;
                $total += $amount;

                purchase_details::create(
                    [
                        'purchaseID' => $purchase->id,
                        'productID' => $id,
                        'pprice' => $pprice,
                        'price' => $price,
                        'wsprice' => $wsprice,
                        'tp' => $tp,
                        'qty' => $qty1,
                        'gstValue' => $request->gstValue[$key],
                        'amount' => $amount,
                        'date' => $request->date,
                        'bonus' => $request->bonus[$key],
                        'unitID' => $unit->id,
                        'unitValue' => $unit->value,
                        'refID' => $ref,
                    ]
                );
                createStock($id, $qty, 0, $request->date, 'Purchased', $ref, $request->warehouseID);

                $product = products::find($id);
                $product->update(
                    [
                        'pprice' => $pprice,
                        'price' => $price,
                        'wsprice' => $wsprice,
                    ]
                );
            }

            $whTax = $total * $request->whTax / 100;

            $net = ($total + $whTax + $request->fright1) - ($request->discount + $request->fright);

            $purchase->update(
                [

                    'whValue' => $whTax,
                    'net' => $net,
                ]
            );

            if ($request->status == 'paid') {
                purchase_payments::create(
                    [
                        'purchaseID' => $purchase->id,
                        'accountID' => $request->accountID,
                        'date' => $request->date,
                        'amount' => $net,
                        'notes' => 'Full Paid',
                        'refID' => $ref,
                    ]
                );

                createTransaction($request->accountID, $request->date, 0, $net, "Payment of Purchase No. $purchase->id", $ref);
            } else {
                createTransaction($request->vendorID, $request->date, 0, $net, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            $categories = $request->category;
            if ($categories) {
                foreach ($categories as $key => $category) {
                    purchase_expenses::create(
                        [
                            'purchaseID' => $purchase->id,
                            'accountID' => $request->expense_account[$key],
                            'cat' => $category,
                            'date' => $request->date,
                            'amount' => $request->expense_amount[$key],
                            'notes' => $request->expense_notes[$key],
                            'refID' => $ref,
                        ]
                    );

                    $notes = $request->expense_notes[$key];

                    createTransaction($request->expense_account[$key], $request->date, 0, $request->expense_amount[$key], "Expense of Purchase No. $purchase->id Notes: $notes", $ref);
                }
            }
            DB::commit();

            return back()->with('success', 'Purchase Updated');
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
        $product = products::with('units')->find($id);

        return $product;
    }
}
