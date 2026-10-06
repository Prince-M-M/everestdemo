<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$branches = Branch::all(true);
$submitted = false;
$error = null;
mark_form_loaded('preneed');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('preneed', 3)) {
        $error = 'Please take a moment to review your details and try again.';
    } else {
        $branchId = (int) ($_POST['branch_id'] ?? 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        if (!$branch) {
            $error = 'Please select a branch.';
        } else {
            $data = $_POST;
            $data['service_interest'] = 'Pre-need / advance planning';
            $data['entry_point'] = 'pre_need';
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

$page_title = 'Pre-Need Planning | Everest Funerals';
$meta_description = 'Plan ahead with Everest Funerals. Advance planning gives your family peace of mind and removes uncertainty at a difficult time.';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Pre-Need Planning</div>
    <h1>Plan ahead, with peace of mind</h1>
    <p>Advance planning is a loving gesture for your family — it removes uncertainty and hard decisions at an already difficult time.</p>
  </div>
</div>

<section>
  <div class="container grid g2" style="align-items:flex-start;">
    <div>
      <h2>Why plan ahead?</h2>
      <ul class="checklist">
        <li><?= ev_icon('check', 18) ?><span>Your family won't need to make difficult financial decisions during grief.</span></li>
        <li><?= ev_icon('check', 18) ?><span>You choose the plan and service that reflects your own wishes.</span></li>
        <li><?= ev_icon('check', 18) ?><span>Costs are settled in advance, on terms that suit you.</span></li>
        <li><?= ev_icon('check', 18) ?><span>One less thing for your loved ones to worry about.</span></li>
      </ul>
      <p class="muted">Speak to our team about which of our plans best suits advance planning for you and your family.</p>
      <a class="btn btn-outline" href="<?= url('services.php') ?>">View our plans</a>
    </div>

    <div class="form-card">
      <?php if ($submitted): ?>
        <h3>Thank you</h3>
        <p>We've received your enquiry about advance planning and will be in touch to discuss your options.</p>
      <?php else: ?>
        <h3>Start planning ahead</h3>
        <p class="muted" style="margin-bottom:1.2rem;">Tell us a little about what you're looking for, no obligation.</p>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label for="full_name">Full name</label><input type="text" id="full_name" name="full_name" required maxlength="150"></div>
          <div class="field-row">
            <div class="field"><label for="phone">Phone</label><input type="tel" id="phone" name="phone" maxlength="30"></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" maxlength="190"></div>
          </div>
          <div class="field">
            <label for="branch_id">Branch</label>
            <select id="branch_id" name="branch_id" required>
              <option value="">Select a branch...</option>
              <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>"><?= h($b['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="field"><label for="message">Anything specific you'd like to know? (optional)</label><textarea id="message" name="message" maxlength="1800"></textarea></div>
          <button class="btn btn-navy btn-block" type="submit">Send enquiry</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
