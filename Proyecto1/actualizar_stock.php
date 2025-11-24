<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

// Verificar que sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['ok' => false, 'error' => 'No tienes permisos']);
    exit;
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$tabla = $data['tabla'] ?? '';
$id = $data['id'] ?? 0;
$cantidad = $data['cantidad'] ?? 0;

// Validar tabla permitida
$tablas_permitidas = ['pasteles', 'bebidas', 'galletas', 'roles'];
if (!in_array($tabla, $tablas_permitidas)) {
    echo json_encode(['ok' => false, 'error' => 'Tabla no válida']);
    exit;
}

try {
    $mysqli = get_db_connection();
    
    // Obtener existencia actual
    $stmt = $mysqli->prepare("SELECT existencia FROM $tabla WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    
    if (!$producto) {
        echo json_encode(['ok' => false, 'error' => 'Producto no encontrado']);
        exit;
    }
    
    $nueva_existencia = $producto['existencia'] + $cantidad;
    
    // Evitar stock negativo
    if ($nueva_existencia < 0) {
        echo json_encode(['ok' => false, 'error' => 'Stock no puede ser negativo']);
        exit;
    }
    
    // Actualizar stock
    $stmt = $mysqli->prepare("UPDATE $tabla SET existencia = ? WHERE id = ?");
    $stmt->bind_param("ii", $nueva_existencia, $id);
    $stmt->execute();
    
    echo json_encode([
        'ok' => true, 
        'nueva_existencia' => $nueva_existencia
    ]);
    
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>