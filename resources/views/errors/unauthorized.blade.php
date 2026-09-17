<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
</head>
<body>
    <img src="{{ asset('images/unauthorized.png') }}" alt="Akses Ditolak" width="500">
    <br><br>
    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
</body>
</html>