<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $branch_id = $request->branch_id ?? "All";
        return view('dashboard.index', compact('from', 'to', 'branch_id'));
    }
}
