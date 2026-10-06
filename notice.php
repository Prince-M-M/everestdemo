<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$id = (int) ($_GET['id'] ?? 0);
$notice = $id ? Notice::find($id) : null;
if (!$notice || $notice['status'] !== 'approved') {
    http_response_code(404);
    $notice = null;
}
$submitted = false;
$error = null;
mark_form_loaded('condolence');

if ($notice && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('condolence', 3)) {
        $error = 'Please take a moment and try again.';
    } else {
        $r = Notice::addCondolence($id, $_POST);
        if ($r['ok']) $submitted = true; else $error = $r['error'];
    }
}
$condolences = $notice ? Notice::condolences($id) : [];

$page_title = $notice ? 'In loving memory of ' . $notice['deceased_name'] . ' | Everest Funerals' : 'Notice not found | Everest Funerals';
$meta_description = $notice ? mb_strimwidth($notice['message'], 0, 155, '…') : '';
$active_nav = 'tributes';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('candles.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / <a href="<?= url('tributes.php') ?>">Notices and tributes</a></div>
    <?php if ($notice): ?>
      <p style="margin-bottom:.4rem;">In loving memory of</p>
      <h1><?= h($notice['deceased_name']) ?></h1>
      <?php if ($notice['date_of_birth'] || $notice['date_of_death']): ?>
        <p><?= $notice['date_of_birth'] ? fmt_date($notice['date_of_birth']) : '' ?><?= $notice['date_of_birth'] && $notice['date_of_death'] ? ' – ' : '' ?><?= $notice['date_of_death'] ? fmt_date($notice['date_of_death']) : '' ?></p>
      <?php endif; ?>
    <?php else: ?>
      <h1>Notice not found</h1>
      <p>This notice may have been removed. <a href="<?= url('tributes.php') ?>" style="color:#fff;">See all notices</a>.</p>
    <?php endif; ?>
  </div>
</div>

<?php if ($notice): ?>
<section>
  <div class="container split top">
    <div>
      <article class="notice-body">
        <?= nl2br(h($notice['message'])) ?>
        <p class="muted" style="margin-top:1.2rem;">Shared by <?= h($notice['submitted_by_name']) ?></p>
      </article>
      <?php if ($notice['service_details']): ?>
        <div class="notice-banner" style="margin-top:1.4rem;"><strong>Funeral service:</strong> <?= h($notice['service_details']) ?></div>
      <?php endif; ?>

      <h2 style="margin-top:2.4rem;">Condolences</h2>
      <?php if (!$condolences): ?><p class="muted">Be the first to leave a message for the family.</p><?php endif; ?>
      <div class="condolences">
        <?php foreach ($condolences as $c): ?>
          <figure class="tribute">
            <blockquote style="margin:0;"><?= nl2br(h($c['message'])) ?></blockquote>
            <figcaption class="date" style="margin:.6rem 0 0;"><?= h($c['name']) ?> · <?= fmt_date(substr($c['created_at'], 0, 10)) ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="form-card sticky-card" id="condolence">
      <?php if ($submitted): ?>
        <h3>Thank you</h3>
        <p>Your message has been received and will appear once our team has reviewed it.</p>
      <?php else: ?>
        <h3>Leave a condolence</h3>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST" action="#condolence">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label for="c_name">Your name</label><input type="text" id="c_name" name="name" required maxlength="150" value="<?= h($_POST['name'] ?? '') ?>"></div>
          <div class="field"><label for="c_message">Your message to the family</label><textarea id="c_message" name="message" required maxlength="1500"><?= h($_POST['message'] ?? '') ?></textarea></div>
          <button class="btn btn-navy btn-block" type="submit">Send condolence</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/_site_footer.php'; ?>
