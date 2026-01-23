<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $apiBase = 'http://localhost:3001';

    public function index()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $user = session('user');

        // ambil data kelas
        $kelasResponse = Http::get($this->apiBase . '/kelas')->json();
        $kelas = $kelasResponse['data'] ?? [];

        // ambil data jadwal
        $jadwalResponse = Http::get($this->apiBase . '/jadwal')->json();
        $jadwal = $jadwalResponse['data'] ?? [];

        // =====================
        // ROLE BASED DASHBOARD
        // =====================

        if ($user['role'] === 'admin') {
            return view('dashboard.admin', compact('user', 'kelas', 'jadwal'));
        }

        if ($user['role'] === 'dosen') {
            return view('dashboard.dosen', compact('user', 'kelas', 'jadwal'));
        }

        if ($user['role'] === 'mahasiswa') {
            return view('dashboard.mahasiswa', compact('user', 'kelas', 'jadwal'));
        }

        // role tidak dikenal
        abort(403, 'Role tidak valid');
    }

    // =====================
    // BOOKING DOSEN
    // =====================
    public function booking(Request $request)
{
    $user = session('user');

    // 1. simpan jadwal
    $jadwal = Http::post($this->apiBase.'/jadwal', [
        'kelasId' => $request->kelas_id,
        'tanggal' => $request->tanggal,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'status' => 'booked'
    ])->json();

    // 2. simpan booking
    Http::post($this->apiBase.'/booking', [
        'userId' => $user['id'],
        'kelasId' => $request->kelas_id,
        'jadwalId' => $jadwal['data']['id'],
        'keperluan' => $request->keperluan,
        'status' => 'booked'
    ]);

    // 3. update status kelas
    Http::put($this->apiBase.'/kelas/'.$request->kelas_id, [
        'status' => 'booked'
    ]);

    return redirect('/dashboard')->with('success', 'Kelas berhasil dibooking');
}

}
