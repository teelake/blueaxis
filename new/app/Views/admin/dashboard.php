<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
  <?php
  $cards = [
      ['label' => 'Quote requests', 'value' => $stats['quotes'], 'sub' => $stats['quotes_new'] . ' new', 'url' => 'admin/quotes'],
      ['label' => 'Contact messages', 'value' => $stats['contacts'], 'sub' => $stats['contacts_unread'] . ' unread', 'url' => 'admin/contacts'],
      ['label' => 'Published articles', 'value' => $stats['posts'], 'sub' => 'View blog', 'url' => 'admin/blog'],
      ['label' => 'Site visits', 'value' => number_format($stats['visits']), 'sub' => 'Analytics placeholder', 'url' => '#'],
  ];
  foreach ($cards as $c): ?>
    <a href="<?= $c['url'] !== '#' ? url($c['url']) : '#' ?>" class="admin-stat-card">
      <p class="admin-stat-card__label"><?= e($c['label']) ?></p>
      <p class="admin-stat-card__value"><?= e((string) $c['value']) ?></p>
      <p class="admin-stat-card__meta"><?= e($c['sub']) ?></p>
    </a>
  <?php endforeach; ?>
</div>

<div class="admin-panel admin-panel__body">
  <h2 class="admin-section-title">Traffic overview</h2>
  <p class="admin-section-desc mb-6">Connect analytics later — sample chart below.</p>
  <canvas id="trafficChart" height="80"></canvas>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('trafficChart');
  if (!ctx || typeof Chart === 'undefined') return;
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun'],
      datasets: [{ label: 'Visits', data: [820, 932, 901, 1240, 1100, 1280], borderColor: '#102A56', backgroundColor: 'rgba(16,42,86,0.06)', fill: true, tension: 0.35 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
  });
});
</script>
