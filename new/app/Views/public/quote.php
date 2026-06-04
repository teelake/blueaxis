<?php
$cartItems = $cartItems ?? [];
$hasCart = $cartItems !== [];
$selectedService = $_SESSION['_old']['service_needed'] ?? ($hasCart ? 'Product catalog / wholesale SKUs' : '');
?>
<section class="bg-brand-navy text-white py-16 lg:py-20">
  <div class="max-w-3xl mx-auto px-4 text-center">
    <p class="section-eyebrow text-brand-gold-light mb-3">B2B partnerships</p>
    <h1 class="text-4xl font-semibold tracking-tight">Request a quote</h1>
    <p class="text-slate-300 mt-4 text-lg leading-relaxed">
      Tell us about your import, warehousing, or distribution needs. Our team will review your request and respond with a tailored proposal.
    </p>
    <?php if ($hasCart): ?>
      <p class="mt-4 text-sm text-brand-gold-light"><?= count($cartItems) ?> product<?= count($cartItems) === 1 ? '' : 's' ?> in your quote list</p>
    <?php endif; ?>
  </div>
</section>

<section class="py-16">
  <div class="max-w-2xl mx-auto px-4">
    <?php if (!empty($success)): ?>
      <div class="mb-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-8 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($hasCart): ?>
      <div class="card mb-8">
        <h2 class="text-lg font-semibold text-brand-navy mb-1">Products for this quote</h2>
        <p class="text-sm text-slate-600 mb-4">These catalog items will be included when you submit. Adjust quantities or remove lines you do not need.</p>
        <ul class="quote-cart-list divide-y divide-slate-100">
          <?php foreach ($cartItems as $item): ?>
            <li class="quote-cart-list__item py-4 first:pt-0 last:pb-0">
              <div class="flex flex-wrap gap-4 items-start justify-between">
                <div class="min-w-0 flex-1">
                  <a href="<?= url('products/' . $item['slug']) ?>" class="font-semibold text-brand-navy hover:text-brand-gold"><?= e($item['title']) ?></a>
                  <?php if (!empty($item['sku'])): ?>
                    <p class="text-xs font-mono text-slate-500 mt-0.5"><?= e($item['sku']) ?></p>
                  <?php endif; ?>
                  <?php if (!empty($item['category'])): ?>
                    <p class="text-xs text-slate-500 mt-1"><?= e($item['category']) ?></p>
                  <?php endif; ?>
                  <?php
                  $cartPrice = format_product_price([
                      'price' => $item['price'] ?? null,
                      'price_unit' => $item['price_unit'] ?? null,
                  ]);
                  if ($cartPrice !== null): ?>
                    <p class="text-sm font-medium text-brand-navy mt-1"><?= e($cartPrice) ?></p>
                  <?php endif; ?>
                </div>
                <form method="post" action="<?= url('quote/cart/remove') ?>" class="shrink-0">
                  <?= \App\Core\Csrf::field() ?>
                  <input type="hidden" name="product_slug" value="<?= e($item['slug']) ?>" />
                  <button type="submit" class="text-xs font-medium text-slate-500 hover:text-red-600" aria-label="Remove <?= e($item['title']) ?>">Remove</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <p class="text-xs text-slate-500 mt-4">
          <a href="<?= url('products') ?>" class="font-medium text-brand-navy hover:text-brand-gold">Browse more products →</a>
        </p>
      </div>
    <?php endif; ?>

    <div class="card">
      <form method="post" action="<?= url('quote') ?>" class="grid gap-5">
        <?= \App\Core\Csrf::field() ?>
        <?php foreach ($cartItems as $item): ?>
          <input type="hidden" name="cart_qty[<?= e($item['slug']) ?>]" value="<?= (int) $item['quantity'] ?>" class="quote-cart-qty-hidden" data-slug="<?= e($item['slug']) ?>" />
        <?php endforeach; ?>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Full name *</label>
            <input name="name" required class="input-field" value="<?= old('name') ?>" autocomplete="name" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Company *</label>
            <input name="company" required class="input-field" value="<?= old('company') ?>" autocomplete="organization" />
          </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Business email *</label>
            <input type="email" name="email" required class="input-field" value="<?= old('email') ?>" autocomplete="email" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
            <input name="phone" type="tel" class="input-field" value="<?= old('phone') ?>" autocomplete="tel" />
          </div>
        </div>

        <?php if ($hasCart): ?>
          <div class="rounded-xl border border-brand-gold/30 bg-brand-gold-muted/20 p-4 space-y-3">
            <p class="text-sm font-medium text-brand-navy">Quantities (optional to adjust before submit)</p>
            <?php foreach ($cartItems as $item): ?>
              <div class="flex flex-wrap items-center gap-3 text-sm">
                <span class="font-medium text-slate-700 flex-1 min-w-[140px]"><?= e($item['title']) ?></span>
                <label class="flex items-center gap-2">
                  <span class="text-slate-500">Qty</span>
                  <input
                    type="number"
                    min="1"
                    max="9999"
                    value="<?= (int) $item['quantity'] ?>"
                    class="input-field w-24 quote-cart-qty-input"
                    data-slug="<?= e($item['slug']) ?>"
                    aria-label="Quantity for <?= e($item['title']) ?>"
                  />
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Service required *</label>
          <select name="service_needed" required class="input-field">
            <option value="">Select a service</option>
            <?php if ($hasCart): ?>
              <option value="Product catalog / wholesale SKUs" <?= $selectedService === 'Product catalog / wholesale SKUs' ? 'selected' : '' ?>>Product catalog / wholesale SKUs</option>
            <?php endif; ?>
            <?php foreach ($services as $svc): ?>
              <option value="<?= e($svc['title']) ?>" <?= $selectedService === $svc['title'] ? 'selected' : '' ?>><?= e($svc['title']) ?></option>
            <?php endforeach; ?>
            <option value="Multiple services" <?= $selectedService === 'Multiple services' ? 'selected' : '' ?>>Multiple services</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Project details</label>
          <p class="text-xs text-slate-500 mb-2">Volumes, timelines, delivery regions, or notes about the products above.</p>
          <textarea name="message" rows="5" class="input-field" placeholder="Brief description of your requirements…"><?= old('message') ?></textarea>
        </div>
        <button type="submit" class="btn-primary w-full sm:w-auto" data-loading-text="Submitting…">Submit quote request</button>
      </form>
    </div>

    <p class="text-center text-sm text-slate-600 mt-8">
      Not ready for a quote?
      <a href="<?= url('contact') ?>" class="font-semibold text-brand-navy hover:text-brand-gold">Contact us</a>
      for general questions.
    </p>
  </div>
</section>
<script>
document.querySelectorAll('.quote-cart-qty-input').forEach(function (input) {
  input.addEventListener('change', function () {
    var slug = input.getAttribute('data-slug');
    var hidden = document.querySelector('.quote-cart-qty-hidden[data-slug="' + slug + '"]');
    if (hidden) hidden.value = input.value;
  });
});
</script>
