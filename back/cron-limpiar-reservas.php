<?php
include "../back/conexion.php";

// Buscar reservas vencidas
$stmt = $pdo->prepare("
    SELECT *
    FROM reservas
    WHERE fecha_vencimiento <= NOW()
");
$stmt->execute();
$reservas = $stmt->fetchAll();

foreach ($reservas as $reserva) {

    // devolver stock
    $pdo->prepare("
        UPDATE variantes
        SET stock = stock + ?,
            reservado = reservado - ?
        WHERE id = ?
    ")->execute([
        $reserva['cantidad'],
        $reserva['cantidad'],
        $reserva['variante_id']
    ]);

    // mover a historial
    $pdo->prepare("
        INSERT INTO reservas_historial
        (numero_reserva, variante_id, cantidad, fecha_reserva, fecha_vencimiento, estado, orden_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ")->execute([
        $reserva['numero_reserva'],
        $reserva['variante_id'],
        $reserva['cantidad'],
        $reserva['fecha_reserva'],
        $reserva['fecha_vencimiento'],
        'vencida',
        $reserva['orden_id']
    ]);

    // eliminar de tabla activa
    $pdo->prepare("
        DELETE FROM reservas
        WHERE id = ?
    ")->execute([$reserva['id']]);
}

echo "Cron ejecutado correctamente";