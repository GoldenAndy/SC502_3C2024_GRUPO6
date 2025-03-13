<?php
require_once __DIR__ . "/../../config/database.php";

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection(); // Llamamos la conexión a la BD
    }

    // 🔹 Registrar un nuevo usuario en la BD y devolver su ID
    public function registrarUsuario($usuario, $email, $password, $rol, $imagenPerfil, $token) {
        $sql = "INSERT INTO usuarios (usuario, email, password, rol, imagen_perfil, token_activacion) 
                VALUES (:usuario, :email, :password, :rol, :imagen_perfil, :token)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":rol", $rol);
        $stmt->bindParam(":imagen_perfil", $imagenPerfil);
        $stmt->bindParam(":token", $token);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Verificar si el usuario ya existe por nombre o email
    public function usuarioExiste($usuario, $email) {
        $sql = "SELECT idUsuario FROM usuarios WHERE usuario = :usuario OR email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false; // Retorna true si el usuario ya existe
    }

    // Activar la cuenta del usuario con el token de activación
    public function activarCuenta($token) {
        $sql = "SELECT idUsuario FROM usuarios WHERE token_activacion = :token LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $sqlUpdate = "UPDATE usuarios SET estado = 1, token_activacion = NULL WHERE idUsuario = :idUsuario";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(":idUsuario", $usuario['idUsuario']);
            return $stmtUpdate->execute();
        }
        return false;
    }

    // Guardar redes sociales asociadas al usuario con su idTipoRed correspondiente
    public function guardarRedesSociales($idUsuario, $redesSociales) {
        $sql = "INSERT INTO redes_sociales (idUsuario, idTipoRed, link_redSocial) 
                VALUES (:idUsuario, :idTipoRed, :link)";
        $stmt = $this->conn->prepare($sql);

        foreach ($redesSociales as $nombreRed => $link) {
            if (!empty($link)) {
                $idTipoRed = $this->obtenerIdTipoRed($nombreRed);
                if ($idTipoRed) {
                    $stmt->bindParam(":idUsuario", $idUsuario);
                    $stmt->bindParam(":idTipoRed", $idTipoRed);
                    $stmt->bindParam(":link", $link);
                    $stmt->execute();
                }
            }
        }
    }

    // Obtener el idTipoRed según el nombre de la red social
    private function obtenerIdTipoRed($nombreRed) {
        $sql = "SELECT idTipoRed FROM tipo_redes WHERE nombre_red = :nombreRed LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombreRed", $nombreRed);
        $stmt->execute();
        $red = $stmt->fetch(PDO::FETCH_ASSOC);
        return $red ? $red['idTipoRed'] : null;
    }


    public function obtenerUsuarioPorEmail($email) {
        $sql = "SELECT idUsuario, usuario, email, password, imagen_perfil, rol FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Obtener la galería del usuario
    public function obtenerGaleria($idUsuario) {
        $sql = "SELECT link_dibujo FROM galeria_personal WHERE idUsuario = :idUsuario ORDER BY fecha_subida DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener las publicaciones del muro
    public function obtenerPublicaciones($idUsuario) {
        $sql = "SELECT contenido, imagen, fecha_publicacion FROM muro_publicaciones WHERE idUsuario = :idUsuario ORDER BY fecha_publicacion DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener información del perfil
    public function obtenerPerfil($idUsuario) {
        $sql = "SELECT descripcion, tos FROM perfil WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener redes sociales del usuario
    public function obtenerRedesSociales($idUsuario) {
        $sql = "SELECT tr.nombre_red, rs.link_redSocial 
                FROM redes_sociales rs
                JOIN tipo_redes tr ON rs.idTipoRed = tr.idTipoRed
                WHERE rs.idUsuario = :idUsuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener TOS del usuario
    public function obtenerTOS($idUsuario) {
        $sql = "SELECT tos FROM perfil WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['tos'] : null;
    }

    // Obtener la cola de comisiones
    public function obtenerColaComisiones($idUsuario) {
        $sql = "SELECT cliente, estado, fecha_creacion FROM comisiones WHERE idUsuario = :idUsuario AND estado = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

}
