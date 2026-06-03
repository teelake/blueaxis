<div class="flex justify-between items-center mb-6">
  <p class="text-sm text-slate-600"><?= count($services) ?> services</p>
  <a href="<?= url('admin/services/create') ?>" class="btn-primary">Add service</a>
</div>
<div class="bg-white rounded-xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-left"><tr><th class="p-4">Title</th><th>Slug</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($services as $s): ?>
        <tr class="border-t">
          <td class="p-4 font-medium"><?= e($s['title']) ?></td>
          <td><?= e($s['slug']) ?></td>
          <td><?= $s['is_published'] ? 'Published' : 'Draft' ?></td>
          <td class="p-4 text-right"><a href="<?= url('admin/services/' . $s['id'] . '/edit') ?>" class="text-brand-navy font-medium">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
