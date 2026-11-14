<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f6f1e7">
<title><?= htmlspecialchars($title ?? 'Saha+', ENT_QUOTES, 'UTF-8') ?> | Saha+</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<?php foreach (['tokens.css', 'base.css', 'background.css', 'shell.css', 'sidebar.css', 'components.css', 'animations.css'] as $kitStylesheet): ?>
<link rel="stylesheet" href="<?= htmlspecialchars(asset('shared/assets/css/' . $kitStylesheet), ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
<?php foreach (($stylesheets ?? []) as $stylesheet): ?>
<link rel="stylesheet" href="<?= htmlspecialchars(asset($stylesheet), ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
