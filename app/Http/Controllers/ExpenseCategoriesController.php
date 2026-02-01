<?php

namespace App\Http\Controllers;

use App\Models\expenseCategories;
use Illuminate\Http\Request;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('Create Expense Categories');
        $cats = expenseCategories::orderBy('name', 'asc')->get();

        return view('Finance.expense.categories', compact('cats'));
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
        expenseCategories::create($request->all());

        return back()->with('msg', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(expenseCategories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(expenseCategories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Edit Expense Categories');
        expenseCategories::find($id)->update($request->all());

        return back()->with('msg', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(expenseCategories $categories)
    {
        //
    }
}
