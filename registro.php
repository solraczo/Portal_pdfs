<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $id    = trim($_POST['id'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "❌ Correo inválido";
    } elseif (!preg_match('/^(CC|NIT)_[0-9]{5,15}$/', $id)) {
        $mensaje = "❌ El usuario debe iniciar con CC_ o NIT_ seguido de números";
    } else {
        $usuariosFile = __DIR__ . '/usuarios.php';

        if (!file_exists($usuariosFile)) {
            file_put_contents($usuariosFile, "<?php\nreturn [];\n");
        }

        $usuarios = include $usuariosFile;

        if (isset($usuarios[$id])) {
            $mensaje = "⚠️ El usuario ya existe.";
        } else {
            // Generar contraseña aleatoria
            $password = bin2hex(random_bytes(4));
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Guardar usuario
            $usuarios[$id] = [
                'email' => $email,
                'password' => $hash
            ];
            file_put_contents($usuariosFile, "<?php\nreturn " . var_export($usuarios, true) . ";\n");

            // Enviar correo
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'excellentiaconsultingbpo.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'info@excellentiaconsultingbpo.com';
                $mail->Password   = 'Ba,[Q{pnWs4K'; // 🔹 CAMBIAR
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('info@excellentiaconsultingbpo.com', 'Portal Admin Excellentia');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Acceso al Portal de Administradores";
                $mail->Body    = "
                    <h2>Bienvenido al Portal de Administradores</h2>
                    <p><b>Usuario:</b> {$id}</p>
                    <p><b>Contraseña:</b> {$password}</p>
                    <p>Accede aquí: <a href='https://excellentiaconsultingbpo.com/Portal_pdfs/'>Portal Administradores</a></p>
                ";

                $mail->send();
                $mensaje = "✅ Usuario creado y credenciales enviadas al correo.";
            } catch (Exception $e) {
                $mensaje = "❌ Error al enviar correo: " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Registrar Nuevo Administrador</h2>
        <?php if ($mensaje): ?>
            <div class="mb-4 text-center text-sm font-semibold text-red-600">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Correo electrónico</label>
                <input type="email" name="email" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium">Número de identificación (CC_ o NIT_)</label>
                <input type="text" name="id" required class="w-full p-2 border rounded" placeholder="Ej: CC_1012200200">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Registrar</button>
        </form>
        <div class="mt-6 text-center">
            <a href="index.php" class="text-blue-600 hover:underline">⬅ Volver al login</a>
        </div>
    </div>
</body>
</html>
