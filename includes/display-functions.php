<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render HTML for the migration page
 */
function bd_migrator_render_html()
{
    echo '<div class="wrap"><h1>Breakdance Migrator</h1>';
    ?>
        <div class="bd-migrator-columns">
            <!-- Export Section -->
            <div class="bd-migrator-column">
                <h2>Export Data</h2>
                <form method="post" id="bd-migrator-form__export" class="bd-migrator-form">
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
                    <div class="bd-migrator-export-result"></div>
                </form>
            </div>

            <!-- Import Section -->
            <div class="bd-migrator-column">
                <h2>Import Data</h2>
                <form method="post" id="bd-migrator-form__import" class="bd-migrator-form" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field('import_breakdance_data_action', 'import_breakdance_data_nonce'); ?>
                    <input type="hidden" name="action" value="bd_migrator_import">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="import_file">Upload File</label>
                            </th>
                            <td>
                                <input type="file" id="import_file" name="import_file">
                            </td>
                        </tr>
                        <!-- <tr>
        <th scope="row">
            <label for="import_url">Or Enter File URL</label>
        </th>
        <td>
            <input type="text" id="import_url" name="import_url">
        </td>
    </tr> -->
                    </table>
                    <p class="submit">
                        <input type="submit" class="button button-secondary" value="Import Data">
                    </p>
                </form>
            </div>
        </div>
        <div class="bd-migrator-import-result"></div>
        <?php
        echo '</div>';
}
