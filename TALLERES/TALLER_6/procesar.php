<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Procesar y validar cada campo
    $campos = ['nombre', 'email', 'edad', 'sitio_web', 'genero', 'intereses', 'comentarios'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];
            $valorSanitizado = call_user_func("sanitizar" . ucfirst($campo), $valor);
            $datos[$campo] = $valorSanitizado;

            if (!call_user_func("validar" . ucfirst($campo), $valorSanitizado)) {
                $errores[] = "El campo $campo no es válido.";
            }
        }
    }

    // Procesar la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fotoPerfil = $_FILES['foto_perfil'];
        $nombreUnico = uniqid() . "_" . basename($fotoPerfil['name']); // Generar un nombre único para la foto
        if (!validarFotoPerfil($fotoPerfil)) {
            $errores[] = "La foto de perfil no es válida.";
        } else {
            $rutaDestino = 'uploads/' . $nombreUnico;
            if (move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Hubo un error al subir la foto de perfil.";
            }
        }
    }

    // Mostrar resultados o errores
    if (empty($errores)) {
        // Guardar datos en JSON
        $archivoRegistros = 'registros.json';
        
        // Verificar si el archivo existe, de lo contrario, inicializar un array vacío
        $registrosPrevios = file_exists($archivoRegistros) ? json_decode(file_get_contents($archivoRegistros), true) : [];

        // Añadir los nuevos datos al array de registros previos
        $registrosPrevios[] = $datos;

        // Guardar los registros actualizados en el archivo JSON
        file_put_contents($archivoRegistros, json_encode($registrosPrevios, JSON_PRETTY_PRINT));


        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr>";
            echo "<th>" . ucfirst($campo) . "</th>";
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        // Enlace para ver todos los registros
        echo "<br><a href='ver_registros.php'>Ver todos los registros procesados</a>";
    } else {
        echo "<h2>Errores:</h2>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";

        // Enlace para volver al formulario
        echo "<br><a href='formulario.html'>Volver al formulario</a>";
    }
} else {
    echo "Acceso no permitido.";
}
?>
