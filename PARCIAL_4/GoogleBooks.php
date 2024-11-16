<?php
// googleBooks.php
session_start();
// Se incluyen los archivos de la API de Google Books y de configuración.
require_once 'GoogleBooksAPI.php';
require_once 'config.php';

// Se verifica si el usuario está autenticado, verificando la existencia de `user_id` en la sesión.
// Si no está autenticado, se redirige al usuario a la página de inicio de sesión.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Se obtiene el ID de usuario de la sesión.
$user_id = $_SESSION['user_id'];
// Se crea una instancia de la API de Google Books para poder buscar y obtener información de libros.
$googleBooksAPI = new GoogleBooksAPI();
$resultados = []; // Array que almacenará los resultados de la búsqueda.

// Se manejan las acciones de búsqueda y guardado de libros, si el formulario fue enviado por POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si la acción es 'buscar', se obtiene el término de búsqueda y se buscan los libros.
    if ($_POST['action'] === 'buscar') {
        $query = $_POST['query'];
        $resultados = $googleBooksAPI->buscarLibros($query);
    } 
    // Si la acción es 'guardar' y se ha recibido un ID de Google Books, se guarda el libro en la base de datos.
    elseif ($_POST['action'] === 'guardar' && isset($_POST['google_books_id'])) {
        $googleBooksId = $_POST['google_books_id'];
        guardarLibro($googleBooksId, $user_id, $db);

        // Después de guardar, se recarga la página para actualizar la lista de libros guardados.
        header('Location: googleBooks.php');
        exit();
    }
}

// Se obtienen los libros guardados por el usuario desde la base de datos.
$librosGuardados = obtenerLibrosGuardados($user_id, $db);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Google Books</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Enlace al archivo de estilos CSS -->
</head>
<body class="app">

<!-- Navegación principal de la aplicación -->
<nav class="nav">
    <div class="navbar-container">
        <div class="navbar-logo">Mi Biblioteca</div>
        <div class="navbar-menu">
            <a href="googleBooks.php" class="navbar-button">Google Books</a>
            <a href="librosGuardados.php" class="navbar-button">Mis Libros Guardados</a>
            <a href="logout.php" class="navbar-button">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<!-- Contenido Principal -->
<div class="main-content">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Buscar Libros en Google Books</h2>
            </div>
        </div>
        <div class="card-content">
            <!-- Formulario de búsqueda de libros en Google Books -->
            <form method="POST" action="">
                <input type="text" name="query" placeholder="Buscar libros..." class="search-input">
                <input type="hidden" name="action" value="buscar">
                <button type="submit" class="search-button">Buscar</button>
            </form>

            <!-- Mostrar resultados de la búsqueda -->
            <?php if (!empty($resultados)): ?>
                <h2>Resultados de la Búsqueda</h2>
                <ul>
                    <?php 
                    // Obtenemos una lista de los IDs de los libros ya guardados para verificar si el libro ya está en la biblioteca.
                    $librosGuardadosIds = array_column($librosGuardados, 'google_books_id'); 
                    foreach ($resultados as $book): 
                        $bookId = $book['id'];
                        // Verificamos si el libro ya está guardado.
                        $isSaved = in_array($bookId, $librosGuardadosIds);
                    ?>
                        <li>
                            <!-- Información básica del libro, como título, autor y portada -->
                            <strong><?php echo htmlspecialchars($book['volumeInfo']['title'] ?? 'Sin título'); ?></strong><br>
                            Autor: <?php echo htmlspecialchars(implode(", ", $book['volumeInfo']['authors'] ?? ['Desconocido'])); ?><br>
                            <img src="<?php echo htmlspecialchars($book['volumeInfo']['imageLinks']['thumbnail'] ?? ''); ?>" alt="Portada"><br>

                            <!-- Si el libro ya está guardado, mostramos un mensaje. Si no, mostramos un botón para guardarlo. -->
                            <?php if ($isSaved): ?>
                                <p>Libro ya guardado</p>
                            <?php else: ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="google_books_id" value="<?php echo htmlspecialchars($bookId); ?>">
                                    <input type="hidden" name="action" value="guardar">
                                    <button type="submit" class="diseño-button">Guardar en mi Biblioteca</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
