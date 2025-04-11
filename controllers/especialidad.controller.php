<?php

require_once '../models/Especialidad.php';

class EspecialidadController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Especialidad();
    }

    /**
     * Registra una nueva especialidad
     */
    public function registrarEspecialidad()
    {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos del formulario
            $datos = [
                'especialidad' => isset($_POST['especialidad']) ? $_POST['especialidad'] : '',
                'precioatencion' => isset($_POST['precioatencion']) ? floatval($_POST['precioatencion']) : 0
            ];

            // Validar campos obligatorios
            if (empty($datos['especialidad'])) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El nombre de la especialidad es requerido'
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

            // Registrar especialidad
            $resultado = $this->modelo->registrarEspecialidad($datos);

            echo json_encode($resultado);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }

    /**
     * Obtiene una especialidad específica
     */
    public function obtenerEspecialidad()
    {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Capturar ID de la especialidad
            $idEspecialidad = isset($_GET['id']) ? intval($_GET['id']) : 0;

            if ($idEspecialidad <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de especialidad no válido'
                ]);
                return;
            }

            // Obtener datos de la especialidad
            $especialidad = $this->modelo->obtenerEspecialidadPorId($idEspecialidad);

            if ($especialidad) {
                echo json_encode([
                    'status' => true,
                    'data' => $especialidad
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'Especialidad no encontrada'
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
     * Obtiene la lista de todas las especialidades
     */
    public function listarEspecialidades()
    {
        // Verificar que se reciba el método GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Obtener especialidades
            $especialidades = $this->modelo->listarEspecialidades();

            echo json_encode([
                'status' => true,
                'data' => $especialidades
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'mensaje' => 'Método de solicitud no válido'
            ]);
        }
    }
    /**
     * Actualiza el precio de atención de una especialidad
     */
    public function actualizarPrecioEspecialidad()
    {
        // Verificar que se reciba el método POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturar datos
            $idespecialidad = isset($_POST['idespecialidad']) ? intval($_POST['idespecialidad']) : 0;
            $precioatencion = isset($_POST['precioatencion']) ? floatval($_POST['precioatencion']) : 0;

            // Validar campos
            if ($idespecialidad <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'ID de especialidad no válido'
                ]);
                return;
            }

            if ($precioatencion <= 0) {
                echo json_encode([
                    'status' => false,
                    'mensaje' => 'El precio de atención debe ser mayor a cero'
                ]);
                return;
            }

            // Actualizar precio
            $resultado = $this->modelo->actualizarPrecioEspecialidad($idespecialidad, $precioatencion);

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
    $controller = new EspecialidadController();

    switch ($_GET['op']) {
        case 'registrar':
            $controller->registrarEspecialidad();
            break;
        case 'obtener':
            $controller->obtenerEspecialidad();
            break;
        case 'listar':
            $controller->listarEspecialidades();
            break;
        case 'actualizar_precio':
            $controller->actualizarPrecioEspecialidad();
            break;
        default:
            echo json_encode([
                'status' => false,
                'mensaje' => 'Operación no válida'
            ]);
            break;
    }
}
