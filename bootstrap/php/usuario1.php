<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios_db";

// Habilitar CORS para desarrollo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // Debug: Mostrar que estamos intentando conectar
    error_log("Intentando conectar a la base de datos...");
    
    // Crear conexión
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Debug: Mostrar conexión exitosa
    error_log("Conexión exitosa a la base de datos");

    // Obtener datos del POST
    $input = file_get_contents("php://input");
    error_log("Datos recibidos: " . $input);
    
    $data = json_decode($input, true);
    error_log("Datos decodificados: " . print_r($data, true));

    if ($data === null) {
        throw new Exception("Datos no válidos: " . $input);
    }

    // Verificar que los datos necesarios estén presentes
    if (!isset($data[0]) || !isset($data[1]) || !isset($data[2])) {
        throw new Exception("Faltan datos requeridos. Datos recibidos: " . print_r($data, true));
    }

    $nombre = $data[0];
    $correo = $data[1];
    $contraseña = password_hash($data[2], PASSWORD_DEFAULT);

    // Debug: Mostrar datos a insertar
    error_log("Intentando insertar: Nombre=$nombre, Correo=$correo");

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (:nombre, :correo, :contrasena)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $contraseña);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "mensaje" => "Usuario registrado correctamente"
        ]);
    } else {
        throw new Exception("Error al registrar el usuario");
    }

} catch(PDOException $e) {
    error_log("Error de PDO: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "mensaje" => "Error de base de datos: " . $e->getMessage(),
        "tipo_error" => "PDO"
    ]);
} catch(Exception $e) {
    error_log("Error general: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "mensaje" => "Error: " . $e->getMessage(),
        "tipo_error" => "General"
    ]);
}

// Validar que los campos no estén vacíos
if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "mensaje" => "Todos los campos son obligatorios."]);
    exit;
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "mensaje" => "El correo electrónico no es válido."]);
    exit;
}
$conn = null;
?> 