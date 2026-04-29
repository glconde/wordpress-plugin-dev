<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_render_job_applications(): string
{
    wp_enqueue_style(
        'wpjt-job-tracker',
        plugin_dir_url(__FILE__) . 'assets/job-tracker.css',
        [],
        '0.1.0'
    );
    
    $query = new WP_Query([
        'post_type' => 'job_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ]);

    if (!$query->have_posts()) {
        return '<p>No job applications found.</p>';
    }

    $emoji_map = [
        'Interested' => '⭐',
        'Applied' => '📤',
        'Interviewing' => '🗣️',
        'Offer' => '💼',
        'Rejected' => '❌',
    ];

    ob_start();

    echo '<div class="wpjt-table-wrapper">';
    echo '<table class="wpjt-table">';
    echo '<thead>';
    echo '<tr><th>Title</th><th>Company</th><th>Status</th></tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($query->have_posts()) {
        $query->the_post();

        $company = get_post_meta(get_the_ID(), '_wpjt_company', true);
        $status = get_post_meta(get_the_ID(), '_wpjt_status', true);

        $status_class = 'wpjt-status-' . sanitize_html_class(strtolower($status));
        $emoji = $emoji_map[$status] ?? '';

        echo '<tr>';
        echo '<td>' . esc_html(get_the_title()) . '</td>';
        echo '<td>' . esc_html($company) . '</td>';
        echo '<td><span class="wpjt-status ' . esc_attr($status_class) . '">'
            . esc_html($emoji . ' ' . $status)
            . '</span></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('job_tracker', 'wpjt_render_job_applications');