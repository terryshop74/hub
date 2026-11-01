<?php
declare(strict_types=1);

/**
 * Connessione al database MySQL dell'hub.
 * I dati di accesso stanno in config.php, che esiste solo sul server
 * e non viene mai caricato su GitHub (vedi config.example.php).
 */
function hub_config(): ?array
{
    $file = __DIR__ . '/../config.php';
    return is_file($file) ? require $file : null;
}

function hub_db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $c = hub_config();
    if ($c === null) {
        throw new RuntimeException('config.php mancante');
    }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $c['db_host'], $c['db_nome']);
    $pdo = new PDO($dsn, $c['db_utente'], $c['db_password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}

/** Controlla il database senza mai mostrare dettagli riservati. */
function hub_db_stato(): string
{
    if (hub_config() === null) {
        return 'non configurato';
    }
    try {
        hub_db()->query('SELECT 1');
        return 'collegato';
    } catch (Throwable $e) {
        return 'errore di collegamento';
    }
}
