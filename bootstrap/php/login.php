<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";  // IMPORTANTE: Debe estar vacío
$dbname = "usuarios_db";

// Habilitar CORS para desarrollo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // Crear conexión SIN contraseña
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname", 
        $username,
        ""  // Contraseña vacía explícitamente
    );
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del POST
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    // Debug
    error_log("Datos recibidos: " . print_r($data, true));

    // Verificar datos
    if (!isset($data['correo']) || !isset($data['password'])) {
        throw new Exception("Faltan datos de login");
    }

    $correo = $data['correo'];
    $password = $data['password'];

    // Buscar usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($password, $usuario['contraseña'])) {
            echo json_encode([
                "success" => true,
                "mensaje" => "Inicio de sesión exitoso"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "mensaje" => "Contraseña incorrecta"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "Usuario no encontrado"
        ]);
    }

} catch(PDOException $e) {
    error_log("Error PDO: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "mensaje" => "Error de conexión a la base de datos"
    ]);
} catch(Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "mensaje" => "Error: " . $e->getMessage()
    ]);
}

$conn = null;
?> 