<?php
// callback.php

// Se incluyen los archivos de GoogleOAuth y Usuario, que contienen las funciones necesarias para manejar la autenticación de Google y la gestión de usuarios.
require_once 'GoogleOAuth.php';
require_once 'Usuario.php';

// Se inicia la sesión para poder guardar datos del usuario si la autenticación es exitosa.
session_start();

// Verificamos si en la URL recibimos el parámetro 'code', que es el código de autorización de Google.
if (isset($_GET['code'])) {
    // Guardamos el código recibido en una variable.
    $codigo = $_GET['code'];
    // Usamos la función obtenerToken de GoogleOAuth para obtener un token de acceso usando el código de autorización.
    $tokenData = GoogleOAuth::obtenerToken($codigo);

    // Verificamos si la respuesta contiene un token de acceso.
    if (isset($tokenData['access_token'])) {
        // Si hay un token, lo usamos para obtener los datos del usuario (como ID, nombre y correo) de Google.
        $datosUsuario = GoogleOAuth::obtenerDatosUsuario($tokenData['access_token']);
        $googleId = $datosUsuario['id'];
        $nombre = $datosUsuario['name'];
        $email = $datosUsuario['email'];

        // Creamos una instancia de la clase Usuario para gestionar al usuario en nuestra base de datos.
        $usuario = new Usuario();
        // Verificamos si el usuario ya existe en la base de datos, buscando por su ID de Google.
        $usuarioExistente = $usuario->obtenerUsuarioPorGoogleId($googleId);

        // Si el usuario no existe, lo registramos en la base de datos.
        if (!$usuarioExistente) {
            $usuario->registrarUsuario($nombre, $email, $googleId);
            // Luego de registrarlo, lo buscamos nuevamente para obtener sus datos completos.
            $usuarioExistente = $usuario->obtenerUsuarioPorGoogleId($googleId);
        }

        // Guardamos el ID del usuario en la sesión para que quede registrado como usuario logueado.
        $_SESSION['user_id'] = $usuarioExistente['id'];

        // Redirigimos al usuario a googleBooks.php, la página principal de la aplicación, después de iniciar sesión correctamente.
        header('Location: googleBooks.php');
        exit();
    } else {
        // Si no se obtiene el token de acceso, mostramos un mensaje de error.
        echo "Error al obtener el token.";
    }
} else {
    // Si el código de autorización no se recibe en la URL, mostramos un mensaje de error.
    echo "Código de autorización no recibido.";
}
?>
