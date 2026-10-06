<?php
/**
 * Records a tap on any "call us" phone link, with the visitor's channel
 * (scope of work: "call and WhatsApp tracking on all paid and organic
 * channels"). Sent in the background by assets/js/site.js; the call itself
 * goes ahead immediately. Counts taps, not completed calls.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
// One tap per visitor every 10 seconds is plenty; ignores double-taps and scripted floods.
$last = (int) ($_SESSION['ef_last_call_tap'] ?? 0);
if (time() - $last >= 10) {
    $_SESSION['ef_last_call_tap'] = time();
    $page = preg_replace('/[^a-z0-9_\/.-]/i', '', (string) ($_POST['page'] ?? ''));
    try {
        Database::run('INSERT INTO call_taps (channel, page) VALUES (?, ?)', [Attribution::current()['channel'], substr($page ?: 'site', 0, 100)]);
    } catch (Throwable $e) { error_log('Call tap not recorded: ' . $e->getMessage()); }
}
http_response_code(204);
