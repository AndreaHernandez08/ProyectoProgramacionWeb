<?php
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Tu carrito está vacío'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar compra</title>
</head>
<body>
    <h2>Confirmar compra</h2>
    <form action="receptor.php" method="POST">
        <label for="email">Correo electrónico para recibir tu ticket:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <button type="submit">Proceder al pago</button>
    </form>
</body>
</html>