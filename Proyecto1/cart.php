<?php
session_start();

require_once __DIR__ . '/validar_stock.php';

// Validar stock automáticamente
$validacion = validarStockCarrito();

// Mostrar errores si los hay
if (!$validacion['ok']) {
    echo '<div class="alert alert-warning" style="background:#fff3cd; padding:15px; border-radius:8px; margin:20px 0;">';
    echo '<strong>Atención:</strong><br>';
    foreach ($validacion['errores'] as $error) {
        echo '• ' . htmlspecialchars($error['mensaje']) . '<br>';
    }
    echo '</div>';
}

// Obtener carrito desde sesión
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

require_once __DIR__ . '/auth_helpers.php';
$is_logged_in = is_logged_in();

// Calcular subtotal
$subtotal = 0.0;
foreach ($cart as $id => $item) {
    $subtotal += $item['price'] * $item['qty'];
}

// Configuración de PayPal
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // cambiar a www.paypal.com en producción
$paypal_id = "seller-facilitator@example.com"; // tu cuenta sandbox de vendedor
$return_url = "http://localhost/Proyecto1/receptor.php";
$cancel_url = "http://localhost/Proyecto1/cancelado.php";
$notify_url = "http://localhost/Proyecto1/receptor.php";
$currency = "MXN";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrito - Cachito de Cielo</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background:#fafafa; color:#333; }
        table { width:100%; border-collapse:collapse; margin-top:1rem; }
        td, th { padding:.6rem; border:1px solid #ddd; text-align:left; }
        img { width:80px; height:auto; border-radius:6px; object-fit:cover; }
        .botones { display:flex; gap:1rem; margin-top:1rem; flex-wrap:wrap; }
        .form_submit {
            padding:10px 18px;
            border:none;
            border-radius:6px;
            background:#0070ba;
            color:#fff;
            cursor:pointer;
            transition:0.2s;
        }
        .form_submit:hover { background:#005ea6; }
        .danger { background:#d9534f; }
        .danger:hover { background:#c9302c; }
        .contenedor { max-width:900px; margin:2rem auto; background:#fff; padding:1.5rem; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.05); }
        .envio { background:#f9f9f9; padding:1rem; border-radius:8px; margin:1.5rem 0; border:1px solid #ddd; }
        label { display:block; margin-top:0.5rem; font-size:0.9rem; }
        input[type="text"] { width:100%; padding:6px; border:1px solid #ccc; border-radius:4px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="contenedor">
        <h2>🛒 Tu carrito</h2>

        <?php
        // Mostrar mensaje de éxito
        if (isset($_SESSION['success_cart'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_cart'] . '</div>';
            unset($_SESSION['success_cart']);
        }

        // Mostrar advertencia
        if (isset($_SESSION['warning_cart'])) {
            echo '<div class="alert alert-warning">' . $_SESSION['warning_cart'] . '</div>';
            unset($_SESSION['warning_cart']);
        }

        // Mostrar error
        if (isset($_SESSION['error_cart'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_cart'] . '</div>';
            unset($_SESSION['error_cart']);
        }

        // Mostrar error de checkout
        if (isset($_SESSION['error_checkout'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_checkout'] . '</div>';
            unset($_SESSION['error_checkout']);
        }
        ?>

        <?php if (empty($cart)): ?>
            <p>Tu carrito está vacío. <a href="index.php">Ir a productos</a></p>
        <?php else: ?>
            <form id="updateCartForm" action="update_cart.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    <span style="color:#777">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><input type="number" name="qty[<?= htmlspecialchars($id) ?>]" value="<?= (int)$item['qty'] ?>" min="0" style="width:60px"></td>
                            <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                            <td><a href="remove_from_cart.php?id=<?= urlencode($id); ?>">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="text-align:right;font-weight:bold;">Subtotal: $<?= number_format($subtotal, 2) ?> MXN</p>

                <div class="botones">
                    <button type="submit" class="form_submit">Actualizar carrito</button>
                    <a class="form_submit" href="index.php" style="text-decoration:none;">Seguir comprando</a>
                </div>
            </form>

            <div class="botones" style="justify-content: right">
                <form action="empty_cart.php" method="post" style="display:inline;margin:0;">
                    <button type="submit" class="form_submit danger">Vaciar carrito</button>
                </form>
            </div>

            <hr style="margin:2rem 0;">

            <!-- 🚚 FORMULARIO DE ENVÍO + PAYPAL -->
            <h3>Dirección de envío</h3>
<?php if (!$is_logged_in): ?>
    <div class="envio">
        <p>Debes <a href="login.php">iniciar sesión</a> para completar la compra.</p>
    </div>
<?php else: ?>
    <form action="<?= $paypal_url ?>" method="post" class="envio">

        <!-- Configuración básica -->
        <input type="hidden" name="business" value="<?= $paypal_id ?>">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="currency_code" value="<?= $currency ?>">
        <input type="hidden" name="return" value="<?= $return_url ?>">
        <input type="hidden" name="cancel_return" value="<?= $cancel_url ?>">
        <input type="hidden" name="notify_url" value="<?= $notify_url ?>">
        <input type="hidden" name="custom" value="<?= session_id() ?>">

        <!-- Productos -->
        <?php
        $i = 1;
        foreach ($cart as $item):
        ?>
            <input type="hidden" name="item_name_<?= $i ?>" value="<?= htmlspecialchars($item['name']) ?>">
            <input type="hidden" name="item_number_<?= $i ?>" value="<?= $i ?>">
            <input type="hidden" name="amount_<?= $i ?>" value="<?= number_format($item['price'], 1, '.', '') ?>">
            <input type="hidden" name="quantity_<?= $i ?>" value="<?= (int)$item['qty'] ?>">
        <?php
        $i++;
        endforeach;
        ?>

        <!-- Datos de envío -->
        <label>Nombre completo:</label>
        <input type="text" name="first_name" required>

        <label>Dirección (calle y número):</label>
        <input type="text" name="address1" required>

        <label>Ciudad:</label>
        <input type="text" name="city" required>

        <label>Código postal:</label>
        <input type="text" name="zip" required>

        <label>País (ISO):</label>
        <input type="text" name="country" value="MX" required>

        <!-- ✉️ Campo para el correo del ticket -->
        <label>Correo electrónico para recibir el ticket:</label>
        <input type="email" name="email" required placeholder="ejemplo@gmail.com">

        <p style="margin-top:1rem; font-weight:bold;">
            Total a pagar: $<?= number_format($subtotal, 2) ?> MXN
        </p>

        <button type="submit" class="form_submit" style="background:#ffc439;color:#111;">
            💳 Pagar con PayPal (Sandbox)
        </button>
    </form>
<?php endif; ?>  <!-- ← Este cierra el if de is_logged_in -->
        <?php endif; ?>  
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>