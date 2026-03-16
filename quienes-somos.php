<?php include "components/header.php"; ?>
<?php include "components/navbar.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - Level Up Store Games</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white">

    <!-- HERO / Título -->
    <header class="bg-gradient-to-r from-gray-600 to-purple-600 py-20 text-center">
        <h1 class="text-4xl md:text-5xl font-bold drop-shadow-lg">Quiénes Somos</h1>
        <p class="mt-4 text-lg md:text-xl text-gray-200">Tu destino para juegos digitales y físicos de PS4, PS5 y Xbox
        </p>
    </header>

    <!-- Contenido principal -->
    <main class="max-w-6xl mx-auto px-6 py-16 space-y-16">

        <!-- Historia -->
        <section class="grid md:grid-cols-2 gap-8 items-center">
            <img src="assets/img/logo.png" alt="Level Up Store Games"
                class="rounded-xl shadow-lg object-contain w-full h-48 md:h-64 lg:h-72">
            <div class="space-y-4">
                <h2 class="text-3xl font-bold text-purple-400">Nuestra Historia</h2>
                <p class="text-gray-200 leading-relaxed">
                    Level Up Store Games nació de la pasión por los videojuegos y el deseo de ofrecer a nuestra
                    comunidad
                    los mejores títulos de PS4, PS5 y Xbox, tanto digitales como físicos. Desde nuestros inicios,
                    buscamos ser un espacio donde los gamers puedan encontrar los últimos lanzamientos, ofertas
                    exclusivas
                    y un servicio confiable y cercano.
                </p>
                <p class="text-gray-200 leading-relaxed">
                    Con años de experiencia en el mundo gamer, entendemos lo que los jugadores necesitan: rapidez,
                    seguridad y
                    una atención personalizada que haga que cada compra sea una experiencia única.
                </p>
            </div>
        </section>

        <!-- Misión -->
        <section class="bg-gray-800 rounded-xl p-10 text-center space-y-4 shadow-lg">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-orange-700 to-purple-500 bg-clip-text text-transparent">
                Nuestra Misión
            </h2>
            <p class="text-gray-200 leading-relaxed">
                Traer los mejores videojuegos a tu hogar con facilidad y confianza, ofreciendo un catálogo completo y
                siempre actualizado,
                para que cada jugador pueda “level up” en su experiencia gamer.
            </p>
        </section>

        <!-- Valores -->
        <section class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:scale-105 transform transition">
                <h2
                    class="text-2xl font-bold bg-gradient-to-r from-orange-700 to-purple-500 bg-clip-text text-transparent">
                    Pasión
                </h2>
                <p class="text-gray-200">Vivimos los videojuegos con entusiasmo y queremos contagiar esa pasión a
                    nuestros clientes.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:scale-105 transform transition">
                <h2
                    class="text-2xl font-bold bg-gradient-to-r from-orange-700 to-purple-500 bg-clip-text text-transparent">
                    Confianza
                </h2>
                <p class="text-gray-200">Garantizamos seguridad en cada compra y un servicio honesto y cercano.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:scale-105 transform transition">
                <h2
                    class="text-2xl font-bold bg-gradient-to-r from-orange-700 to-purple-500 bg-clip-text text-transparent">
                    Innovación
                </h2>
                <p class="text-gray-200">Siempre buscamos traer novedades, ofertas y experiencias únicas para la
                    comunidad gamer.</p>
            </div>
        </section>

        <!-- CTA / Invitación -->
        <section class="text-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-orange-700 to-purple-500 bg-clip-text text-transparent">¡Únete a la comunidad Level Up!</h2>
            <p class="text-gray-200 mb-6">Explora nuestro catálogo y descubre tus próximos juegos favoritos.</p>
            <a href="productos.php"
                class="bg-orange-600 hover:bg-purple-700 px-8 py-4 rounded-lg font-bold transition inline-block">Ver
                Juegos</a>
        </section>

    </main>

    <?php include "components/footer.php"; ?>
</body>

</html>