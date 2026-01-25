@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh">
    <div class="card shadow-sm" style="width: 420px">
        <div class="card-body">
            <h4 class="text-center mb-4">Login</h4>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                Belum punya akun? <a href="{{ url('/register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
