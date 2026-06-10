<?php /** @var array $product */ ?>
<article class="product-card product-card--featured group">
  <a href="<?= url('products/' . $product['slug']) ?>" class="grid md:grid-cols-2 h-full">
    <div class="product-card__media md:min-h-[280px]">
      <?php if (!empty($product['image_path'])): ?>
        <img src="<?= e(media_url($product['image_path'])) ?>" alt="<?= e($product['title']) ?>" loading="lazy" />
      <?php else: ?>
        <div class="product-card__placeholder"></div>
      <?php endif; ?>
    </div>
    <div class="product-card__body product-card__body--featured flex flex-col justify-center">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-gold mb-2">Featured program</p>
      <?php if (!empty($product['sku'])): ?>
        <p class="product-card__sku"><?= e($product['sku']) ?></p>
      <?php endif; ?>
      <h3 class="text-xl sm:text-2xl font-semibold text-brand-navy mb-3"><?= e($product['title']) ?></h3>
      <?php \App\Core\View::partial('products/price', ['product' => $product, 'priceClass' => 'product-card__price text-lg mb-3']); ?>
      <p class="text-slate-600 mb-6"><?= e($product['excerpt'] ?? '') ?></p>
      <span class="text-sm font-semibold text-brand-navy group-hover:text-brand-gold transition">Explore specifications →</span>
    </div>
  </a>
</article>
