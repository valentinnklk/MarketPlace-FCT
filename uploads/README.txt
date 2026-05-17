Esta carpeta almacena las imágenes subidas por usuarios cuando el sistema
funciona en modo "local" (XAMPP, desarrollo).

Cuando se configuren credenciales reales de AWS S3 en /config/s3Config.php,
las imágenes nuevas se subirán automáticamente a S3 en su lugar.

Estructura: /uploads/servicios/{servicio_id}/{timestamp}-{aleatorio}.{ext}
