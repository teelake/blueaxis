<?php $csrf = \App\Core\Csrf::token(); ?>
<form method="post" action="<?= $product ? url('admin/products/' . $product['id']) : url('admin/products') ?>" x-data="adminTabs('details')">
  <?= \App\Core\Csrf::field() ?>
  <div class="admin-panel">
    <nav class="admin-tabs-nav">
      <button type="button" class="admin-tab-btn" :class="tab === 'details' && 'active'" @click="tab = 'details'">Details</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'content' && 'active'" @click="tab = 'content'">Description</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'media' && 'active'" @click="tab = 'media'">Image</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'logistics' && 'active'" @click="tab = 'logistics'">Logistics specs</button>
      <button type="button" class="admin-tab-btn" :class="tab === 'seo' && 'active'" @click="tab = 'seo'">SEO & visibility</button>
    </nav>

    <div class="admin-panel__body">
      <div x-show="tab === 'details'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'Product name', 'name' => 'title', 'value' => $product['title'] ?? '', 'required' => true, 'maxlength' => 200]); ?>
        <?php if ($product): ?>
          <p class="admin-hint">
            SKU <span class="font-mono text-slate-700"><?= e((string) ($product['sku'] ?? '—')) ?></span>
            · URL <span class="font-mono text-slate-700">/products/<?= e((string) ($product['slug'] ?? '')) ?></span>
            <?php if (trim((string) ($product['title'] ?? '')) !== ''): ?>
              — slug updates automatically if you change the product name.
            <?php endif; ?>
          </p>
        <?php else: ?>
          <p class="admin-hint">SKU and URL slug are generated automatically when you save.</p>
        <?php endif; ?>
        <?php
          $currentCategory = (string) ($product['category'] ?? '');
          $categoryNames = array_map(
              static fn ($cat) => is_array($cat) ? (string) ($cat['name'] ?? '') : (string) $cat,
              $categories
          );
        ?>
        <div class="admin-field">
          <label class="admin-label" for="category">Category</label>
          <select id="category" name="category" class="admin-select max-w-lg">
            <option value="">— No category —</option>
            <?php if ($currentCategory !== '' && !in_array($currentCategory, $categoryNames, true)): ?>
              <option value="<?= e($currentCategory) ?>" selected><?= e($currentCategory) ?> (not in category list)</option>
            <?php endif; ?>
            <?php foreach ($categories as $cat): ?>
              <?php $catName = is_array($cat) ? ($cat['name'] ?? '') : (string) $cat; ?>
              <option value="<?= e($catName) ?>" <?= $currentCategory === $catName ? 'selected' : '' ?>><?= e($catName) ?></option>
            <?php endforeach; ?>
          </select>
          <p class="admin-hint mt-1">
            <a href="<?= url('admin/products/categories') ?>" class="text-brand-navy font-medium hover:text-brand-gold">Manage categories</a>
            — add or edit catalog filters shown on the public products page.
          </p>
        </div>
        <div class="grid sm:grid-cols-2 gap-4 max-w-2xl">
          <div class="admin-field">
            <label class="admin-label" for="price">List price (optional)</label>
            <p class="admin-hint">Shown on catalog cards and product pages when set. Leave empty to hide price. Quotes still use your B2B pricing workflow.</p>
            <input
              id="price"
              name="price"
              type="number"
              min="0"
              step="0.01"
              class="admin-input"
              value="<?= isset($product['price']) && $product['price'] !== null && $product['price'] !== '' ? e((string) $product['price']) : '' ?>"
              placeholder="Leave blank to hide"
            />
          </div>
          <?php \App\Core\View::partial('admin/field', [
              'label' => 'Price unit (optional)',
              'name' => 'price_unit',
              'value' => $product['price_unit'] ?? '',
              'placeholder' => 'per case',
              'hint' => 'e.g. per case, per kg, per pallet',
          ]); ?>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Short summary', 'name' => 'excerpt', 'value' => $product['excerpt'] ?? '', 'type' => 'textarea', 'hint' => 'Shown on catalog cards.']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Display order', 'name' => 'sort_order', 'value' => (string) ($product['sort_order'] ?? 0), 'type' => 'number']); ?>
        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
          <input type="checkbox" name="is_featured" class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy" <?= ($product['is_featured'] ?? 0) ? 'checked' : '' ?> />
          Feature on catalog (highlighted row)
        </label>
        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
          <input type="checkbox" name="is_published" class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy" <?= ($product['is_published'] ?? 1) ? 'checked' : '' ?> />
          Visible on the website
        </label>
      </div>

      <div x-show="tab === 'content'" x-cloak class="max-w-3xl">
        <h2 class="admin-section-title mb-2">Full description</h2>
        <p class="admin-section-desc">Specifications, sourcing notes, and wholesale details.</p>
        <?php \App\Core\View::partial('rich-editor', [
            'name' => 'description',
            'id' => 'product_description',
            'value' => $product['description'] ?? '',
            'height' => 320,
        ]); ?>
      </div>

      <div x-show="tab === 'media'" x-cloak class="max-w-xl">
        <?php \App\Core\View::partial('admin/image-upload', [
            'name' => 'image_path',
            'id' => 'product_image',
            'value' => $product['image_path'] ?? '',
            'label' => 'Product image',
            'hint' => 'Drag and drop or click to upload. Shown on catalog cards and detail page.',
            'csrf' => $csrf,
        ]); ?>
      </div>

      <div x-show="tab === 'logistics'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'Origin region', 'name' => 'origin_region', 'value' => $product['origin_region'] ?? '', 'placeholder' => 'West Africa']); ?>
        <div class="grid sm:grid-cols-2 gap-4">
          <?php \App\Core\View::partial('admin/field', ['label' => 'Size', 'name' => 'size', 'value' => $product['size'] ?? '', 'placeholder' => '20L']); ?>
          <?php \App\Core\View::partial('admin/field', ['label' => 'Pack format', 'name' => 'pack_format', 'value' => $product['pack_format'] ?? '', 'placeholder' => 'drum / palletized']); ?>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Storage & handling', 'name' => 'storage_notes', 'value' => $product['storage_notes'] ?? '', 'type' => 'textarea', 'hint' => 'Temperature, shelf life, or warehouse requirements.']); ?>
      </div>

      <div x-show="tab === 'seo'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO title', 'name' => 'meta_title', 'value' => $product['meta_title'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO description', 'name' => 'meta_description', 'value' => $product['meta_description'] ?? '', 'type' => 'textarea']); ?>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <a href="<?= url('admin/products') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy shrink-0">← Back to products</a>
      <div class="admin-sticky-footer__actions">
        <?php if ($product): ?>
          <button formaction="<?= url('admin/products/' . $product['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary" onclick="return confirm('Delete this product permanently?')">Delete</button>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Save product</button>
      </div>
    </div>
  </div>
</form>
