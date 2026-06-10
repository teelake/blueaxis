<?php
/** @var array<string, mixed> $slide */
$primaryUrl = trim((string) ($slide['cta_primary_url'] ?? $slide['link_url'] ?? ''));
$primaryLabel = trim((string) ($slide['cta_primary_label'] ?? $slide['link_label'] ?? ''));
$secondaryUrl = trim((string) ($slide['cta_secondary_url'] ?? ''));
$secondaryLabel = trim((string) ($slide['cta_secondary_label'] ?? ''));
$hasCtas = ($primaryUrl !== '' && $primaryLabel !== '') || ($secondaryUrl !== '' && $secondaryLabel !== '');
?>
<div class="max-w-3xl" data-aos="fade-up">
  <?php if (!empty($slide['eyebrow'])): ?>
    <p class="section-eyebrow text-brand-gold-light mb-4"><?= e((string) $slide['eyebrow']) ?></p>
  <?php endif; ?>
  <?php if (!empty($slide['title'])): ?>
    <h1 class="font-sans font-semibold text-4xl sm:text-5xl lg:text-6xl leading-tight tracking-tight mb-6"><?= e((string) $slide['title']) ?></h1>
  <?php endif; ?>
  <?php if (!empty($slide['subtitle'])): ?>
    <p class="text-lg text-slate-300 leading-relaxed mb-10 max-w-2xl"><?= e((string) $slide['subtitle']) ?></p>
  <?php endif; ?>
  <?php if ($hasCtas): ?>
    <div class="flex flex-wrap gap-4">
      <?php if ($primaryUrl !== '' && $primaryLabel !== ''): ?>
        <a href="<?= e(footer_nav_href($primaryUrl)) ?>" class="btn-accent"><?= e($primaryLabel) ?></a>
      <?php endif; ?>
      <?php if ($secondaryUrl !== '' && $secondaryLabel !== ''): ?>
        <a href="<?= e(footer_nav_href($secondaryUrl)) ?>" class="btn-secondary-hero"><?= e($secondaryLabel) ?></a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
