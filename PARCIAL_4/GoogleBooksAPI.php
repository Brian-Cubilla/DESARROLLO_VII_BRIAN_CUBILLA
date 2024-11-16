<?php
// Incluir la configuración de conexión a la base de datos (configuración de PDO)
require_once "config.php";

// Clase para interactuar con la API de Google Books
class GoogleBooksAPI {
    private $apiUrl = "https://www.googleapis.com/books/v1/volumes";

    // Función para buscar libros usando la API de Google Books
    public function buscarLibros($query) {
        // Convierte la consulta a un formato seguro para URL
        $query = urlencode($query);
        $url = "{$this->apiUrl}?q={$query}";

        // Realiza la solicitud a la API de Google Books
        $response = @file_get_contents($url);
        if ($response === false) {
            return []; // Retorna un array vacío si hay un error
        }

        // Decodifica la respuesta JSON y retorna los libros encontrados
        $data = json_decode($response, true);
        return $data['items'] ?? [];
    }

    // Función para obtener detalles de un libro específico usando su ID de Google Books
    public function obtenerLibroPorId($googleBooksId) {
        $url = "{$this->apiUrl}/{$googleBooksId}"; // URL para obtener los detalles del libro
        $response = @file_get_contents($url);

        if ($response === false) {
            return null; // Retorna null si hay un error en la solicitud
        }

        // Decodifica la respuesta JSON y retorna la información del libro
        $data = json_decode($response, true);
        return $data['volumeInfo'] ?? null;
    }
}

// Función para guardar un libro en la base de datos para un usuario
function guardarLibro($googleBooksId, $userId, $db) {
    $booksApi = new GoogleBooksAPI(); // Crea una instancia de GoogleBooksAPI
    $bookData = $booksApi->obtenerLibroPorId($googleBooksId); // Obtiene los datos del libro

    if ($bookData) {
        // Extrae la información del libro o asigna valores predeterminados si faltan datos
        $title = $bookData['title'] ?? 'Sin título';
        $author = implode(", ", $bookData['authors'] ?? ['Desconocido']);
        $image = $bookData['imageLinks']['thumbnail'] ?? '';

        // Verifica si el libro ya ha sido guardado por el usuario
        $stmt = $db->prepare("SELECT COUNT(*) FROM libros_guardados WHERE user_id = ? AND google_books_id = ?");
        $stmt->execute([$userId, $googleBooksId]);
        $alreadySaved = $stmt->fetchColumn();

        if ($alreadySaved == 0) {
            // Si el libro no está guardado, lo inserta en la base de datos
            $stmt = $db->prepare("INSERT INTO libros_guardados (user_id, google_books_id, titulo, autor, imagen_portada, fecha_guardado) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $googleBooksId, $title, $author, $image]);
            return "Libro guardado"; // Retorna mensaje de éxito
        } else {
            return "El libro ya está guardado en tu biblioteca."; // Mensaje si el libro ya está guardado
        }
    }
    return "Error al guardar el libro."; // Mensaje de error si no se pudo guardar
}

// Función para obtener todos los libros guardados de un usuario
function obtenerLibrosGuardados($userId, $db) {
    $stmt = $db->prepare("SELECT * FROM libros_guardados WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los libros guardados en un array asociativo
}

// Función para eliminar un libro de la biblioteca del usuario
function eliminarLibro($bookId, $userId, $db) {
    $stmt = $db->prepare("DELETE FROM libros_guardados WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookId, $userId]);
    return $stmt->rowCount() > 0; // Retorna true si se eliminó al menos un registro
}

// Función para agregar o actualizar una reseña en un libro guardado por el usuario
function agregarResena($bookId, $resena, $userId, $db) {
    $stmt = $db->prepare("UPDATE libros_guardados SET reseña_personal = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$resena, $bookId, $userId]);
    return $stmt->rowCount() > 0; // Retorna true si se actualizó la reseña
}
?>
