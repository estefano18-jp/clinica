<?php

require_once 'Conexion.php';

class Credencial {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    /**
     * Registra credenciales de acceso para un usuario
     * @param array $datos Datos de las credenciales
     * @return array Resultado de la operación y mensaje
     */
    public function registrarCredenciales($datos) {
        try {
            $pdo = $this->conexion->getConexion();
            
            // Verificar si es un procedimiento almacenado o consulta directa
            if ($this->verificarProcedimientoExiste('sp_registrar_credenciales')) {
                $stmt = $pdo->prepare("CALL sp_registrar_credenciales(?, ?, ?, @resultado, @mensaje, @idusuario)");
                
                $passHash = password_hash($datos['passuser'], PASSWORD_BCRYPT);
                $stmt->execute([
                    $datos['idcontrato'],
                    $datos['nomuser'],
                    $passHash
                ]);
                $stmt->closeCursor();
                
                $result = $pdo->query("SELECT @resultado AS resultado, @mensaje AS mensaje, @idusuario AS idusuario")->fetch(PDO::FETCH_ASSOC);
                return [
                    'status' => (bool)$result['resultado'],
                    'mensaje' => $result['mensaje'],
                    'idusuario' => $result['idusuario']
                ];
            } else {
                // Verificar si el usuario ya existe
                $stmt = $pdo->prepare("SELECT COUNT(*) AS existe FROM usuarios WHERE nomuser = ?");
                $stmt->execute([$datos['nomuser']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result['existe'] > 0) {
                    return [
                        'status' => false,
                        'mensaje' => 'El nombre de usuario ya está en uso',
                        'idusuario' => null
                    ];
                }
                
                // Insertar usuario directamente
                $passHash = password_hash($datos['passuser'], PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (idcontrato, nomuser, passuser, estado, rol) 
                    VALUES (?, ?, ?, TRUE, ?)
                ");
                $rol = isset($datos['rol']) ? $datos['rol'] : 'DOCTOR'; // Por defecto DOCTOR
                $stmt->execute([$datos['idcontrato'], $datos['nomuser'], $passHash, $rol]);
                
                $idusuario = $pdo->lastInsertId();
                
                return [
                    'status' => true,
                    'mensaje' => 'Credenciales de acceso registradas correctamente',
                    'idusuario' => $idusuario
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensaje' => "Error al registrar credenciales: " . $e->getMessage(),
                'idusuario' => null
            ];
        }
    }

    /**
     * Verifica si un procedimiento almacenado existe en la base de datos
     * @param string $nombreProcedimiento Nombre del procedimiento a verificar
     * @return boolean True si existe, False en caso contrario
     */
    private function verificarProcedimientoExiste($nombreProcedimiento) {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS existe
                FROM information_schema.ROUTINES
                WHERE ROUTINE_SCHEMA = 'clinicaDB'
                AND ROUTINE_NAME = ?
            ");
            $stmt->execute([$nombreProcedimiento]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['existe'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Verifica si un nombre de usuario ya existe
     * @param string $nomUser Nombre de usuario a verificar
     * @return boolean True si el usuario existe, False en caso contrario
     */
    public function verificarUsuarioExistente($nomUser) {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("SELECT COUNT(*) AS existe FROM usuarios WHERE nomuser = ?");
            $stmt->execute([$nomUser]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['existe'] > 0;
        } catch (Exception $e) {
            die("Error al verificar usuario: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la información de usuario por ID
     * @param int $idUsuario ID del usuario
     * @return array Datos del usuario
     */
    public function obtenerUsuarioPorId($idUsuario) {
        try {
            $pdo = $this->conexion->getConexion();
            
            if ($this->verificarProcedimientoExiste('sp_obtener_usuario_por_id')) {
                $stmt = $pdo->prepare("CALL sp_obtener_usuario_por_id(?)");
                $stmt->execute([$idUsuario]);
                
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                
                return $usuario;
            } else {
                $stmt = $pdo->prepare("
                    SELECT 
                        u.idusuario,
                        u.idcontrato,
                        u.nomuser,
                        u.estado,
                        u.rol,
                        c.idcolaborador,
                        CONCAT(p.apellidos, ', ', p.nombres) AS colaborador,
                        e.especialidad
                    FROM 
                        usuarios u
                    INNER JOIN 
                        contratos c ON u.idcontrato = c.idcontrato
                    INNER JOIN 
                        colaboradores co ON c.idcolaborador = co.idcolaborador
                    INNER JOIN 
                        personas p ON co.idpersona = p.idpersona
                    LEFT JOIN 
                        especialidades e ON co.idespecialidad = e.idespecialidad
                    WHERE 
                        u.idusuario = ?
                ");
                $stmt->execute([$idUsuario]);
                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            die("Error al obtener usuario: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la lista de usuarios por contrato
     * @param int $idContrato ID del contrato
     * @return array Lista de usuarios
     */
    public function obtenerUsuariosPorContrato($idContrato) {
        try {
            $pdo = $this->conexion->getConexion();
            
            if ($this->verificarProcedimientoExiste('sp_obtener_usuarios_por_contrato')) {
                $stmt = $pdo->prepare("CALL sp_obtener_usuarios_por_contrato(?)");
                $stmt->execute([$idContrato]);
                
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                
                return $usuarios;
            } else {
                $stmt = $pdo->prepare("
                    SELECT 
                        u.idusuario,
                        u.idcontrato,
                        u.nomuser,
                        u.estado,
                        u.rol,
                        CONCAT(p.apellidos, ', ', p.nombres) AS colaborador,
                        e.especialidad
                    FROM 
                        usuarios u
                    INNER JOIN 
                        contratos c ON u.idcontrato = c.idcontrato
                    INNER JOIN 
                        colaboradores co ON c.idcolaborador = co.idcolaborador
                    INNER JOIN 
                        personas p ON co.idpersona = p.idpersona
                    LEFT JOIN 
                        especialidades e ON co.idespecialidad = e.idespecialidad
                    WHERE 
                        u.idcontrato = ?
                    ORDER BY 
                        u.idusuario
                ");
                $stmt->execute([$idContrato]);
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            die("Error al obtener usuarios: " . $e->getMessage());
        }
    }

    /**
     * Cambia el estado de un usuario (activar/desactivar)
     * @param int $idUsuario ID del usuario
     * @param boolean $estado Nuevo estado
     * @return array Resultado de la operación
     */
    public function cambiarEstadoUsuario($idUsuario, $estado) {
        try {
            $pdo = $this->conexion->getConexion();
            
            if ($this->verificarProcedimientoExiste('sp_cambiar_estado_usuario')) {
                $stmt = $pdo->prepare("CALL sp_cambiar_estado_usuario(?, ?, @resultado, @mensaje)");
                $stmt->execute([$idUsuario, $estado]);
                $stmt->closeCursor();
                
                $result = $pdo->query("SELECT @resultado AS resultado, @mensaje AS mensaje")->fetch(PDO::FETCH_ASSOC);
                return [
                    'status' => (bool)$result['resultado'],
                    'mensaje' => $result['mensaje']
                ];
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET estado = ? WHERE idusuario = ?");
                $stmt->execute([$estado, $idUsuario]);
                $filasAfectadas = $stmt->rowCount();
                
                return [
                    'status' => $filasAfectadas > 0,
                    'mensaje' => $filasAfectadas > 0 ? 
                        'Estado de usuario actualizado correctamente' : 
                        'No se pudo actualizar el estado o el usuario no existe'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensaje' => "Error al cambiar estado de usuario: " . $e->getMessage()
            ];
        }
    }
}
?>