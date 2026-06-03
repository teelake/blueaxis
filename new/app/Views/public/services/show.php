<section class="bg-brand-navy text-white py-20">
  <div class="max-w-7xl mx-auto px-4">
    <a href="<?= url('services') ?>" class="text-sm text-brand-gold-light hover:text-white mb-4 inline-block">← All services</a>
    <h1 class="text-4xl font-semibold"><?= e($service['title']) ?></h1>
    <p class="mt-4 text-slate-300 max-w-2xl"><?= e($service['excerpt']) ?></p>
  </div>
</section>

<section class="py-20">
  <div class="max-w-3xl mx-auto px-4">
    <div class="prose prose-lg prose-slate mb-12"><?= $service['description'] ?></div>
    <?php if (!empty($service['benefits_list'])): ?>
      <h2 class="text-xl font-semibold text-brand-navy mb-6">Key benefits</h2>
      <ul class="space-y-3">
        <?php foreach ($service['benefits_list'] as $b): ?>
          <li class="flex gap-3 text-slate-700"><span class="text-brand-gold font-bold">✓</span><?= e($b) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="mt-12 flex flex-wrap gap-4">
      <a href="<?= url('contact#quote') ?>" class="btn-primary">Request a Quote</a>
      <a href="<?= url('contact') ?>" class="btn-secondary">Contact Us</a>
    </div>
  </div>
</section>
