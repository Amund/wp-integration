<?php

require_once '../vendor/html.php';
require_once '../vendor/svgstore.php';
require_once '../vendor/vp.php';

// routing
if (isset($_GET['template'])) {
    $page = $_GET['template'] . '.php';
    if (file_exists($page)) {
        include $page;
        exit;
    }
    die('page not found');
}

// home vupar
$files = array_filter(glob('*.php'), fn($file) => $file !== 'index.php');
$files = array_values($files);
$files = array_map(fn($file) => basename($file, '.php'), $files);

foreach ($files as $i => $file) {
    $files[$i] = [
        'tag' => 'li',
        'content' => [
            'tag' => 'a',
            'href' => $file,
            'content' => ucfirst($file)
        ],
    ];
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['PROJECT'] ?? '' ?> - Vupar</title>
</head>

<body>
    <h1><?= $_ENV['PROJECT'] ?? '' ?></h1>
    <?php
    echo html::render([
        ['tag' => 'p', 'content' => 'Modèles de page :'],
        ['tag' => 'ul', 'content' => $files],
    ]);
    ?>
</body>

</html>