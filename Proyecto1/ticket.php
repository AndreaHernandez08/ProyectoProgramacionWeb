<?php
// ==========================================
// ARCHIVO 2: ticket.php (SIN CAMBIOS)
// ==========================================
?>
<?php
session_start();

// Verificar que existan datos del ticket
if (!isset($_SESSION['ticket_data'])) {
    header('Location: carrito.php');
    exit;
}

$ticket = $_SESSION['ticket_data'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra - <?= htmlspecialchars($ticket['orden_id']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .ticket-header h1 {
            font-size: 32px;
            color: #6b4423;
            margin-bottom: 5px;
        }
        
        .ticket-header p {
            font-size: 14px;
            color: #666;
        }
        
        .orden-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .orden-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .orden-info strong {
            color: #333;
        }
        
        .cliente-info {
            margin: 20px 0;
            padding: 15px;
            background: #fff9e6;
            border-left: 4px solid #ffc439;
        }
        
        .cliente-info h3 {
            color: #6b4423;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .cliente-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .productos-table th {
            background: #6b4423;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        
        .productos-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        
        .productos-table tr:hover {
            background: #f9f9f9;
        }
        
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        
        .total-section p {
            margin: 8px 0;
            font-size: 16px;
        }
        
        .total-final {
            font-size: 24px;
            font-weight: bold;
            color: #6b4423;
            margin-top: 10px;
        }
        
        .ticket-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #333;
            color: #666;
            font-size: 12px;
        }
        
        .botones {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        
        .btn-print {
            background: #4CAF50;
            color: white;
        }
        
        .btn-print:hover {
            background: #45a049;
        }
        
        .btn-home {
            background: #6b4423;
            color: white;
        }
        
        .btn-home:hover {
            background: #543318;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #4CAF50;
            color: white;
        }
        
        /* Estilos para impresión */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                padding: 20px;
            }
            
            .botones {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        
        <!-- HEADER DEL TICKET -->
        <div class="ticket-header">
            <h1>☕ Cachito de Cielo</h1>
            <p>Cafetería hecha con amor y un pedacito de cielo</p>
            <p>Torreón, Coahuila, México</p>
        </div>
        
        <!-- INFORMACIÓN DE LA ORDEN -->
        <div class="orden-info">
            <p><strong>Número de Orden:</strong> <?= htmlspecialchars($ticket['orden_id']) ?></p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['fecha'])) ?></p>
            <p><strong>Estado del Pago:</strong> 
                <span class="status-badge">
                    <?= htmlspecialchars($ticket['payment_status']) ?>
                </span>
            </p>
            <p><strong>ID de Transacción:</strong> <?= htmlspecialchars($ticket['txn_id']) ?></p>
        </div>
        
        <!-- TABLA DE PRODUCTOS -->
        <table class="productos-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Cantidad</th>
                    <th style="text-align:right">Precio Unit.</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                foreach ($ticket['productos'] as $item): 
                    $item_total = $item['price'] * $item['qty'];
                    $subtotal += $item_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td style="text-align:center"><?= (int)$item['qty'] ?></td>
                    <td style="text-align:right">$<?= number_format($item['price'], 2) ?></td>
                    <td style="text-align:right">$<?= number_format($item_total, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- TOTAL -->
        <div class="total-section">
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?> MXN</p>
            <p><strong>Envío:</strong> $0.00 MXN</p>
            <p class="total-final">TOTAL: $<?= number_format($ticket['total'], 2) ?> MXN</p>
        </div>
        
        <!-- FOOTER -->
        <div class="ticket-footer">
            <p>✨ ¡Gracias por tu compra! ✨</p>
            <p>Este ticket es tu comprobante de compra</p>
            <p>Si tienes alguna duda, contáctanos</p>
            <p style="margin-top:10px">www.cachitodecielo.com | contacto@cachitodecielo.com</p>
        </div>
        
        <!-- BOTONES -->
        <div class="botones">
            <button onclick="window.print()" class="btn btn-print">Imprimir Ticket</button>
            <a href="index.php" class="btn btn-home">Volver al Inicio</a>
        </div>
        
    </div>
</body>
</html>