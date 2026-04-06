<?php
session_start();
include "../back/conexion.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/ui-animations.js" defer></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
    <title>Login Admin</title>
</head>

<body class="admin-ui min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md admin-panel p-7 md:p-8">

        <div class="text-center mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-blue-500 mb-2">Acceso seguro</p>
            <h2 class="text-3xl font-bold text-[#11468f]">
                Panel de Administración
            </h2>
            <p class="text-sm text-gray-500 mt-2">Ingresá con tus credenciales para continuar.</p>
        </div>

        <?php if(isset($error)): ?>
            <p class="bg-red-100 text-red-600 p-2 rounded-lg mb-4 text-sm font-medium">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Usuario</label>
                <input 
                    type="text" 
                    name="usuario" 
                    required
                    class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-[#11468f]"
                >
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    required
                    class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-[#11468f]"
                >
            </div>

            <button 
                type="submit"
                class="w-full bg-[#11468f] text-white py-2.5 rounded-lg font-semibold hover:bg-[#0d3a76] transition"
            >
                Ingresar
            </button>

        </form>
    </div>

</body>
</html>