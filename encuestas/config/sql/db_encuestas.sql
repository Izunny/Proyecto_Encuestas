-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-03-2025 a las 18:15:54
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
-- Base de datos: `db_encuestas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_encuestasm`
--

CREATE TABLE `enc_encuestasm` (
  `idencuesta` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `activo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_encuestasm`
--

INSERT INTO `enc_encuestasm` (`idencuesta`, `nombre`, `descripcion`, `idusuario`, `fecha`, `activo`) VALUES
(4, 'Partidos politicos', 'Preferencia de partidos politicos', 2, '2025-03-23', 'S'),
(5, 'Lenguajes de programación', 'Preferencias de Lenguajes de Programacion', 2, '2025-03-23', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_opcion`
--

CREATE TABLE `enc_opcion` (
  `idopciones` int(11) NOT NULL,
  `idpregunta` int(11) NOT NULL,
  `opcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_opcion`
--

INSERT INTO `enc_opcion` (`idopciones`, `idpregunta`, `opcion`) VALUES
(10, 8, 'MORENA'),
(11, 8, 'PRI'),
(12, 8, 'PAN'),
(13, 9, 'Si'),
(14, 9, 'No'),
(15, 11, 'Si'),
(16, 11, 'No'),
(17, 12, 'Si'),
(18, 12, 'No'),
(19, 15, 'Si'),
(20, 15, 'No');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_pregunta`
--

CREATE TABLE `enc_pregunta` (
  `idpregunta` int(11) NOT NULL,
  `idencuesta` int(11) NOT NULL,
  `textopregunta` varchar(255) NOT NULL,
  `requerida` varchar(1) NOT NULL,
  `idtipopregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_pregunta`
--

INSERT INTO `enc_pregunta` (`idpregunta`, `idencuesta`, `textopregunta`, `requerida`, `idtipopregunta`) VALUES
(8, 4, 'A que partido politico has votado?', '0', 4),
(9, 4, 'Tiene una buena aceptacion con el partido que gobierna tu pais actualmente?', '0', 3),
(10, 4, 'En que mejorarias el partido politico actual?', '0', 1),
(11, 4, 'Tienes una buena aceptacion con la actual presidente?', '0', 3),
(12, 4, 'Te gustaria entrar en un partido politico?', '0', 3),
(13, 5, 'Cual es tu lenguaje de programacion favorito?', '0', 1),
(14, 5, 'Que te ha gustado de PHP?', '0', 1),
(15, 5, 'Te gusta PHP?', '0', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_respuesta`
--

CREATE TABLE `enc_respuesta` (
  `idrespuestas` int(11) NOT NULL,
  `idencuesta` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_respuesta`
--

INSERT INTO `enc_respuesta` (`idrespuestas`, `idencuesta`, `idusuario`, `fecha`) VALUES
(11, 4, 2, '2025-03-23'),
(12, 4, 2, '2025-03-23'),
(13, 4, 2, '2025-03-23'),
(14, 4, 2, '2025-03-23'),
(15, 4, 2, '2025-03-23'),
(16, 5, 2, '2025-03-23'),
(17, 5, 2, '2025-03-23'),
(18, 5, 2, '2025-03-23'),
(19, 5, 2, '2025-03-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_respuestaopcion`
--

CREATE TABLE `enc_respuestaopcion` (
  `idrespuestaopcion` int(11) NOT NULL,
  `idopciones` int(11) NOT NULL,
  `idrespuestas` int(11) NOT NULL,
  `idpregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_respuestaopcion`
--

INSERT INTO `enc_respuestaopcion` (`idrespuestaopcion`, `idopciones`, `idrespuestas`, `idpregunta`) VALUES
(27, 10, 11, 8),
(28, 13, 11, 9),
(29, 15, 11, 11),
(30, 17, 11, 12),
(31, 11, 12, 8),
(32, 13, 12, 9),
(33, 15, 12, 11),
(34, 17, 12, 12),
(35, 12, 13, 8),
(36, 13, 13, 9),
(37, 15, 13, 11),
(38, 17, 13, 12),
(39, 10, 14, 8),
(40, 13, 14, 9),
(41, 15, 14, 11),
(42, 17, 14, 12),
(43, 10, 15, 8),
(44, 13, 15, 9),
(45, 15, 15, 11),
(46, 17, 15, 12),
(47, 19, 16, 15),
(48, 20, 17, 15),
(49, 20, 18, 15),
(50, 19, 19, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_respuestatexto`
--

CREATE TABLE `enc_respuestatexto` (
  `idrespuestatexo` int(11) NOT NULL,
  `respuesta` varchar(255) NOT NULL,
  `idrespuestas` int(11) NOT NULL,
  `idpregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_respuestatexto`
--

INSERT INTO `enc_respuestatexto` (`idrespuestatexo`, `respuesta`, `idrespuestas`, `idpregunta`) VALUES
(4, 'Cambiar los partidos politicos', 11, 10),
(5, 'a', 12, 10),
(6, 'b', 13, 10),
(7, 'c', 14, 10),
(8, 'd', 15, 10),
(9, 'Javascript', 16, 13),
(10, 'La facilidad', 16, 14),
(11, 'Java', 17, 13),
(12, 'Es algo dificil', 17, 14),
(13, 'Python', 18, 13),
(14, 'No me gusta', 18, 14),
(15, 'C++', 19, 13),
(16, 'Sus funciones para paginas web', 19, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_tipopreguntacatalogo`
--

CREATE TABLE `enc_tipopreguntacatalogo` (
  `idtipopregunta` int(11) NOT NULL,
  `tipopregunta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enc_tipopreguntacatalogo`
--

INSERT INTO `enc_tipopreguntacatalogo` (`idtipopregunta`, `tipopregunta`) VALUES
(1, 'texto'),
(2, 'texto_abierto'),
(3, 'opcion_unica'),
(4, 'opcion_multiple');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nombreU` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `username`, `nombreU`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `email`, `telefono`, `genero`, `password_hash`) VALUES
(1, 'dan', 'danny', 'Durazo', 'Arvizu', '1991-09-08', 'danny@gmail.com', '6684635851', 'Femenino', '$2y$10$wwMEWJbpAWo0bsulCjszmuj.KlqNEQujuYTqojXKqkFax8Nz0Zi5S'),
(2, 'luis', '', '', '', '0000-00-00', '', '', '', '$2y$10$T0vHXWvvRMAMfmS83kfHo.3cabJq9FpfwyZdsL525DHS.TOgDnXOO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `enc_encuestasm`
--
ALTER TABLE `enc_encuestasm`
  ADD PRIMARY KEY (`idencuesta`),
  ADD KEY `fk_encuesta_usuario` (`idusuario`);

--
-- Indices de la tabla `enc_opcion`
--
ALTER TABLE `enc_opcion`
  ADD PRIMARY KEY (`idopciones`),
  ADD KEY `fk_enc_pregunta` (`idpregunta`);

--
-- Indices de la tabla `enc_pregunta`
--
ALTER TABLE `enc_pregunta`
  ADD PRIMARY KEY (`idpregunta`),
  ADD KEY `fk_enc_encuestasm` (`idencuesta`),
  ADD KEY `fk_enc_tipopreguntacatalogo` (`idtipopregunta`);

--
-- Indices de la tabla `enc_respuesta`
--
ALTER TABLE `enc_respuesta`
  ADD PRIMARY KEY (`idrespuestas`),
  ADD KEY `fk_enc_respuesta_encuesta` (`idencuesta`),
  ADD KEY `fk_respuesta_usuario` (`idusuario`);

--
-- Indices de la tabla `enc_respuestaopcion`
--
ALTER TABLE `enc_respuestaopcion`
  ADD PRIMARY KEY (`idrespuestaopcion`),
  ADD KEY `fk_respopc_opcion` (`idopciones`),
  ADD KEY `fk_respopc_pregunta` (`idpregunta`),
  ADD KEY `fk_respopc_respuesta` (`idrespuestas`);

--
-- Indices de la tabla `enc_respuestatexto`
--
ALTER TABLE `enc_respuestatexto`
  ADD PRIMARY KEY (`idrespuestatexo`),
  ADD KEY `fk_enc_respuestatexo_respuesta` (`idrespuestas`),
  ADD KEY `fk_enc_respuestatexo_pregunta` (`idpregunta`);

--
-- Indices de la tabla `enc_tipopreguntacatalogo`
--
ALTER TABLE `enc_tipopreguntacatalogo`
  ADD PRIMARY KEY (`idtipopregunta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `enc_encuestasm`
--
ALTER TABLE `enc_encuestasm`
  MODIFY `idencuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `enc_opcion`
--
ALTER TABLE `enc_opcion`
  MODIFY `idopciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `enc_pregunta`
--
ALTER TABLE `enc_pregunta`
  MODIFY `idpregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `enc_respuesta`
--
ALTER TABLE `enc_respuesta`
  MODIFY `idrespuestas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `enc_respuestaopcion`
--
ALTER TABLE `enc_respuestaopcion`
  MODIFY `idrespuestaopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `enc_respuestatexto`
--
ALTER TABLE `enc_respuestatexto`
  MODIFY `idrespuestatexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `enc_tipopreguntacatalogo`
--
ALTER TABLE `enc_tipopreguntacatalogo`
  MODIFY `idtipopregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `enc_encuestasm`
--
ALTER TABLE `enc_encuestasm`
  ADD CONSTRAINT `fk_encuesta_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enc_opcion`
--
ALTER TABLE `enc_opcion`
  ADD CONSTRAINT `fk_enc_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `enc_pregunta` (`idpregunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enc_pregunta`
--
ALTER TABLE `enc_pregunta`
  ADD CONSTRAINT `fk_enc_encuestasm` FOREIGN KEY (`idencuesta`) REFERENCES `enc_encuestasm` (`idencuesta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enc_tipopreguntacatalogo` FOREIGN KEY (`idtipopregunta`) REFERENCES `enc_tipopreguntacatalogo` (`idtipopregunta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `enc_respuesta`
--
ALTER TABLE `enc_respuesta`
  ADD CONSTRAINT `fk_enc_respuesta_encuesta` FOREIGN KEY (`idencuesta`) REFERENCES `enc_encuestasm` (`idencuesta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respuesta_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enc_respuestaopcion`
--
ALTER TABLE `enc_respuestaopcion`
  ADD CONSTRAINT `fk_respopc_opcion` FOREIGN KEY (`idopciones`) REFERENCES `enc_opcion` (`idopciones`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respopc_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `enc_pregunta` (`idpregunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respopc_respuesta` FOREIGN KEY (`idrespuestas`) REFERENCES `enc_respuesta` (`idrespuestas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enc_respuestatexto`
--
ALTER TABLE `enc_respuestatexto`
  ADD CONSTRAINT `fk_enc_respuestatexo_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `enc_pregunta` (`idpregunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enc_respuestatexo_respuesta` FOREIGN KEY (`idrespuestas`) REFERENCES `enc_respuesta` (`idrespuestas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
