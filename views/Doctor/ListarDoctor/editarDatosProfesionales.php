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
            <label for="especialidad" class="form-label required-field">Especialidad</label>
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
            <div id="help-especialidad" class="form-text">Seleccione la especialidad médica del doctor.</div>
        </div>
    </div>

    <!-- Precio de Atención -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="precioatencion" class="form-label required-field">Precio de Atención</label>
            <div class="input-group">
                <span class="input-group-text">S/.</span>
                <input type="number" class="form-control" id="precioatencion" name="precioatencion" 
                    value="<?= htmlspecialchars($infoDoctor['precioatencion'] ?? '') ?>" 
                    step="0.01" min="0" required>
                <button type="button" class="btn btn-outline-primary" id="btnEditarPrecio">
                    <i class="fas fa-edit"></i> Editar Precio
                </button>
            </div>
            <div id="help-precioatencion" class="form-text">El precio en soles para la consulta médica con este doctor.</div>
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
                        <label for="nombreEspecialidad" class="form-label required-field">Nombre de Especialidad</label>
                        <input type="text" class="form-control" id="nombreEspecialidad" required>
                        <div id="help-nombreEspecialidad" class="form-text">Ingrese el nombre de la nueva especialidad médica.</div>
                    </div>
                    <div class="mb-3">
                        <label for="precioEspecialidad" class="form-label required-field">Precio de Atención</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="precioEspecialidad" step="0.01" min="0" required>
                        </div>
                        <div id="help-precioEspecialidad" class="form-text">Ingrese el precio de consulta para esta especialidad.</div>
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

<!-- Modal para editar precio de atención -->
<div class="modal fade" id="modalEditarPrecio" tabindex="-1" aria-labelledby="modalEditarPrecioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarPrecioLabel">Editar Precio de Atención</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPrecio">
                    <div class="mb-3">
                        <label for="especialidadActual" class="form-label">Especialidad</label>
                        <input type="text" class="form-control" id="especialidadActual" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoPrecioAtencion" class="form-label required-field">Nuevo Precio de Atención</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="nuevoPrecioAtencion" step="0.01" min="0" required>
                        </div>
                        <div id="help-nuevoPrecioAtencion" class="form-text">Ingrese el nuevo precio para esta especialidad.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarPrecio">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar el cambio de especialidad para actualizar el precio
        const especialidadSelect = document.getElementById('especialidad');
        const precioInput = document.getElementById('precioatencion');
        
        // Función para validar el precio
        function validatePrecio(input) {
            const precio = parseFloat(input.value);
            
            if (isNaN(precio) || precio <= 0) {
                markFieldAsInvalid(input);
                document.getElementById('help-precioatencion').classList.add('text-danger');
                document.getElementById('help-precioatencion').textContent = 'El precio debe ser mayor a cero';
                return false;
            } else {
                markFieldAsValid(input);
                document.getElementById('help-precioatencion').classList.remove('text-danger');
                document.getElementById('help-precioatencion').textContent = 'El precio en soles para la consulta médica con este doctor.';
                return true;
            }
        }
        
        // Validar el precio cuando cambie
        precioInput.addEventListener('change', function() {
            validatePrecio(this);
        });
        
        precioInput.addEventListener('input', function() {
            // Permitir solo números y un punto decimal
            let value = this.value;
            
            // Eliminar caracteres no válidos
            value = value.replace(/[^\d.]/g, '');
            
            // Asegurar que solo hay un punto decimal
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            this.value = value;
        });
        
        // Función para validar la especialidad
        function validateEspecialidad(select) {
            if (!select.value) {
                markFieldAsInvalid(select);
                document.getElementById('help-especialidad').classList.add('text-danger');
                document.getElementById('help-especialidad').textContent = 'Debe seleccionar una especialidad';
                return false;
            } else {
                markFieldAsValid(select);
                document.getElementById('help-especialidad').classList.remove('text-danger');
                document.getElementById('help-especialidad').textContent = 'Especialidad seleccionada correctamente.';
                return true;
            }
        }
        
        // Validar especialidad cuando cambie
        especialidadSelect.addEventListener('change', function() {
            validateEspecialidad(this);
            
            if (this.value) {
                // Hacer una petición AJAX para obtener el precio de la especialidad
                fetch(`../../../controllers/especialidad.controller.php?op=obtener&id=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status && data.data) {
                            precioInput.value = data.data.precioatencion;
                            validatePrecio(precioInput);
                            showSuccessToast('Precio actualizado según la especialidad seleccionada');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener precio de especialidad:', error);
                        showErrorToast('Error al obtener el precio de la especialidad');
                    });
            } else {
                precioInput.value = '';
                precioInput.classList.remove('is-valid', 'is-invalid');
                document.getElementById('help-precioatencion').classList.remove('text-danger');
                document.getElementById('help-precioatencion').textContent = 'El precio en soles para la consulta médica con este doctor.';
            }
        });

        // Configurar el evento del botón para mostrar el modal de nueva especialidad
        const btnNuevaEspecialidad = document.getElementById('btnNuevaEspecialidad');
        btnNuevaEspecialidad.addEventListener('click', function() {
            // Limpiar el formulario
            document.getElementById('formNuevaEspecialidad').reset();
            document.getElementById('nombreEspecialidad').classList.remove('is-valid', 'is-invalid');
            document.getElementById('precioEspecialidad').classList.remove('is-valid', 'is-invalid');
            document.getElementById('help-nombreEspecialidad').classList.remove('text-danger');
            document.getElementById('help-precioEspecialidad').classList.remove('text-danger');
            
            // Mostrar el modal
            const modalNuevaEspecialidad = new bootstrap.Modal(document.getElementById('modalNuevaEspecialidad'));
            modalNuevaEspecialidad.show();
        });

        // Configurar el evento del botón para editar el precio
        const btnEditarPrecio = document.getElementById('btnEditarPrecio');
        btnEditarPrecio.addEventListener('click', function() {
            // Verificar si hay una especialidad seleccionada
            if (!especialidadSelect.value) {
                showErrorToast('Debe seleccionar una especialidad primero');
                return;
            }
            
            // Obtener el texto de la especialidad seleccionada
            const especialidadTexto = especialidadSelect.options[especialidadSelect.selectedIndex].text;
            
            // Configurar el modal
            document.getElementById('especialidadActual').value = especialidadTexto;
            document.getElementById('nuevoPrecioAtencion').value = precioInput.value;
            document.getElementById('nuevoPrecioAtencion').classList.remove('is-valid', 'is-invalid');
            document.getElementById('help-nuevoPrecioAtencion').classList.remove('text-danger');
            
            // Mostrar el modal
            const modalEditarPrecio = new bootstrap.Modal(document.getElementById('modalEditarPrecio'));
            modalEditarPrecio.show();
        });

        // Configurar el evento del botón para guardar la nueva especialidad
        const btnGuardarEspecialidad = document.getElementById('btnGuardarEspecialidad');
        btnGuardarEspecialidad.addEventListener('click', function() {
            const nombreEspecialidad = document.getElementById('nombreEspecialidad');
            const precioEspecialidad = document.getElementById('precioEspecialidad');
            let isValid = true;
            
            // Validar el nombre de la especialidad
            if (!nombreEspecialidad.value.trim()) {
                markFieldAsInvalid(nombreEspecialidad);
                document.getElementById('help-nombreEspecialidad').classList.add('text-danger');
                document.getElementById('help-nombreEspecialidad').textContent = 'El nombre de la especialidad es obligatorio';
                isValid = false;
            } else {
                markFieldAsValid(nombreEspecialidad);
                document.getElementById('help-nombreEspecialidad').classList.remove('text-danger');
            }
            
            // Validar el precio de la especialidad
            const precio = parseFloat(precioEspecialidad.value);
            if (isNaN(precio) || precio <= 0) {
                markFieldAsInvalid(precioEspecialidad);
                document.getElementById('help-precioEspecialidad').classList.add('text-danger');
                document.getElementById('help-precioEspecialidad').textContent = 'El precio debe ser mayor a cero';
                isValid = false;
            } else {
                markFieldAsValid(precioEspecialidad);
                document.getElementById('help-precioEspecialidad').classList.remove('text-danger');
            }
            
            if (!isValid) {
                showErrorToast('Por favor, complete todos los campos requeridos');
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
            formData.append('especialidad', nombreEspecialidad.value);
            formData.append('precioatencion', precioEspecialidad.value);

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

        // Configurar el evento del botón para guardar el nuevo precio
        const btnGuardarPrecio = document.getElementById('btnGuardarPrecio');
        btnGuardarPrecio.addEventListener('click', function() {
            const nuevoPrecioInput = document.getElementById('nuevoPrecioAtencion');
            const nuevoPrecio = parseFloat(nuevoPrecioInput.value);
            
            if (isNaN(nuevoPrecio) || nuevoPrecio <= 0) {
                markFieldAsInvalid(nuevoPrecioInput);
                document.getElementById('help-nuevoPrecioAtencion').classList.add('text-danger');
                document.getElementById('help-nuevoPrecioAtencion').textContent = 'El precio debe ser mayor a cero';
                return;
            }
            
            // Mostrar loader
            Swal.fire({
                title: 'Actualizando...',
                text: 'Por favor espere mientras se actualiza el precio.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Actualizar en el formulario principal
            precioInput.value = nuevoPrecioInput.value;
            validatePrecio(precioInput);
            
            // Cerrar el modal
            bootstrap.Modal.getInstance(document.getElementById('modalEditarPrecio')).hide();
            
            // Mostrar mensaje de éxito
            Swal.close();
            showSuccessToast('Precio actualizado correctamente');
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
                                validatePrecio(document.getElementById('precioatencion'));
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
                        showErrorToast('Error al cargar las especialidades');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar especialidades:', error);
                    showErrorToast('Error al cargar las especialidades');
                });
        }

        // Validar el formulario al enviar
        const formDatosProfesionales = document.getElementById('formEditarDatosProfesionales');
        if (formDatosProfesionales) {
            formDatosProfesionales.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // Validar especialidad
                if (!validateEspecialidad(document.getElementById('especialidad'))) {
                    isValid = false;
                }
                
                // Validar precio
                if (!validatePrecio(document.getElementById('precioatencion'))) {
                    isValid = false;
                }
                
                if (!isValid) {
                    showErrorToast('Por favor, corrija los errores en el formulario');
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
        
        // Funciones de utilidad para marcar campos como válidos/inválidos
        function markFieldAsValid(field) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
        }

        function markFieldAsInvalid(field) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
        }

        // Funciones para mostrar notificaciones
        function showSuccessToast(message) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: message
                });
            } else {
                console.log('Éxito:', message);
            }
        }

        function showErrorToast(message) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: message
                });
            } else {
                console.error('Error:', message);
            }
        }
    });
</script>

<style>
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    
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
    
    .text-danger {
        color: #dc3545 !important;
    }
</style>