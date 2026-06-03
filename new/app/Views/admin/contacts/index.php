<div class="flex justify-between mb-6">
  <form method="get" class="flex gap-2">
    <input name="q" value="<?= e($search) ?>" class="input-field" placeholder="Search…" />
    <button class="btn-secondary">Search</button>
  </form>
  <a href="<?= url('admin/contacts/export') ?>" class="btn-secondary">Export CSV</a>
</div>
<div class="bg-white rounded-xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50"><tr><th class="p-4 text-left">Name</th><th>Company</th><th>Email</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr class="border-t hover:bg-slate-50">
          <td class="p-4"><a href="<?= url('admin/contacts/' . $item['id']) ?>" class="font-medium text-brand-navy"><?= e($item['name']) ?></a></td>
          <td><?= e($item['company'] ?? '') ?></td>
          <td><?= e($item['email']) ?></td>
          <td><?= e($item['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
