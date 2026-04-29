<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_add_job_application_meta_boxes(): void
{
    add_meta_box(
        'wpjt_job_details',
        'Job Details',
        'wpjt_render_job_details_meta_box',
        'job_application',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'wpjt_add_job_application_meta_boxes');

function wpjt_render_job_details_meta_box(WP_Post $post): void
{
    $company = get_post_meta($post->ID, '_wpjt_company', true);
    $status = get_post_meta($post->ID, '_wpjt_status', true);

    wp_nonce_field('wpjt_save_job_details', 'wpjt_job_details_nonce');
    ?>

    <p>
        <label for="wpjt_company">Company</label><br>
        <input
            type="text"
            id="wpjt_company"
            name="wpjt_company"
            value="<?php echo esc_attr($company); ?>"
            style="width: 100%;"
        >
    </p>

    <p>
        <label for="wpjt_status">Application Status</label><br>
        <select id="wpjt_status" name="wpjt_status">
            <?php
            $statuses = ['Interested', 'Applied', 'Interviewing', 'Offer', 'Rejected'];

            foreach ($statuses as $option) {
                echo '<option value="' . esc_attr($option) . '" ' . selected($status, $option, false) . '>' . esc_html($option) . '</option>';
            }
            ?>
        </select>
    </p>

    <?php
}

function wpjt_save_job_details(int $post_id): void
{
    if (!isset($_POST['wpjt_job_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wpjt_job_details_nonce'], 'wpjt_save_job_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['wpjt_company'])) {
        update_post_meta($post_id, '_wpjt_company', sanitize_text_field($_POST['wpjt_company']));
    }

    if (isset($_POST['wpjt_status'])) {
        update_post_meta($post_id, '_wpjt_status', sanitize_text_field($_POST['wpjt_status']));
    }
}

add_action('save_post_job_application', 'wpjt_save_job_details');