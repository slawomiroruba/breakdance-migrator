<?php
/**
 * Plugin Name: Breakdance Migrator
 * Description: Allows to migrate Breakdance content from staging to production site.
 * Version: 1.0
 * Author: Sławomir Oruba
 * Author URI: https://lunadesign.com.pl/slawomir-oruba/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('BD_MIGRATOR_PATH', plugin_dir_path(__FILE__));
define('BD_MIGRATOR_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once BD_MIGRATOR_PATH . 'includes/export-functions.php';
require_once BD_MIGRATOR_PATH . 'includes/display-functions.php';

// Register custom menu page
add_action('admin_menu', 'register_bd_migrator_menu_page', 100);

/**
 * Register custom menu page under 'Breakdance'
 */
function register_bd_migrator_menu_page()
{
    add_submenu_page(
        'breakdance', // Slug of the main menu
        'Breakdance Migrator', // Page title
        'Breakdance Migrator', // Menu title
        'manage_options', // Capability
        'breakdance_migrator', // Menu slug
        'bd_migrator_render_html' // Function to render the HTML
    );
}

// Add styles
// Add scripts
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('bd-migrator-styles', BD_MIGRATOR_URL . 'assets/css/styles.css');
    wp_enqueue_script('bd-migrator-scripts', BD_MIGRATOR_URL . 'assets/js/script.js', array ('jquery'), null, true);
    wp_localize_script(
        'bd-migrator-scripts',
        'bdMigrator',
        array (
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bd_migrator_nonce')
        )
    );
});

