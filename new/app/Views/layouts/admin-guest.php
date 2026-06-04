<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title ?? 'Login') ?> | BlueAxis CMS</title>
  <?php require APP_PATH . '/Views/partials/favicon-head.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
</head>
<body class="admin-login-wrap">
  <?= $content ?>
</body>
</html>
