<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = env('API_URL', 'http://127.0.0.1:3002');
    }

    public function index()
    {
        if (!session()->has('user')) return redirect('/login');
        $user = session('user');

        try {
            $kelas = Http::timeout(2)->get($this->api . '/kelas')->json() ?? [];
            $jadwal = Http::timeout(2)->get($this->api . '/jadwal')->json() ?? [];
            $allBookings = Http::timeout(2)->get($this->api . '/bookings')->json() ?? [];

            // JAHIT DATA (Booking -> Jadwal)
            foreach ($jadwal as &$j) {
                $j['booking'] = null;
                if ($j['status'] !== 'available') {
                    foreach ($allBookings as $b) {
                        $bJadwalId = $b['jadwalId'] ?? ($b['jadwal']['id'] ?? null);
                        if ($bJadwalId == $j['id']) {
                            $j['booking'] = $b;
                            break; 
                        }
                    }
                }
            }
            unset($j);

        } catch (\Exception $e) {
            $kelas = []; $jadwal = [];
        }

        if ($user['role'] === 'admin') return view('dashboard.admin', compact('user', 'kelas', 'jadwal'));
        if ($user['role'] === 'dosen') return view('dashboard.dosen', compact('user', 'kelas', 'jadwal'));
        return view('dashboard.mahasiswa', compact('user', 'kelas', 'jadwal'));
    }

    // === CRUD KELAS ===
    public function storeKelas(Request $request)
    {
        $response = Http::post($this->api . '/kelas', [
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'kapasitas'  => (int) $request->kapasitas, 
            'deskripsi'  => $request->deskripsi ?? '-'
        ]);
        return $this->handleResponse($response, 'Kelas berhasil ditambahkan!');
    }

    public function updateKelas(Request $request, $id)
    {
        $response = Http::put($this->api . '/kelas/' . $id, [
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'kapasitas'  => (int) $request->kapasitas,
            'deskripsi'  => $request->deskripsi
        ]);
        return $this->handleResponse($response, 'Kelas berhasil diupdate!');
    }

    public function deleteKelas($id)
    {
        $allJadwal = Http::get($this->api . '/jadwal')->json() ?? [];
        $jadwalTerkait = collect($allJadwal)->filter(function ($j) use ($id) {
            return isset($j['kelas']['id']) && $j['kelas']['id'] == $id;
        });

        if ($jadwalTerkait->contains(fn($j) => $j['status'] !== 'available')) {
            return back()->with('error', 'GAGAL: Kelas sedang di-booking! Batalkan dulu.');
        }

        foreach ($jadwalTerkait as $j) Http::delete($this->api . '/jadwal/' . $j['id']);
        $response = Http::delete($this->api . '/kelas/' . $id);
        
        if ($response->successful()) return back()->with('success', 'Kelas dihapus.');
        return back()->with('error', 'Gagal hapus kelas.');
    }

    // === CRUD JADWAL ===
    public function storeJadwal(Request $request)
    {
        $hari = $request->hari;
        if (empty($hari) && $request->tanggal) {
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $hari = $days[date('w', strtotime($request->tanggal))];
        }
        $response = Http::post($this->api . '/jadwal', [
            'kelasId' => (int) $request->kelas_id,
            'tanggal' => $request->tanggal,
            'hari' => $hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'available'
        ]);
        return $this->handleResponse($response, 'Jadwal berhasil dibuat!');
    }

    public function updateJadwal(Request $request, $id)
    {
        $hari = $request->hari;
        if (empty($hari) && $request->tanggal) {
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $hari = $days[date('w', strtotime($request->tanggal))];
        }
        $response = Http::put($this->api . '/jadwal/' . $id, [
            'tanggal' => $request->tanggal,
            'hari' => $hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'available' 
        ]);
        return $this->handleResponse($response, 'Jadwal berhasil diedit!');
    }

    public function deleteJadwal($id)
    {
        $response = Http::delete($this->api . '/jadwal/' . $id);
        return $this->handleResponse($response, 'Jadwal dihapus.');
    }

    public function resetStatus($id)
    {
        Http::put($this->api . '/jadwal/' . $id, ['status' => 'available']);
        return back()->with('success', 'Status Reset jadi Available.');
    }

    // === BOOKING (SUDAH BERSIH) ===
    public function booking(Request $request) 
    {
        $user = session('user');
        
        // LANGSUNG KIRIM KEPERLUAN SAJA (Tanpa embel-embel nama)
        $response = Http::post($this->api . '/bookings/create', [
            'userId' => (int) $user['id'],
            'role' => $user['role'],
            'jadwalId' => (int) $request->jadwal_id,
            'keperluan' => $request->keperluan // Murni text keperluan
        ]);

        return $this->handleResponse($response, 'Booking Berhasil Dikirim!');
    }

    private function handleResponse($response, $successMsg)
    {
        if ($response->successful()) return back()->with('success', $successMsg);
        return back()->with('error', 'Gagal: ' . ($response->json()['message'] ?? $response->body()));
    }
}