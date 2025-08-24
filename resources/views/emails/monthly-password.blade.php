<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Password Bulanan — Semua Akun</title>
</head>

<body>
    <h2>Rekap Password Bulanan</h2>
    <p>Berikut password baru untuk tiap akun:</p>

    <ul>
        @foreach ($accounts as $a)
        <li>
            <strong>{{ $a['username'] }}</strong>
            ({{ $a['email'] ?? '—' }}) →
            <code style="font-family:monospace">{{ $a['password'] }}</code>
        </li>
        @endforeach
    </ul>

    <p><em>Catatan:</em> Mohon simpan informasi ini secara aman.</p>
    <hr>
    <small>Pesan otomatis • Jacketing App</small>
</body>

</html>