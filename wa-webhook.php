<?php
/**
 * Webhook for the WhatsApp Business Platform (Cloud API).
 * In Meta's app dashboard set the callback URL to https://<your domain>/wa-webhook.php,
 * the verify token to WHATSAPP_VERIFY_TOKEN, and subscribe to "messages".
 * Every request is checked against WHATSAPP_APP_SECRET before anything is stored.
 */
require_once __DIR__ . '/config/config.php';
header('Content-Type: text/plain; charset=utf-8');

if (!WhatsApp::receivingEnabled()) { http_response_code(404); exit('Not configured'); }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Meta's one-time verification handshake.
    $token = (string) ($_GET['hub_verify_token'] ?? '');
    if (($_GET['hub_mode'] ?? '') === 'subscribe' && hash_equals((string) env('WHATSAPP_VERIFY_TOKEN'), $token)) {
        exit((string) ($_GET['hub_challenge'] ?? ''));
    }
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$raw = (string) file_get_contents('php://input', false, null, 0, 1024 * 1024);
if (!WhatsApp::verifySignature($raw, (string) ($_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? ''))) {
    http_response_code(403);
    exit('Bad signature');
}
$payload = json_decode($raw, true);
if (!is_array($payload)) { http_response_code(400); exit('Bad payload'); }

try {
    WhatsApp::handle($payload);
} catch (Throwable $e) {
    // Answer 200 anyway so Meta doesn't retry forever; the error is logged.
    error_log('WhatsApp webhook error: ' . $e->getMessage());
}
http_response_code(200);
echo 'OK';
