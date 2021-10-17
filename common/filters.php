<?php
/**
 * Copyright (c) WP Clevel <contact@wpclevel.com>
 *
 * This source code is licensed under the LICENSE
 * included in the root directory of this application.
 */

/**
 * Modify loading template.
 */
function wp_theme_switcher_set_template($template)
{
    if (!empty($_COOKIE['wpts-selected-theme']) && $_COOKIE['wpts-selected-theme'] != 'inherit') {
        return sanitize_key($_COOKIE['wpts-selected-theme']);
    }

    return $template;
}
add_filter('template', 'wp_theme_switcher_set_template', PHP_INT_MAX);
add_filter('stylesheet', 'wp_theme_switcher_set_template', PHP_INT_MAX);
add_filter('option_template', 'wp_theme_switcher_set_template', PHP_INT_MAX);
add_filter('option_stylesheet', 'wp_theme_switcher_set_template', PHP_INT_MAX);
add_filter('option_current_theme', 'wp_theme_switcher_set_template', PHP_INT_MAX);
// add_filter('default_option_template', 'wp_theme_switcher_set_template', PHP_INT_MAX);
// add_filter('default_option_stylesheet', 'wp_theme_switcher_set_template', PHP_INT_MAX);
// add_filter('default_option_current_theme', 'wp_theme_switcher_set_template', PHP_INT_MAX);
