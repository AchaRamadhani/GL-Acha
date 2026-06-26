<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'GL-Acha', ENT_QUOTES, 'UTF-8') ?></title>
    <?php $styleVersion = @filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?: time(); ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/css/style.css?v=<?= $styleVersion ?>">
</head>
<body>
    <?= $content ?? '' ?>
    <script src="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/js/app.js"></script>
</body>
</html>
