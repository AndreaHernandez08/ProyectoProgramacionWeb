<?php
session_start();
// Proteger: solo administradores
if (empty($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php?error=2');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <main class="contenedor">
        <h2>Panel de administración</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>. Aquí puedes administrar el sitio.</p>
        <ul>
            <li><a href="index.php">Volver al sitio</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </main>
</body>
</html>
