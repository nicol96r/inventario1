        // Ocultar el formulario de login inicialmente
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            if (!localStorage.getItem('usuarioRegistrado')) {
                loginContainer.classList.add('hidden');
            }
        });

        function verificarLogin() {
            let username = document.getElementById("username").value;
            let password = document.getElementById("password").value;
            let mensaje = document.getElementById("message");

            console.log("Intentando login con:", username);

            fetch("bootstrap/php/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    correo: username,
                    password: password
                })
            })
            .then(response => {
                console.log("Status:", response.status);
                return response.json();
            })
            .then(data => {
                console.log("Respuesta:", data);
                if (data.success) {
                    mensaje.style.color = "green";
                    mensaje.textContent = "¡Inicio de sesión exitoso!";
                    setTimeout(() => {
                        window.location.href = "Interfaz.html";  // nombre de la pagina    
                    }, 1000);
                } else {
                    mensaje.style.color = "red";
                    mensaje.textContent = data.mensaje;
                }
            })
            .catch(error => {
                console.error('Error detallado:', error);
                mensaje.style.color = "red";
                mensaje.textContent = "Error al conectar con el servidor";
            });

            return false;
        }

        function registrarUsuario() {
            const nombre = document.getElementById("registrousuario").value;
            const correo = document.getElementById("registroEmail").value;
            const password = document.getElementById("registroContraseña").value;

            console.log("Intentando registrar:", { nombre, correo });

            fetch("MiInventario1/php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify([nombre, correo, password])
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Respuesta del servidor:", data);
                if (data.success) {
                    // Guardar estado de registro y mostrar el formulario de login
                    localStorage.setItem('usuarioRegistrado', 'true');
                    document.querySelector('.login-container').classList.remove('hidden');
                    alert("Usuario registrado correctamente. Ahora puedes iniciar sesión.");
                } else {
                    alert("Error: " + data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error detallado:', error);
                alert("Error al conectar con el servidor. Revisa la consola para más detalles.");
            });

            return false;
        }
