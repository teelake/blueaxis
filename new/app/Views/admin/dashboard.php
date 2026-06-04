<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
  <?php
  $cards = [
      [
          'label' => 'Quote requests',
          'value' => $stats['quotes'],
          'sub' => $stats['quotes_new'] . ' need follow-up',
          'url' => 'admin/quotes',
          'highlight' => $stats['quotes_new'] > 0,
      ],
      [
          'label' => 'Contact messages',
          'value' => $stats['contacts'],
          'sub' => $stats['contacts_unread'] . ' unread',
          'url' => 'admin/contacts',
          'highlight' => $stats['contacts_unread'] > 0,
      ],
      [
          'label' => 'New leads',
          'value' => $stats['leads_30d'],
          'sub' => $stats['leads_7d'] . ' in the last 7 days',
          'url' => 'admin/quotes',
          'highlight' => false,
      ],
      [
          'label' => 'Newsletter subscribers',
          'value' => $stats['newsletter'],
          'sub' => ($stats['pending_comments'] > 0)
              ? $stats['pending_comments'] . ' blog comments to review'
              : 'Active email list',
          'url' => 'admin/blog',
          'highlight' => $stats['pending_comments'] > 0,
      ],
  ];
  foreach ($cards as $c): ?>
    <a href="<?= url($c['url']) ?>" class="admin-stat-card <?= !empty($c['highlight']) ? 'ring-2 ring-brand-gold/40' : '' ?>">
      <p class="admin-stat-card__label"><?= e($c['label']) ?></p>
      <p class="admin-stat-card__value"><?= e((string) $c['value']) ?></p>
      <p class="admin-stat-card__meta"><?= e($c['sub']) ?></p>
    </a>
  <?php endforeach; ?>
</div>

<?php if (($stats['needs_attention'] ?? 0) > 0): ?>
  <div class="admin-alert mb-6" style="background:#eff6ff;border-color:#bfdbfe;color:#1e40af">
    <strong><?= (int) $stats['needs_attention'] ?> item(s) need your attention</strong>
    — new quotes, unread contacts, or blog comments awaiting approval.
  </div>
<?php endif; ?>

<?php
$chartEmpty = array_sum($leadChart['quotes'] ?? []) + array_sum($leadChart['contacts'] ?? []) === 0;
?>
<div class="admin-panel admin-panel__body">
  <h2 class="admin-section-title">Lead activity</h2>
  <p class="admin-section-desc mb-6">Quote requests and contact form submissions over the last 6 months.</p>
  <?php if ($chartEmpty): ?>
    <?php \App\Core\View::partial('admin/empty-state', [
        'icon' => 'chart',
        'title' => 'No lead activity yet',
        'description' => 'When visitors request quotes or send contact messages, trends will appear in this chart.',
        'actionUrl' => url('admin/quotes'),
        'actionLabel' => 'View quote requests',
    ]); ?>
  <?php else: ?>
    <canvas id="leadChart" height="100"></canvas>
    <div class="flex flex-wrap gap-6 mt-6 text-xs font-medium text-slate-500">
      <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-navy"></span> Quote requests</span>
      <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-gold"></span> Contact messages</span>
    </div>
  <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('leadChart');
  if (!ctx || typeof Chart === 'undefined') return;
  if (<?= $chartEmpty ? 'true' : 'false' ?>) return;
  const labels = <?= json_encode($leadChart['labels'], JSON_THROW_ON_ERROR) ?>;
  const quotes = <?= json_encode($leadChart['quotes'], JSON_THROW_ON_ERROR) ?>;
  const contacts = <?= json_encode($leadChart['contacts'], JSON_THROW_ON_ERROR) ?>;
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Quote requests',
          data: quotes,
          backgroundColor: 'rgba(16, 42, 86, 0.85)',
          borderRadius: 4,
        },
        {
          label: 'Contact messages',
          data: contacts,
          backgroundColor: 'rgba(197, 158, 95, 0.9)',
          borderRadius: 4,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: { mode: 'index', intersect: false },
      },
      scales: {
        x: { stacked: true, grid: { display: false } },
        y: {
          stacked: true,
          beginAtZero: true,
          ticks: { stepSize: 1, precision: 0 },
          grid: { color: '#f1f5f9' },
        },
      },
    },
  });
});
</script>
