<?php
/**
 * Drag-and-drop image field.
 * @var string $name Hidden input name for stored path
 * @var string $id Unique id prefix
 * @var string $value Current uploads/... path
 * @var string $label Field label
 * @var string $hint Optional help text
 * @var string $uploadUrl Defaults to admin media upload
 * @var string $csrf CSRF token
 * @var string $accept File input accept attribute
 * @var string $formats Formats hint shown in drop zone
 * @var bool $compact Smaller drop zone (e.g. favicon)
 */
$name = $name ?? 'image';
$id = $id ?? 'img_' . preg_replace('/[^a-z0-9]/', '_', $name);
$value = $value ?? '';
$label = $label ?? 'Image';
$accept = $accept ?? 'image/*';
$formats = $formats ?? 'JPG, PNG, WebP';
$compact = !empty($compact);
$uploadUrl = $uploadUrl ?? url('admin/media/upload');
$csrf = $csrf ?? \App\Core\Csrf::token();
$previewUrl = $value !== '' ? media_url($value) : '';
$zoneClass = 'image-upload-zone' . ($compact ? ' image-upload-zone--compact' : '');
?>
<div
  class="<?= e($zoneClass) ?>"
  data-image-upload
  data-upload-url="<?= e($uploadUrl) ?>"
  data-csrf="<?= e($csrf) ?>"
  data-initial-path="<?= e($value) ?>"
  data-initial-url="<?= e($previewUrl) ?>"
>
  <label class="admin-label" for="<?= e($id) ?>_file"><?= e($label) ?></label>
  <?php if (!empty($hint)): ?>
    <p class="admin-hint mb-2"><?= e($hint) ?></p>
  <?php endif; ?>
  <div class="image-upload-zone__drop">
    <img data-upload-preview src="<?= $previewUrl !== '' ? e($previewUrl) : '' ?>" alt="" class="<?= $previewUrl === '' ? 'hidden' : '' ?>" />
    <div data-upload-placeholder class="image-upload-zone__placeholder <?= $previewUrl !== '' ? 'hidden' : '' ?>">
      <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      <p class="text-sm font-medium text-slate-600">Drag & drop an image here</p>
      <p class="text-xs text-slate-400 mt-1">or click to browse · <?= e($formats) ?></p>
    </div>
    <input type="file" id="<?= e($id) ?>_file" data-upload-input accept="<?= e($accept) ?>" class="sr-only" />
    <input type="hidden" name="<?= e($name) ?>" data-upload-path value="<?= e($value) ?>" />
  </div>
  <button type="button" data-upload-clear class="text-xs font-medium text-slate-500 hover:text-red-600 mt-2 <?= $value === '' ? 'hidden' : '' ?>">Remove image</button>
</div>
