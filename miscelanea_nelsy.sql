SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-05:00";

CREATE TABLE `categorias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `proveedores` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `nombre_proveedor` varchar(255) NOT NULL,
    `nit` varchar(20) NULL,
    `direccion` varchar(255) NOT NULL,
    `telefono` varchar(50) NOT NULL,
    `correo_electronico` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `productos` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `nombre_producto` varchar(255) NOT NULL,
    `codigo_barras` VARCHAR(255) NOT NULL,
    `precio_compra` decimal(10, 2) NOT NULL,
    `precio_venta` decimal(10, 2) NOT NULL,
    `precio_mayoreo` decimal(10, 2) NOT NULL,
    `unidad` varchar(50) NOT NULL DEFAULT 'unidades',
    `existencias` int(10) NOT NULL,
    `proveedor_id` int(10) NOT NULL,
    `categoria_id` int(10) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `facturas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) NOT NULL,
  `total` decimal(10, 2) NOT NULL,
  `fecha` date NOT NULL,
  `proveedor_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_facturas_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `usuarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nombres` varchar(50) NOT NULL,
    `apellidos` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `pass` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;


CREATE TABLE `clientes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre_cliente` varchar(255) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `direccion` varchar(255) NULL,
  `telefono` varchar(50) NOT NULL,
  `correo_electronico` varchar(50) NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `deudas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(10) NOT NULL,
  `descripcion` text NOT NULL,
  `monto` decimal(10, 2) NOT NULL,
  `fecha_emision` date NOT NULL DEFAULT CURRENT_DATE(),
  `fecha_vencimiento` date NOT NULL,
  `pagado` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;