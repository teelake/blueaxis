<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title ?? 'Admin') ?> | BlueAxis CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/rich-editor.css') ?>" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="admin-shell" data-media-origin="<?= e(app_url_origin()) ?>" data-media-public="<?= e(app_public_web_path()) ?>">
  <div class="flex min-h-screen">
    <?php require APP_PATH . '/Views/partials/admin-sidebar.php'; ?>
    <div class="admin-main">
      <?php require APP_PATH . '/Views/partials/admin-topbar.php'; ?>
      <main class="flex-1 px-4 sm:px-8 py-6 lg:py-8 overflow-auto max-w-6xl w-full mx-auto">
        <?php if (!empty($pageDescription)): ?>
          <div class="admin-page-head">
            <h1><?= e($title ?? 'Admin') ?></h1>
            <p><?= e($pageDescription) ?></p>
          </div>
        <?php endif; ?>
        <?php if ($success = flash('success')): ?>
          <div class="admin-alert admin-alert--success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = flash('error')): ?>
          <div class="admin-alert admin-alert--error"><?= e($error) ?></div>
        <?php endif; ?>
        <?= $content ?>
      </main>
    </div>
  </div>
  <script src="<?= asset('js/form-loading.js') ?>"></script>
  <script src="<?= asset('js/admin-ui.js') ?>"></script>
  <script src="<?= asset('js/admin-image-upload.js') ?>"></script>
  <script src="<?= asset('js/rich-editor.js') ?>"></script>
</body>
</html>
