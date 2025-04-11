<?php

require_once '../models/Doctor.php';

class DoctorController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Doctor();
    }

    /**
     * Verifica si un documento ya está registrado como doctor
     */
    public function verificarDocumento() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar el número de documento
            $nrodoc = isset($_POST['nrodoc']) ? $_POST['nrodoc'] : '';
            
            if (empty($nrodoc)) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El número de documento es requerido'
                ]);
                return;
            }
            
            // Verificar si el documento existe
            $existe = $this->modelo->verificarDocumentoDoctor($nrodoc);
            
            echo json_encode([
                'status' => true,
                'existe' => $existe,
                'mensaje' => $existe ? 'El documento ya está registrado como doctor' : 'El documento no está registrado como doctor'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Registra un doctor unificando información personal y profesional
     */
    public function registrarDoctor() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos del formulario
            $datos = [
                // Datos personales
                'apellidos' => isset($_POST['apellidos']) ? $_POST['apellidos'] : '',
                'nombres' => isset($_POST['nombres']) ? $_POST['nombres'] : '',
                'tipodoc' => isset($_POST['tipodoc']) ? $_POST['tipodoc'] : '',
                'nrodoc' => isset($_POST['nrodoc']) ? $_POST['nrodoc'] : '',
                'telefono' => isset($_POST['telefono']) ? $_POST['telefono'] : '',
                'fechanacimiento' => isset($_POST['fechanacimiento']) ? $_POST['fechanacimiento'] : '',
                'genero' => isset($_POST['genero']) ? $_POST['genero'] : '',
                'direccion' => isset($_POST['direccion']) ? $_POST['direccion'] : '',
                'email' => isset($_POST['email']) ? $_POST['email'] : '',
                
                // Datos profesionales
                'idespecialidad' => isset($_POST['idespecialidad']) ? intval($_POST['idespecialidad']) : 0,
                'precioatencion' => isset($_POST['precioatencion']) ? floatval($_POST['precioatencion']) : 0
            ];
            
            // Validar campos obligatorios personales
            $camposPersonalesRequeridos = ['apellidos', 'nombres', 'tipodoc', 'nrodoc', 'telefono', 'fechanacimiento', 'genero'];
            foreach ($camposPersonalesRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    echo json_encode([
                        'status' => false,
                        'mensaje' => "El campo {$campo} es requerido"
                    ]);
                    return;
                }
            }
            
            // Validar campos obligatorios profesionales
            if ($datos['idespecialidad'] <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'La especialidad es requerida'
                ]);
                return;
            }
            
            if ($datos['precioatencion'] <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El precio de atención debe ser mayor a cero'
                ]);
                return;
            }
            
            // Registrar doctor
            $resultado = $this->modelo->registrarDoctor($datos);
            
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene la lista de todos los doctores
     */
    public function listarDoctores() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Obtener lista de doctores
            $doctores = $this->modelo->listarDoctores();
            
            echo json_encode([
                'status' => true,
                'data' => $doctores
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }
    
    /**
     * Busca un doctor por su número de documento
     */
    public function buscarDoctorPorDocumento() {
        // Verificar que se reciba el método GET o POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar parámetros
            $nrodoc = '';
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $nrodoc = isset($_GET['nrodoc']) ? $_GET['nrodoc'] : '';
            } else {
                $nrodoc = isset($_POST['nrodoc']) ? $_POST['nrodoc'] : '';
            }
            
            if (empty($nrodoc)) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El número de documento es requerido'
                ]);
                return;
            }
            
            // Buscar al doctor
            $doctor = $this->modelo->buscarDoctorPorDocumento($nrodoc);
            
            if ($doctor) {
                echo json_encode([
                    'status' => true,
                    'data' => $doctor
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'Doctor no encontrado'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }
    /**
     * Obtiene la información completa de un doctor por su número de documento
     */
    public function obtenerDoctor() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar el número de documento
            $nrodoc = isset($_GET['nrodoc']) ? $_GET['nrodoc'] : '';
            
            if (empty($nrodoc)) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El número de documento es requerido'
                ]);
                return;
            }
            
            // Obtener datos del doctor
            $doctor = $this->modelo->buscarDoctorPorDocumento($nrodoc);
            
            if ($doctor) {
                echo json_encode([
                    'status' => true,
                    'data' => $doctor
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'Doctor no encontrado'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }
    /**
     * Edita la información de un doctor existente
     */
    public function editarDoctor() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos del formulario
            $datos = [
                // Datos personales
                'apellidos' => isset($_POST['apellidos']) ? $_POST['apellidos'] : '',
                'nombres' => isset($_POST['nombres']) ? $_POST['nombres'] : '',
                'tipodoc' => isset($_POST['tipodoc']) ? $_POST['tipodoc'] : '',
                'nrodoc' => isset($_POST['nrodoc']) ? $_POST['nrodoc'] : '',
                'telefono' => isset($_POST['telefono']) ? $_POST['telefono'] : '',
                'fechanac' => isset($_POST['fechanac']) ? $_POST['fechanac'] : '',
                'genero' => isset($_POST['genero']) ? $_POST['genero'] : '',
                'direccion' => isset($_POST['direccion']) ? $_POST['direccion'] : '',
                'email' => isset($_POST['email']) ? $_POST['email'] : '',
                'estado' => isset($_POST['estado']) ? $_POST['estado'] : 'ACTIVO',
                
                // Datos profesionales
                'especialidad' => isset($_POST['especialidad']) ? $_POST['especialidad'] : '',
                
                // Datos adicionales
                'credenciales' => isset($_POST['credenciales']) ? $_POST['credenciales'] : '',
                'biografia' => isset($_POST['biografia']) ? $_POST['biografia'] : ''
            ];
            
            // Manejar la foto si se ha subido una nueva
            if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
                // Procesar la foto: tamaño máximo, tipos permitidos, etc.
                $foto = $_FILES['foto'];
                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!in_array($foto['type'], $tiposPermitidos)) {
                    echo json_encode([
                        'status' => false,
                        'mensaje' => 'El tipo de archivo no es válido. Solo se permiten imágenes JPG, PNG y GIF.'
                    ]);
                    return;
                }
                
                if ($foto['size'] > 2097152) { // 2MB en bytes
                    echo json_encode([
                        'status' => false,
                        'mensaje' => 'El tamaño de la imagen no debe superar los 2MB.'
                    ]);
                    return;
                }
                
                // Generar nombre único para la foto
                $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                $nombreFoto = 'doctor_' . $datos['nrodoc'] . '_' . time() . '.' . $extension;
                
                // Ruta donde se guardará la foto
                $rutaDestino = '../../../uploads/doctores/';
                
                // Crear el directorio si no existe
                if (!file_exists($rutaDestino)) {
                    mkdir($rutaDestino, 0777, true);
                }
                
                $rutaCompleta = $rutaDestino . $nombreFoto;
                
                // Mover la foto al directorio de destino
                if (move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {
                    $datos['foto'] = $nombreFoto;
                } else {
                    echo json_encode([
                        'status' => false,
                        'mensaje' => 'Error al subir la imagen. Inténtelo de nuevo.'
                    ]);
                    return;
                }
            }
            
            // Validar campos obligatorios
            $camposRequeridos = ['nrodoc', 'nombres', 'apellidos', 'telefono', 'especialidad'];
            foreach ($camposRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    echo json_encode([
                        'status' => false,
                        'mensaje' => "El campo {$campo} es requerido"
                    ]);
                    return;
                }
            }
            
            // Editar doctor
            $resultado = $this->modelo->editarDoctor($datos);
            
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }
    /**
     * Cambia el estado de un doctor (ACTIVO/INACTIVO) por número de documento
     */
    public function cambiarEstadoDoctor() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar el número de documento
            $nrodoc = isset($_POST['nrodoc']) ? $_POST['nrodoc'] : '';
            
            if (empty($nrodoc)) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El número de documento es requerido'
                ]);
                return;
            }
            
            // Cambiar el estado del doctor
            $resultado = $this->modelo->cambiarEstadoDoctor($nrodoc);
            
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

}

// Procesar la solicitud
if (isset($_GET['op'])) {
    $controller = new DoctorController();
    
    switch ($_GET['op']) {
        case 'verificar':
            $controller->verificarDocumento();
            break;
        case 'registrar':
            $controller->registrarDoctor();
            break;
        case 'listar':
            $controller->listarDoctores();
            break;
        case 'buscar':
            $controller->buscarDoctorPorDocumento();
            break;
        case 'obtener':
            $controller->obtenerDoctor();
            break;
        case 'editar':
            $controller->editarDoctor();
            break;
        case 'cambiarestado':
        case 'cambiar_estado': // Added this case to match your JavaScript call
            $controller->cambiarEstadoDoctor();
            break;
        default:
            echo json_encode([
                'status' => false,
                'mensaje' => 'Operación no válida'
            ]);
            break;
    }
}