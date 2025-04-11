<?php

require_once 'Conexion.php';

class Usuario extends Conexion
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = parent::getConexion();
    }

    /**
     * Método para autenticar a un administrador
     * @param array $params Parámetros de login (nomuser, passuser)
     * @return array Datos del usuario autenticado
     */
    public function loginAdministrador($params = [])
    {
        try {
            $query = $this->pdo->prepare("CALL spu_usuario_login_administrador(?, ?)");
            $query->execute([$params['nomuser'], $params['passuser']]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Método para registrar un nuevo administrador
     * @param array $params Parámetros del nuevo usuario
     * @return int ID del usuario registrado o -1 en caso de error
     */
    public function registrarAdministrador($params = []): int
    {
        $idusuario = null;
        try {
            $query = $this->pdo->prepare("CALL spu_usuario_registrar_administrador(?, ?, ?, ?)");
            $query->execute(
                array(
                    $params['idpersona'],
                    $params['nomuser'],
                    password_hash($params['passuser'], PASSWORD_BCRYPT),
                    $params['estado']
                )
            );
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $idusuario = $row['idusuario'];
        } catch (Exception $e) {
            $idusuario = -1;
        }
        return $idusuario;
    }

    /**
     * Verifica si existe un usuario con el nombre de usuario proporcionado
     * @param string $nomuser Nombre de usuario a verificar
     * @return bool True si el nombre de usuario ya existe, False en caso contrario
     */
    public function nombreUsuarioExiste($nomuser)
    {
        try {
            $query = $this->pdo->prepare("CALL spu_usuarios_verificar_nombre(?)");
            $query->execute([$nomuser]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['existe'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene los datos de un usuario específico
     * @param int $idUsuario ID del usuario a consultar
     * @return array Datos del usuario o array vacío si no se encuentra
     */
    public function obtenerDatosUsuario($idUsuario)
    {
        try {
            $query = $this->pdo->prepare("CALL spu_usuarios_obtener_datos(?)");
            $query->execute([$idUsuario]);
            return $query->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Verifica si existe una persona con el número de documento proporcionado
     * @param string $nrodoc Número de documento a verificar
     * @return mixed Array con el idpersona si existe, False en caso contrario
     */
    public function verificarPersonaExistente($nrodoc)
    {
        try {
            $query = $this->pdo->prepare("CALL spu_personas_verificar_documento(?)");
            $query->execute([$nrodoc]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Verifica si ya existe un usuario asociado a una persona con el número de documento proporcionado
     * @param string $nrodoc Número de documento a verificar
     * @return bool True si ya existe un usuario con este documento, False en caso contrario
     */
    public function verificarUsuarioExistente($nrodoc)
    {
        try {
            $query = $this->pdo->prepare("CALL spu_usuarios_verificar_documento(?)");
            $query->execute([$nrodoc]);
            return $query->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene información del usuario asociado a un colaborador
     * @param int $idColaborador ID del colaborador
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerUsuarioPorColaborador($idColaborador)
    {
        try {
            // Usar la propiedad pdo existente en lugar de conexion->getConexion()
            $stmt = $this->pdo->prepare("
            SELECT u.* 
            FROM usuarios u
            INNER JOIN contratos c ON u.idcontrato = c.idcontrato
            WHERE c.idcolaborador = ?
            LIMIT 1
        ");

            $stmt->execute([$idColaborador]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener usuario por colaborador: " . $e->getMessage());
            return null;
        }
    }
}
?>