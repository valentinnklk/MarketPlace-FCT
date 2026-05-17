<?php
// modelo/imagenServicioModelo.php
//
// Gestiona la subida de imágenes a Amazon S3 (cuando hay credenciales)
// o al sistema de ficheros local /uploads/servicios/{id}/ (modo desarrollo).
// Inserta los registros correspondientes en la tabla servicio_imagenes.

require_once __DIR__ . '/../config/s3Config.php';

class ImagenServicioModelo {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // ─────────────────────────────────────────────────────────────
    // API pública: procesa los $_FILES de un servicio.
    // Devuelve un array de errores (vacío si todo fue bien).
    // ─────────────────────────────────────────────────────────────
    public function procesarImagenes(int $servicio_id, array $files): array {
        $errores = [];

        if (empty($files['imagenes']) || empty($files['imagenes']['name'][0])) {
            return $errores; // ninguna imagen subida — está permitido
        }

        $nombres = $files['imagenes']['name'];
        $tmps    = $files['imagenes']['tmp_name'];
        $sizes   = $files['imagenes']['size'];
        $errs    = $files['imagenes']['error'];

        $total = count($nombres);
        if ($total > MAX_IMAGENES_POR_SERVICIO) {
            return ['Solo se permiten ' . MAX_IMAGENES_POR_SERVICIO . ' imágenes por servicio.'];
        }

        $mimes_ok = explode(',', MIMES_PERMITIDOS);
        $finfo    = new finfo(FILEINFO_MIME_TYPE);

        for ($i = 0; $i < $total; $i++) {
            if ($errs[$i] !== UPLOAD_ERR_OK) {
                $errores[] = "Error al subir la imagen " . htmlspecialchars($nombres[$i]);
                continue;
            }
            if ($sizes[$i] > MAX_SIZE_IMAGEN_BYTES) {
                $errores[] = 'La imagen "' . htmlspecialchars($nombres[$i]) . '" supera 2 MB.';
                continue;
            }
            $mime = $finfo->file($tmps[$i]);
            if (!in_array($mime, $mimes_ok, true)) {
                $errores[] = 'Formato no admitido: ' . htmlspecialchars($nombres[$i]);
                continue;
            }

            $ext = self::extDeMime($mime);
            $key = sprintf('servicios/%d/%d-%s.%s',
                $servicio_id,
                time(),
                bin2hex(random_bytes(4)),
                $ext
            );

            $url = $this->subirArchivo($tmps[$i], $key, $mime);
            if (!$url) {
                $errores[] = 'No se pudo guardar la imagen "' . htmlspecialchars($nombres[$i]) . '".';
                continue;
            }

            $this->registrarEnBBDD($servicio_id, $key, $url, $i);
        }

        return $errores;
    }

    // ─────────────────────────────────────────────────────────────
    // Subida del archivo: decide S3 o local según configuración
    // ─────────────────────────────────────────────────────────────
    private function subirArchivo(string $tmpPath, string $key, string $mime): ?string {
        $modo = IMAGENES_STORAGE_MODE;

        if ($modo === 'auto') {
            // Si el SDK está instalado y las credenciales no son placeholders, usar S3
            $tieneSdk = class_exists('\Aws\S3\S3Client');
            $credOk   = AWS_S3_ACCESS_KEY_ID !== 'AKIA-SU-ACCESS-KEY-ID'
                     && AWS_S3_BUCKET        !== 'NOMBRE-DE-SU-BUCKET';
            $modo = ($tieneSdk && $credOk) ? 's3' : 'local';
        }

        if ($modo === 's3') {
            return $this->subirS3($tmpPath, $key, $mime);
        }
        return $this->subirLocal($tmpPath, $key);
    }

    // ─────────────────────────────────────────────────────────────
    // Implementación S3 (requiere AWS SDK instalado vía composer)
    // ─────────────────────────────────────────────────────────────
    private function subirS3(string $tmpPath, string $key, string $mime): ?string {
        try {
            $s3 = new \Aws\S3\S3Client([
                'version'     => 'latest',
                'region'      => AWS_S3_REGION,
                'credentials' => [
                    'key'    => AWS_S3_ACCESS_KEY_ID,
                    'secret' => AWS_S3_SECRET_ACCESS_KEY,
                ],
            ]);

            $resultado = $s3->putObject([
                'Bucket'      => AWS_S3_BUCKET,
                'Key'         => $key,
                'SourceFile'  => $tmpPath,
                'ContentType' => $mime,
                'ACL'         => 'public-read',
            ]);

            // Si hay un CDN configurado, lo usamos
            if (AWS_S3_PUBLIC_URL !== '') {
                return rtrim(AWS_S3_PUBLIC_URL, '/') . '/' . $key;
            }
            return (string) $resultado['ObjectURL'];

        } catch (\Throwable $e) {
            error_log('[ImagenServicio S3] ' . $e->getMessage());
            return null;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Implementación local (XAMPP)
    // ─────────────────────────────────────────────────────────────
    private function subirLocal(string $tmpPath, string $key): ?string {
        $destino = __DIR__ . '/../uploads/' . $key;
        $dir = dirname($destino);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                error_log('[ImagenServicio local] No se pudo crear ' . $dir);
                return null;
            }
        }
        if (!move_uploaded_file($tmpPath, $destino)) {
            error_log('[ImagenServicio local] No se pudo mover el archivo');
            return null;
        }
        // URL pública relativa al proyecto
        return '../uploads/' . $key;
    }

    // ─────────────────────────────────────────────────────────────
    // Registrar la imagen en la BBDD
    // ─────────────────────────────────────────────────────────────
    private function registrarEnBBDD(int $servicio_id, string $key, string $url, int $orden): void {
        $stmt = $this->conexion->prepare(
            "INSERT INTO servicio_imagenes
                (servicio_id, s3_key, url_publica, orden, fecha_subida)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([$servicio_id, $key, $url, $orden]);
    }

    // ─────────────────────────────────────────────────────────────
    // API pública: obtener imágenes de un servicio
    // ─────────────────────────────────────────────────────────────
    public function getImagenesPorServicio(int $servicio_id): array {
        $stmt = $this->conexion->prepare(
            "SELECT id, s3_key, url_publica, orden
             FROM servicio_imagenes
             WHERE servicio_id = ?
             ORDER BY orden ASC, id ASC"
        );
        $stmt->execute([$servicio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ─────────────────────────────────────────────────────────────
    private static function extDeMime(string $mime): string {
        switch ($mime) {
            case 'image/png':  return 'png';
            case 'image/webp': return 'webp';
            case 'image/jpeg':
            default:           return 'jpg';
        }
    }
}
