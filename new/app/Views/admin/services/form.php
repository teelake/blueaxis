<?php
$benefits = '';
if ($service && $service['benefits']) {
    $list = json_decode($service['benefits'], true) ?: [];
    $benefits = implode("\n", $list);
}
?>
<form method="post" action="<?= $service ? url('admin/services/' . $service['id']) : url('admin/services') ?>" class="max-w-2xl space-y-4 card">
  <?= \App\Core\Csrf::field() ?>
  <input name="title" value="<?= e($service['title'] ?? '') ?>" class="input-field" placeholder="Title" required />
  <input name="slug" value="<?= e($service['slug'] ?? '') ?>" class="input-field" placeholder="slug" />
  <textarea name="excerpt" class="input-field" rows="2"><?= e($service['excerpt'] ?? '') ?></textarea>
  <label class="block text-sm font-medium text-slate-700">Description</label>
  <?php \App\Core\View::partial('rich-editor', [
      'name' => 'description',
      'id' => 'service_description',
      'value' => $service['description'] ?? '',
      'height' => 280,
  ]); ?>
  <textarea name="benefits_lines" class="input-field" rows="4" placeholder="One benefit per line"><?= e($benefits) ?></textarea>
  <input name="banner_image" value="<?= e($service['banner_image'] ?? '') ?>" class="input-field" placeholder="Banner image path" />
  <input name="meta_title" value="<?= e($service['meta_title'] ?? '') ?>" class="input-field" placeholder="SEO title" />
  <textarea name="meta_description" class="input-field" rows="2"><?= e($service['meta_description'] ?? '') ?></textarea>
  <input name="sort_order" type="number" value="<?= (int) ($service['sort_order'] ?? 0) ?>" class="input-field w-32" />
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" <?= ($service['is_published'] ?? 1) ? 'checked' : '' ?> /> Published</label>
  <button type="submit" class="btn-primary">Save</button>
  <?php if ($service): ?>
    <button formaction="<?= url('admin/services/' . $service['id'] . '/delete') ?>" formmethod="post" class="btn-secondary ml-2" onclick="return confirm('Delete?')">Delete</button>
  <?php endif; ?>
</form>
