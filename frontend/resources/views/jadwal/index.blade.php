@extends('layouts.app')

@section('content')
<h3>Tambah Jadwal</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="/jadwal">
    @csrf

    <div class="mb-3">
        <label>Mata Kuliah</label>
        <input type="text" name="mata_kuliah" class="form-control">
    </div>

    <div class="mb-3">
        <label>Hari</label>
        <input type="text" name="hari" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jam</label>
        <input type="text" name="jam" class="form-control">
    </div>

    <button class="btn btn-success">Simpan</button>
</form>
@endsection
