  <?php
                    // Cargar categorías desde la base de datos
                    $categorias = $conn->query("SELECT * FROM categorias");
                    while ($categoria = $categorias->fetch_assoc()) {
                        echo "<option value='" . $categoria['id'] . "'>" . $categoria['nombre'] . "</option>";
                    }
                ?>
            </select><br><br>
            
            <label for="proveedor-producto">Proveedor del producto:</label>
            <select id="proveedor-producto" name="proveedor-producto">
                <option value="">Seleccione un proveedor</option>
                <?php
                    // Cargar proveedores desde la base de datos
                    $proveedores = $conn->query("SELECT * FROM proveedores");
                    while ($proveedor = $proveedores->fetch_assoc()) {
                        echo "<option value='" . $proveedor['id'] . "'>" . $proveedor['nombre'] . "</option>";
                    }
                ?>