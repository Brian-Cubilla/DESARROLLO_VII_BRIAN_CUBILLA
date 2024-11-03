<?php
class Paginator {
    protected $pdo;
    protected $table;
    protected $perPage;
    protected $currentPage;
    protected $totalRecords;
    protected $conditions = [];
    protected $params = [];
    protected $orderBy = '';
    protected $joins = [];
    protected $fields = ['*'];

    public function __construct(PDO $pdo, $table, $perPage = 10) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->perPage = $perPage;
        $this->currentPage = 1;
    }

    public function select($fields) {
        $this->fields = is_array($fields) ? $fields : func_get_args();
        return $this;
    }

    public function where($condition, $params = []) {
        $this->conditions[] = $condition;
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function join($join) {
        $this->joins[] = $join;
        return $this;
    }

    public function orderBy($orderBy) {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setPage($page) {
        $this->currentPage = max(1, (int)$page);
        return $this;
    }

    public function setPerPage($perPage) {
        $this->perPage = max(1, (int)$perPage);
        return $this;
    }

    public function getTotalRecords() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }
        
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchColumn();
    }

    public function getResults() {
        $offset = ($this->currentPage - 1) * $this->perPage;
        
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM {$this->table}";
        
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }
        
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }
        
        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }
        
        $sql .= " LIMIT {$this->perPage} OFFSET {$offset}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageInfo() {
        $totalRecords = $this->getTotalRecords();
        $totalPages = ceil($totalRecords / $this->perPage);

        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'has_previous' => $this->currentPage > 1,
            'has_next' => $this->currentPage < $totalPages,
            'previous_page' => $this->currentPage - 1,
            'next_page' => $this->currentPage + 1,
            'first_page' => 1,
            'last_page' => $totalPages,
        ];
    }

    public function exportToCSV($filename = 'export.csv') {
        $results = $this->getResults();
        
        $file = fopen($filename, 'w');
        fputcsv($file, array_keys($results[0]));

        foreach ($results as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}

// Ejemplo de uso del paginador tradicional con configuración de elementos por página
$paginator = new Paginator($pdo, 'productos');
$perPage = isset($_GET['per_page']) ? $_GET['per_page'] : 10;
$paginator->setPerPage($perPage)
          ->select('productos.*', 'categorias.nombre as categoria')
          ->join('LEFT JOIN categorias ON productos.categoria_id = categorias.id')
          ->where('productos.precio >= ?', [100])
          ->orderBy('productos.nombre ASC')
          ->setPage(isset($_GET['page']) ? $_GET['page'] : 1);

$results = $paginator->getResults();
$pageInfo = $paginator->getPageInfo();

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    $paginator->exportToCSV('productos_export.csv');
    echo "Datos exportados a productos_export.csv";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos Paginados</title>
    <style>
        .pagination {
            margin: 20px 0;
            padding: 0;
            list-style: none;
            display: flex;
            gap: 10px;
        }
        .pagination li {
            padding: 5px 10px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .pagination li.active {
            background-color: #007bff;
            color: white;
        }
        .pagination li.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .results {
            margin-bottom: 20px;
        }
        .results table {
            width: 100%;
            border-collapse: collapse;
        }
        .results th, .results td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="results">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><?= htmlspecialchars($row['categoria']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <ul class="pagination">
        <?php if ($pageInfo['has_previous']): ?>
        <li><a href="?page=1&per_page=<?= $pageInfo['per_page'] ?>">Primera</a></li>
        <li><a href="?page=<?= $pageInfo['previous_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Anterior</a></li>
        <?php else: ?>
        <li class="disabled">Primera</li>
        <li class="disabled">Anterior</li>
        <?php endif; ?>

        <li class="active"><?= $pageInfo['current_page'] ?></li>

        <?php if ($pageInfo['has_next']): ?>
        <li><a href="?page=<?= $pageInfo['next_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Siguiente</a></li>
        <li><a href="?page=<?= $pageInfo['last_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Última</a></li>
        <?php else: ?>
        <li class="disabled">Siguiente</li>
        <li class="disabled">Última</li>
        <?php endif; ?>
    </ul>

    <div>
        <form method="GET">
            <label for="per_page">Elementos por página:</label>
            <select id="per_page" name="per_page" onchange="this.form.submit()">
                <option value="5" <?= $pageInfo['per_page'] == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $pageInfo['per_page'] == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $pageInfo['per_page'] == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $pageInfo['per_page'] == 50 ? 'selected' : '' ?>>50</option>
            </select>
        </form>
    </div>

    <div>
        <a href="?export=csv">Exportar a CSV</a>
    </div>
</body>
</html>
