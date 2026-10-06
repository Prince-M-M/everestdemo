<?php
/**
 * Included by every public-facing page after config.php and
 * includes/site-data.php have already been loaded. Expects $page_title,
 * $meta_description, and optionally $active_nav to be set.
 */
$activeNav = $active_nav ?? '';
$navLink = function (string $key, string $href, string $label) use ($activeNav): string {
    return '<a class="link" href="' . url($href) . '"' . ($activeNav === $key ? ' aria-current="page"' : '') . '>' . h($label) . '</a>';
};
?>
<!DOCTYPE html>
<html lang="en" data-call-url="<?= h(url('call.php')) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($page_title ?? 'Everest Funerals') ?></title>
<meta name="description" content="<?= h($meta_description ?? 'Trusted funeral services in Rustenburg and surrounding areas. Affordable funeral plans, cremation and burial, arranged with care and dignity.') ?>">
<meta name="theme-color" content="#062A4E">
<meta property="og:site_name" content="Everest Funerals">
<meta property="og:title" content="<?= h($page_title ?? 'Everest Funerals') ?>">
<meta property="og:image" content="<?= asset_img('hero-dawn.jpg') ?>">
<link rel="preload" href="<?= url('assets/fonts/cormorant-garamond-latin-600-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= url('assets/fonts/figtree-latin-400-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= url('assets/css/site.css') ?>">
<?php if (($gsc = Setting::get('search_console_verification')) !== ''): ?><meta name="google-site-verification" content="<?= h($gsc) ?>">
<?php endif; ?>
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FuneralHome',
    'name' => 'Everest Funerals',
    'url' => url('index.php'),
    'logo' => asset_img('logo-transparent.png'),
    'image' => asset_img('hero-dawn.jpg'),
    'telephone' => COMPANY_PHONE_DISPLAY,
    'email' => COMPANY_EMAIL,
    'foundingDate' => (string) COMPANY_FOUNDED,
    'slogan' => 'Always by your side',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '50 Napoleon Street', 'addressLocality' => 'Rustenburg North', 'addressRegion' => 'North West', 'postalCode' => '2999', 'addressCountry' => 'ZA'],
    'areaServed' => 'South Africa',
    'sameAs' => [COMPANY_FACEBOOK, COMPANY_LINKEDIN],
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<script src="<?= url('assets/js/site.js') ?>" defer></script>
</head>
<body>

<div class="topstrip"><div class="container">
  <div>
    <span class="hide-sm"><?= ev_icon('pin', 15) ?><?= h(COMPANY_ADDRESS) ?></span>
    <a href="mailto:<?= h(COMPANY_EMAIL) ?>"><?= ev_icon('mail', 15) ?><?= h(COMPANY_EMAIL) ?></a>
  </div>
  <div>
    <a class="hide-sm" href="<?= h(COMPANY_FACEBOOK) ?>" target="_blank" rel="noopener">Facebook</a>
    <a class="hide-sm" href="<?= h(COMPANY_LINKEDIN) ?>" target="_blank" rel="noopener">LinkedIn</a>
    <a href="tel:<?= h(COMPANY_PHONE_TEL) ?>"><?= ev_icon('phone', 15) ?><strong><?= h(COMPANY_PHONE_DISPLAY) ?></strong></a>
  </div>
</div></div>

<header class="site-header">
  <div class="container nav-wrap">
    <a href="<?= url('index.php') ?>" class="brand" aria-label="Everest Funerals home">
      <img src="<?= asset_img('logo-transparent.png') ?>" alt="Everest Funerals — always by your side" width="250" height="48">
    </a>

    <nav class="nav" id="siteNav" aria-label="Main">
      <?= $navLink('about', 'about.php', 'About us') ?>
      <div class="has-sub">
        <?= $navLink('services', 'services.php', 'Funeral plans') ?>
        <div class="subnav">
          <?php foreach (SERVICE_PLANS as $navSlug => $navPlan): ?>
            <a href="<?= url('plan.php?slug=' . $navSlug) ?>"><span><?= h($navPlan['name']) ?></span><small>from <?= h($navPlan['from']) ?></small></a>
          <?php endforeach; ?>
          <a href="<?= url('services.php') ?>"><span><strong>Compare all plans and services</strong></span></a>
        </div>
      </div>
      <?= $navLink('claim', 'claim-process.php', 'Claims') ?>
      <?= $navLink('branches', 'branches.php', 'Branches') ?>
      <?= $navLink('tributes', 'tributes.php', 'Notices') ?>
      <?= $navLink('contact', 'contact.php', 'Contact') ?>
      <a class="btn btn-outline btn-sm nav-quote" href="<?= url('quote.php') ?>"<?= $activeNav === 'quote' ? ' aria-current="page"' : '' ?>>Get a quote</a>
      <a class="btn btn-navy btn-sm nav-apply" href="<?= url('apply.php') ?>"<?= $activeNav === 'apply' ? ' aria-current="page"' : '' ?>>Apply now</a>
    </nav>

    <div class="nav-actions">
      <a class="btn btn-wa btn-sm wa-head" href="<?= wa_href('header') ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><?= wa_icon(18) ?><span>WhatsApp</span></a>
      <a class="btn btn-navy btn-sm apply-mobile" href="<?= url('apply.php') ?>">Apply now</a>
      <button class="burger" id="siteBurger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="siteNav"><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="siteNavScrim"></div>
