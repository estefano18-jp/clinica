<?php
// Este archivo carga el formulario de horarios de atención del doctor para edición

// Verificar que se haya proporcionado un número de documento
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '<div class="alert alert-danger">Número de documento no proporcionado.</div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el modelo de Doctor y Horario
require_once '../../../models/Doctor.php';
require_once '../../../models/Horario.php';

$doctor = new Doctor();
$horario = new Horario();

// Obtener la información del doctor
$infoDoctor = $doctor->obtenerDoctorPorNroDoc($nrodoc);

if (!$infoDoctor) {
    echo '<div class="alert alert-danger">Doctor no encontrado.</div>';
    exit;
}

// Obtener los horarios de atención del doctor
try {
    $horarios = $horario->obtenerHorariosPorColaborador($infoDoctor['idcolaborador']);
} catch (Exception $e) {
    // Si hay error, inicializar como array vacío
    $horarios = [];
    // Mostrar mensaje solo en modo desarrollo
    // echo '<div class="alert alert-warning">Error al cargar horarios: ' . $e->getMessage() . '</div>';
}

// Crear un array asociativo para facilitar el acceso a los horarios por día
$horariosPorDia = [];
if (!empty($horarios)) {
    foreach ($horarios as $h) {
        // Si no existe entrada para este día, crear array
        if (!isset($horariosPorDia[$h['dia']])) {
            $horariosPorDia[$h['dia']] = [];
        }
        // Añadir horario al array del día
        $horariosPorDia[$h['dia']][] = $h;
    }
}

// Mapeo de números de día a nombres de días para usar en los IDs
$diasMap = [
    1 => 'Lunes',
    2 => 'Martes',
    3 => 'Miercoles', // Sin tilde para evitar problemas en IDs
    4 => 'Jueves',
    5 => 'Viernes',
    6 => 'Sabado',  // Sin tilde para evitar problemas en IDs
    7 => 'Domingo'
];
?>

<!-- Contenedor principal con ancho máximo -->
<div class="container-fluid px-0">
    <p class="text-center mb-4">
        <span class="badge bg-primary fs-5">Doctor: <?= htmlspecialchars($infoDoctor['nombres'] . ' ' . $infoDoctor['apellidos']) ?></span>
    </p>

    <form id="formEditarHorarioAtencion" method="POST">
        <input type="hidden" name="operacion" value="actualizar_horarios">
        <input type="hidden" name="nrodoc" value="<?= htmlspecialchars($nrodoc) ?>">
        <input type="hidden" name="idcolaborador" value="<?= htmlspecialchars($infoDoctor['idcolaborador']) ?>">

        <div class="row mb-3">
            <div class="col-12">
                <h5 class="border-bottom pb-2 mb-3">Horarios de Atención</h5>
                <p class="text-muted">Configure los horarios en los que el doctor estará disponible para atender pacientes.</p>
            </div>
        </div>

        <div class="alert alert-info">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-info-circle me-2 fs-5"></i>
                <h5 class="alert-heading mb-0">Configuración de Horarios</h5>
            </div>
            <p class="mb-1">Establezca los días y horarios en que el doctor atenderá en la clínica.</p>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Medio tiempo:</strong> Un solo horario (mañana o tarde)</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Tiempo completo:</strong> Dos horarios (mañana y tarde)</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped horario-table">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Día</th>
                        <th style="width: 80px;">Atiende</th>
                        <th style="width: 140px;">Modalidad</th>
                        <th>Horarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dias = [
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        7 => 'Domingo'
                    ];

                    foreach ($dias as $numDia => $nombreDia):
                        $diaNombre = $diasMap[$numDia]; // Nombre sin tildes para IDs
                        
                        // Determinar si el día tiene horarios asignados
                        $tieneHorarios = isset($horariosPorDia[$numDia]) && !empty($horariosPorDia[$numDia]);
                        
                        // Determinar modalidad (medio tiempo o tiempo completo)
                        $modalidad = 'medioTiempo'; // Por defecto
                        if ($tieneHorarios && count($horariosPorDia[$numDia]) > 1) {
                            $modalidad = 'tiempoCompleto';
                        }
                        
                        // Obtener horarios
                        $horaInicio1 = '';
                        $horaFin1 = '';
                        $horaInicio2 = '';
                        $horaFin2 = '';
                        
                        if ($tieneHorarios) {
                            // Ordenar horarios por hora de inicio
                            usort($horariosPorDia[$numDia], function($a, $b) {
                                return strcmp($a['horainicio'], $b['horainicio']);
                            });
                            
                            // Asignar primer horario
                            $horaInicio1 = $horariosPorDia[$numDia][0]['horainicio'];
                            $horaFin1 = $horariosPorDia[$numDia][0]['horafin'];
                            
                            // Si hay segundo horario, asignarlo
                            if (count($horariosPorDia[$numDia]) > 1) {
                                $horaInicio2 = $horariosPorDia[$numDia][1]['horainicio'];
                                $horaFin2 = $horariosPorDia[$numDia][1]['horafin'];
                            }
                        }
                    ?>
                    <tr>
                        <td class="align-middle"><?= $nombreDia ?></td>
                        <td class="text-center align-middle">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input dia-atencion" type="checkbox" 
                                    id="atiende<?= $diaNombre ?>" 
                                    name="atiende[<?= strtoupper($diaNombre) ?>]" 
                                    <?= $tieneHorarios ? 'checked' : '' ?>
                                    onchange="toggleHorario('<?= $diaNombre ?>')">
                            </div>
                        </td>
                        <td class="align-middle">
                            <select class="form-select form-select-sm modalidad-select" 
                                    id="modalidad<?= $diaNombre ?>" 
                                    name="modalidad[<?= strtoupper($diaNombre) ?>]" 
                                    <?= !$tieneHorarios ? 'disabled' : '' ?> 
                                    onchange="toggleModalidad('<?= $diaNombre ?>')">
                                <option value="medioTiempo" <?= $modalidad === 'medioTiempo' ? 'selected' : '' ?>>Medio tiempo</option>
                                <option value="tiempoCompleto" <?= $modalidad === 'tiempoCompleto' ? 'selected' : '' ?>>Tiempo completo</option>
                            </select>
                        </td>
                        <td>
                            <!-- Horario de mañana -->
                            <div class="row g-2 mb-2 horario-manana">
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Desde</span>
                                        <input type="time" class="form-control" 
                                            id="horaInicio<?= $diaNombre ?>1" 
                                            name="horainicio[<?= strtoupper($diaNombre) ?>][]" 
                                            value="<?= $horaInicio1 ?>"
                                            <?= !$tieneHorarios ? 'disabled' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Hasta</span>
                                        <input type="time" class="form-control" 
                                            id="horaFin<?= $diaNombre ?>1" 
                                            name="horafin[<?= strtoupper($diaNombre) ?>][]" 
                                            value="<?= $horaFin1 ?>"
                                            <?= !$tieneHorarios ? 'disabled' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Horario de tarde -->
                            <div class="row g-2 horario-tarde <?= $modalidad === 'tiempoCompleto' ? '' : 'd-none' ?>">
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Desde</span>
                                        <input type="time" class="form-control" 
                                            id="horaInicio<?= $diaNombre ?>2" 
                                            name="horainicio[<?= strtoupper($diaNombre) ?>][]" 
                                            value="<?= $horaInicio2 ?>"
                                            <?= ($modalidad !== 'tiempoCompleto' || !$tieneHorarios) ? 'disabled' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Hasta</span>
                                        <input type="time" class="form-control" 
                                            id="horaFin<?= $diaNombre ?>2" 
                                            name="horafin[<?= strtoupper($diaNombre) ?>][]" 
                                            value="<?= $horaFin2 ?>"
                                            <?= ($modalidad !== 'tiempoCompleto' || !$tieneHorarios) ? 'disabled' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info py-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Asegúrese de que los horarios no se superpongan y de que haya suficiente 
                    tiempo de descanso entre el turno de mañana y el de la tarde (al menos 30 minutos).
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Guardar Horarios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- NOTA: Estas funciones están definidas en el ámbito global para ser accesibles desde cualquier parte -->
<script>
// Función para manejar la activación/desactivación de campos al hacer toggle en el checkbox "Atiende"
window.toggleHorario = function(dia) {
    console.log("toggleHorario llamado para día:", dia);
    
    const checkbox = document.getElementById(`atiende${dia}`);
    const selectModalidad = document.getElementById(`modalidad${dia}`);
    const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
    const horaFin1 = document.getElementById(`horaFin${dia}1`);
    const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
    const horaFin2 = document.getElementById(`horaFin${dia}2`);
    const contenedorTarde = document.querySelector(`#atiende${dia}`).closest('tr').querySelector('.horario-tarde');
    
    if (checkbox && checkbox.checked) {
        // Habilitar el selector de modalidad
        if (selectModalidad) {
            selectModalidad.disabled = false;
        }
        
        // Habilitar los inputs del primer horario (mañana)
        if (horaInicio1) {
            horaInicio1.disabled = false;
            
            // Establecer hora predeterminada si está vacía
            if (!horaInicio1.value) {
                horaInicio1.value = '08:00';
            }
        }
        
        if (horaFin1) {
            horaFin1.disabled = false;
            
            // Establecer hora predeterminada si está vacía
            if (!horaFin1.value) {
                horaFin1.value = '14:00';
            }
        }
        
        // Verificar la modalidad seleccionada
        if (selectModalidad && selectModalidad.value === 'tiempoCompleto') {
            // Mostrar y habilitar campos del segundo horario (tarde)
            contenedorTarde.classList.remove('d-none');
            
            if (horaInicio2) {
                horaInicio2.disabled = false;
                
                // Establecer hora predeterminada si está vacía
                if (!horaInicio2.value) {
                    horaInicio2.value = '15:00';
                }
            }
            
            if (horaFin2) {
                horaFin2.disabled = false;
                
                // Establecer hora predeterminada si está vacía
                if (!horaFin2.value) {
                    horaFin2.value = '20:00';
                }
            }
        } else {
            // Ocultar campos del segundo horario
            contenedorTarde.classList.add('d-none');
            
            if (horaInicio2) {
                horaInicio2.disabled = true;
            }
            
            if (horaFin2) {
                horaFin2.disabled = true;
            }
        }
    } else {
        // Desactivar todo si el día no está seleccionado
        if (selectModalidad) {
            selectModalidad.disabled = true;
        }
        
        if (horaInicio1) {
            horaInicio1.disabled = true;
        }
        
        if (horaFin1) {
            horaFin1.disabled = true;
        }
        
        // Ocultar contenedor del segundo horario
        contenedorTarde.classList.add('d-none');
        
        if (horaInicio2) {
            horaInicio2.disabled = true;
        }
        
        if (horaFin2) {
            horaFin2.disabled = true;
        }
    }
};

// Función para manejar el cambio de modalidad (Medio tiempo / Tiempo completo)
window.toggleModalidad = function(dia) {
    console.log("toggleModalidad llamado para día:", dia);
    
    const modalidad = document.getElementById(`modalidad${dia}`).value;
    const contenedorTarde = document.querySelector(`#modalidad${dia}`).closest('tr').querySelector('.horario-tarde');
    const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
    const horaFin2 = document.getElementById(`horaFin${dia}2`);
    
    if (modalidad === 'tiempoCompleto') {
        // Mostrar y habilitar campos del segundo horario
        contenedorTarde.classList.remove('d-none');
        
        if (horaInicio2) {
            horaInicio2.disabled = false;
            
            // Establecer hora predeterminada si está vacía
            if (!horaInicio2.value) {
                horaInicio2.value = '15:00';
            }
        }
        
        if (horaFin2) {
            horaFin2.disabled = false;
            
            // Establecer hora predeterminada si está vacía
            if (!horaFin2.value) {
                horaFin2.value = '20:00';
            }
        }
    } else {
        // Ocultar y deshabilitar campos del segundo horario
        contenedorTarde.classList.add('d-none');
        
        if (horaInicio2) {
            horaInicio2.disabled = true;
        }
        
        if (horaFin2) {
            horaFin2.disabled = true;
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM completamente cargado");
    
    // Validar horarios al cambiar
    const validarHorarios = function() {
        const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        let horariosValidos = true;
        
        diasSemana.forEach(dia => {
            if (document.getElementById(`atiende${dia}`) && document.getElementById(`atiende${dia}`).checked) {
                const horaInicio1 = document.getElementById(`horaInicio${dia}1`).value;
                const horaFin1 = document.getElementById(`horaFin${dia}1`).value;
                
                // Validar primer rango horario
                if (horaInicio1 >= horaFin1) {
                    horariosValidos = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Horario inválido',
                        text: `La hora de fin debe ser posterior a la hora de inicio en el turno de mañana del ${dia}`
                    });
                    return;
                }
                
                // Si es tiempo completo, validar segundo rango horario
                if (document.getElementById(`modalidad${dia}`).value === 'tiempoCompleto') {
                    const horaInicio2 = document.getElementById(`horaInicio${dia}2`).value;
                    const horaFin2 = document.getElementById(`horaFin${dia}2`).value;
                    
                    if (horaInicio2 >= horaFin2) {
                        horariosValidos = false;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Horario inválido',
                            text: `La hora de fin debe ser posterior a la hora de inicio en el turno de tarde del ${dia}`
                        });
                        return;
                    }
                    
                    // Validar que haya suficiente descanso entre turnos
                    if (horaInicio2 <= horaFin1) {
                        horariosValidos = false;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Horario inválido',
                            text: `El turno de tarde debe comenzar después del turno de mañana en ${dia}`
                        });
                        return;
                    }
                }
            }
        });
        
        return horariosValidos;
    };
    
    // Validar al cambiar horas
    document.querySelectorAll('input[type="time"]').forEach(input => {
        input.addEventListener('change', validarHorarios);
    });
    
    // Registrar eventos para selector de modalidad
    document.querySelectorAll('.modalidad-select').forEach(select => {
        select.addEventListener('change', validarHorarios);
    });
    
    // Manejar envío del formulario
    document.getElementById('formEditarHorarioAtencion').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar horarios antes de enviar
        if (!validarHorarios()) {
            return;
        }
        
        // Recopilar datos de horarios seleccionados
        const horariosActivos = {};
        let hayHorarioActivo = false;
        
        const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        const diasDB = [1, 2, 3, 4, 5, 6, 7]; // Números correspondientes a los días en la BD
        
        diasSemana.forEach((dia, index) => {
            const atiende = document.getElementById(`atiende${dia}`);
            
            if (atiende && atiende.checked) {
                const modalidad = document.getElementById(`modalidad${dia}`).value;
                const horaInicio1 = document.getElementById(`horaInicio${dia}1`).value;
                const horaFin1 = document.getElementById(`horaFin${dia}1`).value;
                
                // Guardar primer horario
                if (!horariosActivos[diasDB[index]]) {
                    horariosActivos[diasDB[index]] = [];
                }
                
                horariosActivos[diasDB[index]].push({
                    dia: diasDB[index],
                    horainicio: horaInicio1,
                    horafin: horaFin1,
                    intervalo: 30 // Valor fijo para este ejemplo
                });
                
                // Si es tiempo completo, guardar segundo horario
                if (modalidad === 'tiempoCompleto') {
                    const horaInicio2 = document.getElementById(`horaInicio${dia}2`).value;
                    const horaFin2 = document.getElementById(`horaFin${dia}2`).value;
                    
                    horariosActivos[diasDB[index]].push({
                        dia: diasDB[index],
                        horainicio: horaInicio2,
                        horafin: horaFin2,
                        intervalo: 30 // Valor fijo para este ejemplo
                    });
                }
                
                hayHorarioActivo = true;
            }
        });
        
        // Verificar que al menos un día tenga horario asignado
        if (!hayHorarioActivo) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin horarios',
                text: 'Debe seleccionar al menos un día de atención'
            });
            return;
        }
        
        // Mostrar estado de carga
        Swal.fire({
            title: 'Guardando horarios',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Preparar datos del formulario
        const formData = new FormData(this);
        
        // Agregar horarios activos en formato JSON
        formData.append('horarios_activos', JSON.stringify(horariosActivos));
        
        // Enviar datos mediante AJAX
        fetch('../../../controllers/horario.controller.php?op=actualizar_horarios', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.status) {
                // Cerrar el modal
                if (window.parent && window.parent.bootstrap) {
                    const modal = window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('modalHorarioAtencion'));
                    if (modal) {
                        modal.hide();
                    }
                }
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Horarios actualizados',
                    text: data.mensaje || 'Los horarios de atención se han guardado correctamente'
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
                    text: data.mensaje || 'No se pudieron guardar los horarios'
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
    
    // Inicializar horarios al cargar
    console.log("Inicializando estados de horarios");
    const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    diasSemana.forEach(dia => {
        const atiende = document.getElementById(`atiende${dia}`);
        if (atiende && atiende.checked) {
            console.log(`Inicializando día: ${dia}`);
            // Forzar una actualización del estado inicial
            window.toggleHorario(dia);
        }
    });
});
</script>

<style>
    /* Estilos para el formulario completo */
    #formEditarHorarioAtencion {
        min-width: 650px;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Estilos para el modal */
    .modal-xl {
        min-width: 800px !important;
    }
    
    /* Estilos para la tabla de horarios */
    .horario-table {
        width: 100%;
        table-layout: fixed;
    }
    
    /* Mejorar el estilo de los switches */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    
    /* Mejorar elementos de tabla */
    .table th {
        background-color: #f8f9fa;
        white-space: nowrap;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Mejorar visualización de horarios */
    .horario-tarde {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #dee2e6;
    }
    
    /* Adaptar a dispositivos móviles */
    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        
        #formEditarHorarioAtencion {
            min-width: auto;
            width: 100%;
        }
        
        .input-group-sm .input-group-text {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
        }
        
        .input-group-sm .form-control {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
        }
    }
</style>