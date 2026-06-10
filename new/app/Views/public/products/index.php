<section class="bg-brand-navy text-white py-14 sm:py-20 lg:py-28 relative overflow-hidden">
  <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-brand-gold via-transparent to-transparent" aria-hidden="true"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="section-eyebrow text-brand-gold-light mb-4">Wholesale catalog</p>
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight max-w-3xl">Premium B2B product lines for logistics partners</h1>
    <p class="mt-4 sm:mt-6 text-base sm:text-lg text-slate-300 max-w-2xl leading-relaxed">Structured import, warehousing, and distribution-ready SKUs for grocery, food service, and wholesale buyers across Manitoba and Canada.</p>
  </div>
</section>

<section class="py-4 sm:py-6 lg:py-8 border-b border-slate-100 bg-white sticky top-[5.5rem] sm:top-24 z-40 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <form method="get" action="<?= url('products') ?>" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div class="catalog-filters" role="navigation" aria-label="Product categories">
        <div class="catalog-filters__track">
          <a href="<?= url('products') ?>" class="catalog-filter <?= $activeCategory === '' ? 'is-active' : '' ?>">All</a>
          <?php foreach ($categories as $cat): ?>
            <a href="<?= url('products?category=' . urlencode($cat)) ?>" class="catalog-filter <?= $activeCategory === $cat ? 'is-active' : '' ?>"><?= e($cat) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="flex flex-col sm:flex-row gap-2 w-full lg:max-w-md lg:w-auto lg:shrink-0">
        <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search products…" class="input-field w-full min-w-0 flex-1" autocomplete="off" />
        <?php if ($activeCategory !== ''): ?>
          <input type="hidden" name="category" value="<?= e($activeCategory) ?>" />
        <?php endif; ?>
        <button type="submit" class="btn-primary w-full sm:w-auto shrink-0">Search</button>
      </div>
    </form>
  </div>
</section>

<?php if ($featured !== [] && $activeCategory === '' && $search === ''): ?>
<section class="py-12 sm:py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="section-eyebrow mb-2">Featured lines</p>
    <h2 class="text-xl sm:text-2xl font-semibold text-brand-navy mb-6 sm:mb-10">Priority wholesale programs</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
      <?php foreach ($featured as $p): ?>
        <?php \App\Core\View::partial('products/card-featured', ['product' => $p]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="py-12 sm:py-16 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php if ($products === []): ?>
      <div class="text-center py-12 sm:py-16">
        <p class="text-base sm:text-lg text-slate-600">No products match your filters.</p>
        <a href="<?= url('products') ?>" class="inline-flex mt-6 text-sm font-semibold text-brand-navy hover:text-brand-gold">View full catalog →</a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        <?php foreach ($products as $p): ?>
          <?php \App\Core\View::partial('products/card', ['product' => $p]); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="py-14 sm:py-20 bg-brand-navy text-white">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
    <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold mb-4">Need volume pricing or import coordination?</h2>
    <p class="text-sm sm:text-base text-slate-300 mb-6 sm:mb-8">Our team supports B2B programs from sourcing through Manitoba fulfillment and national distribution.</p>
    <a href="<?= url('quote') ?>" class="btn-accent w-full sm:w-auto">Request a wholesale quote</a>
  </div>
</section>
