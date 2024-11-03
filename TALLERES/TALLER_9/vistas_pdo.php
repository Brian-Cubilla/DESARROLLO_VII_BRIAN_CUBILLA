<?php
require_once "config_pdo.php";

function mostrarProductosBajoStock($pdo) {
    $stmt = $pdo->query("SELECT * FROM vista_productos_bajo_stock");

    echo "<h3>Productos con Bajo Stock:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Producto</th>
            <th>Stock</th>
            <th>Precio</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['producto']}</td>";
        echo "<td>{$row['stock']}</td>";
        echo "<td>${$row['precio']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>${$row['ingresos_totales']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function mostrarHistorialClientes($pdo) {
    $stmt = $pdo->query("SELECT * FROM vista_historial_clientes");

    echo "<h3>Historial de Compras por Cliente:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Cliente</th>
            <th>Email</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Fecha de Venta</th>
            <th>Total Venta</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['cliente']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['producto']}</td>";
        echo "<td>{$row['cantidad']}</td>";
        echo "<td>${$row['subtotal']}</td>";
        echo "<td>{$row['fecha_venta']}</td>";
        echo "<td>${$row['total_venta']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function mostrarRendimientoCategorias($pdo) {
    $stmt = $pdo->query("SELECT * FROM vista_rendimiento_categorias");

    echo "<h3>Métricas de Rendimiento por Categoría:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Categoría</th>
            <th>Total Productos</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
            <th>Producto Más Vendido</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['categoria']}</td>";
        echo "<td>{$row['total_productos']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>${$row['ingresos_totales']}</td>";
        echo "<td>{$row['producto_mas_vendido']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function mostrarTendenciasVentas($pdo) {
    $stmt = $pdo->query("SELECT * FROM vista_tendencias_ventas");

    echo "<h3>Tendencias de Ventas por Mes:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Mes</th>
            <th>Ventas Totales</th>
            <th>Total Ventas</th>
            <th>Ventas del Mes Anterior</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['mes']}</td>";
        echo "<td>${$row['ventas_totales']}</td>";
        echo "<td>{$row['total_ventas']}</td>";
        echo "<td>${$row['ventas_mes_anterior']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Mostrar los resultados
mostrarProductosBajoStock($pdo);
mostrarHistorialClientes($pdo);
mostrarRendimientoCategorias($pdo);
mostrarTendenciasVentas($pdo);

$pdo = null;
?>
