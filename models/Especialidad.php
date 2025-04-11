<?php

require_once 'Conexion.php';

class Especialidad
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    /**
     * Registra una nueva especialidad
     * @param array $datos Datos de la especialidad
     * @return array Resultado de la operación y mensaje
     */
    public function registrarEspecialidad($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Verificar si la especialidad ya existe
            $stmt = $pdo->prepare("SELECT COUNT(*) AS existe FROM especialidades WHERE especialidad = ?");
            $stmt->execute([$datos['especialidad']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['existe'] > 0) {
                return [
                    'status' => false,
                    'mensaje' => 'Esta especialidad ya está registrada',
                    'idespecialidad' => null
                ];
            }

            // Insertar nueva especialidad
            $stmt = $pdo->prepare("
                INSERT INTO especialidades (especialidad, precioatencion)
                VALUES (?, ?)
            ");

            $stmt->execute([
                $datos['especialidad'],
                $datos['precioatencion']
            ]);

            $idespecialidad = $pdo->lastInsertId();

            return [
                'status' => true,
                'mensaje' => 'Especialidad registrada correctamente',
                'idespecialidad' => $idespecialidad
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensaje' => "Error al registrar especialidad: " . $e->getMessage(),
                'idespecialidad' => null
            ];
        }
    }

    /**
     * Obtiene información de una especialidad específica
     * @param int $idEspecialidad ID de la especialidad
     * @return array|null Datos de la especialidad o null si no existe
     */
    public function obtenerEspecialidadPorId($idEspecialidad)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("
                SELECT idespecialidad, especialidad, precioatencion
                FROM especialidades
                WHERE idespecialidad = ?
            ");

            $stmt->execute([$idEspecialidad]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            die("Error al obtener especialidad: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la lista de todas las especialidades
     * @return array Lista de especialidades
     */
    public function listarEspecialidades()
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->query("
                SELECT idespecialidad, especialidad, precioatencion
                FROM especialidades
                ORDER BY especialidad
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Error al listar especialidades: " . $e->getMessage());
        }
    }
    /**
     * Actualiza el precio de atención de una especialidad
     * @param int $idEspecialidad ID de la especialidad
     * @param float $precioAtencion Nuevo precio de atención
     * @return array Resultado de la operación
     */
    public function actualizarPrecioEspecialidad($idEspecialidad, $precioAtencion)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Verificar si la especialidad existe
            $stmt = $pdo->prepare("SELECT COUNT(*) AS existe FROM especialidades WHERE idespecialidad = ?");
            $stmt->execute([$idEspecialidad]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['existe'] == 0) {
                return [
                    'status' => false,
                    'mensaje' => 'La especialidad no existe'
                ];
            }

            // Actualizar precio
            $stmt = $pdo->prepare("
            UPDATE especialidades 
            SET precioatencion = ? 
            WHERE idespecialidad = ?
        ");

            $stmt->execute([$precioAtencion, $idEspecialidad]);

            return [
                'status' => true,
                'mensaje' => 'Precio de atención actualizado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensaje' => "Error al actualizar precio: " . $e->getMessage()
            ];
        }
    }
}
