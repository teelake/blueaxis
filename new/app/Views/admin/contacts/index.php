<?php
$perPage = (int) config('app.per_page_admin');
$totalPages = max(1, (int) ceil(($total ?? 0) / $perPage));
$page = max(1, (int) ($_GET['page'] ?? 1));
ob_start();
?>
<form method="get" class="admin-toolbar__form">
  <input type="search" name="q" value="<?= e($search) ?>" class="admin-input" placeholder="Search name, email, company…" />
  <button type="submit" class="btn-secondary" data-loading-text="Searching…">Search</button>
</form>
<?php
$filterHtml = ob_get_clean();
$actions = [];
if (($total ?? 0) > 0) {
    $actions[] = ['label' => 'Export CSV', 'url' => url('admin/contacts/export'), 'class' => 'btn-secondary'];
}
\App\Core\View::partial('admin/toolbar', [
    'meta' => ($total ?? 0) > 0 ? (int) $total . ' message' . ((int) $total === 1 ? '' : 's') : null,
    'filterHtml' => $filterHtml,
    'actions' => $actions,
]);
?>

<?php if (empty($items)): ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => ($search ?? '') !== '' ? 'search' : 'inbox',
      'title' => ($search ?? '') !== '' ? 'No messages match your search' : 'No contact messages yet',
      'description' => ($search ?? '') !== ''
          ? 'Try a different name, email, or company.'
          : 'Messages from your contact form will appear here.',
      'actionUrl' => ($search ?? '') !== '' ? url('admin/contacts') : url('contact'),
      'actionLabel' => ($search ?? '') !== '' ? 'Clear search' : 'View contact page',
      'actionExternal' => ($search ?? '') === '',
  ]); ?>
<?php else: ?>
  <?php require __DIR__ . '/_table.php'; ?>
<?php endif; ?>
