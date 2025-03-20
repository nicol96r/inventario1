// archivo: script.js

const formProveedor = document.getElementById('form-proveedor');
const btnGuardarProveedor = document.getElementById('btn-guardar-proveedor');

btnGuardarProveedor.addEventListener('click', (e) => {
  e.preventDefault();
  const formData = new FormData(formProveedor);
  fetch('agregar_proveedor.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => console.log(data))
  .catch(error => console.error(error));
});
<form id="form-proveedor">
<label for="nombre-proveedor">Nombre del proveedor:</label>
<input type="text" id="nombre-proveedor" name="nombre-proveedor">
<label for="direccion-proveedor">Dirección del proveedor:</label>
<input type="text" id="direccion-proveedor" name="direccion-proveedor">
<label for="telefono-proveedor">Teléfono del proveedor:</label>
<input type="text" id="telefono-proveedor" name="telefono-proveedor">
<label for="correo-proveedor">Correo del proveedor:</label>
<input type="email" id="correo-proveedor" name="correo-proveedor">
<button id="btn-guardar-proveedor">Guardar</button>
<button id="btn-cerrar-proveedor">Cerrar</button>
</form>
// Obtener los elementos del formulario
const formProveedor = document.getElementById('form-proveedor');
const btnGuardarProveedor = document.getElementById('btn-guardar-proveedor');
const btnCerrarProveedor = document.getElementById('btn-cerrar-proveedor');

// Agregar evento al botón guardar
btnGuardarProveedor.addEventListener('click', (e) => {
  e.preventDefault();
  // Obtener los valores de los campos
  const nombreProveedor = document.getElementById('nombre-proveedor').value;
  const direccionProveedor = document.getElementById('direccion-proveedor').value;
  const telefonoProveedor = document.getElementById('telefono-proveedor').value;
  const correoProveedor = document.getElementById('correo-proveedor').value;

  // Guardar los datos en una base de datos o arreglo
  // ...
});