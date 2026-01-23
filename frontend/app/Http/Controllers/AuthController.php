<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private $apiUrl = 'http://localhost:3001/auth';

    // ======================
    // FORM
    // ======================
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    // ======================
    // LOGIN
    // ======================
    public function login(Request $request)
    {
        $response = Http::post($this->apiUrl . '/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Username atau password salah');
        }

        session([
            'user' => $response->json()['user']
        ]);

        return redirect('/dashboard');
    }

    // ======================
    // REGISTER
    // ======================
    public function register(Request $request)
    {
        $response = Http::post($this->apiUrl . '/register', [
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Register gagal');
        }

        return redirect('/login')->with('success', 'Register berhasil');
    }

    // ======================
    // DASHBOARD
    // ======================
    public function dashboard()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $user = session('user');

        if ($user['role'] === 'admin') {
            return view('dashboard.admin', compact('user'));
        }

        if ($user['role'] === 'dosen') {
            return view('dashboard.dosen', compact('user'));
        }

        return view('dashboard.mahasiswa', compact('user'));
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout()
    {
        session()->forget('user');
        return redirect('/login');
    }
}
