<section class="bg-brand-navy text-white py-20">
  <div class="max-w-7xl mx-auto px-4">
    <p class="section-eyebrow text-brand-gold-light mb-3">Services</p>
    <h1 class="text-4xl font-semibold">Logistics solutions for wholesale partners</h1>
    <p class="mt-4 text-slate-300 max-w-2xl">Importation, warehousing, and distribution—structured for B2B food supply chains.</p>
  </div>
</section>

<section class="py-20">
  <div class="max-w-7xl mx-auto px-4 space-y-16">
    <?php foreach ($services as $i => $svc): ?>
      <article class="grid lg:grid-cols-2 gap-12 items-center <?= $i % 2 ? 'lg:flex-row-reverse' : '' ?>">
        <div class="<?= $i % 2 ? 'lg:order-2' : '' ?>">
          <div class="aspect-video rounded-xl bg-gradient-to-br from-brand-navy/10 to-brand-gold/20 flex items-center justify-center">
            <?php if ($svc['banner_image']): ?>
              <img src="<?= e(media_url($svc['banner_image'])) ?>" alt="" class="w-full h-full object-cover rounded-xl" />
            <?php else: ?>
              <span class="text-6xl font-bold text-brand-navy/20"><?= $i + 1 ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div>
          <h2 class="text-2xl font-semibold text-brand-navy mb-4"><?= e($svc['title']) ?></h2>
          <p class="text-slate-600 mb-6"><?= e($svc['excerpt']) ?></p>
          <a href="<?= url('services/' . $svc['slug']) ?>" class="btn-primary">Learn more</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
