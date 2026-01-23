@extends('layouts.app')

@section('content')
<h3>Login</h3>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="/login">
    @csrf

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary">Login</button>
</form>

<p class="mt-3">
    Belum punya akun? <a href="/register">Register</a>
</p>
@endsection
