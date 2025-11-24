<?php
session_start();
require_once __DIR__ . '/db.php';

// Proteger: solo administradores
if (empty($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php?error=2');
    exit;
}

// Rutas probables de logs en XAMPP (Windows)
$possible = [
    'C:\\xampp\\apache\\logs\\error.log',
    'C:\\xampp\\php\\logs\\php_error_log',
    ini_get('error_log') ?: null,
];

$logFile = null;
foreach ($possible as $p) {
    if (!$p) continue;
    if (file_exists($p) && is_readable($p)) {
        $logFile = $p;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Últimas entradas del log</title>
    <link href="css/style.css" rel="stylesheet">
    <style>pre{white-space:pre-wrap;word-wrap:break-word;background:#111;color:#eee;padding:1rem;border-radius:.5rem;max-height:70vh;overflow:auto;}</style>
</head>
<body>
    <main class="contenedor">
        <h2>Últimas entradas del log (depuración)</h2>
        <p>Mostrando las últimas líneas del archivo de log detectado. Usa esto sólo en desarrollo.</p>
        <?php if (!$logFile): ?>
            <p style="color:crimson;">No se encontró un archivo de log accesible. Rutas probadas: <?php echo htmlspecialchars(implode(', ', array_filter($possible))); ?></p>
        <?php else: ?>
            <p>Archivo: <strong><?php echo htmlspecialchars($logFile); ?></strong></p>
            <?php
            // Leer últimas líneas de forma sencilla
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $tail = array_slice($lines, -400); // mostrar hasta 400 líneas finales
            ?>
            <pre><?php echo htmlspecialchars(implode("\n", $tail)); ?></pre>
        <?php endif; ?>

        <p><a href="admin.php">Volver al panel</a> | <a href="index.php">Ir al sitio</a></p>
    </main>
</body>
</html>
