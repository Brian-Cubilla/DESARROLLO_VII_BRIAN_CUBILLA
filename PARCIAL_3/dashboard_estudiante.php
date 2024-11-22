<?php
session_start();

if ($_SESSION['role'] !== 'Estudiante') {
    header('Location: index.php');
    exit;
}

$calificaciones = [
    'estudiante1' => 79
];

$calificacion = $calificaciones [$_SESSION['user']] ?? 'Sin Calificacion';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Estudiante</title>
</head>
<body>
    <h1>Bienvenido, Estudiante</h1>
    <p>Tu calificación: <?= $calificacion ?></p>
    <ul>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>