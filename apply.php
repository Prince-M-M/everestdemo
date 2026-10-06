<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$branches = Branch::all(true);
$submitted = false;
$error = null;
mark_form_loaded('apply');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    if (honeypot_tripped()) {
        $submitted = true;
    } elseif (!form_timing_ok('apply', 3)) {
        $error = 'Please take a moment to review your details and try again.';
    } elseif (empty($_POST['accept_terms'])) {
        $error = 'Please accept the Terms and Conditions to continue.';
    } else {
        $branchId = (int) ($_POST['branch_id'] ?? 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        if (!$branch) {
            $error = 'Please select a branch.';
        } else {
            $applicantType = in_array($_POST['applicant_type'] ?? '', ['individual', 'family'], true) ? $_POST['applicant_type'] : 'individual';
            $planName = clean_str($_POST['plan'] ?? '', 100);
            $data = $_POST;
            $data['service_interest'] = $planName;
            $data['message'] = ($applicantType === 'family' ? 'Family/group application. ' : 'Individual application. ')
                . 'Dependants: ' . clean_str($_POST['dependants'] ?? '0', 10) . '. '
                . clean_str($_POST['message'] ?? '', 1800);

            $data['entry_point'] = 'application';
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

$page_title = 'Apply Now | Everest Funerals';
$meta_description = 'Apply for an Everest Funerals plan today. Individual and family applications, processed quickly by our team.';
$active_nav = 'apply';
require __DIR__ . '/_site_header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="crumb"><a href="<?= url('index.php') ?>">Home</a> / Apply Now</div>
    <h1>Apply for a plan</h1>
    <p>Ready to secure peace of mind for your family? Complete the application below and our team will guide you through the rest.</p>
  </div>
</div>

<section>
  <div class="container" style="max-width:720px;">
    <div class="form-card">
      <?php if ($submitted): ?>
        <h3>Application received</h3>
        <p>Thank you for applying with Everest Funerals. A member of our team will contact you shortly to complete your application and answer any questions.</p>
        <p class="muted">For urgent matters, please call us on <strong><?= h(COMPANY_PHONE_DISPLAY) ?></strong>.</p>
      <?php else: ?>
        <?php if ($error): ?><div class="flash flash-error"><?= h($error) ?></div><?php endif; ?>
        <form method="POST">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true"><label for="website_url">Leave blank</label><input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off"></div>

          <div class="field">
            <label>Application type</label>
            <div style="display:flex;gap:1.4rem;margin-top:.4rem;">
              <label class="check" style="align-items:center;"><input type="radio" name="applicant_type" value="individual" checked> Individual</label>
              <label class="check" style="align-items:center;"><input type="radio" name="applicant_type" value="family"> Family / Group</label>
            </div>
          </div>

          <div class="field-row">
            <div class="field"><label for="full_name">Full name</label><input type="text" id="full_name" name="full_name" required maxlength="150"></div>
            <div class="field"><label for="dependants">Number of dependants</label><input type="number" id="dependants" name="dependants" min="0" max="20" value="0"></div>
          </div>
          <div class="field-row">
            <div class="field"><label for="phone">Phone</label><input type="tel" id="phone" name="phone" required maxlength="30"></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" maxlength="190"></div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="plan">Plan you're applying for</label>
              <select id="plan" name="plan" required>
                <option value="">Select a plan...</option>
                <?php foreach (SERVICE_PLANS as $plan): ?><option><?= h($plan['name']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="branch_id">Branch</label>
              <select id="branch_id" name="branch_id" required>
                <option value="">Select a branch...</option>
                <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>"><?= h($b['name']) ?></option><?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="field-row">
            <div class="field"><label for="date_of_birth">Main member's date of birth</label><input type="date" id="date_of_birth" name="date_of_birth" max="<?= date('Y-m-d') ?>" value="<?= h($_POST['date_of_birth'] ?? '') ?>"></div>
            <div class="field" style="display:flex;align-items:flex-end;"><span class="muted" style="font-size:.88rem;">Helps us prepare the right premium for your age group.</span></div>
          </div>
          <div class="field">
            <label for="message">Anything else we should know? (optional)</label>
            <textarea id="message" name="message" maxlength="1800"></textarea>
          </div>
          <label class="check" style="margin-bottom:.8rem;">
            <input type="checkbox" name="reminders_consent" value="1" <?= !empty($_POST['reminders_consent']) ? 'checked' : '' ?>>
            <span>Yes, Everest may send me birthday wishes and a yearly policy check-in by WhatsApp, SMS or email. (Optional. You can ask us to stop at any time.)</span>
          </label>
          <label class="check" style="margin-bottom:1.2rem;">
            <input type="checkbox" name="accept_terms" required>
            <span>I confirm that I have read and agree to the <a href="<?= url('terms.php') ?>" target="_blank">Terms and Conditions</a> of Everest Funerals, and understand the services and policies outlined.</span>
          </label>
          <button class="btn btn-navy btn-block" type="submit">Submit application</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_site_footer.php'; ?>
