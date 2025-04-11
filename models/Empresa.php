<?php
require_once 'Conexion.php';

class Empresa extends Conexion {
    private $pdo;

    public function __CONSTRUCT() {
        $this->pdo = parent::getConexion();
    }

    /**
     * Registrar una nueva empresa
     * @param array $params Datos de la empresa
     * @return array Resultado de la operación
     */
    public function registrar($params = []): array {
        $resultado = [
            'idempresa' => null,
            'mensaje' => ''
        ];
        
        try {
            // Iniciar transacción
            $this->pdo->beginTransaction();
            
            // Validar RUC peruano (11 dígitos)
            if (!preg_match('/^\d{11}$/', $params['ruc'])) {
                throw new Exception("El RUC debe contener exactamente 11 dígitos numéricos.");
            }
            
            // Preparar y ejecutar la llamada al procedimiento almacenado
            $query = $this->pdo->prepare("CALL spu_registrar_empresa(?, ?, ?, ?, ?, ?, @idempresa, @mensaje)");
            
            $query->execute([
                $params['razonsocial'],
                $params['ruc'],
                $params['direccion'],
                $params['nombrecomercial'] ?? '',
                $params['telefono'],
                $params['email']
            ]);
            
            // Obtener las variables de salida
            $queryOutputs = $this->pdo->query("SELECT @idempresa AS idempresa, @mensaje AS mensaje");
            $outputs = $queryOutputs->fetch(PDO::FETCH_ASSOC);
            
            if ($outputs && isset($outputs['idempresa'])) {
                $resultado['idempresa'] = $outputs['idempresa'];
                $resultado['mensaje'] = $outputs['mensaje'];
                
                // Confirmar la transacción
                $this->pdo->commit();
            } else {
                // Si no hay resultados válidos, revertir la transacción
                $this->pdo->rollBack();
                $resultado['mensaje'] = "Error al registrar empresa: No se obtuvo un ID.";
            }
            
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $resultado['mensaje'] = "Error al registrar empresa: " . $e->getMessage();
        }
        
        return $resultado;
    }
}