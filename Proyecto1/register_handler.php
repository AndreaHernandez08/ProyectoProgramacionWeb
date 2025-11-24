<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres   = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $usuario   = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = 'user';

    $errors = [];

    // Validaciones
    if (mb_strlen($nombres) < 2) {
        $errors[] = 'El campo "nombres" debe tener al menos 2 caracteres.';
    }
    if (mb_strlen($apellidos) < 2) {
        $errors[] = 'El campo "apellidos" debe tener al menos 2 caracteres.';
    }
    if (mb_strlen($usuario) < 4) {
        $errors[] = 'El nombre de usuario debe tener al menos 4 caracteres.';
    }
    if (!preg_match('/^[A-Za-z0-9_.]+$/', $usuario)) {
        $errors[] = 'El nombre de usuario solo puede contener letras, números, guion bajo (_) y punto (.).';
    }
    if (mb_strlen($contrasena) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    // Guardar los valores ingresados (para mantenerlos si hay error)
    $_SESSION['register_old'] = [
        'nombres' => htmlspecialchars($nombres, ENT_QUOTES, 'UTF-8'),
        'apellidos' => htmlspecialchars($apellidos, ENT_QUOTES, 'UTF-8'),
        'usuario' => htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8')
    ];

    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header('Location: register.php');
        exit;
    }

    $conn = get_db_connection();

    // Verificar si el usuario ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['register_errors'] = ['El nombre de usuario ya está en uso. Elige otro.'];
        header('Location: register.php');
        exit;
    }
    $stmt->close();

    // Hashear contraseña (esto es correcto y seguro)
    $hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombres, apellidos, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $nombres, $apellidos, $usuario, $hashed, $rol);

    if ($stmt->execute()) {
        unset($_SESSION['register_old']);
        $_SESSION['flash_message'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['register_errors'] = ['Error al registrar. Intenta de nuevo.'];
        header('Location: register.php');
        exit;
    }
}
?>


