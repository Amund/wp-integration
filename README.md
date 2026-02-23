# Installation

Dézipper le projet, puis modifier le nom du projet dans le fichier ".env".

Par défaut, le site sera disponible à l'adresse `http://localhost:12345`. Le port `12345` est modifiable dans le fichier `docker-compose.yml`.

Le dossier public est le dossier `/web`.

Des commandes `make` sont disponibles pour faciliter le développement, en exécutant des commandes dans le conteneur docker, sans avoir à s'y connecter.

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
- Dans le thème, tous les fichiers `.css` inclus dans le dossier `/css` sont compilés dans le fichier `/vp-styles.min.css`. Le fichier `main.css` est auto-généré.
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
- Une fonction statique PHP dédiée existe pour inclure ce snippet: `<?= svgstore::icon('[ID]') ?>` (et également une fonction JS `icon('[ID]')`)

## IMG

Toutes les images sont à placer dans le dossier `img`, mais sont à différencier en 2 catégories:
- les images **statiques**, non-contribuées depuis l'admin de wordpress, qui sont uniquement présentes pour "enjoliver" le thème. Elles sont à utiliser avec une balise HTML `<img src=""...>` simple.
- les images **dynamiques**, modififiables depuis wordpress, seront ajoutées par des champs ACF image qui ne stockeront que leur `post_id`. Toutes ces images passeront par un fichier `part` commun, et seront donc composées à terme d'un ensemble `<figure><img></figure>`. Un exemple est disponible dans le bloc `media`. Le principe peut paraître alambiqué, mais il permet de gérer tous les types de médias une fois dans wordpress (image, vidéo, oembed, lottie, etc.) et de les afficher de manière cohérente.

Il y a une exception aux images dynamiques: les "Image mise en avant" de wordpress, qui sont traitées comme des images statiques, avec uniquement une balise `<img>`, souvent dans les cards.