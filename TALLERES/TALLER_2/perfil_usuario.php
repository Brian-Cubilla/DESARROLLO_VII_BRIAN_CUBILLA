
<?php
$nombre_completo = "Brian Cubilla";
$edad = 20;
$correo "abriancubilla@gmail.com" ;
$telefono = 66525292 ;


define("Ocupacion", "Estudiante");

$mensaje1 = "Hola, mi nombre es " . $nombre_completo . " y tengo " . $edad . " años.";
$mensaje2 = "mi correo es $correo y mi ocupacion " . Ocupacion . ".";

echo $mensaje1 . "<br>";
print($mensaje2  . "<br>");

echo "<br>Información de debugging:<br>";
var_dump($nombre_completo);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($corrreo);
echo "<br>";
var_dump(Ocupacion);
echo "<br>";
?>
                    
