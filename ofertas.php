<?php
session_start();
include "components/header.php";
include "back/conexion.php";
?>
<?php include "components/header.php"; ?>
<?php include "components/navbar.php"; ?>

<head>
    <title>Ofertas - Level Up Store Games</title>
</head>

<body>
    <section class="px-6 md:px-20 py-12 bg-black min-h-screen">

        <div class="flex justify-between items-center mb-6">
            <h1
                class="text-3xl md:text-4xl font-bold mb-8 bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-yellow-400 to-green-400 drop-shadow-xl">
                Ofertas Especiales
            </h1>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-7">

            <?php

            $sql = "
                SELECT p.*, MIN(v.precio) AS precio_min
                FROM productos p
                JOIN variantes v ON p.id = v.producto_id
                WHERE v.stock > 0
                AND p.descuento > 0
                GROUP BY p.id";


            $params = [];
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
</body>

</html>