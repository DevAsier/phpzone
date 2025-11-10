// ===============================
// JS PRINCIPAL - PHPZone (100% AJAX optimizado con Toasts)
// ===============================

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("uploadForm");
  const modal = document.getElementById("modalMensaje");
  const modalTitulo = document.getElementById("modalTitulo");
  const modalTexto = document.getElementById("modalTexto");
  const cerrarModal = document.getElementById("cerrarModal");
  const progressContainer = document.querySelector(".progress-container");
  const progressBar = document.querySelector(".progress-bar");
  const progressText = document.querySelector(".progress-text");
  const dropZone = document.getElementById("dropZone");
  const fileInput = form?.querySelector('input[name="archivo"]');
  const filePreview = document.getElementById("filePreview");

  // =====================================================
  // 🔹 SISTEMA DE TOASTS (éxito, error, aviso)
  // =====================================================
  function showToast(message, color = "#2ecc71") {
    let toast = document.getElementById("toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "toast";
      toast.className = "toast";
      toast.innerHTML = `<span id="toast-message"></span><button class="close-btn" onclick="this.parentElement.remove()">×</button>`;
      document.body.appendChild(toast);
    }
    const msg = toast.querySelector("#toast-message");
    toast.style.backgroundColor = color;
    msg.textContent = message;
    toast.classList.remove("hidden");
    setTimeout(() => toast.classList.add("hidden"), 5000);
  }

  const mostrarAviso = (texto, color = "#2ecc71") => showToast(texto, color);
  const mostrarError = (msg) => showToast(msg, "#e74c3c");

  if (cerrarModal) cerrarModal.addEventListener("click", () => modal.classList.remove("show"));
  window.addEventListener("click", (e) => { if (e.target === modal) modal.classList.remove("show"); });

  // =====================================================
  // 🔹 FUNCIÓN PRINCIPAL PARA CARGAR ARCHIVOS DINÁMICOS
  // =====================================================
  async function cargarArchivos(pagina = 1, busqueda = "") {
    const contenedor = document.getElementById("tablaArchivos");
    if (!contenedor) return;

    try {
      const res = await fetch(`php/listarArchivos.php?busqueda=${encodeURIComponent(busqueda)}`);
      const data = await res.json();

      if (!Array.isArray(data) || data.length === 0) {
        contenedor.innerHTML = `<p style="text-align:center;">No hay archivos disponibles.</p>`;
        return;
      }

      const porPagina = 7;
      const totalPag = Math.ceil(data.length / porPagina);
      const inicio = (pagina - 1) * porPagina;
      const archivos = data.slice(inicio, inicio + porPagina);

      let html = `
        <table class="file-table">
          <thead>
            <tr>
              <th>Nombre visible</th>
              <th>Tipo</th>
              <th>Tamaño</th>
              <th>Fecha subida</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
      `;

      archivos.forEach(a => {
        html += `
          <tr>
            <td>${a.nombreVisible}</td>
            <td>${a.extension}</td>
            <td>${a.tamano}</td>
            <td>${a.fecha}</td>
            <td class="acciones">
              <a href="uploads/${a.archivo}" download title="Descargar"><i class="fa-solid fa-download"></i></a>
              <a href="#" class="borrar-archivo" data-file="${a.archivo}" title="Borrar"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>`;
      });

      html += "</tbody></table>";

      if (totalPag > 1) {
        html += `<div class="paginacion">`;
        for (let i = 1; i <= totalPag; i++) {
          html += `<button class="pagina-btn ${i === pagina ? "activo" : ""}" data-page="${i}">${i}</button>`;
        }
        html += `</div>`;
      }

      contenedor.innerHTML = html;

      contenedor.querySelectorAll(".pagina-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          cargarArchivos(Number(btn.dataset.page), busqueda);
        });
      });

    } catch (e) {
      contenedor.innerHTML = `<p style="color:red; text-align:center;">Error al cargar los archivos.</p>`;
    }
  }

  // =====================================================
  // 🔹 SUBIDA DE ARCHIVOS (AJAX + REFRESCO AUTOMÁTICO)
  // =====================================================
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "php/subirArchivo.php", true);

      progressContainer.style.display = "block";
      progressBar.style.width = "0%";
      progressText.textContent = "0%";

      xhr.upload.addEventListener("progress", (event) => {
        if (event.lengthComputable) {
          const percent = Math.round((event.loaded / event.total) * 100);
          progressBar.style.width = `${percent}%`;
          progressText.textContent = `${percent}%`;
        }
      });

      xhr.onload = async () => {
        try {
          const data = JSON.parse(xhr.responseText);
          if (data.status === "ok") {
            progressBar.style.width = "100%";
            progressBar.style.background = "linear-gradient(90deg, #2ecc71, #58d68d)";
            progressText.textContent = "Completado";

            showToast("Archivo subido correctamente", "#2ecc71");
            form.reset();
            filePreview.innerHTML = ""; // limpiar preview tras subida
            await cargarArchivos();

            setTimeout(() => {
              progressContainer.style.display = "none";
              progressBar.style.width = "0%";
            }, 1000);
          } else showToast(data.mensaje, "#e74c3c");
        } catch {
          showToast("Respuesta inesperada del servidor.", "#e74c3c");
        }
      };

      xhr.onerror = () => showToast("Error de conexión.", "#e74c3c");
      xhr.send(formData);
    });
  }

  // =====================================================
  // 🔹 DRAG & DROP PARA SUBIR ARCHIVOS (con vista previa)
  // =====================================================
  if (dropZone && fileInput) {
    // Clic sobre la zona → abrir input
    dropZone.addEventListener("click", () => fileInput.click());

    // Visual feedback
    ["dragenter", "dragover"].forEach(event => {
      dropZone.addEventListener(event, e => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add("dragover");
      });
    });

    ["dragleave", "drop"].forEach(event => {
      dropZone.addEventListener(event, e => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove("dragover");
      });
    });

    // Soltar archivos
    dropZone.addEventListener("drop", e => {
      e.preventDefault();
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        fileInput.files = files;
        mostrarPreview(files[0]);
        form.dispatchEvent(new Event("submit"));
      }
    });

    // Selección manual
    fileInput.addEventListener("change", () => {
      if (fileInput.files.length > 0) {
        mostrarPreview(fileInput.files[0]);
      }
    });

    // Vista previa
    function mostrarPreview(file) {
      if (!filePreview) return;
      const nombre = file.name;
      const tipo = file.type;

      if (tipo.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = e => {
          filePreview.innerHTML = `
            <img src="${e.target.result}" alt="preview" class="preview-img">
            <p>${nombre}</p>
          `;
        };
        reader.readAsDataURL(file);
      } else {
        let icono = "fa-file";
        if (tipo.includes("pdf")) icono = "fa-file-pdf";
        else if (tipo.includes("zip")) icono = "fa-file-zipper";
        else if (tipo.includes("word")) icono = "fa-file-word";
        else if (tipo.includes("excel")) icono = "fa-file-excel";
        filePreview.innerHTML = `<i class="fa-solid ${icono}"></i><p>${nombre}</p>`;
      }
    }
  }

  // =====================================================
  // 🔹 BORRADO INDIVIDUAL SIN RECARGAR
  // =====================================================
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".borrar-archivo");
    if (!btn) return;
    e.preventDefault();

    const file = btn.dataset.file;
    const fila = btn.closest("tr");

    try {
      const res = await fetch(`php/borrarArchivo.php?file=${encodeURIComponent(file)}`);
      const data = await res.json();

      if (data.status === "ok") {
        fila.style.transition = "opacity .4s ease";
        fila.style.opacity = "0";
        setTimeout(() => fila.remove(), 400);
        showToast("Archivo eliminado", "#e67e22");
        setTimeout(() => cargarArchivos(), 600);
      } else showToast(data.mensaje, "#e74c3c");
    } catch {
      showToast("Error al conectar con el servidor.", "#e74c3c");
    }
  });

  // =====================================================
  // 🔹 BORRADO TOTAL SIN RECARGAR
  // =====================================================
  document.getElementById("borrarTodoForm")?.addEventListener("submit", async (e) => {
    e.preventDefault();
    try {
      const res = await fetch("php/borrarTodo.php", { method: "POST" });
      const data = await res.json();
      if (data.status === "ok") {
        showToast("Todos los archivos eliminados", "#e74c3c");
        await cargarArchivos();
      } else showToast(data.mensaje, "#e74c3c");
    } catch {
      showToast("Error al borrar los archivos.", "#e74c3c");
    }
  });

  // =====================================================
  // 🔹 BUSCADOR INSTANTÁNEO
  // =====================================================
  document.getElementById("searchInput")?.addEventListener("input", (e) => {
    cargarArchivos(1, e.target.value.trim());
  });

  // CARGA INICIAL
  cargarArchivos();
});
