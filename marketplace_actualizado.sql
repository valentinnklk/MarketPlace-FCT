-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-04-2026 a las 20:53:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `marketplace`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_admin`
--

CREATE TABLE `auditoria_admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `tipo_accion` varchar(50) NOT NULL,
  `tipo_objetivo` enum('usuario','servicio','reporte','solicitud') NOT NULL,
  `objetivo_id` int(11) NOT NULL,
  `reporte_id` int(11) DEFAULT NULL,
  `razon` text NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `auditoria_admin`
--

INSERT INTO `auditoria_admin` (`id`, `admin_id`, `tipo_accion`, `tipo_objetivo`, `objetivo_id`, `reporte_id`, `razon`, `fecha`) VALUES
(1, 3, 'aprobar_solicitud', 'solicitud', 1, NULL, 'Categoría de jardinería aprobada por alta demanda', '2026-02-22 12:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_servicio`
--

CREATE TABLE `categorias_servicio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categorias_servicio`
--

INSERT INTO `categorias_servicio` (`id`, `nombre`, `descripcion`, `slug`, `fecha_creacion`) VALUES
(1, 'Clases Particulares', 'Clases de matemáticas, idiomas, música, etc.', 'clases-particulares', '2026-01-01 10:00:00'),
(2, 'Fontanería', 'Reparación de tuberías, grifos, calderas, etc.', 'fontaneria', '2026-01-01 10:00:00'),
(3, 'Mudanzas', 'Ayuda para mudanzas y transporte de muebles', 'mudanzas', '2026-01-01 10:00:00'),
(4, 'Limpieza', 'Limpieza de hogares y oficinas', 'limpieza', '2026-01-01 10:00:00'),
(5, 'Electricidad', 'Reparaciones eléctricas e instalaciones', 'electricidad', '2026-02-01 10:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `precio_acordado` decimal(10,2) NOT NULL,
  `fecha_contrato` datetime NOT NULL,
  `fecha_servicio` datetime DEFAULT NULL,
  `estado` enum('pendiente','aceptado','en_proceso','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_actualizacion` datetime DEFAULT NULL,
  `confirmacion_cliente` tinyint(1) NOT NULL DEFAULT 0,
  `confirmacion_prestador` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id`, `cliente_id`, `servicio_id`, `precio_acordado`, `fecha_contrato`, `fecha_servicio`, `estado`, `fecha_actualizacion`, `confirmacion_cliente`, `confirmacion_prestador`) VALUES
(1, 4, 1, 20.00, '2026-02-18 09:00:00', '2026-02-20 16:00:00', 'completado', '2026-02-20 18:00:00', 1, 1),
(2, 5, 2, 50.00, '2026-02-19 11:30:00', '2026-02-21 10:00:00', 'aceptado', '2026-02-19 14:00:00', 0, 0),
(3, 1, 3, 90.00, '2026-02-20 15:00:00', '2026-02-22 09:00:00', 'pendiente', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversaciones`
--

CREATE TABLE `conversaciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `ultimo_mensaje` text DEFAULT NULL,
  `fecha_ultimo_mensaje` datetime DEFAULT NULL,
  `total_mensajes` int(11) NOT NULL DEFAULT 0,
  `no_leidos_cliente` int(11) NOT NULL DEFAULT 0,
  `no_leidos_prestador` int(11) NOT NULL DEFAULT 0,
  `contrato_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `conversaciones`
--

INSERT INTO `conversaciones` (`id`, `cliente_id`, `prestador_id`, `servicio_id`, `fecha_inicio`, `ultimo_mensaje`, `fecha_ultimo_mensaje`, `total_mensajes`, `no_leidos_cliente`, `no_leidos_prestador`, `contrato_id`) VALUES
(1, 4, 1, 1, '2026-02-17 10:00:00', '¿Podemos cambiar la hora?', '2026-02-17 10:35:00', 3, 0, 1, 1),
(2, 5, 2, 2, '2026-02-18 12:00:00', 'Gracias por su rapidez', '2026-02-18 12:20:00', 4, 0, 0, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fecha_agregado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `servicio_id`, `fecha_agregado`) VALUES
(1, 2, 1, '2026-02-15 12:00:00'),
(2, 4, 2, '2026-02-16 09:30:00'),
(3, 5, 3, '2026-02-17 18:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `conversacion_id` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `conversacion_id`, `remitente_id`, `contenido`, `leido`, `fecha_envio`) VALUES
(1, 1, 4, 'Hola, ¿estás disponible para una clase?', 1, '2026-02-17 10:00:00'),
(2, 1, 1, 'Sí, ¿qué día te viene bien?', 1, '2026-02-17 10:15:00'),
(3, 1, 4, '¿Podemos cambiar la hora?', 0, '2026-02-17 10:35:00'),
(4, 2, 5, 'Necesito un fontanero urgente', 1, '2026-02-18 12:00:00'),
(5, 2, 2, 'Ahora mismo voy para allá', 1, '2026-02-18 12:10:00'),
(6, 2, 5, 'Gracias por su rapidez', 1, '2026-02-18 12:20:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones_sistema`
--

CREATE TABLE `notificaciones_sistema` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('nuevo_servicio','recordatorio') NOT NULL,
  `fecha_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `notificaciones_sistema`
--

INSERT INTO `notificaciones_sistema` (`id`, `titulo`, `mensaje`, `tipo`, `fecha_envio`) VALUES
(1, 'Nueva categoría disponible', 'Ya puedes ofrecer servicios de Jardinería en nuestra plataforma', 'nuevo_servicio', '2026-02-22 12:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones_usuario`
--

CREATE TABLE `notificaciones_usuario` (
  `id` int(11) NOT NULL,
  `usuario_destino_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('solicitud_aprobada','solicitud_rechazada','contrato_actualizado','nueva_reserva','reserva_aceptada','reserva_rechazada','servicio_finalizado') NOT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_envio` datetime NOT NULL,
  `fecha_lectura` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `notificaciones_usuario`
--

INSERT INTO `notificaciones_usuario` (`id`, `usuario_destino_id`, `titulo`, `mensaje`, `tipo`, `leida`, `fecha_envio`, `fecha_lectura`) VALUES
(1, 2, 'Solicitud aprobada', 'Tu solicitud para la categoría \"Jardinería\" ha sido aprobada', 'solicitud_aprobada', 1, '2026-02-22 12:00:00', '2026-02-22 13:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `reportador_id` int(11) NOT NULL,
  `tipo` enum('servicio','usuario') NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `usuario_reportado_id` int(11) DEFAULT NULL,
  `motivo` varchar(100) NOT NULL,
  `estado` enum('pendiente','revisado','resuelto','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_creacion` datetime NOT NULL,
  `fecha_resolucion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `reportador_id`, `tipo`, `servicio_id`, `usuario_reportado_id`, `motivo`, `estado`, `fecha_creacion`, `fecha_resolucion`) VALUES
(1, 4, 'servicio', 2, NULL, 'Precio abusivo', 'pendiente', '2026-02-23 09:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `unidad_cobro` enum('hora','dia','trabajo','sesion','proyecto') NOT NULL DEFAULT 'trabajo',
  `duracion_estimada` varchar(50) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `valoracion_media` decimal(2,1) DEFAULT 0.0,
  `fecha_publicacion` datetime NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `prestador_id`, `categoria_id`, `titulo`, `descripcion`, `precio`, `unidad_cobro`, `duracion_estimada`, `ubicacion`, `latitud`, `longitud`, `tags`, `valoracion_media`, `fecha_publicacion`, `fecha_actualizacion`, `activo`) VALUES
(1, 1, 1, 'Clases de Matemáticas', 'Clases particulares de matemáticas para todos los niveles', 20.00, 'hora', '1 hora', 'Madrid', 40.4167754, -3.7037902, 'matemáticas, clases, particular, apoyo escolar', 4.5, '2026-02-01 10:00:00', NULL, 1),
(2, 2, 2, 'Fontanero urgente', 'Reparación de averías de fontanería 24h', 50.00, 'trabajo', '2 horas', 'Barcelona', 41.3850639, 2.1734035, 'fontanero, tuberías, averías, urgencias', 4.8, '2026-02-05 11:30:00', NULL, 1),
(3, 4, 3, 'Ayuda para mudanza', 'Te ayudo a cargar y descargar muebles en tu mudanza', 30.00, 'hora', '4 horas', 'Valencia', 39.4699075, -0.3511192, 'mudanza, transporte, carga, descarga', 5.0, '2026-02-10 09:15:00', NULL, 1),
(4, 5, 4, 'Limpieza de hogar', 'Limpieza profunda de pisos y casas', 15.00, 'hora', '3 horas', 'Sevilla', 37.3890924, -5.9844589, 'limpieza, hogar, oficinas, profunda', 4.2, '2026-02-15 14:00:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_imagenes`
--

CREATE TABLE `servicio_imagenes` (
  `id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `s3_key` varchar(255) NOT NULL,
  `url_publica` varchar(500) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `fecha_subida` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicio_imagenes`
--

INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `s3_key`, `url_publica`, `orden`, `fecha_subida`) VALUES
(1, 1, 'servicios/matematicas_1.jpg', 'https://s3.amazonaws.com/marketplace/servicios/matematicas_1.jpg', 1, '2026-02-01 10:05:00'),
(2, 1, 'servicios/matematicas_2.jpg', 'https://s3.amazonaws.com/marketplace/servicios/matematicas_2.jpg', 2, '2026-02-01 10:05:00'),
(3, 2, 'servicios/fontanero_1.jpg', 'https://s3.amazonaws.com/marketplace/servicios/fontanero_1.jpg', 1, '2026-02-05 11:35:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_categoria`
--

CREATE TABLE `solicitudes_categoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_solicitado` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `justificacion` text DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` datetime NOT NULL,
  `fecha_resolucion` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `comentario_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_categoria`
--

INSERT INTO `solicitudes_categoria` (`id`, `usuario_id`, `nombre_solicitado`, `descripcion`, `justificacion`, `estado`, `fecha_solicitud`, `fecha_resolucion`, `admin_id`, `comentario_admin`) VALUES
(1, 2, 'Jardinería', 'Cuidado de jardines, poda de árboles, siega de césped', 'Hay mucha demanda de jardineros en mi zona', 'aprobado', '2026-02-20 10:00:00', '2026-02-22 12:00:00', 3, 'Categoría aprobada por alta demanda'),
(2, 4, 'Peluquería a domicilio', 'Corte de pelo, peinados y arreglos en tu casa', 'Las personas mayores necesitan este servicio', 'pendiente', '2026-02-25 15:30:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña_hash` varchar(255) NOT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `es_administrador` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_registro` datetime NOT NULL,
  `tiempo_respuesta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña_hash`, `ubicacion`, `avatar_url`, `es_administrador`, `fecha_registro`, `tiempo_respuesta`) VALUES
(1, 'Ana Martínez', 'ana@email.com', '$2y$10$ejemploHash1', 'Madrid', 'ana.jpg', 0, '2026-01-15 10:00:00', 2),
(2, 'Carlos Gómez', 'carlos@email.com', '$2y$10$ejemploHash2', 'Barcelona', 'carlos.jpg', 0, '2026-01-20 11:30:00', 5),
(3, 'Admin Sistema', 'admin@marketplace.com', '$2y$10$ejemploHash3', 'Madrid', 'admin.jpg', 1, '2026-01-10 09:00:00', NULL),
(4, 'Laura Fernández', 'laura@email.com', '$2y$10$ejemploHash4', 'Valencia', 'laura.jpg', 0, '2026-02-01 14:15:00', 1),
(5, 'Pedro Sánchez', 'pedro@email.com', '$2y$10$ejemploHash5', 'Sevilla', 'pedro.jpg', 0, '2026-02-10 16:45:00', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `id` int(11) NOT NULL,
  `contrato_id` int(11) NOT NULL,
  `revisor_id` int(11) NOT NULL,
  `revisado_id` int(11) NOT NULL,
  `puntuacion` tinyint(4) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `valoraciones`
--

INSERT INTO `valoraciones` (`id`, `contrato_id`, `revisor_id`, `revisado_id`, `puntuacion`, `comentario`, `fecha`) VALUES
(1, 1, 4, 1, 5, 'Muy buena profesora, explica muy bien', '2026-02-21 10:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_admin`
--
ALTER TABLE `auditoria_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `reporte_id` (`reporte_id`);

--
-- Indices de la tabla `categorias_servicio`
--
ALTER TABLE `categorias_servicio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `prestador_id` (`prestador_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_servicio` (`usuario_id`,`servicio_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversacion_id` (`conversacion_id`),
  ADD KEY `remitente_id` (`remitente_id`);

--
-- Indices de la tabla `notificaciones_sistema`
--
ALTER TABLE `notificaciones_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificaciones_usuario`
--
ALTER TABLE `notificaciones_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_destino_id` (`usuario_destino_id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reportador_id` (`reportador_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `usuario_reportado_id` (`usuario_reportado_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestador_id` (`prestador_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `servicio_imagenes`
--
ALTER TABLE `servicio_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `solicitudes_categoria`
--
ALTER TABLE `solicitudes_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nombre_unico` (`nombre`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contrato_id` (`contrato_id`),
  ADD KEY `revisor_id` (`revisor_id`),
  ADD KEY `revisado_id` (`revisado_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_admin`
--
ALTER TABLE `auditoria_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias_servicio`
--
ALTER TABLE `categorias_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `notificaciones_sistema`
--
ALTER TABLE `notificaciones_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `notificaciones_usuario`
--
ALTER TABLE `notificaciones_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicio_imagenes`
--
ALTER TABLE `servicio_imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes_categoria`
--
ALTER TABLE `solicitudes_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria_admin`
--
ALTER TABLE `auditoria_admin`
  ADD CONSTRAINT `auditoria_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auditoria_reporte_fk` FOREIGN KEY (`reporte_id`) REFERENCES `reportes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_cliente_fk` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contratos_servicio_fk` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD CONSTRAINT `conversaciones_cliente_fk` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversaciones_prestador_fk` FOREIGN KEY (`prestador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversaciones_servicio_fk` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_servicio_fk` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_conversacion_fk` FOREIGN KEY (`conversacion_id`) REFERENCES `conversaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensajes_remitente_fk` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones_usuario`
--
ALTER TABLE `notificaciones_usuario`
  ADD CONSTRAINT `notificaciones_usuario_fk` FOREIGN KEY (`usuario_destino_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_reportador_fk` FOREIGN KEY (`reportador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reportes_servicio_fk` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reportes_usuario_fk` FOREIGN KEY (`usuario_reportado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_categoria_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_servicio` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_prestador_fk` FOREIGN KEY (`prestador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `servicio_imagenes`
--
ALTER TABLE `servicio_imagenes`
  ADD CONSTRAINT `imagenes_servicio_fk` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes_categoria`
--
ALTER TABLE `solicitudes_categoria`
  ADD CONSTRAINT `solicitudes_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `solicitudes_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_contrato_fk` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `valoraciones_revisado_fk` FOREIGN KEY (`revisado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `valoraciones_revisor_fk` FOREIGN KEY (`revisor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros adicionales: conversaciones.contrato_id
--
ALTER TABLE `conversaciones`
  ADD CONSTRAINT `conversaciones_contrato_fk` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
