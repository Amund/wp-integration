<?php

// html::print($args);
$type = (string) ($args['type'] ?? 'image');
$caption = (string) ($args['caption'] ?? '');
$ratio = (string) ($args['ratio'] ?? '');
$class = (array) ($args['class'] ?? [] ?: []);

// local variables
$class = ['vp-media', 'is-' . $type, ...$class];
if (!empty($ratio)) $class = [...$class, 'has-ratio', 'has-ratio-' . $ratio];
switch ($type) {
    case 'image':
        $image = (int) ($args['image'] ?? 0);
        $media = vp::part('image', ['id' => $image, 'title' => $caption, 'class' => ['zoom']]);
        break;
    case 'oembed':
        $oembed = (string) ($args['oembed'] ?? '');
        $media = $oembed;
        break;
    case 'vimeo':
        $vimeo = (array) ($args['vimeo'] ?? [] ?: []);
        $media = vp::part('vimeo', [
            'ratio' => $ratio,
            'background' => true,
            ...$vimeo,
        ]);
        break;
    default:
        $media = '';
}

// render
echo html::render([
    'tag' => 'figure',
    'class' => implode(' ', $class),
    'content' => [
        $media,
        ['tag' => 'figcaption', 'content' => $caption],
    ]
]);
