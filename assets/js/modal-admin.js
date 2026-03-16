document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modalProducto");
  const abrirCrear = document.getElementById("abrirModalCrear");
  const cerrar = document.getElementById("cerrarModal");
  const modalTitulo = document.getElementById("modalTitulo");
  const form = document.getElementById("formProducto");

  if (!modal || !modalTitulo || !form) {
    console.error("Falta algún elemento del modal en el HTML");
    return;
  }

  // Abrir modal para crear
  if (abrirCrear) {
    abrirCrear.addEventListener("click", () => {
      modalTitulo.textContent = "Crear producto";
      form.reset();
      form.action = "productos.php?accion=crear";
      modal.classList.remove("hidden");
      document.getElementById("imagenActualContainer").classList.add("hidden");
    });
  }

  // Abrir modal para editar
  document.querySelectorAll(".editarBtn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;

      modalTitulo.textContent = "Editar producto";

      fetch(`productos.php?accion=editar&id=${id}&json=1`)
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          form.nombre.value = data.producto.nombre;
          form.descripcion.value = data.producto.descripcion;
          form.categoria.value = data.producto.categoria;
          form.categoria_genero.value = data.producto.categoria_genero;
          form.destacado.checked = data.producto.destacado == 1;
          form.descuento.checked = data.producto.descuento == 1;

          form.precio_digital.value = data.digital?.precio ?? "";
          form.stock_digital.value = data.digital?.stock ?? "";
          form.precio_fisico.value = data.fisico?.precio ?? "";
          form.stock_fisico.value = data.fisico?.stock ?? "";

          form.action = `productos.php?accion=editar&id=${id}`;

          modal.classList.remove("hidden");

          const imagenContainer = document.getElementById(
            "imagenActualContainer",
          );
          const imagenActual = document.getElementById("imagenActual");

          console.log("Imagen:", data.producto.imagen);

          if (data.producto.imagen) {
            imagenActual.src = `../uploads/${data.producto.imagen}`;
            imagenContainer.classList.remove("hidden");
          } else {
            imagenContainer.classList.add("hidden");
          }
        });
    });
  });

  // Cerrar modal
  if (cerrar) {
    cerrar.addEventListener("click", () => modal.classList.add("hidden"));
  }

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.classList.add("hidden");
  });
});
