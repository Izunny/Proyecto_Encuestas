-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2025 a las 00:52:16
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_opcion`
--

CREATE TABLE `enc_opcion` (
  `idopciones` int(11) NOT NULL,
  `idpregunta` int(11) NOT NULL,
  `opcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_pregunta`
--

CREATE TABLE `enc_pregunta` (
  `idpregunta` int(11) NOT NULL,
  `idencuesta` int(11) NOT NULL,
  `textopregunta` varchar(255) NOT NULL,
  `requerida` varchar(1) NOT NULL,
  `idtipopregunta` int(11) NOT NULL,
  `urlfotografia` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enc_respuestatexo`
--

CREATE TABLE `enc_respuestatexo` (
  `idrespuestatexo` int(11) NOT NULL,
  `respuesta` varchar(255) NOT NULL,
  `idrespuestas` int(11) NOT NULL,
  `idpregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indices de la tabla `enc_respuestatexo`
--
ALTER TABLE `enc_respuestatexo`
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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `enc_encuestasm`
--
ALTER TABLE `enc_encuestasm`
  MODIFY `idencuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_opcion`
--
ALTER TABLE `enc_opcion`
  MODIFY `idopciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_pregunta`
--
ALTER TABLE `enc_pregunta`
  MODIFY `idpregunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_respuesta`
--
ALTER TABLE `enc_respuesta`
  MODIFY `idrespuestas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_respuestaopcion`
--
ALTER TABLE `enc_respuestaopcion`
  MODIFY `idrespuestaopcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_respuestatexo`
--
ALTER TABLE `enc_respuestatexo`
  MODIFY `idrespuestatexo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `enc_tipopreguntacatalogo`
--
ALTER TABLE `enc_tipopreguntacatalogo`
  MODIFY `idtipopregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `enc_respuestatexo`
--
ALTER TABLE `enc_respuestatexo`
  ADD CONSTRAINT `fk_enc_respuestatexo_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `enc_pregunta` (`idpregunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enc_respuestatexo_respuesta` FOREIGN KEY (`idrespuestas`) REFERENCES `enc_respuesta` (`idrespuestas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
