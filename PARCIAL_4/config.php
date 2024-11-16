<?php
// Configuración para el inicio de sesión con Google OAuth.
// Estos valores son proporcionados por la consola de desarrolladores de Google.
// CLIENT_ID y CLIENT_SECRET son exclusivos de la aplicación y deben mantenerse privados.
define('GOOGLE_CLIENT_ID', '584587269411-jjccq67c8v1bdlgpl9k4a99q8bu9fi6g.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
// GOOGLE_REDIRECT_URI es la URL de redirección a la que Google enviará la respuesta con el código de autorización.
// Este debe coincidir exactamente con el configurado en la consola de Google.
define('GOOGLE_REDIRECT_URI', 'http://localhost/PARCIALES/PARCIAL_4/callback.php');

// Configuración de la base de datos para la aplicación.
// Se define el servidor de base de datos, el nombre de usuario, la contraseña y el nombre de la base.
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'BIBLIOTECA');

// Se intenta establecer una conexión con la base de datos utilizando PDO.
try {
    // Se crea una instancia de PDO con los datos de conexión.
    $db = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Se configura PDO para que muestre excepciones en caso de errores, útil para depuración.
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Si ocurre un error al conectar, se termina el script y se muestra el mensaje de error.
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}
?>
