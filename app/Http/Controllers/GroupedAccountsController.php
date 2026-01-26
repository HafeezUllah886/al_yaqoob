<?php

namespace App\Http\Controllers;

use App\Models\groupedAccounts;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\transactions;
use Illuminate\Http\Request;

class GroupedAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = groupedAccounts::all();
        $accounts = accounts::all();
        return view('Finance.groups.index', compact('groups', 'accounts'));
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
        $request->validate([
            'group_name' => 'required|unique:grouped_accounts,group_name',
            'account1' => 'required',
            'account2' => 'required|different:account1',
        ]);
       groupedAccounts::create([
        'group_name' => $request->group_name,
        'account1_id' => $request->account1,
        'account2_id' => $request->account2,
       ]);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id, $from, $to)
    {
        $group = groupedAccounts::find($id);
        $ids = [$group->account1_id, $group->account2_id];

        $transactions = transactions::whereIn('accountID', $ids)->whereBetween('date', [$from, $to])->get();

        $pre_cr = transactions::whereIn('accountID', $ids)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = transactions::whereIn('accountID', $ids)->whereDate('date', '<', $from)->sum('db');
        $pre_balance = $pre_cr - $pre_db;

        $cur_cr = transactions::whereIn('accountID', $ids)->sum('cr');
        $cur_db = transactions::whereIn('accountID', $ids)->sum('db');

        $cur_balance = $cur_cr - $cur_db;

        return view('Finance.groups.statment', compact('group', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(groupedAccounts $groupedAccounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, groupedAccounts $groupedAccounts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        groupedAccounts::find($id)->delete();
        session()->forget('confirmed_password');
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
    }
}
