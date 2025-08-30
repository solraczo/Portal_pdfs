<?php
// Carpeta física donde están los PDFs
$upload_dir = __DIR__ . "/data_pdf_01/";
// URL pública para acceder a los PDFs
$public_url = "data_pdf_01/";

$query = isset($_GET['query']) ? strtolower($_GET['query']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';

if (!is_dir($upload_dir)) {
    die("No existe la carpeta de PDFs.");
}

$files = array_diff(scandir($upload_dir), ['.', '..']);
$results = [];

// Filtrar resultados
foreach ($files as $file) {
    if (stripos($file, $query) !== false) {
        $results[] = [
            'name' => $file,
            'path' => $upload_dir . $file,
            'date' => filemtime($upload_dir . $file)
        ];
    }
}

// Ordenar resultados
if ($sort === 'name') {
    usort($results, function($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });
} elseif ($sort === 'date') {
    usort($results, function($a, $b) {
        return $b['date'] - $a['date']; // más recientes primero
    });
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
        header {
            background: white;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        header img {
            height: 50px;
            margin-right: 15px;
        }
        header h1 {
            font-size: 1.5em;
            color: #333;
            margin: 0;
        }
        main {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; display: flex; justify-content: space-between; align-items: center; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .date { font-size: 0.9em; color: gray; }
    </style>
</head>
<body>

<header>
    <img src="logo001.jpg" alt="Logo">
    <h1>Resultados de búsqueda</h1>
</header>

<main>
<?php if (count($results) > 0): ?>
    <ul>
        <?php foreach ($results as $item): ?>
            <li>
                <a href="<?php echo $public_url . rawurlencode($item['name']); ?>" target="_blank">
                    <?php echo htmlspecialchars($item['name']); ?>
                </a>
                <span class="date"><?php echo date("d/m/Y H:i", $item['date']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No se encontraron archivos que coincidan con "<?php echo htmlspecialchars($query); ?>".</p>
<?php endif; ?>

<a href="index.php">Volver</a>
</main>

</body>
</html>
