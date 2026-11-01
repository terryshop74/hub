<?php
declare(strict_types=1);

/** Invia una risposta JSON e termina. */
function rispondi(array $dati, int $codice = 200): never
{
    http_response_code($codice);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($dati, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}
