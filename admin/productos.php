<?php
session_start();
include "../back/conexion.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$accion = $_GET['accion'] ?? null;

/* ==========================
   ELIMINAR
========================== */
if ($accion === 'eliminar' && isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = $_GET['id'];

    // borrar variantes primero
    $stmt = $pdo->prepare("DELETE FROM variantes WHERE producto_id = ?");
    $stmt->execute([$id]);

    // borrar producto
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: productos.php");
    exit;
}

/* ==========================
   CREAR
========================== */
if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreImagen = null;

    // manejo imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $permitidas)) {
            die("Formato no permitido");
        }

        $nombreImagen = uniqid() . "." . $extension;
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../uploads/" . $nombreImagen);
    }

    // insertar producto
    $stmt = $pdo->prepare("
        INSERT INTO productos 
        (nombre, descripcion, categoria, categoria_genero, destacado, descuento, imagen) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['categoria'],
        $_POST['categoria_genero'],
        isset($_POST['destacado']) ? 1 : 0,
        isset($_POST['descuento']) ? 1 : 0,
        $nombreImagen
    ]);

    $productoId = $pdo->lastInsertId();

    // variantes...
    $stmt = $pdo->prepare("INSERT INTO variantes (producto_id, tipo, precio, stock) VALUES (?, 'digital', ?, ?)");
    $stmt->execute([$productoId, $_POST['precio_digital'], $_POST['stock_digital']]);

    $stmt = $pdo->prepare("INSERT INTO variantes (producto_id, tipo, precio, stock) VALUES (?, 'fisico', ?, ?)");
    $stmt->execute([$productoId, $_POST['precio_fisico'], $_POST['stock_fisico']]);

    header("Location: productos.php");
    exit;
}


/* ==========================
   EDITAR (MODAL + AJAX)
========================== */
if ($accion === 'editar' && isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = $_GET['id'];

    // ==========================
    // 1️⃣ SI ES PETICIÓN JSON (para llenar el modal)
    // ==========================
    if (isset($_GET['json']) && $_GET['json'] == 1) {

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM variantes WHERE producto_id = ?");
        $stmt->execute([$id]);
        $variantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $digital = null;
        $fisico = null;

        foreach ($variantes as $v) {
            if ($v['tipo'] === 'digital')
                $digital = $v;
            if ($v['tipo'] === 'fisico')
                $fisico = $v;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'producto' => $producto,
            'digital' => $digital,
            'fisico' => $fisico
        ]);
        exit;
    }

    // ==========================
    // 2️⃣ SI ENVÍA FORMULARIO (POST)
    // ==========================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Traer producto actual (para mantener imagen si no cambia)
        $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $productoActual = $stmt->fetch(PDO::FETCH_ASSOC);

        $nombreImagen = $productoActual['imagen'];

        // Si sube nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {

            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $permitidas)) {
                die("Formato no permitido");
            }

            $nombreImagen = uniqid() . "." . $extension;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../uploads/" . $nombreImagen);
        }

        // Actualizar producto
        $stmt = $pdo->prepare("
            UPDATE productos 
            SET nombre=?, descripcion=?, categoria=?, categoria_genero=?, destacado=?, descuento=?, imagen=?
            WHERE id=?
        ");

        $stmt->execute([
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['categoria'],
            $_POST['categoria_genero'],
            isset($_POST['destacado']) ? 1 : 0,
            isset($_POST['descuento']) ? 1 : 0,
            $nombreImagen,
            $id
        ]);

        // Actualizar variante digital
        $stmt = $pdo->prepare("
            UPDATE variantes 
            SET precio=?, stock=? 
            WHERE producto_id=? AND tipo='digital'
        ");

        $stmt->execute([
            $_POST['precio_digital'],
            $_POST['stock_digital'],
            $id
        ]);

        // Actualizar variante física
        $stmt = $pdo->prepare("
            UPDATE variantes 
            SET precio=?, stock=? 
            WHERE producto_id=? AND tipo='fisico'
        ");

        $stmt->execute([
            $_POST['precio_fisico'],
            $_POST['stock_fisico'],
            $id
        ]);

        header("Location: productos.php");
        exit;
    }
}

/* ==========================
   LISTADO
========================== */
$buscar = $_GET['buscar'] ?? '';

if ($buscar) {
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.nombre,
            vd.precio AS precio_digital,
            vf.precio AS precio_fisico,
            vd.stock AS stock_digital,
            vd.reservado AS reservado_digital,
            vf.stock AS stock_fisico,
            vf.reservado AS reservado_fisico
        FROM productos p
        LEFT JOIN variantes vd 
            ON p.id = vd.producto_id AND vd.tipo = 'digital'
        LEFT JOIN variantes vf 
            ON p.id = vf.producto_id AND vf.tipo = 'fisico'
        WHERE p.nombre LIKE ? OR p.descripcion LIKE ?
        ORDER BY p.id DESC
    ");
    $stmt->execute(["%$buscar%", "%$buscar%"]);
} else {
    $stmt = $pdo->query("
        SELECT 
            p.id,
            p.nombre,
            vd.precio AS precio_digital,
            vf.precio AS precio_fisico,
            vd.stock AS stock_digital,
            vd.reservado AS reservado_digital,
            vf.stock AS stock_fisico,
            vf.reservado AS reservado_fisico
        FROM productos p
        LEFT JOIN variantes vd 
            ON p.id = vd.producto_id AND vd.tipo = 'digital'
        LEFT JOIN variantes vf 
            ON p.id = vf.producto_id AND vf.tipo = 'fisico'
        ORDER BY p.id DESC;
    ");
}
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Productos</h1>
            <a href="dashboard.php" class="text-sm text-blue-600 hover:underline">← Volver</a>
        </div>

        <!-- MODAL PRODUCTO -->
        <div id="modalProducto" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto">

            <div class="min-h-screen flex items-center justify-center p-4">

                <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl
                    max-h-[90vh] overflow-y-auto relative p-6">

                    <!-- Botón cerrar -->
                    <button id="cerrarModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">
                        &times;
                    </button>

                    <h2 id="modalTitulo" class="text-xl font-semibold mb-6">
                        Crear producto
                    </h2>

                    <form id="formProducto" method="POST" enctype="multipart/form-data" class="space-y-6">

                        <!-- DATOS GENERALES -->
                        <div class="grid md:grid-cols-2 gap-6">

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Nombre</label>
                                <input type="text" name="nombre"
                                    class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Descripción</label>
                                <textarea name="descripcion"
                                    class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                    required></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Imagen</label>

                                <input type="file" name="imagen" class="w-full border rounded-xl px-4 py-2"
                                    accept="image/*">

                                <!-- Vista previa imagen actual -->
                                <div id="imagenActualContainer" class="mt-3 hidden">
                                    <p class="text-sm text-gray-500 mb-2">Imagen actual:</p>
                                    <img id="imagenActual" src=""
                                        class="w-32 h-32 object-cover rounded-lg border shadow">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Categoría</label>
                                <input type="text" name="categoria" required class="w-full border rounded-xl px-4 py-2">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Género</label>
                                <input type="text" name="categoria_genero" required
                                    class="w-full border rounded-xl px-4 py-2">
                            </div>

                            <div class="md:col-span-2 flex items-center space-x-6">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="destacado" value="1">
                                    <span>Destacado</span>
                                </label>

                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="descuento" value="1">
                                    <span>En descuento</span>
                                </label>
                            </div>

                        </div>

                        <!-- VARIANTES -->
                        <div class="border-t pt-6">

                            <h3 class="font-semibold mb-4 text-gray-700">
                                Variantes
                            </h3>

                            <div class="grid md:grid-cols-2 gap-6">

                                <!-- DIGITAL -->
                                <div class="bg-gray-50 p-4 rounded-xl border">
                                    <h4 class="font-medium mb-3 text-blue-600">Digital</h4>

                                    <label class="block text-sm mb-1">Precio</label>
                                    <input type="number" step="0.01" name="precio_digital"
                                        class="w-full border rounded-lg px-3 py-2 mb-3" required>

                                    <label class="block text-sm mb-1">Stock</label>
                                    <input type="number" name="stock_digital" class="w-full border rounded-lg px-3 py-2"
                                        required>
                                </div>

                                <!-- FÍSICO -->
                                <div class="bg-gray-50 p-4 rounded-xl border">
                                    <h4 class="font-medium mb-3 text-green-600">Físico</h4>

                                    <label class="block text-sm mb-1">Precio</label>
                                    <input type="number" step="0.01" name="precio_fisico"
                                        class="w-full border rounded-lg px-3 py-2 mb-3" required>

                                    <label class="block text-sm mb-1">Stock</label>
                                    <input type="number" name="stock_fisico" class="w-full border rounded-lg px-3 py-2"
                                        required>
                                </div>

                            </div>

                        </div>

                        <div class="pt-4">
                            <button class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition">
                                Guardar producto
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <form method="GET" class="mb-6 flex items-center space-x-3">
            <input type="text" name="buscar" placeholder="Buscar producto..."
                value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                class="border rounded-xl px-4 py-2 w-full focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
                Buscar
            </button>
        </form>

        <div class="bg-white rounded-xl shadow overflow-x-auto">

            <table class="min-w-full text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Producto</th>
                        <th class="p-3 text-left">Digital</th>
                        <th class="p-3 text-left">Físico</th>
                        <th class="p-3 text-left">Stock Digital</th>
                        <th class="p-3 text-left">Stock Físico</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($productos as $p): ?>
                        <tr class="border-t">

                            <!-- Nombre -->
                            <td class="p-3 font-medium">
                                <?= htmlspecialchars($p['nombre']) ?>
                            </td>

                            <!-- Precio Digital -->
                            <td class="p-3">
                                <?php if ($p['precio_digital'] !== null): ?>
                                    $<?= number_format($p['precio_digital'], 2) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>

                            <!-- Precio Físico -->
                            <td class="p-3">
                                <?php if ($p['precio_fisico'] !== null): ?>
                                    $<?= number_format($p['precio_fisico'], 2) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>



                            <!-- Stock Digital -->
                            <?php
                            $stockDigital = ($p['stock_digital'] ?? 0);
                            $reservadoDigital = ($p['reservado_digital'] ?? 0);
                            $stockDigitalReal = max(0, $stockDigital - $reservadoDigital);

                            if ($stockDigitalReal <= 0) {
                                $colorDigital = "bg-red-100 text-red-700";
                            } elseif ($stockDigitalReal < 5) {
                                $colorDigital = "bg-yellow-100 text-yellow-700";
                            } else {
                                $colorDigital = "bg-green-100 text-green-700";
                            }
                            ?>

                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $colorDigital ?>">
                                    <?= $stockDigitalReal ?>
                                </span>
                            </td>

                            <!-- Stock Físico -->
                            <?php
                            $stockFisico = ($p['stock_fisico'] ?? 0);
                            $reservadoFisico = ($p['reservado_fisico'] ?? 0);
                            $stockFisicoReal = max(0, $stockFisico - $reservadoFisico);

                            if ($stockFisicoReal <= 0) {
                                $colorFisico = "bg-red-100 text-red-700";
                            } elseif ($stockFisicoReal < 5) {
                                $colorFisico = "bg-yellow-100 text-yellow-700";
                            } else {
                                $colorFisico = "bg-green-100 text-green-700";
                            }
                            ?>

                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $colorFisico ?>">
                                    <?= $stockFisicoReal ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="p-3 space-x-3">
                                <button class="text-blue-600 hover:underline editarBtn" data-id="<?= $p['id'] ?>">
                                    Editar
                                </button>

                                <a href="productos.php?accion=eliminar&id=<?= $p['id'] ?>"
                                    onclick="return confirm('¿Eliminar producto?')" class="text-red-600 hover:underline">
                                    Eliminar
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Botón crear -->
        <button id="abrirModalCrear"
            class="inline-block mt-6 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            + Nuevo producto
        </button>



    </div>

    <script src="/tienda-gamer/assets/js/modal-admin.js"></script>
</body>

</html>