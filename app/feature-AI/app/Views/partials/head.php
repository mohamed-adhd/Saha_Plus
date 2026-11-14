<?php require_once __DIR__ . '/../../../ui-kit/helpers.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="Saha+ administration"><title><?= htmlspecialchars($title ?? 'Saha+ Admin', ENT_QUOTES, 'UTF-8') ?> | Saha+</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<?php foreach (['tokens.css', 'base.css', 'background.css', 'shell.css', 'sidebar.css', 'components.css', 'animations.css'] as $kitStylesheet): ?>
<link rel="stylesheet" href="<?= htmlspecialchars(asset('ui-kit/assets/css/' . $kitStylesheet), ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
<?php foreach (['assistant.css', 'admin.css', 'shell.css'] as $appStylesheet): ?>
<link rel="stylesheet" href="<?= htmlspecialchars(asset('app/Views/assets/css/' . $appStylesheet), ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
</head>
<body class="sp-bg">
<!-- ======== Preloader ======== -->
<div id="preloader" role="status"><div class="spinner-border text-success"><span class="visually-hidden">Loading...</span></div></div>
<noscript><style>#preloader,#spinner{display:none !important}</style></noscript>
<!-- ======== Preloader ======== -->
