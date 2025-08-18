<?php

remove_action('wp_head',             'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles',     'print_emoji_styles');
remove_action('admin_print_styles',  'print_emoji_styles');

remove_action('wp_head', 'wp_resource_hints', 2); //remove dns-prefetch
remove_action('wp_head', 'wp_generator'); //remove meta name="generator"
remove_action('wp_head', 'wlwmanifest_link'); //remove wlwmanifest
remove_action('wp_head', 'rsd_link'); // remove EditURI
remove_action('wp_head', 'rel_canonical'); //remove canonical
remove_action('wp_head', 'wp_shortlink_wp_head', 10); //remove shortlink
remove_action('wp_head', 'wp_oembed_add_discovery_links'); //remove alternate

// Отключить комментарии
add_action("admin_menu", "remove_menus");
function remove_menus()
{
    remove_menu_page("edit-comments.php");        # Комментарии
}

// Переименовать посты
add_action('admin_menu', 'rename_posts_menu_label');
function rename_posts_menu_label()
{
    global $menu;
    global $submenu;

    // Меняем название в основном меню
    $menu[5][0] = 'Новости'; // Новое название

    // Меняем название в подменю
    $submenu['edit.php'][5][0] = 'Все новости'; // Все записи
    $submenu['edit.php'][10][0] = 'Добавить новость'; // Добавить новую
    $submenu['edit.php'][16][0] = 'Метки новостей'; // Метки
}

// Изменить иконку постов
add_action('admin_head', 'change_posts_menu_icon');
function change_posts_menu_icon()
{
    echo '
    <style>
        #menu-posts div.wp-menu-image::before {
            content: "\f321"; /* Иконка из Dashicons */
        }
    </style>
    ';
}

// Предзагрузка шрифтов
add_action('wp_head', 'preload_all_fonts', 1);
function preload_all_fonts()
{
    $fonts_dir = get_theme_file_path('/assets/fonts/');
    $fonts_url = get_theme_file_uri('/assets/fonts/');

    if (file_exists($fonts_dir)) {
        $font_files = scandir($fonts_dir);
        $allowed_extensions = array('woff2', 'woff', 'ttf');

        foreach ($font_files as $font_file) {
            $file_ext = pathinfo($font_file, PATHINFO_EXTENSION);

            if (in_array($file_ext, $allowed_extensions)) {
                $font_url = esc_url($fonts_url . $font_file);
                $font_type = "font/{$file_ext}";

                echo '<link rel="preload" href="' . $font_url . '" as="font" type="' . $font_type . '" crossorigin>' . "\n";
            }
        }
    }
}


// Включить меню
add_action('after_setup_theme', function () {
    register_nav_menus([
        'header_menu' => 'Главное меню',
        'footer_menu' => 'Меню в подвале'
    ]);
});
