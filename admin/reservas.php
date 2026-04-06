<?php
session_start();

// -------------------- MENSAJE --------------------
if (!empty($_SESSION['mensaje'])): ?>
    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
        <?php
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        ?>
    </div>
<?php endif;

include "../back/conexion.php";

// Traer reservas con datos del producto
$stmt = $pdo->query("
    SELECT r.*, 
           o.numero_orden,
           v.tipo,
           v.precio,
           p.nombre,
           p.imagen
    FROM reservas r
    JOIN variantes v ON r.variante_id = v.id
    JOIN productos p ON v.producto_id = p.id
    LEFT JOIN ordenes o ON r.orden_id = o.id
    ORDER BY r.fecha_reserva DESC
");

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/ui-animations.js" defer></script>
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<body class="admin-ui bg-zinc-900 text-white min-h-screen p-4 md:p-6">
    <main class="admin-shell space-y-6">
        <div class="admin-topbar flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-100">Gestión</p>
                <h1 class="text-3xl font-bold">Reservas</h1>
            </div>
            <a href="dashboard.php"
                class="inline-flex items-center justify-center bg-white/10 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white/20 transition">
                ← Volver al panel
            </a>
        </div>

        <?php if (empty($reservas)): ?>
            <div class="admin-panel p-8 text-center">
                <p class="text-gray-500 font-medium">No hay reservas registradas.</p>
            </div>
        <?php else: ?>
            <div class="space-y-5">
                <?php foreach ($reservas as $reserva):

                    if ($reserva['estado'] === 'pendiente' && strtotime($reserva['fecha_vencimiento']) < time()) {
                        $reserva['estado'] = 'vencida';
                    }

                    switch ($reserva['estado']) {
                        case 'pendiente':
                            $color = 'admin-chip warn';
                            break;
                        case 'pagado':
                            $color = 'admin-chip success';
                            break;
                        case 'vencida':
                            $color = 'admin-chip danger';
                            break;
                        default:
                            $color = 'admin-chip neutral';
                    }
                    ?>
                    <div class="admin-panel rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
                        <img src="../uploads/<?php echo $reserva['imagen']; ?>" class="w-full md:w-52 h-52 object-cover">

                        <div class="flex-1 p-5 flex flex-col md:flex-row gap-5 md:items-center md:justify-between">
                            <div class="space-y-1">
                                <h2 class="font-bold text-xl text-[#1c2d52]"><?php echo $reserva['nombre']; ?></h2>
                                <p class="text-sm text-gray-600">Orden: <?php echo $reserva['numero_orden'] ?? 'N/A'; ?></p>
                                <p class="text-sm text-gray-600">Versión: <?php echo ucfirst($reserva['tipo']); ?></p>
                                <p class="text-sm text-gray-600">Precio: $<?php echo number_format($reserva['precio'], 0, ',', '.'); ?>
                                </p>
                                <p class="text-xs text-gray-500">Reserva #: <?php echo $reserva['numero_reserva']; ?></p>
                                <p class="text-xs text-gray-500">Vence: <?php echo $reserva['fecha_vencimiento']; ?></p>
                                <p class="mt-2"><span class="<?php echo $color; ?>"><?php echo ucfirst($reserva['estado']); ?></span></p>
                            </div>

                            <?php if ($reserva['estado'] === 'pendiente'): ?>
                                <div class="w-full md:w-44 flex flex-col gap-2">
                                    <form action="aprobar-reserva.php" method="POST"
                                        onsubmit="return confirm('¿Confirmás aprobar esta reserva?')">
                                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-500 text-white py-2 rounded-lg text-center text-sm font-semibold w-full transition">
                                            Marcar como Pagado
                                        </button>
                                    </form>

                                    <form action="cancelar-reserva.php" method="POST"
                                        onsubmit="return confirm('¿Confirmás cancelar esta reserva?')">
                                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-500 text-white py-2 rounded-lg text-center text-sm font-semibold w-full transition">
                                            Cancelar
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>