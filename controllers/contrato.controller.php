<?php

require_once '../models/Contrato.php';

class ContratoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Contrato();
    }

    /**
     * Registra un nuevo contrato para un colaborador
     */
    public function registrarContrato() {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos del formulario
            $datos = [
                'idcolaborador' => isset($_POST['idcolaborador']) ? intval($_POST['idcolaborador']) : 0,
                'tipocontrato' => isset($_POST['tipocontrato']) ? $_POST['tipocontrato'] : '',
                'fechainicio' => isset($_POST['fechainicio']) ? $_POST['fechainicio'] : '',
                'fechafin' => isset($_POST['fechafin']) && !empty($_POST['fechafin']) ? $_POST['fechafin'] : null
            ];
            
            // Validar campos obligatorios
            if ($datos['idcolaborador'] <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El ID de colaborador es requerido o no válido'
                ]);
                return;
            }
            
            if (empty($datos['tipocontrato'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El tipo de contrato es requerido'
                ]);
                return;
            }
            
            if (empty($datos['fechainicio'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'La fecha de inicio es requerida'
                ]);
                return;
            }
            
            // Registrar contrato
            $resultado = $this->modelo->registrarContrato($datos);
            
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene los contratos de un colaborador específico
     */
    public function obtenerContratosPorColaborador() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar ID del colaborador
            $idColaborador = isset($_GET['idcolaborador']) ? intval($_GET['idcolaborador']) : 0;
            
            if ($idColaborador <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de colaborador no válido'
                ]);
                return;
            }
            
            // Obtener contratos
            $contratos = $this->modelo->obtenerContratosPorColaborador($idColaborador);
            
            echo json_encode([
                'status' => true,
                'data' => $contratos
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene información de un contrato específico
     */
    public function obtenerContrato() {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar ID del contrato
            $idContrato = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($idContrato <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de contrato no válido'
                ]);
                return;
            }
            
            // Obtener datos del contrato
            $contrato = $this->modelo->obtenerContratoPorId($idContrato);
            
            if ($contrato) {
                echo json_encode([
                    'status' => true,
                    'data' => $contrato
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'Contrato no encontrado'
                ]);
            }
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
    $controller = new ContratoController();
    
    switch ($_GET['op']) {
        case 'registrar':
            $controller->registrarContrato();
            break;
        case 'listar':
            $controller->obtenerContratosPorColaborador();
            break;
        case 'obtener':
            $controller->obtenerContrato();
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