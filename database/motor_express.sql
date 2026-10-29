CREATE DATABASE IF NOT EXISTS `motor_express` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `motor_express`;

CREATE TABLE IF NOT EXISTS `usuario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(70) NOT NULL,
    `email` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `creado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cliente` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(70) NOT NULL,
    `telefono` VARCHAR(15),
    `email` VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vehiculo` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cliente_id` INT NOT NULL,
    `placa` VARCHAR(10) NOT NULL UNIQUE,
    `marca` VARCHAR(50) NOT NULL,
    `modelo` VARCHAR(50) NOT NULL,
    `anio` INT NOT NULL,
    CONSTRAINT `fk_vehiculo_cliente` FOREIGN KEY (`cliente_id`) 
        REFERENCES `cliente`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mecanico` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(70) NOT NULL,
    `especialidad` VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cita` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vehiculo_id` INT NOT NULL,
    `mecanico_id` INT NOT NULL,
    `fecha` DATETIME NOT NULL,
    CONSTRAINT `fk_cita_vehiculo` FOREIGN KEY (`vehiculo_id`) 
        REFERENCES `vehiculo`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_cita_mecanico` FOREIGN KEY (`mecanico_id`) 
        REFERENCES `mecanico`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordenes_trabajo` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cita_id` INT NOT NULL,
    `diagnostico` VARCHAR(500),
    `estado` VARCHAR(20) DEFAULT 'pendiente',
    `fecha` DATE NOT NULL,
    CONSTRAINT `fk_orden_cita` FOREIGN KEY (`cita_id`) 
        REFERENCES `cita`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `servicio` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(70) NOT NULL,
    `precio` DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orden_servicio` (
    `orden_id` INT NOT NULL,
    `servicio_id` INT NOT NULL,
    PRIMARY KEY (`orden_id`, `servicio_id`),
    CONSTRAINT `fk_os_orden` FOREIGN KEY (`orden_id`) 
        REFERENCES `ordenes_trabajo`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_os_servicio` FOREIGN KEY (`servicio_id`) 
        REFERENCES `servicio`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `repuesto` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(70) NOT NULL,
    `precio` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orden_repuesto` (
    `orden_id` INT NOT NULL,
    `repuesto_id` INT NOT NULL,
    `cantidad` INT NOT NULL DEFAULT 1,
    PRIMARY KEY (`orden_id`, `repuesto_id`),
    CONSTRAINT `fk_or_orden` FOREIGN KEY (`orden_id`) 
        REFERENCES `ordenes_trabajo`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_or_repuesto` FOREIGN KEY (`repuesto_id`) 
        REFERENCES `repuesto`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pago` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `orden_id` INT NOT NULL,
    `monto` DECIMAL(10,2) NOT NULL,
    `metodo` ENUM('efectivo','tarjeta_credito','tarjeta_debito','yape') NOT NULL,
    `fecha` DATE NOT NULL,
    CONSTRAINT `fk_pago_orden` FOREIGN KEY (`orden_id`) 
        REFERENCES `ordenes_trabajo`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- DATOS INICIALES DE PRUEBA
-- =====================================================================

-- Usuario inicial (Contraseña: admin12345)
INSERT INTO `usuario` (`id`, `nombre`, `email`, `password`) VALUES
(1, 'Administrador Motor Express', 'admin@motorexpress.com', '$2y$10$wB5W.y6QZJszKjG7Y5oY6u5yR3t7pW8/k5K3L5w4rE1j1nLqK9hC2')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Clientes
INSERT INTO `cliente` (`id`, `nombre`, `telefono`, `email`) VALUES
(1, 'Carlos Mendoza Ramos', '987654321', 'carlos.mendoza@gmail.com'),
(2, 'María Teresa Quispe', '912345678', 'maria.quispe@hotmail.com'),
(3, 'Juan Pablo Delgado', '955443322', 'jpablo.delgado@yahoo.com')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Vehículos
INSERT INTO `vehiculo` (`id`, `cliente_id`, `placa`, `marca`, `modelo`, `anio`) VALUES
(1, 1, 'ABC-123', 'Toyota', 'Corolla', 2020),
(2, 2, 'XYZ-789', 'Hyundai', 'Tucson', 2019),
(3, 3, 'MNO-456', 'Nissan', 'Sentra', 2021)
ON DUPLICATE KEY UPDATE `placa` = VALUES(`placa`);

-- Mecánicos
INSERT INTO `mecanico` (`id`, `nombre`, `especialidad`) VALUES
(1, 'Roberto Flores Castro', 'Alineamiento y Suspensión'),
(2, 'Manuel Benítez Solís', 'Inyección Electrónica y Frenos'),
(3, 'Javier Ramos Vargas', 'Mantenimiento General y Motores')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Servicios
INSERT INTO `servicio` (`id`, `nombre`, `precio`) VALUES
(1, 'Cambio de Aceite y Filtro', 120.00),
(2, 'Afinamiento Electrónico', 250.00),
(3, 'Mantenimiento de Frenos', 180.00),
(4, 'Diagnóstico Computarizado', 90.00),
(5, 'Alineación y Balanceo', 110.00)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Repuestos
INSERT INTO `repuesto` (`id`, `nombre`, `precio`, `stock`) VALUES
(1, 'Aceite Sintético 5W-30 (Galón)', 140.00, 25),
(2, 'Filtro de Aceite Original', 35.00, 40),
(3, 'Pastillas de Freno Delanteras', 120.00, 18),
(4, 'Bujías de Iridio (Juego x4)', 160.00, 15),
(5, 'Filtro de Aire Motor', 45.00, 30)
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`);

-- Citas
INSERT INTO `cita` (`id`, `vehiculo_id`, `mecanico_id`, `fecha`) VALUES
(1, 1, 3, '2026-10-30 09:00:00'),
(2, 2, 2, '2026-10-30 11:30:00'),
(3, 3, 1, '2026-10-31 15:00:00')
ON DUPLICATE KEY UPDATE `fecha` = VALUES(`fecha`);

-- Órdenes de Trabajo
INSERT INTO `ordenes_trabajo` (`id`, `cita_id`, `diagnostico`, `estado`, `fecha`) VALUES
(1, 1, 'Pérdida de potencia leve y requiere cambio regular de fluidos.', 'completada', '2026-10-30'),
(2, 2, 'Ruido metálico al frenar en rueda delantera izquierda.', 'pendiente', '2026-10-30'),
(3, 3, 'Desviación del volante a altas velocidades.', 'pendiente', '2026-10-31')
ON DUPLICATE KEY UPDATE `diagnostico` = VALUES(`diagnostico`);

-- Relación Orden - Servicios
INSERT INTO `orden_servicio` (`orden_id`, `servicio_id`) VALUES
(1, 1),
(1, 4),
(2, 3),
(3, 5)
ON DUPLICATE KEY UPDATE `servicio_id` = VALUES(`servicio_id`);

-- Relación Orden - Repuestos
INSERT INTO `orden_repuesto` (`orden_id`, `repuesto_id`, `cantidad`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 3, 1)
ON DUPLICATE KEY UPDATE `cantidad` = VALUES(`cantidad`);

-- Pagos
INSERT INTO `pago` (`id`, `orden_id`, `monto`, `metodo`, `fecha`) VALUES
(1, 1, 385.00, 'yape', '2026-10-30')
ON DUPLICATE KEY UPDATE `monto` = VALUES(`monto`);
