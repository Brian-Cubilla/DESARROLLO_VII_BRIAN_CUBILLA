<?php
// configuracion de conexión PDO
$dsn = 'mysql:host=localhost;dbname=tu_base_datos';
$username = 'usuario';
$password = 'contraseña';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);
?>
