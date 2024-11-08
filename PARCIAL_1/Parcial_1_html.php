<?php
include 'Parcial_1.php';

$datos = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

$media = calcular_media($datos);

$mediana = calcular_mediana($datos);

$moda = calcular_moda($datos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Datos</title>
</head>
<body>
    <h1 style="text-align: center;">Analisis de Datos</h1>
    <table style="width: 50%; margin: 20px auto; border: 1px solid #000;">
        <tr>
            <th style="padding: 8px; text-align: center;">Estadística</th>
            <th style="padding: 8px; text-align: center;">Valor</th>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: center;">Media</td>
            <td style="padding: 8px; text-align: center;"><?php echo round($media, 2); ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: center;">Mediana</td>
            <td style="padding: 8px; text-align: center;"><?php echo $mediana; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: center;">Moda</td>
            <td style="padding: 8px; text-align: center;"><?php echo implode(', ', $moda); ?></td>
        </tr>
    </table> 
</body>
</html>
