<?php
session_start();
require_once __DIR__ . '/db.php';

$allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
$image = isset($_POST['image']) ? trim($_POST['image']) : '';
$category = isset($_POST['category']) ? trim($_POST['category']) : '';

// Validación de datos mínimos
if ($id === '' || $name === '' || $price <= 0 || $qty <= 0) {
    header('Location: index.php');
    exit;
}

// Asegurar carrito
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// VALIDACIÓN DE STOCK MEJORADA
if (in_array($category, $allowed_tables, true)) {
    $mysqli = get_db_connection();
    $sql = "SELECT existencia FROM `{$category}` WHERE LOWER(sabor) LIKE CONCAT('%', LOWER(?), '%') LIMIT 1";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        error_log("Buscando producto: '$name' en tabla '$category'");
        error_log("Filas encontradas: " . $res->num_rows);
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
        // Buscar TODOS los registros para comparar
        $debug_sql = "SELECT sabor FROM `{$category}`";
        $debug_res = $mysqli->query($debug_sql);
        $all_sabores = [];
        while ($r = $debug_res->fetch_assoc()) {
            $all_sabores[] = $r['sabor'];
        }
        error_log("Buscando: '$name'");
        error_log("Disponibles: " . implode(", ", $all_sabores));
    }
        
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $existencia = (int)$row['existencia'];
            $stmt->close();
            
            // Cantidad existente en carrito
            $existingQty = isset($_SESSION['cart'][$id]['qty']) ? (int)$_SESSION['cart'][$id]['qty'] : 0;
            $wantedTotal = $existingQty + $qty;
            
            //VALIDAR STOCK INSUFICIENTE
            if ($wantedTotal > $existencia) {
                $_SESSION['error_cart'] = "⚠️ Stock insuficiente para <strong>{$name}</strong>. Solo hay <strong>{$existencia}</strong> disponibles.";
                $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
                header('Location: ' . $back);
                exit;
            }
            
            // ADVERTENCIA SI QUEDAN POCAS UNIDADES
            if ($existencia <= 5 && $existencia > 0) {
                $_SESSION['warning_cart'] = "⚠️ ¡Últimas {$existencia} unidades de {$name}!";
            }
            
            // VALIDAR PRODUCTO SIN STOCK
            if ($existencia <= 0) {
                $_SESSION['error_cart'] = "❌ <strong>{$name}</strong> está agotado temporalmente.";
                $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
                header('Location: ' . $back);
                exit;
            }
            
        } else {
            // Producto no encontrado en BD
            $_SESSION['error_cart'] = "❌ Producto no encontrado en inventario.";
            $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
            header('Location: ' . $back);
            exit;
        }
    }
    $mysqli->close();
}

// ✅ Añadir o actualizar ítem
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
    if (!empty($image)) $_SESSION['cart'][$id]['image'] = $image;
    if (!empty($category)) $_SESSION['cart'][$id]['category'] = $category;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        'image' => $image,
        'category' => $category,
    ];
}

// Calcular total
$total_qty = array_sum(array_map(function($it){ return (int)$it['qty']; }, $_SESSION['cart']));

$_SESSION['success_cart'] = "✅ <strong>{$name}</strong> agregado al carrito ({$qty} unidad/es).";

$back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header('Location: ' . $back);
exit;