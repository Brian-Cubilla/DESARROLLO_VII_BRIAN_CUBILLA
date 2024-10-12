<?php
// Leer el archivo JSON de registros
$archivoRegistros = 'registros.json';
$registros = file_exists($archivoRegistros) ? json_decode(file_get_contents($archivoRegistros), true) : [];

if (!empty($registros)) {
    echo "<h2>Resumen de Registros Procesados:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nombre</th><th>Email</th><th>Edad</th><th>Sitio Web</th><th>Género</th><th>Intereses</th><th>Comentarios</th><th>Foto de Perfil</th></tr>";

    foreach ($registros as $registro) {
        echo "<tr>";
        echo "<td>{$registro['nombre']}</td>";
        echo "<td>{$registro['email']}</td>";
        echo "<td>{$registro['edad']}</td>";
        echo "<td>{$registro['sitio_web']}</td>";
        echo "<td>{$registro['genero']}</td>";
        echo "<td>" . implode(", ", $registro['intereses']) . "</td>";
        echo "<td>{$registro['comentarios']}</td>";
        echo "<td><img src='{$registro['foto_perfil']}' width='50'></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<h2>No hay registros procesados aún.</h2>";
}

// Enlace para volver al formulario
echo "<br><a href='formulario.html'>Volver al formulario</a>";
?>
