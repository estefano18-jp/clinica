
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estiloLogin.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 500px; /* Ajuste del ancho máximo */
            margin: auto;
            padding: 20px 30px; /* Ajuste del padding horizontal */
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        .input-group {
            position: relative;
        }

        .input-group .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Ajustar el espacio entre etiquetas y campos */
        .form-label {
            margin-bottom: 2px; /* Reducir margen inferior */
        }

        /* Ajustar el margen entre controles del formulario */
        .mb-3 {
            margin-bottom: 15px; /* Ajuste del margen inferior */
        }

        /* Ajustes para dispositivos móviles */
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .login-container {
                font-size: 0.9rem;
                padding: 15px 20px; /* Ajuste del padding en móviles */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="margin-bottom: 15px;">Registrarse</h2>
        <div id="message" class="mb-3"></div>
        <form id="form-register" autocomplete="off">
            <!-- Nombre y Apellido en la misma fila -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios">
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios">
                </div>
            </div>
            <!-- Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono" required pattern="\d{9}" title="El número de teléfono debe tener 9 dígitos" maxlength="9">
            </div>
            <!-- Número de Documento -->
            <div class="mb-3">
                <label for="nrodocumento" class="form-label">Número de Documento:</label>
                <input type="text" id="nrodocumento" name="nrodocumento" class="form-control" placeholder="Número de Documento" required pattern="\d{8}" title="El número de documento debe tener 8 dígitos" maxlength="8">
            </div>
            <!-- Nombre de Usuario -->
            <div class="mb-3">
                <label for="nomuser" class="form-label">Usuario:</label>
                <input type="text" id="nomuser" name="nomuser" class="form-control" placeholder="Usuario" required>
            </div>
            <!-- Contraseña -->
            <div class="mb-3">
                <label for="passuser" class="form-label">Contraseña:</label>
                <div class="input-group">
                    <input type="password" id="passuser" name="passuser" class="form-control" required>
                    <i class="bi bi-eye eye-icon" id="togglePassword1"></i>
                </div>
            </div>
            <!-- Confirmar Contraseña -->
            <div class="mb-3">
                <label for="confirmar_passuser" class="form-label">Confirmar Contraseña:</label>
                <div class="input-group">
                    <input type="password" id="confirmar_passuser" name="confirmar_passuser" class="form-control" required>
                    <i class="bi bi-eye eye-icon" id="togglePassword2"></i>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
            <div class="mt-3 text-center">
                <p>¿Ya tienes cuenta? <a href="../login.php" class="btn btn-link">Iniciar sesión</a></p>
            </div>
        </form>
    </div>

    <!-- Incluye tus scripts aquí -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector("#form-register").addEventListener("submit", function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch("register.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById("message");
                if (data.success) {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.status}</div>`;
                    // Si deseas redirigir después de registrar, descomenta las siguientes líneas
                     setTimeout(() => {
                         window.location.href = '../index.php';
                     }, 1000);
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${data.status}</div>`;
                }
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
    </script>
</body>
</html>
