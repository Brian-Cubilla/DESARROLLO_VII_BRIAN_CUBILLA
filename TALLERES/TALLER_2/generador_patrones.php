<?php
// 1. Patrón de triángulo rectángulo usando asteriscos (*) con un bucle for
echo "<h3>Patrón de triángulo rectángulo</h3>";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "<br>";
}

// 2. Generar una secuencia de números del 1 al 20, mostrando solo los impares usando un bucle while
echo "<h3>Números impares del 1 al 20</h3>";
$numero = 1;
while ($numero <= 20) {
    if ($numero % 2 != 0) {
        echo $numero . " ";
    }
    $numero++;
}
echo "<br>";

// 3. Contador regresivo desde 10 hasta 1, pero saltando el número 5 usando un bucle do-while
echo "<h3>Contador regresivo (sin el 5)</h3>";
$contador = 10;
do {
    if ($contador != 5) {
        echo $contador . " ";
    }
    $contador--;
} while ($contador >= 1);
echo "<br>";
?>
