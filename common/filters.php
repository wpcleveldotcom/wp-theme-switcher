<?php
/**
 * Copyright (c) WP Clevel <contact@wpclevel.com>
 *
 * This source code is licensed under the LICENSE
 * included in the root directory of this application.
 */

/**
 * Alter the parent theme.
 *
 * @see https://developer.wordpress.org/reference/hooks/template/
 */
function wp_theme_switcher_set_template($template)
{
    global $pagenow;

    if (!empty($_COOKIE['wpts-selected-theme']) && $_COOKIE['wpts-selected-theme'] != 'inherit' && 'themes.php' != $pagenow) {
        $selected_theme = sanitize_key($_COOKIE['wpts-selected-theme']);
        if (false !== strpos($selected_theme, '-child')) {
            return str_replace('-child', '', $selected_theme);
        } else {
            return $selected_theme;
        }
    }

    return $template;
}
add_filter('template', 'wp_theme_switcher_set_template', PHP_INT_MAX);

/**
 * Alter the child theme.
 *
 * @see https://developer.wordpress.org/reference/hooks/stylesheet/
 */
function wp_theme_switcher_set_stylesheet($stylesheet)
{
    global $pagenow;

    if (!empty($_COOKIE['wpts-selected-theme']) && $_COOKIE['wpts-selected-theme'] != 'inherit' && 'themes.php' != $pagenow) {
        return sanitize_key($_COOKIE['wpts-selected-theme']);
    }

    return $stylesheet;
}
add_filter('stylesheet', 'wp_theme_switcher_set_stylesheet', PHP_INT_MAX);

/**
 * Modify loading option.
 *
 * @see https://developer.wordpress.org/reference/hooks/option_option/
 */
function wp_theme_switcher_set_option($value, $option)
{
    global $pagenow;

    if (!empty($_COOKIE['wpts-selected-theme']) && $_COOKIE['wpts-selected-theme'] != 'inherit' && 'themes.php' != $pagenow) {
        $selected_theme = sanitize_key($_COOKIE['wpts-selected-theme']);
        switch ($option) {
            case 'template':
                if (false !== strpos($selected_theme, '-child')) {
                    return str_replace('-child', '', $selected_theme);
                } else {
                    return $selected_theme;
                }
                break;
            default:
                return $selected_theme;
                break;
        }
    }

    return $value;
}
add_filter('option_template', 'wp_theme_switcher_set_option', PHP_INT_MAX, 2);
add_filter('option_stylesheet', 'wp_theme_switcher_set_option', PHP_INT_MAX, 2);
