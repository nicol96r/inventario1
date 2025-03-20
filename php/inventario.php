<?php
    include 'conexionn.php';

    $sql = "SELECT p.id, p.imagen, p.nombre, p.descripcion, p.precio, p.cantidad, c.nombre AS categoria, pr.nombre AS proveedor
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            INNER JOIN proveedores pr ON p.proveedor_id = pr.id";

    $result = $conn->query($sql);
?>

<table borde="1">
    <tr>
        <th>ID</th>
        <th>Imagen</th>
        <th>Nombre del producto</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Categoría</th>
        <th>Proveedor</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><img src="<?php echo $row['imagen']; ?>" width="50" height="50"></td>
        <td><?php echo $row['nombre']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td><?php echo $row['precio']; ?></td>
        <td><?php echo $row['cantidad']; ?></td>
        <td><?php echo $row['categoria']; ?></td>
        <td><?php echo $row['proveedor']; ?></td>
        <td>
            <a href="editar_producto.php?id=<?php echo $row['id']; ?>">Editar</a>
            <a href="eliminar_producto.php?id=<?php echo $row['id']; ?>">Eliminar</a>
        </td>
    </tr>
</table>
<?php