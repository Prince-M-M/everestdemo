<?php
// XML sitemap for Google Search Console: submit https://<your domain>/sitemap.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';
header('Content-Type: application/xml; charset=utf-8');

$pages = ['index.php', 'about.php', 'team.php', 'services.php', 'quote.php', 'apply.php', 'claim-process.php',
          'pre-need.php', 'branches.php', 'contact.php', 'tributes.php', 'guides.php', 'terms.php'];
foreach (array_keys(SERVICE_PLANS) as $slug) $pages[] = 'plan.php?slug=' . $slug;
foreach (Article::published(null, 500) as $a) $pages[] = 'guide.php?slug=' . rawurlencode($a['slug']);
foreach (Notice::published(200) as $n) $pages[] = 'notice.php?id=' . (int) $n['id'];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $p) echo '  <url><loc>' . htmlspecialchars(url($p), ENT_XML1) . "</loc></url>\n";
echo '</urlset>';
