<?php
$perPage = (int) config('app.per_page_admin');
$totalPages = max(1, (int) ceil($total / $perPage));
ob_start();
?>
<form method="get" class="admin-toolbar__form">
  <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search title, SKU, category…" class="admin-input" />
  <button type="submit" class="btn-secondary" data-loading-text="Searching…">Search</button>
</form>
<?php
$filterHtml = ob_get_clean();
\App\Core\View::partial('admin/toolbar', [
    'meta' => (int) $total . ' product' . ($total === 1 ? '' : 's'),
    'filterHtml' => $filterHtml,
    'actions' => [
        ['label' => 'Download CSV template', 'url' => url('admin/products/bulk-import/template'), 'class' => 'btn-secondary'],
        ['label' => 'Bulk import', 'url' => url('admin/products/bulk-import'), 'class' => 'btn-secondary'],
        ['label' => 'Add product', 'url' => url('admin/products/create'), 'class' => 'btn-primary'],
    ],
]);
?>
<?php if ($products === []): ?>
  <?php \App\Core\View::partial('admin/empty-state', [
      'icon' => ($search ?? '') !== '' ? 'search' : 'catalog',
      'title' => ($search ?? '') !== '' ? 'No products match your search' : 'No products in the catalog',
      'description' => ($search ?? '') !== ''
          ? 'Try another title, SKU, or category keyword.'
          : 'Add wholesale SKUs with images and logistics specs for your product catalog page.',
      'actionUrl' => ($search ?? '') !== '' ? url('admin/products') : url('admin/products/create'),
      'actionLabel' => ($search ?? '') !== '' ? 'Clear search' : 'Add first product',
  ]); ?>
<?php else: ?>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th class="w-16"></th>
        <th>Product</th>
        <th>Category</th>
        <th>SKU</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr class="border-t">
          <td class="p-4">
            <?php if (!empty($p['image_path'])): ?>
              <img src="<?= e(media_url($p['image_path'])) ?>" alt="" class="w-12 h-12 rounded-lg object-cover border border-slate-100" />
            <?php else: ?>
              <div class="w-12 h-12 rounded-lg bg-slate-100"></div>
            <?php endif; ?>
          </td>
          <td class="p-4 font-medium">
            <?= e($p['title']) ?>
            <?php if ($p['is_featured']): ?><span class="ml-2 text-xs text-brand-gold font-semibold">Featured</span><?php endif; ?>
          </td>
          <td><?= e($p['category'] ?? '—') ?></td>
          <td class="text-slate-500"><?= e($p['sku'] ?? '—') ?></td>
          <td class="text-slate-600 text-sm whitespace-nowrap"><?= format_product_price($p) !== null ? e(format_product_price($p)) : '—' ?></td>
          <td>
            <span class="admin-badge <?= $p['is_published'] ? 'admin-badge--published' : 'admin-badge--draft' ?>">
              <?= $p['is_published'] ? 'Published' : 'Draft' ?>
            </span>
          </td>
          <td class="p-4 text-right">
            <?php \App\Core\View::partial('admin/row-actions', [
                'editUrl' => url('admin/products/' . $p['id'] . '/edit'),
                'viewUrl' => $p['is_published'] ? url('products/' . $p['slug']) : null,
                'toggleUrl' => url('admin/products/' . $p['id'] . '/toggle-publish'),
                'deleteUrl' => url('admin/products/' . $p['id'] . '/delete'),
                'isActive' => (bool) $p['is_published'],
                'entityLabel' => 'product',
                'toggleOffLabel' => 'Unpublish',
                'toggleOnLabel' => 'Publish',
                'toggleOffConfirm' => 'Unpublish this product? It will be hidden from the catalog.',
                'toggleOnConfirm' => 'Publish this product on the website?',
            ]); ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
<?php if ($totalPages > 1 && $products !== []): ?>
  <div class="flex gap-2 mt-6 justify-center">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="<?= url('admin/products?page=' . $i . ($search !== '' ? '&q=' . urlencode($search) : '')) ?>" class="px-3 py-1 rounded text-sm <?= $i === $page ? 'bg-brand-navy text-white' : 'bg-white border text-slate-600' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
<?php endif; ?>
