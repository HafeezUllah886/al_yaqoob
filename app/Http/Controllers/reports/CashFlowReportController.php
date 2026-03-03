<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\Branches;
use App\Models\transactions;
use Illuminate\Http\Request;

class CashFlowReportController extends Controller
{
    public function index()
    {
        $this->authorize('Daily Cash Flow Report');
        return view('reports.cash_flow.index');
    }

    public function details(Request $request)
    {
        $this->authorize('Daily Cash Flow Report');
        $branch_id = $request->branch;
        $date = $request->date;
        $category = $request->category;

         $branch_ids = [];
        if($branch_id == 'All'){
            $branch_ids = Auth()->user()->branch_ids();
            $branch_name = 'All Branches';
        }else{
            $branch_ids = [$branch_id];
            $branch_name = Branches::find($branch_id)->name;
        }
        $accounts = accounts::whereIn('branch_id', $branch_ids)->business();
        if($category != 'All'){
            $accounts->where('category', $category);
        }
        $accounts = $accounts->pluck('id')->toArray();
        $transactions = transactions::whereIn('account_id', $accounts)->get();
        $opening_balance = $transactions->where('date', '<', $date)->sum('cr') - $transactions->where('date', '<', $date)->sum('db');
        $closing_balance = $transactions->where('date', '<=', $date)->sum('cr') - $transactions->where('date', '<=', $date)->sum('db');

        $cash_in = $transactions->where('date', $date)->where('cr', '>', 0);
        $cash_out = $transactions->where('date', $date)->where('db', '>', 0);
     
        
        return view('reports.cash_flow.details', compact('opening_balance', 'closing_balance', 'branch_name', 'date', 'cash_in', 'cash_out', 'category'));
    }
}
