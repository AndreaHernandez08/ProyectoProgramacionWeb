<?php
session_start();

// Guardar datos del cliente en la sesión
$_SESSION['datos_cliente'] = [
    'nombre' => $_POST['first_name'] ?? 'Cliente',
    'email' => $_POST['email'] ?? '',
    'direccion' => $_POST['address1'] ?? '',
    'ciudad' => $_POST['city'] ?? '',
    'codigo_postal' => $_POST['zip'] ?? '',
    'pais' => $_POST['country'] ?? 'MX'
];

// Obtener carrito
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0.0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

// Configuración de PayPal
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
$paypal_id = "seller-facilitator@example.com";
$return_url = "http://localhost/Proyecto1/receptor.php";
$cancel_url = "http://localhost/Proyecto1/cancelado.php";
$notify_url = "http://localhost/Proyecto1/receptor.php";
$currency = "MXN";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redirigiendo a PayPal...</title>
</head>
<body>
    <p>Redirigiendo a PayPal...</p>
    
    <form id="paypalForm" action="<?= $paypal_url ?>" method="post">
        <input type="hidden" name="business" value="<?= $paypal_id ?>">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="currency_code" value="<?= $currency ?>">
        <input type="hidden" name="return" value="<?= $return_url ?>">
        <input type="hidden" name="cancel_return" value="<?= $cancel_url ?>">
        <input type="hidden" name="notify_url" value="<?= $notify_url ?>">
        <input type="hidden" name="custom" value="<?= session_id() ?>">

        <?php
        $i = 1;
        foreach ($cart as $item):
        ?>
            <input type="hidden" name="item_name_<?= $i ?>" value="<?= htmlspecialchars($item['name']) ?>">
            <input type="hidden" name="item_number_<?= $i ?>" value="<?= $i ?>">
            <input type="hidden" name="amount_<?= $i ?>" value="<?= number_format($item['price'], 2, '.', '') ?>">
            <input type="hidden" name="quantity_<?= $i ?>" value="<?= (int)$item['qty'] ?>">
        <?php
        $i++;
        endforeach;
        ?>
    </form>

    <script>
        // Auto-submit al cargar la página
        document.getElementById('paypalForm').submit();
    </script>
</body>
</html>