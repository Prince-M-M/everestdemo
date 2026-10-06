<footer class="site-footer">
  <div class="container">
    <div class="foot-grid">
      <div class="foot-brand">
        <img src="<?= asset_img('logo-light.png') ?>" alt="Everest Funerals" width="270" height="52">
        <p>Funeral cover and funeral services, arranged with care and dignity since <?= (int) COMPANY_FOUNDED ?>.</p>
        <div class="foot-social">
          <a href="<?= h(COMPANY_FACEBOOK) ?>" target="_blank" rel="noopener">Facebook</a>
          <a href="<?= h(COMPANY_LINKEDIN) ?>" target="_blank" rel="noopener">LinkedIn</a>
        </div>
      </div>
      <div>
        <h4>Explore</h4>
        <ul>
          <li><a href="<?= url('about.php') ?>">About us</a></li>
          <li><a href="<?= url('services.php') ?>">Funeral plans</a></li>
          <li><a href="<?= url('claim-process.php') ?>">Claim process</a></li>
          <li><a href="<?= url('pre-need.php') ?>">Pre-need planning</a></li>
          <li><a href="<?= url('tributes.php') ?>">Notices and tributes</a></li>
          <li><a href="<?= url('team.php') ?>">Our team</a></li>
          <li><a href="<?= url('guides.php') ?>">Guides and advice</a></li>
          <li><a href="<?= url('quote.php') ?>">Get a quote</a></li>
          <li><a href="<?= url('apply.php') ?>">Apply online</a></li>
        </ul>
      </div>
      <div>
        <h4>Our plans</h4>
        <ul>
          <?php foreach (SERVICE_PLANS as $navSlug => $navPlan): ?>
            <li><a href="<?= url('plan.php?slug=' . $navSlug) ?>"><?= h($navPlan['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4>Head office</h4>
        <ul>
          <li><a href="tel:<?= h(COMPANY_PHONE_TEL) ?>"><?= h(COMPANY_PHONE_DISPLAY) ?></a></li>
          <li><a href="mailto:<?= h(COMPANY_EMAIL) ?>"><?= h(COMPANY_EMAIL) ?></a></li>
          <li><a href="<?= h(COMPANY_MAPS) ?>" target="_blank" rel="noopener"><?= h(COMPANY_ADDRESS) ?></a></li>
          <li><a href="<?= url('branches.php') ?>">Find a branch</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>&copy; <?= date('Y') ?> Everest Funerals. All rights reserved.</span>
      <span><a href="<?= url('terms.php') ?>">Terms and conditions</a></span>
    </div>
  </div>
</footer>

<a class="wa-float" href="<?= wa_href('float') ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
  <?= wa_icon(24) ?>
  <span>Chat with us</span>
</a>
</body>
</html>
