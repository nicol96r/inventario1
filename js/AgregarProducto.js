AgregarProducto
// Obtener los elementos del DOM
const btnAgregarProducto = document.getElementById('btnAgregarProducto');
const formAgregarProducto = document.getElementById('formAgregarProducto');

// Agregar el evento click al botón
btnAgregarProducto.addEventListener('click', () => {
  // Mostrar u ocultar el formulario al hacer clic
  if (formAgregarProducto.style.display === 'none') {
    formAgregarProducto.style.display = 'block'; // Mostrar el formulario
    btnAgregarProducto.textContent = 'Cerrar Formulario'; // Cambiar el texto del botón
  } else {
    formAgregarProducto.style.display = 'none'; // Ocultar el formulario
    btnAgregarProducto.textContent = 'Agregar Producto'; // Cambiar el texto del botón
  }
});
