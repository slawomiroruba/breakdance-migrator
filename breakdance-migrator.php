<?php
/**
 * Plugin Name: Breakdance Migrator
 * Description: Allows to migrate Breakdance content from staging to production site.
 * Version: 1.0
 * Author: Sławomir Oruba
 * Author URI: https://lunadesign.com.pl/slawomir-oruba/
 * Text Domain: breakdance-migrator
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
// Check if Breakdance plugin is active
include_once (ABSPATH . 'wp-admin/includes/plugin.php');

function is_breakdance_active()
{
    // Additional check: search through all active plugins
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    foreach ($active_plugins as $active_plugin) {
        if (preg_match('/breakdance\/plugin\.php$/', $active_plugin)) {
            error_log("Breakdance is active"); // Log that Breakdance is active
            return true;
        }
    }

    // If Breakdance is not active, deactivate Breakdance Migrator
    error_log("Breakdance is not active, deactivating Breakdance Migrator");
    deactivate_plugins(plugin_basename(__FILE__));
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p><strong>Breakdance Migrator:</strong> Breakdance plugin is not active. Breakdance Migrator has been deactivated.</p></div>';
    });
    return false;
}

function check_breakdance_active_on_activation()
{
    if (!is_breakdance_active()) {
        wp_die('<div class="notice notice-error"><p><strong>Breakdance Migrator:</strong> Breakdance plugin is not active. Please install and activate the Breakdance plugin first.</p></div>', 'Plugin dependency check', array('back_link' => true));
    }
}

register_activation_hook(__FILE__, 'check_breakdance_active_on_activation');

if (!is_breakdance_active()) {
    return;
}

// Deactivate Breakdance Migrator if Breakdance is deactivated
function deactivate_breakdance_migrator_if_breakdance_deactivated($plugin, $network_deactivating)
{
    error_log("Deactivated plugin: $plugin"); // Log the deactivated plugin for debugging
    if (preg_match('/breakdance\/plugin\.php$/', $plugin) && is_plugin_active('breakdance-migrator/breakdance-migrator.php')) {
        deactivate_plugins('breakdance-migrator/breakdance-migrator.php');
        add_action('admin_notices', function () {
            echo '<div class="notice notice-warning"><p><strong>Breakdance Migrator:</strong> Breakdance Migrator has been deactivated because Breakdance plugin was deactivated.</p></div>';
        });
    }
}
add_action('deactivated_plugin', 'deactivate_breakdance_migrator_if_breakdance_deactivated', 10, 2);

// Define constants
define('BD_MIGRATOR_PATH', plugin_dir_path(__FILE__));
define('BD_MIGRATOR_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once BD_MIGRATOR_PATH . 'includes/export-functions.php';
require_once BD_MIGRATOR_PATH . 'includes/display-functions.php';

// Dodanie nowego linku "Migracja" do listy akcji wtyczki
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_migration_link');

function add_migration_link($links)
{
    $migration_link = '<a href="' . admin_url('admin.php?page=breakdance_migrator') . '">Migracja</a>';
    array_unshift($links, $migration_link); // Dodaj nowy link na początku listy
    return $links;
}


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

