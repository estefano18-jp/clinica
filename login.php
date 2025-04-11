<?php 
// Si el usuario ya ha iniciado sesión, redirigir al dashboard
session_start();

if (isset($_SESSION['usuario']) && $_SESSION['usuario']['autenticado']) {
    header('Location: views/include/dashboard.administrador.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Inicio Sesión | Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="css/estiloLogin.css" rel="stylesheet" />
    <link href="css/estilos.css" rel="stylesheet" />
    <link href="css/estiloLoginFix.css" rel="stylesheet" />
    <link href="css/estiloEyeIcon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/alertas/alertas.js"></script>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Sistema Clínica</h2>

            <!-- Formulario de inicio de sesión -->
            <form id="form-login">
                <div class="input-group">
                    <input type="text" id="inputUser" class="form-control" placeholder="Usuario" required>
                </div>
                <div class="input-group">
                    <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                    <div class="eye-icon-wrapper">
                        <i class="bi bi-eye eye-icon" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom">Iniciar Sesión</button>
            </form>

            <div class="footer">
                <a href="./views/register.php" class="btn btn-link">Registrar Nuevo Usuario</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Configuración global para SweetAlert2
        const sweetAlertOptions = {
            customClass: {
                container: 'static-swal',
                popup: 'swal-popup-fixed'
            },
            allowOutsideClick: false,
            position: 'center'
        };

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector("#form-login").addEventListener("submit", (event) => {
                event.preventDefault();

                const nomuser = document.querySelector("#inputUser").value;
                const passuser = document.querySelector("#inputPassword").value;
                
                if (!nomuser || !passuser) {
                    // Alerta para campos incompletos
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: 'warning',
                        title: 'Error',
                        text: 'Por favor, complete todos los campos.'
                    });
                    return;
                }

                // Construimos los parámetros para la solicitud
                const params = new URLSearchParams();
                params.append("operacion", "login");
                params.append("nomuser", nomuser);
                params.append("passuser", passuser);

                // Mostrar pantalla de carga
                Swal.fire({
                    ...sweetAlertOptions,
                    title: 'Ingresando',
                    text: 'Espere por favor...',
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`./controllers/usuario.controller.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(acceso => {
                        Swal.close(); // Cerrar el loading
                        
                        if (!acceso.autenticado) {
                            Swal.fire({
                                ...sweetAlertOptions,
                                icon: 'error',
                                title: 'Error',
                                text: acceso.mensaje || "Credenciales incorrectas."
                            });
                        } else {
                            // Redirigir al dashboard
                            window.location.href = './views/include/dashboard.administrador.php';
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.close(); // Cerrar el loading
                        
                        Swal.fire({
                            ...sweetAlertOptions,
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al conectar con el servidor.'
                        });
                    });
            });

            // Mostrar y ocultar contraseña
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('inputPassword');

            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassword.classList.toggle('bi-eye-slash');
            });
        });

        // Si estás usando la función AlertaSweetAlert personalizada, sobreescríbela así:
        // Esta parte es opcional, solo si usas esa función en tu código
        if (typeof AlertaSweetAlert === 'function') {
            const originalAlertaSweetAlert = AlertaSweetAlert;
            AlertaSweetAlert = function(type, title, text, icon) {
                if (type === "loading") {
                    Swal.fire({
                        ...sweetAlertOptions,
                        title: title || 'Cargando',
                        text: text || 'Por favor espere...',
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                } else if (type === "closeLoading") {
                    Swal.close();
                } else {
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: type || 'info',
                        title: title || '',
                        text: text || ''
                    });
                }
            };
        }
    </script>
</body>
</html>