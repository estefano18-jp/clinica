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

// Total de pacientes registrados (personas que han tenido consultas)
$queryTotalPacientes = "SELECT COUNT(DISTINCT idpaciente) as total FROM consultas";
$stmtTotalPacientes = $pdo->prepare($queryTotalPacientes);
$stmtTotalPacientes->execute();
$totalPacientes = $stmtTotalPacientes->fetch(PDO::FETCH_ASSOC)['total'];

// Total de consultas realizadas en el mes actual
$inicioMes = date('Y-m-01');
$finMes = date('Y-m-t');
$queryConsultasMes = "SELECT COUNT(*) as total FROM consultas WHERE fecha BETWEEN :inicio AND :fin";
$stmtConsultasMes = $pdo->prepare($queryConsultasMes);
$stmtConsultasMes->bindParam(':inicio', $inicioMes);
$stmtConsultasMes->bindParam(':fin', $finMes);
$stmtConsultasMes->execute();
$totalConsultasMes = $stmtConsultasMes->fetch(PDO::FETCH_ASSOC)['total'];

// Total de servicios realizados en el mes
$queryServiciosMes = "SELECT COUNT(*) as total FROM serviciosrequeridos WHERE fechaanalisis BETWEEN :inicio AND :fin";
$stmtServiciosMes = $pdo->prepare($queryServiciosMes);
$stmtServiciosMes->bindParam(':inicio', $inicioMes);
$stmtServiciosMes->bindParam(':fin', $finMes);
$stmtServiciosMes->execute();
$totalServiciosMes = $stmtServiciosMes->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener las próximas citas de hoy
$queryCitasProximas = "SELECT c.fecha, c.hora, p.nombres, p.apellidos, c.estado
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
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/personas/listado.php">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold"><?= $totalConsultasMes ?></h3>
                            <div>Consultas este Mes</div>
                        </div>
                        <i class="fas fa-stethoscope fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= $host ?>/views/consultas/listado.php">Ver Detalles</a>
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
    
    <!-- Gráficos y Tablas -->
    <div class="row">
        <!-- Gráfico de Consultas por Especialidad -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Consultas por Especialidad (Mes Actual)
                </div>
                <div class="card-body">
                    <canvas id="consultasPorEspecialidad" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Evolución de Consultas -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Evolución de Consultas (Últimos 7 días)
                </div>
                <div class="card-body">
                    <canvas id="evolucionConsultas" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Tabla de Citas para Hoy -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-day me-1"></i>
                    Próximas Citas para Hoy
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($citasProximas) > 0): ?>
                                    <?php foreach ($citasProximas as $cita): ?>
                                        <tr>
                                            <td><?= date('H:i', strtotime($cita['hora'])) ?></td>
                                            <td><?= $cita['nombres'] . ' ' . $cita['apellidos'] ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?= $cita['estado'] ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= $host ?>/views/consultas/registro.php?fecha=<?= $cita['fecha'] ?>&paciente=<?= $cita['nombres'] . ' ' . $cita['apellidos'] ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-notes-medical"></i> Atender
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay citas programadas para hoy</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de Últimos Diagnósticos -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-1"></i>
                    Últimos Diagnósticos
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Paciente</th>
                                    <th>Diagnóstico</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ultimosDiagnosticos) > 0): ?>
                                    <?php foreach ($ultimosDiagnosticos as $diagnostico): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($diagnostico['fecha'])) ?></td>
                                            <td><?= $diagnostico['nombres'] . ' ' . $diagnostico['apellidos'] ?></td>
                                            <td><?= $diagnostico['diagnostico'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No hay diagnósticos recientes</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para los gráficos -->
<script>
    // Gráfico de consultas por especialidad
    var ctxEspecialidades = document.getElementById("consultasPorEspecialidad");
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

    // Gráfico de evolución de consultas
    var ctxEvolucion = document.getElementById("evolucionConsultas");
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
</script>

<?php
// Incluir el footer
include_once "../include/footer.php";
?>