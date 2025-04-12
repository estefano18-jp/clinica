<?php
// Este archivo carga el formulario de credenciales de acceso del doctor para edición

// Verificar que se haya proporcionado un número de documento
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '<div class="alert alert-danger">Número de documento no proporcionado.</div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el modelo de Doctor y Usuario
require_once '../../../models/Doctor.php';
require_once '../../../models/Usuario.php';

$doctor = new Doctor();
$usuario = new Usuario();

// Obtener la información del doctor
$infoDoctor = $doctor->obtenerDoctorPorNroDoc($nrodoc);

if (!$infoDoctor) {
    echo '<div class="alert alert-danger">Doctor no encontrado.</div>';
    exit;
}

// Obtener información del usuario asociado al doctor
$infoUsuario = $usuario->obtenerUsuarioPorColaborador($infoDoctor['idcolaborador']);
?>

<p class="text-center mb-4">
    <span class="badge bg-primary fs-6">Doctor: <?= htmlspecialchars($infoDoctor['nombres'] . ' ' . $infoDoctor['apellidos']) ?></span>
</p>

<form id="formEditarCredenciales" method="POST">
    <input type="hidden" name="operacion" value="actualizar_credenciales">
    <input type="hidden" name="nrodoc" value="<?= htmlspecialchars($nrodoc) ?>">
    <input type="hidden" name="idcolaborador" value="<?= htmlspecialchars($infoDoctor['idcolaborador']) ?>">
    <input type="hidden" name="idusuario" value="<?= htmlspecialchars($infoUsuario['idusuario'] ?? '') ?>">

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Credenciales de Acceso</h5>
            <p class="text-muted">Configure las credenciales que el doctor utilizará para acceder al sistema.</p>
        </div>
    </div>

    <?php if (empty($infoUsuario)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Este doctor no tiene credenciales de acceso configuradas. Complete el formulario para crear un usuario.
    </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="nombreusuario" class="form-label required-field">Nombre de Usuario</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="nombreusuario" name="nombreusuario" 
                    value="<?= htmlspecialchars($infoUsuario['nombreusuario'] ?? '') ?>" 
                    required minlength="5" maxlength="20">
            </div>
            <div class="form-text">El nombre de usuario debe tener entre 5 y 20 caracteres. Se permiten letras, números, guiones y puntos.</div>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label required-field">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="emailusuario" name="emailusuario" 
                    value="<?= htmlspecialchars($infoUsuario['email'] ?? $infoDoctor['email'] ?? '') ?>" 
                    required>
            </div>
            <div class="form-text">Ingrese un correo electrónico válido.</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="contrasena" class="form-label <?= empty($infoUsuario) ? 'required-field' : '' ?>"><?= empty($infoUsuario) ? 'Contraseña' : 'Contraseña (Opcional)' ?></label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="contrasena" name="contrasena" 
                    <?= empty($infoUsuario) ? 'required' : '' ?> 
                    minlength="8">
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="form-text">
                <?= empty($infoUsuario) ? 'La contraseña es obligatoria.' : 'Deje en blanco para mantener la contraseña actual.' ?>
                Debe tener al menos 8 caracteres, incluir minúsculas, mayúsculas, números y caracteres especiales.
            </div>
        </div>
        <div class="col-md-6">
            <label for="confirmarcontrasena" class="form-label <?= empty($infoUsuario) ? 'required-field' : '' ?>">Confirmar Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="confirmarcontrasena" name="confirmarcontrasena" 
                    <?= empty($infoUsuario) ? 'required' : '' ?> 
                    minlength="8">
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="form-text">Repita la contraseña para confirmar.</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="tipousuario" class="form-label required-field">Tipo de Usuario</label>
            <select class="form-select" id="tipousuario" name="nivelacceso" required>
                <option value="DOCTOR" selected>Doctor</option>
            </select>
            <div class="form-text">Los doctores tienen acceso específico a sus pacientes y citas.</div>
        </div>
        <div class="col-md-6">
            <label for="estado" class="form-label required-field">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="ACTIVO" <?= (isset($infoUsuario['estado']) && $infoUsuario['estado'] == 'ACTIVO') ? 'selected' : '' ?>>Activo</option>
                <option value="INACTIVO" <?= (isset($infoUsuario['estado']) && $infoUsuario['estado'] == 'INACTIVO') ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="resetearContrasena" name="resetearContrasena" value="1">
                <label class="form-check-label" for="resetearContrasena">
                    Requerir cambio de contraseña en el próximo inicio de sesión
                </label>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar Credenciales
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toast notifications
        function showSuccessToast(message) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        function showErrorToast(message) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: message,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }
        
        // Helper Functions for validation
        function markFieldAsValid(field) {
            if (field) {
                field.classList.add('is-valid');
                field.classList.remove('is-invalid');
            }
        }

        function markFieldAsInvalid(field) {
            if (field) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
            }
        }

        function addFieldHelpMessage(field, message) {
            if (!field) return;
            
            // Eliminar mensaje previo si existe
            removeFieldHelpMessage(field);
            
            // Crear nuevo mensaje
            const helpDiv = document.createElement('div');
            helpDiv.className = 'invalid-feedback';
            helpDiv.id = `help-${field.id}`;
            helpDiv.textContent = message;
            
            // Insertar después del campo o su contenedor
            if (field.parentNode.classList.contains('input-group')) {
                field.parentNode.parentNode.appendChild(helpDiv);
            } else {
                field.parentNode.appendChild(helpDiv);
            }
        }

        function removeFieldHelpMessage(field) {
            if (!field) return;
            
            const helpDiv = document.getElementById(`help-${field.id}`);
            if (helpDiv && helpDiv.parentNode) {
                helpDiv.parentNode.removeChild(helpDiv);
            }
        }
        
        // Función para validar formato de email
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(String(email).toLowerCase());
        }

        // Función para validar la fortaleza de la contraseña
        function validarFortalezaContrasena(contrasena) {
            if (!contrasena) return { esValida: true, mensaje: '' }; // Si está vacía y no es usuario nuevo, es válido
            
            const tieneMinusculas = /[a-z]/.test(contrasena);
            const tieneMayusculas = /[A-Z]/.test(contrasena);
            const tieneNumeros = /[0-9]/.test(contrasena);
            const tieneEspeciales = /[!@#$%^&*(),.?":{}|<>]/.test(contrasena);
            
            // Debe cumplir al menos 3 de 4 criterios
            let criteriosCumplidos = 0;
            if (tieneMinusculas) criteriosCumplidos++;
            if (tieneMayusculas) criteriosCumplidos++;
            if (tieneNumeros) criteriosCumplidos++;
            if (tieneEspeciales) criteriosCumplidos++;
            
            return {
                esValida: contrasena.length >= 8 && criteriosCumplidos >= 3,
                mensaje: 'La contraseña debe tener al menos 8 caracteres y cumplir 3 de 4 criterios: minúsculas, mayúsculas, números y caracteres especiales.'
            };
        }
        
        // Función para mostrar/ocultar contraseña
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = this.previousElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Cambiar el ícono
                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Validación de nombre de usuario
        const nombreUsuarioInput = document.getElementById('nombreusuario');
        if (nombreUsuarioInput) {
            // Validación en tiempo real al escribir
            nombreUsuarioInput.addEventListener('input', function() {
                // Remover caracteres no permitidos (solo letras, números, guiones, puntos)
                this.value = this.value.replace(/[^a-zA-Z0-9._-]/g, '');
                
                // Convertir a minúsculas
                this.value = this.value.toLowerCase();
                
                if (this.value.length < 5) {
                    markFieldAsInvalid(this);
                    addFieldHelpMessage(this, 'El nombre de usuario debe tener al menos 5 caracteres');
                } else if (this.value.length > 20) {
                    markFieldAsInvalid(this);
                    addFieldHelpMessage(this, 'El nombre de usuario no debe exceder los 20 caracteres');
                } else {
                    markFieldAsValid(this);
                    removeFieldHelpMessage(this);
                }
            });
            
            // Validación al perder el foco (para verificar duplicados)
            nombreUsuarioInput.addEventListener('blur', function() {
                if (this.value.length >= 5 && this.value.length <= 20) {
                    // Verificar si el nombre de usuario ya existe (excepto el actual)
                    fetch(`../../../controllers/usuario.controller.php?op=verificar_usuario&nombreusuario=${this.value}&idusuario=<?= htmlspecialchars($infoUsuario['idusuario'] ?? '0') ?>`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                markFieldAsInvalid(this);
                                addFieldHelpMessage(this, 'Este nombre de usuario ya está en uso');
                                showErrorToast('Nombre de usuario duplicado');
                            } else {
                                markFieldAsValid(this);
                                removeFieldHelpMessage(this);
                            }
                        })
                        .catch(error => {
                            console.error('Error al verificar nombre de usuario:', error);
                            showErrorToast('Error al verificar disponibilidad del nombre de usuario');
                        });
                }
            });
        }
        
        // Validación de email
        const emailInput = document.getElementById('emailusuario');
        if (emailInput) {
            // Validación en tiempo real
            emailInput.addEventListener('input', function() {
                if (validateEmail(this.value)) {
                    markFieldAsValid(this);
                    removeFieldHelpMessage(this);
                } else {
                    markFieldAsInvalid(this);
                    addFieldHelpMessage(this, 'Ingrese un correo electrónico válido');
                }
            });
            
            // Verificar duplicados al perder el foco
            emailInput.addEventListener('blur', function() {
                if (validateEmail(this.value)) {
                    // Verificar si el email ya existe (excepto el actual)
                    fetch(`../../../controllers/usuario.controller.php?op=verificar_email&email=${this.value}&idusuario=<?= htmlspecialchars($infoUsuario['idusuario'] ?? '0') ?>`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                markFieldAsInvalid(this);
                                addFieldHelpMessage(this, 'Este email ya está asociado con otro usuario');
                                showErrorToast('Email duplicado');
                            } else {
                                markFieldAsValid(this);
                                removeFieldHelpMessage(this);
                            }
                        })
                        .catch(error => {
                            console.error('Error al verificar email:', error);
                            showErrorToast('Error al verificar disponibilidad del email');
                        });
                }
            });
        }

        // Validación de contraseñas
        const contrasenaInput = document.getElementById('contrasena');
        const confirmarContrasenaInput = document.getElementById('confirmarcontrasena');
        
        if (contrasenaInput) {
            contrasenaInput.addEventListener('input', function() {
                if (this.value) {
                    const validacion = validarFortalezaContrasena(this.value);
                    
                    if (validacion.esValida) {
                        markFieldAsValid(this);
                        removeFieldHelpMessage(this);
                    } else {
                        markFieldAsInvalid(this);
                        addFieldHelpMessage(this, validacion.mensaje);
                    }
                } else {
                    // Si está vacío y no es un usuario nuevo, es válido (mantiene la contraseña actual)
                    if ('<?= empty($infoUsuario) ? '0' : '1' ?>' === '1') {
                        this.classList.remove('is-invalid');
                        this.classList.remove('is-valid');
                        removeFieldHelpMessage(this);
                    } else {
                        markFieldAsInvalid(this);
                        addFieldHelpMessage(this, 'La contraseña es obligatoria para nuevos usuarios');
                    }
                }
                
                // Validar también confirmar contraseña
                if (confirmarContrasenaInput.value) {
                    if (this.value !== confirmarContrasenaInput.value) {
                        markFieldAsInvalid(confirmarContrasenaInput);
                        addFieldHelpMessage(confirmarContrasenaInput, 'Las contraseñas no coinciden');
                    } else {
                        markFieldAsValid(confirmarContrasenaInput);
                        removeFieldHelpMessage(confirmarContrasenaInput);
                    }
                }
            });
        }
        
        if (confirmarContrasenaInput) {
            confirmarContrasenaInput.addEventListener('input', function() {
                if (contrasenaInput.value && this.value) {
                    if (this.value !== contrasenaInput.value) {
                        markFieldAsInvalid(this);
                        addFieldHelpMessage(this, 'Las contraseñas no coinciden');
                    } else {
                        markFieldAsValid(this);
                        removeFieldHelpMessage(this);
                    }
                } else {
                    this.classList.remove('is-valid');
                    this.classList.remove('is-invalid');
                    removeFieldHelpMessage(this);
                }
            });
        }
        
        // Validación del formulario completo
        const form = document.getElementById('formEditarCredenciales');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar campos
                let isValid = true;
                
                // Validar nombre de usuario
                if (nombreUsuarioInput.value.length < 5 || nombreUsuarioInput.value.length > 20) {
                    markFieldAsInvalid(nombreUsuarioInput);
                    addFieldHelpMessage(nombreUsuarioInput, 'El nombre de usuario debe tener entre 5 y 20 caracteres');
                    isValid = false;
                }
                
                // Validar email
                if (!validateEmail(emailInput.value)) {
                    markFieldAsInvalid(emailInput);
                    addFieldHelpMessage(emailInput, 'Ingrese un correo electrónico válido');
                    isValid = false;
                }
                
                // Validar contraseña si está establecida
                if (contrasenaInput.value) {
                    const validacion = validarFortalezaContrasena(contrasenaInput.value);
                    if (!validacion.esValida) {
                        markFieldAsInvalid(contrasenaInput);
                        addFieldHelpMessage(contrasenaInput, validacion.mensaje);
                        isValid = false;
                    }
                    
                    // Validar que las contraseñas coincidan
                    if (contrasenaInput.value !== confirmarContrasenaInput.value) {
                        markFieldAsInvalid(confirmarContrasenaInput);
                        addFieldHelpMessage(confirmarContrasenaInput, 'Las contraseñas no coinciden');
                        isValid = false;
                    }
                } else if ('<?= empty($infoUsuario) ? '1' : '0' ?>' === '1') {
                    // Si es un usuario nuevo y no hay contraseña
                    markFieldAsInvalid(contrasenaInput);
                    addFieldHelpMessage(contrasenaInput, 'La contraseña es obligatoria para nuevos usuarios');
                    isValid = false;
                }
                
                if (!isValid) {
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Por favor corrija los errores en el formulario.'
                    });
                    
                    // Hacer scroll al primer campo con error
                    const firstInvalidField = form.querySelector('.is-invalid');
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                    
                    return;
                }
                
                // Mostrar cargando
                Swal.fire({
                    title: 'Guardando credenciales',
                    text: 'Por favor espere...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar datos mediante AJAX
                const formData = new FormData(form);
                
                fetch('../../../controllers/usuario.controller.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.status) {
                        // Cerrar el modal
                        if (window.parent && window.parent.bootstrap) {
                            const modal = window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('modalCredenciales'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Credenciales guardadas',
                            text: data.mensaje || 'Las credenciales de acceso se han guardado correctamente'
                        }).then(() => {
                            // Recargar la lista de doctores
                            if (window.parent && window.parent.cargarDoctores) {
                                window.parent.cargarDoctores();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.mensaje || 'No se pudieron guardar las credenciales'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor'
                    });
                });
            });
        }
    });
</script>

<style>
    .is-valid {
        border-color: #198754 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    
    .toggle-password {
        z-index: 10;
    }
    
    .input-group .form-control.is-invalid,
    .input-group .form-control.is-valid {
        z-index: 1;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
</style>