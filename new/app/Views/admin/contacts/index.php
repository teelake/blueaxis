<div class="flex flex-wrap justify-between gap-4 mb-6">
  <form method="get" class="flex flex-wrap gap-2 items-center">
    <input name="q" value="<?= e($search) ?>" class="admin-input max-w-xs" placeholder="Search name, email, company…" />
    <button type="submit" class="btn-secondary">Search</button>
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
  <p class="text-sm text-slate-600 mb-4"><?= (int) $total ?> message<?= $total === 1 ? '' : 's' ?></p>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Email</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td class="font-medium text-slate-900">
              <a href="<?= url('admin/contacts/' . $item['id']) ?>" class="text-brand-navy hover:text-brand-gold"><?= e($item['name']) ?></a>
            </td>
            <td><?= e($item['company'] ?? '—') ?></td>
            <td><?= e($item['email']) ?></td>
            <td class="text-slate-500"><?= e(date('M j, Y g:ia', strtotime($item['created_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
