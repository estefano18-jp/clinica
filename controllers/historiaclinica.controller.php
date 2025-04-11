<?php
require_once '../models/Historiaclinica.php';

// Create a new instance of the Historiaclinica model
$historiaclinica = new Historiaclinica();

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
                    // Prepare data for registration
                    $datos = [
                        "antecedentepersonales" => $data['antecedentepersonales'] ?? null,
                        "enfermedadactual"      => $data['enfermedadactual'] ?? null
                    ];
                    
                    $resultado = $historiaclinica->registrar($datos);
                    
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