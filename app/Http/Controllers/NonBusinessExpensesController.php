<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\NonBusinessExpenseCategories;
use App\Models\NonBusinessExpenses;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NonBusinessExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $branch_id = $request->branch_id ?? 'All';
        $this->authorize('Create Non-Business Expenses');
        $expenses = NonBusinessExpenses::whereBetween('date', [$from, $to])->orderby('id', 'desc');
        if ($branch_id != 'All') {
            $expenses = $expenses->where('branch_id', $branch_id);
        } else {
            $expenses = $expenses->currentBranches();
        }
        $expenses = $expenses->get();
        $accounts = accounts::currentBranches()->business()->get();
        $categories = NonBusinessExpenseCategories::all();

        return view('finance.non_business_expense.index', compact('expenses', 'accounts', 'categories', 'from', 'to', 'branch_id'));
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
            NonBusinessExpenses::create(
                [
                    'account_id' => $request->accountID,
                    'category_id' => $request->cat,
                    'amount' => $request->amount,
                    'branch_id' => $request->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, 0, $request->amount, 'Non-Business Expense - '.$request->notes, $ref, 'Non-Business Expense');

            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }
            DB::commit();

            return back()->with('success', 'Non-Business Expense Saved');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NonBusinessExpenses $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NonBusinessExpenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NonBusinessExpenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        $this->authorize('Delete Non-Business Expenses');
        try {
            DB::beginTransaction();
            NonBusinessExpenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            deleteAttachment($ref);
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('nonBusinessExpenses.index')->with('success', 'Non-Business Expense Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('nonBusinessExpenses.index')->with('error', $e->getMessage());
        }
    }
}
