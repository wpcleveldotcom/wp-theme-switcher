<?php
/**
 * Plugin Name: WP Theme Switcher
 * Plugin URI: https://github.com/wpcleveldotcom/wp-theme-switcher
 * Description: Allowing you switching themes without affecting your visitors.
 * Author: WP Clevel
 * Author URI: https://wpclevel.com
 * Version: 1.0.0
 * Text Domain: wp-theme-switcher
 * Tested up to: 5.8.1
 * License:  GPL v3
 */

/**
 * Do installation
 *
 * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
 */
function wp_theme_switcher_install()
{
    // Make sure translation is available.
    load_plugin_textdomain('textdomain', false, 'wp-theme-switcher/languages');

    // Load common resources
    require __DIR__ . '/common/filters.php';

    // Load conditional resources
    if (is_admin()) {
        require __DIR__ . '/admin/actions.php';
    }
}
add_action('plugins_loaded', 'wp_theme_switcher_install', 10, 0);
