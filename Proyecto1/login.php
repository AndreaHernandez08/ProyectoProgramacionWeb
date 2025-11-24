<?php
session_start();
// Si ya está autenticado, redirigir al inicio
if (!empty($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
$error = isset($_GET['error']) ? $_GET['error'] : '';
$flash = '';
if (!empty($_SESSION['flash_message'])) {
    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main class="contenedor">
        <h2>Iniciar sesión</h2>
        <?php if ($flash): ?>
            <p style="color:green"><?php echo htmlspecialchars($flash); ?></p>
        <?php endif; ?>
        <?php if ($error == 1): ?>
            <p style="color:red;">Credenciales incorrectas. Intenta de nuevo.</p>
        <?php elseif ($error == 2): ?>
            <p style="color:red;">Acceso no autorizado. Inicia sesión con una cuenta con permisos adecuados.</p>
        <?php endif; ?>
        <form action="auth.php" method="post">
            <label for="username">Usuario</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Contraseña</label><br>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Entrar</button>
        </form>

        <p><a href="index.php">Volver al inicio</a></p>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        <p>Para probar: admin / adminpass  o  user / userpass</p>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
