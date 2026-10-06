<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$page_title = 'Claim Process | Everest Funerals';
$meta_description = 'How to claim with Everest Funerals: submit a signed claim form with certified ID and bank statement, and approved claims are paid within 48 hours.';
$active_nav = 'claim';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('lake-dusk.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Claim process</div>
    <h1>How to claim</h1>
    <p>A simple, guided process, so you can focus on your family. Approved claims are paid within 48 hours.</p>
  </div>
</div>

<section>
  <div class="container split top">
    <div>
      <span class="eyebrow">Step by step</span>
      <h2>What happens when you claim</h2>
      <div class="steps">
        <?php foreach (CLAIM_STEPS as $s): ?>
          <div class="step"><div class="num"></div><div><h3><?= h($s['title']) ?></h3><p><?= h($s['text']) ?></p></div></div>
        <?php endforeach; ?>
      </div>
      <div class="btn-row">
        <a class="btn btn-navy" href="<?= h(CLAIM_PDF) ?>" target="_blank" rel="noopener"><?= ev_icon('download', 18) ?>Download the claim guide (PDF)</a>
      </div>
    </div>
    <div class="form-card sticky-card">
      <h3>What to bring</h3>
      <ul class="checklist">
        <li><?= ev_icon('check', 18) ?><span>A fully completed and signed claim form</span></li>
        <li><?= ev_icon('check', 18) ?><span>A certified copy of your ID</span></li>
        <li><?= ev_icon('check', 18) ?><span>A recent bank statement for the payout</span></li>
        <li><?= ev_icon('check', 18) ?><span>Proof that you are the next of kin</span></li>
      </ul>
      <p class="muted" style="font-size:.95rem;">Claims can only be paid on policies that are up to date. If you are unsure, call us and we'll check for you.</p>
      <a class="btn btn-navy btn-block" href="tel:<?= h(COMPANY_PHONE_TEL) ?>"><?= ev_icon('phone', 18) ?>Call <?= h(COMPANY_PHONE_DISPLAY) ?></a>
      <a class="btn btn-wa btn-block" style="margin-top:.7rem;" href="<?= wa_href('claims') ?>" target="_blank" rel="noopener">WhatsApp us</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
