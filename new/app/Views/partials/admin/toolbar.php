<?php
/**
 * Horizontal toolbar for admin list pages: meta, filters/search form, action buttons.
 *
 * @var string|null $meta
 * @var string|null $filterHtml Raw HTML for a <form> (use class admin-toolbar__form)
 * @var list<array{label: string, url?: string, class?: string, attrs?: string}> $actions
 */
$actions = $actions ?? [];
?>
<div class="admin-toolbar">
  <?php if (!empty($meta)): ?>
    <p class="admin-toolbar__meta"><?= e($meta) ?></p>
  <?php endif; ?>
  <div class="admin-toolbar__cluster">
    <?php if (!empty($filterHtml)): ?>
      <?= $filterHtml ?>
    <?php endif; ?>
    <?php if ($actions !== []): ?>
      <div class="admin-toolbar__actions" role="group" aria-label="Page actions">
        <?php foreach ($actions as $action): ?>
          <?php if (empty($action['url'])) {
              continue;
          } ?>
          <a
            href="<?= e($action['url']) ?>"
            class="<?= e($action['class'] ?? 'btn-secondary') ?>"
            <?= $action['attrs'] ?? '' ?>
          ><?= e($action['label']) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
