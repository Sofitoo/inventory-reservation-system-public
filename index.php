<?php
session_start();
include "back/conexion.php";

/* Filtro */
$buscar    = $_GET['buscar'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$genero    = $_GET['genero'] ?? '';

$sql = "SELECT * FROM productos WHERE 1=1";

if ($buscar) {
    $sql .= " AND nombre LIKE :buscar";
}

if ($categoria) {
    $sql .= " AND categoria = :categoria";
}

if ($genero) {
    // Aquí usamos la columna correcta
    $sql .= " AND categoria_genero = :genero";
}

$stmt = $pdo->prepare($sql);

if ($buscar) {
    $stmt->bindValue(':buscar', "%$buscar%");
}

if ($categoria) {
    $stmt->bindValue(':categoria', $categoria);
}

if ($genero) {
    $stmt->bindValue(':genero', $genero);
}

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* QUERY PRODUCTOS DESTACADOS */
$result = $pdo->query("
    SELECT p.*, MIN(v.precio) as precio
    FROM productos p
    JOIN variantes v ON p.id = v.producto_id
    WHERE p.destacado = 1
    AND v.stock > 0
    GROUP BY p.id
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level Up Store Games</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/rgbbutton.css">
    <script src="assets/js/ui-animations.js" defer></script>

    <!-- Favicon -->
    <link rel="icon" href="assets/img/palanca-de-mando.png" type="image/x-icon">
</head>

<body class="bg-gray-100 text-gray-900 store-ui">

    <!-- Navbar -->
    <?php include("components/navbar.php"); ?>

    <!-- Buscador -->
    <section class="bg-white py-8 px-4 shadow-sm border-b border-gray-800/40 relative z-[120] overflow-visible">
        <div class="max-w-6xl mx-auto relative overflow-visible">

            <form action="productos.php" method="GET" class="flex flex-col md:flex-row gap-3 items-stretch relative z-[130] overflow-visible">

                <!-- Input -->
                <div class="flex-1">
                    <input type="text" name="buscar" placeholder="Buscar juego..." class="w-full px-4 py-3 rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-red-500
                           focus:border-red-500 transition">
                </div>

                <!-- Select estilizado -->
                <div class="relative w-full md:w-56 z-[140]">

                    <!-- Botón -->
                    <button type="button" id="dropdownBtn" class="w-full px-4 h-[52px] rounded-xl border border-gray-700
               bg-black text-white flex justify-between items-center
               hover:border-red-500 transition">
                        <span id="selectedText" class="flex-1 text-left whitespace-nowrap overflow-hidden text-ellipsis">
                            <?php echo $categoria ? $categoria : "Todas las categorías"; ?>
                        </span>
                        <span>▼</span>
                    </button>

                    <!-- Opciones -->
                    <div id="dropdownMenu" class="hidden absolute left-0 top-full mt-2 w-full bg-black border border-gray-700
                rounded-xl shadow-xl overflow-hidden z-50">

                        <div class="option px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                            data-value="">
                            Todas las categorías
                        </div>

                        <div class="option px-4 py-3 cursor-pointer text-blue-400 hover:bg-gray-800 transition glow"
                            data-value="ps4">
                            PS4
                        </div>

                        <div class="option px-4 py-3 cursor-pointer text-purple-400 hover:bg-gray-800 transition glow"
                            data-value="ps5">
                            PS5
                        </div>

                        <div class="option px-4 py-3 cursor-pointer text-green-400 hover:bg-gray-800 transition glow"
                            data-value="xbox one">
                            Xbox One
                        </div>

                        <div class="option px-4 py-3 cursor-pointer text-yellow-400 hover:bg-gray-800 transition glow"
                            data-value="xbox 360">
                            Xbox 360
                        </div>

                    </div>



                </div>

                <!-- Género -->
                <!-- Dropdown Género estilizado -->
<div class="relative w-full md:w-56 z-[140]">

    <!-- Botón -->
    <button type="button" id="dropdownGeneroBtn" 
            class="w-full px-4 h-[52px] rounded-xl border border-gray-700
                   bg-black text-white flex justify-between items-center
                   hover:border-red-500 transition">
        <span id="selectedGeneroText" class="whitespace-nowrap overflow-hidden text-ellipsis">
            <?= $genero ? ucfirst($genero) : "Todos los géneros"; ?>
        </span>
        <span>▼</span>
    </button>

    <!-- Opciones -->
    <div id="dropdownGeneroMenu" class="hidden absolute left-0 top-full mt-2 w-full bg-black border border-gray-700
                rounded-xl shadow-xl overflow-hidden z-50">

        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="">
            Todos los géneros
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="Accion">
            Acción
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="Aventura">
            Aventura
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="Deportes">
            Deportes
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="RPG">
            RPG
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="Shooter">
            Shooter
        </div>
        <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition" data-value="Supervivencia">
            Supervivencia
        </div>

    </div>

    <!-- Input oculto para enviar al backend -->
    <input type="hidden" name="genero" id="generoInput" value="<?= htmlspecialchars($genero); ?>">

</div>

                <!-- Input oculto para categoría -->
                <input type="hidden" name="categoria" id="categoriaInput"
                    value="<?php echo htmlspecialchars($categoria); ?>">


                <!-- Botón -->
                <button type="submit" class="bg-red-600 hover:bg-red-700 active:scale-95
                       text-white px-8 py-3 rounded-xl font-semibold
                       shadow-md hover:shadow-lg
                       transition-all duration-200">
                    Buscar
                </button>

            </form>

        </div>
    </section>


    <!-- Banner -->
    <?php include("components/banner.php"); ?>

    <!-- Productos -->
    <section class="py-14 px-4 bg-gray-100">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">
                    Productos Destacados
                </h2>

                <a href="productos.php" class="text-red-500 hover:text-red-400 font-semibold">
                    Ver todos →
                </a>

            </div>

            <!-- Carousel -->
            <div class="flex gap-6 overflow-x-auto scroll-smooth pb-4">

                <?php while ($producto = $result->fetch(PDO::FETCH_ASSOC)): ?>

                    <div
                        class="min-w-[190px] md:min-w-[240px] bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 cursor-pointer border border-gray-700/40">

                        <img src="uploads/<?php echo $producto['imagen']; ?>"
                            class="w-full h-48 object-cover rounded-t-xl">

                        <div class="p-4">
                            <h3 class="font-semibold text-sm md:text-base">
                                <?php echo $producto['nombre']; ?>
                            </h3>

                            <p class="text-red-600 font-bold mt-2">
                                $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>
                            </p>

                            <button
                                class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition">
                                <a href="producto.php?id=<?php echo $producto['id']; ?>">
                                    Agregar al carrito
                            </button>
                        </div>

                    </div>

                <?php endwhile; ?>

            </div>


        </div>
    </section>


    <?php include("components/footer.php"); ?>

    <!-- Script para dropdown -->
    <script src="assets/js/rgbbutton.js"></script>

    <!-- Script para los filtros 
    <script src="assets/js/filtros.js"></script>-->

</body>

</html>