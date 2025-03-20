<?php
include "conexion.php";

// Obtener y decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
$action = $input['action'];

if ($action == "addCategory") {
    if (!$input || !isset($input['nombre'])) {
        echo json_encode(['success' => false, 'alert' => 'Datos inválidos']);
        exit;
    }

    $nombre = htmlspecialchars($input['nombre']);

    try {
        $stmt = $pdo->prepare("INSERT INTO categoria (nombre_categoria) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        // Enviar respuesta JSON de éxito
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'alert' => 'La categoría ya existe']);
        } else {
            echo json_encode(['success' => false, 'alert' => 'Error al agregar la categoría: ' . $e->getMessage()]);
        }
    }
}



try {

    $stmt = $pdo->query("SELECT * FROM categoria");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($categorias);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
}
?>
