<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('View Branches');
        $branches = Branches::all();
        $users = User::all();

        return view('branches.index', compact('branches', 'users'));
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
        $this->authorize('Create Branches');
        $request->validate([
            'name' => 'required|unique:branches,name',
            'address' => 'required',
            'phone' => 'required',
            'users' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $branch = Branches::create($request->except('users'));
            $branch->syncUsers($request->users);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('branches.index')->with('success', 'Branch created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branches $branches)
    {
        $users = User::all();

        return view('branches.show', compact('branches', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branches $branches)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Edit Branches');
        $request->validate([
            'name' => 'required|unique:branches,name,'.$id,
            'address' => 'required',
            'phone' => 'required',
            'users' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $branch = Branches::find($id);
            $branch->update($request->except('users'));
            $branch->users()->sync($request->users);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branches $branches)
    {
        //
    }
}
