<?php
require_once "config_pdo.php";

// Función para procesar una devolución de producto
function procesarDevolucion($pdo, $venta_id, $producto_id, $cantidad) {
    try {
        $stmt = $pdo->prepare("CALL sp_procesar_devolucion(:venta_id, :producto_id, :cantidad)");
        $stmt->bindParam(':venta_id', $venta_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        
        echo "Devolución procesada con éxito.";
    } catch (PDOException $e) {
        echo "Error al procesar la devolución: " . $e->getMessage();
    }
}

// Función para calcular y aplicar descuentos
function aplicarDescuento($pdo, $cliente_id, $porcentaje) {
    try {
        $stmt = $pdo->prepare("CALL sp_aplicar_descuento(:cliente_id, :porcentaje)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':porcentaje', $porcentaje, PDO::PARAM_STR);
        $stmt->execute();
        
        $descuento = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $descuento['resultado'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Función para generar un reporte de productos con bajo stock
function reporteBajoStock($pdo) {
    try {
        $stmt = $pdo->query("CALL sp_reporte_bajo_stock()");
        
        echo "<h3>Reporte de Productos con Bajo Stock</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Producto</th>
                <th>Stock</th>
                <th>Promedio Stock</th>
                <th>Sugerencia Reposición</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['producto']}</td>";
            echo "<td>{$row['stock']}</td>";
            echo "<td>{$row['promedio_stock']}</td>";
            echo "<td>{$row['sugerencia_reposicion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Función para calcular comisiones
function calcularComisiones($pdo, $criterio) {
    try {
        $stmt = $pdo->prepare("CALL sp_calcular_comisiones(:criterio)");
        $stmt->bindParam(':criterio', $criterio, PDO::PARAM_STR);
        $stmt->execute();
        
        echo "<h3>Comisiones por " . ucfirst($criterio) . "</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Comisión</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['cliente_id']}</td>";
            echo "<td>${$row['comision']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ejemplos de uso
procesarDevolucion($pdo, 1, 1, 1);
aplicarDescuento($pdo, 1, 10);
reporteBajoStock($pdo);
calcularComisiones($pdo, 'total');

$pdo = null;
?>
