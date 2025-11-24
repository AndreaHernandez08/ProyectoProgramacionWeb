<?php
session_start();

$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id !== '' && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

header('Location: cart.php');
exit;
