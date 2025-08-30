<?php
session_start();

// Si ya está logueado → mostrar buscador/admin
if (isset($_SESSION['admin'])) {
    mostrarPanel();
    exit;
}

// Si se envió el formulario de login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST['id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $usuariosFile = __DIR__ . '/usuarios.php';
    if (file_exists($usuariosFile)) {
        $usuarios = include $usuariosFile;

        if (isset($usuarios[$id]) && password_verify($password, $usuarios[$id]['password'])) {
            $_SESSION['admin'] = $id;
            mostrarPanel();
            exit;
        } else {
            $error = "❌ Usuario o contraseña incorrectos";
        }
    } else {
        $error = "❌ No existen administradores registrados aún.";
    }
}

// Función para mostrar el login
function mostrarLogin($error = "")
{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal de Administradores</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>
    <body class="bg-gray-100 flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Login Administradores</h2>
            <?php if ($error): ?>
                <div class="mb-4 text-center text-sm font-semibold text-red-600">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Usuario (CC_ o NIT_)</label>
                    <input type="text" name="id" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium">Contraseña</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Ingresar</button>
            </form>
            <div class="mt-6 text-center space-y-2">
                <a href="registro.php" class="text-blue-600 hover:underline">📝 Registrar nuevo admin</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Función para mostrar el panel administrador
function mostrarPanel()
{
    $admin = $_SESSION['admin'];
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal de Administradores</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>
    <body class="bg-gray-100 min-h-screen">
        <div class="bg-white p-6 shadow-lg flex justify-between items-center">
            <h1 class="text-xl font-bold text-blue-800">Portal de Administradores</h1>
            <div>
                <span class="mr-4">👤 <?= htmlspecialchars($admin) ?></span>
                <a href="logout.php" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Cerrar sesión</a>
            </div>
        </div>

        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Subir nuevo archivo PDF</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-4 mb-6">
                <input type="file" name="pdf_file" accept="application/pdf" required class="block w-full border p-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Subir PDF</button>
            </form>

            <h2 class="text-lg font-semibold mb-4">Buscar archivos PDF</h2>
            <form action="search.php" method="get" class="space-y-4">
                <input type="text" name="query" placeholder="Buscar por nombre" class="w-full p-2 border rounded">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Buscar</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

// Si no está logueado → mostrar login
mostrarLogin($error ?? "");
