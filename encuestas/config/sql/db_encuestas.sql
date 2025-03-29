-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-03-2025 a las 06:16:30
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
(5, 'Lenguajes de programación', 'Preferencias de Lenguajes de Programacion', 2, '2025-03-23', 'S'),
(6, 'Frutas', 'Preferencias de frutas', 2, '2025-03-27', 'S'),
(7, 'Comparación de métodos educativos', 'El propósito de esta encuesta es recopilar información sobre las experiencias y opiniones de los estudiantes respecto a la educación en línea y la educación tradicional.\r\nLos datos de la encuesta no seran compartidos de forma específica, serán analizados ', 1, '2025-03-28', 'S');

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
(20, 15, 'No'),
(21, 17, 'Mango'),
(22, 17, 'Fresa'),
(23, 17, 'Kiwi'),
(24, 18, 'Platano'),
(25, 18, 'Sandia'),
(26, 19, '18 años a 24 años'),
(27, 19, '25 años a 34 años'),
(28, 19, '35 Años A 44 Años'),
(29, 19, '45 años a 54 años'),
(30, 19, 'Más de 54 años'),
(31, 20, 'Masculino'),
(32, 20, 'Femenino'),
(33, 20, 'Prefiero no decir'),
(34, 21, 'Licenciatura'),
(35, 21, 'Maestría'),
(36, 21, 'Doctorado'),
(37, 22, 'Estudiante'),
(38, 22, 'Docente'),
(39, 22, 'Ambas'),
(40, 24, 'Si'),
(41, 24, 'No'),
(42, 25, 'Escolarizada'),
(43, 25, 'No escolarizada'),
(44, 25, 'Mixta'),
(45, 26, 'Si'),
(46, 26, 'No'),
(47, 27, '1 Totalmente en desacuerdo'),
(48, 27, '2'),
(49, 27, '3'),
(50, 27, '4'),
(51, 27, '5 Totalmente de acuerdo'),
(52, 28, '1 Nada importante / No es necesario'),
(53, 28, '2'),
(54, 28, '3'),
(55, 28, '4'),
(56, 28, '5 Indispensable'),
(57, 29, '1 Totalmente incapaces '),
(58, 29, '2'),
(59, 29, '3'),
(60, 29, '4'),
(61, 29, '5 Totalmente competentes');

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
(15, 5, 'Te gusta PHP?', '0', 3),
(16, 6, 'Porque te gusta la fruta?', '0', 1),
(17, 6, 'Cuales de estas frutas has probado?', '0', 4),
(18, 6, 'Cual de estas frutas prefieres?', '0', 3),
(19, 7, '¿Cuántos años tienes?', '0', 3),
(20, 7, 'Sexo', '0', 3),
(21, 7, 'Nivel de estudios', '0', 3),
(22, 7, 'Actualmente,  ¿Eres estudiante o docente?', '0', 3),
(23, 7, '¿Que licenciatura estudiaste o estas estudiando?', '0', 1),
(24, 7, '¿Has estudiado en un plan de estudios con una modalidad no escolarizada? ', '0', 3),
(25, 7, '¿Qué modalidad de aprendizaje educativa prefieres?', '0', 3),
(26, 7, 'Prefieres (o preferirías en el caso de que nunca lo hayas experimentado) un trabajo en línea (home office) sobre un trabajo presencial', '0', 3),
(27, 7, '¿Que tan acuerdo estás con esta afirmación? \"Se aprende mejor estudiando en una modalidad no escolarizada o cursos en línea.\"', '0', 3),
(28, 7, '¿Qué tan importante es para ti tener a un docente o tutor (presencial) atendiéndote para resolver tus dudas?', '0', 3),
(29, 7, 'Actualmente los docentes son competentes para enseñar de manera profesional en clases en línea (utilización de programas para hacer sesiones grupales, presentar diapositivas, pizarra en línea, organización de contenido en plataformas, organizar e informar', '0', 3),
(30, 7, '¿Qué cambiarías en el enfoque de enseñanza en la modalidad escolarizada?', '0', 1),
(31, 7, '¿Qué cosas no te han gustado al estudiar en línea (sesiones, presentación, tareas, retroalimentación del docente, etc.)?', '0', 1);

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
(19, 5, 2, '2025-03-23'),
(20, 4, 2, '2025-03-27'),
(21, 4, 2, '2025-03-27'),
(22, 4, 2, '2025-03-27'),
(23, 4, 2, '2025-03-27'),
(24, 4, 1, '2025-03-27'),
(25, 6, 2, '2025-03-27'),
(26, 6, 1, '2025-03-27'),
(27, 7, 1, '2025-03-28'),
(28, 7, 1, '2025-03-28'),
(29, 7, 1, '2025-03-28'),
(30, 7, 1, '2025-03-28'),
(31, 7, 2, '2025-03-28'),
(32, 7, 2, '2025-03-28'),
(33, 7, 2, '2025-03-28'),
(34, 7, 2, '2025-03-28');

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
(50, 19, 19, 15),
(51, 10, 20, 8),
(52, 11, 20, 8),
(53, 12, 20, 8),
(54, 13, 20, 9),
(55, 15, 20, 11),
(56, 17, 20, 12),
(57, 10, 21, 8),
(58, 11, 21, 8),
(59, 12, 21, 8),
(60, 14, 21, 9),
(61, 16, 21, 11),
(62, 18, 21, 12),
(63, 10, 22, 8),
(64, 11, 22, 8),
(65, 12, 22, 8),
(66, 13, 22, 9),
(67, 15, 22, 11),
(68, 17, 22, 12),
(69, 10, 23, 8),
(70, 12, 23, 8),
(71, 14, 23, 9),
(72, 15, 23, 11),
(73, 18, 23, 12),
(74, 11, 24, 8),
(75, 14, 24, 9),
(76, 16, 24, 11),
(77, 18, 24, 12),
(78, 21, 25, 17),
(79, 23, 25, 17),
(80, 24, 25, 18),
(81, 21, 26, 17),
(82, 22, 26, 17),
(83, 24, 26, 18),
(84, 26, 27, 19),
(85, 31, 27, 20),
(86, 34, 27, 21),
(87, 38, 27, 22),
(88, 40, 27, 24),
(89, 43, 27, 25),
(90, 46, 27, 26),
(91, 49, 27, 27),
(92, 53, 27, 28),
(93, 58, 27, 29),
(94, 27, 28, 19),
(95, 32, 28, 20),
(96, 35, 28, 21),
(97, 37, 28, 22),
(98, 41, 28, 24),
(99, 42, 28, 25),
(100, 46, 28, 26),
(101, 48, 28, 27),
(102, 55, 28, 28),
(103, 58, 28, 29),
(104, 27, 30, 19),
(105, 32, 30, 20),
(106, 35, 30, 21),
(107, 38, 30, 22),
(108, 41, 30, 24),
(109, 42, 30, 25),
(110, 45, 30, 26),
(111, 48, 30, 27),
(112, 54, 30, 28),
(113, 58, 30, 29),
(114, 26, 31, 19),
(115, 33, 31, 20),
(116, 35, 31, 21),
(117, 37, 31, 22),
(118, 41, 31, 24),
(119, 44, 31, 25),
(120, 46, 31, 26),
(121, 48, 31, 27),
(122, 55, 31, 28),
(123, 58, 31, 29),
(124, 28, 32, 19),
(125, 31, 32, 20),
(126, 34, 32, 21),
(127, 37, 32, 22),
(128, 41, 32, 24),
(129, 43, 32, 25),
(130, 45, 32, 26),
(131, 48, 32, 27),
(132, 53, 32, 28),
(133, 60, 32, 29),
(134, 27, 33, 19),
(135, 31, 33, 20),
(136, 35, 33, 21),
(137, 37, 33, 22),
(138, 40, 33, 24),
(139, 43, 33, 25),
(140, 46, 33, 26),
(141, 48, 33, 27),
(142, 54, 33, 28),
(143, 59, 33, 29),
(144, 27, 34, 19),
(145, 32, 34, 20),
(146, 35, 34, 21),
(147, 37, 34, 22),
(148, 41, 34, 24),
(149, 42, 34, 25),
(150, 46, 34, 26),
(151, 51, 34, 27),
(152, 53, 34, 28),
(153, 59, 34, 29);

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
(16, 'Sus funciones para paginas web', 19, 14),
(17, 'Meter al partido a Adame', 20, 10),
(18, 'Bajar la jornada', 21, 10),
(19, 'Hola', 22, 10),
(20, 'Que todos se hagan millonarios', 23, 10),
(21, 'Que le bajen el precio de la coca (el refresco)', 24, 10),
(22, 'Porque si', 25, 16),
(23, 'Porque estan buenas', 26, 16),
(24, 'Software', 27, 23),
(25, 'La personalización del aprendizaje ya que cada estudiante aprende a su ritmo y tiene intereses distintos', 27, 30),
(26, 'Que en varias ocasiones padres de familia se han quejado de que no es lo mismo estudiar en linea que estudiar de forma presencial en las instalaciones escolares', 27, 31),
(27, 'Psicologia', 28, 23),
(28, 'Usar más tiempo para poner en práctica el conocimiento y utilizar plataformas como una herramienta adicional de enseñanza', 28, 30),
(29, 'Que te dan obliguen a asistir a las sesiones. Deberias poder pasar la materia cumpliendo los trabajos y proyectos.', 28, 31),
(30, 'Que sea híbrida', 29, 30),
(31, 'Que no todos los maestros son capaces de adaptarse', 29, 31),
(32, 'Ingenieria civil', 30, 23),
(33, 'Si', 30, 30),
(34, 'Que hablen todos al mismo tiempo y no escuchar ala primera', 30, 31),
(35, 'Filosofia', 31, 23),
(36, 'La presencia o acudir por Interes', 31, 30),
(37, 'Más seguimiento, realizar micro actividades', 31, 31),
(38, 'Derecho', 32, 23),
(39, 'El uso de material didáctico actualizado', 32, 30),
(40, 'Algunos docentes no organizan bien el contenido en la plataforma.', 32, 31),
(41, 'Odontologia', 33, 23),
(42, 'Esta bien creo la forma actual esta bien', 33, 30),
(43, 'Que te exijan estar con la webcam prendida.', 33, 31),
(44, 'Administracion', 34, 23),
(45, 'No tengo problema con ninguno', 34, 30),
(46, 'Hay profes que hablan demasiado y se desvian, asi como no todos tenemos los recursos para tener clases en linea, pc lentas o viejas, otros no tienen pc y utilizan las que ofrece la escuela, entre otras cosas', 34, 31);

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
(2, 'luis', 'Luis Eduardo', 'Montiel', 'Urias', '1998-12-12', 'luisurias@gmail.com', '6871106756', 'Masculino', '$2y$10$T0vHXWvvRMAMfmS83kfHo.3cabJq9FpfwyZdsL525DHS.TOgDnXOO');

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
  MODIFY `idencuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `enc_opcion`
--
ALTER TABLE `enc_opcion`
  MODIFY `idopciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `enc_pregunta`
--
ALTER TABLE `enc_pregunta`
  MODIFY `idpregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `enc_respuesta`
--
ALTER TABLE `enc_respuesta`
  MODIFY `idrespuestas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `enc_respuestaopcion`
--
ALTER TABLE `enc_respuestaopcion`
  MODIFY `idrespuestaopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT de la tabla `enc_respuestatexto`
--
ALTER TABLE `enc_respuestatexto`
  MODIFY `idrespuestatexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
