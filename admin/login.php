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
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
    <title>Login Admin</title>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-3xl font-bold text-center text-[#083d77] mb-6">
            Panel de Administración
        </h2>

        <?php if(isset($error)): ?>
            <p class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-sm mb-1">Usuario</label>
                <input 
                    type="text" 
                    name="usuario" 
                    required
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#083d77]"
                >
            </div>

            <div>
                <label class="block text-sm mb-1">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    required
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#083d77]"
                >
            </div>

            <button 
                type="submit"
                class="w-full bg-[#083d77] text-white py-2 rounded-lg hover:bg-[#062c56] transition"
            >
                Ingresar
            </button>

        </form>
    </div>

</body>
</html>