<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'GL-Acha', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/css/style.css">
</head>
<body>
    <main>
        <?= $content ?? '' ?>
    </main>
    <script src="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/js/app.js"></script>
</body>
</html>