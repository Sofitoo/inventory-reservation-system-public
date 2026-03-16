<!-- inicio de php    SE COMENTO PORQUE SE CREO EL CRON PARA LIMPIAR LAS RESERVAS VENCIDAS,
                      ASI QUE NO ES NECESARIO HACERLO CADA VEZ QUE SE CARGA EL CARRITO, SINO
                      QUE SE HACE UNA VEZ AL DIA CON EL CRON 


                      
session_start();
include "back/conexion.php";

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

// LIMPIAR RESERVAS VENCIDAS
foreach($_SESSION['carrito'] as $key => $item){

    if(time() - $item['fecha_reserva'] > 86400){

        // devolver stock
        $pdo->prepare("
            UPDATE variantes 
            SET stock = stock + 1,
                reservado = reservado - 1
            WHERE id = ?
        ")->execute([$item['variante_id']]);

        unset($_SESSION['carrito'][$key]);
    }
}

$carrito = $_SESSION['carrito'];
$total = 0;
?>-->