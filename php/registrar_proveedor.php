<?php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    // Validar datos requeridos
    $campos_requeridos = ['nombre', 'nit', 'telefono', 'email', 'categoria'];
    foreach ($campos_requeridos as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            throw new Exception("El campo {$campo} es requerido.");
        }
    }

    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $nit = $_POST['nit'];
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'];
    $email = $_POST['email'] ?? '';

    // Validar que el NIT/documento no exista
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE nit = ?");
    $stmt->execute([$nit]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Ya existe un proveedor con este NIT/documento.');
    }



    // Insertar el proveedor
    $sql = "INSERT INTO proveedores (nombre, nit, direccion, telefono, email, id_categoria, estado, observaciones, fecha_registro) 
            VALUES (:nombre, :nit, :direccion, :telefono, :email, :id_categoria, :estado, :observaciones, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':nit' => $nit,
        ':direccion' => $direccion,
        ':telefono' => $telefono,
        ':email' => $email,
       
    ]);

    $id_proveedor = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Proveedor registrado correctamente',
        'id_proveedor' => $id_proveedor
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 