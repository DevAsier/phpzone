<?php
session_start();
header('Content-Type: application/json');

// --- Seguridad de sesión ---
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Sesión no válida o expirada.']);
    exit;
}

// --- Configuración de directorios ---
$uploadsDir = "../uploads";
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);

$archivoNombres = $uploadsDir . "/nombres.json";
if (!file_exists($archivoNombres)) file_put_contents($archivoNombres, json_encode([]));

// --- Validación de archivo ---
if (!isset($_FILES['archivo'])) {
    echo json_encode(['status' => 'error', 'mensaje' => 'No se ha seleccionado ningún archivo.']);
    exit;
}

$archivo = $_FILES['archivo'];
if ($archivo['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al subir el archivo.']);
    exit;
}

$nombreArchivo = basename($archivo['name']);
$nombreVisible = trim(htmlspecialchars($_POST['nombreVisible'] ?? $nombreArchivo));
$rutaDestino = $uploadsDir . "/" . $nombreArchivo;

$extensionesPermitidas = ['zip', 'rar', 'pdf', 'txt'];
$extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

if (!in_array($extension, $extensionesPermitidas)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Solo se permiten archivos .zip, .rar, .pdf o .txt.']);
    exit;
}

// --- Cargar lista de nombres visibles ---
$listaNombres = json_decode(file_get_contents($archivoNombres), true);

// --- Verificar duplicados ---
// 1️⃣ Archivo con el mismo nombre físico
if (file_exists($rutaDestino)) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => "⚠️ Ya existe un archivo físico con el nombre <strong>$nombreArchivo</strong>.<br>
                      Por favor, renómbralo antes de subirlo."
    ]);
    exit;
}

// 2️⃣ Nombre visible duplicado
if (in_array(strtolower($nombreVisible), array_map('strtolower', $listaNombres))) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => "⚠️ El nombre visible <strong>$nombreVisible</strong> ya está en uso por otro archivo.<br>
                      Por favor, elige otro nombre para evitar confusiones."
    ]);
    exit;
}

// --- Guardar archivo ---
if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {

    // Registrar nombre visible
    $listaNombres[$nombreArchivo] = $nombreVisible;
    file_put_contents($archivoNombres, json_encode($listaNombres, JSON_PRETTY_PRINT));

    // Datos del archivo
    $extensionMayus = strtoupper($extension);
    $tamano = round(filesize($rutaDestino) / 1024, 2) . " KB";
    $fecha = date("d/m/Y H:i", filemtime($rutaDestino));

    echo json_encode([
        'status' => 'ok',
        'mensaje' => "✅ Archivo subido correctamente con el nombre visible <strong>$nombreVisible</strong>.",
        'archivo' => [
            'nombre' => $nombreVisible,
            'tipo' => $extensionMayus,
            'tamano' => $tamano,
            'fecha' => $fecha,
            'ruta' => "uploads/$nombreArchivo",
            'nombreReal' => $nombreArchivo
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al mover el archivo al servidor.']);
}
?>
