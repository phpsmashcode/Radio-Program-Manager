<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="notice notice-info">
        <p><?php _e('Upload a CSV file to import programs.', 'radio-program-manager'); ?></p>
        <p><?php _e('The CSV file should include the following columns:', 'radio-program-manager'); ?></p>
        <ul>
            <li><?php _e('i. Program Name', 'radio-program-manager'); ?></li>
            <li><?php _e('ii. Program Description', 'radio-program-manager'); ?></li>
            <li><?php _e('iii. Program Start Date (Format: YYYY-MM-DD, e.g., 2025-01-01)', 'radio-program-manager'); ?></li>
            <li><?php _e('iv. Program End Date (Format: YYYY-MM-DD, e.g., 2025-01-31)', 'radio-program-manager'); ?></li>
            <li><?php _e('v. Program Thumbnail (URL to the image)', 'radio-program-manager'); ?></li>
            <li><?php _e('vi. Broadcast Schedule (A JSON-like string defining days and times, e.g., {"Mon": "08:00", "Tue": "09:00"}', 'radio-program-manager'); ?></li>
        </ul>
    </div>

    <?php
    // Show success/error messages if any
    $status = get_transient('program_import_status');

    if (!empty($status)) {
        foreach ($status as $msg) {
            echo '<div class="notice notice-' . esc_attr($msg['type']) . ' is-dismissible">';
            echo '<p>' . esc_html($msg['message']) . '</p>';
            echo '</div>';
        }
        // Clear the transient after displaying messages
        delete_transient('program_import_status');
    }
    ?>

    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="import_programs">
        <?php wp_nonce_field('import_program_nonce', 'import_programs'); ?>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <?php submit_button(__('Import Programs', 'radio-program-manager')); ?>
    </form>
</div>