<?php

namespace App\Http\Controllers;

use App\Models\NonBusinessExpenseCategories;
use Illuminate\Http\Request;

class NonBusinessExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('Create Non-Business Expense Categories');
        $cats = NonBusinessExpenseCategories::with('parent')->orderBy('parent_id', 'asc')->orderBy('name', 'asc')->get();

        return view('finance.non_business_expense.categories', compact('cats'));
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
        NonBusinessExpenseCategories::create($request->all());

        return back()->with('msg', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(NonBusinessExpenseCategories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NonBusinessExpenseCategories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Edit Non-Business Expense Categories');
        NonBusinessExpenseCategories::findOrFail($id)->update($request->all());

        return back()->with('msg', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NonBusinessExpenseCategories $categories)
    {
        //
    }
}
