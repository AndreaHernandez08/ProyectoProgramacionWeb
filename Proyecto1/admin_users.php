<?php
session_start();
require_once __DIR__ . '/db.php';

// Solo administradores
if (empty($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php?error=2');
    exit;
}

$mysqli = get_db_connection();

// Manejar creación de usuario desde el formulario
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos según la tabla 'usuarios'
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $newrole = isset($_POST['role']) && $_POST['role'] === 'admin' ? 'admin' : 'user';

    if ($usuario === '' || $password === '') {
        $message = 'Usuario y contraseña obligatorios.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $mysqli->prepare('INSERT INTO usuarios (nombres, apellidos, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)');
        $ins->bind_param('sssss', $nombres, $apellidos, $usuario, $hash, $newrole);
        if ($ins->execute()) {
            $message = 'Usuario creado correctamente.';
        } else {
            $message = 'Error: ' . $ins->error;
        }
        $ins->close();
    }
}

// Listar usuarios según la tabla 'usuarios'
$res = $mysqli->query('SELECT id, nombres, apellidos, usuario, rol FROM usuarios ORDER BY id DESC');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionar usuarios - Admin</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <main class="contenedor">
        <h2>Gestión de usuarios</h2>
        <p><?php echo htmlspecialchars($message); ?></p>

        <h3>Crear nuevo usuario</h3>
        <form method="post">
            <label>Nombres</label><br>
            <input name="nombres" required><br>
            <label>Apellidos</label><br>
            <input name="apellidos"><br>
            <label>Usuario (login)</label><br>
            <input name="usuario" required><br>
            <label>Contraseña</label><br>
            <input type="password" name="password" required><br>
            <label>Rol</label><br>
            <select name="role">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select><br><br>
            <button type="submit">Crear usuario</button>
        </form>

        <h3>Lista de usuarios</h3>
        <table border="1" cellpadding="6">
            <thead>
                <tr><th>ID</th><th>Nombres</th><th>Apellidos</th><th>Usuario</th><th>Rol</th></tr>
            </thead>
            <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <p><a href="admin.php">Volver al panel</a> | <a href="index.php">Ir al sitio</a> | <a href="logout.php">Cerrar sesión</a></p>
    </main>
</body>
</html>
