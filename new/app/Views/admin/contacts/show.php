<?php
if (!$item) {
    \App\Core\View::partial('admin/empty-state', [
        'icon' => 'inbox',
        'title' => 'Message not found',
        'description' => 'This contact inquiry may have been removed.',
        'actionUrl' => url('admin/contacts'),
        'actionLabel' => 'Back to contacts',
    ]);
    return;
}
$submitted = date('F j, Y \a\t g:i A', strtotime($item['created_at']));
$isUnread = empty($item['is_read']);
?>
<?php \App\Core\View::partial('admin/lead-detail-header', [
    'backUrl' => url('admin/contacts'),
    'backLabel' => 'All contact messages',
    'reference' => 'Contact from ' . ($item['name'] ?? 'Visitor'),
    'submittedAt' => $submitted,
    'badgeHtml' => $isUnread
        ? '<span class="admin-badge admin-badge--pending">Unread</span>'
        : '<span class="admin-badge admin-badge--published">Read</span>',
]); ?>

<div class="grid lg:grid-cols-3 gap-8">
  <div class="lg:col-span-2 space-y-6">
    <section class="admin-panel admin-panel__body">
      <h3 class="admin-section-title mb-4">Message</h3>
      <div class="rounded-xl bg-slate-50 border border-slate-100 p-6 text-slate-800 leading-relaxed whitespace-pre-wrap text-base">
        <?= e($item['message']) ?>
      </div>
    </section>
  </div>

  <aside class="space-y-6">
    <section class="admin-panel admin-panel__body space-y-4">
      <h3 class="admin-section-title">Contact details</h3>
      <dl class="space-y-4 text-sm">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Name</dt>
          <dd class="font-medium text-slate-900"><?= e($item['name']) ?></dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Company</dt>
          <dd class="text-slate-700"><?= e($item['company'] ?? '—') ?></dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Email</dt>
          <dd>
            <a href="mailto:<?= e($item['email']) ?>" class="font-medium text-brand-navy hover:text-brand-gold break-all"><?= e($item['email']) ?></a>
          </dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Phone</dt>
          <dd class="text-slate-700">
            <?php if (!empty($item['phone'])): ?>
              <a href="tel:<?= e(preg_replace('/\s+/', '', $item['phone'])) ?>" class="text-brand-navy hover:text-brand-gold"><?= e($item['phone']) ?></a>
            <?php else: ?>
              —
            <?php endif; ?>
          </dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Reference</dt>
          <dd class="font-mono text-slate-600">#<?= (int) $item['id'] ?></dd>
        </div>
      </dl>
    </section>

    <section class="admin-panel admin-panel__body space-y-3">
      <h3 class="admin-section-title">Quick actions</h3>
      <a href="mailto:<?= e($item['email']) ?>?subject=<?= rawurlencode('Re: Your message to BlueAxis') ?>" class="btn-primary w-full justify-center">
        Reply by email
      </a>
      <?php if (!empty($item['phone'])): ?>
        <a href="tel:<?= e(preg_replace('/\s+/', '', $item['phone'])) ?>" class="btn-secondary w-full justify-center">Call</a>
      <?php endif; ?>
    </section>
  </aside>
</div>
