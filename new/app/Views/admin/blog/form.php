<form method="post" action="<?= $post ? url('admin/blog/' . $post['id']) : url('admin/blog') ?>" class="max-w-3xl space-y-4">
  <?= \App\Core\Csrf::field() ?>
  <div class="card space-y-4">
    <input name="title" value="<?= e($post['title'] ?? '') ?>" class="input-field" placeholder="Title" required />
    <input name="slug" value="<?= e($post['slug'] ?? '') ?>" class="input-field" placeholder="slug" />
    <select name="category_id" class="input-field">
      <option value="">Category</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= ($post['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <textarea name="excerpt" rows="2" class="input-field"><?= e($post['excerpt'] ?? '') ?></textarea>
    <label class="block text-sm font-medium text-slate-700">Content</label>
    <?php \App\Core\View::partial('rich-editor', [
        'name' => 'content',
        'id' => 'blog_content',
        'value' => $post['content'] ?? '',
        'height' => 360,
    ]); ?>
    <input name="featured_image" value="<?= e($post['featured_image'] ?? '') ?>" class="input-field" placeholder="Featured image path" />
    <input name="meta_title" value="<?= e($post['meta_title'] ?? '') ?>" class="input-field" />
    <textarea name="meta_description" rows="2" class="input-field"><?= e($post['meta_description'] ?? '') ?></textarea>
    <select name="status" class="input-field">
      <option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
    </select>
    <input name="published_at" type="datetime-local" value="<?= $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>" class="input-field" />
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_featured" <?= ($post['is_featured'] ?? 0) ? 'checked' : '' ?> /> Featured</label>
    <button type="submit" class="btn-primary">Save post</button>
    <?php if ($post): ?>
      <button formaction="<?= url('admin/blog/' . $post['id'] . '/delete') ?>" formmethod="post" class="btn-secondary" onclick="return confirm('Delete?')">Delete</button>
    <?php endif; ?>
  </div>
</form>
