<div class="flex flex-wrap justify-between gap-4 mb-6">
  <form method="get" class="flex flex-wrap gap-2">
    <input name="q" value="<?= e($search) ?>" class="input-field" placeholder="Search" />
    <select name="status" class="input-field">
      <option value="">All statuses</option>
      <?php foreach (['new','in_review','contacted','closed'] as $s): ?>
        <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucfirst(str_replace('_',' ',$s))) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn-secondary">Filter</button>
  </form>
  <a href="<?= url('admin/quotes/export') ?>" class="btn-secondary">Export CSV</a>
</div>
<div class="bg-white rounded-xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50"><tr><th class="p-4 text-left">Name</th><th>Service</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr class="border-t">
          <td class="p-4"><a href="<?= url('admin/quotes/' . $item['id']) ?>" class="font-medium text-brand-navy"><?= e($item['name']) ?></a></td>
          <td><?= e($item['service_needed']) ?></td>
          <td><span class="px-2 py-1 rounded text-xs bg-brand-gold/20"><?= e($item['status']) ?></span></td>
          <td><?= e($item['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
