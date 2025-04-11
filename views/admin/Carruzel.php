<?php 
require_once '../include/header.administrador.php';
require_once '../../models/Conexion.php';

// Creación de conexión
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Variable para almacenar el ID de la imagen seleccionada
$selectedImageId = null;
$selectedImageDescripcion = '';
$errorMessage = null;
$successMessage = null;

// Verificar si existe el directorio para cargas
$uploadDir = '../../img/carrusel/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Crear directorio con permisos
}

if (!is_writable($uploadDir)) {
    $errorMessage = "Error: El directorio de carga no tiene permisos de escritura.";
    error_log("Directory not writable: " . $uploadDir);
}

// Definir nombres fijos para las imágenes del carrusel
$imagenesCarrusel = [
    'imagenCarousel01.jpg',
    'imagenCarousel02.jpg',
    'imagenCarousel03.jpg'
];

// Función para obtener mensajes de error de carga de archivos
function getFileUploadErrorMessage($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return "El archivo subido excede la directiva upload_max_filesize en php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "El archivo subido excede el tamaño máximo permitido por el formulario.";
        case UPLOAD_ERR_PARTIAL:
            return "El archivo se subió parcialmente.";
        case UPLOAD_ERR_NO_FILE:
            return "No se seleccionó ningún archivo para subir.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Falta una carpeta temporal.";
        case UPLOAD_ERR_CANT_WRITE:
            return "No se pudo escribir el archivo en el disco.";
        case UPLOAD_ERR_EXTENSION:
            return "La carga de archivos fue detenida por una extensión PHP.";
        default:
            return "Error desconocido al subir el archivo.";
    }
}

// Contar el número total de imágenes en el carrusel
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM carrusel");
    $stmt->execute();
    $totalImagenes = $stmt->fetchColumn();
} catch (PDOException $e) {
    $errorMessage = "Error al contar imágenes: " . $e->getMessage();
    $totalImagenes = 0;
}

// Manejo de imagen subida
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si es una actualización o inserción
    $isUpdate = isset($_POST['imagenId']) && !empty($_POST['imagenId']);
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    
    // Procesar la imagen si se ha seleccionado
    $hasNewImage = isset($_FILES['imagenCarrusel']) && $_FILES['imagenCarrusel']['error'] == 0;
    
    if ($isUpdate) {
        // CASO DE ACTUALIZACIÓN
        $idImagen = $_POST['imagenId'];
        
        try {
            // Obtenemos el nombre de archivo actual desde la base de datos
            $stmt = $conn->prepare("SELECT imagen FROM carrusel WHERE id = ?");
            $stmt->execute([$idImagen]);
            $imagenActual = $stmt->fetchColumn();
            
            if ($imagenActual) {
                if ($hasNewImage) {
                    // Con nueva imagen: actualizar imagen manteniendo el mismo nombre
                    $fileTmpName = $_FILES['imagenCarrusel']['tmp_name'];
                    $fileType = $_FILES['imagenCarrusel']['type'];
                    
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        $errorMessage = "Error: solo se permiten imágenes JPEG, PNG o GIF.";
                    } else {
                        // Conservamos el mismo nombre para mantener la compatibilidad
                        $uploadFile = $uploadDir . $imagenActual;
                        
                        // Reemplazar el archivo físico
                        if (move_uploaded_file($fileTmpName, $uploadFile)) {
                            // Actualizamos la descripción en la base de datos
                            $stmt = $conn->prepare("UPDATE carrusel SET descripcion = ? WHERE id = ?");
                            $stmt->execute([$descripcion, $idImagen]);
                            
                            $successMessage = "Imagen actualizada correctamente manteniendo el nombre '{$imagenActual}'.";
                        } else {
                            $errorMessage = "Error al reemplazar la imagen. Verifica los permisos de escritura.";
                        }
                    }
                } else {
                    // Sin nueva imagen: actualizar solo descripción
                    $stmt = $conn->prepare("UPDATE carrusel SET descripcion = ? WHERE id = ?");
                    $stmt->execute([$descripcion, $idImagen]);
                    
                    if ($stmt->rowCount() > 0) {
                        $successMessage = "Descripción actualizada correctamente.";
                    } else {
                        $errorMessage = "No se pudo actualizar la descripción o no se realizaron cambios.";
                    }
                }
            } else {
                $errorMessage = "No se encontró la imagen con el ID proporcionado.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        // CASO DE INSERCIÓN
        if ($hasNewImage) {
            $fileTmpName = $_FILES['imagenCarrusel']['tmp_name'];
            $fileType = $_FILES['imagenCarrusel']['type'];
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $errorMessage = "Error: solo se permiten imágenes JPEG, PNG o GIF.";
            } else {
                try {
                    // Verificamos si hay espacio para una nueva imagen
                    if ($totalImagenes < 3) {
                        // Determinamos qué nombre de archivo usar según la posición
                        $posicion = $totalImagenes; // 0, 1 o 2
                        $nombreImagen = $imagenesCarrusel[$posicion]; // Usamos el nombre predefinido correspondiente
                        $uploadFile = $uploadDir . $nombreImagen;
                        
                        if (move_uploaded_file($fileTmpName, $uploadFile)) {
                            // Guardamos el nombre predefinido en la base de datos
                            $stmt = $conn->prepare("INSERT INTO carrusel (imagen, descripcion) VALUES (?, ?)");
                            $stmt->execute([$nombreImagen, $descripcion]);
                            
                            if ($stmt->rowCount() > 0) {
                                $successMessage = "Imagen subida correctamente como $nombreImagen y registrada en la base de datos.";
                            } else {
                                $errorMessage = "No se pudo registrar la imagen en la base de datos.";
                            }
                        } else {
                            $errorMessage = "Error al subir la imagen. Verifica los permisos de escritura.";
                        }
                    } else {
                        $errorMessage = "Ya existen 3 imágenes en el carrusel. Para agregar una nueva, primero reemplace una existente.";
                    }
                } catch (PDOException $e) {
                    $errorMessage = "Error en la base de datos: " . $e->getMessage();
                }
            }
        } else {
            $errorMessage = "No se seleccionó ninguna imagen para subir.";
        }
    }
    
    // Refrescar el conteo de imágenes después de operaciones
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM carrusel");
        $stmt->execute();
        $totalImagenes = $stmt->fetchColumn();
    } catch (PDOException $e) {
        // No es crítico, podemos omitir el manejo aquí
    }
}

// Si se selecciona una imagen para editar
if (isset($_GET['id'])) {
    $selectedImageId = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM carrusel WHERE id = ?");
        $stmt->execute([$selectedImageId]);
        $selectedImage = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($selectedImage) {
            $selectedImageDescripcion = $selectedImage['descripcion'];
        } else {
            $errorMessage = "No se encontró la imagen seleccionada.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Error al obtener datos de la imagen: " . $e->getMessage();
    }
}

// Obtener las últimas 3 imágenes del carrusel
try {
    $stmt = $conn->prepare("SELECT * FROM carrusel ORDER BY id DESC LIMIT 3");
    $stmt->execute();
    $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "Error al obtener las imágenes: " . $e->getMessage();
    $imagenes = [];
}

// Generar un código único para prevenir el cache de imágenes
$cacheBuster = time();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Banner de Carrusel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .avatar-container {
            position: relative;
            margin-bottom: 15px;
            display: inline-block;
        }
        .avatar-container img {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
        .edit-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0,0,0,0.6);
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .selected-image {
            border: 3px solid #198754 !important;
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.5);
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Gestionar Banner de Carrusel</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?php if ($selectedImageId): ?>
                            <i class="fas fa-edit me-2"></i> Reemplazar Imagen del Carrusel
                        <?php else: ?>
                            <?php if ($totalImagenes >= 3): ?>
                                <i class="fas fa-exclamation-triangle me-2"></i> Límite de imágenes alcanzado
                            <?php else: ?>
                                <i class="fas fa-image me-2"></i> Subir Nueva Imagen del Carrusel
                            <?php endif; ?>
                        <?php endif; ?>
                    </h5>
                    <?php if ($selectedImageId): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-plus"></i> Nueva Imagen
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($totalImagenes >= 3 && !$selectedImageId): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> Ya existen 3 imágenes en el carrusel. Para agregar una nueva, primero debe reemplazar una existente.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                            <input type="hidden" id="imagenId" name="imagenId" value="<?php echo $selectedImageId; ?>">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="avatar-container">
                                        <?php if ($selectedImageId && isset($selectedImage)): ?>
                                            <img id="avatarPreview" src="../../img/carrusel/<?php echo htmlspecialchars($selectedImage['imagen']); ?>?v=<?php echo $cacheBuster; ?>" alt="Imagen de Carrusel" onerror="this.src='/api/placeholder/300/200'">
                                        <?php else: ?>
                                            <img id="avatarPreview" src="../../img/carrusel/placeholder.jpg" alt="Imagen de Carrusel" onerror="this.src='/api/placeholder/300/200'">
                                        <?php endif; ?>
                                        <div class="edit-icon">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    </div>
                                    <div class="file-input-container">
                                        <button type="button" class="btn btn-outline-primary w-100" onclick="document.getElementById('imagenCarrusel').click()">
                                            <i class="fas fa-upload me-2"></i> Seleccionar imagen
                                        </button>
                                        <input type="file" id="imagenCarrusel" name="imagenCarrusel" accept="image/*" onchange="previewImage(event)" style="display: none;">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($selectedImageDescripcion); ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <?php if ($selectedImageId): ?>
                                            <i class="fas fa-save me-2"></i> Guardar Cambios
                                        <?php else: ?>
                                            <i class="fas fa-save me-2"></i> Guardar Nueva Imagen
                                        <?php endif; ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i> Imágenes del Carrusel</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (count($imagenes) > 0): ?>
                            <?php foreach ($imagenes as $row): ?>
                                <div class="col-md-4 text-center mb-4">
                                    <div class="card <?php echo ($selectedImageId == $row['id']) ? 'selected-image' : ''; ?>">
                                        <div class="card-body">
                                            <img src="../../img/carrusel/<?php echo htmlspecialchars($row['imagen']); ?>?v=<?php echo $cacheBuster; ?>" class="img-fluid rounded mb-2" alt="Imagen carrusel" onerror="this.src='/api/placeholder/300/200'">
                                            <p class="mt-2"><strong>Nombre:</strong> <?php echo htmlspecialchars($row['imagen']); ?></p>
                                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($row['descripcion']); ?></p>
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>" class="btn btn-warning mt-2">
                                                <i class="fas fa-edit"></i> Reemplazar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center">No hay imágenes en el carrusel.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function previewImage(event) {
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

</body>
</html>

<?php require_once '../include/footer.php'; ?>