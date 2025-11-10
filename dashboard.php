<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php");
  exit;
}

// --- Control de inactividad ---
$inactividadMaxima = 600; // 10 minutos
if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > $inactividadMaxima)) {
  session_unset();
  session_destroy();
  header("Location: index.php?expirada=1");
  exit;
}
$_SESSION['ultima_actividad'] = time();

// --- Configuración del directorio ---
$uploadsDir = "uploads";
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel - PHPZone</title>
  <link rel="stylesheet" href="public/css/estilos.css">
  <link rel="stylesheet" href="public/css/layout.css">
  <link rel="stylesheet" href="public/css/extras.css">
  <link rel="stylesheet" href="public/css/header.css">
  <script src="https://kit.fontawesome.com/1d9c2e7e5c.js" crossorigin="anonymous"></script>
</head>
<body>

  <?php include __DIR__ . '/components/header.php'; ?>

  <div class="dashboard">
    <!-- === SUBIDA DE ARCHIVOS (solo Drag & Drop) === -->
    <h3><i class="fa-solid fa-upload"></i> Subir archivo</h3>

    <form id="uploadForm" enctype="multipart/form-data">
      <!-- 🔹 Zona Drag & Drop -->
      <div id="dropZone" class="drop-zone">
        <i class="fa-solid fa-cloud-arrow-up"></i>
        <p>Arrastra tu archivo aquí o haz clic para seleccionarlo</p>
        <div id="filePreview" class="file-preview"></div>
      </div>

      <!-- 🔹 Campo de nombre visible -->
      <input type="text" name="nombreVisible" id="nombreVisible" placeholder="Nombre que se mostrará" required>

      <!-- 🔹 Botón de subida -->
      <button type="submit" class="btn-subir">
        <i class="fa-solid fa-cloud-arrow-up"></i> Subir archivo
      </button>

      <!-- Barra de progreso -->
      <div class="progress-container" style="display:none;">
        <div class="progress-bar"></div>
        <span class="progress-text">0%</span>
      </div>

      <!-- Input real oculto -->
      <input type="file" name="archivo" id="archivoInput" hidden required>
    </form>

    <!-- === LISTA DE ARCHIVOS DINÁMICA === -->
    <h3><i class="fa-solid fa-folder-open"></i> Archivos disponibles</h3>

    <!-- Buscador -->
    <div class="buscador-archivos">
      <input type="text" id="searchInput" placeholder="Buscar archivo..." />
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>

    <!-- Contenedor donde se genera la tabla con AJAX -->
    <div id="tablaArchivos" class="tabla-ajax"></div>

    <!-- Botón borrar todos -->
    <form id="borrarTodoForm" action="php/borrarTodo.php" method="POST">
      <button type="submit" class="delete-all">
        <i class="fa-solid fa-trash-can"></i> Borrar todos los archivos
      </button>
    </form>
  </div>

  <!-- === FOOTER === -->
  <footer>
    <p>Hecho con <i class="fa-solid fa-heart" style="color:#d9534f;"></i> por 
      <strong><a href="https://github.com/DevAsier" target="_blank">Asier Cobas</a></strong>
    </p>
  </footer>

  <script src="public/js/layout.js"></script>
  <script src="public/js/header.js"></script>
  <script src="public/js/main.js"></script>

  <!-- ✅ Toast mejorado -->
  <div id="toast" class="toast hidden">
    <i id="toast-icon" class="fa-solid fa-circle-check"></i>
    <span id="toast-message"></span>
    <button class="close-btn" onclick="closeToast()"><i class="fa-solid fa-xmark"></i></button>
  </div>

  <script>
  function showToast(message, type = "success") {
    const toast = document.getElementById("toast");
    const msg = document.getElementById("toast-message");
    const icon = document.getElementById("toast-icon");

    // Estilo por tipo
    let color, iconClass;
    switch (type) {
      case "error":
        color = "linear-gradient(90deg, #e74c3c, #ff7675)";
        iconClass = "fa-circle-xmark";
        break;
      case "warning":
        color = "linear-gradient(90deg, #f39c12, #f1c40f)";
        iconClass = "fa-triangle-exclamation";
        break;
      default:
        color = "linear-gradient(90deg, #2ecc71, #27ae60)";
        iconClass = "fa-circle-check";
    }

    // Actualizar contenido
    toast.style.background = color;
    msg.textContent = message;
    icon.className = `fa-solid ${iconClass}`;
    
    toast.classList.remove("hidden");
    setTimeout(() => toast.classList.add("hidden"), 5000);
  }

  function closeToast() {
    document.getElementById("toast").classList.add("hidden");
  }
  </script>

</body>
</html>
