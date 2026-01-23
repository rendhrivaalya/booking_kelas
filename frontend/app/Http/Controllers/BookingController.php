@extends('layouts.app')

@section('content')
<div class="container">
<h3>Admin Dashboard</h3>

<form method="POST" action="/kelas/store" class="mb-3">
@csrf
<input name="kode_kelas" placeholder="Kode">
<input name="nama_kelas" placeholder="Nama">
<input name="kapasitas" placeholder="Kapasitas">
<button class="btn btn-success">Tambah</button>
</form>

@foreach($kelas as $k)
<div class="card mb-2 p-2">
{{ $k['nama_kelas'] }} ({{ $k['status'] }})

<form method="POST" action="/kelas/toggle/{{ $k['id'] }}">
@csrf
<button class="btn btn-warning btn-sm">Toggle Status</button>
</form>

<form method="POST" action="/kelas/delete/{{ $k['id'] }}">
@csrf
<button class="btn btn-danger btn-sm">Hapus</button>
</form>
</div>
@endforeach
</div>
@endsection
