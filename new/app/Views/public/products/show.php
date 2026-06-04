<section class="bg-slate-50 border-b border-slate-100 py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="<?= url('products') ?>" class="text-sm font-medium text-brand-navy hover:text-brand-gold">← Product catalog</a>
  </div>
</section>

<section class="py-16 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">
      <div class="rounded-2xl overflow-hidden shadow-elevated bg-white aspect-square max-h-[520px]">
        <?php if (!empty($product['image_path'])): ?>
          <img src="<?= e(media_url($product['image_path'])) ?>" alt="<?= e($product['title']) ?>" class="w-full h-full object-cover" />
        <?php else: ?>
          <div class="w-full h-full bg-brand-navy/5 flex items-center justify-center text-slate-400">No image</div>
        <?php endif; ?>
      </div>
      <div>
        <?php if (!empty($product['category'])): ?>
          <p class="section-eyebrow mb-2"><?= e($product['category']) ?></p>
        <?php endif; ?>
        <h1 class="text-3xl md:text-4xl font-semibold text-brand-navy tracking-tight"><?= e($product['title']) ?></h1>
        <?php if (!empty($product['sku'])): ?>
          <p class="mt-2 text-sm font-mono text-slate-500">SKU: <?= e($product['sku']) ?></p>
        <?php endif; ?>
        <?php if (!empty($product['excerpt'])): ?>
          <p class="mt-6 text-lg text-slate-600 leading-relaxed"><?= e($product['excerpt']) ?></p>
        <?php endif; ?>
        <dl class="mt-10 grid sm:grid-cols-2 gap-4">
          <?php if (!empty($product['origin_region'])): ?>
            <div class="rounded-xl border border-slate-100 p-4 bg-white">
              <dt class="text-xs font-semibold uppercase text-slate-400">Origin</dt>
              <dd class="mt-1 font-medium text-brand-navy"><?= e($product['origin_region']) ?></dd>
            </div>
          <?php endif; ?>
          <?php if (!empty($product['pack_format'])): ?>
            <div class="rounded-xl border border-slate-100 p-4 bg-white">
              <dt class="text-xs font-semibold uppercase text-slate-400">Pack format</dt>
              <dd class="mt-1 font-medium text-brand-navy"><?= e($product['pack_format']) ?></dd>
            </div>
          <?php endif; ?>
          <?php if (!empty($product['storage_notes'])): ?>
            <div class="rounded-xl border border-slate-100 p-4 bg-white sm:col-span-2">
              <dt class="text-xs font-semibold uppercase text-slate-400">Storage & handling</dt>
              <dd class="mt-1 text-slate-700"><?= e($product['storage_notes']) ?></dd>
            </div>
          <?php endif; ?>
        </dl>
        <div class="mt-10 flex flex-wrap gap-4">
          <a href="<?= url('quote') ?>" class="btn-primary">Request wholesale quote</a>
          <a href="<?= url('contact') ?>" class="btn-secondary">Speak with our team</a>
        </div>
      </div>
    </div>
    <?php if (!empty($product['description'])): ?>
      <div class="mt-20 max-w-3xl prose prose-lg prose-slate">
        <h2 class="section-title !text-2xl mb-6">Product details</h2>
        <?= $product['description'] ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!empty($related)): ?>
<section class="py-20 bg-slate-50 border-t border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="section-title mb-10">Related catalog items</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($related as $p): ?>
        <?php \App\Core\View::partial('products/card', ['product' => $p]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
