-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-03-2026 a las 12:00:00
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `marketplace`
--
DROP DATABASE IF EXISTS `marketplace`;
CREATE DATABASE IF NOT EXISTS `marketplace` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `marketplace`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `contraseña_hash` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `ubicacion` varchar(150) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `avatar_url` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `es_administrador` BOOLEAN NOT NULL DEFAULT FALSE,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña_hash`, `ubicacion`, `avatar_url`, `es_administrador`, `fecha_registro`) VALUES
(1, 'Juan Pérez', 'juan@email.com', '$2y$10$EjemploHash1', 'Madrid', 'avatar1.jpg', FALSE, '2026-01-15 10:30:00'),
(2, 'María García', 'maria@email.com', '$2y$10$EjemploHash2', 'Barcelona', NULL, FALSE, '2026-01-20 14:45:00'),
(3, 'Admin Sistema', 'admin@marketplace.com', '$2y$10$EjemploHash3', 'Madrid', 'admin.jpg', TRUE, '2026-01-10 09:00:00'),
(4, 'Laura Martínez', 'laura@email.com', '$2y$10$EjemploHash4', 'Valencia', NULL, FALSE, '2026-02-05 11:20:00'),
(5, 'Carlos López', 'carlos@email.com', '$2y$10$EjemploHash5', 'Sevilla', 'carlos.jpg', FALSE, '2026-02-10 16:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `slug` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activa` BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `slug`, `icono`, `activa`) VALUES
(1, 'Electrónica', 'Productos electrónicos, móviles, ordenadores', 'electronica', '📱', TRUE),
(2, 'Ropa', 'Moda y complementos', 'ropa', '👕', TRUE),
(3, 'Hogar', 'Artículos para el hogar y decoración', 'hogar', '🏠', TRUE),
(4, 'Deportes', 'Equipamiento y ropa deportiva', 'deportes', '⚽', TRUE),
(5, 'Libros', 'Libros nuevos y de segunda mano', 'libros', '📚', TRUE);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `titulo` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado_producto` enum('nuevo','como_nuevo','bueno','aceptable') COLLATE utf8_spanish_ci NOT NULL,
  `ubicacion` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `visitas` int(11) NOT NULL DEFAULT 0,
  `fecha_publicacion` datetime NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `vendedor_id`, `titulo`, `descripcion`, `precio`, `estado_producto`, `ubicacion`, `visitas`, `fecha_publicacion`, `fecha_actualizacion`, `activo`) VALUES
(1, 1, 'iPhone 12', 'iPhone 12 como nuevo, 128GB, color negro', 450.00, 'como_nuevo', 'Madrid', 120, '2026-02-01 10:00:00', NULL, TRUE),
(2, 2, 'Vestido rojo', 'Vestido rojo talla M, solo usado una vez', 25.00, 'bueno', 'Barcelona', 45, '2026-02-05 15:30:00', NULL, TRUE),
(3, 1, 'Mesa de comedor', 'Mesa de madera, 4 sillas incluidas', 150.00, 'bueno', 'Madrid', 78, '2026-02-10 09:15:00', NULL, TRUE),
(4, 4, 'Balón de fútbol', 'Balón oficial de la liga, nuevo', 30.00, 'nuevo', 'Valencia', 23, '2026-02-15 11:45:00', NULL, TRUE),
(5, 5, 'El Quijote', 'Libro de Cervantes, edición especial', 15.00, 'como_nuevo', 'Sevilla', 12, '2026-02-20 17:20:00', NULL, TRUE);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_categorias`
--

DROP TABLE IF EXISTS `producto_categorias`;
CREATE TABLE `producto_categorias` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `es_principal` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto_categorias`
--

INSERT INTO `producto_categorias` (`id`, `producto_id`, `categoria_id`, `es_principal`) VALUES
(1, 1, 1, TRUE),
(2, 2, 2, TRUE),
(3, 3, 3, TRUE),
(4, 4, 4, TRUE),
(5, 5, 5, TRUE);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagenes`
--

DROP TABLE IF EXISTS `producto_imagenes`;
CREATE TABLE `producto_imagenes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `url_imagen` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL,
  `fecha_subida` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto_imagenes`
--

INSERT INTO `producto_imagenes` (`id`, `producto_id`, `url_imagen`, `orden`, `fecha_subida`) VALUES
(1, 1, 'iphone12_1.jpg', 1, '2026-02-01 10:05:00'),
(2, 1, 'iphone12_2.jpg', 2, '2026-02-01 10:05:00'),
(3, 2, 'vestido_rojo_1.jpg', 1, '2026-02-05 15:35:00'),
(4, 3, 'mesa_1.jpg', 1, '2026-02-10 09:20:00'),
(5, 4, 'balon_1.jpg', 1, '2026-02-15 11:50:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha_agregado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `producto_id`, `fecha_agregado`) VALUES
(1, 2, 1, '2026-02-10 16:00:00'),
(2, 3, 2, '2026-02-12 09:30:00'),
(3, 4, 3, '2026-02-15 12:15:00'),
(4, 5, 1, '2026-02-18 18:45:00'),
(5, 1, 4, '2026-02-20 08:20:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `comprador_id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `ultimo_mensaje` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_ultimo_mensaje` datetime DEFAULT NULL,
  `total_mensajes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `chats`
--

INSERT INTO `chats` (`id`, `comprador_id`, `vendedor_id`, `producto_id`, `fecha_inicio`, `ultimo_mensaje`, `fecha_ultimo_mensaje`, `total_mensajes`) VALUES
(1, 2, 1, 1, '2026-02-10 16:30:00', '¿Todavía lo tienes?', '2026-02-10 16:35:00', 3),
(2, 3, 2, 2, '2026-02-12 10:00:00', 'Gracias por la info', '2026-02-12 10:15:00', 4),
(3, 4, 1, 3, '2026-02-15 13:00:00', 'Me interesa', '2026-02-15 13:10:00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `contenido` text COLLATE utf8_spanish_ci NOT NULL,
  `leido` BOOLEAN NOT NULL DEFAULT FALSE,
  `fecha_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `chat_id`, `remitente_id`, `contenido`, `leido`, `fecha_envio`) VALUES
(1, 1, 2, 'Hola, ¿todavía tienes el iPhone?', TRUE, '2026-02-10 16:30:00'),
(2, 1, 1, 'Sí, todavía está disponible', TRUE, '2026-02-10 16:32:00'),
(3, 1, 2, '¿Me haces precio?', TRUE, '2026-02-10 16:35:00'),
(4, 2, 3, 'Hola, ¿el vestido es talla M?', TRUE, '2026-02-12 10:00:00'),
(5, 2, 2, 'Sí, talla M, te queda bien', TRUE, '2026-02-12 10:05:00'),
(6, 2, 3, '¿Tiene algún defecto?', TRUE, '2026-02-12 10:10:00'),
(7, 2, 2, 'Ninguno, como nuevo', TRUE, '2026-02-12 10:15:00'),
(8, 3, 4, 'Hola, me interesa la mesa', FALSE, '2026-02-15 13:00:00'),
(9, 3, 1, 'Hola, sí, está disponible', FALSE, '2026-02-15 13:10:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `comprador_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_final` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aceptado','enviado','completado','cancelado') COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `comprador_id`, `producto_id`, `precio_final`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 2, 1, 450.00, 'completado', '2026-02-15 11:00:00', '2026-02-20 09:00:00'),
(2, 3, 2, 25.00, 'enviado', '2026-02-18 14:30:00', '2026-02-20 10:15:00'),
(3, 4, 3, 150.00, 'pendiente', '2026-02-20 16:45:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

DROP TABLE IF EXISTS `reseñas`;
CREATE TABLE `reseñas` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `revisor_id` int(11) NOT NULL,
  `revisado_id` int(11) NOT NULL,
  `puntuacion` tinyint(4) NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reseñas`
--

INSERT INTO `reseñas` (`id`, `pedido_id`, `revisor_id`, `revisado_id`, `puntuacion`, `comentario`, `fecha`) VALUES
(1, 1, 2, 1, 5, 'Todo perfecto, muy recomendable', '2026-02-21 10:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

DROP TABLE IF EXISTS `reportes`;
CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `reportador_id` int(11) NOT NULL,
  `tipo` enum('producto','usuario') COLLATE utf8_spanish_ci NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `usuario_reportado_id` int(11) DEFAULT NULL,
  `motivo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('pendiente','revisado','resuelto','rechazado') COLLATE utf8_spanish_ci DEFAULT 'pendiente',
  `fecha_creacion` datetime NOT NULL,
  `fecha_resolucion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `reportador_id`, `tipo`, `producto_id`, `usuario_reportado_id`, `motivo`, `descripcion`, `estado`, `fecha_creacion`, `fecha_resolucion`) VALUES
(1, 3, 'usuario', NULL, 5, 'Comportamiento inapropiado', 'El usuario envía mensajes ofensivos', 'pendiente', '2026-02-22 09:30:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acciones_admin`
--

DROP TABLE IF EXISTS `acciones_admin`;
CREATE TABLE `acciones_admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `tipo_accion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_objetivo` enum('usuario','producto','reporte') COLLATE utf8_spanish_ci NOT NULL,
  `objetivo_id` int(11) NOT NULL,
  `reporte_id` int(11) DEFAULT NULL,
  `razon` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `acciones_admin`
--

INSERT INTO `acciones_admin` (`id`, `admin_id`, `tipo_accion`, `tipo_objetivo`, `objetivo_id`, `reporte_id`, `razon`, `fecha`) VALUES
(1, 3, 'advertir_usuario', 'usuario', 5, 1, 'Se envió advertencia por mensajes inapropiados', '2026-02-22 10:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interacciones`
--

DROP TABLE IF EXISTS `interacciones`;
CREATE TABLE `interacciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('visto','buscado','favorito','comprado') COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `interacciones`
--

INSERT INTO `interacciones` (`id`, `usuario_id`, `producto_id`, `tipo`, `fecha`) VALUES
(1, 2, 1, 'visto', '2026-02-10 16:25:00'),
(2, 2, 1, 'favorito', '2026-02-10 16:26:00'),
(3, 3, 2, 'visto', '2026-02-12 09:55:00'),
(4, 3, 2, 'comprado', '2026-02-18 14:30:00'),
(5, 4, 3, 'visto', '2026-02-15 12:50:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Indices de la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producto_id` (`producto_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`producto_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comprador_id` (`comprador_id`),
  ADD KEY `vendedor_id` (`vendedor_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `remitente_id` (`remitente_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comprador_id` (`comprador_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pedido_id` (`pedido_id`),
  ADD KEY `revisor_id` (`revisor_id`),
  ADD KEY `revisado_id` (`revisado_id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reportador_id` (`reportador_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_reportado_id` (`usuario_reportado_id`);

--
-- Indices de la tabla `acciones_admin`
--
ALTER TABLE `acciones_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `reporte_id` (`reporte_id`);

--
-- Indices de la tabla `interacciones`
--
ALTER TABLE `interacciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `acciones_admin`
--
ALTER TABLE `acciones_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `interacciones`
--
ALTER TABLE `interacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  ADD CONSTRAINT `producto_categorias_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_categorias_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD CONSTRAINT `producto_imagenes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_3` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`revisor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reseñas_ibfk_3` FOREIGN KEY (`revisado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`reportador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reportes_ibfk_3` FOREIGN KEY (`usuario_reportado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `acciones_admin`
--
ALTER TABLE `acciones_admin`
  ADD CONSTRAINT `acciones_admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `acciones_admin_ibfk_2` FOREIGN KEY (`reporte_id`) REFERENCES `reportes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `interacciones`
--
ALTER TABLE `interacciones`
  ADD CONSTRAINT `interacciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interacciones_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/*   Cambios: He cambiado la ubicacion a que sea no nula y que el atributo de es_administrador sea booleano */

-- Hacer que el campo nombre sea único
ALTER TABLE `usuarios` ADD UNIQUE KEY `nombre_unico` (`nombre`);

-- Para evitar errores si ya hay duplicados (borra los duplicados más antiguos)
ALTER IGNORE TABLE `usuarios` ADD UNIQUE KEY `nombre_unico` (`nombre`);


