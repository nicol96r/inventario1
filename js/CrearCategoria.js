

// Agregar evento de clic al menú principal
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(function(menuItem) {
        menuItem.addEventListener('click', function(event) {
            event.preventDefault();
            const submenu = menuItem.querySelector('.submenu');
            submenu.classList.toggle('show');
        });
    });
});

// Agregar evento de clic al botón de agregar categoría
document.addEventListener('DOMContentLoaded', function() {
    const agregarCategoriaButton = document.querySelector('.submenu-item[data-toggle="modal"]');
    agregarCategoriaButton.addEventListener('click', function(event) {
        event.preventDefault();
        const modal = document.querySelector('#agregar-categoria-modal');
        modal.classList.add('show');
    });
});

// Agregar evento de JavaScript

// Agregar evento de clic al botón de cerrar el modal
document.addEventListener('DOMContentLoaded', function() {
    const closeButton = document.querySelector('.close');
    closeButton.addEventListener('click', function(event) {
        event.preventDefault();
        const modal = document.querySelector('#agregar-categoria-modal');
        modal.classList.remove('show');
    });
});

// Agregar evento de submit al formulario de agregar categoría
document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.querySelector('#agregar-categoria-formulario');
    formulario.addEventListener('submit', function(event) {
        event.preventDefault();
        const nombreCategoria = document.querySelector('#nombre-categoria').value;
        const descripcionCategoria = document.querySelector('#descripcion-categoria').value;
        // Aquí puedes agregar la lógica para guardar la categoría en la base de datos
        console.log('Categoría agregada:', nombreCategoria, descripcionCategoria);
    });
});
                    