<?php
session_start();

// Cargar usuarios con contraseñas hasheadas
$usuarios = require __DIR__ . '/usuarios.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($usuarios[$usuario]) && password_verify($password, $usuarios[$usuario])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Administrador - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .card-shadow { box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl p-8 card-shadow">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Portal Administrador</h1>
                <p class="text-gray-500 mt-2">Inicia sesión con tu cuenta</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="mb-6 p-4 rounded-lg text-center bg-red-50 text-red-700">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Usuario</label>
                    <input
                        type="text"
                        name="usuario"
                        required
                        class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Usuario"
                    >
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="********"
                    >
                </div>
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition transform hover:scale-105"
                >
                    Ingresar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    ¿Nuevo administrador?
                    <a href="registro.php" class="text-blue-600 hover:underline font-medium">Registrar nuevo admin</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
