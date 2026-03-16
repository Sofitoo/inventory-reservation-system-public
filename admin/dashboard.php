<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Panel Administrador</h1>

        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 transition">
            Cerrar sesión
        </a>
    </header>

    <!-- Contenido -->
    <main class="p-6">

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Card Productos -->
            <a href="productos.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold mb-2">📦 Productos</h2>
                <p class="text-gray-600 text-sm">Administrar productos y variantes.</p>
            </a>

            <!-- Card Reservas -->
            <a href="reservas.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold mb-2">🕒 Reservas</h2>
                <p class="text-gray-600 text-sm">Ver y gestionar reservas activas.</p>
            </a>

            <!-- Card Ventas -->
            <a href="historial.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h2 class="text-lg font-semibold mb-2">💰 Ventas Registro</h2>
                <p class="text-gray-600 text-sm">Historial de reservas.</p>
            </a>

        </div>

    </main>

</body>

</html>