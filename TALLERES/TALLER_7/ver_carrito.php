<?php
include 'config_sesion.php';

$productos = [
    1 => ['nombre' => 'Producto 1', 'precio' => 10],
    2 => ['nombre' => 'Producto 2', 'precio' => 15],
    3 => ['nombre' => 'Producto 3', 'precio' => 20],
    4 => ['nombre' => 'Producto 4', 'precio' => 25],
    5 => ['nombre' => 'Producto 5', 'precio' => 30],
];

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
</head>
<body>
    <h2>Tu Carrito</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Acción</th>
        </tr>
        <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
            <?php foreach ($_SESSION['carrito'] as $id => $cantidad): ?>
                <tr>
                    <td><?php echo htmlspecialchars($productos[$id]['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($productos[$id]['precio']); ?> €</td>
                    <td><?php echo htmlspecialchars($cantidad); ?></td>
                    <td>
                        <form method="post" action="eliminar_del_carrito.php">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
                <?php $total += $productos[$id]['precio'] * $cantidad; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Tu carrito está vacío.</td>
            </tr>
        <?php endif; ?>
    </table>
    <h3>Total: <?php echo htmlspecialchars($total); ?> €</h3>
    <a href="checkout.php">Checkout</a>
    <a href="productos.php">Seguir Comprando</a>
</body>
</html>
