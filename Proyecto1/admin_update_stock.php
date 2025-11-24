<?php
session_start();
require_once __DIR__ . '/db.php';

// Proteger: solo admin
if (empty($_SESSION['username']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin')) {
    http_response_code(403);
    echo "Acceso denegado. Debes ser administrador.";
    exit;
}

// Responder JSON si es petición AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    $resp = [
        'success' => isset($message) && strpos($message, 'actualizada') !== false,
        'message' => $message ?? '',
        'new_total' => isset($new_total) ? $new_total : null,
    ];
    echo json_encode($resp);
    exit;
}

$allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $id = $_POST['id'] ?? '';
    $sabor = $_POST['sabor'] ?? '';
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;

    if (!in_array($table, $allowed_tables, true)) {
        $message = 'Tabla no válida.';
    } elseif ($qty <= 0) {
        $message = 'Cantidad debe ser mayor a 0.';
    } else {
        $conn = get_db_connection();
        if ($id !== '') {
            $sql = "UPDATE `" . $conn->real_escape_string($table) . "` SET existencia = existencia + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $iid = (int)$id;
                $stmt->bind_param('ii', $qty, $iid);
                $stmt->execute();
                if ($stmt->affected_rows >= 0) {
                    $message = "Existencia actualizada correctamente (id={$iid}). Filas afectadas: {$stmt->affected_rows}.";
                } else {
                    $message = 'No se pudo actualizar la existencia.';
                }
                $stmt->close();
            } else {
                $message = 'Error en la preparación de la consulta: ' . $conn->error;
            }
        } elseif ($sabor !== '') {
            $sql = "UPDATE `" . $conn->real_escape_string($table) . "` SET existencia = existencia + ? WHERE sabor = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('is', $qty, $sabor);
                $stmt->execute();
                if ($stmt->affected_rows >= 0) {
                    $message = "Existencia actualizada correctamente (sabor=" . htmlspecialchars($sabor) . "). Filas afectadas: {$stmt->affected_rows}.";
                } else {
                    $message = 'No se pudo actualizar la existencia.';
                }
                $stmt->close();
            } else {
                $message = 'Error en la preparación de la consulta: ' . $conn->error;
            }
        } else {
            $message = 'Debes indicar id o sabor del producto.';
        }
        $conn->close();
    }
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Actualizar existencia - Admin</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<main class="contenedor">
    <h2>Actualizar existencia (solo admin)</h2>
    <?php if ($message !== ''): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label>Tabla:
            <select name="table">
                <?php foreach ($allowed_tables as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <p>Indica el <strong>id</strong> del producto o el <strong>sabor</strong> si tu tabla no usa id numérico.</p>
        <label>ID (opcional): <input type="text" name="id"></label>
        <label>Sabor (opcional): <input type="text" name="sabor"></label>
        <label>Cantidad a sumar: <input type="number" name="qty" min="1" required></label>
        <p><button type="submit">Actualizar existencia</button></p>
    </form>

    <p><a href="index.php">Volver a la tienda</a></p>
</main>
</body>
</html>
