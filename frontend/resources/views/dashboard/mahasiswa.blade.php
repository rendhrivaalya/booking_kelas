@extends('layouts.app')

@section('content')
<h4>Dashboard Mahasiswa</h4>

@if(empty($kelas))
    <div class="alert alert-warning">Data kelas belum tersedia</div>
@else
<table class="table table-bordered mt-3">
    <thead class="table-dark">
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kapasitas</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kelas as $k)
        <tr>
            <td>{{ $k['kode_kelas'] }}</td>
            <td>{{ $k['nama_kelas'] }}</td>
            <td>{{ $k['kapasitas'] }}</td>
            <td>
                <span class="badge {{ $k['status'] === 'kosong' ? 'bg-success' : 'bg-danger' }}">
                    {{ $k['status'] }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<h5 class="mt-4">Jadwal</h5>

@if(empty($jadwal))
    <div class="alert alert-info">Belum ada jadwal</div>
@else
<table class="table table-striped">
    <tr>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Kelas</th>
        <th>Status</th>
    </tr>
    @foreach($jadwal as $j)
    <tr>
        <td>{{ $j['tanggal'] }}</td>
        <td>{{ $j['jam_mulai'] }} - {{ $j['jam_selesai'] }}</td>
        <td>{{ $j['kelas']['nama_kelas'] }}</td>
        <td>{{ $j['status'] }}</td>
    </tr>
    @endforeach
</table>
@endif
@endsection
