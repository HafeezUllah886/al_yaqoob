<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class UnitsController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(confirmPassword::class, only: ['destroy']),
        ];
    }

    public function index()
    {
        $this->authorize('View Units');
        $units = Unit::all();

        return view('units.index', compact('units'));
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
        $this->authorize('Create Units');
        $request->validate([
            'name' => 'required|unique:units,name',
            'value' => 'required',
        ]);

        try {
            DB::beginTransaction();
            Unit::create($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('units.index')->with('success', 'Unit Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Edit Units');
        $request->validate([
            'name' => 'required|unique:units,name,'.$id,
            'value' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $unit = Unit::find($id);
            $unit->update($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('units.index')->with('success', 'Unit Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('Delete Units');
        try {
            DB::beginTransaction();
            $unit = Unit::find($id);
            $unit->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('units.index')->with('success', 'Unit Deleted Successfully');
    }
}
