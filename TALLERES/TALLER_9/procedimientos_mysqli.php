<?php
require_once "config_mysqli.php";

// Función para procesar una devolución de producto
function procesarDevolucion($conn, $venta_id, $producto_id, $cantidad) {
    $query = "CALL sp_procesar_devolucion(?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $venta_id, $producto_id, $cantidad);
    
    try {
        mysqli_stmt_execute($stmt);
        echo "Devolución procesada con éxito.";
    } catch (Exception $e) {
        echo "Error al procesar la devolución: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para calcular y aplicar descuentos
function aplicarDescuento($conn, $cliente_id, $porcentaje) {
    $query = "CALL sp_aplicar_descuento(?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "id", $cliente_id, $porcentaje);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $descuento = mysqli_fetch_assoc($result);
        echo $descuento['resultado'];
    }
    
    mysqli_stmt_close($stmt);
}

// Función para generar un reporte de productos con bajo stock
function reporteBajoStock($conn) {
    $query = "CALL sp_reporte_bajo_stock()";
    $result = mysqli_query($conn, $query);
    
    echo "<h3>Reporte de Productos con Bajo Stock</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Producto</th>
            <th>Stock</th>
            <th>Promedio Stock</th>
            <th>Sugerencia Reposición</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['producto']}</td>";
        echo "<td>{$row['stock']}</td>";
        echo "<td>{$row['promedio_stock']}</td>";
        echo "<td>{$row['sugerencia_reposicion']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Función para calcular comisiones
function calcularComisiones($conn, $criterio) {
    $query = "CALL sp_calcular_comisiones(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $criterio);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        echo "<h3>Comisiones por " . ucfirst($criterio) . "</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Comisión</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['cliente_id']}</td>";
            echo "<td>${$row['comision']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    mysqli_stmt_close($stmt);
}

// Ejemplos de uso
procesarDevolucion($conn, 1, 1, 1);
aplicarDescuento($conn, 1, 10);
reporteBajoStock($conn);
calcularComisiones($conn, 'total');

mysqli_close($conn);
?>
