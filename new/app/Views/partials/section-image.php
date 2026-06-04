<?php
/** @var string|null $imagePath uploads/... path */
/** @var string $alt */
/** @var string $side left|right */
if (empty($imagePath)) {
    return;
}
$align = ($align ?? 'right') === 'left' ? 'left' : 'right';
$orderClass = $align === 'left' ? 'lg:order-first' : 'lg:order-last';
$aosDir = $align === 'left' ? 'fade-right' : 'fade-left';
?>
<div class="<?= e($orderClass) ?>" data-aos="<?= e($aosDir) ?>">
  <div class="rounded-2xl overflow-hidden shadow-elevated aspect-[4/3] bg-slate-100">
    <img src="<?= e(media_url($imagePath)) ?>" alt="<?= e($alt) ?>" class="w-full h-full object-cover" loading="lazy" />
  </div>
</div>
