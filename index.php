<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/site-data.php';

$page_title = 'Affordable Funeral Services in Rustenburg | Everest Funerals';
$meta_description = 'Trusted funeral services in Rustenburg. Affordable funeral plans, cremation and burial arranged with care and dignity. Speak to Everest Funerals today.';
$active_nav = 'home';
$years = (int) date('Y') - COMPANY_FOUNDED;
$testimonials = Testimonial::published();
$featured = $testimonials[0] ?? null;
require __DIR__ . '/_site_header.php';
?>

<section class="hero-warm hero-photo" aria-labelledby="hero-title">
  <div class="hero-bg" aria-hidden="true"><img src="<?= asset_img('hero-dawn.jpg') ?>" alt="" width="2000" height="1167" fetchpriority="high"></div>
  <div class="container hero-grid">
    <div class="hero-text">
      <span class="eyebrow">Welcome to Everest Funerals</span>
      <h1 id="hero-title">Always by your side.</h1>
      <p class="lead">When you lose someone you love, you shouldn't have to carry everything alone. Since 2007 we've helped South African families say goodbye with dignity, with cover from R70 a month and claims paid within 48 hours.</p>
      <div class="paths" aria-label="Ways to reach us">
        <a class="path path-wa" href="<?= wa_href('home-hero') ?>" target="_blank" rel="noopener">
          <span class="path-ico"><?= wa_icon(24) ?></span>
          <span><b>WhatsApp us</b><small>A real person replies</small></span>
        </a>
        <a class="path" href="<?= url('apply.php') ?>">
          <span class="path-ico"><?= ev_icon('shield', 24) ?></span>
          <span><b>Apply online</b><small>Individual or family</small></span>
        </a>
        <a class="path" href="<?= url('quote.php') ?>">
          <span class="path-ico"><?= ev_icon('calendar', 24) ?></span>
          <span><b>Get a quote</b><small>We'll call you back</small></span>
        </a>
      </div>
      <p class="hero-call">Has a loved one just passed away? <a href="tel:<?= h(COMPANY_PHONE_TEL) ?>">Call us on <?= h(COMPANY_PHONE_DISPLAY) ?></a></p>
    </div>
    <div class="hero-side">
      <div class="float-card">
        <span class="float-ico"><?= ev_icon('clock', 22) ?></span>
        <span><b>Paid within 48 hours</b><small>once we have your documents</small></span>
      </div>
      <div class="float-card">
        <span class="float-ico"><?= ev_icon('family', 22) ?></span>
        <span><b>Up to 6 children covered</b><small>on our family plans</small></span>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="facts-warm">
      <div><strong>48h</strong><span>claim payouts</span></div>
      <div><strong>6</strong><span>funeral plans</span></div>
      <div><strong>R70</strong><span>cover from, per month</span></div>
      <div><strong><?= $years ?> years</strong><span>caring for families</span></div>
    </div>
  </div>
</section>

<section>
  <div class="container split">
    <div class="collage">
      <div class="ph ph-a"><img src="<?= asset_img('white-roses.jpg') ?>" alt="White roses resting among green leaves" loading="lazy"></div>
      <div class="ph ph-b"><img src="<?= asset_img('candles.jpg') ?>" alt="Memorial candles glowing in a quiet room" loading="lazy"></div>
      <div class="since"><strong><?= COMPANY_FOUNDED ?></strong>Founded as a South African funeral and funeral insurance company</div>
    </div>
    <div>
      <span class="eyebrow">Who we are</span>
      <h2>So much more than just a pay-out.</h2>
      <p class="lead">Everything we do shapes your experience of saying goodbye to a loved one. We consider it an honour and a privilege, and treat every step with the utmost care and consideration.</p>
      <p class="muted">We look after our customers and our people, and we behave with compassion and respect.</p>
      <div class="promises">
        <?php foreach (TRUST_BADGES as $b): ?>
          <div class="promise">
            <span class="ico"><?= ev_icon($b['icon'], 22) ?></span>
            <div><h4><?= h($b['label']) ?></h4><p><?= h($b['text']) ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="first-steps" aria-labelledby="fs-title">
  <div class="container">
    <div class="sec-head center">
      <span class="eyebrow">If someone has just passed away</span>
      <h2 id="fs-title">You don't have to know what to do. We'll guide you.</h2>
      <p class="lead">Take a breath. These are the first steps, and our team will walk through each one with you.</p>
    </div>
    <ol class="fs-list">
      <li>
        <span class="fs-num">1</span>
        <h3>Call or WhatsApp us</h3>
        <p>Tell us what has happened and where your loved one is. We'll explain everything that comes next, at your pace.</p>
      </li>
      <li>
        <span class="fs-num">2</span>
        <h3>We bring your loved one into our care</h3>
        <p>Once a doctor or the police have confirmed the death, we arrange the removal and care, within 200 km on our plans.</p>
      </li>
      <li>
        <span class="fs-num">3</span>
        <h3>We help with the paperwork</h3>
        <p>We guide you through registering the death and your claim, which is paid within 48 hours of receiving your documents.</p>
      </li>
    </ol>
    <div class="btn-row center">
      <a class="btn btn-wa" href="<?= wa_href('first-steps') ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>WhatsApp us now</a>
      <a class="btn btn-outline" href="tel:<?= h(COMPANY_PHONE_TEL) ?>">Call <?= h(COMPANY_PHONE_DISPLAY) ?></a>
      <a class="btn btn-outline" href="<?= url('guides.php') ?>">Read our guides</a>
    </div>
  </div>
</section>

<section class="plans-warm" id="plans">
  <div class="container">
    <div class="sec-head-row">
      <div class="sec-head">
        <span class="eyebrow">Funeral plans</span>
        <h2>Find the cover that fits your family.</h2>
      </div>
      <p class="lead" style="max-width:430px;margin:0;">Every plan includes a full funeral service, not only cash. Choose a plan to see premiums, cover amounts and exactly what's included.</p>
    </div>

    <div class="plan-finder">
      <div>
        <div class="plan-tabs" role="tablist" aria-label="Funeral plans" data-plan-tabs>
          <?php $i = 0; foreach (SERVICE_PLANS as $slug => $plan): ?>
            <button class="plan-tab" type="button" role="tab" id="tab-<?= h($slug) ?>" aria-controls="panel-<?= h($slug) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
              <span class="t"><b><?= h($plan['name']) ?></b><small><?= h($plan['badge']) ?></small></span>
              <span class="p"><small>from</small><b><?= h($plan['from']) ?></b></span>
            </button>
          <?php $i++; endforeach; ?>
        </div>
        <p class="plan-note">Premiums are per month. Family plans cover up to 6 children under 21. <a href="<?= h(SERVICE_PLANS['care-plan']['pdf']) ?>" target="_blank" rel="noopener">Download the plan brochure (PDF)</a></p>
      </div>

      <div class="plan-panels">
        <?php foreach (SERVICE_PLANS as $slug => $plan): ?>
          <article class="plan-panel" role="tabpanel" id="panel-<?= h($slug) ?>" aria-labelledby="tab-<?= h($slug) ?>" tabindex="0">
            <div class="plan-panel-head">
              <img src="<?= asset_img($plan['image']) ?>" alt="" loading="lazy">
              <div>
                <div>
                  <span class="badge"><?= h($plan['badge']) ?></span>
                  <h3><?= h($plan['name']) ?></h3>
                  <p><?= h($plan['blurb']) ?></p>
                </div>
                <div class="cover"><small>Main member cover</small><b><?= h($plan['cover']) ?></b></div>
              </div>
            </div>
            <div class="plan-panel-body">
              <div>
                <h4>Monthly premiums</h4>
                <?= plan_premiums_table($plan) ?>
                <?php if (!empty($plan['covers'])): ?>
                  <h4 style="margin-top:1.8rem;">Cover per person</h4>
                  <?= plan_cover_amounts($plan) ?>
                <?php endif; ?>
              </div>
              <div>
                <h4>Funeral services included</h4>
                <?= plan_services_list($plan) ?>
                <div class="btn-row">
                  <a class="btn btn-navy" href="<?= url('quote.php?plan=' . $slug) ?>">Get a quote</a>
                  <a class="btn btn-wa" href="<?= wa_href('plan-' . $slug) ?>" target="_blank" rel="noopener"><?= wa_icon(18) ?>Ask on WhatsApp</a>
                  <a class="text-link" href="<?= url('plan.php?slug=' . $slug) ?>">Plan details</a>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="sec-head center">
      <span class="eyebrow">Who we cover</span>
      <h2>Cover for every kind of family.</h2>
    </div>
    <div class="cover-grid">
      <?php foreach (WHO_WE_COVER as $w): ?>
        <div class="cover-item"><div class="ico"><?= ev_icon($w['icon'], 26) ?></div><span><?= h($w['label']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section style="padding-top:0;">
  <div class="container">
    <div class="claims-block">
      <div class="claims-media">
        <img src="<?= asset_img('lake-dusk.jpg') ?>" alt="A still lake at dusk reflecting the hills" loading="lazy">
        <div class="stamp"><strong>48 hours</strong>Approved claims are paid directly to the beneficiary within 48 hours.</div>
      </div>
      <div class="claims-copy">
        <span class="eyebrow">Claims</span>
        <h2>When the time comes, we keep it simple.</h2>
        <p class="muted">Visit us or send your documents. Our team guides you through every step.</p>
        <div class="steps">
          <?php foreach (CLAIM_STEPS as $s): ?>
            <div class="step"><div class="num"></div><div><h3><?= h($s['title']) ?></h3><p><?= h($s['text']) ?></p></div></div>
          <?php endforeach; ?>
        </div>
        <div class="btn-row">
          <a class="btn btn-navy" href="<?= url('claim-process.php') ?>">How to claim</a>
          <a class="btn btn-outline" href="<?= h(CLAIM_PDF) ?>" target="_blank" rel="noopener"><?= ev_icon('download', 18) ?>Claim guide (PDF)</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($featured): ?>
<section class="testi-warm" aria-labelledby="testi-title">
  <div class="container testi-warm-grid">
    <div class="testi-visual">
      <div class="arch"><img src="<?= asset_img('candles.jpg') ?>" alt="Candles lit in remembrance" loading="lazy"></div>
    </div>
    <div>
      <span class="eyebrow" id="testi-title">Words from the families we've served</span>
      <figure class="testi-feature">
        <blockquote>&ldquo;<?= h($featured['quote']) ?>&rdquo;</blockquote>
        <figcaption class="testi-who">
          <span class="av" aria-hidden="true"><?= h(implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $featured['name']), 0, 2)))) ?></span>
          <span><b><?= h($featured['name']) ?></b><small>Everest family</small></span>
        </figcaption>
      </figure>
      <div class="testi-row">
        <?php foreach (array_slice($testimonials, 1, 3) as $t): ?>
          <figure class="testi">
            <blockquote>&ldquo;<?= h($t['quote']) ?>&rdquo;</blockquote>
            <figcaption><?= h($t['name']) ?><?= $t['location'] ? ', ' . h($t['location']) : '' ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
      <?php if (($reviewsPage = Setting::get('google_reviews_page_url')) !== ''): ?>
        <p style="margin-top:1.4rem;"><a class="text-link" href="<?= h($reviewsPage) ?>" target="_blank" rel="noopener">Read our reviews on Google</a></p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section>
  <div class="container">
    <div class="image-band">
      <img src="<?= asset_img('savanna-sunset.jpg') ?>" alt="Acacia trees silhouetted against a golden sunset" loading="lazy">
      <div>
        <span class="eyebrow" style="color:var(--gold);">Planning ahead</span>
        <h2>Planning ahead is a loving gesture for your family.</h2>
        <p>Settle the details now, so your loved ones don't have to make hard decisions at an already difficult time.</p>
        <div class="btn-row">
          <a class="btn btn-gold" href="<?= url('pre-need.php') ?>">Start planning ahead</a>
          <a class="btn btn-outline-light" href="<?= url('contact.php') ?>">Talk to our team</a>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container" style="padding-bottom:clamp(3rem,6vw,5rem);">
  <div class="notice-banner" style="margin:0;">
    <strong>Please note:</strong> We do not currently have any offices in Pretoria, nor any relationship with a funeral service provider in Pretoria calling themselves Everest Funerals.
  </div>
</div>

<?php require __DIR__ . '/_site_footer.php'; ?>
