<?php
// Carpeta raíz de PDFs
$base_dir = __DIR__ . "/data_pdf_01/";

// Crear carpeta base si no existe
if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["pdf_file"])) {
    $file = $_FILES["pdf_file"];
    $file_name = basename($file["name"]);

    // Validar que sea PDF
    if ($file["type"] !== "application/pdf") {
        die("Error: Solo se permiten archivos PDF. <a href='index.php'>Volver</a>");
    }

    // Validar formato inicial: CC_######## o NIT_########
    $pattern = '/^(CC|NIT)_[0-9]+/i';
    if (!preg_match($pattern, $file_name, $matches)) {
        die("Error: El nombre del archivo debe comenzar con CC_######## o NIT_########. <a href='index.php'>Volver</a>");
    }

    // Carpeta destino con el prefijo del archivo
    $prefix = $matches[0]; // Ej: "CC_77000000" o "NIT_900123123"
    $target_folder = $base_dir . $prefix . "/";

    // Crear carpeta si no existe
    if (!file_exists($target_folder)) {
        mkdir($target_folder, 0777, true);
    }

    // Ruta final del archivo
    $target_path = $target_folder . $file_name;

    // Mover archivo
    if (move_uploaded_file($file["tmp_name"], $target_path)) {
        echo "Archivo subido correctamente a la carpeta <b>$prefix</b>. <a href='index.php'>Volver</a>";
    } else {
        echo "Error al subir el archivo. Revisa permisos de la carpeta.";
    }
} else {
    echo "No se envió ningún archivo.";
}
?>
