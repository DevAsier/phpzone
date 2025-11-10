// === Interacción del menú de usuario ===
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("userToggle");
  const dropdown = document.getElementById("userDropdown");

  if (toggle && dropdown) {
    toggle.addEventListener("click", () => {
      dropdown.classList.toggle("show");
    });

    // Cierra al hacer clic fuera
    window.addEventListener("click", (e) => {
      if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  }
});


// === Interacción del menú de usuario + sombra en scroll ===
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("userToggle");
  const dropdown = document.getElementById("userDropdown");
  const header = document.querySelector(".main-header");

  // Mostrar/ocultar menú de usuario
  if (toggle && dropdown) {
    toggle.addEventListener("click", () => {
      dropdown.classList.toggle("show");
    });

    // Cierra el menú al hacer clic fuera
    window.addEventListener("click", (e) => {
      if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  }

  // Sombra del header al hacer scroll
  window.addEventListener("scroll", () => {
    if (window.scrollY > 10) {
      header.classList.add("scroll");
    } else {
      header.classList.remove("scroll");
    }
  });
});
