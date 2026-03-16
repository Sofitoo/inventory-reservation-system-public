<?php
include "components/header.php";
include "back/conexion.php";
include "components/navbar.php";

// Comprobamos si se recibió un id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-white p-6'>Producto no encontrado.</p>";
    exit;
}

$id = intval($_GET['id']); // seguridad básica

// Traemos el producto de la DB, separado porque esta en tablas distintas (productos y variantes)
// 1️⃣ Producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// 2️⃣ Variantes
$stmt2 = $pdo->prepare("SELECT * FROM variantes WHERE producto_id = ?");
$stmt2->execute([$id]);
$variantes = $stmt2->fetchAll(PDO::FETCH_ASSOC);



if (!$producto) {
    echo "<p class='text-white p-6'>Producto no encontrado.</p>";
    exit;
}
?>

<section class="px-6 md:px-20 py-12 bg-black min-h-screen text-white">

    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-10">

        <!-- Imagen del producto -->
        <div>
            <img src="uploads/<?php echo $producto['imagen']; ?>" class="w-full rounded-xl shadow-lg">
        </div>

        <!-- Información del producto -->
        <div class="flex flex-col justify-between">

            <div>
                <h1 class="text-3xl font-bold mb-4"><?php echo $producto['nombre']; ?></h1>

                <p class="text-gray-300 mb-4"><?php echo $producto['descripcion']; ?></p>

                <p class="text-red-500 font-bold text-2xl mb-6">
                    <?php if (!empty($variantes)): ?>
    $<?php echo number_format($variantes[0]['precio'], 0, ',', '.'); ?>
<?php endif; ?>
                </p>


                <div id="alertaVariante"
                    class="my-8 py-3 px-4 bg-red-900/20 rounded-lg text-center text-red-400 font-bold text-sm latido">
                    ⚠️ Elegí una versión antes de agregar al carrito
                </div>

                <!-- Formulario para agregar al carrito -->

                <form method="POST" action="/tienda-gamer/back/agregar-carrito.php">

                    <?php 
$tieneStock = false;
foreach ($variantes as $v) {
    if ($v['stock'] > 0) {
        $tieneStock = true;
        break;
    }
}
?>
                <?php foreach ($variantes as $variante): ?>
    <label class="mr-4 block mb-2">
        <input 
            type="radio" 
            name="variante_id" 
            value="<?php echo $variante['id']; ?>" 
            <?php if ($variante['stock'] <= 0) { echo 'disabled'; } ?>
            required
        >
        
        <?php echo ucfirst($variante['tipo']); ?>
        ($<?php echo number_format($variante['precio'], 0, ',', '.'); ?>)

        <?php if ($variante['stock'] <= 0): ?>
            <span class="text-red-500 font-bold ml-2">¡Sin stock!</span>
        <?php endif; ?>
    </label>
<?php endforeach; ?>
                    <button type="submit"
    class="bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg transition mt-6 disabled:opacity-50"
    <?php echo !$tieneStock ? 'disabled' : ''; ?>>
    <?php echo !$tieneStock ? 'Sin stock' : 'Agregar al carrito'; ?>
</button>


                </form>
            </div>

        </div>

    </div>

</section>

<?php include "components/footer.php"; ?>
</body>

</html>