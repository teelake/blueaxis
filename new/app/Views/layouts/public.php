<!DOCTYPE html>
<html lang="en-CA">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php require APP_PATH . '/Views/partials/seo-head.php'; ?>
  <meta name="theme-color" content="#102A56" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
  <link rel="icon" href="<?= asset('images/BLUEAXIS_logo.png') ?>" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <?php if (!empty($schema)): ?>
  <script type="application/ld+json"><?= $schema ?></script>
  <?php endif; ?>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body>
  <?php \App\Core\View::partial('header'); ?>
  <main><?= $content ?></main>
  <?php \App\Core\View::partial('footer'); ?>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 700, once: true, offset: 40 });</script>
</body>
</html>
