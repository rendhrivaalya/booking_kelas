<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib pakai ini untuk nembak API

class AuthController extends Controller
{
    public function registerForm() {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Kirim data ke NestJS (Port 3001 sesuai Docker kamu)
        // Kita pakai localhost karena kamu jalankan 'php artisan serve'
        $response = Http::post('http://127.0.0.1:3001/auth/register', [
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => $request->role, // Pastikan isinya 'Dosen' atau 'Mahasiswa'
        ]);

        // 2. CEK APAKAH BERHASIL (Ini No. 2 & 3 yang benar)
        if ($response->successful()) {
            return redirect('/login')->with('success', 'Berhasil! Cek Log NestJS & RabbitMQ!');
        }

        // Kalau gagal, intip errornya
        dd($response->json(), "Cek apakah NestJS nyala di port 3001?");
    }

    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::post('http://127.0.0.1:3001/auth/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            session(['user' => $data['user']]); 
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Login Gagal.']);
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect('/login');
    }
}