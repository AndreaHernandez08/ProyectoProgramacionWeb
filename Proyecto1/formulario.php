<?php
session_start();
$baseUrl = 'http://localhost/Proyecto1';

// CONFIG: cambia esto por el email de tu cuenta de vendedor sandbox
$paypal_business = 'seller-facilitator@example.com';

// Si llegamos por POST con datos de envío desde el carrito, guardarlos y redirigir a PayPal automáticamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    // Esperamos campos: ship_name, ship_address1, ship_city, ship_zip, ship_country
    $ship_name = trim($_POST['ship_name'] ?? '');
    $ship_address1 = trim($_POST['ship_address1'] ?? '');
    $ship_city = trim($_POST['ship_city'] ?? '');
    $ship_zip = trim($_POST['ship_zip'] ?? '');
    $ship_country = trim($_POST['ship_country'] ?? '');

    // Guardar datos de envío en sesión para referencia posterior
    $_SESSION['checkout_delivery'] = [
        'name' => $ship_name,
        'address1' => $ship_address1,
        'city' => $ship_city,
        'zip' => $ship_zip,
        'country' => $ship_country,
    ];

    // Preparar nombre dividido en first/last (simple)
    $parts = preg_split('/\s+/', $ship_name, 2);
    $first_name = $parts[0] ?? '';
    $last_name = $parts[1] ?? '';

    // Construir formulario PayPal y auto-enviar (necesita JS auto-submit)
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Redirigiendo a PayPal...</title>
    </head>
    <body onload="document.getElementById('paypal_form').submit();">
        <p>Redirigiendo a PayPal Sandbox, por favor espera...</p>
        <form id="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="business" value="<?php echo htmlspecialchars($paypal_business); ?>">
            <input type="hidden" name="cmd" value="_cart">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="currency_code" value="USD">

            <input type="hidden" name="custom" value="<?php echo session_id(); ?>">
            <input type="hidden" name="notify_url" value="<?php echo $baseUrl; ?>/receptor.php">
            <input type="hidden" name="return" value="<?php echo $baseUrl; ?>/checkout_success.php">
            <input type="hidden" name="cancel_return" value="<?php echo $baseUrl; ?>/cart.php">

            <!-- Dirección de envío (forzar) -->
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
            <input type="hidden" name="address1" value="<?php echo htmlspecialchars($ship_address1); ?>">
            <input type="hidden" name="city" value="<?php echo htmlspecialchars($ship_city); ?>">
            <input type="hidden" name="zip" value="<?php echo htmlspecialchars($ship_zip); ?>">
            <input type="hidden" name="country" value="<?php echo htmlspecialchars($ship_country); ?>">
            <input type="hidden" name="address_override" value="1">

            <?php $i = 1; foreach ($_SESSION['cart'] as $id => $item): ?>
                <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo htmlspecialchars($item['name']); ?>">
                <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo number_format((float)$item['price'],2,'.',''); ?>">
                <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo (int)$item['qty']; ?>">
                <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo htmlspecialchars($id); ?>">
            <?php $i++; endforeach; ?>

            <noscript>
                <p>Si no eres redirigido automáticamente, pulsa el botón:</p>
                <button type="submit">Ir a PayPal</button>
            </noscript>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Si no es POST, mostramos la página normal para iniciar el proceso (por seguridad)
?>

<h1>Checkout - Pagar con PayPal (Sandbox)</h1>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Tu carrito está vacío. <a href="index.php">Ir a productos</a></p>
<?php else: ?>

    <!-- Envío de carrito a PayPal (Sandbox) - muestra manual si alguien abre la página directamente -->
    <form action="formulario.php" method="post">
        <p>Confirma tu dirección de envío antes de pagar:</p>
        <label>Nombre completo: <input type="text" name="ship_name" required></label><br>
        <label>Dirección: <input type="text" name="ship_address1" required></label><br>
        <label>Ciudad: <input type="text" name="ship_city" required></label><br>
        <label>Código postal: <input type="text" name="ship_zip" required></label><br>
        <label>País (ISO): <input type="text" name="ship_country" value="US" required></label><br>
        <button type="submit">Pagar ahora con PayPal (Sandbox)</button>
    </form>

<?php endif; ?>