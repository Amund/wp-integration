<?php

echo vp::part('header');

echo vp::block('text', [
    'text' => '<p>Exemple de bloc texte.</p>',
]);
echo vp::block('text', [
    'text' => '<p>Un second bloc texte.</p>',
]);
echo vp::block('text', [
    'text' => '<p>Un bloc texte avec un fond coloré.</p>',
    'background' => true,
]);
echo vp::block('text', [
    'text' => '<p>Un second bloc texte avec un fond coloré.</p>',
    'background' => true,
]);
echo vp::block('media', [
    'type' => 'image',
    'caption' => 'Image de test',
    'image' => 42,
    'ratio' => '16-9',
]);

echo vp::part('footer');
