<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\paymentReceiving;
use App\Models\payments;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to = $request->to ?? date('Y-m-t');
        $branch_id = $request->branch_id ?? 'All';

        $payments = payments::whereBetween('date', [$from, $to])->orderby('id', 'desc');
        if ($branch_id != 'All') {
            $payments = $payments->where('branch_id', $branch_id);
        } else {
            $payments = $payments->currentBranches();
        }
        $payments = $payments->get();

        $accounts = accounts::Business()->currentBranches()->get();
        $toaccounts = accounts::where('type', '!=', 'Business')->currentBranches()->get();

        return view('finance.payments.index', compact('payments', 'toaccounts', 'accounts', 'from', 'to', 'branch_id'));
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
            $account = accounts::find($request->accountID);
            $toAccount = accounts::find($request->toAccountID);

            payments::create(
                [
                    'account_id' => $request->accountID,
                    'to_account_id' => $request->toAccountID,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->amount,
                    'branch_id' => $account->branch_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, 0, $request->amount, 'Payment to '.$toAccount->title.' <br>'.$request->notes, $ref);
            createTransaction($request->toAccountID, $request->date, $request->amount, 0, 'Payment from '.$account->title.' <bt>'.$request->notes, $ref);

            if ($request->has('file')) {
                createAttachment($request->file('file'), $ref);
            }
            DB::commit();

            return back()->with('success', 'Payment Saved');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tran = payments::find($id);

        return view('finance.payments.receipt', compact('tran'));
    }

    public function edit(paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        $this->authorize('Delete Payments');
        try {
            DB::beginTransaction();
            payments::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            deleteAttachment($ref);
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('payments.index')->with('success', 'Payment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('payments.index')->with('error', $e->getMessage());
        }
    }
}
