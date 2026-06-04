<?php
/**
 * @var string $variant footer|light — footer uses gold hover on dark bg; light for contact card
 */
$links = social_links();
if ($links === []) {
    return;
}
$variant = $variant ?? 'footer';
$isFooter = $variant === 'footer';
$linkClass = $isFooter
    ? 'inline-flex items-center justify-center w-10 h-10 rounded-lg border border-white/15 text-slate-300 hover:text-brand-gold hover:border-brand-gold/40 transition'
    : 'inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 text-brand-navy hover:text-brand-gold hover:border-brand-gold/50 transition';
$iconClass = 'w-5 h-5';
?>
<ul class="flex flex-wrap gap-2" aria-label="Social media">
  <?php foreach ($links as $link): ?>
    <li>
      <a
        href="<?= e($link['url']) ?>"
        class="<?= $linkClass ?>"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="<?= e($link['label']) ?>"
      >
        <?= social_icon_svg($link['id'], $iconClass) ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
