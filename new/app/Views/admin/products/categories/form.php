<form method="post" action="<?= $category ? url('admin/products/categories/' . $category['id']) : url('admin/products/categories') ?>" class="admin-panel max-w-2xl">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel__body space-y-5">
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Category name',
        'name' => 'name',
        'value' => $category['name'] ?? '',
        'required' => true,
        'maxlength' => 100,
        'placeholder' => 'Oils & fats',
    ]); ?>
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'URL slug',
        'name' => 'slug',
        'value' => $category['slug'] ?? '',
        'hint' => 'Leave blank to auto-generate from the name.',
        'placeholder' => 'oils-fats',
    ]); ?>
    <?php \App\Core\View::partial('admin/field', [
        'label' => 'Display order',
        'name' => 'sort_order',
        'type' => 'number',
        'value' => (string) ($category['sort_order'] ?? 0),
        'hint' => 'Lower numbers appear first in catalog filters.',
    ]); ?>
  </div>
  <div class="admin-sticky-footer">
    <a href="<?= url('admin/products/categories') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy shrink-0">← All categories</a>
    <div class="admin-sticky-footer__actions">
      <?php if ($category): ?>
        <button formaction="<?= url('admin/products/categories/' . $category['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary" onclick="return confirm('Delete this category permanently?')">Delete</button>
      <?php endif; ?>
      <button type="submit" class="btn-primary"><?= $category ? 'Save category' : 'Create category' ?></button>
    </div>
  </div>
</form>
