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
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<div class="bg-white text-black dark:bg-white dark:text-black p-8 space-y-6">



    <body class="bg-zinc-900 text-white min-h-screen">

        <div class="p-8 space-y-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Reservas</h1>
                <a href="dashboard.php" class="text-blue-500 hover:underline text-sm">← Volver</a>
            </div>

            <?php if (empty($reservas)): ?>
                <p class="text-gray-100">No hay reservas registradas.</p>
            <?php else: ?>
                <div class="space-y-6">

                    <?php foreach ($reservas as $reserva):

                        // Verificar vencida
                        if ($reserva['estado'] === 'pendiente' && strtotime($reserva['fecha_vencimiento']) < time()) {
                            $reserva['estado'] = 'vencida';
                        }

                        // Color según estado
                        switch ($reserva['estado']) {
                            case 'pendiente':
                                $color = 'text-yellow-400';
                                break;
                            case 'pagado':
                                $color = 'text-green-400';
                                break;
                            case 'vencida':
                                $color = 'text-red-400';
                                break;
                            default:
                                $color = 'text-gray-400';
                        }

                        ?>
                        <!-- Card -->
                        <div
                            class="bg-zinc-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-200 flex flex-col md:flex-row">

                            <!-- Imagen -->
                            <img src="../uploads/<?php echo $reserva['imagen']; ?>"
                                class="w-full md:w-60 h-60 object-cover rounded-l-xl">

                            <!-- Info y botones -->
                            <div class="flex-1 p-4 flex flex-col justify-between">

                                <!-- Info principal -->
                                <div class="flex flex-col gap-1">
                                    <h2 class="font-bold text-xl truncate"><?php echo $reserva['nombre']; ?></h2>
                                    <p class="text-sm">Orden: <?php echo $reserva['numero_orden'] ?? 'N/A'; ?></p>
                                    <p class="text-sm">Versión: <?php echo ucfirst($reserva['tipo']); ?></p>
                                    <p class="text-sm">Precio: $<?php echo number_format($reserva['precio'], 0, ',', '.'); ?>
                                    </p>
                                    <p class="text-xs">Reserva #: <?php echo $reserva['numero_reserva']; ?></p>
                                    <p class="text-xs">Vence: <?php echo $reserva['fecha_vencimiento']; ?></p>
                                    <p class="font-bold <?php echo $color; ?> mt-2"><?php echo ucfirst($reserva['estado']); ?>
                                    </p>
                                </div>

                                <!-- Botones -->
                                <?php if ($reserva['estado'] === 'pendiente'): ?>
                                    <div class="mt-4 md:mt-0 flex md:flex-col gap-2 md:w-40 md:ml-auto">

                                        <!-- Aprobar reserva -->
                                        <form action="aprobar-reserva.php" method="POST"
                                            onsubmit="return confirm('¿Confirmás aprobar esta reserva?')">
                                            <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-500 text-white py-2 rounded text-center text-sm font-medium w-full transition">
                                                Marcar como Pagado
                                            </button>
                                        </form>

                                        <!-- Cancelar reserva -->
                                        <form action="cancelar-reserva.php" method="POST"
                                            onsubmit="return confirm('¿Confirmás cancelar esta reserva?')">
                                            <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-500 text-white py-2 rounded text-center text-sm font-medium w-full transition">
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
        </div>
</div>

</body>

</html>