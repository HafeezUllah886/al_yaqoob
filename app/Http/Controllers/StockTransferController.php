<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\Branches;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\Product_units;
use App\Models\Products;
use App\Models\stock;
use App\Models\StockTransfer;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('Transfer Stocks');
        $from = $request->start ?? firstDayOfMonth();
        $to = $request->end ?? date('Y-m-d');
        $user_branches = auth()->user()->branch_ids();
        $stockTransfers = StockTransfer::whereBetween('date', [$from, $to])->whereIn('branch_from_id', $user_branches)->orWhereIn('branch_to_id', $user_branches)->get();
        $branches = Branches::all();
        $products = Products::all();

        return view('stock.transfer.index', compact('stockTransfers', 'branches', 'from', 'to', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->fromBranch == $request->toBranch) {
            return redirect()->back()->with('error', 'From and To Branch cannot be the same');
        }

        $branchFrom = Branches::find($request->fromBranch);
        $branchTo = Branches::find($request->toBranch);
        $product = Products::find($request->product);
        $stock = getProductBranchStock($request->product, $request->fromBranch);
        $expense_categories = expenseCategories::all();
        $transporters = accounts::transporter()->get();
        $accounts = accounts::with('branch')->business()->whereIn('branch_id', [$request->fromBranch, $request->toBranch])->get();

        return view('stock.transfer.create', compact('product', 'branchFrom', 'branchTo', 'stock', 'accounts', 'expense_categories', 'transporters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();
            $unit = Product_units::find($request->unit_id);
            $pcs = $request->qty * $unit->value;

            $stockTransfer = StockTransfer::create([
                'branch_from_id' => $request->from,
                'branch_to_id' => $request->to,
                'account_id' => $request->account_id,
                'product_id' => $request->product_id,
                'unit_id' => $request->unit_id,
                'unit_value' => $unit->value,
                'pcs' => $request->qty,
                'date' => $request->date,
                'transporter_id' => $request->transporter,
                'driver' => $request->driver,
                'vehicle' => $request->vehicle,
                'fare' => $request->fare,
                'notes' => $request->notes,
                'payment_status' => $request->payment_status,
                'refID' => $ref,
                'user_id' => auth()->user()->id,
            ]);

            $branchFrom = Branches::find($request->from);
            $branchTo = Branches::find($request->to);

            createStock($request->product_id, 0, $pcs, $request->date, "Transfered to $branchTo->name:  $request->notes", $ref, $request->from);
            createStock($request->product_id, $pcs, 0, $request->date, "Transfered from $branchFrom->name:  $request->notes", $ref, $request->to);

            $notes = "Stock Transfer No. $stockTransfer->id Notes: $request->notes";
            $account = accounts::find($request->account_id);
            if ($request->fare > 0) {
                expenses::create(
                    [
                        'account_id' => $request->account_id,
                        'category_id' => 1,
                        'date' => $request->date,
                        'amount' => $request->fare,
                        'notes' => $notes,
                        'branch_id' => $account->branch_id,
                        'source' => 'Stock Transfer',
                        'refID' => $ref,
                    ]
                );
            }
            if ($request->payment_status == 'Paid') {
                $transporter = accounts::find($request->transporter);
                $payment_notes = "Payment of Stock Transfer ID $stockTransfer->id to Transporter $transporter->title Vehicle: $request->vehicle Driver: $request->driver";
                $payment_notes1 = "Payment of Stock Transfer ID $stockTransfer->id Vehicle: $request->vehicle Driver: $request->driver";
                createTransaction($request->account_id, $request->date, 0, $request->fare, $payment_notes, $ref);
                createTransaction($request->transporter, $request->date, $request->fare, $request->fare, $payment_notes1, $ref);
            } else {
                $payment_notes = "Pending Amount of Stock Transfer ID $stockTransfer->id Vehicle: $request->vehicle Driver: $request->driver";
                createTransaction($request->transporter, $request->date, 0, $request->fare, $payment_notes, $ref);
            }
            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }
            DB::commit();

            return redirect()->route('stockTransfer.index')->with('success', 'Stock Transfer Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transfer = StockTransfer::find($id);
        $expenses = expenses::where('refID', $transfer->refID)->get();

        return view('stock.transfer.view', compact('transfer', 'expenses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransfer $stockTransfer)
    {
        $this->authorize('Transfer Stocks');
        $branchFrom = Branches::find($stockTransfer->branch_from_id);
        $branchTo = Branches::find($stockTransfer->branch_to_id);
        $product = Products::find($stockTransfer->product_id);
        $stock = getProductBranchStock($stockTransfer->product_id, $stockTransfer->branch_from_id) + ($stockTransfer->pcs * $stockTransfer->unit_value);
        $expense_categories = expenseCategories::all();
        $transporters = accounts::transporter()->get();
        $accounts = accounts::with('branch')->business()->whereIn('branch_id', [$stockTransfer->branch_from_id, $stockTransfer->branch_to_id])->get();

        return view('stock.transfer.edit', compact('stockTransfer', 'product', 'branchFrom', 'branchTo', 'stock', 'accounts', 'expense_categories', 'transporters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransfer $stockTransfer)
    {
        $this->authorize('Transfer Stocks');
        try {
            DB::beginTransaction();
            $ref = $stockTransfer->refID;

            // Delete existing records related to this transfer
            stock::where('refID', $ref)->delete();
            expenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();

            $unit = Product_units::find($request->unit_id);
            $pcs = $request->qty * $unit->value;

            $stockTransfer->update([
                'branch_from_id' => $request->from,
                'branch_to_id' => $request->to,
                'account_id' => $request->account_id,
                'product_id' => $request->product_id,
                'unit_id' => $request->unit_id,
                'unit_value' => $unit->value,
                'pcs' => $request->qty,
                'date' => $request->date,
                'transporter_id' => $request->transporter,
                'driver' => $request->driver,
                'vehicle' => $request->vehicle,
                'fare' => $request->fare,
                'notes' => $request->notes,
                'payment_status' => $request->payment_status,
                'user_id' => auth()->user()->id,
            ]);

            $branchFrom = Branches::find($request->from);
            $branchTo = Branches::find($request->to);

            createStock($request->product_id, 0, $pcs, $request->date, "Transfered to $branchTo->name:  $request->notes", $ref, $request->from);
            createStock($request->product_id, $pcs, 0, $request->date, "Transfered from $branchFrom->name:  $request->notes", $ref, $request->to);

            $notes = "Stock Transfer No. $stockTransfer->id Notes: $request->notes";
            $account = accounts::find($request->account_id);
            if ($request->fare > 0) {
                expenses::create([
                    'account_id' => $request->account_id,
                    'category_id' => 1,
                    'date' => $request->date,
                    'amount' => $request->fare,
                    'notes' => $notes,
                    'branch_id' => $account->branch_id,
                    'source' => 'Stock Transfer',
                    'refID' => $ref,
                ]);
            }

            if ($request->payment_status == 'Paid') {
                $transporter = accounts::find($request->transporter);
                $payment_notes = "Payment of Stock Transfer ID $stockTransfer->id to Transporter $transporter->title Vehicle: $request->vehicle Driver: $request->driver";
                $payment_notes1 = "Payment of Stock Transfer ID $stockTransfer->id Vehicle: $request->vehicle Driver: $request->driver";
                createTransaction($request->account_id, $request->date, 0, $request->fare, $payment_notes, $ref);
                createTransaction($request->transporter, $request->date, $request->fare, $request->fare, $payment_notes1, $ref);
            } else {
                $payment_notes = "Pending Amount of Stock Transfer ID $stockTransfer->id Vehicle: $request->vehicle Driver: $request->driver";
                createTransaction($request->transporter, $request->date, 0, $request->fare, $payment_notes, $ref);
            }

            if ($request->has('file')) {
                deleteAttachment($ref);
                createAttachment($request->file('file'), $ref);
            }

            DB::commit();

            return redirect()->route('stockTransfer.index')->with('success', 'Stock Transfer Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        $this->authorize('Delete Transfer Stocks');
        try {
            DB::beginTransaction();
            StockTransfer::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();
            expenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('stockTransfer.index')->with('success', 'Stock Transfer Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('stockTransfer.index')->with('error', $e->getMessage());
        }
    }
}
