<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Export posts and postmeta where post_type includes 'breakdance'
 */
function export_posts()
{
    global $wpdb;
    $posts = $wpdb->get_results("SELECT ID, post_title, post_type, post_date, post_status FROM {$wpdb->posts} WHERE post_type LIKE '%breakdance%'");
    return $posts;
}


function export_postmeta()
{
    global $wpdb;
    $post_meta = $wpdb->get_results("SELECT pm.* FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type LIKE '%breakdance%'");
    return $post_meta;
}

function export_options()
{
    global $wpdb;
    $options = $wpdb->get_results("SELECT * FROM {$wpdb->options} WHERE option_name LIKE '%breakdance%'");
    return $options;
}

function export_icons()
{
    global $wpdb;
    $icons = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}icons WHERE name LIKE '%breakdance%'"); // Assuming there is a `wp_icons` table
    return $icons;
}

function export_icons_set()
{
    global $wpdb;
    $icon_sets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}icon_sets WHERE set_name LIKE '%breakdance%'"); // Assuming there is a `wp_icon_sets` table
    return $icon_sets;
}

/**
 * Save exported data to file
 */
function save_to_file($data)
{
    $filename = 'breakdance_data_export-' . date('Y-m-d-H-i-s') . '.json.gz';
    // Upewnij się, że struktura katalogów istnieje
    $upload_dir = wp_upload_dir();
    $base_dir = $upload_dir['basedir'] . '/breakdance-migrator';

    // Tworzenie katalogu głównego 'breakdance-migrator', jeśli nie istnieje
    if (!file_exists($base_dir)) {
        mkdir($base_dir, 0755, true);
    }

    // Tworzenie podkatalogu 'import', jeśli nie istnieje
    $import_dir = $base_dir . '/import';
    if (!file_exists($import_dir)) {
        mkdir($import_dir, 0755, true);
    }

    // Tworzenie podkatalogu 'export', jeśli nie istnieje
    $export_dir = $base_dir . '/export';
    if (!file_exists($export_dir)) {
        mkdir($export_dir, 0755, true);
    }

    // Ścieżka do pliku
    $file_path = $export_dir . '/' . $filename;
    // Kompresja JSON przy użyciu gzip
    $json_data = json_encode($data);
    $gz_data = gzencode($json_data, 9); // Poziom kompresji od 0 do 9 (najwyższa)

    file_put_contents($file_path, $gz_data);

    $file_url = $upload_dir['baseurl'] . '/breakdance-migrator/export/' . $filename;
    $view_url = add_query_arg('json_file', $filename, home_url());

    echo '<p>Data exported successfully.</p>';
    echo '<p><a href="' . $file_url . '" class="button button-secondary" download>Download</a> ';
}

/**
 * Export breakdance data
 */
function export_breakdance_data($export_icons = false)
{
    $data = array(
        'posts' => export_posts() ?? array(),
        'postmeta' => export_postmeta() ?? array(),
        'options' => export_options() ?? array(),
    );

    if ($export_icons) {
        $data['icons'] = export_icons() ?? array();
        $data['icons_set'] = export_icons_set() ?? array();
    }

    save_to_file($data);
}

add_action('wp_ajax_export_breakdance_data', 'ajax_export_breakdance_data');

function ajax_export_breakdance_data()
{
    check_ajax_referer('bd_migrator_nonce', 'nonce');

    $export_icons = isset($_POST['export_icons']) ? (bool) $_POST['export_icons'] : false;
    ob_start();
    export_breakdance_data($export_icons);
    $output = ob_get_clean();

    wp_send_json_success(array('message' => $output));
}
