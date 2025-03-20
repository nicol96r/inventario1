<?php
include "conexion.php";

// Obtener y decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
$action = $_POST['action'] ?? $input['action'];

if ($action == "registerProducto") {
    // if (!$input || !isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['precio']) || !isset($input['id_categoria']) || !isset($input['id_proveedor'])) {
    //     echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    //     exit;
    // }

    $nombre = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $precio = htmlspecialchars($_POST['precio']);
    $id_categoria = htmlspecialchars ($_POST ['id_categoria']);
    $id_proveedor = htmlspecialchars ($_POST ['id_proveedor']);

        // Manejar la carga de la imagen
        $target_dir = "../imagenes/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // VERIFICA SI EL ARCHIVO YA EXISTE
        if (file_exists($target_file)){
            echo json_encode(['success' => false , 'menssage' => 'Lo sentiento, la imagen ya existe ']);
            $uploadOk = 0;
        }
        // permite ciertos formatos de la imagen
        if ($imageFileType !="jpg" &&  $imageFileType !="png" &&  $imageFileType !="jpeg" && $imageFileType !="git" ){
        echo json_encode(['success' => false,  'menssage' => 'Lo siento  , lo se acepta archivos jpg,png,jprg,git.']);
        $uploadOk = 0;
        }

    // Verificar si $uploadOk está configurado a 0 por un error
    if ($uploadOk == 0) {
        echo json_encode(['success' => false, 'message' => 'Lo siento, tu archivo no fue subido.']);
    // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO producto (nombre_producto , imagen, descripcion_producto, precio_producto , id_categoria ,id_proveedor) 
                                    VALUES (:nombre, :imagen, :descripcion, :precio , :id_categoria , :id_proveedor )");
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':imagen', $target_file);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':precio', $precio);
                $stmt->bindParam(':id_categoria', $id_categoria);
                $stmt->bindParam(':id_proveedor', $id_proveedor);
                $stmt->execute();

                // Enviar respuesta JSON de éxito
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo json_encode(['success' => false, 'message' => 'El producto ya está registrado']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al registrar el producto: ' . $e->getMessage()]);
                }
            }
        }
    }
}



//mostrar productos
if ($action == "cargarProductos") {
    try {
        // Obtener los datos del usuario desde la base de datos
        $stmt = $pdo->prepare ("SELECT 
        categoria.nombre_categoria,
        proveedor.nombre_proveedor,
        producto.*
    FROM 
        producto
    JOIN 
        categoria ON producto.id_categoria = categoria.id_categoria
    JOIN 
        proveedor ON producto.id_proveedor = proveedor.id_proveedor;");
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    }catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener productos:' . $e->getMessage()]);
    }    
}


if ($action == "obtenerProducto") {
    $id = $input['id'];
    $stmt = $pdo->prepare("SELECT * FROM producto WHERE id_producto = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

if ($action == "editarProducto") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];

    $stmt = $pdo->prepare("UPDATE productos SET nombre_producto=?, descripcion_producto=?, precio_producto=?, id_categoria=?, id_proveedor=? WHERE id_producto=?");
    $stmt->execute([$nombre, $descripcion, $precio, $id_categoria, $id_proveedor, $id]);
    echo json_encode(["success" => true, "mensaje" => "Producto actualizado"]);
    exit;
}


elseif ($action == "eliminarProducto") {

    $id = $input['id'];

    try {
            $stmt = $pdo->prepare("DELETE FROM producto WHERE id_Producto = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
    } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
    }

}
?>