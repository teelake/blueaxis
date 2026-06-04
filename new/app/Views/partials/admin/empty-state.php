<?php
/**
 * Empty state for admin list / report pages.
 * @var string $icon inbox|quotes|blog|catalog|services|media|search|chart
 * @var string $title
 * @var string $description
 * @var string|null $actionUrl
 * @var string|null $actionLabel
 */
$icon = $icon ?? 'inbox';
$title = $title ?? 'Nothing here yet';
$description = $description ?? '';
$icons = [
    'inbox' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>',
    'quotes' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'blog' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>',
    'catalog' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V7l-8-4-8 4v6m16 0v6l-8 4m8-10L12 17m0 0L4 13m8 4V7M4 7v6l8 4"/>',
    'services' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
    'media' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
    'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
    'chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
];
$svgPath = $icons[$icon] ?? $icons['inbox'];
?>
<div class="admin-empty-state">
  <div class="admin-empty-state__icon" aria-hidden="true">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $svgPath ?></svg>
  </div>
  <h2 class="admin-empty-state__title"><?= e($title) ?></h2>
  <?php if ($description !== ''): ?>
    <p class="admin-empty-state__desc"><?= e($description) ?></p>
  <?php endif; ?>
  <?php if (!empty($actionUrl) && !empty($actionLabel)): ?>
    <a href="<?= e($actionUrl) ?>" class="btn-primary mt-6"><?= e($actionLabel) ?></a>
  <?php endif; ?>
</div>
