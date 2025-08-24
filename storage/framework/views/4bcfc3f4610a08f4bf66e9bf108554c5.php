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
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($a['username']); ?></td>
                <td><?php echo e($a['email'] ?? '—'); ?></td>
                <td><code style="font-family:monospace"><?php echo e($a['password']); ?></code></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <p style="margin-top:16px"><em>Simpan info ini secara aman.</em></p>
</body>

</html><?php /**PATH D:\cablejacketing\jacketing-kabel\resources\views/emails/all-passwords.blade.php ENDPATH**/ ?>