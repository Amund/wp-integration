<?php

class html
{
    const DEBUG_WRAPPER = [
        'tag' => 'pre',
        'style' =>
        'font:12px/13px Consolas,\'Lucida Console\',monospace;text-align:left;color:#ddd;background-color:#222;padding:10px;max-height:500px;overflow:auto;',
    ];

    /**
     * Return an HTML tag string, with its attributes and contents
     * @param string $tag HTML tag name
     * @param array<string, scalar> $attributes HTML tag attributes
     * @param string $content HTML tag content
     * @param bool $allow_empty_content Allow empty string as content
     * @return string HTML tag string
     */
    static function tag(
        string $tag,
        array $attributes = [],
        string $content = '',
        bool $allow_empty_content = false,
    ): string {
        // empty tag, return empty
        $tag = trim($tag);
        if (empty($tag)) {
            return '';
        }
        // https://www.thoughtco.com/html-singleton-tags-3468620
        $voidElements = [
            'area' => 1,
            'base' => 1,
            'br' => 1,
            'col' => 1,
            'command' => 1,
            'embed' => 1,
            'hr' => 1,
            'img' => 1,
            'input' => 1,
            'keygen' => 1,
            'link' => 1,
            'meta' => 1,
            'param' => 1,
            'source' => 1,
            'track' => 1,
            'wbr' => 1,
        ];
        $has_closing_tag = !isset($voidElements[strtolower($tag)]);

        // prepare attributes output
        if (count($attributes) > 0) {
            foreach ($attributes as $key => $value) {
                if ($value === NULL) {
                    unset($attributes[$key]);
                    continue;
                }
                if ($value !== '0' && empty($value)) {
                    $attributes[$key] = ' ' . $key;
                } else {
                    $attributes[$key] = ' ' . $key . '="' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '"';
                }
            }
            $attributes = implode('', $attributes);
        } else {
            $attributes = '';
        }
        if ($has_closing_tag) {
            if ($content !== '0' && empty($content) && !$allow_empty_content) {
                $output = '';
            } else {
                $output = '<' . $tag . $attributes . '>' . $content . '</' . $tag . '>';
            }
        } else {
            // no closing tag, no content needeed
            $output = '<' . $tag . $attributes . '>';
        }

        return $output;
    }

    /**
     * Render an array or string to html
     *
     * If $data is a string, it is returned as is.
     * If $data is an array, it can be one of two things:
     * - an indexed array of strings or arrays, in which case each item is rendered using this function, and the results are concatenated.
     * - an associative array, in which case the 'tag' key must be present, and optionally the 'content' key.
     *   The 'tag' key is used to determine the html tag to use, and the 'content' key is used as the content of this tag.
     *   if the 'content' key is an array, it is rendered using this function, and the result is used as the content of the tag.
     *   If the 'allow_empty_content' key is present, it is used to determine if an empty content is allowed for the tag.
     *
     * @param mixed $data the data to render
     * @param bool $allow_empty_content whether to allow empty content for the tag
     * @return string the rendered html
     */
    static function render(mixed $data, bool $allow_empty_content = false): string
    {
        if (!is_string($data) && !is_array($data)) {
            $data = (string) $data;
        }

        if (is_string($data)) {
            return $data;
        }

        if (!array_is_list($data)) {
            $tag = $data['tag'] ?? '';
            unset($data['tag']);
            $content = $data['content'] ?? '';
            unset($data['content']);
            if (isset($data['allow_empty_content'])) {
                $allow_empty_content = (bool) $data['allow_empty_content'];
                unset($data['allow_empty_content']);
            }

            if (is_array($content)) {
                if (!array_is_list($content)) {
                    $content = self::render($content);
                } else {
                    $content = implode('', array_map([self::class, 'render'], $content));
                }
            }
            $output = self::tag($tag, $data, $content, $allow_empty_content);
        } else {
            $output = implode('', array_map([self::class, 'render'], $data));
        }

        return $output;
    }

    // Debug: print_r html formatted
    static function print($args): void
    {
        echo self::render(array_merge(self::DEBUG_WRAPPER, ['content' => print_r($args, 1)]));
    }

    // Debug: var_dump html formatted
    static function dump(...$args): void
    {
        ob_start();
        var_dump(...$args);
        $dump = ob_get_contents();
        ob_end_clean();
        echo self::render(array_merge(self::DEBUG_WRAPPER, ['content' => $dump]));
    }

    // Debug: var_export html formatted
    static function export($args): void
    {
        echo self::render(
            array_merge(self::DEBUG_WRAPPER, [
                'content' => var_export($args, 1),
            ]),
        );
    }
}
