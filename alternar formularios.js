// Seleccionar los botones
const btnIniciarSesion = document.getElementById("btn__iniciar-sesion");
const btnRegistrarse = document.getElementById("btn__Registrarse");

// Seleccionar los formularios
const formularioLogin = document.querySelector(".formulario__login");
const formularioRegister = document.querySelector(".formulario__register");

// Función para alternar entre Login y Registro
btnIniciarSesion.addEventListener("click", () => {
    formularioLogin.style.display = "block";
    formularioRegister.style.display = "none";
    document.querySelector(".contenedor__login-register").style.left = "0"; // Mueve el contenedor a la izquierda
});

btnRegistrarse.addEventListener("click", () => {
    formularioLogin.style.display = "none";
    formularioRegister.style.display = "block";
    document.querySelector(".contenedor__login-register").style.left = "-50%"; // Mueve el contenedor a la derecha
});
