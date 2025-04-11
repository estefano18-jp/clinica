<?php
// Verificar si se recibió el parámetro nrodoc
if (!isset($_GET['nrodoc']) || empty($_GET['nrodoc'])) {
    echo '
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        No se especificó un doctor para mostrar detalles
    </div>';
    exit;
}

$nrodoc = $_GET['nrodoc'];

// Incluir el controlador
require_once '../../../controllers/doctor.controller.php';

// Crear instancia del controlador de doctor
$controller = new DoctorController();

// Establecer el nrodoc en $_GET para la función obtenerDoctor
$_GET['nrodoc'] = $nrodoc;

// Obtener datos del doctor usando el método del controlador
ob_start();
$controller->obtenerDoctor();
$response = ob_get_clean();

// Decodificar el JSON obtenido
$responseData = json_decode($response, true);

if (!$responseData['status'] || !isset($responseData['data'])) {
    echo '
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        ' . ($responseData['mensaje'] ?? 'No se pudo obtener la información del doctor') . '
    </div>';
    exit;
}

$doctor = $responseData['data'];

// Formatear fecha de nacimiento
$fechaNacimiento = 'No especificada';
if (!empty($doctor['fechanacimiento'])) {
    $fecha = new DateTime($doctor['fechanacimiento']);
    $fechaNacimiento = $fecha->format('d/m/Y');
    
    // Calcular edad
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha)->y;
}

// Formatear género
$genero = '';
switch ($doctor['genero']) {
    case 'M':
        $genero = 'Masculino';
        break;
    case 'F':
        $genero = 'Femenino';
        break;
    case 'OTRO':
        $genero = 'Otro';
        break;
    default:
        $genero = 'No especificado';
}

// Formatear estado
$estadoClass = $doctor['estado'] === 'ACTIVO' ? 'bg-success' : 'bg-danger';
$estadoTexto = $doctor['estado'] === 'ACTIVO' ? 'Activo' : 'Inactivo';

// Ruta de la foto
$fotoUrl = '../../../uploads/doctores/' . ($doctor['foto'] ?? 'default.png');
if (!file_exists($fotoUrl) || empty($doctor['foto'])) {
    $fotoUrl = '../../../assets/img/default-doctor.png';
}
?>

<div class="doctor-detail-container">
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="doctor-photo-container mb-3">
                <?php if (file_exists($fotoUrl)): ?>
                    <img src="<?php echo $fotoUrl; ?>" alt="Foto de <?php echo $doctor['nombres'] . ' ' . $doctor['apellidos']; ?>" class="doctor-photo">
                <?php else: ?>
                    <div class="doctor-photo-placeholder">
                        <i class="fas fa-user-md fa-4x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="doctor-status mb-3">
                <span class="badge <?php echo $estadoClass; ?>">
                    <?php echo $estadoTexto; ?>
                </span>
            </div>
        </div>
        
        <div class="col-md-9">
            <h4 class="doctor-name mb-3">
                <?php echo $doctor['nombres'] . ' ' . $doctor['apellidos']; ?>
            </h4>
            
            <div class="doctor-info">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-stethoscope"></i> Especialidad:
                    </div>
                    <div class="info-value">
                        <?php echo $doctor['especialidad'] ?? 'No especificada'; ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-card"></i> Documento:
                    </div>
                    <div class="info-value">
                        <?php echo $doctor['tipodoc'] . ': ' . $doctor['nrodoc']; ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i> Email:
                    </div>
                    <div class="info-value">
                        <?php echo !empty($doctor['email']) ? $doctor['email'] : 'No especificado'; ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-phone"></i> Teléfono:
                    </div>
                    <div class="info-value">
                        <?php echo !empty($doctor['telefono']) ? $doctor['telefono'] : 'No especificado'; ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-birthday-cake"></i> Fecha de Nacimiento:
                    </div>
                    <div class="info-value">
                        <?php echo $fechaNacimiento; ?>
                        <?php if (isset($edad)): ?>
                            (<?php echo $edad; ?> años)
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-venus-mars"></i> Género:
                    </div>
                    <div class="info-value">
                        <?php echo $genero; ?>
                    </div>
                </div>
                
                <?php if (!empty($doctor['direccion'])): ?>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Dirección:
                    </div>
                    <div class="info-value">
                        <?php echo $doctor['direccion']; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($doctor['credenciales'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="doctor-credentials">
                <h5><i class="fas fa-award me-2"></i>Credenciales Profesionales</h5>
                <div class="credentials-content">
                    <?php echo nl2br($doctor['credenciales']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($doctor['biografia'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="doctor-biography">
                <h5><i class="fas fa-user-md me-2"></i>Biografía</h5>
                <div class="biography-content">
                    <?php echo nl2br($doctor['biografia']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .doctor-detail-container {
        padding: 15px;
    }
    
    .doctor-photo-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    
    .doctor-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .doctor-photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #adb5bd;
    }
    
    .doctor-name {
        font-weight: 600;
        color: #3a3a3a;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .doctor-status .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
    
    .doctor-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .info-item {
        display: flex;
        margin-bottom: 5px;
    }
    
    .info-label {
        font-weight: 600;
        color: #666;
        margin-right: 10px;
        min-width: 120px;
    }
    
    .info-value {
        flex: 1;
    }
    
    .doctor-credentials, .doctor-biography {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .doctor-credentials h5, .doctor-biography h5 {
        color: #0d6efd;
        font-weight: 600;
        margin-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 8px;
    }
    
    .credentials-content, .biography-content {
        margin-top: 10px;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .doctor-info {
            grid-template-columns: 1fr;
        }
        
        .info-item {
            flex-direction: column;
            margin-bottom: 15px;
        }
        
        .info-label {
            margin-bottom: 5px;
        }
    }
</style>