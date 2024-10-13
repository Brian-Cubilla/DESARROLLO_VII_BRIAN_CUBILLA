<?php
include 'config_sesion.php';

$productos = [
    ['id' => 1, 'nombre' => 'Producto 1', 'precio' => 10],
    ['id' => 2, 'nombre' => 'Producto 2', 'precio' => 15],
    ['id' => 3, 'nombre' => 'Producto 3', 'precio' => 20],
    ['id' => 4, 'nombre' => 'Producto 4', 'precio' => 25],
    ['id' => 5, 'nombre' => 'Producto 5', 'precio' => 30],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
</head>
<body>
    <h2>Lista de Productos</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
            <td><?php echo htmlspecialchars($producto['precio']); ?> €</td>
            <td>
                <form method="post" action="agregar_al_carrito.php">
                    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="submit" value="Añadir al Carrito">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="ver_carrito.php">Ver Carrito</a>
</body>
</html>
