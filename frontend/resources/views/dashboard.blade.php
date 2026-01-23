<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Dashboard</h3>
    <p>Halo, <b>{{ $user['username'] }}</b></p>
    <p>Role: {{ $user['role'] }}</p>

    <a href="/logout" class="btn btn-danger">Logout</a>
</div>

</body>
</html>
