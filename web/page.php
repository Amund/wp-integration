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

echo vp::part('footer');
