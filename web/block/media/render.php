<?php

// variables
// html::print($args);

// local variables
$class = ['vp-block-media'];
$media = vp::part('media', [...$args]);

// render
?>
<section class="<?= implode(' ', $class) ?>">
    <div class="container wysiwyg">
        <?= $media ?>
    </div>
</section>