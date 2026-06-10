<div class="mb-6">
  <a href="<?= url('admin/products') ?>" class="text-sm font-medium text-slate-600 hover:text-brand-navy">← Back to products</a>
</div>

<?php if (!empty($importSummary)): ?>
  <div class="admin-alert admin-alert--success mb-6">
    <p class="font-medium">Last import</p>
    <p class="text-sm mt-1">
      <?= (int) ($importSummary['created'] ?? 0) ?> created,
      <?= (int) ($importSummary['updated'] ?? 0) ?> updated.
    </p>
  </div>
<?php endif; ?>

<div class="grid lg:grid-cols-2 gap-8">
  <div class="admin-panel">
    <div class="admin-panel__body space-y-6">
      <div>
        <h2 class="admin-section-title">Upload CSV</h2>
        <p class="admin-section-desc">Rows with a matching <code class="text-xs bg-slate-100 px-1 rounded">title</code> update existing products. SKU and URL slug are generated automatically. New rows are published by default. Description, images, SEO, and visibility are not changed by import.</p>
      </div>
      <form method="post" action="<?= url('admin/products/bulk-import') ?>" enctype="multipart/form-data">
        <?= \App\Core\Csrf::field() ?>
        <div class="admin-inline-form">
          <div class="admin-field flex-1 min-w-0">
            <label class="admin-label" for="csv">CSV file</label>
            <input type="file" id="csv" name="csv" accept=".csv,text/csv" class="admin-input" required />
            <p class="admin-hint mt-2">UTF-8 encoding recommended.</p>
          </div>
          <button type="submit" class="btn-primary self-end" data-loading-text="Importing…">Import products</button>
        </div>
      </form>
    </div>
  </div>

  <div class="admin-panel">
    <div class="admin-panel__body space-y-6">
      <div class="admin-toolbar lg:flex-col lg:items-stretch lg:gap-4 mb-0">
        <div>
          <h2 class="admin-section-title">CSV template</h2>
          <p class="admin-section-desc">Download a starter file with the correct headers and two example rows.</p>
        </div>
        <div class="admin-toolbar__actions lg:justify-start">
          <a href="<?= url('admin/products/bulk-import/template') ?>" class="btn-secondary">Download template (.csv)</a>
        </div>
      </div>

      <div class="rounded-xl border border-slate-200 overflow-hidden">
        <p class="px-4 py-3 text-sm font-semibold text-slate-700 bg-slate-50 border-b border-slate-200">Columns</p>
        <ul class="divide-y divide-slate-100 text-sm">
          <?php foreach ($columns as $column): ?>
            <li class="px-4 py-2.5 font-mono text-slate-700"><?= e($column) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600 space-y-2">
        <p class="font-semibold text-slate-700">Import rules</p>
        <ul class="list-disc list-inside space-y-1">
          <li><strong>title</strong> required — also used to match updates</li>
          <li>SKU and slug are auto-generated (not in the CSV)</li>
          <li><strong>price</strong> optional — leave empty to hide on site</li>
          <li><strong>size</strong> and <strong>pack_format</strong> are separate fields</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($importErrors)): ?>
  <div class="admin-panel mt-8">
    <div class="admin-panel__body">
      <h2 class="admin-section-title mb-4">Rows with errors</h2>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Row</th>
              <th>Title</th>
              <th>Error</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($importErrors as $err): ?>
              <tr class="border-t">
                <td class="p-4 text-slate-500"><?= (int) ($err['row'] ?? 0) ?></td>
                <td class="p-4 text-sm"><?= e((string) ($err['title'] ?? '—')) ?></td>
                <td class="p-4 text-red-600 text-sm"><?= e((string) ($err['message'] ?? '')) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>
