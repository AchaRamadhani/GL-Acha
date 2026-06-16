<?php ob_start(); ?>
<section class="page">
    <h1><?= htmlspecialchars($title ?? 'GL-Acha', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Project MVC siap dikembangkan.</p>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
