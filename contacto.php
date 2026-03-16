<?php include "components/header.php"; ?>
<?php include "components/navbar.php"; ?>

<section class="min-h-screen bg-gray-900 text-white py-16 px-6">
    
    <div class="max-w-4xl mx-auto text-center">

        <!-- Título -->
        <h1 class="text-4xl md:text-5xl font-bold mb-6 
        bg-clip-text text-transparent 
        bg-gradient-to-r from-red-500 via-yellow-400 to-green-400">
        Contacto
        </h1>

        <p class="text-gray-300 mb-10">
        Si tenés alguna consulta sobre productos, reservas o compras, podés comunicarte con nosotros.
        Nuestro equipo responderá lo antes posible.
        </p>

        <!-- Tarjetas -->
        <div class="grid md:grid-cols-2 gap-8">

            <!-- WhatsApp -->
            <div class="bg-gray-800 p-8 rounded-2xl shadow-lg hover:scale-105 transition">
                <h2 class="text-2xl font-semibold mb-4">WhatsApp</h2>
                <p class="text-gray-400 mb-6">
                Podés escribirnos directamente para consultar sobre productos o coordinar una compra.
                </p>

                <a href="https://wa.me/5492241541640"
                target="_blank"
                class="bg-green-500 hover:bg-red-500 px-6 py-3 rounded-xl font-semibold transition">
                Enviar mensaje
                </a>
            </div>

            <!-- Email -->
            <div class="bg-gray-800 p-8 rounded-2xl shadow-lg hover:scale-105 transition">
                <h2 class="text-2xl font-semibold mb-4">Email</h2>
                <p class="text-gray-400 mb-6">
                También podés enviarnos un correo electrónico con tu consulta.
                </p>

                <a href="mailto:contacto@levelupstore.com"
                class="bg-red-500 hover:bg-green-500 px-6 py-3 rounded-xl font-semibold transition">
                Enviar email
                </a>
            </div>

        </div>

    </div>

</section>

<?php include "components/footer.php"; ?>
</body>
</html>