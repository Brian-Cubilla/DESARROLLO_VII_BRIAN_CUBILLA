<?php
session_start();

// verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// se verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $fecha_limite = $_POST['fecha_limite'];

    // validar que los campos estén completos
    if (empty($titulo) || empty($fecha_limite)) {
        $_SESSION['error'] = "Por favor, completa todos los campos.";
    } elseif (strtotime($fecha_limite) < time()) {
        $_SESSION['error'] = "La fecha límite debe ser en el futuro.";
    } else {
        // almacenar la tarea en un array a la sesión del usuario
        if (!isset($_SESSION['tareas'])) {
            $_SESSION['tareas'] = [];
        }
        $_SESSION['tareas'][] = ['titulo' => $titulo, 'fecha_limite' => $fecha_limite];
        $_SESSION['mensaje'] = "Tarea agregada exitosamente.";
    }
}

// redirigir de nuevo al dashboard para mostrar las tareas cargadas
header('Location: dashboard.php');
exit;
