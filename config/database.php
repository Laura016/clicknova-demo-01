<?php

$hostActual = $_SERVER['HTTP_HOST'] ?? '';

// Detectar si el proyecto se ejecuta en Laragon
$esLocal = (
    $hostActual === 'localhost' ||
    strpos($hostActual, 'localhost:') === 0 ||
    strpos($hostActual, '127.0.0.1') === 0
);

if ($esLocal) {
    require_once __DIR__ . '/database-local.php';
} else {
    require_once __DIR__ . '/database-production.php';
}
