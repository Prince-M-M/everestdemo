<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$branches = Branch::all(true);
$submitted = false;
$error = null;
mark_form_loaded('contact');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    if (honeypot_tripped()) {
        $submitted = true; // pretend success to the bot
    } elseif (!form_timing_ok('contact', 3)) {
        $error = 'Please take a moment to review your details and try again.';
    } else {
        $branchId = (int) ($_POST['branch_id'] ?? 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        if (!$branch) {
            $error = 'Please select a branch.';
        } else {
            $data = $_POST;
            $data['entry_point'] = 'enquiry';
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

$page_title = 'Contact Us | Everest Funerals';
$meta_description = 'Get in touch with Everest Funerals. Call, WhatsApp, or send us your details and a member of our team will contact you shortly.';
$active_nav = 'contact';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Contact Us</div>
    <h1>Get in touch</h1>
    <p>Whether you need immediate assistance or simply want more information, we're here for you.</p>
  </div>
</div>

<section>
  <div class="container split top">
    <div>
      <h2>We'd love to hear from you</h2>
      <p class="muted">Call or WhatsApp us directly for urgent matters, or fill in the form and a member of our team will be in touch as soon as possible.</p>

      <div class="contact-list">
        <a href="tel:<?= h(COMPANY_PHONE_TEL) ?>"><span class="ico"><?= ev_icon('phone', 22) ?></span><span><small>Call head office</small><b><?= h(COMPANY_PHONE_DISPLAY) ?></b></span></a>
        <a href="mailto:<?= h(COMPANY_EMAIL) ?>"><span class="ico"><?= ev_icon('mail', 22) ?></span><span><small>Email us</small><b><?= h(COMPANY_EMAIL) ?></b></span></a>
        <a href="<?= h(COMPANY_MAPS) ?>" target="_blank" rel="noopener"><span class="ico"><?= ev_icon('pin', 22) ?></span><span><small>Visit head office</small><b><?= h(COMPANY_ADDRESS) ?></b></span></a>
        <a href="<?= wa_href('contact') ?>" target="_blank" rel="noopener"><span class="ico"><svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22" aria-hidden="true"><path d="M20.5 3.5A11.8 11.8 0 0 0 12 0 12 12 0 0 0 1.6 17.9L0 24l6.3-1.6A12 12 0 1 0 20.5 3.5zM12 21.9a9.9 9.9 0 0 1-5-1.4l-.4-.2-3.7 1 1-3.6-.3-.4A9.9 9.9 0 1 1 12 21.9z"/></svg></span><span><small>WhatsApp</small><b>Chat with us now</b></span></a>
      </div>
      <p class="muted" style="margin-top:1.6rem;font-size:.95rem;">Looking for a specific branch? <a href="<?= url('branches.php') ?>">See all 6 branches</a></p>
    </div>

    <div class="form-card">
      <?php if ($submitted): ?>
        <h3>Thank you</h3>
        <p>We've received your enquiry and someone from our team will be in touch with you shortly.</p>
        <p class="muted">For urgent matters, please call us on <strong><?= h(COMPANY_PHONE_DISPLAY) ?></strong>.</p>
      <?php else: ?>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>

          <div class="field">
            <label for="branch_id">Nearest branch</label>
            <select id="branch_id" name="branch_id" required>
              <option value="">Select a branch...</option>
              <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>"><?= h($b['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="full_name">Full name</label>
            <input type="text" id="full_name" name="full_name" required maxlength="150">
          </div>
          <div class="field-row">
            <div class="field"><label for="phone">Phone</label><input type="tel" id="phone" name="phone" maxlength="30"></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" maxlength="190"></div>
          </div>
          <div class="field">
            <label for="service_interest">What can we help with?</label>
            <select id="service_interest" name="service_interest">
              <option value="">Select an option...</option>
              <?php foreach (SERVICE_PLANS as $plan): ?><option><?= h($plan['name']) ?></option><?php endforeach; ?>
              <option>Pre-need / advance planning</option>
              <option>General enquiry</option>
            </select>
          </div>
          <div class="field">
            <label for="message">Message (optional)</label>
            <textarea id="message" name="message" maxlength="2000"></textarea>
          </div>
          <button class="btn btn-navy btn-block" type="submit">Send enquiry</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
