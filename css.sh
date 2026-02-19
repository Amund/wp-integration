#!/bin/bash

# ne fonctionne que sous docker, lightningcss et inotify-tools sont dans l'image
# `make css` pour lancer le build des css
# ou
# `make css watch` pour lancer le watcher pendant le dev

targets="Safari >= 15.6"
theme_dir="web"
cwd=$(pwd)

css_all_blocks () {
    for sub_dir in "$theme_dir/block"/*/; do
        if [ -f "$sub_dir/style.src.css" ]; then
            lightningcss --minify --targets "$targets" "$sub_dir/style.src.css" -o "$sub_dir/style.css"
        fi
    done
}

css_bundle () {
    # auto-generate main.css
    {
        echo "/* Auto-generated file, don't edit manually. */"
        find "$theme_dir/css" -type f -name "*.css" ! -name "main.css" | cut -c $((${#theme_dir}+6))- | sort | sed "s|.*|@import '&';|"
    } > "$theme_dir/css/main.css"
    # process bundle
    cd $theme_dir
    lightningcss --minify --bundle --sourcemap --targets "$targets" "css/main.css" -o "vp-styles.min.css"
    cd $cwd
}

css_block () {
    lightningcss --minify --targets "$targets" "$theme_dir/block/$1/style.src.css" -o "$theme_dir/block/$1/style.css"
}

watch () {
    echo "Watching..."
    trap "echo ' Stopping CSS watcher.'; exit 0" SIGTERM SIGINT SIGKILL
    while true; do
        inotifywait -mqre close_write "$theme_dir/block" "$theme_dir/css" --format '%w %f %e' |
        while read dir file event ; do
            if [[ $dir = "$theme_dir/block/"* ]] && [[ $file = "style.src.css" ]]; then
                block_name=$(basename "$dir")
                css_block "$block_name" || echo "Error updating block $block_name"
                echo "CSS block $block_name updated"
            elif [[ $dir = "$theme_dir/css/"* ]] && [[ $file != "main.css" ]]; then
                css_bundle || echo "Error updating CSS bundle"
                echo "CSS bundle updated"
            fi
        done
        echo "inotifywait crashed, restarting..."
        sleep 1
    done
}

build () {
    echo -n "Building CSS bundle and blocks... "
    css_all_blocks
    css_bundle
    echo "ok"
}

if [[ "$1" = "watch" ]]; then
    build
    watch
    exit 0
else
    build
    exit 0
fi
