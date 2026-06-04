<?php if (!empty($pendingComments)): ?>
  <div class="admin-alert admin-alert--success mb-6" style="background:#fffbeb;border-color:#fde68a;color:#92400e">
    <?= (int) $pendingComments ?> comment(s) waiting for your approval — open an article to review.
  </div>
<?php endif; ?>

<div class="flex flex-wrap gap-4 justify-between items-center mb-6">
  <form method="get" class="flex flex-wrap gap-2 items-center">
    <label class="text-sm font-medium text-slate-600">Show</label>
    <select name="status" class="admin-select max-w-[180px]">
      <option value="">All articles</option>
      <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
      <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Drafts</option>
    </select>
    <button type="submit" class="btn-secondary">Apply</button>
  </form>
  <a href="<?= url('admin/blog/create') ?>" class="btn-primary">+ New article</a>
</div>

<?php if (!empty($posts)): ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Status</th>
          <th>Featured</th>
          <th>Updated</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $p): ?>
          <tr>
            <td class="font-medium text-slate-900"><?= e($p['title']) ?></td>
            <td>
              <span class="admin-badge <?= $p['status'] === 'published' ? 'admin-badge--published' : 'admin-badge--draft' ?>"><?= e($p['status']) ?></span>
            </td>
            <td><?= ($p['is_featured'] ?? 0) ? 'Yes' : '—' ?></td>
            <td class="text-slate-500"><?= e(date('M j, Y', strtotime($p['updated_at']))) ?></td>
            <td>
              <?php \App\Core\View::partial('admin/row-actions', [
                  'editUrl' => url('admin/blog/' . $p['id'] . '/edit'),
                  'viewUrl' => $p['status'] === 'published' ? url('blog/' . $p['slug']) : null,
                  'toggleUrl' => url('admin/blog/' . $p['id'] . '/toggle-status'),
                  'deleteUrl' => url('admin/blog/' . $p['id'] . '/delete'),
                  'isActive' => $p['status'] === 'published',
                  'entityLabel' => 'article',
                  'toggleOffLabel' => 'Unpublish',
                  'toggleOnLabel' => 'Publish',
                  'toggleOffConfirm' => 'Move this article to draft? It will be hidden from the blog.',
                  'toggleOnConfirm' => 'Publish this article on the blog?',
              ]); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <?php
  $hasFilter = ($status ?? '') !== '';
  \App\Core\View::partial('admin/empty-state', [
      'icon' => $hasFilter ? 'search' : 'blog',
      'title' => $hasFilter ? 'No articles in this view' : 'No blog articles yet',
      'description' => $hasFilter
          ? 'Try showing all articles or choose a different status.'
          : 'Publish logistics insights and company news for your B2B audience.',
      'actionUrl' => $hasFilter ? url('admin/blog') : url('admin/blog/create'),
      'actionLabel' => $hasFilter ? 'Show all articles' : 'Write first article',
  ]);
  ?>
<?php endif; ?>
