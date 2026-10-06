<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$slug = clean_str($_GET['slug'] ?? '', 160);
$article = $slug !== '' ? Article::findPublishedBySlug($slug) : null;
if (!$article) http_response_code(404);
$more = $article ? array_values(array_filter(Article::published(null, 4), fn($a) => $a['id'] !== $article['id'])) : [];

$page_title = ($article ? $article['title'] : 'Guide not found') . ' | Everest Funerals';
$meta_description = $article['summary'] ?? '';
$active_nav = 'guides';
require __DIR__ . '/_site_header.php';
?>
<div class="page-hero" style="background-image:url('<?= asset_img('lake-dusk.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / <a href="<?= url('guides.php') ?>">Guides</a></div>
    <h1><?= h($article['title'] ?? 'Guide not found') ?></h1>
    <?php if ($article && $article['summary']): ?><p><?= h($article['summary']) ?></p><?php endif; ?>
  </div>
</div>
<section>
  <div class="container split top">
    <article class="prose">
      <?php if ($article): ?>
        <?= Article::render($article['body']) ?>
        <p class="muted" style="font-size:.9rem;margin-top:2rem;">Updated <?= fmt_date(substr($article['updated_at'], 0, 10)) ?></p>
      <?php else: ?>
        <p>This guide may have moved. <a href="<?= url('guides.php') ?>">See all guides</a>.</p>
      <?php endif; ?>
    </article>
    <aside>
      <div class="form-card sticky-card">
        <h3>Need help right now?</h3>
        <p class="muted">A real person will answer, by WhatsApp or phone.</p>
        <a class="btn btn-wa btn-block" href="<?= wa_href('guide') ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>WhatsApp us</a>
        <a class="btn btn-outline btn-block" style="margin-top:.7rem;" href="tel:<?= h(COMPANY_PHONE_TEL) ?>">Call <?= h(COMPANY_PHONE_DISPLAY) ?></a>
        <?php if ($more): ?>
          <h4 style="margin-top:1.6rem;font-family:var(--font);font-size:1rem;">More guides</h4>
          <ul class="checklist" style="margin:0;"><?php foreach (array_slice($more, 0, 3) as $m): ?><li><?= ev_icon('check', 16) ?><a href="<?= url('guide.php?slug=' . urlencode($m['slug'])) ?>"><?= h($m['title']) ?></a></li><?php endforeach; ?></ul>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/_site_footer.php'; ?>
