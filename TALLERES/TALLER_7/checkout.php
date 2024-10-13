<?php
include 'config_sesion.php';

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    die("Tu carrito está vacío.");
}

// Resumen de la compra
$total = 0;
$compras = [];
foreach ($_SESSION['carrito'] as $id => $cantidad) {
    $compras[] = [
        'nombre' => htmlspecialchars($productos[$id]['nombre']),
        'precio' => htmlspecialchars($productos[$id]['precio']),
        'cantidad' => htmlspecialchars($cantidad),
        'subtotal' => htmlspecialchars($productos[$id]['precio'] * $cantidad)
    ];
    $total += $productos[$id]['precio'] * $cantidad;
}

// Limpiar el carrito
unset($_SESSION['carrito']);

// Guardar el nombre del usuario en una cookie
setcookie("usuario", "Juan", time() + (86400), "/"); // 24 horas

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Compra</title>
</head>
<body>
    <h2>Resumen de Compra</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($compras as $compra): ?>
        <tr>
            <td><?php echo $compra['nombre']; ?></td>
            <td><?php echo $compra['precio']; ?> €</td>
            <td><?php echo $compra['cantidad']; ?></td>
            <td><?php echo $compra['subtotal']; ?> €</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h3>Total: <?php echo htmlspecialchars($total); ?> €</h3>
    <p>Gracias por tu compra!</p>
    <a href="productos.php">Volver a Productos</a>
</body>
</html>
