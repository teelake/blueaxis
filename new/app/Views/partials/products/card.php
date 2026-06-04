<?php /** @var array $product */ ?>
<article class="product-card group flex flex-col" data-aos="fade-up">
  <a href="<?= url('products/' . $product['slug']) ?>" class="block flex-1">
    <div class="product-card__media">
      <?php if (!empty($product['image_path'])): ?>
        <img src="<?= e(media_url($product['image_path'])) ?>" alt="<?= e($product['title']) ?>" loading="lazy" />
      <?php else: ?>
        <div class="product-card__placeholder"></div>
      <?php endif; ?>
      <?php if (!empty($product['category'])): ?>
        <span class="product-card__badge"><?= e($product['category']) ?></span>
      <?php endif; ?>
    </div>
    <div class="product-card__body">
      <?php if (!empty($product['sku'])): ?>
        <p class="product-card__sku"><?= e($product['sku']) ?></p>
      <?php endif; ?>
      <h3 class="product-card__title"><?= e($product['title']) ?></h3>
      <?php \App\Core\View::partial('products/price', ['product' => $product, 'priceClass' => 'product-card__price']); ?>
      <?php if (!empty($product['excerpt'])): ?>
        <p class="product-card__excerpt"><?= e($product['excerpt']) ?></p>
      <?php endif; ?>
      <ul class="product-card__meta">
        <?php if (!empty($product['origin_region'])): ?>
          <li><span>Origin</span> <?= e($product['origin_region']) ?></li>
        <?php endif; ?>
        <?php if (!empty($product['pack_format'])): ?>
          <li><span>Format</span> <?= e($product['pack_format']) ?></li>
        <?php endif; ?>
      </ul>
      <span class="product-card__link">View product →</span>
    </div>
  </a>
  <div class="product-card__actions px-6 pb-6 pt-0 border-t border-slate-100">
    <?php \App\Core\View::partial('product-add-to-quote', [
        'product' => $product,
        'redirect' => 'quote',
        'qty' => false,
        'label' => '+ Add to quote',
        'btnClass' => 'btn-secondary w-full justify-center',
    ]); ?>
  </div>
</article>
