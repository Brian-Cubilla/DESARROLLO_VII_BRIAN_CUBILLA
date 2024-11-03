<?php
require_once "config_pdo.php";

function busquedaAvanzada($pdo, array $criterios) {
    $qb = new QueryBuilder($pdo);
    
    $qb->table('productos p')
       ->select('p.id', 'p.nombre', 'c.nombre as categoria', 'p.precio')
       ->join('categorias c', 'p.categoria_id', '=', 'c.id');

    if (!empty($criterios['nombre'])) {
        $qb->where('p.nombre', 'LIKE', '%' . $criterios['nombre'] . '%');
    }

    if (!empty($criterios['precio_min'])) {
        $qb->where('p.precio', '>=', $criterios['precio_min']);
    }

    if (!empty($criterios['precio_max'])) {
        $qb->where('p.precio', '<=', $criterios['precio_max']);
    }

    if (!empty($criterios['categorias']) && is_array($criterios['categorias'])) {
        $qb->whereIn('c.id', $criterios['categorias']);
    }

    if (!empty($criterios['ordenar_por'])) {
        $orden = $criterios['orden'] ?? 'ASC';
        $qb->orderBy($criterios['ordenar_por'], $orden);
    }

    if (!empty($criterios['limite'])) {
        $offset = $criterios['offset'] ?? 0;
        $qb->limit($criterios['limite'], $offset);
    }

    return $qb->execute();
}

// Ejemplo de uso
$criterios = [
    'nombre' => 'laptop',
    'precio_min' => 500,
    'precio_max' => 2000,
    'categorias' => [1, 2],
    'ordenar_por' => 'p.precio',
    'orden' => 'DESC',
    'limite' => 10
];

$resultados = busquedaAvanzada($pdo, $criterios);

// Mostrar los resultados
echo "<pre>";
print_r($resultados);
echo "</pre>";
?>
