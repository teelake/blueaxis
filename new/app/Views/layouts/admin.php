<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title ?? 'Admin') ?> | BlueAxis CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="bg-slate-100 min-h-screen">
  <div class="flex min-h-screen">
    <?php require APP_PATH . '/Views/partials/admin-sidebar.php'; ?>
    <div class="flex-1 flex flex-col min-w-0">
      <?php require APP_PATH . '/Views/partials/admin-topbar.php'; ?>
      <main class="flex-1 p-6 lg:p-8 overflow-auto">
        <?php if ($success = flash('success')): ?>
          <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = flash('error')): ?>
          <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm"><?= e($error) ?></div>
        <?php endif; ?>
        <?= $content ?>
      </main>
    </div>
  </div>
</body>
</html>
