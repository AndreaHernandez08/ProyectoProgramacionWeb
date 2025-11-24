<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        header('Location: login.php?error=1');
        exit;
    }

    $mysqli = get_db_connection();

    $stmt = $mysqli->prepare('SELECT id, nombres, apellidos, usuario, contrasena, rol FROM usuarios WHERE usuario = ? LIMIT 1');
    if (!$stmt) {
        error_log('Prepare failed: ' . $mysqli->error);
        header('Location: login.php?error=1');
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    $verified = false;

    if ($user) {
        // Caso 1: contraseñas hasheadas
        if (password_verify($password, $user['contrasena'])) {
            $verified = true;
        } 
        // Caso 2: contraseñas en texto plano (no recomendado)
        elseif ($password === $user['contrasena']) {
            $verified = true;
        }
    }

    if ($user && $verified) {
        session_regenerate_id(true);
        $_SESSION['username'] = $user['usuario'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre_completo'] = trim($user['nombres'] . ' ' . $user['apellidos']);
        header('Location: index.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}

// Si no es POST, redirigir al login
header('Location: login.php');
exit;

