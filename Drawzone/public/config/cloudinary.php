<?php
require __DIR__ . '/../../vendor/autoload.php';


use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Exception\ApiError;

// Configuración de Cloudinary  *NO TOCAR*
Configuration::instance([
    'cloud_name' => "djxmig9hr",
    'api_key'    => "188986481348561",
    'api_secret' => "XZPwfcApFHnLTcG9TyxoB2lYgoU",
    'secure'     => true
]);

/**
 * Subir una imagen a Cloudinary
 * @param string $filePath Ruta temporal del archivo en el servidor ($_FILES['tmp_name'])
 * @param string $folder Carpeta en Cloudinary donde se almacenará
 * @return array|false Retorna información de la imagen subida o false si falla
 */
function subirImagenACloudinary($filePath, $folder = "drawzone_perfiles") {
    try {
        // 🔍 Verificar si el archivo existe
        if (!file_exists($filePath)) {
            throw new Exception("El archivo no existe en la ruta temporal.");
        }

        // 📤 Intentar subir la imagen
        $resultado = (new UploadApi())->upload($filePath, [
            "folder" => $folder,
            "resource_type" => "image",
            "quality" => "auto",
            "format" => "jpg",
            "transformation" => [
                ["width" => 500, "height" => 500, "crop" => "fill"]
            ]
        ]);

        return $resultado;
    } catch (ApiError $e) {
        error_log("❌ Error en Cloudinary: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("❌ Error general: " . $e->getMessage());
        return false;
    }
}
