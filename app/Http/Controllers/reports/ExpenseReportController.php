<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\expenseCategories;
use App\Models\expenses;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index()
    {
        $categories = expenseCategories::whereNull('parent_id')->get();
        $subcategories = expenseCategories::whereNotNull('parent_id')->get();
        $branches = auth()->user()->branches;

        return view('reports.expenses.index', compact('categories', 'subcategories', 'branches'));
    }

    public function details(Request $request)
    {
        $branch = $request->branch;
        $category_ids = $request->categories ?? [];
        $subcategory_ids = $request->subcategories ?? [];
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = expenses::whereBetween('date', [$start_date, $end_date]);

        if ($branch != 'all') {
            $query->where('branch_id', $branch);
        } else {
            $query->whereIn('branch_id', auth()->user()->branch_ids());
        }

        $all_category_ids = $subcategory_ids;

        if (!empty($category_ids)) {
            $child_categories = expenseCategories::whereIn('parent_id', $category_ids)->pluck('id')->toArray();
            $all_category_ids = array_unique(array_merge($all_category_ids, $category_ids, $child_categories));
        }

        if (!empty($all_category_ids)) {
            $query->whereIn('category_id', $all_category_ids);
        }

        $expenses = $query->with(['category', 'branch', 'account'])->get();
        $total_amount = $expenses->sum('amount');

        return view('reports.expenses.details', compact('expenses', 'total_amount', 'branch', 'start_date', 'end_date'));
    }
}
