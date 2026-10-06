<?php
/**
 * Every public WhatsApp button links here instead of straight to wa.me.
 * It records the tap with the visitor's channel (so the dashboard can show
 * WhatsApp interest by Google, TikTok, Facebook and so on), then sends the
 * visitor on to WhatsApp with the pre-filled message. Nothing is shown.
 *
 *   wa.php?from=home-hero            general number
 *   wa.php?from=branches&branch=3    that branch's WhatsApp number
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

Attribution::capture();

$from = preg_replace('/[^a-z0-9_-]/i', '', (string) ($_GET['from'] ?? 'site'));
$branchId = (int) ($_GET['branch'] ?? 0);
$number = COMPANY_WHATSAPP;
$branch = null;
if ($branchId > 0) {
    $branch = Branch::find($branchId);
    if ($branch && !empty($branch['whatsapp_number'])) {
        $number = preg_replace('/\D/', '', $branch['whatsapp_number']);
    }
}

try {
    Lead::recordWhatsappTap($branch ? (int) $branch['id'] : null, Attribution::current()['channel'], $from ?: 'site');
} catch (Throwable $e) {
    // Never block a family from reaching WhatsApp because logging failed.
    error_log('WhatsApp tap not recorded: ' . $e->getMessage());
}

$message = "Hello, I'd like to find out more about Everest Funerals.";
if (str_starts_with($from, 'plan-')) {
    $slug = substr($from, 5);
    if (isset(SERVICE_PLANS[$slug])) $message = "Hello, I'd like to find out more about the " . SERVICE_PLANS[$slug]['name'] . '.';
}
if ($from === 'claims') $message = "Hello, I need help with a claim.";

header('Cache-Control: no-store');
header('Location: https://wa.me/' . $number . '?text=' . rawurlencode($message), true, 302);
exit;
