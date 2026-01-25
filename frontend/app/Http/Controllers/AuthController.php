<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    // --- FITUR REGISTER ---
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role'     => 'required|in:dosen,mahasiswa,admin'
        ]);

        // 2. Simpan ke Database (REAL)
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Password di-enkripsi
            'role'     => $request->role
        ]);

        // 3. Sukses
        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan Login.');
    }

    // --- FITUR LOGIN ---
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Bisa login pakai Username ATAU Email
        $input = $request->input('username') ?? $request->input('email');
        $password = $request->input('password');
        
        $type = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$type => $input, 'password' => $password])) {
            $request->session()->regenerate();
            
            // Simpan session manual (PENTING buat Dashboard kamu)
            session(['user' => Auth::user()->toArray()]); 

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Username atau Password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->forget('user');
        return redirect('/login');
    }
}