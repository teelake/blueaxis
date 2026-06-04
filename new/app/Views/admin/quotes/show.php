<?php
if (!$item):
    ?><p>Not found.</p><?php
    return;
endif;
use App\Services\QuoteCartService;
$products = QuoteCartService::parseStored($item['products_json'] ?? null);
?>
<div class="grid lg:grid-cols-2 gap-8">
  <div class="card space-y-4 text-sm">
    <p><strong>Name:</strong> <?= e($item['name']) ?></p>
    <p><strong>Company:</strong> <?= e($item['company'] ?? '—') ?></p>
    <p><strong>Email:</strong> <?= e($item['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($item['phone'] ?? '—') ?></p>
    <p><strong>Service:</strong> <?= e($item['service_needed']) ?></p>
    <?php if ($products !== []): ?>
      <div>
        <p class="font-semibold text-brand-navy mb-2">Products requested</p>
        <ul class="list-disc list-inside space-y-1 text-slate-700">
          <?php foreach ($products as $p): ?>
            <li>
              <?= (int) ($p['quantity'] ?? 1) ?>× <?= e($p['title'] ?? 'Product') ?>
              <?php if (!empty($p['sku'])): ?><span class="text-slate-500 font-mono text-xs">(<?= e($p['sku']) ?>)</span><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <p class="whitespace-pre-wrap"><strong>Message:</strong><br><?= e($item['message'] ?? '') ?></p>
    <p class="text-xs text-slate-500">Submitted <?= e(date('M j, Y g:ia', strtotime($item['created_at']))) ?></p>
  </div>
  <form method="post" action="<?= url('admin/quotes/' . $item['id'] . '/status') ?>" class="card space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <label class="block text-sm font-medium">Status</label>
    <select name="status" class="input-field">
      <?php foreach (['new', 'in_review', 'contacted', 'closed'] as $s): ?>
        <option value="<?= $s ?>" <?= $item['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
    <textarea name="admin_notes" rows="4" class="input-field" placeholder="Internal notes"><?= e($item['admin_notes'] ?? '') ?></textarea>
    <button type="submit" class="btn-primary" data-loading-text="Updating…">Update</button>
  </form>
</div>
