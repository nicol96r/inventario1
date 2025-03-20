<?php
// Establecer la cabecera para JSON
header('Content-Type: application/json');

// Obtener los datos enviados por el frontend
$data = json_decode(file_get_contents("php://input"));

// Verificar que los datos estén completos
if (isset($data[0]) && isset($data[1]) && isset($data[2])) {
    $username = $data[0];
    $email = $data[1];
    $password = $data[2];

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

    // Conectar a la base de datos (asegúrate de cambiar estos valores a tus propios datos)
    $servername = "localhost";
    $dbname = "inventario";
    $user = "usuario";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbuser, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si el usuario ya existe en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "mensaje" => "El correo electrónico ya está registrado."]);
        } else {
            // Insertar el nuevo usuario en la base de datos
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Asegúrate de encriptar la contraseña
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (:username, :email, :password)");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedPassword);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "mensaje" => "¡Registro exitoso!"]);
            } else {
                echo json_encode(["success" => false, "mensaje" => "Hubo un error al registrar al usuario."]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "mensaje" => "Error en la base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "mensaje" => "Datos incompletos."]);
}
?>
