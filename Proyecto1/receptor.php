<?php
// ==========================================
// ARCHIVO: receptor.php (MODIFICADO)
// ==========================================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/validar_stock.php';

// Validación final de stock
$validacion = validarStockCarrito();

if (!$validacion['ok']) {
    $_SESSION['error_checkout'] = "No se pudo completar la compra. Algunos productos no tienen stock suficiente.";
    header('Location: carrito.php');
    exit;
}

// PHPMailer: si usas composer o manual
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    require_once __DIR__ . '/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/SMTP.php';
    require_once __DIR__ . '/PHPMailer/Exception.php';
}

/**
 * Ajustes SMTP: reemplaza por tus datos reales antes de usar
 */
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USER = 'tu_correo@gmail.com';              
const SMTP_PASS = 'tu_contraseña_de_aplicación';      
const SMTP_FROM = 'tu_correo@gmail.com';
const SMTP_FROM_NAME = 'Cachito de Cielo';
const SMTP_DEBUG = false;

// PayPal REST API
const PAYPAL_MODE = 'sandbox'; 
const PAYPAL_CLIENT_ID = 'YOUR_PAYPAL_CLIENT_ID';
const PAYPAL_SECRET = 'YOUR_PAYPAL_SECRET';

/**
 * Función para enviar correo con PHPMailer
 */
function enviarReciboPorCorreo(string $toEmail, string $subject, string $htmlBody): bool {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        if (defined('SMTP_DEBUG') && SMTP_DEBUG) {
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'error_log';
        }
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer error: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica un paymentId/PayerID con la API REST de PayPal.
 */
function verifyPayPalPayment(string $paymentId, string $payerId): array {
    if (PAYPAL_CLIENT_ID === 'YOUR_PAYPAL_CLIENT_ID' || PAYPAL_SECRET === 'YOUR_PAYPAL_SECRET') {
        return ['ok' => false, 'payer_email' => null, 'raw' => 'No PayPal credentials configured'];
    }

    $base = PAYPAL_MODE === 'live' ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';

    // Obtener token
    $tokenCh = curl_init($base . '/v1/oauth2/token');
    curl_setopt_array($tokenCh, [
        CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Accept-Language: en_US']
    ]);
    $tokenRes = curl_exec($tokenCh);
    if ($tokenRes === false) {
        $err = curl_error($tokenCh);
        curl_close($tokenCh);
        error_log('PayPal token curl error: ' . $err);
        return ['ok' => false, 'payer_email' => null, 'raw' => 'curl token error: ' . $err];
    }
    curl_close($tokenCh);
    $tokenJson = json_decode($tokenRes, true);
    if (!isset($tokenJson['access_token'])) {
        error_log('PayPal token response invalid: ' . $tokenRes);
        return ['ok' => false, 'payer_email' => null, 'raw' => $tokenRes];
    }
    $accessToken = $tokenJson['access_token'];

    // Obtener detalles del pago
    $detailsCh = curl_init($base . '/v1/payments/payment/' . rawurlencode($paymentId));
    curl_setopt_array($detailsCh, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]
    ]);
    $detailsRes = curl_exec($detailsCh);
    if ($detailsRes === false) {
        $err = curl_error($detailsCh);
        curl_close($detailsCh);
        error_log('PayPal details curl error: ' . $err);
        return ['ok' => false, 'payer_email' => null, 'raw' => 'curl details error: ' . $err];
    }
    curl_close($detailsCh);
    $detailsJson = json_decode($detailsRes, true);

    $state = $detailsJson['state'] ?? null;
    $payerEmail = $detailsJson['payer']['payer_info']['email'] ?? ($detailsJson['payer']['email'] ?? null);

    $ok = false;
    if ($state === 'approved') {
        $ok = true;
    } else {
        if (isset($detailsJson['transactions']) && is_array($detailsJson['transactions'])) {
            foreach ($detailsJson['transactions'] as $t) {
                if (isset($t['related_resources']) && is_array($t['related_resources'])) {
                    foreach ($t['related_resources'] as $rr) {
                        if (isset($rr['sale']['state']) && strtolower($rr['sale']['state']) === 'completed') {
                            $ok = true;
                        }
                    }
                }
            }
        }
    }

    return ['ok' => $ok, 'payer_email' => $payerEmail, 'raw' => $detailsJson];
}

// ==========================================
// 🎫  GENERAR TICKET
// ==========================================

$allowed_tables = ['pasteles', 'bebidas', 'galletas', 'roles'];
$conexion = get_db_connection();

// Obtener datos de PayPal o formulario
$payment_status = 'Completado'; // Por defecto

// Estados
$payment_status = 'Completado';
$txn_id = 'COMPRA-' . time();

// Verificar que hay carrito
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("❌ No hay productos en el carrito.");
}

$cart = $_SESSION['cart'];

// Calcular total
$total = 0.0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}

// Generar número de orden único
$orden_id = 'CC-' . strtoupper(substr(md5(uniqid()), 0, 8));

// 🔹 ACTUALIZAR EXISTENCIAS EN LA BASE DE DATOS
foreach ($cart as $id => $item) {
    $tabla = $item['category'] ?? '';
    $cantidad = isset($item['qty']) ? (int)$item['qty'] : 1;
    $sabor = $item['name'] ?? '';

    if (!in_array($tabla, $allowed_tables, true)) {
        error_log("❌ Tabla inválida: " . $tabla);
        continue;
    }

    // Verificar existencia
    $sql_check = "SELECT existencia FROM `{$tabla}` WHERE sabor = ?";
    $stmt_check = $conexion->prepare($sql_check);
    if (!$stmt_check) {
        error_log("Error al preparar SELECT: " . $conexion->error);
        continue;
    }
    $stmt_check->bind_param('s', $sabor);
    $stmt_check->execute();
    $res = $stmt_check->get_result();

    if (!$res || $res->num_rows === 0) {
        error_log("⚠️ No se encontró el sabor '{$sabor}' en la tabla '{$tabla}'.");
        $stmt_check->close();
        continue;
    }

    $row = $res->fetch_assoc();
    $existencia_actual = (int)$row['existencia'];
    $stmt_check->close();

    if ($existencia_actual < $cantidad) {
        error_log("🚫 Stock insuficiente para '{$sabor}' (disponible: {$existencia_actual}, pedido: {$cantidad}).");
        continue;
    }

    // Actualizar existencia
    $sql_update = "UPDATE `{$tabla}` SET existencia = GREATEST(existencia - ?, 0) WHERE sabor = ?";
    $stmt_update = $conexion->prepare($sql_update);
    if (!$stmt_update) {
        error_log("Error al preparar UPDATE: " . $conexion->error);
        continue;
    }
    $stmt_update->bind_param('is', $cantidad, $sabor);
    $stmt_update->execute();
    $stmt_update->close();
}

// 🎫 GUARDAR DATOS DEL TICKET EN LA SESIÓN
$_SESSION['ticket_data'] = [
    'orden_id' => $orden_id,
    'fecha' => date('Y-m-d H:i:s'),
    'payment_status' => $payment_status,
    'txn_id' => $txn_id,
    'cliente' => [
        'nombre' => $first_name,           
        'email' => $payer_email,           
        'direccion' => $address,           
        'ciudad' => $city,                 
        'codigo_postal' => $zip,           
        'pais' => $country                 
    ],
    'productos' => $cart,
    'total' => $total
];

// Vaciar el carrito
unset($_SESSION['cart']);  // ← FALTABA EL PUNTO Y COMA AQUÍ

// Redirigir al ticket
header('Location: ticket.php');
exit;