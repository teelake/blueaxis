<?php if (!$item): ?><p>Not found.</p><?php return; endif; ?>
<div class="card max-w-2xl space-y-3 text-sm">
  <p><strong>Name:</strong> <?= e($item['name']) ?></p>
  <p><strong>Company:</strong> <?= e($item['company'] ?? '—') ?></p>
  <p><strong>Email:</strong> <a href="mailto:<?= e($item['email']) ?>"><?= e($item['email']) ?></a></p>
  <p><strong>Phone:</strong> <?= e($item['phone'] ?? '—') ?></p>
  <p><strong>Date:</strong> <?= e($item['created_at']) ?></p>
  <div class="pt-4 border-t"><strong>Message</strong><p class="mt-2 text-slate-700 whitespace-pre-wrap"><?= e($item['message']) ?></p></div>
  <a href="<?= url('admin/contacts') ?>" class="btn-secondary inline-flex mt-4">Back</a>
</div>
