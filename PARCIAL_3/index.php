<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuarios = include 'usuarios.php';
    $username = trim($_POST['username'] ?? ''); 
    $password = $_POST['password'] ?? '';

    // Validaciones
    if (strlen($username) < 4 || !ctype_alnum($username)) {
        $error = "El nombre de usuario debe tener más de 4 caracteres y solo contener letras y números.";
    } elseif (strlen($password) < 5) {
        $error = "La contraseña debe tener más de 5 caracteres.";
    } elseif (isset($usuarios[$username]) && $usuarios[$username]['password']=== $password) {
        // Autenticación exitosa
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $usuarios[$username]['role'];
        header('Location: ' .($usuarios[$username]['role']=== 'Profesor' ? 'dashboard_profesor.php' : 'dashboard_estudiante.php'));
        exit;
    } else {
        $error = "Las credenciales no coinciden, por favor colocar las credenciales correctas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
