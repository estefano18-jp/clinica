<?php
// Este archivo carga el formulario para editar la información de contrato del doctor

// Verificar que se haya proporcionado un número de documento
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '<div class="alert alert-danger">Número de documento no proporcionado.</div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el modelo de Doctor y Contrato (asumiendo que existe)
require_once '../../../models/Doctor.php';
require_once '../../../models/Contrato.php';

$doctor = new Doctor();
$contrato = new Contrato();

// Obtener la información del doctor
$infoDoctor = $doctor->obtenerDoctorPorNroDoc($nrodoc);

if (!$infoDoctor) {
    echo '<div class="alert alert-danger">Doctor no encontrado.</div>';
    exit;
}

// Obtener información del contrato
$infoContrato = []; // Inicializar como array vacío
$contratos = $contrato->obtenerContratosPorColaborador($infoDoctor['idcolaborador']);
if ($contratos && count($contratos) > 0) {
    // Tomamos el contrato más reciente
    $infoContrato = $contratos[0];
} else {
    // Si no hay información de contrato, crear valores por defecto
    echo '<div class="alert alert-warning">No hay información de contrato para este doctor. Puede crear uno nuevo ahora.</div>';
    $infoContrato = [
        'idcontrato' => 0,
        'tipocontrato' => '',
        'fechainicio' => date('Y-m-d'),
        'fechafin' => null,
        'estado' => 'ACTIVO'
    ];
}

// Obtener la fecha actual para establecer límites
$fechaHoy = date('Y-m-d');
?>

<p class="text-center mb-4">
    <span class="badge bg-primary fs-6">Doctor: <?= htmlspecialchars($infoDoctor['nombres'] . ' ' . $infoDoctor['apellidos']) ?></span>
</p>

<form id="formEditarInfoContrato" method="POST">
    <input type="hidden" name="operacion" value="actualizar_contrato">
    <input type="hidden" name="nrodoc" value="<?= htmlspecialchars($nrodoc) ?>">
    <input type="hidden" name="idcolaborador" value="<?= htmlspecialchars($infoDoctor['idcolaborador'] ?? '') ?>">
    <input type="hidden" name="idcontrato" value="<?= htmlspecialchars($infoContrato['idcontrato'] ?? 0) ?>">

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Información de Contrato</strong>
                <p class="mb-0">Desde aquí puede modificar la información del contrato del doctor.</p>
            </div>
        </div>
    </div>

    <!-- Tipo de Contrato -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="tipocontrato" class="form-label">Tipo de Contrato</label>
            <select class="form-select" id="tipocontrato" name="tipocontrato" required>
                <option value="">Seleccione...</option>
                <option value="INDEFINIDO" <?= ($infoContrato['tipocontrato'] == 'INDEFINIDO') ? 'selected' : '' ?>>Indefinido</option>
                <option value="PLAZO FIJO" <?= ($infoContrato['tipocontrato'] == 'PLAZO FIJO') ? 'selected' : '' ?>>Plazo Fijo</option>
                <option value="TEMPORAL" <?= ($infoContrato['tipocontrato'] == 'TEMPORAL') ? 'selected' : '' ?>>Temporal</option>
                <option value="EVENTUAL" <?= ($infoContrato['tipocontrato'] == 'EVENTUAL') ? 'selected' : '' ?>>Eventual</option>
            </select>
        </div>
    </div>

    <!-- Fechas de Inicio y Fin -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="fechainicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fechainicio" name="fechainicio" 
                value="<?= htmlspecialchars($infoContrato['fechainicio'] ?? $fechaHoy) ?>" 
                max="<?= $fechaHoy ?>" required>
        </div>
        <div class="col-md-6">
            <label for="fechafin" class="form-label">Fecha de Fin</label>
            <input type="date" class="form-control" id="fechafin" name="fechafin" 
                value="<?= htmlspecialchars($infoContrato['fechafin'] ?? '') ?>">
            <small class="text-muted">Opcional para contratos indefinidos</small>
        </div>
    </div>

    <!-- Estado del Contrato -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="estado" class="form-label">Estado del Contrato</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="ACTIVO" <?= ($infoContrato['estado'] == 'ACTIVO') ? 'selected' : '' ?>>Activo</option>
                <option value="INACTIVO" <?= ($infoContrato['estado'] == 'INACTIVO') ? 'selected' : '' ?>>Inactivo</option>
                <option value="FINALIZADO" <?= ($infoContrato['estado'] == 'FINALIZADO') ? 'selected' : '' ?>>Finalizado</option>
            </select>
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
        // Validar tipo de contrato y configurar fechas
        const tipoContratoSelect = document.getElementById('tipocontrato');
        const fechaFinInput = document.getElementById('fechafin');
        
        tipoContratoSelect.addEventListener('change', function() {
            if (this.value === 'INDEFINIDO') {
                fechaFinInput.required = false;
                fechaFinInput.disabled = true;
                fechaFinInput.value = '';
            } else {
                fechaFinInput.required = true;
                fechaFinInput.disabled = false;
            }
        });
        
        // Disparar el evento al cargar para configurar correctamente
        if (tipoContratoSelect.value === 'INDEFINIDO') {
            fechaFinInput.required = false;
            fechaFinInput.disabled = true;
        }
        
        // Validar fechas
        const fechaInicioInput = document.getElementById('fechainicio');
        
        function validarFechas() {
            const fechaInicio = new Date(fechaInicioInput.value);
            const fechaFin = fechaFinInput.value ? new Date(fechaFinInput.value) : null;
            const fechaHoy = new Date();
            
            // Reset validación
            fechaInicioInput.classList.remove('is-invalid', 'is-valid');
            fechaFinInput.classList.remove('is-invalid', 'is-valid');
            
            // Validar que fecha inicio no sea futura
            if (fechaInicio > fechaHoy) {
                fechaInicioInput.classList.add('is-invalid');
                return false;
            } else {
                fechaInicioInput.classList.add('is-valid');
            }
            
            // Validar que fecha fin sea posterior a fecha inicio
            if (fechaFin && fechaFin <= fechaInicio) {
                fechaFinInput.classList.add('is-invalid');
                return false;
            } else if (fechaFin) {
                fechaFinInput.classList.add('is-valid');
            }
            
            return true;
        }
        
        fechaInicioInput.addEventListener('change', validarFechas);
        fechaFinInput.addEventListener('change', validarFechas);
        
        // Validar el formulario al enviar
        const form = document.getElementById('formEditarInfoContrato');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos
            if (!tipoContratoSelect.value) {
                tipoContratoSelect.classList.add('is-invalid');
                Swal.fire({
                    icon: 'error',
                    title: 'Campo obligatorio',
                    text: 'Debe seleccionar un tipo de contrato.'
                });
                return;
            }
            
            if (!validarFechas()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en las fechas',
                    text: 'Por favor verifique las fechas ingresadas. La fecha de inicio no puede ser futura y la fecha de fin debe ser posterior a la fecha de inicio.'
                });
                return;
            }
            
            // Mostrar loader
            Swal.fire({
                title: 'Guardando...',
                text: 'Por favor espere mientras se actualizan los datos del contrato.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario mediante AJAX
            const formData = new FormData(this);
            const idcontrato = parseInt(formData.get('idcontrato'));
            
            // Si el idcontrato es 0, usamos 'registrar', de lo contrario 'actualizar'
            const operacion = idcontrato === 0 ? 'registrar' : 'registrar'; // Usamos registrar en ambos casos ya que el SP maneja la lógica
            
            fetch(`../../../controllers/contrato.controller.php?op=${operacion}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.status) {
                    // Cerrar modal
                    if (window.parent && window.parent.bootstrap) {
                        const modal = window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('modalInfoContrato'));
                        if (modal) {
                            modal.hide();
                        }
                    }
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Contrato actualizado',
                        text: data.mensaje || 'La información del contrato ha sido actualizada correctamente.'
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
                        text: data.mensaje || 'No se pudieron guardar los cambios en el contrato.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor.'
                });
            });
        });
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