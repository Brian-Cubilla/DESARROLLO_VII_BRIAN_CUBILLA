<?php
interface Prestable{
    function obtenerDetallesPrestamos(): string;
}

abstract class RecursoBiblioteca implements Prestable {
    public $id;
    public $titulo;
    public $autor;
    public $anioPublicacion;
    public $estado;
    public $fechaAdquisicion;
    public $tipo;

    public function __construct($datos) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getTitulo()
    {
        return $this->titulo;
    }
    public function getAutor()
    {
        return $this->autor;
    }
    public function getanioPublicacion()
    {
        return $this->anioPublicacion;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getFechaAdquisicion()
    {
        return $this->fechaAdquisicion;
    }
    public function getTipo()
    {
        return $this->tipo;
    }

   
}

// Implementar las clases Libro, Revista y DVD aquí

class Libro extends RecursoBiblioteca implements Prestable{
    public $isbn;
    public function __construct($isbn){
        $this->isbn = $isbn;

    }
    

    public function obtenerDestallesPrestamo():string
    {
    return "ID {$this->id}Libro: Titulo: {$this->titulo}, Autor: {$this->autor}, Año de Publicación: {$this->anioPublicacion}, Estado: {$this->estado}, Fecha  de Adquisición: {$this->fechaAdquisicion}, tipo: {$this->tipo}, isbn: {$this->isbn}. ";
    }
    
       
    
}
class Revista extends RecursoBiblioteca implements Prestable {
    public $numeroEdicion;

    public function __construct($numeroEdicion){
        $this->numeroEdicion = $numeroEdicion;
        
    }

    public function obtenerDestallesPrestamo():string
    {
    return "ID {$this->id}Libro: Titulo: {$this->titulo}, Autor: {$this->autor}, Año de Publicación: {$this->anioPublicacion}, Estado: {$this->estado}, Fecha  de Adquisición: {$this->fechaAdquisicion}, tipo: {$this->tipo},numero de Edicion: {$this->numeroEdicion}. ";
    }
}
class DVD extends RecursoBiblioteca implements Prestable{
    public $duracion;
    public function __construct($duracion){
        $this->duracion = $duracion;
        
    }
    public function obtenerDestallesPrestamo():string
    {
    return "ID {$this->id}Libro: Titulo: {$this->titulo}, Autor: {$this->autor}, Año de Publicación: {$this->anioPublicacion}, Estado: {$this->estado}, Fecha  de Adquisición: {$this->fechaAdquisicion}, tipo: {$this->tipo},duracion: {$this->duracion}.";
    }
}




class GestorBiblioteca  {
    private $recursos = [];

    public function cargarRecursos() {
        $json = file_get_contents('biblioteca.json');
        $data = json_decode($json, true);
        
        foreach ($data as $recursoData) {
            $recurso = new RecursoBiblioteca($recursoData);
            $this->recursos[] = $recurso;
        }
        
        return $this->recursos;
    }

    public function agregarRecurso(RecursoBiblioteca $recurso) {
        $this->recursos[] = $recurso;
        $this->guardarRecurso();
    }
    
    public function eliminarRecurso($id) {
        $this->recursos = array_filter($this->recursos, function($recurso) use ($id) {
            return $recurso->getId() !== $id;
        });
        $this->guardarRecurso();
    }
    
    public function actualizarRecurso($id ,RecursoBiblioteca $recurso) {
        foreach ($this->recursos as $key => $r) {
            if ($r->getId() === $recurso->getId()) {
                $this->recursos[$key] = $recurso;
                break;
            }
            
        }
        $this->guardarRecurso();
    }

    
    public function actualizarEstadoRecurso($id, $nuevoEstado) {
        foreach ($this->recursos as $recurso) {
            if ($recurso->getId() === $id) {
                $recurso->setEstado($nuevoEstado);
                break;
            }
        }
        $this->guardarRecurso();
    }

    public function buscarRecursoPorEstado($estado) {
        return array_filter($this->recursos, function($recurso) use ($estado) {
            return $recurso->getEstado() === $estado;
        });
    }
    
    public function listarRecurso($filtroEstado = '') {
        if ($filtroEstado) {
            return $this->buscarRecursoPorEstado($filtroEstado);
        }
        return $this->recursos;
    }

    private function guardarRecurso() {
        $recursos = array_map(function($recurso) {
            return $recurso->toArray();
        }, $this->recursos);
    
        file_put_contents('tareas.json', json_encode($recursos));
    }
    
}

