 <?php
header('Content-Type: application/json');
session_start();
require_once 'conexion.php'; // Asegúrate de que la conexión esté establecida

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos requeridos
    $camposRequeridos = [
        'tipoDocumento', 'documento', 'nombre', 'apellido',
        'fechaNacimiento', 'genero', 'tipoUsuario', 'email', 'password'
    ];

    $datosCompletos = true;
    $datos = [];

    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $datosCompletos = false;
            break;
        }
        $datos[$campo] = trim($_POST[$campo]);
    }

    if (!$datosCompletos) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios'
        ]);
        exit;
    }

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios WHERE email = ? OR documento = ?");
    $stmt->execute([$datos['email'], $datos['documento']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'El correo o documento ya están registrados'
        ]);
        exit;
    }

    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (tipoDocumento, documento, nombre, apellido, fechaNacimiento, genero, tipoUsuario, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    // Hash de la contraseña
    $hashedPassword = password_hash($datos['password'], PASSWORD_DEFAULT);

    // Ejecutar la consulta
    if ($stmt->execute([$datos['tipoDocumento'], $datos['documento'], $datos['nombre'], $datos['apellido'], $datos['fechaNacimiento'], $datos['genero'], $datos['tipoUsuario'], $datos['email'], $hashedPassword])) {
        // Almacena los datos del nuevo usuario en la sesión
        $_SESSION['usuario'] = [
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'documento' => $datos['documento'],
            'email' => $datos['email']
        ];
        echo json_encode(['success' => true, 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>