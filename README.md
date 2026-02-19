# Installation

Dézipper le projet, puis modifier le nom du projet dans le fichier ".env".
Par défaut, le site sera disponible à l'adresse `http://localhost:12345`. Le port `12345` est modifiable dans le fichier `docker-compose.yml`

```bash
$ make up # démarre le conteneur docker
$ make svgstore # fabrique le fichier svgstore.svg
$ make css # compile les fichiers css
```

Voir le fichier `Makefile` pour le détail des commandes disponibles.

## PHP

La class `vp` contient quelques fonctions statiques de raccourcis "maisons":
- `vp::part()` charge un partial dans le template. Le second argument permet d'injecter des variables.
- `vp::block()` charge un block dans le template. Le second argument permet d'injecter des variables.
- `vp::scripts()`: utilisé dans le footer pour charger automatiquement les fichiers JS
- `vp::styles()`: utilisé dans le header pour charger automatiquement les fichiers CSS

## CSS

Ici, pas de gulp, sass, postcss, webpack et autres joyeusetés. Le css est compilé via `lightningcss` (inclus dans l'image docker). Tous les fichiers source sont du **css natifs**, la compilation ne fait que vérifier la compatibilité avec les navigateurs spécifiés (voir `/css.sh`).
- Dans le thème, tous les fichiers `.css` inclus dans le dossier `/css` sont compilés dans le fichiers `/vp-styles.min.css`. Le fichier `main.css` est auto-généré.
- Pour chacun des blocs wordpress dans le dossier `/block`, le fichier `style.src.css` est compilé dans son équivalent `style.css`.

La commande `make css` lance le build. La commande `make css watch` lance le build et la surveillance de tous les dossiers/fichiers concernés (`ctrl-c` pour interrompre).

## JS

**Tous les fichiers sont des modules es6** et sont placés dans le dossier `/js`.
- Tous les fichiers sont déclarés et inclus automatiquement via php, et un bloc `importmap` est généré automatiquement et inclus dans chaque page, avec un hash pour contourner le cache navigateur.
- Donc toute modification de `js` est nécessairement prise en compte lors d'un rechargement de page, pas besoin de vider le cache.

## SVG

Une librairie dédiée existe pour la gestion des svg: `svgstore`.
- Tous les fichiers svg nécessaires sont placés dans le dossier `/svgstore`, si possible nettoyés et allégés avec https://jakearchibald.github.io/svgomg/
- La commande `make svgstore` permet de générer 1 seul fichier `/svgstore.svg`, contenant tous les svg sous la forme de `symbol`, et ayant pour `id` le nom du fichier source. Un raccourci make existe: `make svgstore`.
- Le fichier compilé est appelé sur toutes les pages dans le footer, et masqué dans une `div`.
- Il est possible d'inclure une ou plusieurs instances de chacun des svg présents dans le store, en utilisant la syntaxe: `<svg><use xlink:href="#[ID]"></use></svg>`, et en remplaçant `[ID]` par l'id du svg à afficher.
- Une fonction statique PHP dédiée existe pour inclure ce snippet: `<?= svgstore::icon('[ID]') ?>`
