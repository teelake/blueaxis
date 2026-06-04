<?php
/** @var array $product */
$priceLabel = format_product_price($product);
if ($priceLabel === null) {
    return;
}
?>
<p class="product-price <?= e($priceClass ?? '') ?>"><?= e($priceLabel) ?></p>
