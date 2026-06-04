<div class="flex flex-wrap justify-between gap-4 mb-6">
  <form method="get" class="flex flex-wrap gap-2 items-center">
    <input name="q" value="<?= e($search) ?>" class="admin-input max-w-xs" placeholder="Search name, email…" />
    <select name="status" class="admin-select max-w-[180px]">
      <option value="">All statuses</option>
      <?php foreach (['new', 'in_review', 'contacted', 'closed'] as $s): ?>
        <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucfirst(str_replace('_', ' ', $s))) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
  </form>
  <?php if (($total ?? 0) > 0): ?>
    <a href="<?= url('admin/quotes/export') ?>" class="btn-secondary">Export CSV</a>
  <?php endif; ?>
</div>

<?php if (empty($items)): ?>
  <?php
  $hasFilters = ($search ?? '') !== '' || ($status ?? '') !== '';
  \App\Core\View::partial('admin/empty-state', [
      'icon' => $hasFilters ? 'search' : 'quotes',
      'title' => $hasFilters ? 'No quote requests match your filters' : 'No quote requests yet',
      'description' => $hasFilters
          ? 'Adjust your search or status filter to see more results.'
          : 'B2B quote submissions from your website will show up here for follow-up.',
      'actionUrl' => $hasFilters ? url('admin/quotes') : url('quote'),
      'actionLabel' => $hasFilters ? 'Clear filters' : 'View quote form',
  ]);
  ?>
<?php else: ?>
  <p class="text-sm text-slate-600 mb-4"><?= (int) $total ?> request<?= $total === 1 ? '' : 's' ?></p>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Service</th>
          <th>Products</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td class="font-medium">
              <a href="<?= url('admin/quotes/' . $item['id']) ?>" class="text-brand-navy hover:text-brand-gold"><?= e($item['name']) ?></a>
            </td>
            <td><?= e($item['service_needed']) ?></td>
            <td class="text-slate-600 text-xs max-w-[200px]">
              <?php
              $pCount = count(\App\Services\QuoteCartService::parseStored($item['products_json'] ?? null));
              echo $pCount > 0 ? $pCount . ' item' . ($pCount === 1 ? '' : 's') : '—';
              ?>
            </td>
            <td>
              <span class="admin-badge admin-badge--pending"><?= e(str_replace('_', ' ', $item['status'])) ?></span>
            </td>
            <td class="text-slate-500"><?= e(date('M j, Y g:ia', strtotime($item['created_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
