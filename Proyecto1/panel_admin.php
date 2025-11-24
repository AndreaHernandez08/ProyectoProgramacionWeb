<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

// Verificar que sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php'); 
    exit;
}

// Obtener todos los productos de todas las tablas
$mysqli = get_db_connection();
$productos = [
    'pasteles' => [],
    'bebidas' => [],
    'galletas' => [],
    'roles' => []
];

foreach ($productos as $tabla => &$items) {
    $result = $mysqli->query("SELECT id, sabor, existencia, costo FROM $tabla ORDER BY sabor");
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Stock - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-stock {
            transition: all 0.2s;
        }
        .btn-stock:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .producto-card {
            transition: all 0.3s;
        }
        .producto-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-orange-50 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Panel de Stock</h1>
                        <p class="text-sm text-gray-500">Administrador: <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Admin'); ?></p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="bg-pink-100 rounded-xl px-6 py-3">
                        <div class="text-sm text-pink-600 font-medium">Stock Total</div>
                        <div class="text-2xl font-bold text-pink-700" id="stock-total">0</div>
                    </div>
                    <div class="bg-orange-100 rounded-xl px-6 py-3">
                        <div class="text-sm text-orange-600 font-medium">⚠️ Stock Bajo</div>
                        <div class="text-2xl font-bold text-orange-700" id="stock-bajo">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje de feedback -->
        <div id="mensaje" class="hidden mb-6 p-4 rounded-xl shadow-lg"></div>

        <!-- Alerta de stock bajo -->
        <div id="alerta-stock-bajo" class="hidden bg-orange-50 border-2 border-orange-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h2 class="font-bold text-orange-800">Productos con stock bajo </h2>
            </div>
            <div id="lista-stock-bajo" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
        </div>

        <!-- Grid de categorías -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php 
            $iconos = [
                'pasteles' => '🍰',
                'bebidas' => '☕',
                'galletas' => '🍪',
                'roles' => '🥐'
            ];
            
            foreach ($productos as $tabla => $items): 
            ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span><?php echo $iconos[$tabla] . ' ' . ucfirst($tabla); ?></span>
                    <span class="text-sm font-normal text-gray-500">(<?php echo count($items); ?>)</span>
                </h2>
                
                <div class="space-y-3">
                    <?php foreach ($items as $producto): 
                        $stockBajo = $producto['existencia'] < 1;
                    ?>
                    <div class="producto-card border-2 rounded-xl p-4 <?php echo $stockBajo ? 'border-orange-300 bg-orange-50' : 'border-gray-200 hover:border-pink-300'; ?>" 
                         data-stock="<?php echo $producto['existencia']; ?>">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($producto['sabor']); ?></h3>
                                <p class="text-sm text-gray-600">$<?php echo number_format($producto['costo'], 2); ?> MXN</p>
                            </div>
                            <div class="stock-badge font-bold text-lg px-3 py-1 rounded-lg <?php echo $stockBajo ? 'bg-orange-200 text-orange-800' : 'bg-green-100 text-green-800'; ?>">
                                <?php echo $producto['existencia']; ?>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="actualizarStock('<?php echo $tabla; ?>', <?php echo $producto['id']; ?>, -1, this)" 
                                    class="btn-stock flex-1 bg-red-500 hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2"
                                    <?php echo $producto['existencia'] == 0 ? 'disabled' : ''; ?>>
                                <span>−</span> -1
                            </button>
                            <button onclick="actualizarStock('<?php echo $tabla; ?>', <?php echo $producto['id']; ?>, -5, this)" 
                                    class="btn-stock flex-1 bg-red-400 hover:bg-red-500 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2"
                                    <?php echo $producto['existencia'] < 5 ? 'disabled' : ''; ?>>
                                <span>−</span> -5
                            </button>
                            <button onclick="actualizarStock('<?php echo $tabla; ?>', <?php echo $producto['id']; ?>, 5, this)" 
                                    class="btn-stock flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                <span>+</span> +5
                            </button>
                            <button onclick="actualizarStock('<?php echo $tabla; ?>', <?php echo $producto['id']; ?>, 10, this)" 
                                    class="btn-stock flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                <span>+</span> +10
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <script>
        // Actualizar contadores al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            actualizarContadores();
        });

        async function actualizarStock(tabla, id, cantidad, btn) {
            const card = btn.closest('.producto-card');
            const stockBadge = card.querySelector('.stock-badge');
            const stockActual = parseInt(stockBadge.textContent);
            
            // Validación local
            if (stockActual + cantidad < 0) {
                mostrarMensaje('No puedes reducir el stock por debajo de 0', 'error');
                return;
            }

            // Deshabilitar botón durante la operación
            btn.disabled = true;
            
            try {
                const response = await fetch('actualizar_stock.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ tabla, id, cantidad })
                });

                const data = await response.json();

                if (data.ok) {
                    // Actualizar UI
                    stockBadge.textContent = data.nueva_existencia;
                    card.dataset.stock = data.nueva_existencia;
                    
                    // Actualizar estilos según el nuevo stock
                    const esBajo = data.nueva_existencia < ;
                    if (esBajo) {
                        card.classList.add('border-orange-300', 'bg-orange-50');
                        card.classList.remove('border-gray-200', 'hover:border-pink-300');
                        stockBadge.classList.add('bg-orange-200', 'text-orange-800');
                        stockBadge.classList.remove('bg-green-100', 'text-green-800');
                    } else {
                        card.classList.remove('border-orange-300', 'bg-orange-50');
                        card.classList.add('border-gray-200', 'hover:border-pink-300');
                        stockBadge.classList.remove('bg-orange-200', 'text-orange-800');
                        stockBadge.classList.add('bg-green-100', 'text-green-800');
                    }
                    
                    // Actualizar estados de botones
                    actualizarBotones(card, data.nueva_existencia);
                    
                    // Actualizar contadores
                    actualizarContadores();
                    
                    mostrarMensaje(`✓ Stock actualizado: ${cantidad > 0 ? '+' : ''}${cantidad}`, 'success');
                } else {
                    mostrarMensaje('Error: ' + data.error, 'error');
                }
            } catch (error) {
                mostrarMensaje('Error de conexión al actualizar stock', 'error');
            } finally {
                btn.disabled = false;
            }
        }

        function actualizarBotones(card, stock) {
            const botones = card.querySelectorAll('button');
            botones[0].disabled = stock === 0; // -1
            botones[1].disabled = stock < 5;   // -5
        }

        function actualizarContadores() {
            let total = 0;
            let bajo = 0;
            
            document.querySelectorAll('.producto-card').forEach(card => {
                const stock = parseInt(card.dataset.stock);
                total += stock;
                if (stock < 1) bajo++;
            });
            
            document.getElementById('stock-total').textContent = total;
            document.getElementById('stock-bajo').textContent = bajo;
            
            // Actualizar alerta de stock bajo
            if (bajo > 0) {
                mostrarStockBajo();
            } else {
                document.getElementById('alerta-stock-bajo').classList.add('hidden');
            }
        }

        function mostrarStockBajo() {
            const alerta = document.getElementById('alerta-stock-bajo');
            const lista = document.getElementById('lista-stock-bajo');
            lista.innerHTML = '';
            
            document.querySelectorAll('.producto-card').forEach(card => {
                const stock = parseInt(card.dataset.stock);
                if (stock < 1) {
                    const nombre = card.querySelector('h3').textContent;
                    const categoria = card.closest('.bg-white').querySelector('h2').textContent.split('(')[0].trim();
                    
                    const item = document.createElement('div');
                    item.className = 'bg-white rounded-lg p-3 border border-orange-200';
                    item.innerHTML = `
                        <div class="font-medium text-gray-800">${nombre}</div>
                        <div class="text-sm text-gray-600">${categoria}</div>
                        <div class="text-orange-600 font-bold mt-1">Stock: ${stock}</div>
                    `;
                    lista.appendChild(item);
                }
            });
            
            alerta.classList.remove('hidden');
        }

        function mostrarMensaje(texto, tipo) {
            const mensaje = document.getElementById('mensaje');
            mensaje.textContent = texto;
            mensaje.className = `mb-6 p-4 rounded-xl shadow-lg ${
                tipo === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            }`;
            mensaje.classList.remove('hidden');
            
            setTimeout(() => {
                mensaje.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>