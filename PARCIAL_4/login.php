<?php
// la clase de GoogleOAuth para manejar la autenticación de Google
require_once 'GoogleOAuth.php';

// Se obtiene la URL de autenticación desde la clase GoogleOAuth
$urlDeAutenticacion = GoogleOAuth::obtenerUrlDeAutenticacion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login con Google</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <!-- Enlace para iniciar sesión con Google, redirige a la URL de autenticación de Google -->
        <a href="<?php echo $urlDeAutenticacion; ?>">Iniciar sesión con Google</a>
    </div>
</body>
</html>
