<div class="flex flex-wrap gap-4 justify-between mb-6">
  <form method="get" class="flex gap-2">
    <select name="status" class="input-field">
      <option value="">All</option>
      <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
      <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
    </select>
    <button class="btn-secondary">Filter</button>
  </form>
  <a href="<?= url('admin/blog/create') ?>" class="btn-primary">New post</a>
</div>
<div class="bg-white rounded-xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50"><tr><th class="p-4 text-left">Title</th><th>Status</th><th>Date</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr class="border-t">
          <td class="p-4"><?= e($p['title']) ?></td>
          <td><?= e($p['status']) ?></td>
          <td><?= e($p['updated_at']) ?></td>
          <td class="p-4"><a href="<?= url('admin/blog/' . $p['id'] . '/edit') ?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
