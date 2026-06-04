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
        <?php \App\Core\View::partial('admin/field', ['label' => 'URL slug', 'name' => 'slug', 'value' => $product['slug'] ?? '', 'hint' => 'Leave blank to auto-generate from the title.', 'placeholder' => 'palm-oil-bulk']); ?>
        <div>
          <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
          <input
            id="category"
            name="category"
            list="product-category-suggestions"
            class="admin-input w-full max-w-lg"
            value="<?= e($product['category'] ?? '') ?>"
            placeholder="Oils & fats"
          />
          <?php if (!empty($categories)): ?>
            <datalist id="product-category-suggestions">
              <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat) ?>"></option>
              <?php endforeach; ?>
            </datalist>
          <?php endif; ?>
          <p class="mt-1 text-xs text-slate-500">
            Type a category name for this product. Matching names group together on the public catalog filters.
            There is no separate category list — reuse an existing name from the suggestions or enter a new one.
          </p>
        </div>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SKU / product code', 'name' => 'sku', 'value' => $product['sku'] ?? '', 'placeholder' => 'BAX-PO-001']); ?>
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
        <?php \App\Core\View::partial('admin/field', ['label' => 'Pack format', 'name' => 'pack_format', 'value' => $product['pack_format'] ?? '', 'placeholder' => '20L drum / palletized']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'Storage & handling', 'name' => 'storage_notes', 'value' => $product['storage_notes'] ?? '', 'type' => 'textarea', 'hint' => 'Temperature, shelf life, or warehouse requirements.']); ?>
      </div>

      <div x-show="tab === 'seo'" x-cloak class="space-y-5 max-w-2xl">
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO title', 'name' => 'meta_title', 'value' => $product['meta_title'] ?? '']); ?>
        <?php \App\Core\View::partial('admin/field', ['label' => 'SEO description', 'name' => 'meta_description', 'value' => $product['meta_description'] ?? '', 'type' => 'textarea']); ?>
      </div>
    </div>

    <div class="admin-sticky-footer">
      <a href="<?= url('admin/products') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy">← Back to products</a>
      <div class="flex gap-3">
        <?php if ($product): ?>
          <button formaction="<?= url('admin/products/' . $product['id'] . '/delete') ?>" formmethod="post" type="submit" class="btn-secondary" onclick="return confirm('Delete this product permanently?')">Delete</button>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Save product</button>
      </div>
    </div>
  </div>
</form>
