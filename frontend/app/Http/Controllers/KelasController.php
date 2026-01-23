<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KelasController extends Controller
{
    public function index()
    {
        return view('kelas.index');
    }

    public function store(Request $request)
    {
        Http::post('http://localhost:3001/kelas', [
            'nama_kelas' => $request->nama_kelas,
            'semester' => $request->semester,
        ]);

        return back()->with('success', 'Kelas berhasil dibuat');
    }
}
