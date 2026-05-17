<?php
// config/s3Config.php
//
// ═══════════════════════════════════════════════════════════════
//   CONFIGURACIÓN DE AMAZON S3 PARA SUBIDA DE IMÁGENES
// ═══════════════════════════════════════════════════════════════
//
// 🔧 PASO 1 — Instalar el SDK oficial de AWS para PHP:
//
//     composer require aws/aws-sdk-php
//
// 🔧 PASO 2 — Rellenar las constantes de abajo con sus credenciales
//   reales obtenidas desde el panel de AWS IAM.
//   IMPORTANTE: no subir este archivo con credenciales reales al
//   repositorio público. Añadirlo a .gitignore.
//
// 🔧 PASO 3 — Asegurarse de que el bucket S3 está creado y tiene
//   política CORS y permisos de lectura pública sobre /servicios/*
//   (o servir las imágenes a través de CloudFront).
//
// ═══════════════════════════════════════════════════════════════

// === CREDENCIALES — RELLENE AQUÍ ===
define('AWS_S3_REGION',            'eu-west-1');         // p. ej. eu-west-1 (Irlanda) o eu-south-2 (España)
define('AWS_S3_BUCKET',            'NOMBRE-DE-SU-BUCKET'); // nombre del bucket creado en S3
define('AWS_S3_ACCESS_KEY_ID',     'AKIA-SU-ACCESS-KEY-ID');
define('AWS_S3_SECRET_ACCESS_KEY', 'SU-SECRET-KEY-COMPLETA');

// === Opcional: si usa CloudFront como CDN delante de S3 ===
define('AWS_S3_PUBLIC_URL',        ''); // ej: 'https://d1234abcd.cloudfront.net'
                                         // déjelo vacío para usar la URL directa de S3

// === Modo de operación ===
// 'local' → guarda las imágenes en /uploads/servicios/{servicio_id}/ (sirve para desarrollo en XAMPP).
// 's3'    → sube las imágenes a Amazon S3 usando las credenciales de arriba.
// 'auto'  → intenta usar S3 si el SDK está instalado y las credenciales no son placeholders; si no, cae a 'local'.
define('IMAGENES_STORAGE_MODE', 'auto');

// === Configuración común ===
define('MAX_IMAGENES_POR_SERVICIO', 3);
define('MAX_SIZE_IMAGEN_BYTES',     2 * 1024 * 1024);  // 2 MB
define('MIMES_PERMITIDOS',          'image/jpeg,image/png,image/webp');
