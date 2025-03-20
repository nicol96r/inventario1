<?php
try {
    $host = '127.0.0.1'; // Cambia esto si tu base de datos está en otro host
    $dbname = 'inventario'; // Asegúrate de que este es el nombre de la base de datos que tienes en mysql
    $username = 'root'; // Cambia esto si tu usuario es diferente
    $password = ''; // Esta es la contraseña del root de mysql entonces ten cuidado
    $charset = 'utf8mb4';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión a la base de datos");
}
?>