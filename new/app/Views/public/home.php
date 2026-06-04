<?php if (!empty($heroSlides)): ?>
  <?php \App\Core\View::partial('hero-slider', ['slides' => $heroSlides]); ?>
  <section class="hero-section relative overflow-hidden bg-brand-navy-dark text-white border-b border-white/10">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
      <div class="max-w-3xl" data-aos="fade-up">
        <p class="section-eyebrow text-brand-gold-light mb-3"><?= e(section($hero, 'eyebrow')) ?></p>
        <h1 class="font-sans font-semibold text-2xl sm:text-3xl lg:text-4xl leading-tight tracking-tight mb-4"><?= e(section($hero, 'title')) ?></h1>
        <p class="text-base text-slate-300 leading-relaxed mb-6 max-w-2xl"><?= e(section($hero, 'lead')) ?></p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= url(ltrim(section($hero, 'cta_primary_url', '/quote'), '/')) ?>" class="btn-accent"><?= e(section($hero, 'cta_primary_label', 'Request a Quote')) ?></a>
          <a href="<?= url(ltrim(section($hero, 'cta_secondary_url', '/services'), '/')) ?>" class="btn-secondary-hero"><?= e(section($hero, 'cta_secondary_label', 'Our Services')) ?></a>
        </div>
      </div>
    </div>
  </section>
<?php else: ?>
<section class="hero-section relative overflow-hidden bg-brand-navy-dark text-white">
  <?php \App\Core\View::partial('hero-background'); ?>
  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-32">
    <div class="max-w-3xl" data-aos="fade-up">
      <p class="section-eyebrow text-brand-gold-light mb-4"><?= e(section($hero, 'eyebrow')) ?></p>
      <h1 class="font-sans font-semibold text-4xl sm:text-5xl lg:text-6xl leading-tight tracking-tight mb-6"><?= e(section($hero, 'title')) ?></h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-10 max-w-2xl"><?= e(section($hero, 'lead')) ?></p>
      <div class="flex flex-wrap gap-4">
        <a href="<?= url(ltrim(section($hero, 'cta_primary_url', '/quote'), '/')) ?>" class="btn-accent"><?= e(section($hero, 'cta_primary_label', 'Request a Quote')) ?></a>
        <a href="<?= url(ltrim(section($hero, 'cta_secondary_url', '/services'), '/')) ?>" class="btn-secondary-hero"><?= e(section($hero, 'cta_secondary_label', 'Our Services')) ?></a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Trust Indicators -->
<section class="border-b border-slate-100 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center" data-aos="fade-up">
      <?php foreach ($trustItems as $t): ?>
        <div>
          <p class="text-2xl font-bold text-brand-navy"><?= e($t['stat']) ?></p>
          <p class="text-sm text-slate-600 mt-1"><?= e($t['label']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- About -->
<?php $aboutImage = section($about, 'image'); ?>
<section class="py-20 lg:py-28">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-16 items-center">
    <div data-aos="fade-right" class="<?= $aboutImage !== '' ? '' : 'lg:col-span-1' ?>">
      <p class="section-eyebrow mb-3">About BlueAxis</p>
      <h2 class="section-title mb-6"><?= e(section($about, 'title')) ?></h2>
      <div class="prose prose-slate max-w-none text-slate-600"><?= section($about, 'body') ?></div>
      <a href="<?= url('about') ?>" class="inline-flex mt-8 text-sm font-semibold text-brand-navy hover:text-brand-gold transition">Learn more about us →</a>
    </div>
    <?php if ($aboutImage !== ''): ?>
      <?php \App\Core\View::partial('section-image', ['imagePath' => $aboutImage, 'alt' => section($about, 'title', 'About BlueAxis'), 'align' => 'right']); ?>
    <?php else: ?>
    <div class="card bg-brand-gold-muted/30 border-brand-gold/20" data-aos="fade-left">
      <ul class="space-y-4 text-sm text-brand-navy-dark">
        <li class="flex gap-3"><span class="text-brand-gold font-bold">01</span> Strategic African food importation</li>
        <li class="flex gap-3"><span class="text-brand-gold font-bold">02</span> Manitoba warehousing & inventory control</li>
        <li class="flex gap-3"><span class="text-brand-gold font-bold">03</span> Canada-wide B2B distribution</li>
        <li class="flex gap-3"><span class="text-brand-gold font-bold">04</span> Long-term wholesale partnerships</li>
      </ul>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Services -->
<section class="py-20 lg:py-28 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
      <p class="section-eyebrow mb-3">Core Services</p>
      <h2 class="section-title">End-to-end logistics for wholesale partners</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($services as $i => $svc): ?>
        <article class="card group hover:shadow-elevated transition" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
          <div class="w-12 h-12 rounded-lg bg-brand-navy/10 flex items-center justify-center text-brand-navy font-bold text-lg mb-5"><?= $i + 1 ?></div>
          <h3 class="text-xl font-semibold text-brand-navy mb-3"><?= e($svc['title']) ?></h3>
          <p class="text-sm text-slate-600 mb-6"><?= e($svc['excerpt']) ?></p>
          <a href="<?= url('services/' . $svc['slug']) ?>" class="text-sm font-semibold text-brand-navy group-hover:text-brand-gold transition">View service →</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="py-20 lg:py-28">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
      <p class="section-eyebrow mb-3">How It Works</p>
      <h2 class="section-title">A clear path from sourcing to shelf</h2>
    </div>
    <div class="grid md:grid-cols-4 gap-6">
      <?php
      $steps = [
          ['title' => 'Consult', 'desc' => 'Define product needs, volumes, and timelines with our team.'],
          ['title' => 'Source & Import', 'desc' => 'Coordinated supplier sourcing and inbound logistics.'],
          ['title' => 'Store', 'desc' => 'Secure warehousing with organized inventory handling.'],
          ['title' => 'Distribute', 'desc' => 'Fulfillment across Manitoba and Canada-wide networks.'],
      ];
      foreach ($steps as $i => $step): ?>
        <div class="relative p-6 border border-slate-100 rounded-xl" data-aos="fade-up" data-aos-delay="<?= $i * 60 ?>">
          <span class="text-4xl font-bold text-brand-gold/40"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <h3 class="font-semibold text-brand-navy mt-4 mb-2"><?= e($step['title']) ?></h3>
          <p class="text-sm text-slate-600"><?= e($step['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Industries -->
<?php $indImage = section($industries ?? [], 'image'); ?>
<section class="py-20 bg-brand-navy text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <p class="section-eyebrow text-brand-gold-light mb-3"><?= e(section($industries ?? [], 'eyebrow', 'Industries Served')) ?></p>
        <h2 class="text-3xl md:text-4xl font-semibold mb-6"><?= e(section($industries ?? [], 'title', 'Built for B2B food supply chains')) ?></h2>
        <p class="text-slate-300"><?= e(section($industries ?? [], 'lead', 'We partner with organizations that need dependable logistics—not consumer retail experiences.')) ?></p>
      </div>
      <?php if ($indImage !== ''): ?>
        <?php \App\Core\View::partial('section-image', ['imagePath' => $indImage, 'alt' => section($industries ?? [], 'title'), 'align' => 'right']); ?>
      <?php else: ?>
      <ul class="grid sm:grid-cols-2 gap-4" data-aos="fade-left">
        <?php foreach (['Grocery stores', 'African food retailers', 'Food distributors', 'Restaurants', 'Wholesale buyers', 'Import/export partners'] as $ind): ?>
          <li class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-sm"><?= e($ind) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 lg:py-28">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
      <p class="section-eyebrow mb-3">Why Choose Us</p>
      <h2 class="section-title">Enterprise discipline. Partner-first delivery.</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ([
          ['title' => 'Operational reliability', 'desc' => 'Structured processes across import, storage, and fulfillment.'],
          ['title' => 'Canadian market expertise', 'desc' => 'Manitoba-based with expanding national distribution capability.'],
          ['title' => 'Transparent partnerships', 'desc' => 'Clear communication and accountability at every stage.'],
      ] as $item): ?>
        <div class="card" data-aos="fade-up">
          <h3 class="font-semibold text-brand-navy mb-2"><?= e($item['title']) ?></h3>
          <p class="text-sm text-slate-600"><?= e($item['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php \App\Core\View::partial('home-testimonials', get_defined_vars()); ?>

<!-- Blog preview -->
<?php if (!empty($posts)): ?>
<section class="py-20 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
      <div>
        <p class="section-eyebrow mb-2">Insights</p>
        <h2 class="section-title">Latest from our blog</h2>
      </div>
      <a href="<?= url('blog') ?>" class="text-sm font-semibold text-brand-navy hover:text-brand-gold">View all articles →</a>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($posts as $post): ?>
        <article class="card p-0 overflow-hidden" data-aos="fade-up">
          <a href="<?= url('blog/' . $post['slug']) ?>" class="block">
            <?php if ($post['featured_image']): ?>
              <img src="<?= e(media_url($post['featured_image'])) ?>" alt="" class="w-full h-44 object-cover" />
            <?php else: ?>
              <div class="h-44 bg-brand-navy/10"></div>
            <?php endif; ?>
            <div class="p-6">
              <p class="text-xs text-brand-gold font-semibold uppercase mb-2"><?= e($post['category_name'] ?? 'Article') ?></p>
              <h3 class="font-semibold text-brand-navy line-clamp-2"><?= e($post['title']) ?></h3>
              <p class="text-sm text-slate-600 mt-2 line-clamp-2"><?= e($post['excerpt']) ?></p>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php \App\Core\View::partial('home-newsletter', get_defined_vars()); ?>

<!-- CTA -->
<?php $ctaImage = section($cta, 'image'); ?>
<section class="py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-10 items-center rounded-2xl bg-brand-navy text-white shadow-elevated overflow-hidden" data-aos="zoom-in">
      <?php if ($ctaImage !== ''): ?>
        <div class="relative min-h-[280px] lg:min-h-full lg:h-full">
          <img src="<?= e(media_url($ctaImage)) ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-90" />
          <div class="absolute inset-0 bg-brand-navy/40 lg:hidden"></div>
        </div>
      <?php endif; ?>
      <div class="px-8 py-16 <?= $ctaImage === '' ? 'lg:col-span-2 text-center' : '' ?>">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4"><?= e(section($cta, 'title')) ?></h2>
        <p class="text-slate-300 mb-8 max-w-xl <?= $ctaImage === '' ? 'mx-auto' : '' ?>"><?= e(section($cta, 'body')) ?></p>
        <a href="<?= url(ltrim(section($cta, 'button_url', '/contact'), '/')) ?>" class="btn-accent"><?= e(section($cta, 'button_label', 'Get in Touch')) ?></a>
      </div>
    </div>
  </div>
</section>
