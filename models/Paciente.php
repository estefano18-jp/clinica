<?php

require_once 'Conexion.php';

class Paciente extends Conexion
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = parent::getConexion();
    }

    /**
     * Verifica si un documento ya está registrado como paciente
     * @param string $nrodoc Número de documento a verificar
     * @return bool True si el documento ya existe como paciente, False en caso contrario
     */
    public function verificarDocumentoPaciente($nrodoc)
    {
        try {
            $stmt = $this->pdo->prepare("SET @existe = 0");
            $stmt->execute();

            $stmt = $this->pdo->prepare("CALL sp_verificar_documento_paciente(?, @existe)");
            $stmt->bindParam(1, $nrodoc, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->query("SELECT @existe as existe");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['existe'] ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Registra un nuevo paciente
     * @param array $params Parámetros del paciente
     * @return array Resultado de la operación [resultado, mensaje]
     */
    public function registrar($params = [])
    {
        try {
            $stmt = $this->pdo->prepare("SET @resultado = 0, @mensaje = ''");
            $stmt->execute();

            $stmt = $this->pdo->prepare("CALL sp_registrar_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, @resultado, @mensaje)");
            $stmt->bindParam(1, $params['apellidos'], PDO::PARAM_STR);
            $stmt->bindParam(2, $params['nombres'], PDO::PARAM_STR);
            $stmt->bindParam(3, $params['tipodoc'], PDO::PARAM_STR);
            $stmt->bindParam(4, $params['nrodoc'], PDO::PARAM_STR);
            $stmt->bindParam(5, $params['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(6, $params['fechanacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(7, $params['genero'], PDO::PARAM_STR);
            $stmt->bindParam(8, $params['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(9, $params['email'], PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->query("SELECT @resultado as resultado, @mensaje as mensaje");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [
                'resultado' => 0,
                'mensaje' => 'Error al registrar el paciente: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista los pacientes con filtros opcionales
     * @param string|null $busqueda Término de búsqueda (opcional)
     * @param string|null $estado Estado médico del paciente (opcional)
     * @param string|null $genero Género del paciente (opcional)
     * @return array Lista de pacientes
     */
    public function listar($busqueda = null, $estado = null, $genero = null)
    {
        try {
            // Consulta SQL directa para incluir filtro por género
            $sql = "
            SELECT 
                p.idpersona,
                pac.idpaciente,
                p.apellidos,
                p.nombres,
                p.tipodoc,
                p.nrodoc,
                p.telefono,
                p.fechanacimiento,
                p.genero,
                p.direccion,
                p.email,
                pac.fecharegistro
            FROM 
                pacientes pac
            INNER JOIN 
                personas p ON pac.idpersona = p.idpersona
            ";

            // Añadir condiciones de búsqueda si hay parámetros
            $whereClause = [];
            $params = [];

            if ($busqueda) {
                $whereClause[] = "(p.apellidos LIKE ? OR p.nombres LIKE ? OR p.nrodoc LIKE ?)";
                $busquedaParam = "%{$busqueda}%";
                $params[] = $busquedaParam;
                $params[] = $busquedaParam;
                $params[] = $busquedaParam;
            }

            if ($estado) {
                $whereClause[] = "pac.estado = ?";
                $params[] = $estado;
            }

            // Añadir filtro de género si se proporciona
            if ($genero) {
                $whereClause[] = "p.genero = ?";
                $params[] = $genero;
            }

            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(" AND ", $whereClause);
            }

            $sql .= " ORDER BY pac.idpaciente DESC";

            $stmt = $this->pdo->prepare($sql);

            // Asignar parámetros dinámicamente
            foreach ($params as $index => $param) {
                $stmt->bindValue($index + 1, $param);
            }

            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;
        } catch (Exception $e) {
            // Registrar error para depuración
            error_log("Error en método listar de Paciente: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los datos de un paciente específico
     * @param int $idpaciente ID del paciente
     * @return array Datos del paciente o array vacío si no existe
     */
    public function obtenerPorId($idpaciente)
    {
        try {
            // Usar el procedimiento almacenado sp_obtener_paciente_por_id
            $stmt = $this->pdo->prepare("CALL sp_obtener_paciente_por_id(?)");
            $stmt->bindParam(1, $idpaciente, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca pacientes por número de documento
     * @param string $nrodoc Número de documento
     * @return array Datos del paciente o array vacío si no existe
     */
    public function buscarPorDocumento($nrodoc)
    {
        try {
            // Usar el procedimiento almacenado sp_buscar_paciente_por_documento
            $stmt = $this->pdo->prepare("CALL sp_buscar_paciente_por_documento(?)");
            $stmt->bindParam(1, $nrodoc, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Actualiza los datos personales de un paciente
     * @param array $params Parámetros del paciente a actualizar
     * @return array Resultado de la operación
     */
    public function actualizar($params = [])
    {
        try {
            $stmt = $this->pdo->prepare("SET @resultado = 0, @mensaje = ''");
            $stmt->execute();

            $stmt = $this->pdo->prepare("CALL sp_actualizar_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @resultado, @mensaje)");
            $stmt->bindParam(1, $params['idpaciente'], PDO::PARAM_INT);
            $stmt->bindParam(2, $params['apellidos'], PDO::PARAM_STR);
            $stmt->bindParam(3, $params['nombres'], PDO::PARAM_STR);
            $stmt->bindParam(4, $params['tipodoc'], PDO::PARAM_STR);
            $stmt->bindParam(5, $params['nrodoc'], PDO::PARAM_STR);
            $stmt->bindParam(6, $params['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(7, $params['fechanacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(8, $params['genero'], PDO::PARAM_STR);
            $stmt->bindParam(9, $params['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(10, $params['email'], PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->query("SELECT @resultado as resultado, @mensaje as mensaje");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [
                'resultado' => 0,
                'mensaje' => 'Error al actualizar el paciente: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene los datos de un paciente específico
     * @param int $idpaciente ID del paciente
     * @return array Datos del paciente o array vacío si no existe
     */
    public function obtenerPacientePorId($idpaciente)
    {
        try {
            // Usar el procedimiento almacenado sp_obtener_paciente_por_id
            $stmt = $this->pdo->prepare("CALL sp_obtener_paciente_por_id(?)");
            $stmt->bindParam(1, $idpaciente, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca pacientes por diferentes criterios (apellidos, nombres, cualquier tipo de documento)
     * @param string $busqueda Término de búsqueda
     * @return array Lista de pacientes que coinciden con la búsqueda
     */
    public function buscarPacientes($busqueda = "")
    {
        try {
            $query = "SELECT 
                    p.idpersona,
                    pac.idpaciente,
                    p.apellidos,
                    p.nombres,
                    p.tipodoc,
                    p.nrodoc,
                    p.telefono,
                    p.fechanacimiento,
                    p.genero,
                    p.direccion,
                    p.email,
                    pac.fecharegistro
                  FROM 
                    pacientes pac
                  INNER JOIN 
                    personas p ON pac.idpersona = p.idpersona
                  WHERE 
                    p.apellidos LIKE :busqueda OR
                    p.nombres LIKE :busqueda OR
                    p.nrodoc LIKE :busqueda
                  ORDER BY 
                    pac.idpaciente ASC";

            $stmt = $this->pdo->prepare($query);
            $term = "%{$busqueda}%";
            $stmt->bindParam(':busqueda', $term, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtiene las alergias de un paciente específico
     * @param int $idpaciente ID del paciente
     * @return array Lista de alergias del paciente
     */
    public function obtenerAlergiasPorId($idpaciente)
    {
        try {
            // Primero, obtener el idpersona del paciente
            $stmt = $this->pdo->prepare("SELECT idpersona FROM pacientes WHERE idpaciente = ?");
            $stmt->bindParam(1, $idpaciente, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return [];
            }

            $idpersona = $row['idpersona'];

            // Obtener las alergias con el idpersona
            $stmt = $this->pdo->prepare("
                SELECT 
                    la.idlistaalergia AS id,
                    a.tipoalergia,
                    a.alergia,
                    la.gravedad
                FROM 
                    listaalergias la
                INNER JOIN 
                    alergias a ON la.idalergia = a.idalergia
                WHERE 
                    la.idpersona = ?
                ORDER BY 
                    a.tipoalergia, a.alergia
            ");
            $stmt->bindParam(1, $idpersona, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Elimina un paciente por su ID junto con todas sus alergias
     * @param int $idpaciente ID del paciente a eliminar
     * @return array Resultado de la operación
     */
    public function eliminar($idpaciente)
    {
        try {
            // Llamamos al procedimiento almacenado para eliminar el paciente y sus alergias
            $stmt = $this->pdo->prepare("CALL spu_eliminar_paciente_completo(?)");
            $stmt->bindParam(1, $idpaciente, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'resultado' => $resultado['resultado'] ?? 0,
                'mensaje' => $resultado['mensaje'] ?? 'Error al eliminar el paciente'
            ];
        } catch (Exception $e) {
            return [
                'resultado' => 0,
                'mensaje' => 'Error al eliminar el paciente: ' . $e->getMessage()
            ];
        }
    }
}