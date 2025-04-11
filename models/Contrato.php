<?php

require_once 'Conexion.php';

class Contrato {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    /**
     * Registra un contrato para un colaborador
     * @param array $datos Datos del contrato
     * @return array Resultado de la operación y mensaje
     */
    public function registrarContrato($datos) {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_registrar_contrato(?, ?, ?, ?, @resultado, @mensaje, @idcontrato)");
            
            $stmt->execute([
                $datos['idcolaborador'],
                $datos['tipocontrato'],
                $datos['fechainicio'],
                $datos['fechafin']
            ]);
            $stmt->closeCursor();
            
            $result = $pdo->query("SELECT @resultado AS resultado, @mensaje AS mensaje, @idcontrato AS idcontrato")->fetch(PDO::FETCH_ASSOC);
            return [
                'status' => (bool)$result['resultado'],
                'mensaje' => $result['mensaje'],
                'idcontrato' => $result['idcontrato']
            ];
        } catch (Exception $e) {
            die("Error al registrar contrato: " . $e->getMessage());
        }
    }

    /**
     * Obtiene los contratos de un colaborador específico
     * @param int $idColaborador ID del colaborador
     * @return array Lista de contratos
     */
    public function obtenerContratosPorColaborador($idColaborador) {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_obtener_contratos_por_colaborador(?)");
            $stmt->execute([$idColaborador]);
            
            $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            
            return $contratos;
        } catch (Exception $e) {
            die("Error al obtener contratos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene información de un contrato específico
     * @param int $idContrato ID del contrato
     * @return array Datos del contrato
     */
    public function obtenerContratoPorId($idContrato) {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_obtener_contrato_por_id(?)");
            $stmt->execute([$idContrato]);
            
            $contrato = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            
            return $contrato;
        } catch (Exception $e) {
            die("Error al obtener contrato: " . $e->getMessage());
        }
    }
}
?>