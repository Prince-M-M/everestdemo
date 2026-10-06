<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$cat = isset(Article::CATEGORIES[$_GET['type'] ?? '']) ? $_GET['type'] : null;
$articles = Article::published($cat);

$page_title = 'Funeral Guides and Advice | Everest Funerals';
$meta_description = 'Practical, gentle guidance for families: what to do when someone dies, burial or cremation, choosing a funeral plan and more.';
$active_nav = 'guides';
require __DIR__ . '/_site_header.php';
?>
<div class="page-hero" style="background-image:url('<?= asset_img('lake-dusk.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Guides</div>
    <h1>Guides and advice</h1>
    <p>Clear, gentle answers to the questions families ask us most, written by our team.</p>
  </div>
</div>
<section>
  <div class="container">
    <nav class="guide-tabs" aria-label="Filter guides">
      <a href="<?= url('guides.php') ?>" class="<?= $cat === null ? 'active' : '' ?>">All</a>
      <?php foreach (Article::CATEGORIES as $k => $v): ?><a href="<?= url('guides.php?type=' . $k) ?>" class="<?= $cat === $k ? 'active' : '' ?>"><?= h($v) ?></a><?php endforeach; ?>
    </nav>
    <?php if (!$articles): ?><p class="muted">New guides are on their way. In the meantime, <a href="<?= wa_href('guides') ?>">WhatsApp us</a> with any question.</p><?php endif; ?>
    <div class="grid g3">
      <?php foreach ($articles as $a): ?>
        <a class="card plan-card guide-card" href="<?= url('guide.php?slug=' . urlencode($a['slug'])) ?>">
          <div class="card-body">
            <span class="eyebrow"><?= h(Article::CATEGORIES[$a['category']]) ?></span>
            <h3><?= h($a['title']) ?></h3>
            <p class="muted" style="font-size:.95rem;"><?= h($a['summary'] ?? mb_strimwidth(strip_tags($a['body']), 0, 160, '…')) ?></p>
            <span class="go">Read the guide</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/_site_footer.php'; ?>
