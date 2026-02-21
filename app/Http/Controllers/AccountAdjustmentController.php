<?php

namespace App\Http\Controllers;

use App\Models\AccountAdjustment;
use App\Models\accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trans = AccountAdjustment::currentBranches()->orderBy('id', 'desc')->get();
        $user_branches = auth()->user()->branch_ids();
        $accounts = accounts::whereIn('branch_id', $user_branches)->get();

        return view('finance.adjustments.index', compact('trans', 'accounts'));
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
            $account = accounts::findOrFail($request->accountID);

            AccountAdjustment::create(
                [
                    'account_id' => $request->accountID,
                    'branch_id' => $account->branch_id,
                    'user_id' => auth()->id(),
                    'date' => $request->date,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            if ($request->type == 'Credit') {
                createTransaction($request->accountID, $request->date, $request->amount, 0, 'Credit: '.$request->notes, $ref, 'Credit');
            } else {
                createTransaction($request->accountID, $request->date, 0, $request->amount, 'Debit: '.$request->notes, $ref, 'Debit');
            }
            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }

            DB::commit();

            return back()->with('success', 'Account Adjustment Created');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try {
            DB::beginTransaction();
            AccountAdjustment::where('refID', $ref)->delete();
            \App\Models\transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('account_adjustment.index')->with('success', 'Account Adjustment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('account_adjustment.index')->with('error', $e->getMessage());
        }
    }
}
