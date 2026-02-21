<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\transactions;
use App\Models\transfer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->from ?? firstDayOfMonth();
        $end = $request->to ?? now()->toDateString();
        $branch_id = $request->branch_id ?? 'All';

        $transfers = transfer::whereBetween('date', [$start, $end])->orderby('id', 'desc');
        if ($branch_id != 'All') {
            $transfers = $transfers->where('branch_id', $branch_id);
        } else {
            $transfers = $transfers->currentBranches();
        }
        $transfers = $transfers->get();
        $accounts = accounts::currentBranches()->get();

        return view('finance.transfer.index', compact('transfers', 'accounts', 'start', 'end', 'branch_id'));
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
        $request->validate(
            [
                'to_id' => 'different:from_id',
            ],
            [
                'to_id.different' => 'From and To Accounts Must be different',
            ]
        );

        try {
            DB::beginTransaction();
            $ref = getRef();
            $fromAccount = accounts::find($request->from_id);
            $toAccount = accounts::find($request->to_id);

            $transfer = transfer::create(
                [
                    'from_id' => $request->from_id,
                    'to_id' => $request->to_id,
                    'branch_id' => $fromAccount->branch_id,
                    'user_id' => auth()->user()->id,
                    'date' => $request->date,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->from_id, $request->date, 0, $request->amount, "Transfered to $toAccount->title <br> $request->notes", $ref);
            createTransaction($request->to_id, $request->date, $request->amount, 0, "Transfered from $fromAccount->title <br> $request->notes", $ref);
            DB::commit();

            return back()->with('success', 'Transfered Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(transfer $transfer) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transfer $transfer)
    {
        $accounts = accounts::whereNotIn('id', [2, 3])->get();
        session()->forget('confirmed_password');

        return view('Finance.transfer.edit', compact('transfer', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transfer $transfer)
    {
        $request->validate(
            [
                'to_id' => 'different:from_id',
            ],
            [
                'to_id.different' => 'From and To Accounts Must be different',
            ]
        );

        try {
            DB::beginTransaction();
            transactions::where('refID', $transfer->refID)->delete();
            $transfer->update(
                [
                    'from_id' => $request->from_id,
                    'to_id' => $request->to_id,
                    'date' => $request->date,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                ]
            );
            $ref = $transfer->refID;
            $fromAccount = $transfer->fromAccount->title;
            $toAccount = $transfer->toAccount->title;
            createTransaction($request->from_id, $request->date, 0, $request->amount, "Transfered to $toAccount <br> $request->notes", $ref);
            createTransaction($request->to_id, $request->date, $request->amount, 0, "Transfered from $fromAccount <br> $request->notes", $ref);
            DB::commit();
            session()->forget('confirmed_password');

            return to_route('transfers.index')->with('success', 'Transfer Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return to_route('transfers.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try {
            DB::beginTransaction();
            transfer::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('transfers.index')->with('success', 'Transfer Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('transfers.index')->with('error', $e->getMessage());
        }
    }
}
