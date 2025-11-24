<?php
session_start();
// Proteger: solo usuarios autenticados
if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <main class="contenedor">
        <h2>Mi cuenta</h2>
        <p>Hola, <?php echo htmlspecialchars($_SESSION['username']); ?>. Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>.</p>
        <ul>
            <li><a href="index.php">Volver al sitio</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </main>
</body>
</html>
