<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Password Bulanan</title>
</head>

<body>
    <h2>Rekap Password Bulanan</h2>
    <p>Berikut password baru untuk tiap akun:</p>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Password Baru</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $a)
            <tr>
                <td>{{ $a['username'] }}</td>
                <td>{{ $a['email'] ?? '—' }}</td>
                <td><code style="font-family:monospace">{{ $a['password'] }}</code></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:16px"><em>Simpan info ini secara aman.</em></p>
</body>

</html>