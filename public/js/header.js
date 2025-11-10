// === Control del menú de usuario y sombra en scroll ===
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("userToggle");
  const dropdown = document.getElementById("userDropdown");
  const header = document.querySelector(".main-header");

  // --- Mostrar/ocultar menú de usuario ---
  if (toggle && dropdown) {
    toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdown.classList.toggle("show");
    });

    // Cerrar al hacer clic fuera
    document.addEventListener("click", (e) => {
      if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  }

  // --- Añadir sombra al hacer scroll ---
  window.addEventListener("scroll", () => {
    if (window.scrollY > 10) {
      header.classList.add("scroll");
    } else {
      header.classList.remove("scroll");
    }
  });
});
