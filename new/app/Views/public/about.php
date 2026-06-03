<section class="bg-brand-navy text-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="section-eyebrow text-brand-gold-light mb-3">About Us</p>
    <h1 class="text-4xl md:text-5xl font-semibold max-w-3xl">BlueAxis Logistics & Warehousing Ltd.</h1>
  </div>
</section>

<section class="py-20">
  <div class="max-w-3xl mx-auto px-4 prose prose-lg prose-slate">
    <h2><?= e(section($blocks['overview'] ?? [], 'title', 'Company Overview')) ?></h2>
    <?= section($blocks['overview'] ?? [], 'body') ?>
  </div>
</section>

<section class="py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12">
    <div class="card">
      <h2 class="text-xl font-semibold text-brand-navy mb-4"><?= e(section($blocks['mission'] ?? [], 'title', 'Mission')) ?></h2>
      <div class="prose prose-slate text-sm"><?= section($blocks['mission'] ?? [], 'body') ?></div>
    </div>
    <div class="card">
      <h2 class="text-xl font-semibold text-brand-navy mb-4"><?= e(section($blocks['vision'] ?? [], 'title', 'Vision')) ?></h2>
      <div class="prose prose-slate text-sm"><?= section($blocks['vision'] ?? [], 'body') ?></div>
    </div>
  </div>
</section>

<section class="py-20">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="section-title text-center mb-12">Our Values</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($values as $v): ?>
        <div class="card text-center">
          <h3 class="font-semibold text-brand-navy mb-2"><?= e($v['title'] ?? '') ?></h3>
          <p class="text-sm text-slate-600"><?= e($v['description'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-20 bg-slate-50">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <h2 class="section-title mb-4">Leadership</h2>
    <p class="text-slate-600">Leadership profiles will be announced as our executive team expands. For partnership inquiries, please <a href="<?= url('contact') ?>" class="text-brand-navy font-semibold underline">contact us</a>.</p>
    <div class="mt-10 grid sm:grid-cols-3 gap-6">
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <div class="card">
          <div class="w-20 h-20 mx-auto rounded-full bg-brand-navy/10 mb-4"></div>
          <p class="font-medium text-brand-navy">Leadership Team</p>
          <p class="text-xs text-slate-500 mt-1">Position — Coming soon</p>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
