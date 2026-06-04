<?php
/**
 * Site logo (header or footer).
 * @var string $variant header|footer
 * @var bool $link Wrap in home link (default true)
 */
$variant = ($variant ?? 'header') === 'footer' ? 'footer' : 'header';
$link = $link ?? true;
$url = site_logo_url($variant);
$alt = site_logo_alt();
$invert = $variant === 'footer' && site_logo_footer_invert();
$imgClass = 'site-logo' . ($variant === 'footer' ? ' site-logo--footer' : '');
if ($invert) {
    $imgClass .= ' site-logo--invert';
}
$attrs = [
    'src' => $url,
    'alt' => $alt,
    'class' => $imgClass,
    'width' => '280',
    'height' => '76',
    'decoding' => 'async',
];
if ($variant === 'header') {
    $attrs['fetchpriority'] = 'high';
}
if ($variant === 'footer') {
    unset($attrs['fetchpriority']);
    $attrs['loading'] = 'lazy';
}
?>
<?php if ($link): ?>
  <a href="<?= url('/') ?>" class="site-logo-link" aria-label="<?= e($alt) ?> — Home">
<?php endif; ?>
<img
  src="<?= e($attrs['src']) ?>"
  alt="<?= e($attrs['alt']) ?>"
  class="<?= e($attrs['class']) ?>"
  width="<?= e($attrs['width']) ?>"
  height="<?= e($attrs['height']) ?>"
  decoding="<?= e($attrs['decoding']) ?>"
  <?php if (!empty($attrs['fetchpriority'])): ?>fetchpriority="<?= e($attrs['fetchpriority']) ?>"<?php endif; ?>
  <?php if (!empty($attrs['loading'])): ?>loading="<?= e($attrs['loading']) ?>"<?php endif; ?>
/>
<?php if ($link): ?>
  </a>
<?php endif; ?>
