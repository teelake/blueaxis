<?php
/** @var array $product Must include slug */
$redirect = $redirect ?? 'quote';
$qty = $qty ?? true;
$label = $label ?? 'Add to quote list';
$btnClass = $btnClass ?? 'btn-secondary';
?>
<form method="post" action="<?= url('quote/cart/add') ?>" class="flex flex-col sm:inline-flex sm:flex-row flex-wrap items-stretch sm:items-center gap-2 w-full sm:w-auto">
  <?= \App\Core\Csrf::field() ?>
  <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>" />
  <input type="hidden" name="redirect" value="<?= e($redirect) ?>" />
  <?php if ($qty): ?>
    <label class="sr-only" for="qty_<?= e($product['slug']) ?>">Quantity</label>
    <input
      type="number"
      name="quantity"
      id="qty_<?= e($product['slug']) ?>"
      value="1"
      min="1"
      max="9999"
      class="input-field w-20 min-h-[44px] text-sm"
      aria-label="Quantity"
    />
  <?php else: ?>
    <input type="hidden" name="quantity" value="1" />
  <?php endif; ?>
  <button type="submit" class="<?= e($btnClass) ?> text-sm min-h-[44px]"><?= e($label) ?></button>
</form>
