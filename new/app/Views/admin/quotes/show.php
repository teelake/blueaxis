<?php
use App\Models\Product;
use App\Services\QuoteCartService;

if (!$item) {
    \App\Core\View::partial('admin/empty-state', [
        'icon' => 'quotes',
        'title' => 'Quote request not found',
        'description' => 'This quote may have been removed.',
        'actionUrl' => url('admin/quotes'),
        'actionLabel' => 'Back to quotes',
    ]);
    return;
}

$products = QuoteCartService::parseStored($item['products_json'] ?? null);
$submitted = date('F j, Y \a\t g:i A', strtotime($item['created_at']));
$status = (string) ($item['status'] ?? 'new');
$statusLabel = ucfirst(str_replace('_', ' ', $status));
$statusClass = match ($status) {
    'closed' => 'admin-badge--draft',
    'contacted' => 'admin-badge--published',
    'in_review' => 'admin-badge--pending',
    default => 'admin-badge--pending',
};
?>
<?php \App\Core\View::partial('admin/lead-detail-header', [
    'backUrl' => url('admin/quotes'),
    'backLabel' => 'All quote requests',
    'reference' => 'Quote from ' . ($item['company'] ?? $item['name'] ?? 'Visitor'),
    'submittedAt' => $submitted,
    'badgeHtml' => '<span class="admin-badge ' . $statusClass . '">' . e($statusLabel) . '</span>',
]); ?>

<div class="grid lg:grid-cols-3 gap-8">
  <div class="lg:col-span-2 space-y-6">
    <section class="admin-panel admin-panel__body">
      <h3 class="admin-section-title mb-4">Request summary</h3>
      <dl class="grid sm:grid-cols-2 gap-6 text-sm">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Service needed</dt>
          <dd class="font-medium text-brand-navy"><?= e($item['service_needed']) ?></dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Reference</dt>
          <dd class="font-mono text-slate-600">#<?= (int) $item['id'] ?></dd>
        </div>
      </dl>
    </section>

    <?php if ($products !== []): ?>
      <section class="admin-panel admin-panel__body">
        <h3 class="admin-section-title mb-4">Products on quote list</h3>
        <ul class="divide-y divide-slate-100">
          <?php foreach ($products as $p): ?>
            <?php
            $slug = (string) ($p['slug'] ?? '');
            $live = $slug !== '' ? Product::findPublishedBySlug($slug) : null;
            $priceLabel = isset($p['price']) && $p['price'] !== null && $p['price'] !== ''
                ? format_product_price(['price' => $p['price'], 'price_unit' => $p['price_unit'] ?? null])
                : null;
            ?>
            <li class="py-4 first:pt-0 last:pb-0 flex flex-wrap gap-4 justify-between items-start">
              <div>
                <p class="font-semibold text-brand-navy">
                  <?= (int) ($p['quantity'] ?? 1) ?>× <?= e($p['title'] ?? 'Product') ?>
                </p>
                <?php if (!empty($p['sku'])): ?>
                  <p class="text-xs font-mono text-slate-500 mt-0.5">SKU: <?= e($p['sku']) ?></p>
                <?php endif; ?>
                <?php if (!empty($p['category'])): ?>
                  <p class="text-xs text-slate-500 mt-1"><?= e($p['category']) ?></p>
                <?php endif; ?>
                <?php if ($priceLabel): ?>
                  <p class="text-sm font-medium text-slate-700 mt-1"><?= e($priceLabel) ?></p>
                <?php endif; ?>
              </div>
              <?php if ($live): ?>
                <a href="<?= url('products/' . $slug) ?>" target="_blank" rel="noopener" class="text-sm font-semibold text-brand-navy hover:text-brand-gold shrink-0">View product →</a>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
    <?php endif; ?>

    <?php if (trim((string) ($item['message'] ?? '')) !== ''): ?>
      <section class="admin-panel admin-panel__body">
        <h3 class="admin-section-title mb-4">Project details</h3>
        <div class="rounded-xl bg-slate-50 border border-slate-100 p-6 text-slate-800 leading-relaxed whitespace-pre-wrap">
          <?= e($item['message']) ?>
        </div>
      </section>
    <?php endif; ?>
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
      </dl>
      <a href="mailto:<?= e($item['email']) ?>?subject=<?= rawurlencode('Re: Your BlueAxis quote request #' . $item['id']) ?>" class="btn-primary w-full justify-center mt-2">
        Reply by email
      </a>
    </section>

    <form method="post" action="<?= url('admin/quotes/' . $item['id'] . '/status') ?>" class="admin-panel admin-panel__body space-y-4">
      <?= \App\Core\Csrf::field() ?>
      <h3 class="admin-section-title">Internal follow-up</h3>
      <?php \App\Core\View::partial('admin/field', [
          'label' => 'Status',
          'name' => 'status',
          'type' => 'select',
          'value' => $status,
          'options' => [
              'new' => 'New',
              'in_review' => 'In review',
              'contacted' => 'Contacted',
              'closed' => 'Closed',
          ],
      ]); ?>
      <?php \App\Core\View::partial('admin/field', [
          'label' => 'Internal notes',
          'name' => 'admin_notes',
          'type' => 'textarea',
          'value' => $item['admin_notes'] ?? '',
          'placeholder' => 'Notes for your team (not visible to the visitor)',
          'maxlength' => 5000,
      ]); ?>
      <button type="submit" class="btn-primary w-full justify-center" data-loading-text="Saving…">Save status & notes</button>
    </form>
  </aside>
</div>
