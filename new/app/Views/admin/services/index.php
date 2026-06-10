<?php \App\Core\View::partial('admin/toolbar', [
    'meta' => count($services) . ' service' . (count($services) === 1 ? '' : 's'),
    'filterHtml' => null,
    'actions' => [
        ['label' => 'Add service', 'url' => url('admin/services/create'), 'class' => 'btn-primary'],
    ],
]); ?>

<?php if ($services === []): ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => 'services',
      'title' => 'No services yet',
      'description' => 'Add your logistics services so visitors can explore import, warehousing, and distribution offerings.',
      'actionUrl' => url('admin/services/create'),
      'actionLabel' => 'Add first service',
  ]); ?>
<?php else: ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $s): ?>
          <tr>
            <td class="font-medium text-slate-900"><?= e($s['title']) ?></td>
            <td class="text-slate-500"><?= e($s['slug']) ?></td>
            <td>
              <span class="admin-badge <?= $s['is_published'] ? 'admin-badge--published' : 'admin-badge--draft' ?>">
                <?= $s['is_published'] ? 'Published' : 'Draft' ?>
              </span>
            </td>
            <td class="text-right">
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
<?php endif; ?>
