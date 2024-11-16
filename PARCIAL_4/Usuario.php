<?php
// Usuario.php

// Incluir el archivo de configuración donde se establece la conexión con la base de datos
require_once 'config.php';

// Definir la clase Usuario
class Usuario {

    // Funcion para obtener un usuario desde la base de datos usando el Google ID
    public function obtenerUsuarioPorGoogleId($googleId) {
        global $db; // Usar la conexión a la base de datos global
        // Preparar la consulta SQL para seleccionar el usuario por su Google ID
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE google_id = :google_id");
        // Ejecutar la consulta pasando el Google ID como parámetro
        $stmt->execute(['google_id' => $googleId]);
        // Devolver el resultado como un arreglo asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funcion para registrar un nuevo usuario en la base de datos
    public function registrarUsuario($nombre, $email, $googleId) {
        global $db; // Usar la conexión a la base de datos global
        // Preparar la consulta SQL para insertar un nuevo usuario
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, google_id) VALUES (:nombre, :email, :google_id)");
        // Ejecutar la consulta pasando los parámetros del nuevo usuario
        $stmt->execute(['nombre' => $nombre, 'email' => $email, 'google_id' => $googleId]);
    }
}
?>
