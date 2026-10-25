<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function index()
    {
        return view('login');
    }

    // Memproses data login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Arahkan ke halaman cctv/dashboard setelah sukses login
            return redirect()->intended('/cctv'); 
        }

        return back()->with('loginError', 'Email atau Password salah!');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); // Kembali ke landing page setelah logout
    }
}