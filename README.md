# Hub dati Terryshop74

Cuore dati condiviso tra il gestionale FBA/FBM (Python) e le app Android.
Gira su Hostinger (PHP + MySQL) all'indirizzo `https://hub.terryshop74.it`.

## Come funziona la pubblicazione

Ogni modifica caricata sul ramo `main` viene copiata in automatico sul server
dalla GitHub Action `Pubblica su Hostinger`. Il file `config.php` con le
password esiste solo sul server e non viene mai toccato né caricato qui.

## Rotte disponibili

| Metodo | Percorso      | Cosa fa                                  |
|--------|---------------|------------------------------------------|
| GET    | `/api/stato`  | Dice se l'hub è attivo e se il database è collegato |

## Pagine

| Percorso | Cosa fa |
|---|---|
| `/b#<dati>` | Biglietto da visita dell'app **Biglietto NFC**: chi avvicina il telefono (Android o iPhone, senza app) vede il biglietto e lo salva in rubrica. I dati stanno nel link dopo `#`: non arrivano al server e non vengono salvati. |
