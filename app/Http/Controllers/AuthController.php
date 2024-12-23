<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isAdmin()){
                
                return redirect()->intended('dashboardAdm'); 
            }
            return redirect()->intended('dashboard'); 
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Menangani logout
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
