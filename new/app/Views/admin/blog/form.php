<?php
$featuredUrl = !empty($post['featured_image']) ? media_url($post['featured_image']) : '';
$defaultTab = $post ? 'content' : 'content';
?>
<form method="post" action="<?= $post ? url('admin/blog/' . $post['id']) : url('admin/blog') ?>" enctype="multipart/form-data" x-data="adminTabs('<?= $defaultTab ?>')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'content' && 'active'" @click="tab = 'content'">Article</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'publish' && 'active'" @click="tab = 'publish'">Publish</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'media' && 'active'" @click="tab = 'media'">Image & tags</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'seo' && 'active'" @click="tab = 'seo'">SEO</button>
      <?php if ($post): ?>
        <button type="button" class="admin-tab-btn" :class="tab === 'comments' && 'active'" @click="tab = 'comments'">Comments</button>
      <?php endif; ?>
    </nav>

    <div class="admin-panel__body">
      <div x-show="tab === 'content'" x-cloak class="space-y-5 max-w-3xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'Article title', 'name' => 'title', 'value' => $post['title'] ?? '', 'required' => true]); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'URL slug', 'name' => 'slug', 'value' => $post['slug'] ?? '', 'hint' => 'Leave blank to create from the title.', 'placeholder' => 'my-article-name']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Short summary', 'name' => 'excerpt', 'value' => $post['excerpt'] ?? '', 'type' => 'textarea', 'hint' => 'Shown in blog listings and search results.']); ?>
        <div class="admin-field">
          <label class="admin-label">Article body</label>
          <p class="admin-hint">Use the image button in the toolbar to upload photos into your article.</p>
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

      <div x-show="tab === 'publish'" x-cloak class="space-y-5 max-w-md">
        <h2 class="admin-section-title">Publishing</h2>
        <p class="admin-section-desc">Control when and how this article appears on the site.</p>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Status',
            'name' => 'status',
            'type' => 'select',
            'value' => $post['status'] ?? 'draft',
            'options' => ['draft' => 'Draft — hidden from visitors', 'published' => 'Published — live on the blog'],
        ]); ?>
        <?php \App\Core\View::partial('admin/field', [
            'label' => 'Publish date & time',
            'name' => 'published_at',
            'type' => 'datetime-local',
            'value' => !empty($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '',
            'hint' => 'For scheduled publishing, set a future date.',
        ]); ?>
        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
          <input type="checkbox" name="is_featured" class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy" <?= ($post['is_featured'] ?? 0) ? 'checked' : '' ?> />
          Feature this article on the blog homepage
        </label>
      </div>

      <div x-show="tab === 'media'" x-cloak class="space-y-8 max-w-2xl" x-data="featuredImagePicker()" x-init="initFeatured('<?= e($featuredUrl) ?>', '<?= e($post['featured_image'] ?? '') ?>')">
        <div>
          <h2 class="admin-section-title">Featured image</h2>
          <p class="admin-section-desc">Large image at the top of the article. Upload a file or choose from your library.</p>
          <div class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden aspect-video flex items-center justify-center mt-4">
            <img x-show="preview" :src="preview" alt="" class="w-full h-full object-cover" />
            <span x-show="!preview" class="text-sm text-slate-400">No image selected</span>
          </div>
          <input type="hidden" name="featured_image" x-model="path" />
          <div class="mt-4 admin-field">
            <label class="admin-label">Upload new image</label>
            <input type="file" name="featured_image_file" accept="image/*" class="text-sm text-slate-600" @change="onFile($event)" />
          </div>
          <p class="admin-hint mt-4">Or click a thumbnail below:</p>
          <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-h-48 overflow-y-auto mt-2">
            <?php foreach ($mediaItems as $m): ?>
              <button type="button" class="rounded-lg border-2 border-transparent hover:border-brand-gold overflow-hidden focus:outline-none focus:border-brand-navy" @click="pick('<?= e($m['path']) ?>', '<?= e(media_url($m['path'])) ?>')">
                <img src="<?= e(media_url($m['path'])) ?>" alt="" class="w-full h-16 object-cover" />
              </button>
            <?php endforeach; ?>
          </div>
          <button type="button" class="admin-btn-ghost mt-2" x-show="path" @click="clear()">Remove featured image</button>
        </div>
        <div>
          <?php
          $catOpts = ['' => '— No category —'];
          foreach ($categories as $c) {
              $catOpts[(string) $c['id']] = $c['name'];
          }
          \App\Core\View::partial('admin/field', [
              'label' => 'Category',
              'name' => 'category_id',
              'type' => 'select',
              'value' => (string) ($post['category_id'] ?? ''),
              'options' => $catOpts,
          ]);
          ?>
          <?php \App\Core\View::partial('admin/field', [
              'label' => 'Tags',
              'name' => 'tags',
              'value' => $tags ?? '',
              'placeholder' => 'logistics, supply chain, Manitoba',
              'hint' => 'Separate tags with commas. Helps organize related articles.',
          ]); ?>
        </div>
      </div>

      <div x-show="tab === 'seo'" x-cloak class="space-y-5 max-w-2xl">
        <h2 class="admin-section-title">Search engines</h2>
        <p class="admin-section-desc">Optional. Improves how this page appears in Google and when shared on social media.</p>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO title', 'name' => 'meta_title', 'value' => $post['meta_title'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO description', 'name' => 'meta_description', 'value' => $post['meta_description'] ?? '', 'type' => 'textarea']); ?>
      </div>

      <?php if ($post): ?>
        <div x-show="tab === 'comments'" x-cloak id="comments">
          <h2 class="admin-section-title">Reader comments</h2>
          <p class="admin-section-desc">Approve comments before they appear on the public article.</p>
          <?php if (empty($comments)): ?>
            <p class="text-sm text-slate-500 mt-6">No comments on this article yet.</p>
          <?php else: ?>
            <ul class="space-y-4 mt-6">
              <?php foreach ($comments as $c): ?>
                <li class="admin-repeater-row">
                  <div class="flex flex-wrap justify-between gap-2 mb-2">
                    <strong class="text-slate-900"><?= e($c['author_name']) ?></strong>
                    <span class="admin-badge <?= $c['status'] === 'approved' ? 'admin-badge--published' : ($c['status'] === 'spam' ? 'bg-red-100 text-red-800' : 'admin-badge--pending') ?>"><?= e($c['status']) ?></span>
                  </div>
                  <p class="text-slate-600 text-sm"><?= e($c['body']) ?></p>
                  <p class="text-xs text-slate-400 mt-2"><?= e($c['email']) ?> · <?= e($c['created_at']) ?></p>
                  <div class="flex flex-wrap gap-3 mt-3">
                    <?php if ($c['status'] !== 'approved'): ?>
                      <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/approve') ?>">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="text-sm font-semibold text-brand-navy hover:text-brand-gold">Approve</button>
                      </form>
                    <?php endif; ?>
                    <?php if ($c['status'] !== 'spam'): ?>
                      <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/spam') ?>">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Mark spam</button>
                      </form>
                    <?php endif; ?>
                    <form method="post" action="<?= url('admin/blog/' . $post['id'] . '/comments/' . $c['id'] . '/delete') ?>">
                      <?= \App\Core\Csrf::field() ?>
                      <button type="submit" class="text-sm text-red-600" onclick="return confirm('Delete this comment?')">Delete</button>
                    </form>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="admin-sticky-footer">
      <a href="<?= url('admin/blog') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy">← All articles</a>
      <div class="flex gap-3">
        <?php if ($post): ?>
          <button formaction="<?= url('admin/blog/' . $post['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary" onclick="return confirm('Delete this article permanently?')">Delete</button>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Save article</button>
      </div>
    </div>
  </div>
</form>

<script>
function featuredImagePicker() {
  return {
    preview: '',
    path: '',
    initFeatured(url, path) {
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
