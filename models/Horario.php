<?php

require_once 'Conexion.php';

class Horario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    /**
     * Obtiene los horarios de un colaborador específico
     * @param int $idColaborador ID del colaborador
     * @return array Lista de horarios
     */
    public function obtenerHorariosPorColaborador($idColaborador)
    {
        try {
            // Verificar primero si existe la tabla horarios_atencion
            if ($this->verificarTablaHorariosAtencion()) {
                $pdo = $this->conexion->getConexion();
                $stmt = $pdo->prepare("CALL sp_obtener_horarios_por_colaborador(?)");
                $stmt->execute([$idColaborador]);

                $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();

                return $horarios;
            } else {
                // Usar un enfoque alternativo si no existe la tabla horarios_atencion
                return $this->obtenerHorariosAlternativos($idColaborador);
            }
        } catch (Exception $e) {
            // Registrar el error para depuración
            error_log("Error al obtener horarios: " . $e->getMessage());

            // Devolver un array vacío en caso de error
            return [];
        }
    }

    /**
     * Obtiene información de un horario específico
     * @param int $idHorario ID del horario
     * @return array Datos del horario
     */
    public function obtenerHorarioPorId($idHorario)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_obtener_horario_por_id(?)");
            $stmt->execute([$idHorario]);

            $horario = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $horario;
        } catch (Exception $e) {
            die("Error al obtener horario: " . $e->getMessage());
        }
    }
    /**
     * Verifica si existe la tabla horarios_atencion en la base de datos
     * @return boolean True si existe la tabla, False en caso contrario
     */
    public function verificarTablaHorariosAtencion()
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->query("SHOW TABLES LIKE 'horarios_atencion'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            die("Error al verificar tabla: " . $e->getMessage());
        }
    }

    /**
     * Registra un horario de atención para un colaborador
     * @param array $datos Datos del horario
     * @return array Resultado de la operación y mensaje
     */
    public function registrarHorario($datos)
    {
        // Verificar si existe la tabla horarios_atencion
        if ($this->verificarTablaHorariosAtencion()) {
            // Usar el procedimiento almacenado para horarios_atencion
            try {
                $pdo = $this->conexion->getConexion();
                $stmt = $pdo->prepare("CALL sp_registrar_horario(?, ?, ?, ?, @resultado, @mensaje)");

                $stmt->execute([
                    $datos['idcolaborador'],
                    $datos['dia'],
                    $datos['horainicio'],
                    $datos['horafin']
                ]);
                $stmt->closeCursor();

                $result = $pdo->query("SELECT @resultado AS resultado, @mensaje AS mensaje")->fetch(PDO::FETCH_ASSOC);
                return [
                    'status' => (bool) $result['resultado'],
                    'mensaje' => $result['mensaje']
                ];
            } catch (Exception $e) {
                die("Error al registrar horario: " . $e->getMessage());
            }
        } else {
            // Usar el método alternativo para atenciones/horarios
            return $this->registrarHorarioAlternativo($datos);
        }
    }
    /**
     * Registra un horario de forma simple
     * @param array $datos Datos del horario
     * @return array Resultado de la operación
     */
    public function registrarHorarioSimple($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Insertar horario
            $stmt = $pdo->prepare("
            INSERT INTO horarios (idatencion, horainicio, horafin)
            VALUES (?, ?, ?)
        ");
            $stmt->execute([
                $datos['idatencion'],
                $datos['horainicio'],
                $datos['horafin']
            ]);

            $idhorario = $pdo->lastInsertId();

            return [
                'status' => true,
                'mensaje' => 'Horario registrado correctamente',
                'idhorario' => $idhorario
            ];
        } catch (Exception $e) {
            error_log("Error al registrar horario: " . $e->getMessage());

            return [
                'status' => false,
                'mensaje' => 'Error al registrar horario: ' . $e->getMessage(),
                'idhorario' => null
            ];
        }
    }
    /**
     * Registra un horario usando las tablas atenciones y horarios
     * @param array $datos Datos del horario
     * @return array Resultado de la operación y mensaje
     */
    public function registrarHorarioAlternativo($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Iniciar una transacción para asegurar la integridad de los datos
            $pdo->beginTransaction();

            // 1. Verificar que exista el colaborador
            $stmt = $pdo->prepare("
            SELECT idcolaborador 
            FROM colaboradores 
            WHERE idcolaborador = ?
        ");
            $stmt->execute([$datos['idcolaborador']]);
            $colaborador = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$colaborador) {
                return [
                    'status' => false,
                    'mensaje' => 'El colaborador no existe'
                ];
            }

            // 2. Obtener el contrato activo del colaborador
            $stmt = $pdo->prepare("
            SELECT idcontrato
            FROM contratos
            WHERE idcolaborador = ?
            AND (fechafin IS NULL OR fechafin >= CURDATE())
            ORDER BY fechainicio DESC
            LIMIT 1
        ");
            $stmt->execute([$datos['idcolaborador']]);
            $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$contrato) {
                // Si no hay contrato activo, verificar si hay algún contrato
                $stmt = $pdo->prepare("
                SELECT idcontrato
                FROM contratos
                WHERE idcolaborador = ?
                ORDER BY fechainicio DESC
                LIMIT 1
            ");
                $stmt->execute([$datos['idcolaborador']]);
                $contratoAlternativo = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$contratoAlternativo) {
                    return [
                        'status' => false,
                        'mensaje' => 'No se encontró ningún contrato para el colaborador'
                    ];
                }

                $idcontrato = $contratoAlternativo['idcontrato'];
            } else {
                $idcontrato = $contrato['idcontrato'];
            }

            // 3. Verificar si ya existe una atención para ese día y contrato
            $stmt = $pdo->prepare("
            SELECT idatencion 
            FROM atenciones 
            WHERE idcontrato = ? AND diasemana = ?
        ");
            $stmt->execute([$idcontrato, $datos['dia']]);
            $atencionExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($atencionExistente) {
                $idatencion = $atencionExistente['idatencion'];

                // Verificar si ya hay un horario para esta atención
                $stmt = $pdo->prepare("
                SELECT idhorario 
                FROM horarios 
                WHERE idatencion = ?
            ");
                $stmt->execute([$idatencion]);
                $horarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($horarioExistente) {
                    // Actualizar horario existente
                    $stmt = $pdo->prepare("
                    UPDATE horarios 
                    SET horainicio = ?, horafin = ? 
                    WHERE idhorario = ?
                ");
                    $stmt->execute([
                        $datos['horainicio'],
                        $datos['horafin'],
                        $horarioExistente['idhorario']
                    ]);

                    $pdo->commit();
                    return [
                        'status' => true,
                        'mensaje' => 'Horario actualizado correctamente',
                        'idatencion' => $idatencion,
                        'idhorario' => $horarioExistente['idhorario']
                    ];
                } else {
                    // Crear nuevo horario para la atención existente
                    $stmt = $pdo->prepare("
                    INSERT INTO horarios (idatencion, horainicio, horafin) 
                    VALUES (?, ?, ?)
                ");
                    $stmt->execute([$idatencion, $datos['horainicio'], $datos['horafin']]);
                    $idhorario = $pdo->lastInsertId();

                    $pdo->commit();
                    return [
                        'status' => true,
                        'mensaje' => 'Horario registrado correctamente',
                        'idatencion' => $idatencion,
                        'idhorario' => $idhorario
                    ];
                }
            } else {
                // Crear nueva atención y horario
                $stmt = $pdo->prepare("
                INSERT INTO atenciones (idcontrato, diasemana) 
                VALUES (?, ?)
            ");
                $stmt->execute([$idcontrato, $datos['dia']]);
                $idatencion = $pdo->lastInsertId();

                $stmt = $pdo->prepare("
                INSERT INTO horarios (idatencion, horainicio, horafin) 
                VALUES (?, ?, ?)
            ");
                $stmt->execute([$idatencion, $datos['horainicio'], $datos['horafin']]);
                $idhorario = $pdo->lastInsertId();

                $pdo->commit();
                return [
                    'status' => true,
                    'mensaje' => 'Horario registrado correctamente',
                    'idatencion' => $idatencion,
                    'idhorario' => $idhorario
                ];
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Registrar el error para depuración
            error_log("Error al registrar horario: " . $e->getMessage());

            return [
                'status' => false,
                'mensaje' => 'Error al registrar horario: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene los horarios usando un enfoque alternativo
     * @param int $idColaborador ID del colaborador
     * @return array Lista de horarios
     */
    private function obtenerHorariosAlternativos($idColaborador)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Consulta para obtener horarios de las tablas atenciones y horarios
            $stmt = $pdo->prepare("
            SELECT 
                a.diasemana AS dia,
                h.horainicio,
                h.horafin,
                COALESCE(h.intervalo, 30) AS intervalo
            FROM 
                atenciones a
            INNER JOIN 
                horarios h ON a.idatencion = h.idatencion
            INNER JOIN 
                contratos c ON a.idcontrato = c.idcontrato
            WHERE 
                c.idcolaborador = ?
        ");

            $stmt->execute([$idColaborador]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener horarios alternativos: " . $e->getMessage());
            return [];
        }
    }
}
