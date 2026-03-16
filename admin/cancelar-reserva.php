<?php
session_start();
include "../back/conexion.php";

$id = $_POST['id'] ?? null;
if(!$id) die("ID de reserva inválido");

// Obtener reserva
$stmt = $pdo->prepare("SELECT * FROM reservas WHERE id = ?");
$stmt->execute([$id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$reserva) die("Reserva no encontrada");

// Obtener variante
$stmt = $pdo->prepare("SELECT * FROM variantes WHERE id = ?");
$stmt->execute([$reserva['variante_id']]);
$variante = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$variante) die("Variante no encontrada");

// Devolver cantidad a stock y restar de reservado
$nuevo_stock = $variante['stock'] + $reserva['cantidad'];
$nuevo_reservado = $variante['reservado'] - $reserva['cantidad'];

$stmt = $pdo->prepare("UPDATE variantes SET stock = ?, reservado = ? WHERE id = ?");
$stmt->execute([$nuevo_stock, $nuevo_reservado, $variante['id']]);

// Insertar en historial con estado cancelada o vencida
$stmt = $pdo->prepare("
    INSERT INTO reservas_historial 
    (numero_reserva, variante_id, cantidad, fecha_reserva, fecha_vencimiento, estado, orden_id)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $reserva['numero_reserva'],
    $reserva['variante_id'],
    $reserva['cantidad'],
    $reserva['fecha_reserva'],
    $reserva['fecha_vencimiento'],
    'cancelada',
    $reserva['orden_id']
]);

// Eliminar de tabla activa
$stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
$stmt->execute([$id]);

// -------------------- MENSAJE --------------------
$_SESSION['mensaje'] = "Reserva cancelada correctamente";

// Redirigir al panel
header("Location: reservas.php");
exit;