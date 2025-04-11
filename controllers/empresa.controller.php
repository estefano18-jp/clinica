<?php
require_once '../models/Empresa.php';

// Create a new instance of the Empresa model
$empresa = new Empresa();

// Get data from the request
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

try {
    // Set the response header to JSON
    if (php_sapi_name() !== 'cli') {
        header('Content-Type: application/json');
    }

    // Handle POST operations
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($data['operation'])) {
            switch ($data['operation']) {
                case 'registrar':
                    // Validate required fields
                    $camposObligatorios = ['razonsocial', 'ruc', 'direccion', 'telefono', 'email'];
                    
                    foreach ($camposObligatorios as $campo) {
                        if (!isset($data[$campo]) || $data[$campo] === '') {
                            throw new Exception("El campo $campo es obligatorio.");
                        }
                    }

                    // Prepare data for registration
                    $datos = [
                        "razonsocial"      => $data['razonsocial'],
                        "ruc"              => $data['ruc'],
                        "direccion"        => $data['direccion'],
                        "nombrecomercial"  => $data['nombrecomercial'] ?? null,
                        "telefono"         => $data['telefono'],
                        "email"            => $data['email']
                    ];
                    
                    $resultado = $empresa->registrar($datos);
                    
                    // Send response
                    echo json_encode($resultado);
                    break;

                default:
                    throw new Exception("Operación no válida");
            }
        } else {
            throw new Exception("No se especificó una operación");
        }
    } 
    else {
        throw new Exception("Método HTTP no permitido");
    }

} catch (Exception $e) {
    // Handle errors
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
?>