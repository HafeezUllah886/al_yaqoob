<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('View Users');
        $users = User::whereNotIn('id', [1, auth()->user()->id])->get();
        $roles = Role::where('name', '!=', 'Admin')->get();

        return view('users.index', compact('users', 'roles'));
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
        $this->authorize('Create Users');
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create($request->except('role'));
            $user->assignRole($request->role);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(Request $request, $id)
    {
        $this->authorize('Change User Password');
        $request->validate([
            'new_password' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->password = Hash::make($request->new_password);
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User password changed successfully');
    }

    public function changeStatus($id)
    {
        $this->authorize('Change User Status');
        $user = User::find($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();

        session()->forget('confirmed_password');

        return redirect()->route('users.index')->with('success', 'User status changed successfully');
    }
}
