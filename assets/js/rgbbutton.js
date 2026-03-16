document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById('dropdownBtn');
    const menu = document.getElementById('dropdownMenu');
    const selectedText = document.getElementById('selectedText');
    const categoriaInput = document.getElementById("categoriaInput");

    const generoBtn = document.getElementById("dropdownGeneroBtn");
    const generoMenu = document.getElementById("dropdownGeneroMenu");
    const selectedGeneroText = document.getElementById("selectedGeneroText");
    const generoInput = document.getElementById("generoInput");

    // --------- CATEGORIA ---------

    btn?.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
    });

    document.querySelectorAll('.option').forEach(option => {
        option.addEventListener('click', function () {
            const value = this.dataset.value ?? "";
            const text = this.textContent.trim();

            selectedText.textContent = text;
            categoriaInput.value = value;

            menu.classList.add('hidden');
        });
    });

    // --------- GENERO ---------

    generoBtn?.addEventListener("click", function (e) {
        e.stopPropagation();
        generoMenu.classList.toggle("hidden");
    });

    document.querySelectorAll('.optionGenero').forEach(option => {
        option.addEventListener('click', function () {
            const value = this.dataset.value ?? "";
            const text = this.textContent.trim();

            selectedGeneroText.textContent = text;
            generoInput.value = value;

            generoMenu.classList.add('hidden');
        });
    });

    // --------- Cerrar al hacer click afuera ---------

    document.addEventListener("click", function () {
        menu?.classList.add("hidden");
        generoMenu?.classList.add("hidden");
    });

});