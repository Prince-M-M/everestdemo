<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

// Funeral notices (obituaries). Families submit a notice here; it appears
// once our team has reviewed it, and visitors can then leave condolences.
$submitted = false;
$error = null;
mark_form_loaded('notice');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('notice', 4)) {
        $error = 'Please take a moment and try again.';
    } else {
        $result = Notice::save($_POST);
        if ($result['ok']) $submitted = true; else $error = $result['error'];
    }
}

$notices = Notice::published();

$page_title = 'Funeral Notices and Tributes | Everest Funerals';
$meta_description = 'Funeral notices and tributes from the families we serve. Share a notice for your loved one and receive condolences from friends and family.';
$active_nav = 'tributes';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('candles.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Notices and tributes</div>
    <h1>Notices and tributes</h1>
    <p>Share a funeral notice for your loved one, and let friends and family leave their condolences. Every message is reviewed by our team before it appears.</p>
  </div>
</div>

<section>
  <div class="container split top">
    <div>
      <span class="eyebrow">In loving memory</span>
      <h2>Recent notices</h2>
      <?php if (!$notices): ?>
        <p class="muted">There are no notices yet.</p>
      <?php endif; ?>
      <div class="notice-list">
        <?php foreach ($notices as $n): ?>
          <a class="notice-card" href="<?= url('notice.php?id=' . $n['id']) ?>">
            <span class="who"><?= h($n['deceased_name']) ?></span>
            <?php if ($n['date_of_birth'] || $n['date_of_death']): ?>
              <span class="dates"><?= $n['date_of_birth'] ? fmt_date($n['date_of_birth']) : '' ?><?= $n['date_of_birth'] && $n['date_of_death'] ? ' – ' : '' ?><?= $n['date_of_death'] ? fmt_date($n['date_of_death']) : '' ?></span>
            <?php endif; ?>
            <span class="excerpt"><?= h(mb_strimwidth($n['message'], 0, 180, '…')) ?></span>
            <span class="meta"><?= (int) $n['condolence_count'] ?> <?= (int) $n['condolence_count'] === 1 ? 'condolence' : 'condolences' ?> · Read and leave a message</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="form-card sticky-card" id="share">
      <?php if ($submitted): ?>
        <h3>Thank you</h3>
        <p>Your notice has been received. Our team will review it shortly, and it will appear here once approved.</p>
      <?php else: ?>
        <h3>Share a notice or tribute</h3>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST" action="#share">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label for="deceased_name">Name of your loved one</label><input type="text" id="deceased_name" name="deceased_name" required maxlength="150" value="<?= h($_POST['deceased_name'] ?? '') ?>"></div>
          <div class="field-row">
            <div class="field"><label for="date_of_birth">Date of birth (optional)</label><input type="date" id="date_of_birth" name="date_of_birth" value="<?= h($_POST['date_of_birth'] ?? '') ?>"></div>
            <div class="field"><label for="date_of_death">Date of passing (optional)</label><input type="date" id="date_of_death" name="date_of_death" value="<?= h($_POST['date_of_death'] ?? '') ?>"></div>
          </div>
          <div class="field"><label for="service_details">Funeral service details (optional)</label><input type="text" id="service_details" name="service_details" maxlength="500" placeholder="Date, time and place" value="<?= h($_POST['service_details'] ?? '') ?>"></div>
          <div class="field"><label for="message">Notice or tribute</label><textarea id="message" name="message" required maxlength="4000"><?= h($_POST['message'] ?? '') ?></textarea></div>
          <div class="field-row">
            <div class="field"><label for="submitted_by_name">Your name</label><input type="text" id="submitted_by_name" name="submitted_by_name" required maxlength="150" value="<?= h($_POST['submitted_by_name'] ?? '') ?>"></div>
            <div class="field"><label for="submitted_by_email">Your email (not shown)</label><input type="email" id="submitted_by_email" name="submitted_by_email" maxlength="190" value="<?= h($_POST['submitted_by_email'] ?? '') ?>"></div>
          </div>
          <button class="btn btn-navy btn-block" type="submit">Submit for review</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
