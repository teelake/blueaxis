<?php
$featuredUrl = !empty($post['featured_image']) ? media_url($post['featured_image']) : '';
?>
<form method="post" action="<?= $post ? url('admin/blog/' . $post['id']) : url('admin/blog') ?>" enctype="multipart/form-data" class="max-w-6xl">
  <?= \App\Core\Csrf::field() ?>
  <div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
      <div class="card space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input name="title" value="<?= e($post['title'] ?? '') ?>" class="input-field" required />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Slug</label>
          <input name="slug" value="<?= e($post['slug'] ?? '') ?>" class="input-field" placeholder="auto-from-title" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Excerpt</label>
          <textarea name="excerpt" rows="3" class="input-field"><?= e($post['excerpt'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Content</label>
          <?php \App\Core\View::partial('rich-editor', [
              'name' => 'content',
              'id' => 'blog_content',
              'value' => $post['content'] ?? '',
              'height' => 400,
              'upload_url' => $uploadUrl,
              'csrf' => $csrf,
          ]); ?>
        </div>
      </div>

      <?php if ($post): ?>
        <div id="comments" class="card">
          <h2 class="font-semibold text-brand-navy mb-4">Comments</h2>
          <?php if (empty($comments)): ?>
            <p class="text-sm text-slate-500">No comments yet.</p>
          <?php else: ?>
            <ul class="space-y-4">
              <?php foreach ($comments as $c): ?>
                <li class="border border-slate-100 rounded-lg p-4 text-sm">
                  <div class="flex flex-wrap justify-between gap-2 mb-2">
                    <strong><?= e($c['author_name']) ?></strong>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $c['status'] === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($c['status'] === 'spam' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') ?>"><?= e($c['status']) ?></span>
                  </div>
                  <p class="text-slate-600"><?= e($c['body']) ?></p>
                  <p class="text-xs text-slate-400 mt-2"><?= e($c['email']) ?> · <?= e($c['created_at']) ?></p>
                  <?php if ($c['status'] !== 'approved'): ?>
                    <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/approve') ?>" class="inline mt-2">
                      <?= \App\Core\Csrf::field() ?>
                      <button type="submit" class="text-xs font-semibold text-brand-navy hover:text-brand-gold">Approve</button>
                    </form>
                  <?php endif; ?>
                  <?php if ($c['status'] !== 'spam'): ?>
                    <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/spam') ?>" class="inline mt-2 ml-2">
                      <?= \App\Core\Csrf::field() ?>
                      <button type="submit" class="text-xs text-slate-500 hover:text-red-600">Spam</button>
                    </form>
                  <?php endif; ?>
                  <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/delete') ?>" class="inline mt-2 ml-2">
                    <?= \App\Core\Csrf::field() ?>
                    <button type="submit" class="text-xs text-red-600" onclick="return confirm('Delete comment?')">Delete</button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="space-y-6">
      <div class="card space-y-4">
        <h2 class="font-semibold text-brand-navy text-sm">Publish</h2>
        <select name="status" class="input-field">
          <option value="draft" <?= ($post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
        </select>
        <div>
          <label class="block text-sm font-medium mb-1">Publish date</label>
          <input name="published_at" type="datetime-local" value="<?= !empty($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>" class="input-field" />
        </div>
        <label class="flex gap-2 text-sm items-center">
          <input type="checkbox" name="is_featured" <?= ($post['is_featured'] ?? 0) ? 'checked' : '' ?> />
          Featured post
        </label>
        <button type="submit" class="btn-primary w-full">Save post</button>
        <?php if ($post): ?>
          <button formaction="<?= url('admin/blog/' . $post['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary w-full" onclick="return confirm('Delete this post?')">Delete post</button>
        <?php endif; ?>
      </div>

      <div class="card space-y-4" x-data="featuredImagePicker()" x-init="init('<?= e($featuredUrl) ?>', '<?= e($post['featured_image'] ?? '') ?>')">
        <h2 class="font-semibold text-brand-navy text-sm">Featured image</h2>
        <div class="rounded-lg border border-slate-200 bg-slate-50 overflow-hidden aspect-video flex items-center justify-center">
          <img x-show="preview" :src="preview" alt="" class="w-full h-full object-cover" />
          <span x-show="!preview" class="text-xs text-slate-400">No image selected</span>
        </div>
        <input type="hidden" name="featured_image" x-model="path" />
        <input type="file" name="featured_image_file" accept="image/*" class="text-sm w-full" @change="onFile($event)" />
        <p class="text-xs text-slate-500">Or pick from library:</p>
        <div class="grid grid-cols-3 gap-2 max-h-40 overflow-y-auto">
          <?php foreach ($mediaItems as $m): ?>
            <button type="button" class="rounded border-2 border-transparent hover:border-brand-gold overflow-hidden" @click="pick('<?= e($m['path']) ?>', '<?= e(media_url($m['path'])) ?>')">
              <img src="<?= e(media_url($m['path'])) ?>" alt="" class="w-full h-14 object-cover" />
            </button>
          <?php endforeach; ?>
        </div>
        <button type="button" class="text-xs text-slate-500 hover:text-red-600" x-show="path" @click="clear()">Remove featured image</button>
      </div>

      <div class="card space-y-4">
        <h2 class="font-semibold text-brand-navy text-sm">Category</h2>
        <select name="category_id" class="input-field">
          <option value="">— None —</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($post['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="card space-y-4">
        <h2 class="font-semibold text-brand-navy text-sm">Tags</h2>
        <input name="tags" value="<?= e($tags ?? '') ?>" class="input-field" placeholder="logistics, warehousing, B2B" />
        <p class="text-xs text-slate-500">Comma-separated</p>
      </div>

      <div class="card space-y-4">
        <h2 class="font-semibold text-brand-navy text-sm">SEO</h2>
        <input name="meta_title" value="<?= e($post['meta_title'] ?? '') ?>" class="input-field" placeholder="Meta title" />
        <textarea name="meta_description" rows="3" class="input-field" placeholder="Meta description"><?= e($post['meta_description'] ?? '') ?></textarea>
      </div>
    </div>
  </div>
</form>

<script>
function featuredImagePicker() {
  return {
    preview: '',
    path: '',
    init(url, path) {
      this.preview = url || '';
      this.path = path || '';
    },
    pick(path, url) {
      this.path = path;
      this.preview = url;
    },
    clear() {
      this.path = '';
      this.preview = '';
    },
    onFile(e) {
      if (e.target.files && e.target.files[0]) {
        this.preview = URL.createObjectURL(e.target.files[0]);
      }
    }
  };
}
</script>
