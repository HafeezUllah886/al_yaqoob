<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\NonBusinessExpenses;
use App\Models\purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $branch_id = $request->branch_id ?? "All";

         $branch_ids = [];
        if($branch_id == 'All'){
            $branch_ids = Auth()->user()->branch_ids();
        }else{
            $branch_ids = [$branch_id];
        }

        $sales = Sale::whereIn('branch_id', $branch_ids)->whereBetween('date', [$from, $to])->sum('total');
        $purchases = purchase::whereIn('branch_id', $branch_ids)->whereBetween('date', [$from, $to])->sum('total');
        $expenses = expenses::whereIn('branch_id', $branch_ids)->whereBetween('date', [$from, $to])->sum('amount');
        $non_business_expenses = NonBusinessExpenses::whereIn('branch_id', $branch_ids)->whereBetween('date', [$from, $to])->sum('amount');
        $stock_value = getBranchStockValue($branch_id);
        $customer_balance = branchAccountCategoryBalance($branch_id, 'Customer');
        $vendor_balance = branchAccountCategoryBalance($branch_id, 'Vendor');
        $business_balance = branchAccountCategoryBalance($branch_id, 'Business');
        $transporter_balance = branchAccountCategoryBalance($branch_id, 'Transporter');
        $branch_balance = ($customer_balance + $business_balance + $stock_value) - ($vendor_balance + $transporter_balance);
      

        return view('dashboard.index', compact('from', 'to', 'branch_id', 'sales', 'purchases', 'expenses', 'non_business_expenses', 'stock_value', 'customer_balance', 'vendor_balance', 'business_balance', 'transporter_balance', 'branch_balance'));
    }
}
