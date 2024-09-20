<?php
require_once 'Empleado.php';

class Gerente extends Empleado {
    private $departamento;

    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function asignarBono($monto) {
        $this->setSalarioBase($this->getSalarioBase() + $monto);
    }
}
?>
