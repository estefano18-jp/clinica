<?php
// Incluir el encabezado del administrador que ya tiene la verificación de sesión
include_once "../include/header.administrador.php";

// Conexión a la base de datos
require_once "../../models/Conexion.php";
$conexion = new Conexion();
$pdo = $conexion->getConexion();

// Consultas para obtener datos para el dashboard
// Total de citas programadas para hoy
$fechaHoy = date('Y-m-d');
$queryTotalCitasHoy = "SELECT COUNT(*) as total FROM citas WHERE fecha = :fecha AND estado = 'PROGRAMADA'";
$stmtCitasHoy = $pdo->prepare($queryTotalCitasHoy);
$stmtCitasHoy->bindParam(':fecha', $fechaHoy);
$stmtCitasHoy->execute();
$totalCitasHoy = $stmtCitasHoy->fetch(PDO::FETCH_ASSOC)['total'];

// Total de pacientes registrados - CORREGIDO: ahora cuenta todos los pacientes en la tabla pacientes
$queryTotalPacientes = "SELECT COUNT(*) as total FROM pacientes";
$stmtTotalPacientes = $pdo->prepare($queryTotalPacientes);
$stmtTotalPacientes->execute();
$totalPacientes = $stmtTotalPacientes->fetch(PDO::FETCH_ASSOC)['total'];

// Total de doctores registrados
$queryTotalDoctores = "SELECT COUNT(*) as total FROM colaboradores c 
                      INNER JOIN personas p ON c.idpersona = p.idpersona";
$stmtTotalDoctores = $pdo->prepare($queryTotalDoctores);
$stmtTotalDoctores->execute();
$totalDoctores = $stmtTotalDoctores->fetch(PDO::FETCH_ASSOC)['total'];

// Total de servicios realizados en el mes
$inicioMes = date('Y-m-01');
$finMes = date('Y-m-t');
$queryServiciosMes = "SELECT COUNT(*) as total FROM serviciosrequeridos WHERE fechaanalisis BETWEEN :inicio AND :fin";
$stmtServiciosMes = $pdo->prepare($queryServiciosMes);
$stmtServiciosMes->bindParam(':inicio', $inicioMes);
$stmtServiciosMes->bindParam(':fin', $finMes);
$stmtServiciosMes->execute();
$totalServiciosMes = $stmtServiciosMes->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener las próximas citas de hoy
$queryCitasProximas = "SELECT c.fecha, c.hora, p.nombres, p.apellidos, c.estado, c.idcita, c.idpersona
                      FROM citas c
                      INNER JOIN personas p ON c.idpersona = p.idpersona
                      WHERE c.fecha = :fecha AND c.estado = 'PROGRAMADA'
                      ORDER BY c.hora ASC
                      LIMIT 5";
$stmtCitasProximas = $pdo->prepare($queryCitasProximas);
$stmtCitasProximas->bindParam(':fecha', $fechaHoy);
$stmtCitasProximas->execute();
$citasProximas = $stmtCitasProximas->fetchAll(PDO::FETCH_ASSOC);

// Obtener los últimos diagnósticos registrados
$queryUltimosDiagnosticos = "SELECT d.nombre as diagnostico, p.nombres, p.apellidos, c.fecha
                           FROM consultas c
                           INNER JOIN diagnosticos d ON c.iddiagnostico = d.iddiagnostico
                           INNER JOIN personas p ON c.idpaciente = p.idpersona
                           ORDER BY c.fecha DESC, c.horaatencion DESC
                           LIMIT 5";
$stmtUltimosDiagnosticos = $pdo->prepare($queryUltimosDiagnosticos);
$stmtUltimosDiagnosticos->execute();
$ultimosDiagnosticos = $stmtUltimosDiagnosticos->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos para el gráfico de consultas por especialidad
$queryConsultasPorEspecialidad = "SELECT e.especialidad, COUNT(*) as total
                                FROM consultas c
                                INNER JOIN horarios h ON c.idhorario = h.idhorario
                                INNER JOIN atenciones a ON h.idatencion = a.idatencion
                                INNER JOIN contratos ct ON a.idcontrato = ct.idcontrato
                                INNER JOIN colaboradores cl ON ct.idcolaborador = cl.idcolaborador
                                INNER JOIN especialidades e ON cl.idespecialidad = e.idespecialidad
                                WHERE c.fecha BETWEEN :inicio AND :fin
                                GROUP BY e.especialidad";
$stmtConsultasPorEspecialidad = $pdo->prepare($queryConsultasPorEspecialidad);
$stmtConsultasPorEspecialidad->bindParam(':inicio', $inicioMes);
$stmtConsultasPorEspecialidad->bindParam(':fin', $finMes);
$stmtConsultasPorEspecialidad->execute();
$consultasPorEspecialidad = $stmtConsultasPorEspecialidad->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos para el gráfico de evolución de consultas (últimos 7 días)
$fechaInicio = date('Y-m-d', strtotime('-6 days'));
$queryEvolucionConsultas = "SELECT fecha, COUNT(*) as total 
                           FROM consultas 
                           WHERE fecha BETWEEN :inicio AND :fin 
                           GROUP BY fecha 
                           ORDER BY fecha";
$stmtEvolucionConsultas = $pdo->prepare($queryEvolucionConsultas);
$stmtEvolucionConsultas->bindParam(':inicio', $fechaInicio);
$stmtEvolucionConsultas->bindParam(':fin', $fechaHoy);
$stmtEvolucionConsultas->execute();
$evolucionConsultas = $stmtEvolucionConsultas->fetchAll(PDO::FETCH_ASSOC);

// Formatear los datos para los gráficos
$especialidades = [];
$totalConsultas = [];

foreach ($consultasPorEspecialidad as $consulta) {
    $especialidades[] = $consulta['especialidad'];
    $totalConsultas[] = $consulta['total'];
}

// Preparar datos para el gráfico de evolución
$fechasEvolucion = [];
$totalesEvolucion = [];

// Inicializar el array con los últimos 7 días
for ($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $fechasEvolucion[] = date('d/m', strtotime($fecha));
    $totalesEvolucion[$fecha] = 0;
}

// Llenar con datos reales
foreach ($evolucionConsultas as $consulta) {
    $fechaFormateada = $consulta['fecha'];
    $totalesEvolucion[$fechaFormateada] = $consulta['total'];
}

// Convertir a formato JSON para usar en JavaScript
$especialidadesJSON = json_encode($especialidades);
$totalConsultasJSON = json_encode(array_values($totalConsultas));
$fechasEvolucionJSON = json_encode(array_values($fechasEvolucion));
$totalesEvolucionJSON = json_encode(array_values($totalesEvolucion));
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Panel de Control</li>
    </ol>
    
    <!-- Tarjetas de resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold"><?= $totalCitasHoy ?></h3>
                            <div>Citas para Hoy</div>
                        </div>
                        <i class="fas fa-calendar-check fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/citas/listado.php">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold"><?= $totalPacientes ?></h3>
                            <div>Pacientes Registrados</div>
                        </div>
                        <i class="fas fa-user-injured fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/personas/listado.php?tipo=pacientes">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold"><?= $totalDoctores ?></h3>
                            <div>Doctores Registrados</div>
                        </div>
                        <i class="fas fa-user-md fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/personas/listado.php?tipo=doctores">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold"><?= $totalServiciosMes ?></h3>
                            <div>Servicios este Mes</div>
                        </div>
                        <i class="fas fa-flask fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/servicios/resultados.php">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos y Tablas - Sección Mejorada -->
<div class="row">
    <!-- Gráfico de Consultas por Especialidad -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-bar me-1"></i> Consultas por Especialidad (Mes Actual)</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" id="dropdownEspecialidadBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownEspecialidadBtn">
                            <li><a class="dropdown-item" href="#" id="downloadEspPDF"><i class="fas fa-file-pdf me-2"></i>Exportar PDF</a></li>
                            <li><a class="dropdown-item" href="#" id="refreshEspChart"><i class="fas fa-sync-alt me-2"></i>Actualizar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="consultasPorEspecialidad"></canvas>
                </div>
                <?php if (count($consultasPorEspecialidad) == 0): ?>
                <div class="text-center mt-4 text-muted">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <p>No hay datos disponibles para el mes actual</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de Evolución de Consultas -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow">
            <div class="card-header bg-gradient-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line me-1"></i> Evolución de Consultas (Últimos 7 días)</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" id="dropdownEvolucionBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownEvolucionBtn">
                            <li><a class="dropdown-item" href="#" id="downloadEvoPDF"><i class="fas fa-file-pdf me-2"></i>Exportar PDF</a></li>
                            <li><a class="dropdown-item" href="#" id="refreshEvoChart"><i class="fas fa-sync-alt me-2"></i>Actualizar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="evolucionConsultas"></canvas>
                </div>
                <?php if (count($evolucionConsultas) == 0): ?>
                <div class="text-center mt-4 text-muted">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <p>No hay datos disponibles para los últimos 7 días</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tabla de Citas para Hoy -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow">
            <div class="card-header bg-gradient-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-day me-1"></i> Próximas Citas para Hoy (<?= date('d/m/Y') ?>)</span>
                    <a href="<?= $host ?>/views/citas/nueva.php" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Nueva Cita
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($citasProximas) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover border-top-0">
                        <thead>
                            <tr>
                                <th class="text-nowrap"><i class="far fa-clock me-2"></i>Hora</th>
                                <th><i class="fas fa-user me-2"></i>Paciente</th>
                                <th><i class="fas fa-tag me-2"></i>Estado</th>
                                <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citasProximas as $cita): ?>
                                <tr>
                                    <td class="fw-bold"><?= date('H:i', strtotime($cita['hora'])) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2 bg-primary rounded-circle text-white">
                                                <?= strtoupper(substr($cita['nombres'], 0, 1)) ?>
                                            </div>
                                            <div><?= $cita['nombres'] . ' ' . $cita['apellidos'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="fas fa-calendar-check me-1"></i> <?= $cita['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= $host ?>/views/consultas/registro.php?idcita=<?= $cita['idcita'] ?>&idpersona=<?= $cita['idpersona'] ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Atender">
                                                <i class="fas fa-notes-medical"></i>
                                            </a>
                                            <a href="<?= $host ?>/views/citas/editar.php?id=<?= $cita['idcita'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger cancelar-cita" data-id="<?= $cita['idcita'] ?>" data-bs-toggle="tooltip" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <img src="<?= $host ?>/assets/img/no-appointments.svg" alt="No hay citas" class="img-fluid mb-3" style="max-height: 150px;">
                    <h5 class="text-muted">No hay citas programadas para hoy</h5>
                    <p class="text-muted">Puedes programar una nueva cita haciendo clic en el botón superior</p>
                    <a href="<?= $host ?>/views/citas/nueva.php" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Programar Nueva Cita
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Mostrando las próximas 5 citas</small>
                    <a href="<?= $host ?>/views/citas/listado.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Últimos Diagnósticos -->
    <div class="col-xl-6">
        <div class="card mb-4 shadow">
            <div class="card-header bg-gradient-warning text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clipboard-list me-1"></i> Últimos Diagnósticos</span>
                    <a href="<?= $host ?>/views/consultas/historial.php" class="btn btn-sm btn-light">
                        <i class="fas fa-history"></i> Historial
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($ultimosDiagnosticos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover border-top-0">
                        <thead>
                            <tr>
                                <th class="text-nowrap"><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                <th><i class="fas fa-user me-2"></i>Paciente</th>
                                <th><i class="fas fa-stethoscope me-2"></i>Diagnóstico</th>
                                <th class="text-center"><i class="fas fa-file-medical me-2"></i>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ultimosDiagnosticos as $diagnostico): ?>
                                <tr>
                                    <td class="text-nowrap fw-bold"><?= date('d/m/Y', strtotime($diagnostico['fecha'])) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2 bg-warning rounded-circle text-white">
                                                <?= strtoupper(substr($diagnostico['nombres'], 0, 1)) ?>
                                            </div>
                                            <div><?= $diagnostico['nombres'] . ' ' . $diagnostico['apellidos'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" data-bs-toggle="tooltip" title="<?= $diagnostico['diagnostico'] ?>">
                                            <?= $diagnostico['diagnostico'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $host ?>/views/consultas/detalle.php?id=<?= $diagnostico['idconsulta'] ?? 0 ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <img src="<?= $host ?>/assets/img/no-diagnostics.svg" alt="No hay diagnósticos" class="img-fluid mb-3" style="max-height: 150px;">
                    <h5 class="text-muted">No hay diagnósticos recientes</h5>
                    <p class="text-muted">Los diagnósticos aparecerán aquí después de atender consultas</p>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Mostrando los últimos 5 diagnósticos</small>
                    <a href="<?= $host ?>/views/consultas/historial.php" class="btn btn-sm btn-outline-primary">Ver historial completo</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para los gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de consultas por especialidad
    var ctxEspecialidades = document.getElementById("consultasPorEspecialidad");
    if (ctxEspecialidades) {
        var chartEspecialidades = new Chart(ctxEspecialidades, {
            type: "bar",
            data: {
                labels: <?= $especialidadesJSON ?>,
                datasets: [{
                    label: "Consultas",
                    backgroundColor: "rgba(54, 162, 235, 0.7)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                    data: <?= $totalConsultasJSON ?>
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Gráfico de evolución de consultas
    var ctxEvolucion = document.getElementById("evolucionConsultas");
    if (ctxEvolucion) {
        var chartEvolucion = new Chart(ctxEvolucion, {
            type: "line",
            data: {
                labels: <?= $fechasEvolucionJSON ?>,
                datasets: [{
                    label: "Consultas",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    data: <?= $totalesEvolucionJSON ?>
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php
// Incluir el footer
include_once "../include/footer.php";
?>