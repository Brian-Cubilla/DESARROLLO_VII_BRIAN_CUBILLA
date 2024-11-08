<?php
function calcular_media($datos){
    $media = array_sum($datos);
    $cantidad = count($datos);
    return $media / $cantidad;
}

function calcular_mediana($datos){
    sort($datos);
    $n = count($datos);
    
    if ($n % 2 == 1) {
        $mediana = $datos[intval($n / 2)];    
    } else {
        $mediana = ($datos[$n / 2 - 1] + $datos[$n / 2]) / 2;
    }
    return $mediana;
}

function calcular_moda($datos){
    $frecuencia =array_count_values($datos);
    arsort($frecuencia);

    $frecuencia_max = max($frecuencia);

    $moda = array_keys ($frecuencia, $frecuencia_max);

    return $moda;
}
?>