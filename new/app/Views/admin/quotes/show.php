<?php if (!$item): ?><p>Not found.</p><?php return; endif; ?>
<div class="grid lg:grid-cols-2 gap-8">
  <div class="card space-y-3 text-sm">
    <p><strong>Name:</strong> <?= e($item['name']) ?></p>
    <p><strong>Company:</strong> <?= e($item['company'] ?? '—') ?></p>
    <p><strong>Email:</strong> <?= e($item['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($item['phone'] ?? '—') ?></p>
    <p><strong>Service:</strong> <?= e($item['service_needed']) ?></p>
    <p class="whitespace-pre-wrap"><strong>Message:</strong><br><?= e($item['message'] ?? '') ?></p>
  </div>
  <form method="post" action="<?= url('admin/quotes/' . $item['id'] . '/status') ?>" class="card space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <label class="block text-sm font-medium">Status</label>
    <select name="status" class="input-field">
      <?php foreach (['new','in_review','contacted','closed'] as $s): ?>
        <option value="<?= $s ?>" <?= $item['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
    <textarea name="admin_notes" rows="4" class="input-field" placeholder="Internal notes"><?= e($item['admin_notes'] ?? '') ?></textarea>
    <button type="submit" class="btn-primary">Update</button>
  </form>
</div>
