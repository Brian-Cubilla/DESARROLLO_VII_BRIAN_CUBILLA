<?php
// Función para leer el inventario desde el archivo JSON
function leerInventario($archivo) {
    if (file_exists($archivo)) {
        $json = file_get_contents($archivo);
        return json_decode($json, true);
    } else {
        return [];
    }
}

// Función para ordenar el inventario alfabéticamente por nombre del producto
function ordenarInventario(&$inventario) {
    usort($inventario, function ($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });
}

// Función para mostrar un resumen del inventario ordenado
function mostrarInventario($inventario) {
    echo "Resumen del inventario:\n";
    foreach ($inventario as $producto) {
        echo "Producto: " . $producto['nombre'] . ", Precio: $" . number_format($producto['precio'], 2) . ", Cantidad: " . $producto['cantidad'] . "\n";
    }
}

// Función para calcular el valor total del inventario
function calcularValorTotal($inventario) {
    $valorTotal = array_sum(array_map(function ($producto) {
        return $producto['precio'] * $producto['cantidad'];
    }, $inventario));

    return $valorTotal;
}

// Función para generar un informe de productos con stock bajo (menos de 5 unidades)
function generarInformeStockBajo($inventario) {
    $productosBajoStock = array_filter($inventario, function ($producto) {
        return $producto['cantidad'] < 5;
    });

    if (count($productosBajoStock) > 0) {
        echo "Informe de productos con stock bajo (menos de 5 unidades):\n";
        foreach ($productosBajoStock as $producto) {
            echo "Producto: " . $producto['nombre'] . ", Cantidad: " . $producto['cantidad'] . "\n";
        }
    } else {
        echo "No hay productos con stock bajo.\n";
    }
}

// Script principal para demostrar el uso de las funciones
$archivoInventario = "inventario.json";
$inventario = leerInventario($archivoInventario);

if (empty($inventario)) {
    echo "El archivo de inventario está vacío o no se encontró.\n";
    exit;
}

// Ordenar el inventario alfabéticamente
ordenarInventario($inventario);

// Mostrar el inventario ordenado
mostrarInventario($inventario);

// Calcular y mostrar el valor total del inventario
$valorTotal = calcularValorTotal($inventario);
echo "El valor total del inventario es: $" . number_format($valorTotal, 2) . "\n";

// Generar el informe de productos con stock bajo
generarInformeStockBajo($inventario);
?>
