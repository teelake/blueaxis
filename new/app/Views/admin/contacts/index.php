<?php
$perPage = (int) config('app.per_page_admin');
$totalPages = max(1, (int) ceil(($total ?? 0) / $perPage));
$page = max(1, (int) ($_GET['page'] ?? 1));
?>
<div class="flex flex-wrap justify-between gap-4 mb-6">
  <form method="get" class="flex flex-wrap gap-2 items-center">
    <input name="q" value="<?= e($search) ?>" class="admin-input max-w-xs" placeholder="Search name, email, company…" />
    <button type="submit" class="btn-secondary" data-loading-text="Searching…">Search</button>
  </form>
  <?php if (($total ?? 0) > 0): ?>
    <a href="<?= url('admin/contacts/export') ?>" class="btn-secondary">Export CSV</a>
  <?php endif; ?>
</div>

<?php if (empty($items)): ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => ($search ?? '') !== '' ? 'search' : 'inbox',
      'title' => ($search ?? '') !== '' ? 'No messages match your search' : 'No contact messages yet',
      'description' => ($search ?? '') !== ''
          ? 'Try a different name, email, or company keyword.'
          : 'When visitors submit the contact form on your website, inquiries will appear here.',
      'actionUrl' => ($search ?? '') !== '' ? url('admin/contacts') : url('contact'),
      'actionLabel' => ($search ?? '') !== '' ? 'Clear search' : 'View contact page',
  ]); ?>
<?php else: ?>
  <p class="text-sm text-slate-600 mb-4"><?= (int) $total ?> message<?= $total === 1 ? '' : 's' ?> — click a row or use View to open the full message.</p>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Preview</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <?php
          $preview = truncate(trim((string) ($item['message'] ?? '')), 80);
          $unread = empty($item['is_read']);
          ?>
          <tr class="<?= $unread ? 'bg-brand-gold-muted/20' : '' ?>">
            <td class="font-medium text-slate-900">
              <a href="<?= url('admin/contacts/' . $item['id']) ?>" class="text-brand-navy hover:text-brand-gold inline-flex items-center gap-2">
                <?= e($item['name']) ?>
                <?php if ($unread): ?><span class="admin-badge admin-badge--pending text-[10px] px-1.5 py-0">New</span><?php endif; ?>
              </a>
              <p class="text-xs text-slate-500 mt-0.5"><?= e($item['email']) ?></p>
            </td>
            <td><?= e($item['company'] ?? '—') ?></td>
            <td class="text-slate-600 text-sm max-w-xs"><?= e($preview) ?></td>
            <td class="text-slate-500 whitespace-nowrap"><?= e(date('M j, Y g:ia', strtotime($item['created_at']))) ?></td>
            <td class="text-right">
              <?php \App\Core\View::partial('admin/row-actions', [
                  'detailUrl' => url('admin/contacts/' . $item['id']),
                  'entityLabel' => 'message',
              ]); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($totalPages > 1): ?>
    <nav class="flex flex-wrap gap-2 justify-center mt-8" aria-label="Pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php
        $qs = http_build_query(array_filter(['q' => $search ?? '', 'page' => $i > 1 ? $i : null]));
        $href = url('admin/contacts') . ($qs !== '' ? '?' . $qs : '');
        ?>
        <a href="<?= e($href) ?>" class="px-3 py-1.5 text-sm rounded-lg border <?= $i === $page ? 'bg-brand-navy text-white border-brand-navy' : 'border-slate-200 text-slate-600 hover:bg-slate-50' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </nav>
  <?php endif; ?>
<?php endif; ?>
