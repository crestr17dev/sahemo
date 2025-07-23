-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:43:51
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_usuario`
--

CREATE TABLE `App_usuarios_usuario` (
  `UsuarioId` int NOT NULL,
  `UsuarioCodigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioDocumento` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioTipoDocumento` enum('CC','CE','TI','PP','NIT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'CC',
  `UsuarioNombres` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioApellidos` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioTelefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UsuarioPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `UsuarioFoto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UsuarioCargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UsuarioDepartamento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UsuarioFechaRegistro` datetime NOT NULL,
  `UsuarioFechaActualizacion` datetime NOT NULL,
  `UsuarioUltimoAcceso` datetime DEFAULT NULL,
  `UsuarioIntentosLogin` int DEFAULT '0',
  `UsuarioFechaBloqueo` datetime DEFAULT NULL,
  `UsuarioPasswordCambio` datetime DEFAULT NULL,
  `UsuarioPasswordExpira` tinyint(1) DEFAULT '0',
  `UsuarioEstado` enum('Activo','Inactivo','Bloqueado','Eliminado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo',
  `UsuarioEmpresaId` int DEFAULT NULL,
  `UsuarioSucursalId` int DEFAULT NULL,
  `UsuarioSedeId` int DEFAULT NULL,
  `UsuarioIsSuperAdmin` tinyint(1) DEFAULT '0' COMMENT 'Super administrador inmutable',
  `UsuarioIsSystemAdmin` tinyint(1) DEFAULT '0' COMMENT 'Admin del sistema (proveedor)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `App_usuarios_usuario`
--

INSERT INTO `App_usuarios_usuario` (`UsuarioId`, `UsuarioCodigo`, `UsuarioDocumento`, `UsuarioTipoDocumento`, `UsuarioNombres`, `UsuarioApellidos`, `UsuarioEmail`, `UsuarioTelefono`, `UsuarioPassword`, `UsuarioFoto`, `UsuarioCargo`, `UsuarioDepartamento`, `UsuarioFechaRegistro`, `UsuarioFechaActualizacion`, `UsuarioUltimoAcceso`, `UsuarioIntentosLogin`, `UsuarioFechaBloqueo`, `UsuarioPasswordCambio`, `UsuarioPasswordExpira`, `UsuarioEstado`, `UsuarioEmpresaId`, `UsuarioSucursalId`, `UsuarioSedeId`, `UsuarioIsSuperAdmin`, `UsuarioIsSystemAdmin`) VALUES
(16, 'US7499190251', '1128392521', 'CC', 'Carlos Andres', 'Restrepo Gomez', 'contacto@sahemo.com', '3126457218', '$2y$10$QH0orLT2gJc9Cp7cwiDQdeqc91zGJT42XswbJX.Msz0kVs.ifUeKu', NULL, 'Administrador', 'Admin Sahemo', '2025-07-12 11:26:19', '2025-07-22 18:36:31', '2025-07-22 18:36:31', 0, NULL, '2025-07-18 16:07:07', 0, 'Activo', 16, 12, 1, 1, 1),
(21, 'US7956923452', '1017140643', 'CC', 'Diana Carolina', 'Garcia Mejia', 'karolgarcia12@gmail.com', '314245412', '$2y$10$Y.YmurxVdvg/imrF1Ou61.8E5BpioREJWIkZScjFwBZOPRe8PxBme', NULL, 'Administradora', 'Administradora', '2025-07-18 19:27:32', '2025-07-20 18:13:25', '2025-07-20 18:13:25', 0, NULL, '2025-07-18 20:07:40', 0, 'Activo', 16, 12, 1, 1, 0),
(22, 'US0062222963', '70252268', 'CC', 'Asdrubal De Jesus', 'Restrepo Castañeda', 'asdrubal@gmail.com', '3214575412', '$2y$10$HK5DrACbZATR3NtY7utO2OMZ99Pwct5/u46CF20xw1EIPPb1wfIAK', NULL, 'Auxiliar', 'Ventas', '2025-07-19 17:56:33', '2025-07-19 17:56:33', NULL, 0, NULL, '2025-07-26 17:56:33', 1, 'Activo', 16, 12, 1, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_usuario`
--
ALTER TABLE `App_usuarios_usuario`
  ADD PRIMARY KEY (`UsuarioId`),
  ADD UNIQUE KEY `UsuarioCodigo` (`UsuarioCodigo`),
  ADD UNIQUE KEY `UsuarioDocumento` (`UsuarioDocumento`),
  ADD UNIQUE KEY `UsuarioEmail` (`UsuarioEmail`),
  ADD KEY `idx_usuario_estado` (`UsuarioEstado`),
  ADD KEY `idx_usuario_email` (`UsuarioEmail`),
  ADD KEY `idx_usuario_documento` (`UsuarioDocumento`),
  ADD KEY `idx_usuario_ultimo_acceso` (`UsuarioUltimoAcceso`),
  ADD KEY `idx_usuario_fecha_registro` (`UsuarioFechaRegistro`),
  ADD KEY `fk_usuario_empresa` (`UsuarioEmpresaId`),
  ADD KEY `fk_usuario_sucursal` (`UsuarioSucursalId`),
  ADD KEY `fk_usuario_sede` (`UsuarioSedeId`),
  ADD KEY `idx_usuario_super_admin` (`UsuarioIsSuperAdmin`),
  ADD KEY `idx_usuario_system_admin` (`UsuarioIsSystemAdmin`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_usuario`
--
ALTER TABLE `App_usuarios_usuario`
  MODIFY `UsuarioId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `App_usuarios_usuario`
--
ALTER TABLE `App_usuarios_usuario`
  ADD CONSTRAINT `fk_usuario_empresa` FOREIGN KEY (`UsuarioEmpresaId`) REFERENCES `App_empresa_empresa` (`EmpresaId`),
  ADD CONSTRAINT `fk_usuario_sede` FOREIGN KEY (`UsuarioSedeId`) REFERENCES `App_empresa_sede` (`SedeId`),
  ADD CONSTRAINT `fk_usuario_sucursal` FOREIGN KEY (`UsuarioSucursalId`) REFERENCES `App_empresa_sucursal` (`SucursalId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- ===============================================
-- TABLA DE ROLES DEL SISTEMA
-- ===============================================


-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:45:03
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_rol`
--

CREATE TABLE `App_usuarios_rol` (
  `RolId` int NOT NULL,
  `RolCodigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `RolNombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `RolDescripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `RolNivel` int DEFAULT '1',
  `RolFechaCreacion` datetime NOT NULL,
  `RolEstado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `App_usuarios_rol`
--

INSERT INTO `App_usuarios_rol` (`RolId`, `RolCodigo`, `RolNombre`, `RolDescripcion`, `RolNivel`, `RolFechaCreacion`, `RolEstado`) VALUES
(1, 'SYSTEM_ADMIN', 'Administrador del Sistema', 'Proveedor del software con acceso total', 0, '2025-07-20 14:34:08', 'Activo'),
(2, 'SUPER_ADMIN', 'Super Administrador', 'Administrador principal de la empresa cliente', 1, '2025-07-20 14:34:08', 'Activo'),
(3, 'ADMIN', 'Administrador', 'Administrador de la empresa con permisos amplios', 2, '2025-07-20 14:34:08', 'Activo'),
(4, 'MANAGER', 'Gerente/Supervisor', 'Supervisor con permisos de gestión limitados', 3, '2025-07-20 14:34:08', 'Activo'),
(5, 'USER', 'Usuario Estándar', 'Usuario final con permisos básicos', 4, '2025-07-20 14:34:08', 'Activo'),
(6, 'READONLY', 'Solo Lectura', 'Usuario con permisos únicamente de consulta', 5, '2025-07-20 14:34:08', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_rol`
--
ALTER TABLE `App_usuarios_rol`
  ADD PRIMARY KEY (`RolId`),
  ADD UNIQUE KEY `RolCodigo` (`RolCodigo`),
  ADD KEY `idx_rol_estado` (`RolEstado`),
  ADD KEY `idx_rol_nivel` (`RolNivel`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_rol`
--
ALTER TABLE `App_usuarios_rol`
  MODIFY `RolId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ===============================================
-- TABLA DE PERMISOS DEL SISTEMA
-- ===============================================

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:46:40
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_permiso`
--

CREATE TABLE `App_usuarios_permiso` (
  `PermisoId` int NOT NULL,
  `PermisoCodigo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `PermisoNombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `PermisoDescripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `PermisoModulo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `PermisoGrupo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PermisoFechaCreacion` datetime NOT NULL,
  `PermisoEstado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `App_usuarios_permiso`
--

INSERT INTO `App_usuarios_permiso` (`PermisoId`, `PermisoCodigo`, `PermisoNombre`, `PermisoDescripcion`, `PermisoModulo`, `PermisoGrupo`, `PermisoFechaCreacion`, `PermisoEstado`) VALUES
(1, 'empresa_crear', 'Crear Empresas', 'El usuario puede crear empresas nuevas', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(2, 'listar_empresas', 'Listar Empresas', 'El usuario puede enlistar las empresas', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(3, 'eliminar_empresas', 'Eliminar Empresas', 'El usuario puede eliminar empresas del sistema', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(4, 'ver_empresas', 'Ver Empresas', 'El usuario puede ver empresas del sistema', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(5, 'editar_empresas', 'Editar Empresas', 'El usuario puede editar empresas del sistema', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(6, 'cambiar_estado_empresas', 'Cambiar Estado Empresas', 'El usuario puede cambiar los estados de las empresas', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(7, 'exportar_empresas', 'Exportar Empresas', 'El usuario puede exportar las empresas a excel', 'empresas', 'gestion_empresas', '2025-07-20 17:53:40', 'Activo'),
(8, 'listar_sucursales', 'Listar Sucursales', 'El usuario puede enlistar las sucursales', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(9, 'crear_sucursales', 'Crear Sucursales', 'El usuario puede crear sucursales nuevas', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(10, 'ver_sucursales', 'Ver Sucursales', 'El usuario puede ver sucursales del sistema', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(11, 'editar_sucursales', 'Editar Sucursales', 'El usuario puede editar sucursal del sistema', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(12, 'eliminar_sucursales', 'Eliminar Sucursales', 'El usuario puede eliminar sucursales del sistema', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(13, 'cambiar_estado_sucursales', 'Cambiar Estado Sucursales', 'El usuario puede cambiar los estados de las sucursales', 'empresas', 'gestion_sucursales', '2025-07-20 17:53:40', 'Activo'),
(14, 'listar_sedes', 'Listar Sedes', 'El usuario puede enlistar las sedes', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(15, 'crear_sedes', 'Crear Sedes', 'El usuario puede crear sedes nuevas', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(16, 'ver_sedes', 'Ver Sedes', 'El usuario puede ver sedes del sistema', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(17, 'editar_sedes', 'Editar Sedes', 'El usuario puede editar sedes del sistema', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(18, 'eliminar_sedes', 'Eliminar Sedes', 'El usuario puede eliminar sedes del sistema', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(19, 'cambiar_estado_sedes', 'Cambiar Estado Sedes', 'El usuario puede cambiar los estados de las sedes', 'empresas', 'gestion_sedes', '2025-07-20 17:53:40', 'Activo'),
(20, 'usuario_crear', 'Crear Usuarios', 'El usuario puede crear usuarios nuevos', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(21, 'listar_usuarios', 'Listar Usuarios', 'El usuario puede enlistar los usuarios', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(22, 'eliminar_usuarios', 'Eliminar Usuarios', 'El usuario puede eliminar usuarios del sistema', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(23, 'ver_usuarios', 'Ver Usuarios', 'El usuario puede ver usuarios del sistema', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(24, 'editar_usuarios', 'Editar Usuarios', 'El usuario puede editar usuarios del sistema', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(25, 'cambiar_estado_usuarios', 'Cambiar Estado Usuarios', 'El usuario puede cambiar los estados de los usuarios', 'usuarios', 'gestion_usuarios', '2025-07-20 17:53:40', 'Activo'),
(26, 'listar_roles', 'Listar Roles', 'El usuario puede ver la lista de roles del sistema', 'usuarios', 'gestion_roles', '2025-07-20 17:53:40', 'Activo'),
(27, 'crear_roles', 'Crear Roles', 'El usuario puede crear nuevos roles', 'usuarios', 'gestion_roles', '2025-07-20 17:53:40', 'Activo'),
(28, 'editar_roles', 'Editar Roles', 'El usuario puede modificar roles existentes', 'usuarios', 'gestion_roles', '2025-07-20 17:53:40', 'Activo'),
(29, 'eliminar_roles', 'Eliminar Roles', 'El usuario puede eliminar roles del sistema', 'usuarios', 'gestion_roles', '2025-07-20 17:53:40', 'Activo'),
(30, 'asignar_roles', 'Asignar Roles a Usuarios', 'El usuario puede asignar y quitar roles a otros usuarios', 'usuarios', 'gestion_roles', '2025-07-20 17:53:40', 'Activo'),
(31, 'ver_permisos', 'Ver Permisos', 'El usuario puede visualizar los permisos del sistema', 'usuarios', 'gestion_permisos', '2025-07-20 17:53:40', 'Activo'),
(32, 'gestionar_permisos', 'Gestionar Permisos', 'El usuario puede crear y modificar permisos específicos', 'usuarios', 'gestion_permisos', '2025-07-20 17:53:40', 'Activo'),
(33, 'ver_logs_seguridad', 'Ver Logs de Seguridad', 'El usuario puede consultar los logs de seguridad del sistema', 'sistema', 'administracion', '2025-07-20 17:53:40', 'Activo'),
(34, 'gestionar_sistema', 'Gestionar Sistema', 'El usuario puede realizar tareas de administración del sistema', 'sistema', 'administracion', '2025-07-20 17:53:40', 'Activo'),
(35, 'configurar_sistema', 'Configurar Sistema', 'El usuario puede modificar configuraciones del sistema', 'sistema', 'administracion', '2025-07-20 17:53:40', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_permiso`
--
ALTER TABLE `App_usuarios_permiso`
  ADD PRIMARY KEY (`PermisoId`),
  ADD UNIQUE KEY `PermisoCodigo` (`PermisoCodigo`),
  ADD KEY `idx_permiso_modulo` (`PermisoModulo`),
  ADD KEY `idx_permiso_grupo` (`PermisoGrupo`),
  ADD KEY `idx_permiso_estado` (`PermisoEstado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_permiso`
--
ALTER TABLE `App_usuarios_permiso`
  MODIFY `PermisoId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ===============================================
-- TABLA RELACIÓN USUARIO - ROL (Muchos a Muchos)
-- ===============================================

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:47:15
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_usuario_rol`
--

CREATE TABLE `App_usuarios_usuario_rol` (
  `UsuarioRolId` int NOT NULL,
  `UsuarioRolIdUsuario` int NOT NULL,
  `UsuarioRolIdRol` int NOT NULL,
  `UsuarioRolFechaAsignacion` datetime NOT NULL,
  `UsuarioRolUsuarioAsigna` int DEFAULT NULL,
  `UsuarioRolEstado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `App_usuarios_usuario_rol`
--

INSERT INTO `App_usuarios_usuario_rol` (`UsuarioRolId`, `UsuarioRolIdUsuario`, `UsuarioRolIdRol`, `UsuarioRolFechaAsignacion`, `UsuarioRolUsuarioAsigna`, `UsuarioRolEstado`) VALUES
(1, 16, 1, '2025-07-20 18:15:56', 16, 'Activo'),
(2, 21, 3, '2025-07-20 18:20:21', 21, 'Activo'),
(3, 22, 4, '2025-07-20 18:22:45', 22, 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_usuario_rol`
--
ALTER TABLE `App_usuarios_usuario_rol`
  ADD PRIMARY KEY (`UsuarioRolId`),
  ADD UNIQUE KEY `usuario_rol_unico` (`UsuarioRolIdUsuario`,`UsuarioRolIdRol`),
  ADD KEY `UsuarioRolIdUsuario` (`UsuarioRolIdUsuario`),
  ADD KEY `UsuarioRolIdRol` (`UsuarioRolIdRol`),
  ADD KEY `UsuarioRolUsuarioAsigna` (`UsuarioRolUsuarioAsigna`),
  ADD KEY `idx_usuario_rol_estado` (`UsuarioRolEstado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_usuario_rol`
--
ALTER TABLE `App_usuarios_usuario_rol`
  MODIFY `UsuarioRolId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `App_usuarios_usuario_rol`
--
ALTER TABLE `App_usuarios_usuario_rol`
  ADD CONSTRAINT `App_usuarios_usuario_rol_ibfk_1` FOREIGN KEY (`UsuarioRolIdUsuario`) REFERENCES `App_usuarios_usuario` (`UsuarioId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `App_usuarios_usuario_rol_ibfk_2` FOREIGN KEY (`UsuarioRolIdRol`) REFERENCES `App_usuarios_rol` (`RolId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `App_usuarios_usuario_rol_ibfk_3` FOREIGN KEY (`UsuarioRolUsuarioAsigna`) REFERENCES `App_usuarios_usuario` (`UsuarioId`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ===============================================
-- TABLA RELACIÓN ROL - PERMISO (Muchos a Muchos)
-- ===============================================

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:48:18
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_rol_permiso`
--

CREATE TABLE `App_usuarios_rol_permiso` (
  `RolPermisoId` int NOT NULL,
  `RolPermisoIdRol` int NOT NULL,
  `RolPermisoIdPermiso` int NOT NULL,
  `RolPermisoFechaAsignacion` datetime NOT NULL,
  `RolPermisoEstado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `App_usuarios_rol_permiso`
--

INSERT INTO `App_usuarios_rol_permiso` (`RolPermisoId`, `RolPermisoIdRol`, `RolPermisoIdPermiso`, `RolPermisoFechaAsignacion`, `RolPermisoEstado`) VALUES
(1, 1, 1, '2025-07-20 17:58:30', 'Activo'),
(2, 1, 2, '2025-07-20 17:58:30', 'Activo'),
(3, 1, 3, '2025-07-20 17:58:30', 'Activo'),
(4, 1, 4, '2025-07-20 17:58:30', 'Activo'),
(5, 1, 5, '2025-07-20 17:58:30', 'Activo'),
(6, 1, 6, '2025-07-20 17:58:30', 'Activo'),
(7, 1, 7, '2025-07-20 17:58:30', 'Activo'),
(8, 1, 8, '2025-07-20 17:58:30', 'Activo'),
(9, 1, 9, '2025-07-20 17:58:30', 'Activo'),
(10, 1, 10, '2025-07-20 17:58:30', 'Activo'),
(11, 1, 11, '2025-07-20 17:58:30', 'Activo'),
(12, 1, 12, '2025-07-20 17:58:30', 'Activo'),
(13, 1, 13, '2025-07-20 17:58:30', 'Activo'),
(14, 1, 14, '2025-07-20 17:58:30', 'Activo'),
(15, 1, 15, '2025-07-20 17:58:30', 'Activo'),
(16, 1, 16, '2025-07-20 17:58:30', 'Activo'),
(17, 1, 17, '2025-07-20 17:58:30', 'Activo'),
(18, 1, 18, '2025-07-20 17:58:30', 'Activo'),
(19, 1, 19, '2025-07-20 17:58:30', 'Activo'),
(20, 1, 20, '2025-07-20 17:58:30', 'Activo'),
(21, 1, 21, '2025-07-20 17:58:30', 'Activo'),
(22, 1, 22, '2025-07-20 17:58:30', 'Activo'),
(23, 1, 23, '2025-07-20 17:58:30', 'Activo'),
(24, 1, 24, '2025-07-20 17:58:30', 'Activo'),
(25, 1, 25, '2025-07-20 17:58:30', 'Activo'),
(26, 1, 26, '2025-07-20 17:58:30', 'Activo'),
(27, 1, 27, '2025-07-20 17:58:30', 'Activo'),
(28, 1, 28, '2025-07-20 17:58:30', 'Activo'),
(29, 1, 29, '2025-07-20 17:58:30', 'Activo'),
(30, 1, 30, '2025-07-20 17:58:30', 'Activo'),
(31, 1, 31, '2025-07-20 17:58:30', 'Activo'),
(32, 1, 32, '2025-07-20 17:58:30', 'Activo'),
(33, 1, 33, '2025-07-20 17:58:30', 'Activo'),
(34, 1, 34, '2025-07-20 17:58:30', 'Activo'),
(35, 1, 35, '2025-07-20 17:58:30', 'Activo'),
(64, 2, 2, '2025-07-20 17:59:18', 'Activo'),
(65, 2, 3, '2025-07-20 17:59:18', 'Activo'),
(66, 2, 4, '2025-07-20 17:59:18', 'Activo'),
(67, 2, 5, '2025-07-20 17:59:18', 'Activo'),
(68, 2, 6, '2025-07-20 17:59:18', 'Activo'),
(69, 2, 7, '2025-07-20 17:59:18', 'Activo'),
(70, 2, 8, '2025-07-20 17:59:18', 'Activo'),
(71, 2, 9, '2025-07-20 17:59:18', 'Activo'),
(72, 2, 10, '2025-07-20 17:59:18', 'Activo'),
(73, 2, 11, '2025-07-20 17:59:18', 'Activo'),
(74, 2, 12, '2025-07-20 17:59:18', 'Activo'),
(75, 2, 13, '2025-07-20 17:59:18', 'Activo'),
(76, 2, 14, '2025-07-20 17:59:18', 'Activo'),
(77, 2, 15, '2025-07-20 17:59:18', 'Activo'),
(78, 2, 16, '2025-07-20 17:59:18', 'Activo'),
(79, 2, 17, '2025-07-20 17:59:18', 'Activo'),
(80, 2, 18, '2025-07-20 17:59:18', 'Activo'),
(81, 2, 19, '2025-07-20 17:59:18', 'Activo'),
(82, 2, 20, '2025-07-20 17:59:18', 'Activo'),
(83, 2, 21, '2025-07-20 17:59:18', 'Activo'),
(84, 2, 22, '2025-07-20 17:59:18', 'Activo'),
(85, 2, 23, '2025-07-20 17:59:18', 'Activo'),
(86, 2, 24, '2025-07-20 17:59:18', 'Activo'),
(87, 2, 25, '2025-07-20 17:59:18', 'Activo'),
(88, 2, 26, '2025-07-20 17:59:18', 'Activo'),
(89, 2, 27, '2025-07-20 17:59:18', 'Activo'),
(90, 2, 28, '2025-07-20 17:59:18', 'Activo'),
(91, 2, 29, '2025-07-20 17:59:18', 'Activo'),
(92, 2, 30, '2025-07-20 17:59:18', 'Activo'),
(93, 2, 31, '2025-07-20 17:59:18', 'Activo'),
(94, 2, 32, '2025-07-20 17:59:18', 'Activo'),
(95, 2, 33, '2025-07-20 17:59:18', 'Activo'),
(127, 3, 30, '2025-07-20 18:07:44', 'Activo'),
(128, 3, 19, '2025-07-20 18:07:44', 'Activo'),
(129, 3, 13, '2025-07-20 18:07:44', 'Activo'),
(130, 3, 25, '2025-07-20 18:07:44', 'Activo'),
(131, 3, 15, '2025-07-20 18:07:44', 'Activo'),
(132, 3, 9, '2025-07-20 18:07:44', 'Activo'),
(133, 3, 5, '2025-07-20 18:07:44', 'Activo'),
(134, 3, 17, '2025-07-20 18:07:44', 'Activo'),
(135, 3, 11, '2025-07-20 18:07:44', 'Activo'),
(136, 3, 24, '2025-07-20 18:07:44', 'Activo'),
(137, 3, 18, '2025-07-20 18:07:44', 'Activo'),
(138, 3, 12, '2025-07-20 18:07:44', 'Activo'),
(139, 3, 22, '2025-07-20 18:07:44', 'Activo'),
(140, 3, 7, '2025-07-20 18:07:44', 'Activo'),
(141, 3, 2, '2025-07-20 18:07:44', 'Activo'),
(142, 3, 26, '2025-07-20 18:07:44', 'Activo'),
(143, 3, 14, '2025-07-20 18:07:44', 'Activo'),
(144, 3, 8, '2025-07-20 18:07:44', 'Activo'),
(145, 3, 21, '2025-07-20 18:07:44', 'Activo'),
(146, 3, 20, '2025-07-20 18:07:44', 'Activo'),
(147, 3, 4, '2025-07-20 18:07:44', 'Activo'),
(148, 3, 31, '2025-07-20 18:07:44', 'Activo'),
(149, 3, 16, '2025-07-20 18:07:44', 'Activo'),
(150, 3, 10, '2025-07-20 18:07:44', 'Activo'),
(151, 3, 23, '2025-07-20 18:07:44', 'Activo'),
(158, 4, 17, '2025-07-20 18:10:09', 'Activo'),
(159, 4, 11, '2025-07-20 18:10:09', 'Activo'),
(160, 4, 24, '2025-07-20 18:10:09', 'Activo'),
(161, 4, 2, '2025-07-20 18:10:09', 'Activo'),
(162, 4, 26, '2025-07-20 18:10:09', 'Activo'),
(163, 4, 14, '2025-07-20 18:10:09', 'Activo'),
(164, 4, 8, '2025-07-20 18:10:09', 'Activo'),
(165, 4, 21, '2025-07-20 18:10:09', 'Activo'),
(166, 4, 4, '2025-07-20 18:10:09', 'Activo'),
(167, 4, 31, '2025-07-20 18:10:09', 'Activo'),
(168, 4, 16, '2025-07-20 18:10:09', 'Activo'),
(169, 4, 10, '2025-07-20 18:10:09', 'Activo'),
(170, 4, 23, '2025-07-20 18:10:09', 'Activo'),
(173, 6, 2, '2025-07-20 18:12:34', 'Activo'),
(174, 6, 26, '2025-07-20 18:12:34', 'Activo'),
(175, 6, 14, '2025-07-20 18:12:34', 'Activo'),
(176, 6, 8, '2025-07-20 18:12:34', 'Activo'),
(177, 6, 21, '2025-07-20 18:12:34', 'Activo'),
(178, 6, 4, '2025-07-20 18:12:34', 'Activo'),
(179, 6, 16, '2025-07-20 18:12:34', 'Activo'),
(180, 6, 10, '2025-07-20 18:12:34', 'Activo'),
(181, 6, 23, '2025-07-20 18:12:34', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_rol_permiso`
--
ALTER TABLE `App_usuarios_rol_permiso`
  ADD PRIMARY KEY (`RolPermisoId`),
  ADD UNIQUE KEY `rol_permiso_unico` (`RolPermisoIdRol`,`RolPermisoIdPermiso`),
  ADD KEY `RolPermisoIdRol` (`RolPermisoIdRol`),
  ADD KEY `RolPermisoIdPermiso` (`RolPermisoIdPermiso`),
  ADD KEY `idx_rol_permiso_estado` (`RolPermisoEstado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_rol_permiso`
--
ALTER TABLE `App_usuarios_rol_permiso`
  MODIFY `RolPermisoId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `App_usuarios_rol_permiso`
--
ALTER TABLE `App_usuarios_rol_permiso`
  ADD CONSTRAINT `App_usuarios_rol_permiso_ibfk_1` FOREIGN KEY (`RolPermisoIdRol`) REFERENCES `App_usuarios_rol` (`RolId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `App_usuarios_rol_permiso_ibfk_2` FOREIGN KEY (`RolPermisoIdPermiso`) REFERENCES `App_usuarios_permiso` (`PermisoId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ===============================================
-- TABLA PERMISOS ESPECÍFICOS POR USUARIO
-- ===============================================

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:49:07
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_usuario_permiso`
--

CREATE TABLE `App_usuarios_usuario_permiso` (
  `UsuarioPermisoId` int NOT NULL,
  `UsuarioPermisoIdUsuario` int NOT NULL,
  `UsuarioPermisoIdPermiso` int NOT NULL,
  `UsuarioPermisoTipo` enum('Concedido','Denegado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Concedido',
  `UsuarioPermisoFechaAsignacion` datetime NOT NULL,
  `UsuarioPermisoUsuarioAsigna` int DEFAULT NULL,
  `UsuarioPermisoMotivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `UsuarioPermisoEstado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_usuario_permiso`
--
ALTER TABLE `App_usuarios_usuario_permiso`
  ADD PRIMARY KEY (`UsuarioPermisoId`),
  ADD UNIQUE KEY `usuario_permiso_unico` (`UsuarioPermisoIdUsuario`,`UsuarioPermisoIdPermiso`),
  ADD KEY `UsuarioPermisoIdUsuario` (`UsuarioPermisoIdUsuario`),
  ADD KEY `UsuarioPermisoIdPermiso` (`UsuarioPermisoIdPermiso`),
  ADD KEY `UsuarioPermisoUsuarioAsigna` (`UsuarioPermisoUsuarioAsigna`),
  ADD KEY `idx_usuario_permiso_tipo` (`UsuarioPermisoTipo`),
  ADD KEY `idx_usuario_permiso_estado` (`UsuarioPermisoEstado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_usuario_permiso`
--
ALTER TABLE `App_usuarios_usuario_permiso`
  MODIFY `UsuarioPermisoId` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `App_usuarios_usuario_permiso`
--
ALTER TABLE `App_usuarios_usuario_permiso`
  ADD CONSTRAINT `App_usuarios_usuario_permiso_ibfk_1` FOREIGN KEY (`UsuarioPermisoIdUsuario`) REFERENCES `App_usuarios_usuario` (`UsuarioId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `App_usuarios_usuario_permiso_ibfk_2` FOREIGN KEY (`UsuarioPermisoIdPermiso`) REFERENCES `App_usuarios_permiso` (`PermisoId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `App_usuarios_usuario_permiso_ibfk_3` FOREIGN KEY (`UsuarioPermisoUsuarioAsigna`) REFERENCES `App_usuarios_usuario` (`UsuarioId`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ===============================================
-- TABLA CONTROL DE SESIONES ACTIVAS
-- ===============================================

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-07-2025 a las 00:49:41
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `MI_EMPRENDIMIENTO`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `App_usuarios_sesion`
--

CREATE TABLE `App_usuarios_sesion` (
  `SesionId` int NOT NULL,
  `SesionIdUsuario` int NOT NULL,
  `SesionToken` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SesionIpAcceso` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SesionUserAgent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `SesionFechaInicio` datetime NOT NULL,
  `SesionFechaUltimaActividad` datetime NOT NULL,
  `SesionFechaExpiracion` datetime NOT NULL,
  `SesionEstado` enum('Activa','Expirada','Cerrada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `App_usuarios_sesion`
--
ALTER TABLE `App_usuarios_sesion`
  ADD PRIMARY KEY (`SesionId`),
  ADD UNIQUE KEY `SesionToken` (`SesionToken`),
  ADD KEY `SesionIdUsuario` (`SesionIdUsuario`),
  ADD KEY `idx_sesion_estado` (`SesionEstado`),
  ADD KEY `idx_sesion_expiracion` (`SesionFechaExpiracion`),
  ADD KEY `idx_sesion_ultima_actividad` (`SesionFechaUltimaActividad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `App_usuarios_sesion`
--
ALTER TABLE `App_usuarios_sesion`
  MODIFY `SesionId` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `App_usuarios_sesion`
--
ALTER TABLE `App_usuarios_sesion`
  ADD CONSTRAINT `App_usuarios_sesion_ibfk_1` FOREIGN KEY (`SesionIdUsuario`) REFERENCES `App_usuarios_usuario` (`UsuarioId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



