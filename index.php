<?php
/**
 * Hub dati Terryshop74 — punto di ingresso unico.
 * Tutte le richieste arrivano qui (vedi .htaccess) e vengono smistate.
 */
declare(strict_types=1);

const HUB_VERSIONE = '0.1.0';

require __DIR__ . '/src/risposta.php';
require __DIR__ . '/src/db.php';

$metodo   = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$percorso = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/', '/');

// Rotte disponibili in questa versione
if ($metodo === 'GET' && ($percorso === '' || $percorso === 'api/stato')) {
    $db = hub_db_stato();
    rispondi([
        'stato'    => 'hub attivo',
        'versione' => HUB_VERSIONE,
        'database' => $db,
        'ora'      => date('c'),
    ]);
}

rispondi(['errore' => 'percorso non trovato', 'percorso' => $percorso], 404);
