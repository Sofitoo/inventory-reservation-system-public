<nav class="bg-black text-white relative z-50">

    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

        <!-- Logo -->
        <div class="flex items-center">
            <img src="assets/img/logo.png"
                 class="h-10 md:h-12 w-auto object-contain">
        </div>

        <!-- Botón hamburguesa (solo móvil) -->
        <button id="menuBtn" class="md:hidden text-2xl rounded-lg px-2 py-1 border border-gray-700 hover:border-red-500 transition">
            ☰
        </button>

        <!-- Links -->
        <div id="menu"
            class="hidden absolute top-16 left-0 w-full bg-black z-50 border-b border-gray-800
            md:static md:flex md:w-auto md:gap-3 md:bg-transparent md:border-0">


            <a href="index.php" class="block px-4 py-2 hover:text-red-500 font-medium">Home</a>
            <a href="ofertas.php" class="block px-4 py-2 hover:text-red-500 font-medium">Ofertas</a>
            <a href="quienes-somos.php" class="block px-4 py-2 hover:text-red-500 font-medium">Quiénes Somos</a>
            <a href="contacto.php" class="block px-4 py-2 hover:text-red-500 font-medium">Contacto</a>
            <a href="carrito.php" class="block px-4 py-2 hover:text-red-500 font-medium">
                Carrito 🛒
            </a>

        </div>

    </div>
</nav>

<script>
    const btn = document.getElementById('menuBtn');
    const menu = document.getElementById('menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
