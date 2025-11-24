<?php
// install.php - script de instalación rápida para crear la base de datos y la tabla users
// Úsalo solo en desarrollo: http://localhost/Proyecto1/install.php

// Parámetros de conexión (ajusta si tu root tiene contraseña)
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'reposterias';

// Conectar al servidor (sin seleccionar DB) para crear la base
$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    die('Error al conectar a MySQL: ' . $mysqli->connect_error);
}

// Crear la base si no existe
if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die('Error al crear la base de datos: ' . $mysqli->error);
}

$mysqli->select_db($dbname);

// Tabla 'usuarios' con las columnas que indicaste
$sql = "CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombres` VARCHAR(150) NOT NULL,
    `apellidos` VARCHAR(150) DEFAULT NULL,
    `usuario` VARCHAR(100) NOT NULL UNIQUE,
    `contrasena` VARCHAR(255) NOT NULL,
    `rol` ENUM('admin','user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (!$mysqli->query($sql)) {
    die('Error al crear la tabla usuarios: ' . $mysqli->error);
}

// Insertar usuarios de ejemplo si no existen

function insert_user_if_not_exists($mysqli, $usuario, $plain_password, $role = 'user', $nombres = '', $apellidos = '') {
    $check = $mysqli->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
    $check->bind_param('s', $usuario);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
        $hash = password_hash($plain_password, PASSWORD_DEFAULT);
        $ins = $mysqli->prepare('INSERT INTO usuarios (nombres, apellidos, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)');
        $ins->bind_param('sssss', $nombres, $apellidos, $usuario, $hash, $role);
        if (!$ins->execute()) {
            echo "Error creando usuario $usuario: " . htmlspecialchars($ins->error) . "<br>";
        } else {
            echo "Usuario $usuario creado. (<strong>contraseña</strong>: $plain_password)<br>";
        }
        $ins->close();
    } else {
        echo "Usuario $usuario ya existe.\n";
    }
    $check->close();
}

// Ejemplos (ajusta nombres si quieres)
insert_user_if_not_exists($mysqli, 'admin', 'adminpass', 'admin', 'Administrador', '');
insert_user_if_not_exists($mysqli, 'user', 'userpass', 'user', 'Usuario', 'Prueba');

echo "\nInstalación completada. Puedes borrar este archivo (install.php) o restringir su acceso.\n";

$mysqli->close();
