<?php
session_start();
header("Content-Type: application/json");

// --- Verificar sesión ---
if (!isset($_SESSION['usuario'])) {
  echo json_encode(['status' => 'error', 'mensaje' => 'Sesión expirada.']);
  exit;
}

// --- Verificar parámetro ---
if (empty($_GET['file'])) {
  echo json_encode(['status' => 'error', 'mensaje' => 'Archivo no especificado.']);
  exit;
}

$archivo = basename($_GET['file']);
$ruta = "../uploads/$archivo";
$nombresJson = "../uploads/nombres.json";

// --- Comprobar existencia ---
if (!file_exists($ruta)) {
  echo json_encode(['status' => 'error', 'mensaje' => 'El archivo no existe o ya fue eliminado.']);
  exit;
}

// --- Eliminar archivo físico ---
if (@unlink($ruta)) {
  // Actualizar nombres.json si existe
  if (file_exists($nombresJson)) {
    $nombres = json_decode(file_get_contents($nombresJson), true) ?? [];
    if (isset($nombres[$archivo])) {
      unset($nombres[$archivo]);
      file_put_contents($nombresJson, json_encode($nombres, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
  }

  echo json_encode([
    'status' => 'ok',
    'mensaje' => 'Archivo eliminado correctamente.',
    'archivo' => $archivo
  ]);
} else {
  echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo eliminar el archivo (ver permisos del servidor).']);
}
?>
