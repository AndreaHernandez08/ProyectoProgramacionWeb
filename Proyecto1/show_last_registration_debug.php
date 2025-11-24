<?php
session_start();
// Sólo permitir ver a administradores
if (empty($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$dbg = $_SESSION['last_registration_debug'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Debug registro</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <main class="contenedor">
        <h2>Último intento de registro (debug)</h2>
        <?php if (!$dbg): ?>
            <p>No hay datos de registro recientes en sesión.</p>
        <?php else: ?>
            <table>
                <tr><th>Campo</th><th>Valor</th></tr>
                <tr><td>nombres_raw</td><td><?php echo htmlspecialchars($dbg['nombres_raw']); ?></td></tr>
                <tr><td>apellidos_raw</td><td><?php echo htmlspecialchars($dbg['apellidos_raw']); ?></td></tr>
                <tr><td>usuario_raw</td><td><?php echo htmlspecialchars($dbg['usuario_raw']); ?></td></tr>
                <tr><td>contrasena_length</td><td><?php echo (int)$dbg['contrasena_length']; ?> caracteres</td></tr>
                <tr><td>contrasena_hashed</td><td style="word-break:break-all;font-family:monospace"><?php echo htmlspecialchars($dbg['contrasena_hashed']); ?></td></tr>
                <tr><td>insert_sql (preview)</td><td><?php echo htmlspecialchars($dbg['insert_sql']); ?></td></tr>
            </table>
        <?php endif; ?>
        <p><a href="admin.php">Volver al panel</a></p>
    </main>
</body>
</html>
