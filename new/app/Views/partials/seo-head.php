<?php
$seo = $seo ?? ['title' => config('app.name'), 'description' => '', 'canonical' => null, 'og_image' => asset('images/BLUEAXIS_logo.png')];
$title = e($seo['title'] ?? config('app.name'));
$desc = e($seo['description'] ?? '');
$canonical = $seo['canonical'] ?? url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$og = $seo['og_image'] ?? asset('images/BLUEAXIS_logo.png');
?>
<title><?= $title ?></title>
<meta name="description" content="<?= $desc ?>" />
<link rel="canonical" href="<?= e($canonical) ?>" />
<meta property="og:title" content="<?= $title ?>" />
<meta property="og:description" content="<?= $desc ?>" />
<meta property="og:image" content="<?= e($og) ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?= e($canonical) ?>" />
<meta name="twitter:card" content="summary_large_image" />
