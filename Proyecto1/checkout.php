<?php
session_start();

// Verificar que haya productos en el carrito
if (empty($_SESSION['cart'])) {
    $_SESSION['flash_message'] = 'Tu carrito está vacío.';
    header('Location: index.php');
    exit;
}

// Verificar que el usuario esté autenticado
if (empty($_SESSION['username'])) {
    // Guardar destino y pedir login
    $_SESSION['flash_message'] = 'Debes iniciar sesión para completar la compra.';
    header('Location: login.php');
    exit;
}

// Simular pago: calcular total y vaciar carrito
$order_total = 0.0;
foreach ($_SESSION['cart'] as $it) {
    $order_total += $it['price'] * $it['qty'];
}

unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compra realizada - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <main class="contenedor">
        <h2>Compra simulada realizada</h2>
        <p>Gracias <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'cliente'; ?>, tu pago por <strong>$<?php echo number_format($order_total,2); ?></strong> fue procesado (simulado).</p>
        <p>Se ha vaciado tu carrito. <a href="index.php">Seguir comprando</a></p>
    </main>
</body>
</html>
