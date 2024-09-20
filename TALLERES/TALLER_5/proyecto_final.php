<?php

class Estudiante {
    private $id;
    private $nombre;
    private $edad;
    private $carrera;
    private $materias;

    public function __construct($id, $nombre, $edad, $carrera) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->carrera = $carrera;
        $this->materias = [];
    }

    public function agregarMateria($materia, $calificacion) {
        $this->materias[$materia] = $calificacion;
    }

    public function obtenerPromedio() {
        if (count($this->materias) === 0) {
            return 0;
        }
        return array_sum($this->materias) / count($this->materias);
    }

    public function obtenerDetalles() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'carrera' => $this->carrera,
            'materias' => $this->materias,
            'promedio' => $this->obtenerPromedio()
        ];
    }

    public function __toString() {
        return "{$this->nombre} (ID: {$this->id}, Promedio: {$this->obtenerPromedio()})";
    }
}

class SistemaGestionEstudiantes {
    private $estudiantes;
    private $graduados;

    public function __construct() {
        $this->estudiantes = [];
        $this->graduados = [];
    }

    public function agregarEstudiante(Estudiante $estudiante) {
        $this->estudiantes[$estudiante->obtenerDetalles()['id']] = $estudiante;
    }

    public function obtenerEstudiante($id) {
        return $this->estudiantes[$id] ?? null;
    }

    public function listarEstudiantes() {
        return $this->estudiantes;
    }

    public function calcularPromedioGeneral() {
        if (count($this->estudiantes) === 0) return 0;

        $promedios = array_map(function($estudiante) {
            return $estudiante->obtenerPromedio();
        }, $this->estudiantes);
        
        return array_sum($promedios) / count($promedios);
    }

    public function obtenerEstudiantesPorCarrera($carrera) {
        return array_filter($this->estudiantes, function($estudiante) use ($carrera) {
            return $estudiante->obtenerDetalles()['carrera'] === $carrera;
        });
    }

    public function obtenerMejorEstudiante() {
        return array_reduce($this->estudiantes, function($mejor, $estudiante) {
            return ($mejor === null || $estudiante->obtenerPromedio() > $mejor->obtenerPromedio()) ? $estudiante : $mejor;
        });
    }

    public function generarReporteRendimiento() {
        $reporte = [];
        foreach ($this->estudiantes as $estudiante) {
            foreach ($estudiante->obtenerDetalles()['materias'] as $materia => $calificacion) {
                if (!isset($reporte[$materia])) {
                    $reporte[$materia] = [
                        'promedio' => 0,
                        'max' => 0,
                        'min' => 100,
                        'total' => 0,
                        'conteo' => 0
                    ];
                }

                $reporte[$materia]['promedio'] += $calificacion;
                $reporte[$materia]['max'] = max($reporte[$materia]['max'], $calificacion);
                $reporte[$materia]['min'] = min($reporte[$materia]['min'], $calificacion);
                $reporte[$materia]['total'] += $calificacion;
                $reporte[$materia]['conteo']++;
            }
        }

        foreach ($reporte as &$data) {
            $data['promedio'] = $data['promedio'] / $data['conteo'];
        }

        return $reporte;
    }

    public function graduarEstudiante($id) {
        if (isset($this->estudiantes[$id])) {
            $this->graduados[$id] = $this->estudiantes[$id];
            unset($this->estudiantes[$id]);
        }
    }

    public function generarRanking() {
        usort($this->estudiantes, function($a, $b) {
            return $b->obtenerPromedio() <=> $a->obtenerPromedio();
        });
        return $this->estudiantes;
    }
}

// Ejemplo de uso:

$sistema = new SistemaGestionEstudiantes();

// Crear estudiantes
$estudiante1 = new Estudiante(1, "Juan Pérez", 20, "Ingeniería");
$estudiante2 = new Estudiante(2, "Ana López", 22, "Medicina");
$estudiante3 = new Estudiante(3, "Carlos Gómez", 21, "Derecho");

// Agregar materias y calificaciones
$estudiante1->agregarMateria("Matemáticas", 90);
$estudiante1->agregarMateria("Física", 85);
$estudiante2->agregarMateria("Biología", 95);
$estudiante2->agregarMateria("Química", 88);
$estudiante3->agregarMateria("Derecho Civil", 80);
$estudiante3->agregarMateria("Derecho Penal", 92);

// Agregar estudiantes al sistema
$sistema->agregarEstudiante($estudiante1);
$sistema->agregarEstudiante($estudiante2);
$sistema->agregarEstudiante($estudiante3);

// Listar estudiantes
foreach ($sistema->listarEstudiantes() as $estudiante) {
    echo $estudiante . "\n";
}

// Obtener mejor estudiante
echo "Mejor estudiante: " . $sistema->obtenerMejorEstudiante() . "\n";

// Graduar a un estudiante
$sistema->graduarEstudiante(1);

// Mostrar ranking
$ranking = $sistema->generarRanking();
echo "\nRanking de estudiantes:\n";
foreach ($ranking as $estudiante) {
    echo $estudiante . "\n";
}

?>
