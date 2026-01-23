<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    private $api = 'http://localhost:3001';

    public function toggleStatus($id)
    {
        Http::post($this->api."/kelas/$id/toggle");
        return redirect('/dashboard');
    }

    public function store(Request $request)
    {
        Http::post($this->api.'/kelas', [
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'kapasitas'  => $request->kapasitas,
        ]);

        return redirect('/dashboard');
    }

    public function destroy($id)
    {
        Http::delete($this->api."/kelas/$id");
        return redirect('/dashboard');
    }
}
