<form method="post" action="<?= url('admin/media/upload') ?>" enctype="multipart/form-data" class="card mb-8 flex flex-wrap gap-4 items-end">
  <?= \App\Core\Csrf::field() ?>
  <input type="file" name="file" accept="image/*" required class="text-sm" />
  <button type="submit" class="btn-primary">Upload</button>
</form>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
  <?php foreach ($items as $m): ?>
    <div class="card p-2">
      <img src="/<?= e(str_replace('\\', '/', $m['path'])) ?>" alt="<?= e($m['alt_text'] ?? '') ?>" class="w-full h-24 object-cover rounded" />
      <p class="text-xs truncate mt-2" title="<?= e($m['original_name']) ?>"><?= e($m['original_name']) ?></p>
      <p class="text-xs text-slate-400 font-mono truncate">/<?= e($m['path']) ?></p>
      <form method="post" action="<?= url('admin/media/' . $m['id'] . '/delete') ?>" class="mt-2">
        <?= \App\Core\Csrf::field() ?>
        <button type="submit" class="text-xs text-red-600" onclick="return confirm('Delete?')">Delete</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
