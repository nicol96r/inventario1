<?php
include "conexion.php";

// Iniciar o reanudar la sesión
session_start();

// Obtener y decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó ninguna acción']);
    exit;
}

$action = $input['action'];

if ($action == "register") {
    // Obtener y decodificar la entrada JSON
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input || !isset($input['nombre']) || !isset($input['apellido'])     || !isset($input['telefono'])|| !isset($input['email']) || !isset($input['password']) || !isset($input['direccion'])|| !isset($input['rol'])){
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }


    $nombre = htmlspecialchars($input['nombre']);
    $apellido = htmlspecialchars($input['apellido']);
    $telefono = htmlspecialchars($input['telefono']);
    $email = htmlspecialchars($input['email']);
    $password = htmlspecialchars($input['password']);
    $direcccion = htmlspecialchars($input['direccion']);
    $rol = htmlspecialchars($input['rol']);
    // // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
    
        $stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, apellido_usuario, email_usuario, password_usuario, telefono_usuario, direccion_usuario, rol_usuario) 
                               VALUES (:nombre, :apellido, :email, :password, :telefono, :direccion, :rol)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();

        // Enviar respuesta JSON de éxito
        echo json_encode(['success' => true]);

    }  catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'El usuario o email ya está registrado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . $e->getMessage()]);
    }
}
}elseif ($action == "login") {
    // Obtener y decodificar la entrada JSON
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input || !isset($input['username']) || !isset($input['password'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $nombre = htmlspecialchars($input['username']);
    $password = htmlspecialchars($input['password']);

    try {
        // Preparar la consulta de selección para comprobar el usuario
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user){
            $storedPassword=$user['password_usuario'];
            // Verificar la contraseña hasheada
            if (password_verify($password, $storedPassword)) {
                // Iniciar sesión y guardar los datos del usuario en la sesión
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_name'] = $user['nombre_usuario'];
                // $_SESSION['user_role'] = $user['rol']; // Asumiendo que tienes un campo 'rol' en tu tabla

                echo json_encode(['success' => true , 'message' => 'Inicio de sección exitoso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuario o Contraseña incorrecta']);
            }
        }else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
        
    }catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión: ' . $e->getMessage()]);
    }
}
//mostrar ferfil
elseif ($action == "getPerfil") {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        exit;
    }
    $userId = $_SESSION['user_id'];
    try {
        // Obtener los datos del usuario desde la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Id_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo json_encode([
                'success' => true, 
                'nombre' => $user['nombre_usuario'],
                'apellido'=> $user['apellido_usuario'],
                'email'=> $user['email_usuario'],
                'telefono'=> $user['telefono_usuario'],
                'direccion'=> $user['direccion_usuario'],
                'rol'=> $user['rol_usuario']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los datos del perfil: ' . $e->getMessage()]);
    }
}

elseif ($action == "modificar") {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        exit;
    }

    $input=json_decode(file_get_contents("php://input"),true);
    if (!$input || !isset($input['nombre']) || !isset($input['apellido']) || !isset($input['telefono'])|| !isset($input['email']) || !isset($input['direccion'])){
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    $nombre = htmlspecialchars($input['nombre']);
    $apellido = htmlspecialchars($input['apellido']);
    $telefono = htmlspecialchars($input['telefono']);
    $email = htmlspecialchars($input['email']);
    $direccion = htmlspecialchars($input['direccion']);
    $rol = htmlspecialchars($input['rol']);

    try {
        $stmt = $pdo->prepare("UPDATE usuario SET nombre_usuario =:nombre, apellido_usuario=:apellido, email_usuario =:email, telefono_usuario=:telefono, direccion_usuario=:direccion , rol_usuario = :rol WHERE id_usuario = :id");
        // UPDATE usuario SET nombre_usuario ="pepito", apellido_usuario="perez", email_usuario ="p@gmail.com", telefono_usuario="1212123452", direccion_usuario="sogamoso" WHERE id_usuario = 1;
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();

        // Enviar respuesta JSON de éxito
        echo json_encode(['success' => true, 'message' => 'Usuario modificado exitosamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al modificar el usuario: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>

