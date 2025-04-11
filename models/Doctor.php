<?php

require_once 'Conexion.php';

class Doctor
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    /**
     * Verifica si un número de documento ya está registrado como doctor
     * @param string $nroDoc Número de documento a verificar
     * @return boolean True si el documento existe, False en caso contrario
     */
    public function verificarDocumentoDoctor($nroDoc)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_verificar_documento_doctor(?, @existe)");
            $stmt->execute([$nroDoc]);
            $stmt->closeCursor();

            $result = $pdo->query("SELECT @existe AS existe")->fetch(PDO::FETCH_ASSOC);
            return (bool)$result['existe'];
        } catch (Exception $e) {
            die("Error al verificar documento: " . $e->getMessage());
        }
    }

    /**
     * Registra la información personal y profesional de un doctor en un solo proceso
     * @param array $datos Datos personales y profesionales del doctor
     * @return array Resultado de la operación y mensaje
     */
    public function registrarDoctor($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $pdo->beginTransaction();

            // Verificar si la persona ya existe
            $stmtVerificar = $pdo->prepare("SELECT idpersona FROM personas WHERE nrodoc = ?");
            $stmtVerificar->execute([$datos['nrodoc']]);
            $personaExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
            $idpersona = null;

            if ($personaExistente) {
                // La persona ya existe, usar su ID
                $idpersona = $personaExistente['idpersona'];
            } else {
                // Insertar nueva persona
                $stmtPersona = $pdo->prepare("
                    INSERT INTO personas (
                        apellidos, nombres, tipodoc, nrodoc, 
                        telefono, fechanacimiento, genero, 
                        direccion, email
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmtPersona->execute([
                    $datos['apellidos'],
                    $datos['nombres'],
                    $datos['tipodoc'],
                    $datos['nrodoc'],
                    $datos['telefono'],
                    $datos['fechanacimiento'],
                    $datos['genero'],
                    $datos['direccion'],
                    $datos['email']
                ]);

                $idpersona = $pdo->lastInsertId();
            }

            // Verificar si ya existe como colaborador
            $stmtVerificarColaborador = $pdo->prepare("
                SELECT idcolaborador FROM colaboradores WHERE idpersona = ?
            ");
            $stmtVerificarColaborador->execute([$idpersona]);
            $colaboradorExistente = $stmtVerificarColaborador->fetch(PDO::FETCH_ASSOC);
            $idcolaborador = null;

            if ($colaboradorExistente) {
                // Actualizar colaborador existente
                $stmtColaborador = $pdo->prepare("
                    UPDATE colaboradores 
                    SET idespecialidad = ?
                    WHERE idcolaborador = ?
                ");

                $stmtColaborador->execute([
                    $datos['idespecialidad'],
                    $colaboradorExistente['idcolaborador']
                ]);

                $idcolaborador = $colaboradorExistente['idcolaborador'];
            } else {
                // Insertar nuevo colaborador
                $stmtColaborador = $pdo->prepare("
                    INSERT INTO colaboradores (idpersona, idespecialidad)
                    VALUES (?, ?)
                ");

                $stmtColaborador->execute([
                    $idpersona,
                    $datos['idespecialidad']
                ]);

                $idcolaborador = $pdo->lastInsertId();
            }

            $pdo->commit();

            return [
                'status' => true,
                'mensaje' => 'Doctor registrado correctamente',
                'idpersona' => $idpersona,
                'idcolaborador' => $idcolaborador
            ];
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            return [
                'status' => false,
                'mensaje' => "Error al registrar doctor: " . $e->getMessage(),
                'idpersona' => null,
                'idcolaborador' => null
            ];
        }
    }

    /**
     * Obtiene la lista de todos los doctores
     * @return array Lista de doctores
     */
    public function listarDoctores()
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_listar_doctores()");
            $stmt->execute();

            $doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $doctores;
        } catch (Exception $e) {
            die("Error al listar doctores: " . $e->getMessage());
        }
    }

    

    /**
     * Busca un doctor por su número de documento
     * @param string $nroDoc Número de documento
     * @return array|null Datos del doctor o null si no existe
     */
    public function buscarDoctorPorDocumento($nroDoc)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL sp_buscar_doctor_por_documento(?)");
            $stmt->execute([$nroDoc]);

            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $doctor ?: null;
        } catch (Exception $e) {
            die("Error al buscar doctor: " . $e->getMessage());
        }
    }
    /**
     * Obtiene la información completa de un doctor por su número de documento
     * @param string $nroDoc Número de documento del doctor
     * @return array|null Información del doctor o null si no existe
     */
    public function obtenerDoctorPorNroDoc($nroDoc)
    {
        try {
            $pdo = $this->conexion->getConexion();
            
            // Consulta para obtener datos completos del doctor incluyendo especialidad
            $sql = "
                SELECT 
                    p.idpersona,
                    p.apellidos,
                    p.nombres,
                    p.tipodoc,
                    p.nrodoc,
                    p.telefono,
                    p.fechanacimiento,
                    p.genero,
                    p.direccion,
                    p.email,
                    c.idcolaborador,
                    c.idespecialidad,
                    c.estado,
                    e.especialidad,
                    e.precioatencion
                FROM personas p
                INNER JOIN colaboradores c ON p.idpersona = c.idpersona
                INNER JOIN especialidades e ON c.idespecialidad = e.idespecialidad
                WHERE p.nrodoc = ?
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nroDoc]);
            
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $doctor ? $doctor : null;
        } catch (Exception $e) {
            // En producción, es mejor loguear el error que mostrarlo directamente
            error_log("Error al obtener doctor por nro doc: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Edita la información de un doctor existente
     * @param array $datos Datos actualizados del doctor
     * @return array Resultado de la operación
     */
    public function editarDoctor($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $pdo->beginTransaction();

            // 1. Primero obtenemos los IDs necesarios para la actualización
            $stmtIds = $pdo->prepare("
                SELECT p.idpersona, c.idcolaborador, c.idespecialidad
                FROM personas p
                INNER JOIN colaboradores c ON p.idpersona = c.idpersona
                WHERE p.nrodoc = ?
            ");
            $stmtIds->execute([$datos['nrodoc']]);
            $ids = $stmtIds->fetch(PDO::FETCH_ASSOC);

            if (!$ids) {
                return [
                    'status' => false,
                    'mensaje' => 'No se encontró el doctor con el documento especificado'
                ];
            }

            // 2. Actualizar datos de la persona
            $stmtPersona = $pdo->prepare("
                UPDATE personas 
                SET nombres = ?, 
                    apellidos = ?, 
                    telefono = ?, 
                    fechanacimiento = ?, 
                    genero = ?, 
                    direccion = ?, 
                    email = ?
                WHERE idpersona = ?
            ");

            $stmtPersona->execute([
                $datos['nombres'],
                $datos['apellidos'],
                $datos['telefono'],
                $datos['fechanac'],
                $datos['genero'],
                $datos['direccion'],
                $datos['email'],
                $ids['idpersona']
            ]);

            // 3. Actualizar datos del colaborador (incluido el estado)
            $stmtColaborador = $pdo->prepare("
                UPDATE colaboradores 
                SET idespecialidad = ?, 
                    estado = ?
                WHERE idcolaborador = ?
            ");

            // Obtener el ID de la especialidad (puede ser el ID o el nombre dependiendo del form)
            $idespecialidad = $datos['especialidad'];
            // Si no es un número, buscar el ID por nombre
            if (!is_numeric($idespecialidad)) {
                $stmtEsp = $pdo->prepare("SELECT idespecialidad FROM especialidades WHERE especialidad = ?");
                $stmtEsp->execute([$idespecialidad]);
                $espRow = $stmtEsp->fetch(PDO::FETCH_ASSOC);
                $idespecialidad = $espRow ? $espRow['idespecialidad'] : $ids['idespecialidad'];
            }

            $stmtColaborador->execute([
                $idespecialidad,
                $datos['estado'],
                $ids['idcolaborador']
            ]);

            // 4. Actualizar datos adicionales si existen (credenciales, biografía)
            // Esto podría estar en una tabla separada o en campos adicionales de colaboradores
            
            // 5. Actualizar foto si se ha proporcionado una nueva
            if (isset($datos['foto'])) {
                // Aquí iría el código para actualizar la foto en la base de datos
                // Por ejemplo:
                $stmtFoto = $pdo->prepare("
                    UPDATE colaboradores 
                    SET foto = ? 
                    WHERE idcolaborador = ?
                ");
                $stmtFoto->execute([$datos['foto'], $ids['idcolaborador']]);
            }

            $pdo->commit();

            return [
                'status' => true,
                'mensaje' => 'Doctor actualizado correctamente'
            ];
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return [
                'status' => false,
                'mensaje' => 'Error al actualizar doctor: ' . $e->getMessage()
            ];
        }
    }
    /**
     * Actualiza los datos profesionales de un doctor
     * @param int $idcolaborador ID del colaborador a actualizar
     * @param int $idespecialidad ID de la especialidad
     * @param float $precioatencion Precio de atención
     * @return array Resultado de la operación
     */
    public function actualizarDatosProfesionales($idcolaborador, $idespecialidad, $precioatencion = null)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $pdo->beginTransaction();

            // Actualizar especialidad del colaborador
            $sql = "UPDATE colaboradores SET idespecialidad = ? WHERE idcolaborador = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idespecialidad, $idcolaborador]);

            // Si se proporciona un precio de atención personalizado, actualizarlo
            if ($precioatencion !== null) {
                // Aquí podría ir la lógica para manejar precios personalizados
                // por doctor que pueden diferir de los precios estándar de la especialidad
                // Por ejemplo, podría haber una tabla precios_doctores
            }

            $pdo->commit();

            return [
                'status' => true,
                'mensaje' => 'Datos profesionales actualizados correctamente'
            ];
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            return [
                'status' => false,
                'mensaje' => 'Error al actualizar datos profesionales: ' . $e->getMessage()
            ];
        }
    }
    /**
     * Actualiza los datos personales y profesionales de un doctor
     * @param array $datos Datos actualizados del doctor
     * @return array Resultado de la operación
     */
    public function actualizarDoctorCompleto($datos)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $pdo->beginTransaction();

            // 1. Actualizar datos personales
            $sqlPersona = "
                UPDATE personas 
                SET apellidos = ?, 
                    nombres = ?, 
                    telefono = ?, 
                    fechanacimiento = ?, 
                    genero = ?, 
                    direccion = ?, 
                    email = ?
                WHERE idpersona = ?
            ";
            
            $stmtPersona = $pdo->prepare($sqlPersona);
            $stmtPersona->execute([
                $datos['apellidos'],
                $datos['nombres'],
                $datos['telefono'],
                $datos['fechanacimiento'],
                $datos['genero'],
                $datos['direccion'],
                $datos['email'],
                $datos['idpersona']
            ]);

            // 2. Actualizar datos profesionales
            $sqlColaborador = "
                UPDATE colaboradores 
                SET idespecialidad = ?
                WHERE idcolaborador = ?
            ";
            
            $stmtColaborador = $pdo->prepare($sqlColaborador);
            $stmtColaborador->execute([
                $datos['idespecialidad'],
                $datos['idcolaborador']
            ]);

            $pdo->commit();

            return [
                'status' => true,
                'mensaje' => 'Doctor actualizado correctamente'
            ];
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            return [
                'status' => false,
                'mensaje' => 'Error al actualizar doctor: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cambia el estado de un doctor (ACTIVO/INACTIVO) por número de documento
     * @param string $nrodoc Número de documento del doctor
     * @return array Resultado de la operación
     */
    public function cambiarEstadoDoctor($nrodoc)
    {
        try {
            $conexion = $this->conexion->getConexion();

            // Preparar la llamada al procedimiento almacenado
            $stmt = $conexion->prepare("CALL sp_cambiar_estado_doctor(?, @p_resultado, @p_mensaje)");
            $stmt->bindParam(1, $nrodoc, PDO::PARAM_STR);
            $stmt->execute();

            // Obtener los parámetros de salida
            $stmt = $conexion->query("SELECT @p_resultado AS resultado, @p_mensaje AS mensaje");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'status' => $resultado['resultado'] == 1,
                'mensaje' => $resultado['mensaje']
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensaje' => 'Error al cambiar el estado del doctor: ' . $e->getMessage()
            ];
        }
    }


}