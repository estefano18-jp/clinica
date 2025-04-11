<?php

require_once '../models/Credencial.php';

class CredencialController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Credencial();
    }

    /**
     * Registra credenciales de acceso para un usuario
     */
    public function registrarCredenciales() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos del formulario
            $datos = [
                'idcontrato' => isset($_POST['idcontrato']) ? intval($_POST['idcontrato']) : 0,
                'nomuser' => isset($_POST['nomuser']) ? $_POST['nomuser'] : '',
                'passuser' => isset($_POST['passuser']) ? $_POST['passuser'] : '',
                'rol' => isset($_POST['rol']) ? $_POST['rol'] : 'DOCTOR' // Por defecto DOCTOR
            ];
            
            // Validar campos obligatorios
            if ($datos['idcontrato'] <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El ID de contrato es requerido o no válido'
                ]);
                return;
            }
            
            if (empty($datos['nomuser'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El nombre de usuario es requerido'
                ]);
                return;
            }
            
            if (empty($datos['passuser'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'La contraseña es requerida'
                ]);
                return;
            }
            
            // Verificar si el nombre de usuario ya existe
            if ($this->modelo->verificarUsuarioExistente($datos['nomuser'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El nombre de usuario ya está en uso'
                ]);
                return;
            }
            
            // Registrar credenciales
            $resultado = $this->modelo->registrarCredenciales($datos);
            
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene la lista de usuarios por contrato
     */
    public function obtenerUsuariosPorContrato() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar ID del contrato
            $idContrato = isset($_GET['idcontrato']) ? intval($_GET['idcontrato']) : 0;
            
            if ($idContrato <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de contrato no válido'
                ]);
                return;
            }
            
            // Obtener usuarios
            $usuarios = $this->modelo->obtenerUsuariosPorContrato($idContrato);
            
            echo json_encode([
                'status' => true,
                'data' => $usuarios
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene información de un usuario específico
     */
    public function obtenerUsuario() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar ID del usuario
            $idUsuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($idUsuario <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de usuario no válido'
                ]);
                return;
            }
            
            // Obtener datos del usuario
            $usuario = $this->modelo->obtenerUsuarioPorId($idUsuario);
            
            if ($usuario) {
                echo json_encode([
                    'status' => true,
                    'data' => $usuario
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'Usuario no encontrado'
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
     * Cambia el estado de un usuario (activar/desactivar)
     */
    public function cambiarEstadoUsuario() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos
            $idUsuario = isset($_POST['idusuario']) ? intval($_POST['idusuario']) : 0;
            $estado = isset($_POST['estado']) ? boolval($_POST['estado']) : null;
            
            if ($idUsuario <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de usuario no válido'
                ]);
                return;
            }
            
            if ($estado === null) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El estado es requerido'
                ]);
                return;
            }
            
            // Cambiar estado
            $resultado = $this->modelo->cambiarEstadoUsuario($idUsuario, $estado);
            
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
    $controller = new CredencialController();
    
    switch ($_GET['op']) {
        case 'registrar':
            $controller->registrarCredenciales();
            break;
        case 'listar':
            $controller->obtenerUsuariosPorContrato();
            break;
        case 'obtener':
            $controller->obtenerUsuario();
            break;
        case 'cambiarestado':
            $controller->cambiarEstadoUsuario();
            break;
        default:
            echo json_encode([
                'status' => false,
                'mensaje' => 'Operación no válida'
            ]);
            break;
    }
}
?>