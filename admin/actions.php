<?php
/**
 * Copyright (c) WP Clevel <contact@wpclevel.com>
 *
 * This source code is licensed under the LICENSE
 * included in the root directory of this application.
 */


// Being inside the `plugins_loaded` hook.
if (isset($_GET['wpts-selected-theme'])) {
    if ($_GET['wpts-selected-theme'] == 'inherit') {
        unset($_COOKIE['wpts-selected-theme']);
        setcookie('wpts-selected-theme', null, [
            'path' => '/',
            'expires' => -1,
        ]);
    } else {
        setcookie('wpts-selected-theme', $_GET['wpts-selected-theme'], [
            'path' => '/',
            'expires' => strtotime('+1 year'),
        ]);
    }
    header("Location: " . admin_url());
    exit;
}

/**
 * Show available themes to switch on the admin bar.
 */
function wp_theme_switcher_admin_menu_bar($wp_admin_bar)
{
    global $wpdb;

    $themes = wp_get_themes();

    if (empty($themes)) {
        return;
    }

    $req_uri = $_SERVER['REQUEST_URI'];
    $req_var = parse_url($req_uri, PHP_URL_QUERY);
    $seleted = empty($_COOKIE['wpts-selected-theme']) ? false : $_COOKIE['wpts-selected-theme'];

    $is_child_theme = is_child_theme();
    $default_template = $wpdb->get_var("SELECT option_value from $wpdb->options WHERE option_name='template' LIMIT 1;");
    $default_stylesheet = $wpdb->get_var("SELECT option_value from $wpdb->options WHERE option_name='stylesheet' LIMIT 1;");

    $wp_admin_bar->add_node([
        'id' => 'wp_theme_switcher',
        'title' => __('Theme Switcher', 'textdomain'),
    ]);

    $wp_admin_bar->add_menu([
        'id' => 'wp_theme_switcher_inherit',
        'parent' => 'wp_theme_switcher',
        'title' => __('Default', 'textdomain'),
        'href' => $req_var ? $req_uri . '&wpts-selected-theme=inherit' : $req_uri . '?wpts-selected-theme=inherit'
    ]);

    foreach ($themes as $slug => $theme) {
        if (($is_child_theme && $slug == $default_stylesheet) || $slug == $default_template) {
            continue;
        }
        $wp_admin_bar->add_menu([
            'id' => 'wp_theme_switcher_' . $slug,
            'parent' => 'wp_theme_switcher',
            'title' => $seleted && $seleted == $slug ? $theme->Name .  ' ' . __('(Active)', 'textdomain') : $theme->Name,
            'href' => $req_var ? $req_uri . '&wpts-selected-theme=' . $slug : $req_uri . '?wpts-selected-theme=' . $slug
        ]);
    }
}
add_action('admin_bar_menu', 'wp_theme_switcher_admin_menu_bar', 200);
