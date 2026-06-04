<div class="admin-panel admin-panel__body mb-8">
  <h2 class="admin-section-title">Upload images</h2>
  <p class="admin-section-desc">Photos you upload here can be used in blog posts, services, and page content.</p>
  <form method="post" action="<?= url('admin/media/upload') ?>" enctype="multipart/form-data" class="flex flex-wrap gap-4 items-end mt-6">
    <?= \App\Core\Csrf::field() ?>
    <div class="admin-field flex-1 min-w-[200px]">
      <label class="admin-label">Choose file</label>
      <input type="file" name="file" accept="image/*" required class="text-sm text-slate-600" />
    </div>
    <button type="submit" class="btn-primary">Upload</button>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="admin-panel admin-panel__body text-center text-slate-500 py-12">
    No images yet. Upload your first file above.
  </div>
<?php else: ?>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
    <?php foreach ($items as $m): ?>
      <div class="admin-panel overflow-hidden">
        <img src="<?= e(media_url($m['path'])) ?>" alt="<?= e($m['alt_text'] ?? '') ?>" class="w-full h-32 object-cover" />
        <div class="p-3">
          <p class="text-xs font-medium text-slate-800 truncate" title="<?= e($m['original_name']) ?>"><?= e($m['original_name']) ?></p>
          <p class="text-xs text-slate-500 mt-2">Click “Use” when editing a post</p>
          <form method="post" action="<?= url('admin/media/' . $m['id'] . '/delete') ?>" class="mt-2">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="text-xs text-red-600 hover:underline" onclick="return confirm('Delete this image?')">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
