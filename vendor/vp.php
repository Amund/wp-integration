<?php

class vp
{
    /**
     * Process worpress template part
     *
     * @param string $part Part path, without part folder and without extension
     * @param array $args Arguments added to template part
     * @return string Template part after compilation
     */
    static function part(string $part, array $args = []): string
    {
        if (file_exists("part/$part.php")) {
            extract($args, EXTR_SKIP);
            ob_start();
            include "part/$part.php";
            $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = '<div class="error notice notice-error"><p>part "' . $part . '" not found.</p></div>';
        }

        $content = strtr('<!--part {part}-->{content}<!--part /{part}-->', [
            '{part}' => $part,
            '{content}' => $content,
        ]);

        return $content;
    }

    public static function block(string $block, array $args = []): string
    {
        if (file_exists("block/$block/render.php")) {
            extract($args, EXTR_SKIP);
            ob_start();
            include "block/$block/render.php";
            $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = '<div class="error notice notice-error"><p>block "' . $part . '" not found.</p></div>';
        }

        $content = strtr('<!--block {block}-->{content}<!--block /{block}-->', [
            '{block}' => $block,
            '{content}' => $content,
        ]);

        return $content;
    }

    public static function styles(): void
    {
        $list = [];

        // global css
        $path = 'vp-styles.min.css';
        if (file_exists($path)) {
            $hash = filemtime($path);
            $path = "/$path?ver=$hash";
            $list[] = ['tag' => 'link', 'rel' => 'stylesheet', 'type' => 'text/css', 'href' => $path];
        }

        // block css
        foreach (self::filemap('block') as $path) {
            if (str_ends_with($path, '/style.css')) {
                $hash = filemtime('block/' . $path);
                $list[] = [
                    'tag' => 'link',
                    'rel' => 'stylesheet',
                    'type' => 'text/css',
                    'href' => "/block/$path?ver=$hash",
                ];
            }
        }

        // render
        echo html::render($list);
    }

    /**
     * Autoload tous les modules globaux (/js) et les modules de block (/block)
     *
     * En cas d'utilisation de scripts externes, il faut les ajouter en arguments de la fonction.
     * Ils seront ajoutés dans l'importmap, mais pas autoloadés.
     *
     * @param array $externals An associative array of external modules files to include (id => url).
     */
    static function scripts(array $externals = []): void
    {
        $import = [];

        // external js
        foreach ($externals as $id => $url) {
            $import[$id] = $url;
        }

        // global js
        foreach (self::filemap('js') as $path) {
            if (str_ends_with($path, '.js')) {
                $id = '@vp/' . preg_replace('#\.js$#', '', $path);
                $hash = filemtime('js/' . $path);
                $import[$id] = '/js/' . $path . '?ver=' . $hash;
            }
        }

        // block js
        foreach (self::filemap('block') as $path) {
            if (str_ends_with($path, '/script.js')) {
                $id = '@vp/block/' . preg_replace('#/script\.js$#', '', $path);
                $hash = filemtime('block/' . $path);
                $import[$id] = '/block/' . $path . '?ver=' . $hash;
            }
        }

        // render
        if (!empty($import)) {
            $script = [];
            foreach ($import as $id => $url) {
                // no autoload on external libs
                if (str_starts_with($id, '@vp/')) {
                    $script[] = "import '$id'";
                }
            }

            echo html::render([
                [
                    'tag' => 'script',
                    'type' => 'importmap',
                    'content' => json_encode(['imports' => $import], JSON_UNESCAPED_SLASHES)
                ],
                [
                    'tag' => 'script',
                    'type' => 'module',
                    'content' => implode("\n", $script),
                ],
            ]);
        }
    }

    /**
     * Recursive folder content mapping.
     *
     * @param string $folder The path to the folder.
     * @param int    $sort   The sorting flags (default: SORT_NATURAL | SORT_FLAG_CASE).
     *
     * @return array A list of relative paths to files and folders.
     */
    static function filemap($folder, $sort = SORT_NATURAL | SORT_FLAG_CASE)
    {
        if (is_dir($folder) && ($fp = @opendir($folder))) {
            $folders = [];
            $files = [];
            while (($entry = readdir($fp)) !== false) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                if (is_dir($folder . '/' . $entry)) {
                    $folders[] = $entry;
                } elseif (is_file($folder . '/' . $entry)) {
                    $files[] = $entry;
                }
            }
            closedir($fp);
            if (empty($folders) && empty($files)) {
                return false;
            }
            sort($folders, $sort);
            sort($files, $sort);
            foreach ($folders as $key => $value) {
                $map = self::filemap($folder . '/' . $value);
                unset($folders[$key]);
                if (is_array($map) && !empty($map)) {
                    foreach ($map as $p) {
                        $folders[] = $value . '/' . $p;
                    }
                }
            }
            $output = [...$folders, ...$files];
            return $output;
        }

        return [];
    }
}
