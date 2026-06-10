<?php if (!empty($pendingComments)): ?>
  <div class="admin-alert admin-alert--success mb-6" style="background:#fffbeb;border-color:#fde68a;color:#92400e">
    <?= (int) $pendingComments ?> comment(s) waiting for your approval — open an article to review.
  </div>
<?php endif; ?>

<?php ob_start(); ?>
<form method="get" class="admin-toolbar__form admin-toolbar__form--wide">
  <label class="sr-only" for="blog-status">Status</label>
  <select id="blog-status" name="status" class="admin-select">
    <option value="">All articles</option>
    <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
    <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Drafts</option>
  </select>
  <button type="submit" class="btn-secondary">Apply</button>
</form>
<?php
$filterHtml = ob_get_clean();
\App\Core\View::partial('admin/toolbar', [
    'meta' => !empty($posts) ? count($posts) . ' article' . (count($posts) === 1 ? '' : 's') : null,
    'filterHtml' => $filterHtml,
    'actions' => [
        ['label' => '+ New article', 'url' => url('admin/blog/create'), 'class' => 'btn-primary'],
    ],
]);
?>

<?php if (!empty($posts)): ?>
  <?php require __DIR__ . '/_list.php'; ?>
<?php else: ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => 'article',
      'title' => 'No articles yet',
      'description' => 'Publish insights and company news for your B2B partners.',
      'actionUrl' => url('admin/blog/create'),
      'actionLabel' => 'Write first article',
  ]); ?>
<?php endif; ?>
