<?php
session_start();
// Si ya está autenticado, redirigir
if (!empty($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main class="contenedor">
        <h2>Registrarse</h2>
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div style="background:#eef;padding:8px;border-radius:6px;margin-bottom:8px"><?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['register_errors'])): ?>
            <div style="background:#fee;padding:8px;border-radius:6px;margin-bottom:8px;color:#900">
                <ul style="margin:0;padding-left:18px">
                <?php foreach ($_SESSION['register_errors'] as $err): ?>
                    <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['register_errors']); ?>
        <?php endif; ?>

        <?php
        $old = $_SESSION['register_old'] ?? ['nombres'=>'','apellidos'=>'','usuario'=>''];
        // Remove old values after reading so they aren't reused
        unset($_SESSION['register_old']);
        ?>

        <form action="register_handler.php" method="post">
            <label>Nombres</label><br>
            <input type="text" name="nombres" required value="<?php echo $old['nombres']; ?>"><br>
            <label>Apellidos</label><br>
            <input type="text" name="apellidos" required value="<?php echo $old['apellidos']; ?>"><br>
            <label>Usuario</label><br>
            <input type="text" name="usuario" required value="<?php echo $old['usuario']; ?>"><br>
            <label>Contraseña</label><br>
            <input type="password" name="contrasena" required><br>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
