<?php
session_start();

// verificacion si el usuario ya está autenticado
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php'); // Si ya está autenticado, redirigir al dashboard
    exit;
}

// se procesa formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    // usuarios y contraseñas para autenticación
    $usuarios = [
        'usuario1' => 'password1',
        'usuario2' => 'password2'
    ];

    // se verifican si las credenciales son válidas
    if (isset($usuarios[$usuario]) && $usuarios[$usuario] === $password) {
        // Iniciar sesión si las credenciales son correctas
        $_SESSION['usuario'] = $usuario;
        header('Location: dashboard.php'); // Redirigir al dashboard
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>
