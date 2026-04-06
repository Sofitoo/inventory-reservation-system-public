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
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/ui-animations.js" defer></script>
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<body class="admin-ui bg-gray-100 min-h-screen p-4 md:p-6">
    <main class="admin-shell space-y-6">

        <header class="admin-topbar flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-blue-100">Level Up Store</p>
                <h1 class="text-2xl md:text-3xl font-bold">Panel Administrador</h1>
                <p class="text-sm text-blue-100/90 mt-1">Gestioná productos, reservas y ventas desde un único lugar.</p>
            </div>

            <a href="logout.php"
                class="inline-flex items-center justify-center bg-red-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                Cerrar sesión
            </a>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="admin-stat-card">
                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Módulo</p>
                <h2 class="text-xl font-bold text-[#11468f] mb-2">Productos</h2>
                <p class="text-sm text-gray-600 mb-4">Altas, edición, control de variantes y stock.</p>
                <a href="productos.php"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Ir a Productos
                </a>
            </div>

            <div class="admin-stat-card">
                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Módulo</p>
                <h2 class="text-xl font-bold text-[#11468f] mb-2">Reservas</h2>
                <p class="text-sm text-gray-600 mb-4">Seguimiento de reservas pendientes y acciones rápidas.</p>
                <a href="reservas.php"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Ir a Reservas
                </a>
            </div>

            <div class="admin-stat-card">
                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Módulo</p>
                <h2 class="text-xl font-bold text-[#11468f] mb-2">Historial</h2>
                <p class="text-sm text-gray-600 mb-4">Registro de operaciones y estado histórico de ventas.</p>
                <a href="historial.php"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Ir al Historial
                </a>
            </div>
        </section>
    </main>

</body>

</html>