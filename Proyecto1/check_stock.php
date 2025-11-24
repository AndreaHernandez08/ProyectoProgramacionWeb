<?php
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];

$id = isset($_POST['id']) ? trim($_POST['id']) : null;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : null;

if ($id === null || $qty === null) {
    echo json_encode(['ok' => false, 'error' => 'Parámetros faltantes']);
    exit;
}

$category = '';
$name = '';
if (isset($_SESSION['cart'][$id])) {
    $item = $_SESSION['cart'][$id];
    $category = $item['category'] ?? '';
    $name = $item['name'] ?? '';
} else {
    // permitir consulta mediante nombre/categoría si se envían
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
}

if ($category && in_array($category, $allowed_tables, true) && $name) {
    $mysqli = get_db_connection();
    $sql = "SELECT existencia FROM `" . $mysqli->real_escape_string($category) . "` WHERE sabor = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode(['ok' => false, 'error' => 'Error en la consulta a la base de datos']);
        exit;
    }
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $dbExist = (int)$row['existencia'];
        // Si el producto está en el carrito, restar la cantidad ya reservada
        $reserved = 0;
        if (isset($_SESSION['cart'][$id])) {
            $reserved = isset($_SESSION['cart'][$id]['qty']) ? (int)$_SESSION['cart'][$id]['qty'] : 0;
        }
        $available = max(0, $dbExist - $reserved);
        echo json_encode(['ok' => true, 'available' => $available, 'name' => $name, 'reserved' => $reserved, 'dbExist' => $dbExist]);
    } else {
        // producto no encontrado en tabla -> permitir (no bloquea)
        echo json_encode(['ok' => true, 'available' => 99999, 'name' => $name]);
    }
    $stmt->close();
    $mysqli->close();
    exit;
}

// Si no hay categoría conocida, no verificamos en BD (permitir)
echo json_encode(['ok' => true, 'available' => 99999, 'name' => $name]);
exit;
