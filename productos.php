<?php
include "components/header.php";
include "back/conexion.php";

$buscar = $_GET['buscar'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$genero = $_GET['genero'] ?? '';
?>

<?php include "components/navbar.php"; ?>



<!-- FILTROS -->
<section class="py-10 px-6 md:px-20 bg-black relative z-[120] overflow-visible">
    <div class="w-full relative overflow-visible">

        <form method="GET" class="flex flex-col md:flex-row gap-4 w-full relative z-[130] overflow-visible
             bg-gray-900 p-6 md:p-7 rounded-2xl border border-gray-800 shadow-2xl">

            <!-- Buscador -->
            <div class="flex-1">
                <input type="text" name="buscar" value="<?php echo htmlspecialchars($buscar); ?>"
                    placeholder="Buscar juego..." class="w-full px-4 py-3 rounded-xl border border-gray-300
                              focus:outline-none focus:ring-2 focus:ring-red-500
                              focus:border-red-500 transition">
            </div>

            <!-- DROPDOWN CATEGORIA -->
            <div class="relative w-full md:w-56 z-[140]">

                <button type="button" id="dropdownBtn" class="w-full px-4 h-[52px] rounded-xl border border-gray-700
                               bg-black text-white flex justify-between items-center
                               hover:border-red-500 transition">

                    <span id="selectedText" class="flex-1 text-left whitespace-nowrap overflow-hidden text-ellipsis">
                        <?php echo $categoria ? strtoupper($categoria) : "Todas las categorías"; ?>
                    </span>

                    <span>▼</span>
                </button>

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

                <input type="hidden" name="categoria" id="categoriaInput"
                    value="<?php echo htmlspecialchars($categoria); ?>">
            </div>


            <!-- DROPDOWN GENERO -->
            <div class="relative w-full md:w-56 z-[140]">

                <button type="button" id="dropdownGeneroBtn" class="w-full px-4 h-[52px] rounded-xl border border-gray-700
                               bg-black text-white flex justify-between items-center
                               hover:border-red-500 transition">

                    <span id="selectedGeneroText"
                        class="flex-1 text-left whitespace-nowrap overflow-hidden text-ellipsis">
                        <?= $genero ? ucfirst($genero) : "Todos los géneros"; ?>
                    </span>

                    <span>▼</span>
                </button>

                <div id="dropdownGeneroMenu" class="hidden absolute left-0 top-full mt-2 w-full bg-black border border-gray-700
                            rounded-xl shadow-xl overflow-hidden z-50">

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="">
                        Todos los géneros
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="Accion">
                        Acción
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="Aventura">
                        Aventura
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="Deportes">
                        Deportes
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="RPG">
                        RPG
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="Shooter">
                        Shooter
                    </div>

                    <div class="optionGenero px-4 py-3 cursor-pointer text-gray-300 hover:bg-gray-800 transition"
                        data-value="Supervivencia">
                        Supervivencia
                    </div>

                </div>

                <input type="hidden" name="genero" id="generoInput" value="<?= htmlspecialchars($genero); ?>">
            </div>


            <!-- Botón -->
            <button type="submit" class="bg-red-600 hover:bg-red-700 active:scale-95
                           text-white px-8 py-3 rounded-xl font-semibold
                           shadow-md hover:shadow-lg
                           transition-all duration-200">
                Filtrar
            </button>

        </form>

    </div>
</section>

<section class="px-6 md:px-20 py-12 bg-black">
    <div class="flex justify-between items-center mb-16">
    <h1 class="text-3xl md:text-4xl font-bold leading-tight pb-1
bg-clip-text text-transparent 
bg-gradient-to-r from-red-500 via-yellow-400 to-green-400 
drop-shadow-xl">
        Todos los Juegos
    </h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-7">

        <?php
        // QUERY BASE// FILTROS DINÁMICOS
        $sql = "
SELECT p.*, MIN(v.precio) AS precio_min
FROM productos p
JOIN variantes v ON p.id = v.producto_id
WHERE v.stock > 0
";

        $params = [];

        if ($buscar !== '') {
            $sql .= " AND p.nombre LIKE :buscar";
            $params[':buscar'] = "%$buscar%";
        }

        if ($categoria !== '') {
            $sql .= " AND p.categoria = :categoria";
            $params[':categoria'] = $categoria;
        }

        if ($genero !== '') {
            $sql .= " AND p.categoria_genero = :genero";
            $params[':genero'] = $genero;
        }

        $sql .= " GROUP BY p.id ORDER BY p.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="bg-gray-900 rounded-xl overflow-hidden hover:scale-105 transition duration-300 border border-white/10">

                <img src="uploads/<?php echo $producto['imagen']; ?>" class="w-full h-48 object-cover">

                <div class="p-4">
                    <h2 class="text-white font-semibold">
                        <?php echo $producto['nombre']; ?>
                    </h2>

                    <p class="text-red-500 font-bold mt-2">
                        $<?php echo number_format($producto['precio_min'], 0, ',', '.'); ?>
                    </p>

                    <a href="producto.php?id=<?php echo $producto['id']; ?>"
                        class="mt-3 block bg-red-600 text-center py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                        Ver producto
                    </a>
                </div>

            </div>
        <?php endwhile; ?>

    </div>

</section>

<?php include "components/footer.php"; ?>
<script src="assets/js/rgbbutton.js"></script>
</body>

</html>