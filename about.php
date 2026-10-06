<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$page_title = 'About Us | Everest Funerals';
$meta_description = 'Learn about Everest Funerals: established in 2007, providing funeral insurance and funeral services to families across South Africa with care and dignity.';
$active_nav = 'about';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('savanna-sunset.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / About us</div>
    <h1>In time of need.</h1>
    <p>It is our honour and privilege to help you and your family through one of life's hardest moments.</p>
  </div>
</div>

<section>
  <div class="container split">
    <div>
      <span class="eyebrow">Our story</span>
      <h2>Established in <?= COMPANY_FOUNDED ?>, always by your side.</h2>
      <p class="lead">Everest Funerals is a South African company providing funerals and funeral insurance. We build funeral products and services around a wide range of family needs.</p>
      <p>Our portfolio has two streams, insurance and funeral services, so the people who cover you are the people who care for your family on the day. Through our network of professional funeral parlours in South Africa and beyond, we offer a one-stop service marked by care and quality.</p>
      <p><a class="text-link" href="<?= url('team.php') ?>">Meet our team and see our credentials</a></p>
      <p>We understand this is no ordinary industry. Losing a loved one is emotionally and financially challenging, and we go well beyond the basic financial requirements so you can say goodbye the way you would like.</p>
    </div>
    <div class="collage">
      <div class="ph ph-a"><img src="<?= asset_img('doves.jpg') ?>" alt="White doves flying through soft morning light" loading="lazy"></div>
      <div class="ph ph-b"><img src="<?= asset_img('white-roses.jpg') ?>" alt="White roses" loading="lazy"></div>
    </div>
  </div>
</section>

<section class="bg-cream">
  <div class="container grid g2">
    <div class="card" style="padding:2.2rem;background:var(--navy);border-color:var(--navy);">
      <span class="eyebrow" style="color:var(--gold);">Our mission</span>
      <p style="color:#fff;font:500 1.7rem/1.3 var(--serif);margin:0;"><?= h(COMPANY_MISSION) ?></p>
    </div>
    <div class="card" style="padding:2.2rem;">
      <span class="eyebrow">Our vision</span>
      <p style="color:var(--navy);font:500 1.7rem/1.3 var(--serif);margin:0;"><?= h(COMPANY_VISION) ?></p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="sec-head center"><span class="eyebrow">What we promise</span><h2>So much more than just a pay-out.</h2></div>
    <div class="grid g4">
      <?php foreach (TRUST_BADGES as $b): ?>
        <div class="promise" style="flex-direction:column;">
          <span class="ico"><?= ev_icon($b['icon'], 22) ?></span>
          <div><h4><?= h($b['label']) ?></h4><p><?= h($b['text']) ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-navy">
  <div class="container stats-row">
    <div><div class="n"><?= COMPANY_FOUNDED ?></div><div class="l">Year we were established</div></div>
    <div><div class="n">6</div><div class="l">Branches in the region</div></div>
    <div><div class="n">48h</div><div class="l">Claim payout after documents</div></div>
    <div><div class="n">6</div><div class="l">Plans to choose from</div></div>
  </div>
</section>

<section>
  <div class="container">
    <div class="sec-head center"><span class="eyebrow">Who we cover</span><h2>Wherever your family is, we're here for you.</h2></div>
    <div class="cover-grid">
      <?php foreach (WHO_WE_COVER as $w): ?>
        <div class="cover-item"><div class="ico"><?= ev_icon($w['icon'], 26) ?></div><span><?= h($w['label']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="container" style="padding-bottom:clamp(3rem,6vw,5rem);">
  <div class="notice-banner" style="margin-bottom:2.4rem;">
    <strong>Please note:</strong> We do not currently have any offices in Pretoria, nor any relationship with a funeral service provider in Pretoria calling themselves Everest Funerals.
  </div>
  <div class="center">
    <h2>Rest assured, when you need us, we'll be by your side.</h2>
    <div class="btn-row">
      <a class="btn btn-navy" href="<?= url('contact.php') ?>">Contact us</a>
      <a class="btn btn-outline" href="<?= url('team.php') ?>">Meet our team</a>
    </div>
  </div>
</div>

<?php require __DIR__ . '/_site_footer.php'; ?>
