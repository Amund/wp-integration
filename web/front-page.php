<?php

echo vp::part('header');

echo vp::block('text', [
    'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis malesuada volutpat neque sit amet posuere. Aenean luctus sagittis mollis. Nunc tristique semper neque et lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque eros massa, mollis vel metus id, placerat mollis eros. Nunc facilisis mi in massa dapibus, maximus tincidunt odio vestibulum. Etiam dictum urna a felis euismod, eu efficitur libero egestas. Etiam vehicula, lacus id tincidunt luctus, risus eros consectetur urna, quis interdum sem nunc in ex.</p>',
]);

echo vp::part('footer');
