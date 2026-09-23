
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `ram_asignaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_asignaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `unidad_id` int unsigned NOT NULL,
  `terminado` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `asignaciones_cotizacion_id_foreign` (`cotizacion_id`),
  KEY `asignaciones_user_id_foreign` (`user_id`),
  KEY `asignaciones_unidad_id_foreign` (`unidad_id`),
  CONSTRAINT `asignaciones_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `ram_cotizaciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `asignaciones_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `ram_unidades` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `asignaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1265 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_asignaciones_combustibles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_asignaciones_combustibles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `asignacion_id` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `gasolinera_id` int unsigned NOT NULL,
  `ticket` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `litros` double(15,2) unsigned NOT NULL,
  `precio` double(15,2) unsigned NOT NULL,
  `total` double(15,2) unsigned NOT NULL,
  `kilometraje` double(15,2) unsigned NOT NULL,
  `rendimiento` double(15,2) unsigned NOT NULL,
  `foto_ticket` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_antes` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_despues` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_km` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `factura_proveedor` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dias_credito` int DEFAULT NULL,
  `fecha_limite_pago` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asignaciones_combustibles_asignacion_id_foreign` (`asignacion_id`),
  KEY `asignaciones_combustibles_gasolinera_id_foreign` (`gasolinera_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1473 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_asignaciones_especiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_asignaciones_especiales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unidad_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `gasolinera_id` int unsigned NOT NULL,
  `ticket` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `litros` double(15,2) unsigned NOT NULL,
  `precio` double(15,2) unsigned NOT NULL,
  `total` double(15,2) unsigned NOT NULL,
  `kilometraje` double(15,2) unsigned NOT NULL,
  `rendimiento` double(15,2) unsigned NOT NULL,
  `foto_ticket` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_antes` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_despues` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_tablero_km` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `factura_proveedor` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dias_credito` int DEFAULT NULL,
  `fecha_limite_pago` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asignaciones_especiales_unidad_id_foreign` (`unidad_id`),
  KEY `asignaciones_especiales_user_id_foreign` (`user_id`),
  KEY `asignaciones_especiales_gasolinera_id_foreign` (`gasolinera_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25209 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_asistencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_asistencias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `asistencia` date NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10971 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `banco` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `no_cuenta` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `clabe` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_categorias` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_detalle`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_detalle` AS SELECT
 1 AS `id`,
 1 AS `bancos_id`,
 1 AS `mes`,
 1 AS `anno`,
 1 AS `periodo`,
 1 AS `movimiento`,
 1 AS `total`,
 1 AS `observaciones`,
 1 AS `fecha`,
 1 AS `folio`,
 1 AS `categoria_id`,
 1 AS `subcategoria_id`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_list`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_list`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_list` AS SELECT
 1 AS `id`,
 1 AS `banco`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_movimientos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bancos_id` int unsigned NOT NULL,
  `periodo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `categoria_id` int unsigned NOT NULL,
  `subcategoria_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `movimiento` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `folio` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `tipo` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cantidad` double(15,2) NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `bancos_movimientos_bancos_id_foreign` (`bancos_id`),
  CONSTRAINT `bancos_movimientos_bancos_id_foreign` FOREIGN KEY (`bancos_id`) REFERENCES `ram_bancos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=100193 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_movimientos_sum`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_movimientos_sum`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_movimientos_sum` AS SELECT
 1 AS `periodo`,
 1 AS `bancos_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_movimientos_sum_det`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_movimientos_sum_det`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_movimientos_sum_det` AS SELECT
 1 AS `periodo`,
 1 AS `bancos_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_periodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_periodos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `periodo` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_prestamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_prestamos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bancos_id` int unsigned NOT NULL,
  `periodo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `categoria_id` int unsigned NOT NULL,
  `subcategoria_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `movimiento` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `folio` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `user_id` int unsigned NOT NULL,
  `tipo` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cantidad` double(15,2) NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `bancos_prestamos_bancos_id_foreign` (`bancos_id`),
  KEY `bancos_prestamos_user_id_foreign` (`user_id`),
  CONSTRAINT `bancos_prestamos_bancos_id_foreign` FOREIGN KEY (`bancos_id`) REFERENCES `ram_bancos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bancos_prestamos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=30754 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_presupuesto`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_presupuesto`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_presupuesto` AS SELECT
 1 AS `bancos_id`,
 1 AS `mes`,
 1 AS `anno`,
 1 AS `periodo`,
 1 AS `categoria_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_presupuesto_detalle`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_presupuesto_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_presupuesto_detalle` AS SELECT
 1 AS `bancos_id`,
 1 AS `periodo`,
 1 AS `categoria_id`,
 1 AS `subcategoria_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_reporte_presupuesto`;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_reporte_presupuesto`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_bancos_reporte_presupuesto` AS SELECT
 1 AS `bancos_id`,
 1 AS `periodo`,
 1 AS `year`,
 1 AS `mes`,
 1 AS `categoria_id`,
 1 AS `subcategoria_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_bancos_saldos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_saldos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bancos_id` int unsigned NOT NULL,
  `periodo` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `saldo_inicial` double(15,2) NOT NULL,
  `saldo_final` double(15,2) NOT NULL,
  `cerrado` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `bancos_saldos_bancos_id_foreign` (`bancos_id`),
  CONSTRAINT `bancos_saldos_bancos_id_foreign` FOREIGN KEY (`bancos_id`) REFERENCES `ram_bancos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2164 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bancos_subcategorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bancos_subcategorias` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` int unsigned NOT NULL,
  `subcategoria` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `bancos_subcategorias_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `bancos_subcategorias_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `ram_bancos_categorias` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_bitacoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_bitacoras` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unidad_id` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `kilometraje` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `proveedor_id` int unsigned NOT NULL,
  `costo` double(15,2) NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `llantas_marca` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `llanta_eje_direccion_der` tinyint(1) NOT NULL,
  `llanta_eje_inter_der` tinyint(1) NOT NULL,
  `llanta_eje_matriz_der` tinyint(1) NOT NULL,
  `llanta_eje_direccion_izq` tinyint(1) NOT NULL,
  `llanta_eje_inter_izq` tinyint(1) NOT NULL,
  `llanta_eje_matriz_izq` tinyint(1) NOT NULL,
  `balata_eje_direccion_der` tinyint(1) NOT NULL,
  `balata_eje_inter_der` tinyint(1) NOT NULL,
  `balata_eje_matriz_der` tinyint(1) NOT NULL,
  `balata_eje_direccion_izq` tinyint(1) NOT NULL,
  `balata_eje_inter_izq` tinyint(1) NOT NULL,
  `balata_eje_matriz_izq` tinyint(1) NOT NULL,
  `filtro_aire` tinyint(1) NOT NULL,
  `filtro_aceite` tinyint(1) NOT NULL,
  `filtro_diesel` tinyint(1) NOT NULL,
  `filtro_agua` tinyint(1) NOT NULL,
  `filtro_aceite_hidraulico` tinyint(1) NOT NULL,
  `filtro_diesel_separador_agua` tinyint(1) NOT NULL,
  `filtro_diesel_separador_condimentos` tinyint(1) NOT NULL,
  `filtro_adblue` tinyint(1) NOT NULL,
  `filtro_secador_aire` tinyint NOT NULL DEFAULT '0',
  `aceite_motor` tinyint(1) NOT NULL,
  `aceite_caja_diferencial` tinyint(1) NOT NULL,
  `aceite_caja` tinyint(1) NOT NULL,
  `aceite_hidraulico` tinyint(1) NOT NULL,
  `aceite_liquido_frenos` tinyint(1) NOT NULL,
  `anticongelante` tinyint(1) NOT NULL,
  `reparacion_especial` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1076 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_cat_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_cat_proveedores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `proveedor` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre_contacto` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=797 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_clientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cliente` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre_contacto` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gasto_admon` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_codigos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_codigos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `idEstado` smallint NOT NULL,
  `estado` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `idMunicipio` smallint NOT NULL,
  `municipio` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ciudad` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `zona` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cp` mediumint NOT NULL,
  `asentamiento` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145971 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_combustibles`;
/*!50001 DROP VIEW IF EXISTS `ram_combustibles`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_combustibles` AS SELECT
 1 AS `costo`,
 1 AS `combustible`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_comprobantes_combustible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_comprobantes_combustible` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `combustible_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `comprobantes_combustible_user_id_foreign` (`user_id`),
  CONSTRAINT `comprobantes_combustible_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=758 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_comprobantes_gastos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_comprobantes_gastos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total` double(15,2) unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `comprobantes_gastos_user_id_foreign` (`user_id`),
  CONSTRAINT `comprobantes_gastos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7334 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_controles_vehiculares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_controles_vehiculares` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `factura_id` int DEFAULT NULL,
  `porcentaje` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `control_vehicular` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `origen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `toneladas` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tarifa` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cantidad` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `iva` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pagado` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `controles_vehiculares_user_id_foreign` (`user_id`),
  CONSTRAINT `controles_vehiculares_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=68478 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_costos_combustibles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_costos_combustibles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `combustible` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `costo` double(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_cotizaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `folio` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cliente_id` int unsigned NOT NULL,
  `ruta_id` int unsigned NOT NULL,
  `tipo_de_unidad_id` int unsigned NOT NULL,
  `rendimiento_id` int unsigned NOT NULL,
  `tot_km` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `costo_combustible` double(15,2) unsigned NOT NULL,
  `propuesta` double(15,2) unsigned NOT NULL,
  `utilidad` double(15,2) unsigned NOT NULL,
  `sueldo_ope` double(15,2) unsigned NOT NULL,
  `gastos_admon` double(15,2) unsigned NOT NULL,
  `otros_gastos` double(15,2) unsigned NOT NULL,
  `combustible` double(15,2) unsigned NOT NULL,
  `caseta` double(15,2) unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `cotizaciones_cliente_id_foreign` (`cliente_id`),
  KEY `cotizaciones_ruta_id_foreign` (`ruta_id`),
  KEY `cotizaciones_tipo_de_unidad_id_foreign` (`tipo_de_unidad_id`),
  KEY `cotizaciones_rendimiento_id_foreign` (`rendimiento_id`),
  CONSTRAINT `cotizaciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `ram_clientes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cotizaciones_rendimiento_id_foreign` FOREIGN KEY (`rendimiento_id`) REFERENCES `ram_rendimientos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cotizaciones_ruta_id_foreign` FOREIGN KEY (`ruta_id`) REFERENCES `ram_nombres_rutas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cotizaciones_tipo_de_unidad_id_foreign` FOREIGN KEY (`tipo_de_unidad_id`) REFERENCES `ram_tipos_de_unidades` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2190 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_cotizaciones_list`;
/*!50001 DROP VIEW IF EXISTS `ram_cotizaciones_list`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_cotizaciones_list` AS SELECT
 1 AS `id`,
 1 AS `nombre`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_detalles_rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_detalles_rutas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre_id` int unsigned NOT NULL,
  `estado` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `origen` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `estado_destino` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `destino` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `km` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `detalles_rutas_nombre_id_foreign` (`nombre_id`),
  CONSTRAINT `detalles_rutas_nombre_id_foreign` FOREIGN KEY (`nombre_id`) REFERENCES `ram_nombres_rutas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1533 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_diesel_autorizado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_diesel_autorizado` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_de_unidad_id` int NOT NULL,
  `origen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `destino` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `lts_aut` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_estados`;
/*!50001 DROP VIEW IF EXISTS `ram_estados`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_estados` AS SELECT
 1 AS `idEstado`,
 1 AS `estado`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_facturas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int unsigned NOT NULL,
  `subtotal` double(15,2) unsigned NOT NULL,
  `maniobras` double(15,2) unsigned NOT NULL,
  `otros` double(15,2) unsigned NOT NULL,
  `iva` double(15,2) unsigned NOT NULL,
  `retencion` double(15,2) unsigned NOT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total` double(15,2) unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pagada` tinyint(1) NOT NULL,
  `fecha_pago` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `facturas_cotizacion_id_foreign` (`cotizacion_id`),
  CONSTRAINT `facturas_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `ram_cotizaciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1557 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_facturas_controles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_facturas_controles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `control_id` int unsigned NOT NULL,
  `cliente_id` int unsigned NOT NULL,
  `subtotal` double(15,2) unsigned NOT NULL,
  `maniobras` double(15,2) unsigned NOT NULL,
  `otros` double(15,2) unsigned NOT NULL,
  `iva` double(15,2) unsigned NOT NULL,
  `retencion` double(15,2) unsigned NOT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total` double(15,2) unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pagada` tinyint(1) NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47391 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_facturas_cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_facturas_cotizaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int unsigned NOT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `factura_num` double(15,2) unsigned NOT NULL,
  `fecha_pago` date NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `facturas_cotizaciones_cotizacion_id_foreign` (`cotizacion_id`),
  CONSTRAINT `facturas_cotizaciones_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `ram_cotizaciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_facturas_unidades`;
/*!50001 DROP VIEW IF EXISTS `ram_facturas_unidades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_facturas_unidades` AS SELECT
 1 AS `factura`,
 1 AS `total`,
 1 AS `pagada`,
 1 AS `fecha_pago`,
 1 AS `cotizacion_id`,
 1 AS `id`,
 1 AS `unidad_id`,
 1 AS `unidad`,
 1 AS `placas`,
 1 AS `ticket`,
 1 AS `total_ticket`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_facturas_unidades_inicial`;
/*!50001 DROP VIEW IF EXISTS `ram_facturas_unidades_inicial`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_facturas_unidades_inicial` AS SELECT
 1 AS `factura`,
 1 AS `total`,
 1 AS `pagada`,
 1 AS `fecha_pago`,
 1 AS `id`,
 1 AS `unidad_id`,
 1 AS `unidad`,
 1 AS `placas`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_foraneo_operador_view`;
/*!50001 DROP VIEW IF EXISTS `ram_foraneo_operador_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_foraneo_operador_view` AS SELECT
 1 AS `foraneo_operador`,
 1 AS `saldo`,
 1 AS `created_at`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_foraneo_view`;
/*!50001 DROP VIEW IF EXISTS `ram_foraneo_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_foraneo_view` AS SELECT
 1 AS `id`,
 1 AS `foraneo_operador_id`,
 1 AS `unidad_id`,
 1 AS `fecha`,
 1 AS `concepto`,
 1 AS `tipo`,
 1 AS `monto`,
 1 AS `tp`,
 1 AS `saldo`,
 1 AS `deleted_at`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_foraneos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_foraneos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `foraneo_operador_id` int unsigned NOT NULL,
  `unidad_id` int unsigned NOT NULL,
  `fecha` date NOT NULL,
  `concepto` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `monto` double(15,2) NOT NULL,
  `tp` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `saldo` double(15,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `foraneos_foraneo_operador_id_foreign` (`foraneo_operador_id`),
  CONSTRAINT `foraneos_foraneo_operador_id_foreign` FOREIGN KEY (`foraneo_operador_id`) REFERENCES `ram_foraneos_operadores` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_foraneos_operadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_foraneos_operadores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `foraneo_operador` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_gasolineras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_gasolineras` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `gasolinera` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `estacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `contacto` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_gastos_unidades`;
/*!50001 DROP VIEW IF EXISTS `ram_gastos_unidades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_gastos_unidades` AS SELECT
 1 AS `fecha`,
 1 AS `descripcion`,
 1 AS `total`,
 1 AS `unidad_id`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_inventariosmateriales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_inventariosmateriales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `precio` double(15,2) NOT NULL,
  `existencia` int unsigned NOT NULL,
  `valor` double(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_inventariosmaterialesentradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_inventariosmaterialesentradas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventariomaterial_id` int unsigned NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `precio` double(15,2) NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_inventariosmaterialessalidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_inventariosmaterialessalidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventariomaterial_id` int unsigned NOT NULL,
  `unidad_id` int unsigned NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_llantas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_llantas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `marca` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `medida` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `existencia` int unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_llantasentradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_llantasentradas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `llanta_id` int unsigned NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `precio` double(15,2) NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_llantassalidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_llantassalidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `llanta_id` int unsigned NOT NULL,
  `unidad_id` int unsigned NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_mantenimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_mantenimientos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `plazo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cantidad` double(15,2) NOT NULL,
  `descuento` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `unidad_id` int unsigned NOT NULL,
  `status` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `proveedor_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `mantenimientos_unidad_id_foreign` (`unidad_id`),
  CONSTRAINT `mantenimientos_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `ram_unidades` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11633 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_migrations` (
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_municipios`;
/*!50001 DROP VIEW IF EXISTS `ram_municipios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_municipios` AS SELECT
 1 AS `idEstado`,
 1 AS `estado`,
 1 AS `municipio`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_nombres_rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_nombres_rutas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_km` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=853 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_operadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_operadores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `nss` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `contacto` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tel_contacto` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `licencia` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `vigencia` date NOT NULL,
  `medica` date NOT NULL,
  `unidad_id` int unsigned NOT NULL,
  `cliente_id` int unsigned NOT NULL,
  `origen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `destino` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `estatus` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `operadores_user_id_foreign` (`user_id`),
  CONSTRAINT `operadores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_operadores_list`;
/*!50001 DROP VIEW IF EXISTS `ram_operadores_list`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_operadores_list` AS SELECT
 1 AS `id`,
 1 AS `nombre`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_origenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_origenes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `origen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_value_unique` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_porpagar_facturado`;
/*!50001 DROP VIEW IF EXISTS `ram_porpagar_facturado`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_porpagar_facturado` AS SELECT
 1 AS `cliente`,
 1 AS `factura`,
 1 AS `total`,
 1 AS `Facturado`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_proveedores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `comprobante_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `valor_factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `banco_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `categoria_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subcategoria_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25616 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_rendimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_rendimientos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tipo_de_unidad_id` int unsigned NOT NULL,
  `rendimiento` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `rendimientos_tipo_de_unidad_id_foreign` (`tipo_de_unidad_id`),
  CONSTRAINT `rendimientos_tipo_de_unidad_id_foreign` FOREIGN KEY (`tipo_de_unidad_id`) REFERENCES `ram_tipos_de_unidades` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_reporte_unidades`;
/*!50001 DROP VIEW IF EXISTS `ram_reporte_unidades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_reporte_unidades` AS SELECT
 1 AS `fecha`,
 1 AS `user_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_reporte_unidades_view`;
/*!50001 DROP VIEW IF EXISTS `ram_reporte_unidades_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_reporte_unidades_view` AS SELECT
 1 AS `fecha`,
 1 AS `user_id`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_sueldos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_sueldos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `operador_id` int unsigned NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ram_sueldos_ram_users_FK` (`operador_id`),
  CONSTRAINT `ram_sueldos_ram_users_FK` FOREIGN KEY (`operador_id`) REFERENCES `ram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4737 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_throttle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_throttle` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4524 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_tipos_de_unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_tipos_de_unidades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tipo_de_unidad` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `porcentaje` int NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_unidades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unidad` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_de_unidad_id` int unsigned NOT NULL,
  `placas` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serie` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `poliza` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `aseguradora` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `vigencia` date NOT NULL,
  `km_inicial` double(15,2) unsigned NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `unidades_tipo_de_unidad_id_foreign` (`tipo_de_unidad_id`),
  CONSTRAINT `unidades_tipo_de_unidad_id_foreign` FOREIGN KEY (`tipo_de_unidad_id`) REFERENCES `ram_tipos_de_unidades` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_unidades_list`;
/*!50001 DROP VIEW IF EXISTS `ram_unidades_list`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_unidades_list` AS SELECT
 1 AS `id`,
 1 AS `unidad`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ram_users_groups` (
  `user_id` int unsigned NOT NULL,
  `group_id` int unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ram_vista_comprobantes_combustible`;
/*!50001 DROP VIEW IF EXISTS `ram_vista_comprobantes_combustible`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_vista_comprobantes_combustible` AS SELECT
 1 AS `id`,
 1 AS `user_id`,
 1 AS `unidad_id`,
 1 AS `fecha`,
 1 AS `gasolinera_id`,
 1 AS `ticket`,
 1 AS `litros`,
 1 AS `precio`,
 1 AS `total`,
 1 AS `kilometraje`,
 1 AS `rendimiento`,
 1 AS `foto_ticket`,
 1 AS `foto_tablero_antes`,
 1 AS `foto_tablero_despues`,
 1 AS `foto_tablero_km`,
 1 AS `factura_proveedor`,
 1 AS `fecha_limite_pago`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `ram_vista_comprobantes_proveedores`;
/*!50001 DROP VIEW IF EXISTS `ram_vista_comprobantes_proveedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `ram_vista_comprobantes_proveedores` AS SELECT
 1 AS `id`,
 1 AS `user_id`,
 1 AS `unidad_id`,
 1 AS `fecha`,
 1 AS `gasolinera_id`,
 1 AS `ticket`,
 1 AS `litros`,
 1 AS `precio`,
 1 AS `total`,
 1 AS `kilometraje`,
 1 AS `rendimiento`,
 1 AS `foto_ticket`,
 1 AS `foto_tablero_antes`,
 1 AS `foto_tablero_despues`,
 1 AS `foto_tablero_km`,
 1 AS `factura_proveedor`,
 1 AS `fecha_limite_pago`*/;
SET character_set_client = @saved_cs_client;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_detalle` AS select concat('m-',`ram_bancos_movimientos`.`id`) AS `id`,`ram_bancos_movimientos`.`bancos_id` AS `bancos_id`,substring_index(`ram_bancos_movimientos`.`periodo`,'-',1) AS `mes`,substring_index(substring_index(`ram_bancos_movimientos`.`periodo`,'-',2),'-',-(1)) AS `anno`,`ram_bancos_movimientos`.`periodo` AS `periodo`,`ram_bancos_movimientos`.`movimiento` AS `movimiento`,(`ram_bancos_movimientos`.`tipo` * `ram_bancos_movimientos`.`cantidad`) AS `total`,`ram_bancos_movimientos`.`observaciones` AS `observaciones`,`ram_bancos_movimientos`.`fecha` AS `fecha`,`ram_bancos_movimientos`.`folio` AS `folio`,`ram_bancos_movimientos`.`categoria_id` AS `categoria_id`,`ram_bancos_movimientos`.`subcategoria_id` AS `subcategoria_id` from `ram_bancos_movimientos` union all select concat('p-',`ram_bancos_prestamos`.`id`) AS `id`,`ram_bancos_prestamos`.`bancos_id` AS `bancos_id`,substring_index(`ram_bancos_prestamos`.`periodo`,'-',1) AS `mes`,substring_index(substring_index(`ram_bancos_prestamos`.`periodo`,'-',2),'-',-(1)) AS `anno`,`ram_bancos_prestamos`.`periodo` AS `periodo`,`ram_bancos_prestamos`.`movimiento` AS `movimiento`,(`ram_bancos_prestamos`.`tipo` * `ram_bancos_prestamos`.`cantidad`) AS `total`,concat('Prestamo: ',`ru`.`first_name`,' ',`ru`.`last_name`,' ',`ram_bancos_prestamos`.`observaciones`) AS `observaciones`,`ram_bancos_prestamos`.`fecha` AS `fecha`,`ram_bancos_prestamos`.`folio` AS `folio`,`ram_bancos_prestamos`.`categoria_id` AS `categoria_id`,`ram_bancos_prestamos`.`subcategoria_id` AS `subcategoria_id` from (`ram_bancos_prestamos` left join `ram_users` `ru` on((`ram_bancos_prestamos`.`user_id` = `ru`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_list` AS select `ram_bancos`.`id` AS `id`,concat(`ram_bancos`.`banco`,' | ',`ram_bancos`.`no_cuenta`) AS `banco` from `ram_bancos` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_movimientos_sum`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_movimientos_sum` AS select `ram_bancos_movimientos_sum_det`.`periodo` AS `periodo`,`ram_bancos_movimientos_sum_det`.`bancos_id` AS `bancos_id`,sum(`ram_bancos_movimientos_sum_det`.`total`) AS `total` from `ram_bancos_movimientos_sum_det` group by `ram_bancos_movimientos_sum_det`.`periodo`,`ram_bancos_movimientos_sum_det`.`bancos_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_movimientos_sum_det`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_movimientos_sum_det` AS select `ram_bancos_movimientos`.`periodo` AS `periodo`,`ram_bancos_movimientos`.`bancos_id` AS `bancos_id`,sum((`ram_bancos_movimientos`.`cantidad` * `ram_bancos_movimientos`.`tipo`)) AS `total` from `ram_bancos_movimientos` group by `ram_bancos_movimientos`.`bancos_id`,`ram_bancos_movimientos`.`periodo` union all select `ram_bancos_prestamos`.`periodo` AS `periodo`,`ram_bancos_prestamos`.`bancos_id` AS `bancos_id`,sum((`ram_bancos_prestamos`.`cantidad` * `ram_bancos_prestamos`.`tipo`)) AS `total` from `ram_bancos_prestamos` group by `ram_bancos_prestamos`.`bancos_id`,`ram_bancos_prestamos`.`periodo` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_presupuesto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_presupuesto` AS select `ram_bancos_detalle`.`bancos_id` AS `bancos_id`,substring_index(`ram_bancos_detalle`.`periodo`,'-',1) AS `mes`,substring_index(substring_index(`ram_bancos_detalle`.`periodo`,'-',2),'-',-(1)) AS `anno`,`ram_bancos_detalle`.`periodo` AS `periodo`,`ram_bancos_detalle`.`categoria_id` AS `categoria_id`,sum(`ram_bancos_detalle`.`total`) AS `total` from `ram_bancos_detalle` group by `ram_bancos_detalle`.`bancos_id`,`ram_bancos_detalle`.`periodo`,`ram_bancos_detalle`.`categoria_id` order by (`ram_bancos_detalle`.`categoria_id` = 4) desc,`ram_bancos_detalle`.`categoria_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_presupuesto_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_presupuesto_detalle` AS select `ram_bancos_detalle`.`bancos_id` AS `bancos_id`,`ram_bancos_detalle`.`periodo` AS `periodo`,`ram_bancos_detalle`.`categoria_id` AS `categoria_id`,`ram_bancos_detalle`.`subcategoria_id` AS `subcategoria_id`,sum(`ram_bancos_detalle`.`total`) AS `total` from `ram_bancos_detalle` group by `ram_bancos_detalle`.`bancos_id`,`ram_bancos_detalle`.`periodo`,`ram_bancos_detalle`.`categoria_id`,`ram_bancos_detalle`.`subcategoria_id` order by (`ram_bancos_detalle`.`categoria_id` = 4) desc,`ram_bancos_detalle`.`categoria_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_bancos_reporte_presupuesto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_bancos_reporte_presupuesto` AS select `ram_bancos_saldos`.`bancos_id` AS `bancos_id`,`ram_bancos_saldos`.`periodo` AS `periodo`,right(`ram_bancos_saldos`.`periodo`,4) AS `year`,substring_index(`ram_bancos_saldos`.`periodo`,'-',1) AS `mes`,0 AS `categoria_id`,0 AS `subcategoria_id`,`ram_bancos_saldos`.`saldo_inicial` AS `total` from `ram_bancos_saldos` union all select `ram_bancos_detalle`.`bancos_id` AS `bancos_id`,`ram_bancos_detalle`.`periodo` AS `periodo`,right(`ram_bancos_detalle`.`periodo`,4) AS `year`,substring_index(`ram_bancos_detalle`.`periodo`,'-',1) AS `mes`,`ram_bancos_detalle`.`categoria_id` AS `categoria_id`,`ram_bancos_detalle`.`subcategoria_id` AS `subcategoria_id`,`ram_bancos_detalle`.`total` AS `total` from `ram_bancos_detalle` union all select `ram_bancos_saldos`.`bancos_id` AS `bancos_id`,`ram_bancos_saldos`.`periodo` AS `periodo`,right(`ram_bancos_saldos`.`periodo`,4) AS `year`,substring_index(`ram_bancos_saldos`.`periodo`,'-',1) AS `mes`,0 AS `categoria_id`,1 AS `subcategoria_id`,(`ram_bancos_saldos`.`saldo_final` * -(1)) AS `total` from `ram_bancos_saldos` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_combustibles`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_combustibles` AS select `ram_costos_combustibles`.`costo` AS `costo`,concat(`ram_costos_combustibles`.`combustible`,' | $',`ram_costos_combustibles`.`costo`,' x lt.') AS `combustible` from `ram_costos_combustibles` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_cotizaciones_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_cotizaciones_list` AS select `a`.`id` AS `id`,concat(`a`.`folio`,' | ',`b`.`cliente`,' | ',`c`.`nombre`) AS `nombre` from ((`ram_cotizaciones` `a` left join `ram_clientes` `b` on((`a`.`cliente_id` = `b`.`id`))) left join `ram_nombres_rutas` `c` on((`a`.`ruta_id` = `c`.`id`))) where (`a`.`deleted_at` is null) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_estados`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_estados` AS select `ram_codigos`.`idEstado` AS `idEstado`,`ram_codigos`.`estado` AS `estado` from `ram_codigos` group by `ram_codigos`.`idEstado`,`ram_codigos`.`estado` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_facturas_unidades`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_facturas_unidades` AS select `a`.`factura` AS `factura`,`a`.`total` AS `total`,`a`.`pagada` AS `pagada`,`a`.`fecha_pago` AS `fecha_pago`,`a`.`cotizacion_id` AS `cotizacion_id`,`b`.`id` AS `id`,`b`.`unidad_id` AS `unidad_id`,`d`.`unidad` AS `unidad`,`d`.`placas` AS `placas`,`c`.`ticket` AS `ticket`,`c`.`total` AS `total_ticket` from (((`ram_facturas` `a` left join `ram_asignaciones` `b` on((`a`.`cotizacion_id` = `b`.`cotizacion_id`))) left join `ram_asignaciones_combustibles` `c` on((`b`.`id` = `c`.`asignacion_id`))) left join `ram_unidades` `d` on((`b`.`unidad_id` = `d`.`id`))) where ((`a`.`factura` <> 'AJUSTE MANUAL') and (`a`.`pagada` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_facturas_unidades_inicial`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_facturas_unidades_inicial` AS select `a`.`factura` AS `factura`,`a`.`total` AS `total`,`a`.`pagada` AS `pagada`,`a`.`fecha_pago` AS `fecha_pago`,`b`.`id` AS `id`,`b`.`unidad_id` AS `unidad_id`,`d`.`unidad` AS `unidad`,`d`.`placas` AS `placas` from ((`ram_facturas` `a` left join `ram_asignaciones` `b` on((`a`.`cotizacion_id` = `b`.`cotizacion_id`))) left join `ram_unidades` `d` on((`b`.`unidad_id` = `d`.`id`))) where ((`a`.`factura` <> 'AJUSTE MANUAL') and (`a`.`pagada` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_foraneo_operador_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_foraneo_operador_view` AS select `fo`.`foraneo_operador` AS `foraneo_operador`,`rf`.`saldo` AS `saldo`,`rf`.`created_at` AS `created_at` from (`ram_foraneo_view` `rf` left join `ram_foraneos_operadores` `fo` on((`rf`.`foraneo_operador_id` = `fo`.`id`))) where ((`fo`.`deleted_at` is null) and `rf`.`id` in (select max(`rf`.`id`) from `ram_foraneos` group by `ram_foraneos`.`foraneo_operador_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_foraneo_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_foraneo_view` AS select `rf`.`id` AS `id`,`rf`.`foraneo_operador_id` AS `foraneo_operador_id`,`rf`.`unidad_id` AS `unidad_id`,`rf`.`fecha` AS `fecha`,`rf`.`concepto` AS `concepto`,`rf`.`tipo` AS `tipo`,`rf`.`monto` AS `monto`,`rf`.`tp` AS `tp`,`rf`.`saldo` AS `saldo`,`rf`.`deleted_at` AS `deleted_at`,`rf`.`created_at` AS `created_at`,`rf`.`updated_at` AS `updated_at` from `ram_foraneos` `rf` where `rf`.`id` in (select max(`ram_foraneos`.`id`) from `ram_foraneos` group by `ram_foraneos`.`foraneo_operador_id`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_gastos_unidades`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_gastos_unidades` AS select `a`.`fecha` AS `fecha`,`a`.`descripcion` AS `descripcion`,`a`.`total` AS `total`,`b`.`unidad_id` AS `unidad_id` from (`ram_comprobantes_gastos` `a` left join `ram_operadores` `b` on((`a`.`user_id` = `b`.`user_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_municipios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_municipios` AS select `ram_codigos`.`idEstado` AS `idEstado`,`ram_codigos`.`estado` AS `estado`,`ram_codigos`.`municipio` AS `municipio` from `ram_codigos` group by `ram_codigos`.`idEstado`,`ram_codigos`.`estado`,`ram_codigos`.`municipio` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_operadores_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_operadores_list` AS select `ram_users`.`id` AS `id`,concat(`ram_users`.`first_name`,' ',`ram_users`.`last_name`) AS `nombre` from `ram_users` where `ram_users`.`id` in (select `ram_operadores`.`user_id` from `ram_operadores` where (`ram_operadores`.`deleted_at` is null)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_porpagar_facturado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_porpagar_facturado` AS select `rc2`.`cliente` AS `cliente`,`rf`.`factura` AS `factura`,sum(`rf`.`total`) AS `total`,'Facturado' AS `Facturado` from ((`ram_facturas` `rf` left join `ram_cotizaciones` `rc` on((`rf`.`cotizacion_id` = `rc`.`id`))) left join `ram_clientes` `rc2` on((`rc`.`cliente_id` = `rc2`.`id`))) where (`rf`.`pagada` = 0) group by `rc2`.`cliente`,`rf`.`factura` union all select `rc2`.`cliente` AS `cliente`,`rfc`.`factura` AS `factura`,sum(`rfc`.`total`) AS `total`,'Facturado' AS `Facturado` from (`ram_facturas_controles` `rfc` left join `ram_clientes` `rc2` on((`rfc`.`cliente_id` = `rc2`.`id`))) where (`rfc`.`pagada` = 0) group by `rc2`.`cliente`,`rfc`.`factura` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_reporte_unidades`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_reporte_unidades` AS select `a`.`created_at` AS `fecha`,`a`.`user_id` AS `user_id`,sum(`b`.`propuesta`) AS `total` from (`ram_asignaciones` `a` left join `ram_cotizaciones` `b` on((`a`.`cotizacion_id` = `b`.`id`))) group by `a`.`created_at`,`a`.`user_id` union select `ram_controles_vehiculares`.`fecha` AS `fecha`,`ram_controles_vehiculares`.`user_id` AS `user_id`,sum(`ram_controles_vehiculares`.`cantidad`) AS `total` from `ram_controles_vehiculares` group by `ram_controles_vehiculares`.`fecha`,`ram_controles_vehiculares`.`user_id` union select `ram_comprobantes_gastos`.`fecha` AS `fecha`,`ram_comprobantes_gastos`.`user_id` AS `user_id`,(sum(`ram_comprobantes_gastos`.`total`) * -(1)) AS `total` from `ram_comprobantes_gastos` where (not((`ram_comprobantes_gastos`.`descripcion` like '%maniob%'))) group by `ram_comprobantes_gastos`.`fecha`,`ram_comprobantes_gastos`.`user_id` union select `ram_vista_comprobantes_combustible`.`fecha` AS `fecha`,`ram_vista_comprobantes_combustible`.`user_id` AS `user_id`,(sum(`ram_vista_comprobantes_combustible`.`total`) * -(1)) AS `total` from `ram_vista_comprobantes_combustible` group by `ram_vista_comprobantes_combustible`.`fecha`,`ram_vista_comprobantes_combustible`.`user_id` union select `a`.`created_at` AS `fecha`,`a`.`user_id` AS `user_id`,(sum(`b`.`sueldo_ope`) * -(1)) AS `total` from (`ram_asignaciones` `a` left join `ram_cotizaciones` `b` on((`a`.`cotizacion_id` = `b`.`id`))) group by `a`.`created_at`,`a`.`user_id` union select `ram_controles_vehiculares`.`fecha` AS `fecha`,`ram_controles_vehiculares`.`user_id` AS `user_id`,(sum(((`ram_controles_vehiculares`.`cantidad` * `ram_controles_vehiculares`.`porcentaje`) / 100)) * -(1)) AS `total` from `ram_controles_vehiculares` group by `ram_controles_vehiculares`.`fecha`,`ram_controles_vehiculares`.`user_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_reporte_unidades_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_reporte_unidades_view` AS select date_format(`ram_reporte_unidades`.`fecha`,'%Y-%m') AS `fecha`,`ram_reporte_unidades`.`user_id` AS `user_id`,sum(`ram_reporte_unidades`.`total`) AS `total` from `ram_reporte_unidades` group by date_format(`ram_reporte_unidades`.`fecha`,'%Y-%m'),`ram_reporte_unidades`.`user_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_unidades_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_unidades_list` AS select `ram_unidades`.`id` AS `id`,concat(`ram_unidades`.`unidad`,' | ',`ram_unidades`.`placas`) AS `unidad` from `ram_unidades` where (`ram_unidades`.`deleted_at` is null) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_vista_comprobantes_combustible`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_vista_comprobantes_combustible` AS select concat('a-',`a`.`id`) AS `id`,`b`.`user_id` AS `user_id`,`b`.`unidad_id` AS `unidad_id`,`a`.`fecha` AS `fecha`,`a`.`gasolinera_id` AS `gasolinera_id`,`a`.`ticket` AS `ticket`,`a`.`litros` AS `litros`,`a`.`precio` AS `precio`,`a`.`total` AS `total`,`a`.`kilometraje` AS `kilometraje`,`a`.`rendimiento` AS `rendimiento`,`a`.`foto_ticket` AS `foto_ticket`,`a`.`foto_tablero_antes` AS `foto_tablero_antes`,`a`.`foto_tablero_despues` AS `foto_tablero_despues`,`a`.`foto_tablero_km` AS `foto_tablero_km`,`a`.`factura_proveedor` AS `factura_proveedor`,`a`.`fecha_limite_pago` AS `fecha_limite_pago` from (`ram_asignaciones_combustibles` `a` left join `ram_asignaciones` `b` on((`a`.`asignacion_id` = `b`.`id`))) union all select concat('e-',`ram_asignaciones_especiales`.`id`) AS `id`,`ram_asignaciones_especiales`.`user_id` AS `user_id`,`ram_asignaciones_especiales`.`unidad_id` AS `unidad_id`,`ram_asignaciones_especiales`.`fecha` AS `fecha`,`ram_asignaciones_especiales`.`gasolinera_id` AS `gasolinera_id`,`ram_asignaciones_especiales`.`ticket` AS `ticket`,`ram_asignaciones_especiales`.`litros` AS `litros`,`ram_asignaciones_especiales`.`precio` AS `precio`,`ram_asignaciones_especiales`.`total` AS `total`,`ram_asignaciones_especiales`.`kilometraje` AS `kilometraje`,`ram_asignaciones_especiales`.`rendimiento` AS `rendimiento`,`ram_asignaciones_especiales`.`foto_ticket` AS `foto_ticket`,`ram_asignaciones_especiales`.`foto_tablero_antes` AS `foto_tablero_antes`,`ram_asignaciones_especiales`.`foto_tablero_despues` AS `foto_tablero_despues`,`ram_asignaciones_especiales`.`foto_tablero_km` AS `foto_tablero_km`,`ram_asignaciones_especiales`.`factura_proveedor` AS `factura_proveedor`,`ram_asignaciones_especiales`.`fecha_limite_pago` AS `fecha_limite_pago` from `ram_asignaciones_especiales` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `ram_vista_comprobantes_proveedores`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `ram_vista_comprobantes_proveedores` AS select `ram_vista_comprobantes_combustible`.`id` AS `id`,`ram_vista_comprobantes_combustible`.`user_id` AS `user_id`,`ram_vista_comprobantes_combustible`.`unidad_id` AS `unidad_id`,`ram_vista_comprobantes_combustible`.`fecha` AS `fecha`,`ram_vista_comprobantes_combustible`.`gasolinera_id` AS `gasolinera_id`,`ram_vista_comprobantes_combustible`.`ticket` AS `ticket`,`ram_vista_comprobantes_combustible`.`litros` AS `litros`,`ram_vista_comprobantes_combustible`.`precio` AS `precio`,`ram_vista_comprobantes_combustible`.`total` AS `total`,`ram_vista_comprobantes_combustible`.`kilometraje` AS `kilometraje`,`ram_vista_comprobantes_combustible`.`rendimiento` AS `rendimiento`,`ram_vista_comprobantes_combustible`.`foto_ticket` AS `foto_ticket`,`ram_vista_comprobantes_combustible`.`foto_tablero_antes` AS `foto_tablero_antes`,`ram_vista_comprobantes_combustible`.`foto_tablero_despues` AS `foto_tablero_despues`,`ram_vista_comprobantes_combustible`.`foto_tablero_km` AS `foto_tablero_km`,`ram_vista_comprobantes_combustible`.`factura_proveedor` AS `factura_proveedor`,`ram_vista_comprobantes_combustible`.`fecha_limite_pago` AS `fecha_limite_pago` from `ram_vista_comprobantes_combustible` where `ram_vista_comprobantes_combustible`.`id` in (select `ram_proveedores`.`comprobante_id` from `ram_proveedores`) is false */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
