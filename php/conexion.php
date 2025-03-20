<?php

//fragmento de conexio1.php
// <?php
//$conexion = mysqli_connect("localhost", "root", "", "inventario");
//if($conexion){
//}else{
//    echo 'No se pudo conectar a la base de datos Intente mas tarde';
//

header('Content-Type: application/json');
session_start();
require_once 'conexion.php'; // Asegúrate de que la conexión esté establecida

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Definir los campos requeridos
        $camposRequeridos = [
            'tipoDocumento', 'documento', 'nombre', 'apellido',
            'fechaNacimiento', 'genero', 'tipoUsuario', 'email', 'password'
        ];

        $datos = [];
        $datosCompletos = true;

        // Validar que todos los campos estén presentes y no vacíos
        foreach ($camposRequeridos as $campo) {
            if (empty($_POST[$campo])) {
                $datosCompletos = false;
                break;
            }
            $datos[$campo] = htmlspecialchars(trim($_POST[$campo])); // Limpiar los datos
        }

        if (!$datosCompletos) {
            echo json_encode([
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ]);
            exit;
        }

        // Validar formato del correo electrónico
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'El formato del correo es inválido'
            ]);
            exit;
        }

        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios WHERE email = :email OR documento = :documento");
        $stmt->execute([
            ':email' => $datos['email'],
            ':documento' => $datos['documento']
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'El correo o documento ya están registrados'
            ]);
            exit;
        }

        // Hash de la contraseña
        $hashedPassword = password_hash($datos['password'], PASSWORD_DEFAULT);

        // Preparar e insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (tipoDocumento, documento, nombre, apellido, fechaNacimiento, genero, tipoUsuario, email, password)
                VALUES (:tipoDocumento, :documento, :nombre, :apellido, :fechaNacimiento, :genero, :tipoUsuario, :email, :password)";
        $stmt = $pdo->prepare($sql);

        $resultado = $stmt->execute([
            ':tipoDocumento' => $datos['tipoDocumento'],
            ':documento' => $datos['documento'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':fechaNacimiento' => $datos['fechaNacimiento'],
            ':genero' => $datos['genero'],
            ':tipoUsuario' => $datos['tipoUsuario'],
            ':email' => $datos['email'],
            ':password' => $hashedPassword
        ]);

        if ($resultado) {
            // Guardar datos en la sesión
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
} catch (Exception $e) {
    // Manejo de errores generales
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()
    ]);
}
?>
