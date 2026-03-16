<?php
session_start();
include "components/header.php";
include "back/conexion.php";

include "components/navbar.php";

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<head>
    <!-- Favicon -->
    <link rel="icon" href="/assets/img/palanca-de-mando.png" type="image/x-icon">
</head>


<div class="bg-black text-white min-h-screen px-6 py-10">

    <h1 class="text-3xl md:text-4xl font-extrabold mb-8 text-center
bg-clip-text text-transparent
bg-gradient-to-r from-red-500 via-orange-400 to-yellow-400
tracking-wide drop-shadow-xl">
        🛒 Tu Carrito de Compra
    </h1>

    <?php if (empty($carrito)): ?>

        <p class="text-gray-400">Tu carrito está vacío</p>

    <?php else: ?>

        <div class="space-y-4">
            <?php foreach ($carrito as $key => $item):
                $total += $item['precio'];
                ?>

                <div class="bg-zinc-900 p-5 rounded-xl 
            flex flex-col md:flex-row 
            gap-4 md:gap-6 md:items-center">

                    <!-- Imagen -->
                    <div class="w-full md:w-32">
                        <img src="uploads/<?php echo $item['imagen']; ?>" class="rounded-lg shadow-lg w-full object-cover">
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <h2 class="text-lg md:text-xl font-bold">
                            <?php echo $item['nombre']; ?>
                        </h2>

                        <p class="text-sm text-gray-400 mb-2">
                            <?php echo $item['descripcion']; ?>
                        </p>

                        <p class="text-sm">
                            Versión:
                            <span class="font-semibold">
                                <?php echo ucfirst($item['tipo']); ?>
                            </span>
                        </p>

                        <p class="text-xs text-green-400 mt-2">
                            ✔ Reservado por 24 horas
                        </p>
                    </div>

                    <!-- Precio + Botón -->
                    <div class="flex flex-col md:items-end gap-3 mt-2 md:mt-0">

                        <p class="text-red-500 font-bold text-lg md:text-xl">
                            $<?php echo number_format($item['precio'], 0, ',', '.'); ?>
                        </p>

                        <form action="back/eliminar-carrito.php" method="POST">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

                            <button class="flex items-center justify-center gap-2 
                       bg-red-600 hover:bg-red-700 
                       text-white text-sm px-4 py-2 
                       rounded-lg transition duration-200 
                       shadow-md hover:scale-105 w-full md:w-auto">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M5 7l1 13h12l1-13" />
                                </svg>

                                Eliminar
                            </button>
                        </form>

                    </div>

                </div>

            <?php endforeach; ?>

            <div class="mt-6 border-t border-zinc-700 pt-4 flex justify-between">
                <h2 class="text-xl font-bold">
                    Total:
                </h2>

                <h2 class="text-xl font-bold text-green-500">
                    $<?php echo number_format($total, 0, ',', '.'); ?>
                </h2>
            </div>

            <a href="back/procesar-compra.php"
                class="mt-6 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition">
                Confirmar compra
            </a>

        <?php endif; ?>

    </div>
    <?php include "components/footer.php"; ?>
    </body>

    </html>