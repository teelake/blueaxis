<?php
/**
 * Icon action buttons for admin tables.
 * @var string $editUrl
 * @var string|null $detailUrl Admin detail page (e.g. view full message)
 * @var string|null $viewUrl Public page (opens new tab)
 * @var string|null $toggleUrl POST URL to publish/unpublish or activate/deactivate
 * @var bool $isActive Whether item is published/active
 * @var string|null $deleteUrl POST URL to delete
 * @var string $entityLabel For confirm dialogs, e.g. "product"
 */
$editUrl = $editUrl ?? '';
$detailUrl = $detailUrl ?? null;
$viewUrl = $viewUrl ?? null;
$toggleUrl = $toggleUrl ?? null;
$deleteUrl = $deleteUrl ?? null;
$isActive = $isActive ?? true;
$entityLabel = $entityLabel ?? 'item';
$toggleOnLabel = $toggleOnLabel ?? 'Activate';
$toggleOffLabel = $toggleOffLabel ?? 'Deactivate';
$toggleOffConfirm = $toggleOffConfirm ?? 'Deactivate this ' . $entityLabel . ' on the website?';
$toggleOnConfirm = $toggleOnConfirm ?? 'Activate this ' . $entityLabel . ' on the website?';
$deleteConfirm = $deleteConfirm ?? 'Delete this ' . $entityLabel . ' permanently? This cannot be undone.';
?>
<div class="admin-action-group" role="group" aria-label="Actions">
  <?php if ($detailUrl): ?>
    <a href="<?= e($detailUrl) ?>" class="admin-icon-btn" title="View details" aria-label="View details">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    </a>
  <?php endif; ?>
  <?php if ($editUrl !== ''): ?>
    <a href="<?= e($editUrl) ?>" class="admin-icon-btn" title="Edit" aria-label="Edit">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    </a>
  <?php endif; ?>
  <?php if ($viewUrl): ?>
    <a href="<?= e($viewUrl) ?>" class="admin-icon-btn" title="View on website" aria-label="View on website" target="_blank" rel="noopener noreferrer">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
    </a>
  <?php endif; ?>
  <?php if ($toggleUrl): ?>
    <form method="post" action="<?= e($toggleUrl) ?>" class="inline">
      <?= \App\Core\Csrf::field() ?>
      <button
        type="submit"
        class="admin-icon-btn <?= $isActive ? 'admin-icon-btn--warning' : '' ?>"
        title="<?= e($isActive ? $toggleOffLabel : $toggleOnLabel) ?>"
        aria-label="<?= e($isActive ? $toggleOffLabel : $toggleOnLabel) ?>"
        onclick="return confirm('<?= e($isActive ? $toggleOffConfirm : $toggleOnConfirm) ?>')"
      >
        <?php if ($isActive): ?>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        <?php else: ?>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?php endif; ?>
      </button>
    </form>
  <?php endif; ?>
  <?php if ($deleteUrl): ?>
    <form method="post" action="<?= e($deleteUrl) ?>" class="inline">
      <?= \App\Core\Csrf::field() ?>
      <button
        type="submit"
        class="admin-icon-btn admin-icon-btn--danger"
        title="Delete"
        aria-label="Delete"
        onclick="return confirm('<?= e($deleteConfirm) ?>')"
      >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </button>
    </form>
  <?php endif; ?>
</div>
