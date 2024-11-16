<?php
session_start();
// Incluir archivos de configuración y la clase de la API de Google Books
require_once 'config.php';
require_once 'GoogleBooksAPI.php';

// Verificar que el usuario esté autenticado (sesión activa)
if (!isset($_SESSION['user_id'])) {
    // Si el usuario no está autenticado, redirigir a la página de login
    header('Location: login.php');
    exit();
}
// Se obtiene el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Se obtiene los libros guardados del usuario desde la base de datos
$librosGuardados = obtenerLibrosGuardados($user_id, $db);

// Procesar las acciones de agregar reseña o eliminar libro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si la acción es 'agregarResena' y se han enviado 'book_id' y 'resena'
    if ($_POST['action'] === 'agregarResena' && isset($_POST['book_id'], $_POST['resena'])) {
        $bookId = $_POST['book_id'];   // ID del libro
        $resena = $_POST['resena'];    // Contenido de la reseña
        agregarResena($bookId, $resena, $user_id, $db);  // Llamar a la función para agregar o actualizar la reseña
        
        // Recargar la misma página después de guardar la reseña
        header('Location: librosGuardados.php');
        exit();
    }
    // Si la acción es 'eliminar' y se ha enviado 'book_id'
    elseif ($_POST['action'] === 'eliminar' && isset($_POST['book_id'])) {
        $bookId = $_POST['book_id'];  // ID del libro a eliminar
        eliminarLibro($bookId, $user_id, $db);  // Llamar a la función para eliminar el libro
        
        // Recargar la misma página después de eliminar el libro
        header('Location: librosGuardados.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Libros Guardados</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<!-- Navegación -->
<nav class="nav">
    <div class="navbar-container">
        <div class="navbar-logo">Mi Biblioteca</div>
        <div class="navbar-menu">
            <!-- Botones de navegación a otras páginas -->
            <a href="googleBooks.php" class="navbar-button">Google Books</a>
            <a href="librosGuardados.php" class="navbar-button">Mis Libros Guardados</a>
            <a href="logout.php" class="navbar-button">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <span class="card-icon">📚</span>
                Mis Libros Guardados
            </h2>
            <p class="card-description">Aquí puedes agregar o eliminar libros guardados, así como actualizar tus reseñas.</p>
        </div>
        <div class="card-content">
            <ul>
                <?php if (empty($librosGuardados)): ?>
                    <p class="empty-message">No tienes libros guardados aún.</p>
                <?php else: ?>
                    <?php foreach ($librosGuardados as $row): ?>
                        <li class="book-info">
                            <strong><?php echo htmlspecialchars($row['titulo']); ?></strong>
                            <p>Autor: <?php echo htmlspecialchars($row['autor']); ?></p>
                            <img src="<?php echo htmlspecialchars($row['imagen_portada']); ?>" alt="Portada">
                            
                            <p>Reseña: <?php echo htmlspecialchars($row['reseña_personal'] ?? 'Sin reseña'); ?></p>
                        
                            <!-- Formulario para agregar o actualizar reseña -->
                            <form method="POST" action="librosGuardados.php">
                                <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <textarea name="resena"></textarea>
                                <input type="hidden" name="action" value="agregarResena">
                                <button type="submit" class="diseño-button">Agregar/Actualizar Reseña</button>
                            </form>
                            
                            <!-- Formulario para eliminar un libro -->
                            <form method="POST" action="librosGuardados.php">
                                <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="action" value="eliminar">
                                <button type="submit" class="diseño-button">Eliminar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
