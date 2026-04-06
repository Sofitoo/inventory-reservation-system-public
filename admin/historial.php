<?php
session_start();
include "../back/conexion.php";

// Paginación
$por_pagina = 10;
$pagina = $_GET['pagina'] ?? 1;
$inicio = ($pagina - 1) * $por_pagina;

// Traer historial
$stmt = $pdo->prepare("SELECT h.*, v.tipo, v.precio, p.nombre AS producto_nombre, o.numero_orden
                       FROM reservas_historial h
                       JOIN variantes v ON h.variante_id = v.id
                       JOIN productos p ON v.producto_id = p.id
                       LEFT JOIN ordenes o ON h.orden_id = o.id
                       ORDER BY h.creado_en DESC
                       LIMIT ?, ?");
$stmt->bindValue(1, (int) $inicio, PDO::PARAM_INT);
$stmt->bindValue(2, (int) $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar total de filas para paginación
$total_stmt = $pdo->query("SELECT COUNT(*) FROM reservas_historial");
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/ui-animations.js" defer></script>
    <link rel="icon" type="image/png" href="img/admin.png">
    <link rel="shortcut icon" href="img/admin.png">
</head>

<body class="admin-ui bg-zinc-100 text-zinc-900 min-h-screen p-4 md:p-6">
    <main class="admin-shell space-y-6">
        <div class="admin-topbar flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-100">Histórico</p>
                <h1 class="text-3xl font-bold">Historial de Reservas</h1>
            </div>
            <a href="dashboard.php"
                class="inline-flex items-center justify-center bg-white/10 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white/20 transition">
                ← Volver al panel
            </a>
        </div>

        <div class="admin-table-wrap bg-white shadow rounded overflow-hidden">
            <table class="w-full min-w-[980px] table-auto">
                <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">Reserva #</th>
                <th class="px-4 py-2 text-left">Número de Orden</th>
                <th class="px-4 py-2 text-left">Producto</th>
                <th class="px-4 py-2 text-left">Variante</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Precio</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Fecha Reserva</th>
            </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2"><?php echo $reserva['numero_reserva']; ?></td>
                            <td class="px-4 py-2"><?php echo $reserva['numero_orden']; ?></td>
                            <td class="px-4 py-2"><?php echo $reserva['producto_nombre']; ?></td>
                            <td class="px-4 py-2"><?php echo ucfirst($reserva['tipo']); ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $reserva['cantidad']; ?></td>
                            <td class="px-4 py-2 text-center">$<?php echo number_format($reserva['precio'], 0, ',', '.'); ?></td>
                            <td class="px-4 py-2 text-center">
                                <?php
                                switch ($reserva['estado']) {
                                    case 'pagado':
                                        echo '<span class="admin-chip success">Pagado</span>';
                                        break;
                                    case 'vencida':
                                        echo '<span class="admin-chip danger">Vencida</span>';
                                        break;
                                    case 'cancelada':
                                        echo '<span class="admin-chip neutral">Cancelada</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="px-4 py-2 text-center whitespace-nowrap"><?php echo $reserva['fecha_reserva']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-2 flex justify-center gap-2 flex-wrap">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>"
                    class="px-3 py-1.5 rounded-lg text-sm font-semibold <?php echo $i == $pagina ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </main>

</body>

</html>