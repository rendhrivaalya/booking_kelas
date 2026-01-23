@extends('layouts.app')

@section('content')
<h3>Tambah Kelas</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="/kelas">
    @csrf

    <div class="mb-3">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas" class="form-control">
    </div>

    <div class="mb-3">
        <label>Semester</label>
        <input type="number" name="semester" class="form-control">
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection
