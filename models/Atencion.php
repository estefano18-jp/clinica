<?php
// Atencion.php
require_once 'Conexion.php';

class Atencion {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    /**
     * Registra una nueva atención o devuelve una existente
     * @param array $datos Datos de la atención
     * @return array Resultado de la operación
     */
    public function registrarAtencion($datos) {
        try {
            $pdo = $this->conexion->getConexion();
            
            // Verificar si ya existe una atención para ese contrato y día
            $stmt = $pdo->prepare("
                SELECT idatencion 
                FROM atenciones 
                WHERE idcontrato = ? AND diasemana = ?
            ");
            $stmt->execute([$datos['idcontrato'], $datos['diasemana']]);
            $atencionExistente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($atencionExistente) {
                // Retornar la atención existente
                return [
                    'status' => true,
                    'mensaje' => 'Atención ya existente',
                    'idatencion' => $atencionExistente['idatencion']
                ];
            } else {
                // Insertar nueva atención
                $stmt = $pdo->prepare("
                    INSERT INTO atenciones (idcontrato, diasemana)
                    VALUES (?, ?)
                ");
                $stmt->execute([
                    $datos['idcontrato'],
                    $datos['diasemana']
                ]);
                
                $idatencion = $pdo->lastInsertId();
                
                return [
                    'status' => true,
                    'mensaje' => 'Atención registrada correctamente',
                    'idatencion' => $idatencion
                ];
            }
        } catch (Exception $e) {
            error_log("Error al registrar atención: " . $e->getMessage());
            
            return [
                'status' => false,
                'mensaje' => 'Error al registrar atención: ' . $e->getMessage(),
                'idatencion' => null
            ];
        }
    }
}
?>