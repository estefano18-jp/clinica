<?php
require_once 'Conexion.php';

class Historiaclinica extends Conexion {
    private $pdo;

    public function __CONSTRUCT() {
        $this->pdo = parent::getConexion();
    }

    /**
     * Registrar una nueva historia clínica
     * @param array $params Datos de la historia clínica
     * @return array Resultado de la operación
     */
    public function registrar($params = []): array {
        $resultado = [
            'idhistoriaclinica' => null,
            'mensaje' => ''
        ];
        
        try {
            // Iniciar transacción
            $this->pdo->beginTransaction();
            
            // Preparar y ejecutar la llamada al procedimiento almacenado
            $query = $this->pdo->prepare("CALL spu_registrar_historia_clinica(?, ?, @idhistoriaclinica, @mensaje)");
            
            $query->execute([
                $params['antecedentepersonales'] ?? '',
                $params['enfermedadactual'] ?? ''
            ]);
            
            // Obtener las variables de salida
            $queryOutputs = $this->pdo->query("SELECT @idhistoriaclinica AS idhistoriaclinica, @mensaje AS mensaje");
            $outputs = $queryOutputs->fetch(PDO::FETCH_ASSOC);
            
            if ($outputs && isset($outputs['idhistoriaclinica'])) {
                $resultado['idhistoriaclinica'] = $outputs['idhistoriaclinica'];
                $resultado['mensaje'] = $outputs['mensaje'];
                
                // Confirmar la transacción
                $this->pdo->commit();
            } else {
                // Si no hay resultados válidos, revertir la transacción
                $this->pdo->rollBack();
                $resultado['mensaje'] = "Error al registrar historia clínica: No se obtuvo un ID.";
            }
            
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $resultado['mensaje'] = "Error al registrar historia clínica: " . $e->getMessage();
        }
        
        return $resultado;
    }
}