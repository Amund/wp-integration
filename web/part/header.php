<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
    <base href="/">
    <title><?= $_GET['template'] ?? '' ?> - <?= $_ENV['PROJECT'] ?? '' ?> - Vupar</title>
    <link rel="icon" href="/img/favicon.jpg" sizes="32x32">
    <?php vp::styles() ?>
</head>

<body class="<?= $_GET['template'] ?? '' ?>">

    <header class="vp-header">
        <div class="container">
            header
        </div>
    </header>

    <main>