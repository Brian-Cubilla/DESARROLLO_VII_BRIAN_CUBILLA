<?php
require_once 'Empleado.php';
require_once 'Gerente.php';
require_once 'Desarrollador.php';
require_once 'Empresa.php';

$empresa = new Empresa();

$gerente = new Gerente("Carlos", 1, 5000, "IT");
$desarrollador = new Desarrollador("Ana", 2, 3000, "PHP", "Intermedio");

$empresa->agregarEmpleado($gerente);
$empresa->agregarEmpleado($desarrollador);

echo "Listado de empleados:\n";
$empresa->listarEmpleados();

echo "Nómina total: " . $empresa->calcularNominaTotal() . "\n";

// Ejemplo de evaluación de desempeño (puedes implementar la lógica en cada clase)
$empresa->evaluarDesempenio();
?>
