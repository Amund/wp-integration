<?php

// variables
// html::print($args);
$background = (bool) ($args['background'] ?? false);
$text = (string) ($args['text'] ?? '');

// local variables
$class = ['vp-block-text'];
if ($background) $class[] = 'has-background';

// render
?>
<section class="<?= implode(' ', $class) ?>">
    <div class="container wysiwyg">
        <?= $text ?>
    </div>
</section>