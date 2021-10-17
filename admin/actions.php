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
    $themes = wp_get_themes();

    if (empty($themes)) {
        return;
    }

    $req_uri = $_SERVER['REQUEST_URI'];
    $req_var = parse_url($req_uri, PHP_URL_QUERY);
    $seleted = empty($_COOKIE['wpts-selected-theme']) ? false : $_COOKIE['wpts-selected-theme'];

    $wp_admin_bar->add_node([
        'id' => 'wp_theme_switcher',
        'title' => __('Theme Switcher', 'textdomain'),
    ]);

    $wp_admin_bar->add_menu([
        'id' => 'wp_theme_switcher_inherit',
        'parent' => 'wp_theme_switcher',
        'title' => $seleted ? __('Default', 'textdomain') : __('Default', 'textdomain') . ' ' . __('(Active)', 'textdomain'),
        'href' => $req_var ? $req_uri . '&wpts-selected-theme=inherit' : $req_uri . '?wpts-selected-theme=inherit'
    ]);

    foreach ($themes as $stylesheet => $theme) {
        if ($theme->parent()) {
            continue;
        }
        $title = $seleted && $seleted == $stylesheet ? ucwords(str_replace(['-', '_'], ' ', $stylesheet)) .  ' ' . __('(Active)', 'textdomain') : ucwords(str_replace(['-', '_'], ' ', $stylesheet));
        $wp_admin_bar->add_menu([
            'id' => 'wp_theme_switcher_' . $stylesheet,
            'parent' => 'wp_theme_switcher',
            'title' => $title,
            'href' => $req_var ? $req_uri . '&wpts-selected-theme=' . $stylesheet : $req_uri . '?wpts-selected-theme=' . $stylesheet
        ]);
    }
}
add_action('admin_bar_menu', 'wp_theme_switcher_admin_menu_bar', 200);
