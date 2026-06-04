<?php
$perPage = (int) config('app.per_page_admin');
$totalPages = max(1, (int) ceil($total / $perPage));
?>
<form method="get" class="flex flex-wrap gap-3 items-center justify-between mb-6">
  <p class="text-sm text-slate-600"><?= (int) $total ?> products</p>
  <div class="flex flex-wrap gap-3 items-center">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search title, SKU, category…" class="admin-input max-w-xs" />
    <button type="submit" class="btn-secondary">Search</button>
    <a href="<?= url('admin/products/create') ?>" class="btn-primary">Add product</a>
  </div>
</form>
<div class="bg-white rounded-xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-left">
      <tr>
        <th class="p-4 w-16"></th>
        <th class="p-4">Product</th>
        <th>Category</th>
        <th>SKU</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if ($products === []): ?>
        <tr><td colspan="6" class="p-8 text-center text-slate-500">No products yet. Add your first catalog item.</td></tr>
      <?php endif; ?>
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
          <td><?= $p['is_published'] ? 'Published' : 'Draft' ?></td>
          <td class="p-4 text-right"><a href="<?= url('admin/products/' . $p['id'] . '/edit') ?>" class="text-brand-navy font-medium">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php if ($totalPages > 1): ?>
  <div class="flex gap-2 mt-6 justify-center">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="<?= url('admin/products?page=' . $i . ($search !== '' ? '&q=' . urlencode($search) : '')) ?>" class="px-3 py-1 rounded text-sm <?= $i === $page ? 'bg-brand-navy text-white' : 'bg-white border text-slate-600' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
<?php endif; ?>
