<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\Category;
use App\Models\Product_units;
use App\Models\Products;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $s_cat = $request->s_cat ?? 'all';
        $products = Products::query();

        if ($s_cat != 'all') {
            $products->where('category_id', $s_cat);
        }

        $items = $products->get();

        $cats = Category::orderBy('name', 'asc')->get();

        return view('products.product', compact('items', 'cats', 's_cat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cats = Category::orderBy('name', 'asc')->get();
        $units = Unit::get();

        return view('products.create', compact('cats', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'unique:products,name',
            ],
            [
                'name.unique' => 'Product already Existing',
            ]
        );

        $product = Products::create($request->only(['name', 'category_id']));

        $units = $request->unit_names;

        foreach ($units as $key => $unit) {
            Product_units::create(
                [
                    'product_id' => $product->id,
                    'unit_name' => $unit,
                    'value' => $request->unit_values[$key],
                    'price' => $request->prices[$key],
                ]
            );
        }

        return back()->with('success', 'Product Created');
    }

    /**
     * Display the specified resource.
     */
    public function show($all)
    {
        $categories = categories::with('products')->get();

        return view('products.pricelist', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        $cats = Category::orderBy('name', 'asc')->get();

        return view('products.edit', compact('cats', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'unique:products,name,'.$id,
            ],
            [
                'name.unique' => 'Product already Existing',
            ]
        );

        $product = Products::find($id);
        $product->update($request->only(['name', 'nameurdu', 'catID', 'brandID', 'pprice', 'price', 'discount', 'status', 'vendorID', 'fright', 'labor', 'claim', 'sfright', 'sclaim', 'discountp', 'branchID']));

        return redirect()->back()->with('success', 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        //
    }
}
