<?php
/**
 * @var string $backUrl
 * @var string $backLabel
 * @var string $reference e.g. Contact #12
 * @var string $submittedAt
 * @var string|null $badgeHtml optional status/read badge HTML
 */
?>
<div class="flex flex-wrap items-start justify-between gap-4 mb-8">
  <div>
    <a href="<?= e($backUrl) ?>" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-brand-navy mb-3">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      <?= e($backLabel) ?>
    </a>
    <h2 class="text-xl font-semibold text-slate-900"><?= e($reference) ?></h2>
    <p class="text-sm text-slate-500 mt-1">Received <?= e($submittedAt) ?></p>
  </div>
  <?php if (!empty($badgeHtml)): ?>
    <div class="shrink-0"><?= $badgeHtml ?></div>
  <?php endif; ?>
</div>
