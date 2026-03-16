<?php
$orden = $_GET['orden'] ?? '';
$total = $_GET['total'] ?? 0;
$nro = $_GET['nro'] ?? '';

$telefono = "5492241541640";
$mensaje = urlencode("Hola! Quiero confirmar mi compra. Orden #" . $nro . " - Total: $" . number_format($total, 0, ',', '.'));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gracias por tu compra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white flex items-center justify-center min-h-screen">

    <div class="text-center space-y-6">

        <!-- Loader -->
        <div id="loader" class="flex justify-center">
            <div class="w-16 h-16 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <h1 class="text-3xl font-bold text-green-500">
            🎉 ¡Gracias por tu compra!
        </h1>

        <p class="text-gray-400">
            Tu orden #<?php echo $nro; ?> está lista.
        </p>

        <div class="space-y-3">

            <a href="ordenes/<?php echo $orden; ?>"
                class="block bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition">
                📄 Descargar orden manualmente
            </a>

            <a id="btnWpp" href="#" class="block bg-gray-600 px-6 py-3 rounded-lg opacity-50 cursor-not-allowed">
                📲 Enviar por WhatsApp
            </a>

            <a href="index.php"
                class="block bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-lg transition text-center text-white font-medium">
                🏠 Volver al inicio
            </a>

        </div>

    </div>

    <script>

        window.onload = function () {

            // Crear link invisible para descarga automática
            const link = document.createElement("a");
            link.href = "ordenes/<?php echo $orden; ?>";
            link.download = "<?php echo $orden; ?>";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Después de 2 segundos habilitar WhatsApp
            setTimeout(function () {

                document.getElementById("loader").style.display = "none";

                let btn = document.getElementById("btnWpp");
                btn.classList.remove("bg-gray-600", "opacity-50", "cursor-not-allowed");
                btn.classList.add("bg-green-600", "hover:bg-green-700");
                btn.href = "https://wa.me/<?php echo $telefono; ?>?text=<?php echo $mensaje; ?>";

                btn.onclick = function () {
                    window.open(btn.href, "_blank");

                    // Volver al home después
                    setTimeout(function () {
                        window.location.href = "index.php";
                    }, 800);
                };

            }, 2000);

        };

    </script>

</body>

</html>