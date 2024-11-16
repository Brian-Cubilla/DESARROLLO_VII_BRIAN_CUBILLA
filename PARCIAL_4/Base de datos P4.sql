CREATE DATABASE BIBLIOTECA;

-- Usar la base de datos BIBLIOTECA
USE BIBLIOTECA;

-- Crear la tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    google_id VARCHAR(255) NOT NULL UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE libros_guardados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    google_books_id VARCHAR(255) NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    imagen_portada VARCHAR(500),
    reseña_personal TEXT,
    fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id)
);

