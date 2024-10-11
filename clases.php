<?php

use Entrada as GlobalEntrada;

require_once "Detalle.php";

abstract class Entrada {
    public $id;
    public $fecha_creacion;
    public $tipo;
    public $titulo;
    public $descripcion;

    public function __construct($datos = []) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getId() {
        return $this->id;
    }

    
    public function getTitulo() {
        return $this->titulo;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    abstract public function obtenerDetallesEspeficos(): string;
}




class  EntradaUnaColumna extends Entrada {

    public function __construct($datos=[]) {
        parent::__construct($datos=[]);
    }

    public function obtenerDetallesEspeficos(): string {
        return "Entrada de una columna Titulo :". parent::getTitulo()  ."Descripcion:"  .parent::getDescripcion();
    }
}

class  EntradaDosColumna extends Entrada {
    public $titulo2;
    public $descripcion2;

    public function __construct($titulo2,$descripcion2) {
        parent::__construct($datos=[]);
        $this->titulo2=$titulo2;
        $this->descripcion2=$descripcion2;
    }

    public function obtenerDetallesEspeficos(): string {
        return "Entrada de Dos columna Titulo :". parent::getTitulo()  ."Descripcion:"  .parent::getDescripcion()."Titulo 2: {$this->titulo2} Descripcion 2 {$this->descripcion2}:";
    }
}
class  EntradaTresColumna extends Entrada {
    public $titulo2;
    public $descripcion2;
    public $titulo3;
    public $descripcion3;

    public function __construct($titulo2,$descripcion2,$titulo3,$descripcion3) {
        parent::__construct($datos=[]);
        $this->titulo2=$titulo2;
        $this->$descripcion2=$descripcion2;
        $this->titulo3=$titulo3;
        $this->descripcion3=$descripcion3;
    }

    public function obtenerDetallesEspeficos(): string {
        return "Entrada de Tres columna Titulo :". parent::getTitulo()  ."Descripcion:"  .parent::getDescripcion()."Titulo 2: {$this->titulo2} Descripcion 2 {$this->descripcion2} Titulo 3: {$this->titulo3} Descripcion3: {$this->descripcion3}";
    } 
    
}

   

class GestorBlog {
    private $entradas = [];

    public function cargarEntradas() {
        if (file_exists('blog.json')) {
            $json = file_get_contents('blog.json');
            $data = json_decode($json, true);
            foreach ($data as $entradaData) {
                $this->entradas[] = new Entrada();
            }
        }
    }

    public function guardarEntradas() {
        $data = array_map(function($entrada) {
            return get_object_vars($entrada);
        }, $this->entradas);
        file_put_contents('blog.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerEntradas() {
        return $this->entradas;
    }



    public function agregarEntrada(Entrada $entrada) {
        $this->entradas[] = $entrada;
    }

    public function eliminarEntrada($id) {
        foreach ($this->entradas as $indice => $entrada) {
            if ($entrada->id == $id) {
                unset($this->entrada[$indice]);
                return;
            }
        }
    }

    public function actualizarEntrada(Entrada $entradaActualizada) {
        foreach ($this->entradas as $indice => $entrada) {
            if ($entrada->getId() == $entradaActualizada->getId()) {
                $this->$entrada[$indice] = $entradaActualizada;
                return;
            }
        }
    }
}   