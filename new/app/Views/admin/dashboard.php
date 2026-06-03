<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <?php
  $cards = [
      ['label' => 'Quote requests', 'value' => $stats['quotes'], 'sub' => $stats['quotes_new'] . ' new', 'url' => 'admin/quotes'],
      ['label' => 'Contact inquiries', 'value' => $stats['contacts'], 'sub' => $stats['contacts_unread'] . ' unread', 'url' => 'admin/contacts'],
      ['label' => 'Published posts', 'value' => $stats['posts'], 'sub' => 'Blog', 'url' => 'admin/blog'],
      ['label' => 'Site visits (placeholder)', 'value' => number_format($stats['visits']), 'sub' => number_format($stats['pageviews']) . ' pageviews', 'url' => '#'],
  ];
  foreach ($cards as $c): ?>
    <a href="<?= $c['url'] !== '#' ? url($c['url']) : '#' ?>" class="card hover:shadow-elevated transition block">
      <p class="text-sm text-slate-500"><?= e($c['label']) ?></p>
      <p class="text-3xl font-bold text-brand-navy mt-2"><?= e((string) $c['value']) ?></p>
      <p class="text-xs text-brand-gold mt-1"><?= e($c['sub']) ?></p>
    </a>
  <?php endforeach; ?>
</div>

<div class="card">
  <h2 class="font-semibold text-brand-navy mb-4">Traffic overview (placeholder)</h2>
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
      datasets: [{ label: 'Visits', data: [820, 932, 901, 1240, 1100, 1280], borderColor: '#102A56', tension: 0.3 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
  });
});
</script>
