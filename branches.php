<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$branches = Branch::all(true);

$page_title = 'Our Branches | Everest Funerals';
$meta_description = 'Find your nearest Everest Funerals branch across Rustenburg and the surrounding region.';
$active_nav = 'branches';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Branches</div>
    <h1>Our Branches</h1>
    <p>Six branches serving Rustenburg and the surrounding region. Find the one nearest you.</p>
  </div>
</div>

<section>
  <div class="container">
    <div class="grid g3">
      <?php foreach ($branches as $b): ?>
        <div class="card branch-card">
          <h3><?= h($b['name']) ?></h3>
          <?php if ($b['address']): ?><div class="row"><?= ev_icon('pin', 18) ?><span><?= h($b['address']) ?></span></div><?php endif; ?>
          <?php if ($b['phone']): ?><div class="row"><?= ev_icon('phone', 18) ?><a href="tel:<?= h($b['phone']) ?>"><?= h($b['phone']) ?></a></div><?php endif; ?>
          <div class="actions">
            <a class="btn btn-wa btn-sm" href="<?= wa_href('branches', (int) $b['id']) ?>" target="_blank" rel="noopener">WhatsApp this branch</a>
            <a class="btn btn-outline btn-sm" href="<?= url('contact.php') ?>">Contact this branch</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (!$branches): ?>
      <p class="muted center">Branch details are being updated — please call us on <?= h(COMPANY_PHONE_DISPLAY) ?>.</p>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
