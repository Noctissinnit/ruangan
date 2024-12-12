<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function indexLogin()
    {
        return view("auth.login");
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->where('password', $request->password)->first();

        if ($user === null) {
            return back()->with('error', 'Email atau Password salah.');
        }

        Auth::login($user);
        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('home');
    }
}
