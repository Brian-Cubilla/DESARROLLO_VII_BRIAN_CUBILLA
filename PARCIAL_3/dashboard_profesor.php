<?php
session_start();

if ($_SESSION['role'] !== 'Profesor') {
    header('Location: index.php');
    exit;
}

$estudiantes = [
    ['nombre' => 'Brian', 'calificacion' => 79], //estudiante 1
    ['nombre' => 'Smith', 'calificacion' => 100]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Profesor</title>
</head>
<body>
    <h1>Bienvenido, Profesor</h1>
    <h2>Lista de Estudiantes</h2>
    <ul>
        <?php foreach ($estudiantes as $estudiante): ?>
            <li><?= $estudiante['nombre'] ?> - Calificación: <?= $estudiante['calificacion'] ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>