<?php
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // Cambia a www.paypal.com en producción
$paypal_id = "sb-e8s43547307028@business.example.com"; // tu correo de sandbox (vendedor)
$return_url = "http://localhost/Proyecto1/index.php"; // Página de retorno tras el pago
$notify_url = "http://localhost/Proyecto1/receptor.php"; // IPN listener (el que ya tienes)
$currency = "MXN";
?>
