CREATE DATABASE chatdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE chatdb;

CREATE TABLE mensajes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      usuario VARCHAR(50) NOT NULL,
      texto TEXT NOT NULL,
      fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE usuarios (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nombre VARCHAR(50) NOT NULL,
      apellido VARCHAR(50) NOT NULL,
      email VARCHAR(100) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para recuperación de contraseña
CREATE TABLE password_resets (
     id INT AUTO_INCREMENT PRIMARY KEY,
     email VARCHAR(100) NOT NULL,
     token VARCHAR(255) NOT NULL,
     expires_at DATETIME NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
