<?php
session_start();
require('../libs/fpdf.php');
require('conexion.php');

if(empty($_SESSION['carrito'])){
    header("Location: ../carrito.php");
    exit;
}

$carrito = $_SESSION['carrito'];
$total = 0;

try {
    $pdo->beginTransaction();

    // 🔒 Validación y reserva real del stock
    foreach($carrito as $item){
        $stmt = $pdo->prepare("
            UPDATE variantes
            SET stock = stock - 1
            WHERE id = ? AND stock > 0
        ");
        $stmt->execute([$item['variante_id']]);
        if($stmt->rowCount() === 0){
            throw new Exception("El producto {$item['nombre']} no tiene stock disponible.");
        }
    }

    $pdo->commit();
} catch(Exception $e){
    $pdo->rollBack();
    die($e->getMessage());
}

// Calcular total
foreach($carrito as $item){
    $total += $item['precio'];
}

// Obtener número de orden incremental
$stmt = $pdo->query("SELECT MAX(numero_orden) as ultima FROM ordenes");
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$numeroOrden = $resultado['ultima'] ? $resultado['ultima'] + 1 : 1000;

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../assets/img/logo.png',10,10,40);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'LEVEL UP STORE GAMES',0,1,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,6,'Orden #'.$numeroOrden,0,1,'R');
$pdf->Cell(0,6,'Fecha: '.date("d/m/Y H:i"),0,1,'R');
$pdf->Ln(20);

$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(40,40,40);
$pdf->SetTextColor(255);
$pdf->Cell(80,10,'Producto',1,0,'C',true);
$pdf->Cell(40,10,'Version',1,0,'C',true);
$pdf->Cell(40,10,'Precio',1,1,'C',true);

$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
foreach($carrito as $item){
    $pdf->Cell(80,10,$item['nombre'],1);
    $pdf->Cell(40,10,$item['tipo'],1);
    $pdf->Cell(40,10,'$'.number_format($item['precio'],0,',','.'),1,1);
}

$pdf->SetFont('Arial','B',14);
$pdf->Cell(120,10,'TOTAL',1);
$pdf->Cell(40,10,'$'.number_format($total,0,',','.'),1,1);

$nombreArchivo = "orden_".$numeroOrden.".pdf";
$ruta = "../ordenes/".$nombreArchivo;
$pdf->Output('F', $ruta);

// Guardar orden en DB
$pdo->prepare("
    INSERT INTO ordenes (numero_orden, total, archivo, estado)
    VALUES (?, ?, ?, 'pendiente')
")->execute([$numeroOrden, $total, $nombreArchivo]);

$ordenId = $pdo->lastInsertId(); // <-- ID de la orden recién creada

// Asociar todas las reservas pendientes del carrito a esta orden
// --- Después de crear la orden y obtener $ordenId ---

foreach ($carrito as $item) {
    // Asociar todas las reservas pendientes del producto comprado
    $stmt = $pdo->prepare("
        UPDATE reservas
        SET orden_id = ?
        WHERE variante_id = ? 
          AND estado = 'pendiente'
          AND orden_id IS NULL
          AND fecha_reserva >= NOW() - INTERVAL 1 DAY
    ");
    $stmt->execute([$ordenId, $item['variante_id']]);
}

// Guardar detalle de orden
foreach($carrito as $item){
    $pdo->prepare("
        INSERT INTO orden_detalle 
        (orden_id, producto_id, nombre_producto, tipo, variante_id, precio, cantidad)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ")->execute([
        $ordenId,
        $item['producto_id'],
        $item['nombre'],
        $item['tipo'],
        $item['variante_id'],
        $item['precio'],
        1
    ]);
}

// Limpiar PDFs viejos
foreach(glob("../ordenes/*.pdf") as $archivo){
    if(time() - filemtime($archivo) > 86400){
        unlink($archivo);
    }
}

// Limpiar carrito
unset($_SESSION['carrito']);

// Redirigir a finalizar
header("Location: ../finalizar.php?orden=$nombreArchivo&total=$total&nro=$numeroOrden");
exit;