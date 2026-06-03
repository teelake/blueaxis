<?php
/**
 * Quill rich text editor (BSD-3-Clause — free for commercial use)
 * @var string $name Form field name
 * @var string $id Unique element id
 * @var string $value Initial HTML content
 * @var int $height Editor height in px
 */
$name = $name ?? 'content';
$id = $id ?? 'editor_' . preg_replace('/[^a-z0-9]/', '_', $name);
$value = $value ?? '';
$height = $height ?? 320;
$uploadUrl = $upload_url ?? '';
$csrf = $csrf ?? '';
?>
<div class="rich-editor-wrap" data-editor-id="<?= e($id) ?>"
  <?= $uploadUrl !== '' ? 'data-upload-url="' . e($uploadUrl) . '"' : '' ?>
  <?= $csrf !== '' ? 'data-csrf="' . e($csrf) . '"' : '' ?>>
  <div id="<?= e($id) ?>_mount" class="rich-editor-mount bg-white border border-slate-200 rounded-md" style="min-height:<?= (int) $height ?>px"></div>
  <textarea name="<?= e($name) ?>" id="<?= e($id) ?>" class="sr-only rich-editor-source" aria-hidden="true"><?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?></textarea>
</div>
