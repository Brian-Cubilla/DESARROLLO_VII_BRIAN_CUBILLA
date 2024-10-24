<?php
session_start();

// comprobar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// cargar las tareas almacenadas en la sesión
$tareas = isset($_SESSION['tareas']) ? $_SESSION['tareas'] : [];

// mensaje por si hay errores o mensajes de éxito
if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>{$_SESSION['error']}</p>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['mensaje'])) {
    echo "<p style='color: green;'>{$_SESSION['mensaje']}</p>";
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Dashboard, <?php echo $_SESSION['usuario']; ?>!</h1>

        <form action="agregar_tarea.php" method="POST">
            <label for="titulo">Título de la tarea:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="fecha_limite">Fecha Límite:</label>
            <input type="date" id="fecha_limite" name="fecha_limite" required>

            <input type="submit" value="Agregar Tarea">
        </form>

        <div class="task-list">
            <h2>Lista de Tareas</h2>
            <?php if (empty($tareas)): ?>
                <p>No hay tareas disponibles.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($tareas as $tarea): ?>
                        <li><?php echo $tarea['titulo'] . " - " . $tarea['fecha_limite']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
