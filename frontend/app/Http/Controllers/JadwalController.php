<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JadwalController extends Controller
{
    public function index()
    {
        return view('jadwal.index');
    }

    public function store(Request $request)
    {
        Http::post('http://localhost:3001/jadwal', [
            'mata_kuliah' => $request->mata_kuliah,
            'hari' => $request->hari,
            'jam' => $request->jam,
        ]);

        return back()->with('success', 'Jadwal berhasil dibuat');
    }
}
