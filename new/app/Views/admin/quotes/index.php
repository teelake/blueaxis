<?php
use App\Services\QuoteCartService;

$perPage = (int) config('app.per_page_admin');
$totalPages = max(1, (int) ceil(($total ?? 0) / $perPage));
$page = max(1, (int) ($_GET['page'] ?? 1));
ob_start();
?>
<form method="get" class="admin-toolbar__form admin-toolbar__form--wide">
  <input type="search" name="q" value="<?= e($search) ?>" class="admin-input" placeholder="Search name, email…" />
  <select name="status" class="admin-select">
    <option value="">All statuses</option>
    <?php foreach (['new', 'in_review', 'contacted', 'closed'] as $s): ?>
      <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucfirst(str_replace('_', ' ', $s))) ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn-secondary">Filter</button>
</form>
<?php
$filterHtml = ob_get_clean();
$actions = [];
if (($total ?? 0) > 0) {
    $actions[] = ['label' => 'Export CSV', 'url' => url('admin/quotes/export'), 'class' => 'btn-secondary'];
}
\App\Core\View::partial('admin/toolbar', [
    'meta' => ($total ?? 0) > 0 ? (int) $total . ' request' . ((int) $total === 1 ? '' : 's') : null,
    'filterHtml' => $filterHtml,
    'actions' => $actions,
]);
?>

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
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Requester</th>
          <th>Service</th>
          <th>Products</th>
          <th>Status</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <?php
          $pCount = count(QuoteCartService::parseStored($item['products_json'] ?? null));
          $statusKey = (string) ($item['status'] ?? 'new');
          ?>
          <tr>
            <td class="font-medium">
              <a href="<?= url('admin/quotes/' . $item['id']) ?>" class="text-brand-navy hover:text-brand-gold block">
                <?= e($item['name']) ?>
              </a>
              <p class="text-xs text-slate-500 mt-0.5"><?= e($item['company'] ?? '') ?> · <?= e($item['email']) ?></p>
            </td>
            <td class="text-sm"><?= e($item['service_needed']) ?></td>
            <td class="text-slate-600 text-sm">
              <?= $pCount > 0 ? $pCount . ' product' . ($pCount === 1 ? '' : 's') : '—' ?>
            </td>
            <td>
              <span class="admin-badge admin-badge--pending"><?= e(str_replace('_', ' ', $statusKey)) ?></span>
            </td>
            <td class="text-slate-500 whitespace-nowrap"><?= e(date('M j, Y g:ia', strtotime($item['created_at']))) ?></td>
            <td class="text-right">
              <?php \App\Core\View::partial('admin/row-actions', [
                  'detailUrl' => url('admin/quotes/' . $item['id']),
                  'entityLabel' => 'quote request',
              ]); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($totalPages > 1): ?>
    <nav class="flex flex-wrap gap-2 justify-center mt-8" aria-label="Pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php
        $qs = http_build_query(array_filter(['q' => $search ?? '', 'status' => $status ?? '', 'page' => $i > 1 ? $i : null]));
        $href = url('admin/quotes') . ($qs !== '' ? '?' . $qs : '');
        ?>
        <a href="<?= e($href) ?>" class="px-3 py-1.5 text-sm rounded-lg border <?= $i === $page ? 'bg-brand-navy text-white border-brand-navy' : 'border-slate-200 text-slate-600 hover:bg-slate-50' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </nav>
  <?php endif; ?>
<?php endif; ?>
