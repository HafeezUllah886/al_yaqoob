<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\Branches;
use Illuminate\Http\Request;

class BalanceSheetReportController extends Controller
{
    public function index()
    {
        return view('reports.balance_sheet.index');
    }

    public function details(Request $request)
    {
        $account_type = $request->account_type;
        $branch_id = $request->branch;

        $branch_ids = [];
        if($branch_id == 'All'){
            $branch_ids = Auth()->user()->branch_ids();
            $branch_name = 'All Branches';
        }else{
            $branch_ids = [$branch_id];
            $branch_name = Branches::find($branch_id)->name;
        }

        $accounts = accounts::whereIn('branch_id', $branch_ids);
        if($account_type != 'All'){
            $accounts->where('type', $account_type);
        }
        $accounts = $accounts->orderBy('type', 'asc')->orderBy('title', 'asc')->get();

        foreach($accounts as $account){
            $account->balance = getAccountBalance($account->id);
        }
        
        return view('reports.balance_sheet.details', compact('accounts', 'account_type', 'branch_name'));
    }
}
