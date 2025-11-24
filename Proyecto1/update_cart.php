<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
require_once __DIR__ . '/db.php';

$allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];

$qtys = isset($_POST['qty']) && is_array($_POST['qty']) ? $_POST['qty'] : [];
$errors = [];
// Conexión la abriremos sólo si hace falta
$mysqli = null;
foreach ($qtys as $id => $q) {
    $q = intval($q);
    if ($q <= 0) {
        // eliminar
        unset($_SESSION['cart'][$id]);
        continue;
    }

    if (!isset($_SESSION['cart'][$id])) {
        // no existe en carrito; saltar
        continue;
    }

    $item = $_SESSION['cart'][$id];
    $category = $item['category'] ?? '';
    $name = $item['name'] ?? '';

    // Si la categoría está en las permitidas, comprobar en BD
    if (in_array($category, $allowed_tables, true)) {
        if ($mysqli === null) $mysqli = get_db_connection();
        $sql = "SELECT existencia FROM `" . $mysqli->real_escape_string($category) . "` WHERE sabor = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $existencia = (int)$row['existencia'];
                $stmt->close();
                if ($q > $existencia) {
                    // No actualizar, agregar error y continuar
                    $errors[] = "No hay suficientes unidades de {$name}. Disponibles: {$existencia}.";
                    // dejamos la cantidad anterior (no actualizar)
                    continue;
                }
            } else {
                $stmt->close();
                // no encontrado -> permitimos la actualización
            }
        }
    }

    // actualizar cantidad
    $_SESSION['cart'][$id]['qty'] = $q;
}

if ($mysqli) $mysqli->close();

if (!empty($errors)) {
    $_SESSION['flash_cart'] = [
        'message' => implode(' ', $errors),
        'total_qty' => array_sum(array_map(function($it){ return (int)$it['qty']; }, $_SESSION['cart']))
    ];
}

header('Location: cart.php');
exit;
