<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$page_title = 'Our Team and Credentials | Everest Funerals';
$meta_description = 'Meet the funeral directors and staff of Everest Funerals, and see our registrations and credentials.';
$active_nav = 'about';
// Profiles are managed in the dashboard (Website content > Team profiles).
// Until the first one is added, the placeholder layout from site-data.php shows.
$members = TeamMember::published();
if (!$members) $members = array_map(fn($m) => $m + ['branch_label' => $m['branch'], 'photo' => null], TEAM_MEMBERS);
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('doves.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / <a href="<?= url('about.php') ?>">About us</a> / Our team</div>
    <h1>The people who care for your family</h1>
    <p>Our funeral directors and consultants are with you from the first call to the final goodbye.</p>
  </div>
</div>

<section>
  <div class="container">
    <div class="sec-head"><span class="eyebrow">Our team</span><h2>Meet our team</h2></div>
    <div class="grid g4 team-grid">
      <?php foreach ($members as $m):
        $initials = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($m['name'], '[] ')), 0, 2))); ?>
        <article class="card team-card">
          <div class="team-photo">
            <?php if (!empty($m['photo'])): ?>
              <img src="<?= url(Upload::PUBLIC_DIR . $m['photo']) ?>" alt="<?= h($m['name']) ?>" loading="lazy">
            <?php else: ?>
              <span aria-hidden="true"><?= h(empty($m['placeholder']) ? $initials : 'EF') ?></span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <h3><?= h($m['name']) ?></h3>
            <p class="team-role"><?= h($m['role']) ?></p>
            <p class="muted team-branch"><?= ev_icon('pin', 15) ?><?= h($m['branch_label'] ?? '') ?></p>
            <p class="team-bio"><?= h($m['bio'] ?? '') ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-navy">
  <div class="container">
    <div class="sec-head"><span class="eyebrow">Credentials</span><h2>Registered, established and accountable</h2></div>
    <div class="grid g4">
      <?php
        // Registration numbers come from Settings; anything left blank is hidden.
        $creds = [['label' => 'Established', 'value' => (string) COMPANY_FOUNDED]];
        if (Setting::get('credential_fsp') !== '') $creds[] = ['label' => 'Funeral insurance: FSP licence', 'value' => Setting::get('credential_fsp')];
        if (Setting::get('credential_coc') !== '') $creds[] = ['label' => 'Funeral parlour: certificate of competence', 'value' => Setting::get('credential_coc')];
        $creds[] = ['label' => 'Branches', 'value' => '6 in the North West region'];
        foreach ($creds as $c): ?>
        <div class="cred"><small><?= h($c['label']) ?></small><b><?= h($c['value']) ?></b></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section>
  <div class="container center">
    <h2>Speak to someone today</h2>
    <p class="lead">A real person will answer, by WhatsApp or by phone.</p>
    <div class="btn-row">
      <a class="btn btn-wa" href="<?= wa_href('team') ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>WhatsApp us</a>
      <a class="btn btn-outline" href="tel:<?= h(COMPANY_PHONE_TEL) ?>">Call <?= h(COMPANY_PHONE_DISPLAY) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
