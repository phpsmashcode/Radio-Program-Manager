<div class="program-meta-box">
    <p>
        <label for="program_start_date"><?php _e('Start Date (required):', 'radio-program-manager'); ?></label>
        <input
            type="date"
            id="program_start_date"
            name="program_start_date"
            value="<?php echo esc_attr($start_date); ?>"
            required>
    </p>
    <p>
        <label for="program_end_date"><?php _e('End Date (required):', 'radio-program-manager'); ?></label>
        <input
            type="date"
            id="program_end_date"
            name="program_end_date"
            value="<?php echo esc_attr($end_date); ?>"
            required>
    </p>
</div>