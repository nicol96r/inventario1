cargarProductos();
function registroProducto() {
        event.preventDefault(); // Evita la recarga del formulario
    const action = "registerProducto";
    const formData = new FormData();
    formData.append('action', action)
    formData.append('nombre', document.getElementById("nombre_producto").value);
    formData.append('descripcion', document.getElementById("descripcion_producto").value);
    formData.append('precio', document.getElementById("precio_producto").value);
    formData.append('cantidad', document.getElementById("cantidad_producto").value);
    formData.append('imagen', document.getElementById("imagenProducto").files[0]);
    formData.append('id_categoria', document.getElementById("categoria_producto").value);
    formData.append('id_proveedor', document.getElementById("proveedor_producto").value);

    fetch("/MiInventario1/PHP/producto.php", {
        method: "POST",
        // headers: {
        //     "Content-Type": "application/json"
        // },
        body: formData
        //  JSON.stringify({ action, nombre,descripcion, precio,cantidad,imagen, id_categoria, id_proveedor })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("¡Producto registrado exitosamente!", "success");
        } else {
            alert("Error: " + data.mensaje, "danger");
        }
    })
    .catch(error => {
        alert("Error en el registro del producto", "danger");
        console.error("Error:", error);
    });
}



function cargarProductos() {
    const action = "cargarProductos";
    fetch("/MiInventario1/PHP/producto.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action}) 
    })
    .then(response => response.json())
    .then(data => {
        let tbody = document.getElementById('listaProductos');
        tbody.innerHTML='';
        data.forEach( producto => {
            let tr =document.createElement ('tr')
            tr.innerHTML = `
            <td>${producto.id_producto}</td>
            <td>${producto.nombre_producto}</td>
            <td>${producto.descripcion_producto}</td>
            <td>${producto.precio_producto}</td>
            <td>${producto.cantidad_producto}</td>
            <td><img src="${producto.Imagen}" width="50" height="50"></td>
            <td>${producto.nombre_categoria}</td>
            <td>${producto.nombre_proveedor}</td>
            <td> 
                <button  class="btn btn-warning" onclick="abrirModalEditar(${producto.id_producto})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarProducto(${producto.id_producto})">Eliminar</button>
            </td>
            `;
            tbody.appendChild(tr);
        });
    });
}

function abrirModalEditar(id) {
    const action = "obtenerProducto";
    fetch("/MiInventario1/PHP/producto.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action, id })
    })
    .then(response => response.json())
    .then(producto => {
        document.getElementById("nombre_producto").value = producto.nombre_producto;
        document.getElementById("descripcion_producto").value = producto.descripcion_producto;
        document.getElementById("precio_producto").value = producto.precio_producto;
        document.getElementById("cantidad_producto").value = producto.cantidad_producto;
        document.getElementById("categoria_producto").value = producto.id_categoria;
        document.getElementById("proveedor_producto").value = producto.id_proveedor;
        document.getElementById("btnRegistrar").setAttribute("onclick", `editarProducto(${id})`);
        $('#productoModal').modal('show');
    });
}

function editarProducto(id) {
    const action = "editarProducto";
    const formData = new FormData();
    formData.append('action', action);
    formData.append('id', id);
    formData.append('nombre', document.getElementById("nombre_producto").value);
    formData.append('descripcion', document.getElementById("descripcion_producto").value);
    formData.append('precio', document.getElementById("precio_producto").value);
    formData.append('cantidad', document.getElementById("cantidad_producto").value);
    formData.append('id_categoria', document.getElementById("categoria_producto").value);
    formData.append('id_proveedor', document.getElementById("proveedor_producto").value);
    fetch("/MiInventario1/PHP/producto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        $('#productoModal').modal('hide');
        cargarProductos();
    });
}

function eliminarProducto(id) {
    if (!confirm("¿Seguro que deseas eliminar este producto?")) return;

    fetch("/MiInventario1/PHP/producto.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action: "eliminarProducto", id: id })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        if (data.success) {
            cargarProductos(); // Recargar la lista después de eliminar
        }
    })
    .catch(error => {
        console.error("Error al eliminar el producto:", error);
    });
}
//AgregarProducto
// Obtener los elementos del DOM
//const btnAgregarProducto = document.getElementById('btnAgregarProducto');
//const formAgregarProducto = document.getElementById('formAgregarProducto');

// Agregar el evento click al botón
//btnAgregarProducto.addEventListener('click', () => {
  // Mostrar u ocultar el formulario al hacer clic
  //  if (formAgregarProducto.style.display === 'none') {
   //   formAgregarProducto.style.display = 'block'; // Mostrar el formulario
 //     btnAgregarProducto.textContent = 'Cerrar Formulario'; // Cambiar el texto del botón
 //   } else {
   //   formAgregarProducto.style.display = 'none'; // Ocultar el formulario
  //    btnAgregarProducto.textContent = 'Agregar Producto'; // Cambiar el texto del botón
 //   }
 // })
 