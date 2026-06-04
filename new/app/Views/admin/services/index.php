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
          <td>
            <span class="admin-badge <?= $s['is_published'] ? 'admin-badge--published' : 'admin-badge--draft' ?>">
              <?= $s['is_published'] ? 'Published' : 'Draft' ?>
            </span>
          </td>
          <td class="p-4 text-right">
            <?php \App\Core\View::partial('admin/row-actions', [
                'editUrl' => url('admin/services/' . $s['id'] . '/edit'),
                'viewUrl' => $s['is_published'] ? url('services/' . $s['slug']) : null,
                'toggleUrl' => url('admin/services/' . $s['id'] . '/toggle-publish'),
                'deleteUrl' => url('admin/services/' . $s['id'] . '/delete'),
                'isActive' => (bool) $s['is_published'],
                'entityLabel' => 'service',
                'toggleOffLabel' => 'Unpublish',
                'toggleOnLabel' => 'Publish',
                'toggleOffConfirm' => 'Unpublish this service? It will be hidden from the website.',
                'toggleOnConfirm' => 'Publish this service on the website?',
            ]); ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
