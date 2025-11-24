<?php
require_once __DIR__ . '/db.php';

function validarStockCarrito() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return ['ok' => true, 'errores' => []];
    }
    
    $allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];
    $mysqli = get_db_connection();
    $errores = [];
    
    foreach ($_SESSION['cart'] as $id => $item) {
        $category = $item['category'] ?? '';
        $name = $item['name'] ?? '';
        $qty = (int)($item['qty'] ?? 0);
        
        if (!in_array($category, $allowed_tables, true)) {
            continue;
        }
        
        $sql = "SELECT existencia FROM `{$category}` WHERE LOWER(sabor) LIKE CONCAT('%', LOWER(?), '%') LIMIT 1";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $existencia = (int)$row['existencia'];
                
                // Validar stock
                if ($qty > $existencia) {
                    $errores[] = [
                        'producto' => $name,
                        'solicitado' => $qty,
                        'disponible' => $existencia,
                        'mensaje' => "Stock insuficiente para {$name}. Solicitaste {$qty}, solo hay {$existencia} disponibles."
                    ];
                    
                    // Ajustar cantidad en el carrito automáticamente
                    if ($existencia > 0) {
                        $_SESSION['cart'][$id]['qty'] = $existencia;
                    } else {
                        unset($_SESSION['cart'][$id]); // Eliminar del carrito
                    }
                }
                
                if ($existencia <= 0) {
                    $errores[] = [
                        'producto' => $name,
                        'mensaje' => "{$name} está agotado."
                    ];
                    unset($_SESSION['cart'][$id]);
                }
            }
            $stmt->close();
        }
    }
    
    $mysqli->close();
    
    return [
        'ok' => empty($errores),
        'errores' => $errores
    ];
}

// Si se llama directamente, mostrar resultado
if (basename($_SERVER['PHP_SELF']) === 'validar_stock.php') {
    $resultado = validarStockCarrito();
    
    if (!$resultado['ok']) {
        $_SESSION['error_checkout'] = "⚠️ Algunos productos no tienen suficiente stock:";
        foreach ($resultado['errores'] as $error) {
            $_SESSION['error_checkout'] .= "<br>• " . $error['mensaje'];
        }
    }
    
    header('Location: carrito.php');
    exit;
}
?>