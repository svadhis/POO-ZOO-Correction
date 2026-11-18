-- Base de données pour le projet POO-ZOO
CREATE DATABASE IF NOT EXISTS `poo_zoo`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `poo_zoo`;

-- Table des employés
CREATE TABLE IF NOT EXISTS `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `age` INT NOT NULL,
    `gender` ENUM('male', 'female') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des zoos
CREATE TABLE IF NOT EXISTS `zoos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `max_enclosure` INT NOT NULL,
    `employee_id` INT NOT NULL,
    CONSTRAINT `fk_zoos_employee`
        FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des enclos (Enclosure, Aviary, Aquarium)
CREATE TABLE IF NOT EXISTS `enclosures` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `zoo_id` INT NOT NULL,
    `type` ENUM('Enclosure', 'Aviary', 'Aquarium') NOT NULL DEFAULT 'Enclosure',
    `name` VARCHAR(100) NOT NULL,
    `length` INT NOT NULL DEFAULT 6,
    `actual_species` VARCHAR(50) NOT NULL DEFAULT 'none',
    `cleanliness` ENUM('clean', 'moderately clean', 'dirty') NOT NULL DEFAULT 'clean',
    `height` FLOAT NULL,
    `top_cleanliness` ENUM('clean', 'moderately clean', 'dirty') NULL,
    `salinity` FLOAT NULL,
    CONSTRAINT `fk_enclosures_zoo`
        FOREIGN KEY (`zoo_id`) REFERENCES `zoos`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des animaux
CREATE TABLE IF NOT EXISTS `animals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `enclosure_id` INT NOT NULL,
    `species` ENUM('tiger', 'bear', 'fish', 'eagle') NOT NULL,
    `weight` FLOAT NOT NULL,
    `height` FLOAT NOT NULL,
    `age` INT NOT NULL,
    `is_hungry` TINYINT(1) NOT NULL DEFAULT 0,
    `is_sleeping` TINYINT(1) NOT NULL DEFAULT 0,
    `is_sick` TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT `fk_animals_enclosure`
        FOREIGN KEY (`enclosure_id`) REFERENCES `enclosures`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
