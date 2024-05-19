<?php
// Handle the form submission
add_action('admin_post_bd_migrator_import', 'bd_migrator_handle_import');

function bd_migrator_handle_import()
{
    if (!isset($_POST['import_breakdance_data_nonce']) || !wp_verify_nonce($_POST['import_breakdance_data_nonce'], 'import_breakdance_data_action')) {
        wp_die('Invalid nonce');
    }

    if (!empty($_FILES['import_file']['name'])) {
        // Handle file upload
        $uploaded_file = $_FILES['import_file'];
        if ($uploaded_file['error'] === UPLOAD_ERR_OK) {
            $upload_dir = wp_upload_dir();
            $uploaded_file_path = $upload_dir['path'] . '/breakdance-migrator/import/' . basename($uploaded_file['name']);
            if (move_uploaded_file($uploaded_file['tmp_name'], $uploaded_file_path)) {
                // Process the uploaded file
                // ...
                wp_die('File uploaded successfully.');
            } else {
                wp_die('Failed to move uploaded file.');
            }
        } else {
            wp_die('File upload error.');
        }
    } elseif (!empty($_POST['import_url'])) {
        // Handle file URL
        $file_url = esc_url_raw($_POST['import_url']);
        $response = wp_remote_get($file_url);
        if (is_wp_error($response)) {
            wp_die('Invalid file URL.');
        } else {
            $file_contents = wp_remote_retrieve_body($response);
            if (!empty($file_contents)) {
                // Process the file contents
                // ...
                wp_die('File imported from URL successfully.');
            } else {
                wp_die('Failed to retrieve file from URL.');
            }
        }
    } else {
        wp_die('No file uploaded or URL provided.');
    }

    // Redirect back to the plugin page
    wp_redirect(admin_url('admin.php?page=breakdance_migrator'));
    exit;
}
