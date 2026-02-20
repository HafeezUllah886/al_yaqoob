<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\branches;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\Product_units;
use App\Models\products;
use App\Models\RouteExpenses;
use App\Models\stock;
use App\Models\StockTransfer;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\createStock;
use function App\Helpers\getProductBranchStock;

class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->start ?? firstDayOfMonth();
        $to = $request->end ?? date('Y-m-d');
        $user_branches = auth()->user()->branch_ids();
        $stockTransfers = StockTransfer::whereBetween('date', [$from, $to])->whereIn('branch_from_id', $user_branches)->orWhereIn('branch_to_id', $user_branches)->get();
        $branches = branches::all();
        $products = products::all();

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

        $branchFrom = branches::find($request->fromBranch);
        $branchTo = branches::find($request->toBranch);
        $product = products::find($request->product);
        $stock = getProductBranchStock($request->product, $request->fromBranch);
        $expense_categories = expenseCategories::all();
        $accounts = accounts::with('branch')->business()->whereIn('branch_id', [$request->fromBranch, $request->toBranch])->get();

        return view('stock.transfer.create', compact('product', 'branchFrom', 'branchTo', 'stock', 'accounts', 'expense_categories'));
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
                'product_id' => $request->product_id,
                'unit_id' => $request->unit_id,
                'unit_value' => $unit->value,
                'pcs' => $request->qty,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
                'user_id' => auth()->user()->id,
            ]);

            $branchFrom = branches::find($request->from);
            $branchTo = branches::find($request->to);

            createStock($request->product_id, 0, $pcs, $request->date, "Transfered to $branchTo->name:  $request->notes", $ref, $request->from);
            createStock($request->product_id, $pcs, 0, $request->date, "Transfered from $branchFrom->name:  $request->notes", $ref, $request->to);

            $categories = $request->category;
            if ($categories) {
                foreach ($categories as $key => $category) {
                    $account = accounts::find($request->expense_account[$key]);
                    expenses::create(
                        [
                            'account_id' => $request->expense_account[$key],
                            'category_id' => $category,
                            'date' => $request->date,
                            'amount' => $request->expense_amount[$key],
                            'notes' => $request->expense_notes[$key],
                            'branch_id' => $account->branch_id,
                            'source' => 'Stock Transfer',
                            'refID' => $ref,
                        ]
                    );

                    $notes = $request->expense_notes[$key];

                    createTransaction($request->expense_account[$key], $request->date, 0, $request->expense_amount[$key], "Expense of Stock Transfer No. $stockTransfer->id Notes: $notes", $ref);
                }
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
        $stockTransfer = StockTransfer::with('details')->find($id);

        return view('stock.transfer.details', compact('stockTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        try {
            DB::beginTransaction();
            StockTransfer::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();
            RouteExpenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('stockTransfers.index')->with('success', 'Stock Transfer Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('stockTransfers.index')->with('error', $e->getMessage());
        }
    }
}
