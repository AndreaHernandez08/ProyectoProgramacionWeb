<?php
// auth_helpers.php

function is_logged_in() {
    return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

function get_username() {
    return $_SESSION['username'] ?? null;
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_role() {
    return $_SESSION['role'] ?? null;
}

function get_nombre_completo() {
    return $_SESSION['nombre_completo'] ?? 'Usuario';
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>