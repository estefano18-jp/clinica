<?php
// Este archivo carga el formulario para editar los datos profesionales del doctor (especialidad y precio)

// Verificar que se haya proporcionado un número de documento
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '<div class="alert alert-danger">Número de documento no proporcionado.</div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el modelo de Doctor y Especialidad
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
?>

<p class="text-center mb-4">
    <span class="badge bg-primary fs-6">Doctor: <?= htmlspecialchars($infoDoctor['nombres'] . ' ' . $infoDoctor['apellidos']) ?></span>
</p>

<form id="formEditarDatosProfesionales" method="POST">
    <input type="hidden" name="operacion" value="actualizar_profesional">
    <input type="hidden" name="nrodoc" value="<?= htmlspecialchars($nrodoc) ?>">
    <input type="hidden" name="idcolaborador" value="<?= htmlspecialchars($infoDoctor['idcolaborador'] ?? '') ?>">

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Actualización de Datos Profesionales</strong>
                <p class="mb-0">Desde aquí puede modificar la especialidad y el precio de atención del doctor.</p>
            </div>
        </div>
    </div>

    <!-- Especialidad -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="especialidad" class="form-label">Especialidad</label>
            <div class="input-group">
                <select class="form-select" id="especialidad" name="idespecialidad" required>
                    <option value="">Seleccione...</option>
                    <?php if($especialidades): ?>
                        <?php foreach($especialidades as $esp): ?>
                            <option value="<?= htmlspecialchars($esp['idespecialidad']) ?>" 
                                    <?= ($infoDoctor['idespecialidad'] == $esp['idespecialidad']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($esp['especialidad']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="button" class="btn btn-outline-primary" id="btnNuevaEspecialidad">
                    <i class="fas fa-plus"></i> Nueva Especialidad
                </button>
            </div>
        </div>
    </div>

    <!-- Precio de Atención -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="precioatencion" class="form-label">Precio de Atención</label>
            <div class="input-group">
                <span class="input-group-text">S/.</span>
                <input type="number" class="form-control" id="precioatencion" name="precioatencion" 
                    value="<?= htmlspecialchars($infoDoctor['precioatencion'] ?? '') ?>" 
                    step="0.01" min="0" required>
            </div>
            <small class="text-muted">El precio en soles para la consulta médica con este doctor.</small>
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

<!-- Modal para agregar nueva especialidad -->
<div class="modal fade" id="modalNuevaEspecialidad" tabindex="-1" aria-labelledby="modalNuevaEspecialidadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevaEspecialidadLabel">Agregar Nueva Especialidad</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaEspecialidad">
                    <div class="mb-3">
                        <label for="nombreEspecialidad" class="form-label">Nombre de Especialidad</label>
                        <input type="text" class="form-control" id="nombreEspecialidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="precioEspecialidad" class="form-label">Precio de Atención</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="precioEspecialidad" step="0.01" min="0" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarEspecialidad">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar el cambio de especialidad para actualizar el precio
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
                            precioInput.classList.add('is-valid');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener precio de especialidad:', error);
                    });
            }
        });

        // Configurar el evento del botón para mostrar el modal de nueva especialidad
        const btnNuevaEspecialidad = document.getElementById('btnNuevaEspecialidad');
        btnNuevaEspecialidad.addEventListener('click', function() {
            const modalNuevaEspecialidad = new bootstrap.Modal(document.getElementById('modalNuevaEspecialidad'));
            modalNuevaEspecialidad.show();
        });

        // Configurar el evento del botón para guardar la nueva especialidad
        const btnGuardarEspecialidad = document.getElementById('btnGuardarEspecialidad');
        btnGuardarEspecialidad.addEventListener('click', function() {
            const nombreEspecialidad = document.getElementById('nombreEspecialidad').value;
            const precioEspecialidad = document.getElementById('precioEspecialidad').value;

            if (!nombreEspecialidad || !precioEspecialidad) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos requeridos.'
                });
                return;
            }

            // Mostrar loader
            Swal.fire({
                title: 'Guardando...',
                text: 'Por favor espere mientras se registra la especialidad.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('especialidad', nombreEspecialidad);
            formData.append('precioatencion', precioEspecialidad);

            fetch('../../../controllers/especialidad.controller.php?op=registrar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.status) {
                        // Cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('modalNuevaEspecialidad')).hide();
                        
                        // Limpiar formulario
                        document.getElementById('nombreEspecialidad').value = '';
                        document.getElementById('precioEspecialidad').value = '';
                        
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Especialidad registrada',
                            text: 'La especialidad ha sido registrada correctamente.'
                        }).then(() => {
                            // Recargar especialidades y seleccionar la nueva
                            cargarEspecialidades(data.idespecialidad);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al registrar',
                            text: data.mensaje || 'No se pudo registrar la especialidad.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al registrar especialidad:', error);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo completar el registro de la especialidad.'
                    });
                });
        });

        // Función para cargar y actualizar el listado de especialidades
        function cargarEspecialidades(seleccionarId = null) {
            fetch('../../../controllers/especialidad.controller.php?op=listar')
                .then(response => response.json())
                .then(result => {
                    if (result.status && Array.isArray(result.data)) {
                        // Limpiar y recargar el select de especialidades
                        const especialidadSelect = document.getElementById('especialidad');
                        const valorActual = especialidadSelect.value;
                        especialidadSelect.innerHTML = '<option value="">Seleccione...</option>';
                        
                        result.data.forEach(esp => {
                            const option = document.createElement('option');
                            option.value = esp.idespecialidad;
                            option.textContent = esp.especialidad;
                            
                            // Seleccionar automáticamente si coincide con el ID proporcionado
                            if (seleccionarId && esp.idespecialidad == seleccionarId) {
                                option.selected = true;
                                // Cargar también el precio
                                document.getElementById('precioatencion').value = esp.precioatencion;
                            } else if (esp.idespecialidad == valorActual) {
                                // Mantener la selección actual si no hay ID nuevo
                                option.selected = true;
                            }
                            
                            especialidadSelect.appendChild(option);
                        });
                        
                        // Disparar el evento change para cargar el precio
                        if (seleccionarId) {
                            const event = new Event('change');
                            especialidadSelect.dispatchEvent(event);
                        }
                    } else {
                        console.error('Formato de respuesta inesperado:', result);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar especialidades:', error);
                });
        }

        // Validar el formulario al enviar
        const formDatosProfesionales = document.getElementById('formEditarDatosProfesionales');
        if (formDatosProfesionales) {
            formDatosProfesionales.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Verificar campos obligatorios
                const especialidad = document.getElementById('especialidad');
                const precio = document.getElementById('precioatencion');
                
                if (!especialidad.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campo obligatorio',
                        text: 'Debe seleccionar una especialidad.'
                    });
                    especialidad.classList.add('is-invalid');
                    return;
                }
                
                if (!precio.value || parseFloat(precio.value) <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campo obligatorio',
                        text: 'El precio de atención debe ser mayor a cero.'
                    });
                    precio.classList.add('is-invalid');
                    return;
                }
                
                // Mostrar loader
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor espere mientras se actualizan los datos.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar formulario mediante AJAX
                const formData = new FormData(this);
                
                fetch('../../../controllers/doctor.controller.php?op=actualizar_profesional', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.status) {
                        // Cerrar modal
                        if (window.parent && window.parent.bootstrap) {
                            const modal = window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('modalDatosProfesionales'));
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos actualizados',
                            text: data.mensaje || 'Los datos profesionales han sido actualizados correctamente.'
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
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.'
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
</style>