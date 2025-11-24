<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado
require_once __DIR__ . '/db.php';

function sendPurchaseReceipt($email, $cart, $total, $transaction_id = null) {
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_correo@gmail.com'; // 🔹 tu correo Gmail
        $mail->Password = 'tu_token_de_aplicacion'; // 🔹 usa token de aplicación, no contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('tu_correo@gmail.com', 'Repostería Dulce Sabor');
        $mail->addAddress($email);

        // Asunto
        $mail->Subject = '🧁 Recibo de compra - Repostería Dulce Sabor';

        // Cuerpo del correo
        $html = "<h2>Gracias por tu compra 🩷</h2>";
        $html .= "<p>Tu pedido fue procesado con éxito. Aquí tienes tu ticket:</p>";

        if ($transaction_id) {
            $html .= "<p><strong>Transacción PayPal:</strong> {$transaction_id}</p>";
        }

        $html .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
                    <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>";

        $total_final = 0;
        foreach ($cart as $item) {
            $subtotal = $item['qty'] * $item['price'];
            $total_final += $subtotal;
            $html .= "<tr>
                        <td>{$item['name']}</td>
                        <td>{$item['qty']}</td>
                        <td>$" . number_format($item['price'], 2) . "</td>
                        <td>$" . number_format($subtotal, 2) . "</td>
                      </tr>";
        }

        $html .= "</table><p><strong>Total: $" . number_format($total_final, 2) . "</strong></p>";
        $html .= "<p>Esperamos verte pronto 💕</p>";

        $mail->isHTML(true);
        $mail->Body = $html;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
?>

