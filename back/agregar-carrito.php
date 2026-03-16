<?php
session_start();
include "conexion.php";

if(!isset($_POST['variante_id'])){
    header("Location: ../index.php");
    exit;
}

$variante_id = intval($_POST['variante_id']);

$stmt = $pdo->prepare("
    SELECT v.*, p.nombre, p.imagen, p.descripcion
    FROM variantes v
    JOIN productos p ON v.producto_id = p.id
    WHERE v.id = ?
");
$stmt->execute([$variante_id]);
$variante = $stmt->fetch(PDO::FETCH_ASSOC);


if(!$variante){
    header("Location: ../index.php");
    exit;
}

// Verificar stock disponible
if($variante['stock'] <= 0){
    header("Location: ../producto.php?id=".$variante['producto_id']);
    exit;
}

// Bloquear en el back si no hay stock (por si alguien hace trampa con el HTML)
$variante_id = $_POST['variante_id'];

$stmt = $pdo->prepare("SELECT stock FROM variantes WHERE id = ?");
$stmt->execute([$variante_id]);
$stock_check = $stmt->fetch(PDO::FETCH_ASSOC);

if ($stock_check['stock'] <= 0) {
    die("Esta variante no tiene stock disponible.");
}

// Reservar stock (24h)
$pdo->prepare("
    UPDATE variantes 
    SET stock = stock - 1,
        reservado = reservado + 1
    WHERE id = ?
")->execute([$variante_id]);

// Generar número de reserva
$numero_reserva = 'RSV-' . time() . '-' . rand(100,999);

$fecha_reserva = date("Y-m-d H:i:s");
$fecha_vencimiento = date("Y-m-d H:i:s", time() + 86400); // 24h

$pdo->prepare("
    INSERT INTO reservas 
    (numero_reserva, variante_id, cantidad, fecha_reserva, fecha_vencimiento)
    VALUES (?, ?, ?, ?, ?)
")->execute([
    $numero_reserva,
    $variante_id,
    1,
    $fecha_reserva,
    $fecha_vencimiento
]);

// Crear carrito si no existe
if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

// Agregar producto a sesión
$_SESSION['carrito'][] = [
    "variante_id" => $variante['id'],
    "producto_id" => $variante['producto_id'],
    "nombre" => $variante['nombre'],
    "imagen" => $variante['imagen'],
    "descripcion" => $variante['descripcion'],
    "tipo" => $variante['tipo'],
    "precio" => $variante['precio'],
    "fecha_reserva" => time()
];

header("Location: ../carrito.php");
exit;