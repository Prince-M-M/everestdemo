<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

// "Get a quote": the low-friction path in the customer journey, for a
// family who isn't ready to apply yet. Four fields, one tap to send.
$branches = Branch::all(true);
$submitted = false;
$error = null;
$preselect = clean_str($_GET['plan'] ?? '', 60);
mark_form_loaded('quote');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('quote', 3)) {
        $error = 'Please take a moment to review your details and try again.';
    } else {
        $branchId = (int) ($_POST['branch_id'] ?? 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        $slug = clean_str($_POST['plan'] ?? '', 60);
        if (!$branch) {
            $error = 'Please choose your nearest branch.';
        } else {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'service_interest' => SERVICE_PLANS[$slug]['name'] ?? 'Not sure yet',
                'message' => 'Quote request. Cover for: ' . clean_str($_POST['cover_for'] ?? '', 40) . '.',
                'entry_point' => 'quote',
            ];
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

$page_title = 'Get a Funeral Plan Quote | Everest Funerals';
$meta_description = 'Get a free, no-obligation funeral plan quote from Everest Funerals. Cover from R70 a month, with payouts within 48 hours.';
$active_nav = 'quote';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero" style="background-image:url('<?= asset_img('white-roses.jpg') ?>');">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Get a quote</div>
    <h1>Get a free quote</h1>
    <p>Not ready to apply yet? Leave your details and a consultant will call you back with a personalised quote. No obligation.</p>
  </div>
</div>

<section>
  <div class="container split top">
    <div class="form-card" id="quote">
      <?php if ($submitted): ?>
        <h3>Thank you, we've received your request</h3>
        <p>A consultant from your nearest branch will be in touch shortly with your quote.</p>
        <p class="muted">Need help sooner? WhatsApp or call us now.</p>
        <div class="btn-row">
          <a class="btn btn-wa" href="<?= wa_href('quote-thanks') ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>WhatsApp us</a>
          <a class="btn btn-outline" href="tel:<?= h(COMPANY_PHONE_TEL) ?>">Call <?= h(COMPANY_PHONE_DISPLAY) ?></a>
        </div>
      <?php else: ?>
        <h3>Your details</h3>
        <p class="muted" style="margin-bottom:1.2rem;">Takes less than a minute.</p>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST" action="#quote">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label for="full_name">Full name</label><input type="text" id="full_name" name="full_name" required maxlength="150" autocomplete="name" value="<?= h($_POST['full_name'] ?? '') ?>"></div>
          <div class="field-row">
            <div class="field"><label for="phone">Phone or WhatsApp number</label><input type="tel" id="phone" name="phone" maxlength="30" autocomplete="tel" value="<?= h($_POST['phone'] ?? '') ?>"></div>
            <div class="field"><label for="email">Email (optional)</label><input type="email" id="email" name="email" maxlength="190" autocomplete="email" value="<?= h($_POST['email'] ?? '') ?>"></div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="plan">Plan</label>
              <select id="plan" name="plan">
                <option value="">Not sure yet</option>
                <?php $sel = $_POST['plan'] ?? $preselect; foreach (SERVICE_PLANS as $slug => $p): ?>
                  <option value="<?= h($slug) ?>" <?= $sel === $slug ? 'selected' : '' ?>><?= h($p['name']) ?> (from <?= h($p['from']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="cover_for">Who to cover</label>
              <select id="cover_for" name="cover_for">
                <option>My family</option><option>Just me</option><option>Me and my children</option><option>My parents</option><option>A group or society</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="branch_id">Nearest branch</label>
            <select id="branch_id" name="branch_id" required>
              <option value="">Select a branch</option>
              <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>" <?= (string) ($_POST['branch_id'] ?? '') === (string) $b['id'] ? 'selected' : '' ?>><?= h($b['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <button class="btn btn-navy btn-block" type="submit">Send my quote request</button>
          <p class="muted" style="font-size:.88rem;margin:.9rem 0 0;">We only use your details to prepare your quote.</p>
        </form>
      <?php endif; ?>
    </div>

    <div>
      <h2 style="font-size:2.3rem;">Ready now? Choose the quickest way.</h2>
      <div class="paths paths-stack">
        <a class="path path-wa" href="<?= wa_href('quote-page') ?>" target="_blank" rel="noopener">
          <span class="path-ico"><?= wa_icon(24) ?></span>
          <span><b>WhatsApp us</b><small>One tap opens a chat with our team. The fastest way to reach us.</small></span>
        </a>
        <a class="path" href="<?= url('apply.php') ?>">
          <span class="path-ico"><?= ev_icon('shield', 24) ?></span>
          <span><b>Apply online</b><small>Individual or family application, completed in one sitting.</small></span>
        </a>
        <a class="path" href="tel:<?= h(COMPANY_PHONE_TEL) ?>">
          <span class="path-ico"><?= ev_icon('phone', 24) ?></span>
          <span><b>Call <?= h(COMPANY_PHONE_DISPLAY) ?></b><small>Speak to a consultant at head office.</small></span>
        </a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
