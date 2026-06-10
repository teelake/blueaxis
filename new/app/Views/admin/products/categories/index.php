<?php \App\Core\View::partial('admin/toolbar', [
    'meta' => count($categories) . ' categor' . (count($categories) === 1 ? 'y' : 'ies'),
    'filterHtml' => null,
    'actions' => [
        ['label' => '← Products', 'url' => url('admin/products'), 'class' => 'btn-secondary'],
        ['label' => 'Add category', 'url' => url('admin/products/categories/create'), 'class' => 'btn-primary'],
    ],
]); ?>

<?php if ($categories === []): ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => 'catalog',
      'title' => 'No categories yet',
      'description' => 'Create categories to organize your product catalog filters on the public site.',
      'actionUrl' => url('admin/products/categories/create'),
      'actionLabel' => 'Add first category',
  ]); ?>
<?php else: ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <th>Products</th>
          <th>Order</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
          <tr class="border-t">
            <td class="p-4 font-medium text-slate-900"><?= e($cat['name']) ?></td>
            <td class="p-4 text-slate-500 font-mono text-sm"><?= e($cat['slug']) ?></td>
            <td class="p-4 text-slate-600"><?= (int) ($cat['product_count'] ?? 0) ?></td>
            <td class="p-4 text-slate-500"><?= (int) $cat['sort_order'] ?></td>
            <td class="p-4 text-right">
              <?php \App\Core\View::partial('admin/row-actions', [
                  'editUrl' => url('admin/products/categories/' . $cat['id'] . '/edit'),
                  'deleteUrl' => url('admin/products/categories/' . $cat['id'] . '/delete'),
                  'entityLabel' => 'category',
              ]); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
