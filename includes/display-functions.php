<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render HTML for the migration page
 */
function bd_migrator_render_html() {
    echo '<div class="wrap"><h1>Breakdance Migrator</h1>';
    ?>
    <form method="post" class="bd-migrator-form">
        <?php wp_nonce_field('export_breakdance_data_action', 'export_breakdance_data_nonce'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="export_icons">Export Icons</label>
                </th>
                <td>
                    <input type="checkbox" id="export_icons" name="export_icons" value="1">
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="Export Data">
        </p>
    </form>
    <div class="bd-migrator-result"></div>
    <?php
    echo '</div>';
}


/**
 * Display JSON file with appropriate headers
 */
function display_json_file()
{
    if (isset($_GET['json_file'])) {
        $file = sanitize_text_field($_GET['json_file']);
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/breakdance_export/' . $file;

        if (file_exists($file_path)) {
            header('Content-Type: application/json');
            header('Content-Encoding: gzip');
            echo file_get_contents($file_path);
            exit;
        } else {
            wp_die('File not found.');
        }
    }
}
add_action('init', 'display_json_file');
