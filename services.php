<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$page_title = 'Funeral Plans | Everest Funerals';
$meta_description = 'Compare Everest Funerals\' plans: Care Plan, Pensioners Plan, Social Scheme, Grocery Plan, Essential Plan and Prestige Plan. Cover from R70 a month.';
$active_nav = 'services';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Funeral plans</div>
    <h1>Funeral plans and services</h1>
    <p>Six plans for different families and budgets. Every one includes a full funeral service, delivered with the same care and dignity.</p>
    <div class="hero-meta">
      <span class="pill pill-gold">Cover from R70 a month</span>
      <span class="pill">Payouts within 48 hours</span>
      <span class="pill">You choose your debit day</span>
    </div>
  </div>
</div>

<section>
  <div class="container">
    <div class="grid g3">
      <?php foreach (SERVICE_PLANS as $slug => $plan): ?>
        <a class="card plan-card" href="<?= url('plan.php?slug=' . $slug) ?>">
          <div class="thumb">
            <img src="<?= asset_img($plan['image']) ?>" alt="" loading="lazy">
            <span class="pill"><?= h($plan['badge']) ?></span>
          </div>
          <div class="card-body">
            <h3><?= h($plan['name']) ?></h3>
            <div class="price-line">from <b><?= h($plan['from']) ?></b> a month</div>
            <p class="muted" style="font-size:.95rem;"><?= h($plan['blurb']) ?></p>
            <ul>
              <li><?= ev_icon('check', 16) ?><span>Up to <?= h($plan['cover']) ?> main member cover</span></li>
              <?php foreach (array_slice($plan['extras'] ?? array_slice($plan['services'], 4), 0, 3) as $inc): ?>
                <li><?= ev_icon('check', 16) ?><span><?= h($inc) ?></span></li>
              <?php endforeach; ?>
            </ul>
            <span class="go">See premiums and full details</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-white" id="funeral-services">
  <div class="container">
    <div class="sec-head-row">
      <div class="sec-head">
        <span class="eyebrow">Funeral services</span>
        <h2>Arranging a funeral without a plan</h2>
      </div>
      <p class="lead" style="max-width:440px;margin:0;">Whether or not your loved one had cover with us, we can arrange the full service. Ask for a quote and we'll explain every cost up front.</p>
    </div>
    <div class="grid g4">
      <?php foreach (FUNERAL_SERVICES as $key => $svc): $svc['from'] = Setting::get('price_' . $key) ?: null; // set in Settings ?>
        <div class="card plan-card service-card">
          <div class="thumb"><img src="<?= asset_img($svc['image']) ?>" alt="" loading="lazy"></div>
          <div class="card-body">
            <h3><?= h($svc['name']) ?></h3>
            <?php if ($svc['from']): ?>
              <div class="price-line">from <b><?= h($svc['from']) ?></b></div>
            <?php else: ?>
              <span class="quote-on-request">Quote on request</span>
            <?php endif; ?>
            <p class="muted" style="font-size:.95rem;"><?= h($svc['text']) ?></p>
            <div style="margin-top:auto;display:flex;gap:.5rem;flex-wrap:wrap;">
              <a class="btn btn-outline btn-sm" href="<?= url('quote.php') ?>">Get a quote</a>
              <a class="btn btn-wa btn-sm" href="<?= wa_href('service-' . $key) ?>" target="_blank" rel="noopener"><?= wa_icon(16) ?>WhatsApp</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-cream">
  <div class="container">
    <div class="sec-head center">
      <span class="eyebrow">Included in every plan</span>
      <h2>The essentials, taken care of.</h2>
      <p class="lead">Whichever plan you choose, our team handles the practical arrangements so your family can focus on saying goodbye.</p>
    </div>
    <ul class="incl" style="max-width:820px;margin-inline:auto;">
      <?php foreach (PLAN_BASE_SERVICES as $s): ?><li><?= ev_icon('check', 16) ?><span><?= h($s) ?></span></li><?php endforeach; ?>
    </ul>
    <p class="muted center" style="font-size:.9rem;margin-top:1.4rem;">The Grocery Plan includes a shorter list of services; see its page for details.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="image-band">
      <img src="<?= asset_img('doves.jpg') ?>" alt="" loading="lazy">
      <div>
        <h2>Not sure which plan is right for your family?</h2>
        <p>Our team will talk you through your options, with no obligation.</p>
        <div class="btn-row">
          <a class="btn btn-wa" href="<?= wa_href('services-cta') ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>Ask on WhatsApp</a>
          <a class="btn btn-gold" href="<?= url('quote.php') ?>">Get a quote</a>
          <a class="btn btn-outline-light" href="<?= url('apply.php') ?>">Apply for a plan</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
