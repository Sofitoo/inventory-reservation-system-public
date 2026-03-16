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
if($reserva['estado'] !== 'pendiente') die("La reserva ya fue procesada");

// Obtener variante
$stmt = $pdo->prepare("SELECT * FROM variantes WHERE id = ?");
$stmt->execute([$reserva['variante_id']]);
$variante = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$variante) die("Variante no encontrada");

// Actualizar stock y reservado
$nuevo_stock = $variante['stock'] - $reserva['cantidad'];
$nuevo_reservado = $variante['reservado'] - $reserva['cantidad'];

if($nuevo_stock < 0) die("No hay suficiente stock");

$stmt = $pdo->prepare("UPDATE variantes SET stock = ?, reservado = ? WHERE id = ?");
$stmt->execute([$nuevo_stock, $nuevo_reservado, $variante['id']]);

// Marcar reserva como pagada
$stmt = $pdo->prepare("UPDATE reservas SET estado = 'pagado' WHERE id = ?");
$stmt->execute([$id]);

// Insertar en historial
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
    'pagado',
    $reserva['orden_id']
]);

// Opcional: eliminar de tabla activa
$stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
$stmt->execute([$id]);

// -------------------- MENSAJE --------------------
$_SESSION['mensaje'] = "Reserva aprobada correctamente";

// Redirigir al panel
header("Location: reservas.php");
exit;