<?php
session_start();
include "conexion.php";

if(!isset($_POST['key'])){
    header("Location: ../carrito.php");
    exit;
}

$key = $_POST['key'];

if(!isset($_SESSION['carrito'][$key])){
    header("Location: ../carrito.php");
    exit;
}

$item = $_SESSION['carrito'][$key];

// Restaurar stock
$pdo->prepare("
    UPDATE variantes
    SET stock = stock + 1,
        reservado = reservado - 1
    WHERE id = ?
")->execute([$item['variante_id']]);

// Eliminar de sesión
$_SESSION['carrito'] = array_values($_SESSION['carrito']);
unset($_SESSION['carrito'][$key]);

header("Location: ../carrito.php");
exit;