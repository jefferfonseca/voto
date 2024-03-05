/*
Estructura basica de la bade de datos
*/
--
-- Base de datos: `educovota`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--
CREATE DATABASE educovota CHARACTER SET utf8 COLLATE utf8_general_ci;
use educovota;
CREATE TABLE IF NOT EXISTS `administradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `nombres`, `apellidos`, `password`) VALUES
(1, 'admin', 'Ing. Jeferson', 'Fonseca Soto', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general`
--

CREATE TABLE IF NOT EXISTS `general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institucion` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `activo` varchar(1) NOT NULL,
  `clave` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `general`
--

INSERT INTO `general` (`id`, `institucion`, `descripcion`, `activo`, `clave`) VALUES
(1, 'Institución Educativa Técnica San Nicolás', 'ELECCIONES ESTUDIANTILES', 'S', 'S');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `candidatos`
--

CREATE TABLE IF NOT EXISTS `candidatos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `representante` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `candidatos`
--

INSERT INTO `candidatos` (`id`, `nombres`, `apellidos`, `representante`) VALUES
(1, 'NOMBRE1', 'CANDIDATO1', 1),
(2, 'NOMBRE2', 'CANDIDATO2', 1),
(3, 'VOTO EN BLANCO', '', 1),
(4, 'NOMBRE1', 'CANDIDATO1', 2),
(5, 'NOMBRE2', 'CANDIDATO2', 2),
(6, 'VOTO EN BLANCO', '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control`
--

CREATE TABLE IF NOT EXISTS `control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_fecha` date NOT NULL,
  `c_hora` time NOT NULL,
  `c_ip` varchar(20) NOT NULL,
  `c_accion` varchar(50) NOT NULL,
  `c_idest` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grado` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `documento` varchar(30) NOT NULL,  
  `clave` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Personero', 'Candidatos a la Personería'),
(2, 'Consejo', 'Candidatos al Consejo Directivo');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voto`
--

CREATE TABLE IF NOT EXISTS `voto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `candidato` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE IF NOT EXISTS `grados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grado` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Volcar la base de datos para la tabla `grados`
--

INSERT INTO `grados` (`grado`) VALUES
('TRANSICIÓN'),
('PRIMERO'),
('SEGUNDO'),
('TERCERO'),
('CUARTO'),
('QUINTO'),
('SEXTO A'),
('SEXTO B'),
('SEPTIMO'),
('OCTAVO A'),
('OCTAVO B'),
('NOVENO'),
('DECIMO'),
('UNDECIMO');
