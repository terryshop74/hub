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

// Biglietto NFC: rimanda il contatto come file .vcf, così il telefono lo apre in rubrica.
// Arriva in POST (non finisce nei log dell'indirizzo) e NON viene salvato da nessuna parte.
if ($metodo === 'POST' && $percorso === 'vcf') {
    $v = (string)($_POST['v'] ?? '');
    if (strlen($v) > 8000 || strncmp($v, 'BEGIN:VCARD', 11) !== 0) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Biglietto non valido');
    }
    $nome = preg_match('/^FN:(.*)$/m', $v, $m) ? trim($m[1]) : '';
    $nome = preg_replace('/[^A-Za-z0-9 _-]+/', '', $nome) ?: 'contatto';
    header('Content-Type: text/vcard; charset=utf-8');
    header('Content-Disposition: inline; filename="' . $nome . '.vcf"');
    header('Cache-Control: no-store');
    header('X-Robots-Tag: noindex');
    echo $v;
    exit;
}

rispondi(['errore' => 'percorso non trovato', 'percorso' => $percorso], 404);
