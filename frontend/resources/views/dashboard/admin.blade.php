@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Halo, {{ $user['username'] }}</h4>
    <p class="text-muted">Role: Admin</p>

    {{-- TAMBAH KELAS --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">Tambah Kelas</div>
        <div class="card-body">
            <form method="POST" action="/admin/kelas">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="kode_kelas" class="form-control" placeholder="Kode Kelas" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="kapasitas" class="form-control" placeholder="Kapasitas" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi">
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Tambah Kelas</button>
            </form>
        </div>
    </div>

    {{-- LIST KELAS --}}
    <div class="card">
        <div class="card-header fw-bold">Daftar Kelas</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                        <td>
                            @if($k['status'] === 'booked')
                                <form method="POST" action="/admin/kelas/{{ $k['id'] }}/reset">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-warning btn-sm">
                                        Reset ke Kosong
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
