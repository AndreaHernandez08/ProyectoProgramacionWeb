<?php
session_start();
// Vaciar el carrito y redirigir a cart.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    unset($_SESSION['cart']);
    $_SESSION['flash_message'] = 'Carrito vaciado correctamente.';
}
header('Location: cart.php');
exit;
