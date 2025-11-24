<?php
require_once __DIR__ . '/db.php';

$mysqli = get_db_connection();

$queries = [
    // Tabla de productos
    "CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(100) UNIQUE DEFAULT NULL,
        nombre VARCHAR(255) NOT NULL,
        precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        stock INT NOT NULL DEFAULT 0,
        imagen VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Tabla de pedidos
    "CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
        session_id VARCHAR(128) DEFAULT NULL,
        total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        currency VARCHAR(10) DEFAULT 'USD',
        status VARCHAR(50) DEFAULT 'pending',
        payer_email VARCHAR(255) DEFAULT NULL,
        shipping_name VARCHAR(255) DEFAULT NULL,
        shipping_address VARCHAR(255) DEFAULT NULL,
        shipping_city VARCHAR(100) DEFAULT NULL,
        shipping_zip VARCHAR(50) DEFAULT NULL,
        shipping_country VARCHAR(10) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Tabla de items de pedido
    "CREATE TABLE IF NOT EXISTS pedido_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        producto_id INT DEFAULT NULL,
        producto_sku VARCHAR(100) DEFAULT NULL,
        nombre VARCHAR(255) NOT NULL,
        precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        cantidad INT NOT NULL DEFAULT 1,
        subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $sql) {
    if (!$mysqli->query($sql)) {
        echo "Error al ejecutar query: " . $mysqli->error . "\n";
    }
}

echo "Tablas creadas/aseguradas correctamente.\n";

$mysqli->close();

?>
