<?php
// Si el usuario ya ha iniciado sesión, redirigir al dashboard
session_start();

if (isset($_SESSION['usuario']) && $_SESSION['usuario']['autenticado']) {
    header('Location: include/dashboard.administrador.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Registro de Usuario | Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="../css/estiloLogin.css" rel="stylesheet" />
    <link href="../css/estilos.css" rel="stylesheet" />
    <link href="../css/estiloLoginFix.css" rel="stylesheet" />
    <link href="../css/estiloEyeIcon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/alertas/alertas.js"></script>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Registrarse</h2>
            <div id="message" class="mb-3"></div>

            <!-- Formulario de registro -->
            <form id="form-register" autocomplete="off">
                <div class="input-group">
                    <input type="text" id="nrodocumento" name="nrodocumento" class="form-control" 
                           placeholder="Número de Documento" required pattern="\d{8}" 
                           title="El número de documento debe tener 8 dígitos" maxlength="8">
                </div>
                <div class="input-group">
                    <input type="password" id="passuser" name="passuser" class="form-control" 
                           placeholder="Contraseña" required>
                    <div class="eye-icon-wrapper">
                        <i class="bi bi-eye eye-icon" id="togglePassword1"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="password" id="confirmar_passuser" name="confirmar_passuser" class="form-control" 
                           placeholder="Confirmar Contraseña" required>
                    <div class="eye-icon-wrapper">
                        <i class="bi bi-eye eye-icon" id="togglePassword2"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom">Registrar</button>
            </form>

            <div class="footer">
                <a href="../login.php" class="btn btn-link">¿Ya tienes cuenta? Iniciar sesión</a>
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
            document.querySelector("#form-register").addEventListener("submit", (event) => {
                event.preventDefault();
                
                const nrodocumento = document.querySelector("#nrodocumento").value;
                const passuser = document.querySelector("#passuser").value;
                const confirmar_passuser = document.querySelector("#confirmar_passuser").value;
                
                if (!nrodocumento || !passuser || !confirmar_passuser) {
                    // Alerta para campos incompletos
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: 'warning',
                        title: 'Error',
                        text: 'Por favor, complete todos los campos.'
                    });
                    return;
                }
                
                // Validar el formato del documento
                if (!/^\d{8}$/.test(nrodocumento)) {
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: 'warning',
                        title: 'Error',
                        text: 'El número de documento debe tener 8 dígitos.'
                    });
                    return;
                }
                
                // Validar que las contraseñas coincidan
                if (passuser !== confirmar_passuser) {
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: 'warning',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden.'
                    });
                    return;
                }

                // Mostrar pantalla de carga
                Swal.fire({
                    ...sweetAlertOptions,
                    title: 'Registrando',
                    text: 'Espere por favor...',
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(document.querySelector("#form-register"));
                
                fetch("register.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Cerrar el loading
                    
                    if (data.success) {
                        Swal.fire({
                            ...sweetAlertOptions,
                            icon: 'success',
                            title: 'Éxito',
                            text: data.status,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '../login.php';
                        });
                    } else {
                        Swal.fire({
                            ...sweetAlertOptions,
                            icon: 'error',
                            title: 'Error',
                            text: data.status
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.close();
                    
                    Swal.fire({
                        ...sweetAlertOptions,
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al conectar con el servidor.'
                    });
                });
            });

            // Mostrar/ocultar contraseña
            document.getElementById('togglePassword1').addEventListener('click', function () {
                const passwordField = document.getElementById('passuser');
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.classList.toggle('bi-eye-slash');
            });

            document.getElementById('togglePassword2').addEventListener('click', function () {
                const passwordField = document.getElementById('confirmar_passuser');
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
</body>
</html>