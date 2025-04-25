<?php
require_once __DIR__ . '/../models/Usuario.php';

class MensajesController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function obtenerConversaciones($idUsuario) {
        return $this->usuarioModel->listarConversaciones($idUsuario);
    }

    public function obtenerMensajes($idConversacion) {
        return $this->usuarioModel->listarMensajes($idConversacion);
    }

    public function enviarMensaje($idConversacion, $idRemitente, $contenido) {
        return $this->usuarioModel->guardarMensaje($idConversacion, $idRemitente, $contenido);
    }

    public function obtenerOCrearConversacion($idUsuario1, $idUsuario2) {
        $idConversacion = $this->usuarioModel->buscarConversacion($idUsuario1, $idUsuario2);
        if (!$idConversacion) {
            $idConversacion = $this->usuarioModel->crearConversacion($idUsuario1, $idUsuario2);
        }
        return $idConversacion;
    }
}
