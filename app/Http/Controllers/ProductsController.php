<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('View Products');
        $products = Product::with('category', 'unit')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $units = Unit::all();

        return view('products.index', compact('products', 'categories', 'units'));
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
        $this->authorize('Create Products');
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products,code',
            'category_id' => 'nullable',
            'unit_id' => 'required',
            'price' => 'required',
        ]);

        try {
            DB::beginTransaction();
            Product::create($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Product Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Edit Products');
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products,code,'.$id,
            'category_id' => 'nullable',
            'unit_id' => 'required',
            'price' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $product = Product::find($id);
            $product->update($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('Delete Products');
        try {
            DB::beginTransaction();
            $product = Product::find($id);
            $product->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Product Deleted Successfully');
    }

    public function ajaxCreate(Request $request)
    {
        $check = Product::where('name', $request->name)->count();
        if ($check > 0) {
            return response()->json(
                ['response' => 'Exists']
            );
        }
        $product = Product::create($request->all());

        return response()->json(
            ['response' => $product->id]
        );
    }

    public function barcodePrint($id)
    {
        $product = Product::find($id);

        return view('products.barcode', compact('product'));
    }

    public function generateCode()
    {
        gen:
        $value = rand(1111111111, 9999999999);
        $check = Product::where('code', "C$value")->count();
        if ($check > 0) {
            goto gen;
        }

        return "C$value";
    }
}
