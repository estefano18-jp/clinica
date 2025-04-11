<?php
// Este archivo carga el contenido del formulario de información personal para mostrarlo en un modal

require_once '../../../models/Paciente.php';

// Verificar que se haya proporcionado un ID de paciente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID de paciente no proporcionado.</div>';
    exit;
}

$idPaciente = $_GET['id'];
$paciente = new Paciente();

// Obtener la información del paciente
$infoPaciente = $paciente->obtenerPacientePorId($idPaciente);

if (!$infoPaciente) {
    echo '<div class="alert alert-danger">Paciente no encontrado.</div>';
    exit;
}

// Obtener la fecha actual para establecer la fecha máxima
$fechaHoy = date('Y-m-d');
?>

<p class="text-center mb-4">
    <span class="badge bg-primary fs-6">Paciente: <?= htmlspecialchars($infoPaciente['apellidos'] . ', ' . $infoPaciente['nombres']) ?></span>
</p>

<form id="editarInformacionPersonalForm" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($idPaciente) ?>">
    <input type="hidden" name="operacion" value="actualizar">
    <input type="hidden" name="idpaciente" value="<?= htmlspecialchars($idPaciente) ?>">

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos"
                value="<?= htmlspecialchars($infoPaciente['apellidos'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="nombres" name="nombres"
                value="<?= htmlspecialchars($infoPaciente['nombres'] ?? '') ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="tipodoc" class="form-label">Tipo de Documento</label>
            <select class="form-select" id="tipodoc" name="tipodoc" required>
                <option value="DNI" <?= ($infoPaciente['tipodoc'] == 'DNI') ? 'selected' : '' ?>>DNI</option>
                <option value="PASAPORTE" <?= ($infoPaciente['tipodoc'] == 'PASAPORTE') ? 'selected' : '' ?>>Pasaporte</option>
                <option value="CARNET DE EXTRANJERIA" <?= ($infoPaciente['tipodoc'] == 'CARNET DE EXTRANJERIA') ? 'selected' : '' ?>>Carnet de Extranjería</option>
                <option value="OTRO" <?= ($infoPaciente['tipodoc'] == 'OTRO') ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="nrodoc" class="form-label">Número de Documento</label>
            <input type="text" class="form-control" id="nrodoc" name="nrodoc"
                value="<?= htmlspecialchars($infoPaciente['nrodoc'] ?? '') ?>" required>
        </div>
        <div class="col-md-4">
            <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fechanacimiento" name="fechanacimiento"
                value="<?= htmlspecialchars($infoPaciente['fechanacimiento'] ?? '') ?>"
                max="<?= $fechaHoy ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="genero" class="form-label">Género</label>
            <select class="form-select" id="genero" name="genero" required>
                <option value="M" <?= ($infoPaciente['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= ($infoPaciente['genero'] == 'F') ? 'selected' : '' ?>>Femenino</option>
                <option value="OTRO" <?= ($infoPaciente['genero'] == 'OTRO') ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="telefono" class="form-label">Teléfono</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" id="telefono" name="telefono"
                    value="<?= htmlspecialchars($infoPaciente['telefono'] ?? '') ?>"
                    pattern="^9\d{8}$" maxlength="9" title="El teléfono debe tener 9 dígitos y comenzar con 9" required>
            </div>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?= htmlspecialchars($infoPaciente['email'] ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label for="direccion" class="form-label">Dirección</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <input type="text" class="form-control" id="direccion" name="direccion"
                    value="<?= htmlspecialchars($infoPaciente['direccion'] ?? '') ?>" required>
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

    // Actualizar validación cuando cambia el tipo de documento
    document.getElementById('tipodoc').addEventListener('change', function() {
        const tipodoc = this.value;
        const nrodocInput = document.getElementById('nrodoc');
        
        // Configurar maxlength y otras restricciones
        if (tipodoc && documentoConfig[tipodoc]) {
            const config = documentoConfig[tipodoc];
            nrodocInput.setAttribute('maxlength', config.maxLength);
            
            // Limpiar el valor si el tipo cambia
            nrodocInput.value = '';
            
            // Establecer patrón y mensaje
            nrodocInput.pattern = config.pattern.source;
            nrodocInput.title = config.message;
        }
    });

    // Validar en tiempo real
    document.getElementById('nrodoc').addEventListener('input', function() {
        const tipodoc = document.getElementById('tipodoc').value;
        
        if (tipodoc && documentoConfig[tipodoc]) {
            const config = documentoConfig[tipodoc];
            
            // Si solo se permiten números, eliminar otros caracteres
            if (config.onlyNumbers) {
                this.value = this.value.replace(/\D/g, '');
            }
            
            // Convertir a mayúsculas para documentos que lo requieran
            if (tipodoc === 'PASAPORTE' || tipodoc === 'CARNET DE EXTRANJERIA') {
                this.value = this.value.toUpperCase();
            }
            
            // Validar longitud y patrón
            if (this.value && config.pattern.test(this.value)) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else if (this.value) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.remove('is-invalid');
            }
        }
    });

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

    // Inicializar cuando se carga el formulario
    document.addEventListener('DOMContentLoaded', function() {
        // Aplicar configuración inicial según el tipo de documento
        updateDocumentValidation();
        setupPhoneValidation();
        setupDateValidation();

        // Validación de email
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
    });

    function updateDocumentValidation() {
        const tipodocSelect = document.getElementById('tipodoc');
        const nrodocInput = document.getElementById('nrodoc');
        const tipoDoc = tipodocSelect.value;
        const config = documentoConfig[tipoDoc];

        if (config) {
            nrodocInput.setAttribute('maxlength', config.maxLength);
            nrodocInput.pattern = config.pattern.source;
            nrodocInput.title = config.message;

            // Validar el valor actual
            if (nrodocInput.value) {
                if (config.onlyNumbers) {
                    nrodocInput.value = nrodocInput.value.replace(/\D/g, '');
                }
                
                if (tipodocSelect.value === 'PASAPORTE' || tipodocSelect.value === 'CARNET DE EXTRANJERIA') {
                    nrodocInput.value = nrodocInput.value.toUpperCase();
                }
                
                if (config.pattern.test(nrodocInput.value)) {
                    nrodocInput.classList.add('is-valid');
                    nrodocInput.classList.remove('is-invalid');
                } else {
                    nrodocInput.classList.add('is-invalid');
                    nrodocInput.classList.remove('is-valid');
                }
            }
        }
    }
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
</style>