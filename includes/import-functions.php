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

                // 0. Check if the uploaded file is a valid JSON file or archive
                $file_extension = pathinfo($uploaded_file_path, PATHINFO_EXTENSION);
                if (!in_array($file_extension, ['zip', 'gz', 'json'])) {
                    wp_die('Invalid file format. Please upload a valid JSON file.');
                }

                // Jeśli plik jest archiwum, rozpakuj go
                if ($file_extension === 'zip') {
                    $zip = new ZipArchive();
                    if ($zip->open($uploaded_file_path) === true) {
                        $zip->extractTo($upload_dir['path'] . '/breakdance-migrator/import/');
                        $zip->close();
                        // Get the extracted file path
                        $extracted_files = glob($upload_dir['path'] . '/breakdance-migrator/import/*');
                        if (count($extracted_files) === 1) {
                            $uploaded_file_path = $extracted_files[0];
                        } else {
                            wp_die('Invalid ZIP archive. Please upload a valid JSON file.');
                        }
                    } else {
                        wp_die('Failed to extract ZIP archive.');
                    }
                } elseif ($file_extension === 'gz') {
                    $json_data = gzdecode(file_get_contents($uploaded_file_path));
                    if ($json_data === false) {
                        wp_die('Failed to decompress GZIP archive.');
                    }
                    $json_file_path = $upload_dir['path'] . '/breakdance-migrator/import/' . basename($uploaded_file['name'], '.gz');
                    file_put_contents($json_file_path, $json_data);
                    $uploaded_file_path = $json_file_path;
                }

                // 1. Read the file contents
                $file_contents = file_get_contents($uploaded_file_path);
                // 2. Parse the file contents - array of data
                $data = json_decode($file_contents, true);
                // 3. Process the data
                $posts = $data['posts'] ?? [];
                /**
                 * Process the posts data
                 * 1. Sprawdzamy czy post o podanym ID już istnieje
                 * 2. Jeśli nie istnieje, 
                 *    a. tworzymy nowy post
                 *    b. dodajemy meta dane
                 * 3. Jeśli istnieje
                 *    a. Sprawdzamy czy post jest tego samego typu
                 *    b. Aktualizujemy post (tytuł, name)
                 *    c. Aktualizujemy meta dane
                 */
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
