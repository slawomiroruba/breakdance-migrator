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
    $upload_dir = wp_upload_dir();
    $filename = 'breakdance_data_export-' . date('Y-m-d-H-i-s') . '.json.gz';
    $file_path = $upload_dir['basedir'] . '/breakdance_export/' . $filename;

    if (!file_exists($upload_dir['basedir'] . '/breakdance_export')) {
        mkdir($upload_dir['basedir'] . '/breakdance_export', 0755, true);
    }

    // Kompresja JSON przy użyciu gzip
    $json_data = json_encode($data);
    $gz_data = gzencode($json_data, 9); // Poziom kompresji od 0 do 9 (najwyższa)

    file_put_contents($file_path, $gz_data);

    $file_url = $upload_dir['baseurl'] . '/breakdance_export/' . $filename;
    $view_url = add_query_arg('json_file', $filename, home_url());

    echo '<p>Data exported successfully.</p>';
    echo '<p><a href="' . $file_url . '" class="button button-secondary" download>Download</a> ';
    echo '<a href="' . $view_url . '" class="button button-primary" target="_blank">Open</a></p>';
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
