<?php
function get_db_connection() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "reposteria"; // Cambia esto por el nombre real de tu base de datos

    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }

    return $mysqli;
}
?>

