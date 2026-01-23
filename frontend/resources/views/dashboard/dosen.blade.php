@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Halo, {{ $user['username'] }}</h4>
    <p class="text-muted">Role: Dosen</p>

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
                            @if($k['status'] === 'kosong')
                                <button class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bookingModal{{ $k['id'] }}">
                                    Booking
                                </button>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    Sudah dibooking
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- MODAL BOOKING --}}
                    <div class="modal fade" id="bookingModal{{ $k['id'] }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="/booking">
                                @csrf
                                <input type="hidden" name="kelas_id" value="{{ $k['id'] }}">

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Booking {{ $k['nama_kelas'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Jam Mulai</label>
                                            <input type="time" name="jam_mulai" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Jam Selesai</label>
                                            <input type="time" name="jam_selesai" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Keperluan</label>
                                            <input type="text" name="keperluan" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Booking</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
