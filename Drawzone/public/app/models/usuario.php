<?php
require_once __DIR__ . "/../../config/database.php";

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

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

    public function usuarioExiste($usuario, $email) {
        $sql = "SELECT idUsuario FROM usuarios WHERE usuario = :usuario OR email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

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

    private function obtenerIdTipoRed($nombreRed) {
        $sql = "SELECT idTipoRed FROM tipo_redes WHERE nombre_red = :nombreRed LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombreRed", $nombreRed);
        $stmt->execute();
        $red = $stmt->fetch(PDO::FETCH_ASSOC);
        return $red ? $red['idTipoRed'] : null;
    }


    public function obtenerUsuarioPorEmail($email) {
        $sql = "SELECT idUsuario, usuario, email, password, imagen_perfil, rol, estado, token_activacion 
                FROM usuarios 
                WHERE email = :email 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function obtenerGaleria($idUsuario) {
        $sql = "SELECT 
                    g.idDibujo, 
                    g.titulo, 
                    g.link_dibujo, 
                    g.fecha_subida,
                    e.nombre_estilo, 
                    ti.nombre_ilustracion, 
                    c.nombre_coloreado, 
                    u.usuario AS nombre_usuario
                FROM galeria_personal g
                JOIN estilos e ON g.idEstilo = e.idEstilo
                JOIN tipo_ilustracion ti ON g.idIlustracion = ti.idIlustracion
                JOIN coloreado c ON g.idColoreado = c.idColoreado
                JOIN usuarios u ON g.idUsuario = u.idUsuario
                WHERE g.idUsuario = :idUsuario
                ORDER BY g.fecha_subida DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    


    public function obtenerPublicaciones($idUsuario) {
        $sql = "SELECT idPublicacion, contenido, imagen, fecha_publicacion, likes FROM muro_publicaciones WHERE idUsuario = :idUsuario ORDER BY fecha_publicacion DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerPerfil($idUsuario) {
        $sql = "SELECT p.descripcion, p.tos, u.rol
                FROM perfil p
                LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                WHERE p.idUsuario = :idUsuario
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


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


    public function obtenerTOS($idUsuario) {
        $sql = "SELECT tos FROM perfil WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['tos'] : null;
    }


public function obtenerColaComisiones($idUsuario, $esDueno = false) {
    if ($esDueno) {

        $sql = "SELECT idComision, cliente, estado, fecha_creacion 
                FROM comisiones 
                WHERE idUsuario = :idUsuario 
                ORDER BY fecha_creacion DESC";
    } else {

        $sql = "SELECT idComision, cliente, estado, fecha_creacion 
                FROM comisiones 
                WHERE idUsuario = :idUsuario AND estado != 0 
                ORDER BY fecha_creacion DESC";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":idUsuario", $idUsuario);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    

    public function obtenerIdPorNombre($nombreUsuario) {
        $sql = "SELECT idUsuario FROM usuarios WHERE usuario = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombreUsuario);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['idUsuario'] : null;
    }

    
    public function listarConversaciones($idUsuario) {
        $sql = "SELECT c.idConversacion, u.idUsuario, u.usuario, u.imagen_perfil
                FROM conversaciones c
                JOIN usuarios u ON (u.idUsuario = IF(c.idUsuario1 = :idUsuario, c.idUsuario2, c.idUsuario1))
                WHERE c.idUsuario1 = :idUsuario OR c.idUsuario2 = :idUsuario
                ORDER BY c.ultima_actualizacion DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listarMensajes($idConversacion) {
        $sql = "SELECT m.*, u.usuario AS remitente_nombre, u.imagen_perfil
                FROM mensajes m
                JOIN usuarios u ON m.idRemitente = u.idUsuario
                WHERE m.idConversacion = :idConversacion
                ORDER BY m.fecha_envio ASC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idConversacion", $idConversacion);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function guardarMensaje($idConversacion, $idRemitente, $contenido) {
        $sql = "INSERT INTO mensajes (idConversacion, idRemitente, contenido, leido, estado)
                VALUES (:idConversacion, :idRemitente, :contenido, 0, 1)";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idConversacion", $idConversacion);
        $stmt->bindParam(":idRemitente", $idRemitente);
        $stmt->bindParam(":contenido", $contenido);
        $stmt->execute();
    

        $update = "UPDATE conversaciones SET ultima_actualizacion = NOW() WHERE idConversacion = :idConversacion";
        $stmt2 = $this->conn->prepare($update);
        $stmt2->bindParam(":idConversacion", $idConversacion);
        $stmt2->execute();
    
        return true;
    }


    public function buscarConversacion($idUsuario1, $idUsuario2) {
        $sql = "SELECT idConversacion FROM conversaciones
                WHERE (idUsuario1 = :id1 AND idUsuario2 = :id2)
                   OR (idUsuario1 = :id2 AND idUsuario2 = :id1) LIMIT 1";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id1", $idUsuario1);
        $stmt->bindParam(":id2", $idUsuario2);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['idConversacion'] : null;
    }

    

    public function crearConversacion($idUsuario1, $idUsuario2) {
        $sql = "INSERT INTO conversaciones (idUsuario1, idUsuario2) 
                VALUES (:id1, :id2)";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id1", $idUsuario1);
        $stmt->bindParam(":id2", $idUsuario2);
        $stmt->execute();
    
        return $this->conn->lastInsertId();
    }
    


    public function obtenerConversaciones($idUsuario) {
        $sql = "
            SELECT 
                c.idConversacion,
                u.idUsuario,
                u.usuario,
                u.imagen_perfil,
                (
                    SELECT contenido 
                    FROM mensajes m 
                    WHERE m.idConversacion = c.idConversacion 
                    ORDER BY m.fecha_envio DESC 
                    LIMIT 1
                ) AS ultimo_mensaje
            FROM conversaciones c
            JOIN usuarios u 
                ON (u.idUsuario = IF(c.idUsuario1 = :id, c.idUsuario2, c.idUsuario1))
            WHERE c.idUsuario1 = :id OR c.idUsuario2 = :id
            ORDER BY c.ultima_actualizacion DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function guardarDibujoGaleria($idUsuario, $titulo, $linkDibujo, $idEstilo, $idIlustracion, $idColoreado) {
        $stmt = $this->conn->prepare("
            INSERT INTO galeria_personal (idUsuario, titulo, link_dibujo, idEstilo, idIlustracion, idColoreado)
            VALUES (:idUsuario, :titulo, :linkDibujo, :idEstilo, :idIlustracion, :idColoreado)
        ");
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':linkDibujo', $linkDibujo, PDO::PARAM_STR);
        $stmt->bindParam(':idEstilo', $idEstilo, PDO::PARAM_INT);
        $stmt->bindParam(':idIlustracion', $idIlustracion, PDO::PARAM_INT);
        $stmt->bindParam(':idColoreado', $idColoreado, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    


    public function guardarPublicacionMuro($idUsuario, $contenido, $imagen = null) {
        $sql = "INSERT INTO muro_publicaciones (idUsuario, contenido, imagen) VALUES (:idUsuario, :contenido, :imagen)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        return $stmt->execute();
    }
    

    
    public function obtenerEstilos() {
        $sql = "SELECT idEstilo, nombre_estilo FROM estilos ORDER BY nombre_estilo ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function actualizarTOS($idUsuario, $tos) {

        $check = $this->conn->prepare("SELECT idPerfil FROM perfil WHERE idUsuario = :idUsuario");
        $check->bindParam(':idUsuario', $idUsuario);
        $check->execute();
    
        if ($check->rowCount() > 0) {

            $stmt = $this->conn->prepare("UPDATE perfil SET tos = :tos WHERE idUsuario = :idUsuario");
        } else {

            $stmt = $this->conn->prepare("INSERT INTO perfil (idUsuario, tos) VALUES (:idUsuario, :tos)");
        }
    
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':tos', $tos);
        return $stmt->execute();
    }


    public function crearComision($idUsuario, $cliente, $estado) {
        $stmt = $this->conn->prepare("
            INSERT INTO comisiones (idUsuario, cliente, estado)
            VALUES (:idUsuario, :cliente, :estado)
        ");
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function actualizarEstadoComision($idUsuario, $idComision, $nuevoEstado) {
        $stmt = $this->conn->prepare("
            UPDATE comisiones 
            SET estado = :estado 
            WHERE idComision = :idComision AND idUsuario = :idUsuario
        ");
        $stmt->bindParam(':estado', $nuevoEstado, PDO::PARAM_INT);
        $stmt->bindParam(':idComision', $idComision, PDO::PARAM_INT);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function aumentarLikesPublicacion($idPublicacion) {
        $sql = "UPDATE muro_publicaciones SET likes = likes + 1 WHERE idPublicacion = :idPublicacion";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idPublicacion", $idPublicacion, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerInfoBasicaUsuario($idUsuario) {
        $sql = "SELECT usuario, imagen_perfil FROM usuarios WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function alternarLikePublicacion($idUsuario, $idPublicacion) {

        $sqlCheck = "SELECT COUNT(*) FROM likes_publicaciones WHERE idUsuario = :idUsuario AND idPublicacion = :idPublicacion";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([
            ":idUsuario" => $idUsuario,
            ":idPublicacion" => $idPublicacion
        ]);
        $yaLike = $stmt->fetchColumn() > 0;
    
        if ($yaLike) {

            $sqlRemove = "DELETE FROM likes_publicaciones WHERE idUsuario = :idUsuario AND idPublicacion = :idPublicacion";
            $this->conn->prepare($sqlRemove)->execute([
                ":idUsuario" => $idUsuario,
                ":idPublicacion" => $idPublicacion
            ]);
    
            $dioLike = false;
            $this->conn->prepare("UPDATE muro_publicaciones SET likes = likes - 1 WHERE idPublicacion = :idPublicacion")
                ->execute([":idPublicacion" => $idPublicacion]);
        } else {

            $sqlAdd = "INSERT INTO likes_publicaciones (idUsuario, idPublicacion) VALUES (:idUsuario, :idPublicacion)";
            $this->conn->prepare($sqlAdd)->execute([
                ":idUsuario" => $idUsuario,
                ":idPublicacion" => $idPublicacion
            ]);
    
            $dioLike = true;
            $this->conn->prepare("UPDATE muro_publicaciones SET likes = likes + 1 WHERE idPublicacion = :idPublicacion")
                ->execute([":idPublicacion" => $idPublicacion]);
        }
    

        $stmt = $this->conn->prepare("SELECT likes FROM muro_publicaciones WHERE idPublicacion = :idPublicacion");
        $stmt->execute([":idPublicacion" => $idPublicacion]);
        $nuevoTotal = (int)$stmt->fetchColumn();
    

        return [
            "likes" => $nuevoTotal,
            "dioLike" => $dioLike
        ];
    }
    


    public function obtenerLikesUsuario($idUsuario) {
        $sql = "SELECT idPublicacion FROM likes_publicaciones WHERE idUsuario = :idUsuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idUsuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function obtenerFichasPrecios($idUsuario) {
        $sql = "SELECT * FROM fichas_precios WHERE idUsuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insertarFichaPrecios($idUsuario, $linkFicha) {
        $sql = "INSERT INTO fichas_precios (idUsuario, link_ficha)
                VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idUsuario, $linkFicha]);
    }


    public function actualizarFichaPrecio($idFicha, $linkFicha) {
        $sql = "UPDATE fichas_precios 
                SET link_ficha = ?
                WHERE idFicha = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$linkFicha, $idFicha]);
    }


    public function eliminarFicha($idFicha) {
        $sql = "DELETE FROM fichas_precios WHERE idFicha = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idFicha]);
    }



    public function obtenerRangoPrecios($idUsuario) {
        $sql = "SELECT * FROM rango_precios WHERE idUsuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function insertarRangoPrecios($idUsuario, $min, $max) {
        $sql = "INSERT INTO rango_precios (idUsuario, precio_minimo, precio_maximo) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idUsuario, $min, $max]);
    }
    
    public function actualizarRangoPrecios($idUsuario, $min, $max) {
        $sql = "UPDATE rango_precios SET precio_minimo = ?, precio_maximo = ? WHERE idUsuario = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$min, $max, $idUsuario]);
    }


    public function obtenerTiposIlustracion() {
        $sql = "SELECT idIlustracion, nombre_ilustracion FROM tipo_ilustracion ORDER BY nombre_ilustracion ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerTiposColoreado() {
        $sql = "SELECT idColoreado, nombre_coloreado FROM coloreado ORDER BY nombre_coloreado ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerGaleriaDestacada($limite = 400, $offset = 0) {
        $sql = "SELECT 
                    gp.idDibujo,
                    gp.titulo,
                    gp.link_dibujo,
                    gp.fecha_subida,
                    e.nombre_estilo,
                    ti.nombre_ilustracion,
                    c.nombre_coloreado,
                    u.usuario AS nombre_usuario,
                    u.idUsuario
                FROM galeria_personal gp
                JOIN estilos e ON gp.idEstilo = e.idEstilo
                JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                JOIN coloreado c ON gp.idColoreado = c.idColoreado
                JOIN usuarios u ON gp.idUsuario = u.idUsuario
                ORDER BY gp.fecha_subida DESC, gp.idDibujo DESC
                LIMIT :limite OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    


    public function buscarDibujosPorTitulo($busqueda) {
        $sql = "SELECT gp.*, u.usuario AS nombre_usuario, e.nombre_estilo, ti.nombre_ilustracion, c.nombre_coloreado
                FROM galeria_personal gp
                INNER JOIN usuarios u ON gp.idUsuario = u.idUsuario
                INNER JOIN estilos e ON gp.idEstilo = e.idEstilo
                INNER JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                INNER JOIN coloreado c ON gp.idColoreado = c.idColoreado
                WHERE LOWER(gp.titulo) LIKE LOWER(CONCAT('%', ?, '%'))
                ORDER BY gp.fecha_subida DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$busqueda]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarDibujosPorFiltrosAvanzados($filtros) {
        $sql = "SELECT gp.*, u.usuario AS nombre_usuario, e.nombre_estilo, ti.nombre_ilustracion, c.nombre_coloreado
                FROM galeria_personal gp
                INNER JOIN usuarios u ON gp.idUsuario = u.idUsuario
                INNER JOIN estilos e ON gp.idEstilo = e.idEstilo
                INNER JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                INNER JOIN coloreado c ON gp.idColoreado = c.idColoreado
                INNER JOIN rango_precios rp ON u.idUsuario = rp.idUsuario
                WHERE 1=1";
    
        $params = [];
    
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (gp.titulo LIKE :busqueda OR u.usuario LIKE :busqueda)";
            $params[':busqueda'] = '%' . trim($filtros['busqueda']) . '%';
        }
    
        $filtroCampos = [
            'estilos'        => 'e.nombre_estilo',
            'ilustraciones'  => 'ti.nombre_ilustracion',
            'coloreados'     => 'c.nombre_coloreado'
        ];
    
        foreach ($filtroCampos as $clave => $campoBD) {
            if (!empty($filtros[$clave])) {
                $in = [];
                foreach ($filtros[$clave] as $i => $valor) {
                    $param = ":{$clave}_$i";
                    $in[] = $param;
                    $params[$param] = trim($valor);
                }
                $sql .= " AND $campoBD IN (" . implode(',', $in) . ")";
            }
        }
    
        if (!is_null($filtros['precio_min'])) {
            $sql .= " AND rp.precio_minimo >= :precio_min";
            $params[':precio_min'] = $filtros['precio_min'];
        }
        if (!is_null($filtros['precio_max'])) {
            $sql .= " AND rp.precio_maximo <= :precio_max";
            $params[':precio_max'] = $filtros['precio_max'];
        }
        
    
        $sql .= " ORDER BY gp.idDibujo DESC LIMIT :limite OFFSET :offset";
        $params[':limite'] = (int)($filtros['limite'] ?? 400);
        $params[':offset'] = (int)($filtros['offset'] ?? 0);
    
    
        $stmt = $this->conn->prepare($sql);
    
        foreach ($params as $clave => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($clave, $valor, $tipo);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    

    public function buscarUsuariosPorFiltrosAvanzados($filtros) {
        $rolesSeleccionados = $filtros['tipo_usuario'] ?? [];
    
        $rolesNorm = array_map('strtolower', $rolesSeleccionados);
        sort($rolesNorm);
        $soloAmbos = (count($rolesNorm) === 2 && in_array('artista', $rolesNorm) && in_array('comprador', $rolesNorm));
    
        $esSoloCompradores = (
            !$soloAmbos &&
            !empty($rolesSeleccionados) &&
            count($rolesSeleccionados) === 1 &&
            in_array('comprador', $rolesSeleccionados) &&
            empty($filtros['estilos']) &&
            empty($filtros['ilustraciones']) &&
            empty($filtros['coloreados']) &&
            is_null($filtros['precio_min']) &&
            is_null($filtros['precio_max'])
        );
    
        $params = [];
    
        if ($esSoloCompradores || $soloAmbos) {
            $sql = "SELECT u.idUsuario, u.usuario, u.imagen_perfil, u.rol
                    FROM usuarios u
                    WHERE ";
    
            if ($soloAmbos) {
                $sql .= "u.rol = 'ambos'";
            } else {
                $sql .= "u.rol = 'comprador'";
            }
    
            if (!empty($filtros['busqueda'])) {
                $sql .= " AND LOWER(u.usuario) LIKE LOWER(:busqueda)";
                $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
            }
    
        } else {
            $sql = "SELECT DISTINCT u.idUsuario, u.usuario, u.imagen_perfil, u.rol
                    FROM usuarios u
                    INNER JOIN galeria_personal gp ON u.idUsuario = gp.idUsuario
                    INNER JOIN estilos e ON gp.idEstilo = e.idEstilo
                    INNER JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                    INNER JOIN coloreado c ON gp.idColoreado = c.idColoreado
                    INNER JOIN rango_precios rp ON u.idUsuario = rp.idUsuario
                    WHERE 1=1";
    
            if (!empty($rolesSeleccionados)) {
                $in = [];
                foreach ($rolesSeleccionados as $i => $rol) {
                    $key = ":rol_$i";
                    $in[] = $key;
                    $params[$key] = $rol;
                }
                $sql .= " AND u.rol IN (" . implode(',', $in) . ")";
            }
    
            if (!empty($filtros['busqueda'])) {
                $sql .= " AND LOWER(u.usuario) LIKE LOWER(:busqueda)";
                $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
            }
    
            if (!empty($filtros['estilos'])) {
                $in = [];
                foreach ($filtros['estilos'] as $i => $estilo) {
                    $key = ":estilo_$i";
                    $in[] = $key;
                    $params[$key] = $estilo;
                }
                $sql .= " AND e.nombre_estilo IN (" . implode(',', $in) . ")";
            }
    
            if (!empty($filtros['ilustraciones'])) {
                $in = [];
                foreach ($filtros['ilustraciones'] as $i => $ilust) {
                    $key = ":ilustracion_$i";
                    $in[] = $key;
                    $params[$key] = $ilust;
                }
                $sql .= " AND ti.nombre_ilustracion IN (" . implode(',', $in) . ")";
            }
    
            if (!empty($filtros['coloreados'])) {
                $in = [];
                foreach ($filtros['coloreados'] as $i => $col) {
                    $key = ":coloreado_$i";
                    $in[] = $key;
                    $params[$key] = $col;
                }
                $sql .= " AND c.nombre_coloreado IN (" . implode(',', $in) . ")";
            }
    
            if (!is_null($filtros['precio_min'])) {
                $sql .= " AND rp.precio_maximo >= :precio_min";
                $params[':precio_min'] = $filtros['precio_min'];
            }
    
            if (!is_null($filtros['precio_max'])) {
                $sql .= " AND rp.precio_minimo <= :precio_max";
                $params[':precio_max'] = $filtros['precio_max'];
            }
        }
    
        $sql .= " ORDER BY u.usuario ASC LIMIT :limite OFFSET :offset";
        $params[':limite'] = (int)($filtros['limite'] ?? 400);
        $params[':offset'] = (int)($filtros['offset'] ?? 0);
    
        $stmt = $this->conn->prepare($sql);
    
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public function obtenerRolUsuario($idUsuario) {
        $sql = "SELECT rol FROM usuarios WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['rol'] : null;
    }
    
    public function obtenerRolPorId($idUsuario) {
        $sql = "SELECT rol FROM usuarios WHERE idUsuario = :idUsuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['rol'] : null;
    }


    public function obtenerPublicacionPorId($idPublicacion) {
        $sql = "SELECT * FROM muro_publicaciones WHERE idPublicacion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $idPublicacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function editarPublicacionMuro($idPublicacion, $contenido, $imagen) {
        $sql = "UPDATE muro_publicaciones SET contenido = :contenido, imagen = :imagen WHERE idPublicacion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idPublicacion, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminarPublicacionMuro($idPublicacion) {
        $sql = "DELETE FROM muro_publicaciones WHERE idPublicacion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $idPublicacion, PDO::PARAM_INT);
        return $stmt->execute();
    }
    

    public function obtenerReviews($idArtista) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.usuario AS nombre_cliente
            FROM reseñas_artistas r
            INNER JOIN usuarios u ON r.idCliente = u.idUsuario
            WHERE r.idArtista = ?
            ORDER BY r.fecha_resena DESC
        ");
        $stmt->execute([$idArtista]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function guardarReview($idArtista, $idCliente, $calificacion, $comentario, $imagenResultado = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO reseñas_artistas (idArtista, idCliente, calificacion, comentario, imagen_resultado)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$idArtista, $idCliente, $calificacion, $comentario, $imagenResultado]);
    }
    

    public function obtenerLinkPaypal($idUsuario) {
        $stmt = $this->conn->prepare("SELECT link_paypal FROM paypal_links WHERE idUsuario = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }
    
    public function marcarMensajesComoLeidos($idConversacion, $idUsuario) {
        $sql = "UPDATE mensajes 
                SET leido = 1 
                WHERE idConversacion = ? 
                  AND idRemitente != ? 
                  AND leido = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idConversacion, $idUsuario]);
    }
    

    public function contarMensajesNoLeidos($idUsuario) {
        $sql = "
            SELECT COUNT(*) FROM mensajes m
            INNER JOIN conversaciones c ON m.idConversacion = c.idConversacion
            WHERE m.leido = 0 
            AND m.idRemitente != ? 
            AND (
                c.idUsuario1 = ? OR c.idUsuario2 = ?
            )
            AND m.estado = 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idUsuario, $idUsuario, $idUsuario]);
        return $stmt->fetchColumn();
    }

    public function actualizarRedesSociales($idUsuario, $redesSociales) {
        foreach ($redesSociales as $idTipoRed => $link) {
            if (!empty($link)) {
                $sqlCheck = "SELECT COUNT(*) FROM redes_sociales WHERE idUsuario = :idUsuario AND idTipoRed = :idTipoRed";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->execute([
                    ':idUsuario' => $idUsuario,
                    ':idTipoRed' => $idTipoRed
                ]);
                $existe = $stmtCheck->fetchColumn();
    
                if ($existe) {
                    $sqlUpdate = "UPDATE redes_sociales SET link_redSocial = :link WHERE idUsuario = :idUsuario AND idTipoRed = :idTipoRed";
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $stmtUpdate->execute([
                        ':link' => $link,
                        ':idUsuario' => $idUsuario,
                        ':idTipoRed' => $idTipoRed
                    ]);
                } else {
                    $sqlInsert = "INSERT INTO redes_sociales (idUsuario, idTipoRed, link_redSocial) VALUES (:idUsuario, :idTipoRed, :link)";
                    $stmtInsert = $this->conn->prepare($sqlInsert);
                    $stmtInsert->execute([
                        ':idUsuario' => $idUsuario,
                        ':idTipoRed' => $idTipoRed,
                        ':link' => $link
                    ]);
                }
            }
        }
    }


    public function obtenerTiposRedes() {
        $sql = "SELECT idTipoRed, nombre_red FROM tipo_redes ORDER BY nombre_red ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function obtenerRedesParaActualizar($idUsuario) {
        $sql = "SELECT 
                    rs.id_redSocial, 
                    rs.idTipoRed, 
                    rs.link_redSocial, 
                    tr.nombre_red 
                FROM redes_sociales rs
                JOIN tipo_redes tr ON rs.idTipoRed = tr.idTipoRed
                WHERE rs.idUsuario = :idUsuario";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function eliminarRedSocial($idUsuario, $idRedSocial) {
        $sql = "DELETE FROM redes_sociales 
                WHERE id_redSocial = :idRedSocial AND idUsuario = :idUsuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idRedSocial' => $idRedSocial,
            ':idUsuario' => $idUsuario
        ]);
    }


    public function agregarRedSocial($idUsuario, $idTipoRed, $link) {
        $sqlCheck = "SELECT COUNT(*) FROM redes_sociales WHERE idUsuario = :idUsuario AND idTipoRed = :idTipoRed";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([
            ':idUsuario' => $idUsuario,
            ':idTipoRed' => $idTipoRed
        ]);
    
        $existe = $stmtCheck->fetchColumn();
    
        if (!$existe) {
            $sqlInsert = "INSERT INTO redes_sociales (idUsuario, idTipoRed, link_redSocial)
                          VALUES (:idUsuario, :idTipoRed, :link)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->execute([
                ':idUsuario' => $idUsuario,
                ':idTipoRed' => $idTipoRed,
                ':link' => $link
            ]);
        }
    }
    

    public function obtenerDibujosRecientes($limite = 9, $offset = 0) {
        $sql = "SELECT 
                    gp.idDibujo,
                    gp.titulo,
                    gp.link_dibujo,
                    u.idUsuario,
                    u.usuario AS nombre_usuario,
                    e.nombre_estilo,
                    ti.nombre_ilustracion,
                    c.nombre_coloreado
                FROM galeria_personal gp
                JOIN usuarios u ON gp.idUsuario = u.idUsuario
                JOIN estilos e ON gp.idEstilo = e.idEstilo
                JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                JOIN coloreado c ON gp.idColoreado = c.idColoreado
                ORDER BY gp.fecha_subida DESC
                LIMIT :limite OFFSET :offset";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    
    public function obtenerDibujosAleatorios($limite = 9, $excluirIds = []) {
        $sql = "SELECT 
                    gp.idDibujo,
                    gp.titulo,
                    gp.link_dibujo,
                    u.idUsuario,
                    u.usuario AS nombre_usuario,
                    e.nombre_estilo,
                    ti.nombre_ilustracion,
                    c.nombre_coloreado
                FROM galeria_personal gp
                JOIN usuarios u ON gp.idUsuario = u.idUsuario
                JOIN estilos e ON gp.idEstilo = e.idEstilo
                JOIN tipo_ilustracion ti ON gp.idIlustracion = ti.idIlustracion
                JOIN coloreado c ON gp.idColoreado = c.idColoreado";
    
        if (!empty($excluirIds)) {
            $placeholders = implode(',', array_map(fn($i) => ":id$i", array_keys($excluirIds)));
            $sql .= " WHERE gp.idDibujo NOT IN ($placeholders)";
        }
    
        $sql .= " ORDER BY RAND() LIMIT :limite";
    
        $stmt = $this->conn->prepare($sql);
    
        if (!empty($excluirIds)) {
            foreach ($excluirIds as $index => $id) {
                $stmt->bindValue(":id$index", $id, PDO::PARAM_INT);
            }
        }
    
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

}
