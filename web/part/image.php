<?php

// html::print($args);
// variables
$id = (int) ($args['id'] ?? 0);
$decoding = (string) ($args['decoding'] ?? 'async'); // sync, async, auto
$loading = (string) ($args['loading'] ?? 'lazy'); // eager, lazy
$class = (array) ($args['class'] ?? [] ?: []);
$title = (string) ($args['title'] ?? '');

// local variables
$class = ['vp-image', 'wpsmartcrop-image', ...$class];
$src = "/img/$id.jpg" ?: '';
if (empty($src)) return;
$metadatas = getimagesize(ROOT_PATH . $src);
$width = $metadatas[0] ?? 0;
$height = $metadatas[1] ?? 0;
$alt = 'image';

// render
echo html::render([
    'tag' => 'img',
    'src' => $src,
    'width' => $width,
    'height' => $height,
    'alt' => $alt,
    'decoding' => $decoding,
    'loading' => $loading,
    'class' => implode(' ', $class),
    'draggable' => 'false',
    'data-original' => $src,
    'style' => '--original-ratio:' . $width . ' / ' . $height . ';',
]);
