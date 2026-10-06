<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$slug = clean_str($_GET['slug'] ?? '', 100);
$plan = SERVICE_PLANS[$slug] ?? null;
if (!$plan) { http_response_code(404); $plan = reset(SERVICE_PLANS); $slug = array_key_first(SERVICE_PLANS); }

$branches = Branch::all(true);
$submitted = false;
$error = null;
mark_form_loaded('plan_' . $slug);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('plan_' . $slug, 3)) {
        $error = 'Please take a moment to review your details and try again.';
    } else {
        $branchId = (int) ($_POST['branch_id'] ?? 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        if (!$branch) {
            $error = 'Please select a branch.';
        } else {
            $data = $_POST;
            $data['service_interest'] = $plan['name'];
            $data['entry_point'] = 'quote';
            $result = Lead::create($branchId, $data, 'website');
            if ($result['ok']) {
                $lead = Lead::find($result['id']);
                Notifier::newLeadAdmin($lead);
                if (!empty($lead['email'])) Notifier::leadReceivedCustomer($lead);
                $submitted = true;
            } else {
                $error = $result['error'];
            }
        }
    }
}

$page_title = $plan['name'] . ' | Everest Funerals';
$meta_description = $plan['name'] . ' from Everest Funerals — ' . $plan['blurb'];
$active_nav = 'services';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img($plan['image']) ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / <a href="<?= url('services.php') ?>">Funeral plans</a> / <?= h($plan['name']) ?></div>
    <h1><?= h($plan['name']) ?></h1>
    <p><?= h($plan['blurb']) ?></p>
    <div class="hero-meta">
      <span class="pill pill-gold">From <?= h($plan['from']) ?> a month</span>
      <span class="pill">Up to <?= h($plan['cover']) ?> main member cover</span>
      <span class="pill"><?= h($plan['badge']) ?></span>
    </div>
  </div>
</div>

<section>
  <div class="container split top plan-detail">
    <div>
      <div class="block">
        <h2 style="font-size:2.3rem;">Monthly premiums</h2>
        <?= plan_premiums_table($plan) ?>
      </div>
      <?php if (!empty($plan['covers'])): ?>
        <div class="block">
          <h2 style="font-size:2.3rem;">Cover per person</h2>
          <?= plan_cover_amounts($plan) ?>
        </div>
      <?php endif; ?>
      <div class="block">
        <h2 style="font-size:2.3rem;">What's included</h2>
        <p class="muted">Funeral services for the main member. Children's cover includes a scaled version of this service.</p>
        <?= plan_services_list($plan) ?>
      </div>
      <div class="badges" style="margin-top:0;">
        <?php foreach (array_slice(TRUST_BADGES, 2, 2) as $b): ?>
          <div class="badge-card"><span class="ico"><?= ev_icon($b['icon'], 22) ?></span><h4><?= h($b['label']) ?></h4></div>
        <?php endforeach; ?>
      </div>
      <div class="btn-row" style="margin-top:1.6rem;">
        <a class="btn btn-wa" href="<?= wa_href('plan-' . $slug) ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>Ask about this plan on WhatsApp</a>
        <a class="btn btn-navy" href="<?= url('apply.php') ?>">Apply now</a>
      </div>
      <p style="margin-top:1.6rem;"><a class="text-link" href="<?= h($plan['pdf']) ?>" target="_blank" rel="noopener"><?= ev_icon('download', 18) ?>Download the plan brochure (PDF)</a></p>
    </div>

    <div class="form-card sticky-card" id="quote">
      <?php if ($submitted): ?>
        <h3>Thank you</h3>
        <p>We've received your interest in the <?= h($plan['name']) ?> and will contact you with a personalised quote shortly.</p>
        <p class="muted">Need us urgently? Call <a href="tel:<?= h(COMPANY_PHONE_TEL) ?>"><?= h(COMPANY_PHONE_DISPLAY) ?></a>.</p>
      <?php else: ?>
        <h3>Get a quote for the <?= h($plan['name']) ?></h3>
        <p class="muted" style="margin-bottom:1.2rem;">No obligation. We'll come back to you with the details.</p>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST" action="#quote">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label for="full_name">Full name</label><input type="text" id="full_name" name="full_name" required maxlength="150" autocomplete="name"></div>
          <div class="field-row">
            <div class="field"><label for="phone">Phone</label><input type="tel" id="phone" name="phone" maxlength="30" autocomplete="tel"></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" maxlength="190" autocomplete="email"></div>
          </div>
          <div class="field">
            <label for="branch_id">Nearest branch</label>
            <select id="branch_id" name="branch_id" required>
              <option value="">Select a branch</option>
              <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>"><?= h($b['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <button class="btn btn-navy btn-block" type="submit">Request my quote</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="bg-cream">
  <div class="container">
    <div class="sec-head center"><span class="eyebrow">Compare</span><h2>Other plans you may like</h2></div>
    <div class="grid g3">
      <?php $others = array_filter(SERVICE_PLANS, fn($k) => $k !== $slug, ARRAY_FILTER_USE_KEY);
      foreach (array_slice($others, 0, 3, true) as $oslug => $oplan): ?>
        <a class="card plan-card" href="<?= url('plan.php?slug=' . $oslug) ?>">
          <div class="thumb"><img src="<?= asset_img($oplan['image']) ?>" alt="" loading="lazy"><span class="pill"><?= h($oplan['badge']) ?></span></div>
          <div class="card-body">
            <h3><?= h($oplan['name']) ?></h3>
            <div class="price-line">from <b><?= h($oplan['from']) ?></b> a month</div>
            <p class="muted" style="font-size:.95rem;"><?= h($oplan['blurb']) ?></p>
            <span class="go">See this plan</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
