<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class authController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function login(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $req->name)->first();
        if ($user->status == 'inactive') {
            return back()->with('error', 'Your account is inactive');
        }
        if (auth()->attempt($req->only('name', 'password'))) {
            $req->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->with('error', 'Wrong User Name or Password');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
