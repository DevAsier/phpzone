<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}

$uploadsDir = "../uploads";
$nombresJson = $uploadsDir . "/nombres.json";

$archivos = array_diff(scandir($uploadsDir), ['.', '..']);
$nombresVisibles = file_exists($nombresJson) ? json_decode(file_get_contents($nombresJson), true) : [];

$busqueda = isset($_GET['busqueda']) ? strtolower(trim($_GET['busqueda'])) : "";

$resultado = [];

foreach ($archivos as $archivo) {
    $ruta = $uploadsDir . "/" . $archivo;
    if (!is_file($ruta)) continue;

    $extension = strtoupper(pathinfo($archivo, PATHINFO_EXTENSION));
    $tamano = round(filesize($ruta) / 1024, 2) . " KB";
    $fecha = date("d/m/Y H:i", filemtime($ruta));
    $nombreVisible = $nombresVisibles[$archivo] ?? $archivo;

    // --- Solo filtra por el nombre visible ---
    if ($busqueda && strpos(strtolower($nombreVisible), $busqueda) === false) continue;

    $resultado[] = [
        "archivo" => $archivo,
        "nombreVisible" => $nombreVisible,
        "extension" => $extension,
        "tamano" => $tamano,
        "fecha" => $fecha
    ];
}

echo json_encode($resultado, JSON_PRETTY_PRINT);
