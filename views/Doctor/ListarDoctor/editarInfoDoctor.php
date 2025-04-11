<?php
// Este archivo carga el formulario de información personal y profesional del doctor para edición

// Verificar que se haya proporcionado un número de documento
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '<div class="alert alert-danger">Número de documento no proporcionado.</div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el modelo de Doctor (asumiendo que existe)
require_once '../../../models/Doctor.php';
require_once '../../../models/Especialidad.php';

$doctor = new Doctor();
$especialidad = new Especialidad();

// Obtener la información del doctor
$infoDoctor = $doctor->obtenerDoctorPorNroDoc($nrodoc);

if (!$infoDoctor) {
    echo '<div class="alert alert-danger">Doctor no encontrado.</div>';
    exit;
}

// Obtener todas las especialidades para el select
$especialidades = $especialidad->listarEspecialidades();

// Obtener la fecha actual para establecer la fecha máxima
$fechaHoy = date('Y-m-d');
?>

<p class="text-center mb-4">
    <span class="badge bg-primary fs-6">Doctor: <?= htmlspecialchars($infoDoctor['nombres'] . ' ' . $infoDoctor['apellidos']) ?></span>
</p>

<form id="formEditarInfoDoctor" method="POST">
    <input type="hidden" name="operacion" value="actualizar_doctor">
    <input type="hidden" name="nrodoc" value="<?= htmlspecialchars($nrodoc) ?>">
    <input type="hidden" name="idcolaborador" value="<?= htmlspecialchars($infoDoctor['idcolaborador'] ?? '') ?>">
    <input type="hidden" name="idpersona" value="<?= htmlspecialchars($infoDoctor['idpersona'] ?? '') ?>">

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>
        </div>
    </div>

    <!-- Tipo y Número de Documento -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="tipodoc" class="form-label">Tipo de Documento</label>
            <select class="form-select" id="tipodoc" name="tipodoc" required>
                <option value="DNI" <?= ($infoDoctor['tipodoc'] == 'DNI') ? 'selected' : '' ?>>DNI</option>
                <option value="PASAPORTE" <?= ($infoDoctor['tipodoc'] == 'PASAPORTE') ? 'selected' : '' ?>>Pasaporte</option>
                <option value="CARNET DE EXTRANJERIA" <?= ($infoDoctor['tipodoc'] == 'CARNET DE EXTRANJERIA') ? 'selected' : '' ?>>Carnet de Extranjería</option>
                <option value="OTRO" <?= ($infoDoctor['tipodoc'] == 'OTRO') ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="nrodoc" class="form-label">Número de Documento</label>
            <input type="text" class="form-control" id="nrodoc_actual" value="<?= htmlspecialchars($infoDoctor['nrodoc'] ?? '') ?>" readonly>
            <small class="text-muted">El número de documento no se puede modificar</small>
        </div>
        <div class="col-md-4">
            <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fechanacimiento" name="fechanacimiento" 
                value="<?= htmlspecialchars($infoDoctor['fechanacimiento'] ?? '') ?>" 
                max="<?= $fechaHoy ?>" required>
        </div>
    </div>

    <!-- Apellidos y Nombres -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" 
                value="<?= htmlspecialchars($infoDoctor['apellidos'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="nombres" name="nombres" 
                value="<?= htmlspecialchars($infoDoctor['nombres'] ?? '') ?>" required>
        </div>
    </div>

    <!-- Género, Teléfono, Email -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="genero" class="form-label">Género</label>
            <select class="form-select" id="genero" name="genero" required>
                <option value="M" <?= ($infoDoctor['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= ($infoDoctor['genero'] == 'F') ? 'selected' : '' ?>>Femenino</option>
                <option value="OTRO" <?= ($infoDoctor['genero'] == 'OTRO') ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="telefono" class="form-label">Teléfono</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" id="telefono" name="telefono" 
                    value="<?= htmlspecialchars($infoDoctor['telefono'] ?? '') ?>" 
                    pattern="^9\d{8}$" maxlength="9" title="El teléfono debe tener 9 dígitos y comenzar con 9" required>
            </div>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" 
                    value="<?= htmlspecialchars($infoDoctor['email'] ?? '') ?>" required>
            </div>
        </div>
    </div>

    <!-- Dirección -->
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="direccion" class="form-label">Dirección</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <input type="text" class="form-control" id="direccion" name="direccion" 
                    value="<?= htmlspecialchars($infoDoctor['direccion'] ?? '') ?>" required>
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
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para cada tipo de documento
        const documentoConfig = {
            'DNI': {
                pattern: /^\d{8}$/,
                message: 'El DNI debe tener 8 dígitos numéricos',
                maxLength: 8,
                onlyNumbers: true
            },
            'PASAPORTE': {
                pattern: /^[A-Z0-9]{6,12}$/,
                message: 'El pasaporte debe tener entre 6 y 12 caracteres alfanuméricos',
                maxLength: 12,
                onlyNumbers: false
            },
            'CARNET DE EXTRANJERIA': {
                pattern: /^[A-Z0-9]{9}$/,
                message: 'El carnet de extranjería debe tener 9 caracteres alfanuméricos',
                maxLength: 9,
                onlyNumbers: false
            },
            'OTRO': {
                pattern: /^.{1,15}$/,
                message: 'El documento puede tener hasta 15 caracteres',
                maxLength: 15,
                onlyNumbers: false
            }
        };

        // Configurar validación de teléfono
        function setupPhoneValidation() {
            const telefonoInput = document.getElementById('telefono');

            telefonoInput.addEventListener('input', function() {
                // Eliminar cualquier carácter que no sea un número
                this.value = this.value.replace(/\D/g, '');

                // Asegurar que comience con 9
                if (this.value.length > 0 && this.value.charAt(0) !== '9') {
                    this.value = '9' + this.value.substring(1);
                }

                // Validar el patrón
                if (this.value.length === 9 && this.value.charAt(0) === '9') {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else if (this.value.length > 0) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Validar fecha de nacimiento
        function setupDateValidation() {
            const fechaNacimientoInput = document.getElementById('fechanacimiento');

            fechaNacimientoInput.addEventListener('change', function() {
                const fechaSeleccionada = new Date(this.value);
                const fechaActual = new Date();

                if (fechaSeleccionada > fechaActual) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Validar email
        function setupEmailValidation() {
            const emailInput = document.getElementById('email');
            
            emailInput.addEventListener('blur', function() {
                if (this.value) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailPattern.test(this.value)) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                } else {
                    this.classList.remove('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Validar campos de texto requeridos
        function setupRequiredFieldsValidation() {
            const requiredFields = document.querySelectorAll('input[required], select[required]');
            
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            });
        }

        // Configurar el cambio de especialidad para actualizar el precio
        function setupEspecialidadChange() {
            const especialidadSelect = document.getElementById('especialidad');
            const precioInput = document.getElementById('precioatencion');
            
            especialidadSelect.addEventListener('change', function() {
                if (this.value) {
                    // Hacer una petición AJAX para obtener el precio de la especialidad
                    fetch(`../../../controllers/especialidad.controller.php?op=obtener&id=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status && data.data) {
                                precioInput.value = data.data.precioatencion;
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener precio de especialidad:', error);
                        });
                }
            });
        }

        // Validar el formulario al enviar
        function setupFormSubmit() {
            const form = document.getElementById('formEditarInfoDoctor');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Verificar campos obligatorios
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.add('is-valid');
                    }
                });
                
                if (!isValid) {
                    // Mostrar alerta de campos incompletos
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos incompletos',
                        text: 'Por favor complete todos los campos obligatorios.'
                    });
                    return;
                }
                
                // Mostrar cargando
                Swal.fire({
                    title: 'Guardando cambios',
                    text: 'Por favor espere...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar formulario mediante AJAX
                const formData = new FormData(form);
                
                fetch('../../../controllers/doctor.controller.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        // Cerrar el modal
                        if (window.parent && window.parent.bootstrap) {
                            const modal = window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('modalInfoDoctor'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Cambios guardados',
                            text: data.mensaje || 'Los datos del doctor han sido actualizados correctamente.'
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
                            text: data.mensaje || 'No se pudieron guardar los cambios.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.'
                    });
                });
            });
        }

        // Inicializar todas las validaciones
        setupPhoneValidation();
        setupDateValidation();
        setupEmailValidation();
        setupRequiredFieldsValidation();
        setupEspecialidadChange();
        setupFormSubmit();
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
</style>