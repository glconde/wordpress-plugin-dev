<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_render_job_applications(): string
{
    $query = new WP_Query([
        'post_type' => 'job_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ]);

    if (!$query->have_posts()) {
        return '<p>No job applications found.</p>';
    }

    ob_start();

    echo '<table border="1" cellpadding="5">';
    echo '<tr><th>Title</th><th>Company</th><th>Status</th></tr>';

    while ($query->have_posts()) {
        $query->the_post();

        $company = get_post_meta(get_the_ID(), '_wpjt_company', true);
        $status = get_post_meta(get_the_ID(), '_wpjt_status', true);

        echo '<tr>';
        echo '<td>' . esc_html(get_the_title()) . '</td>';
        echo '<td>' . esc_html($company) . '</td>';
        echo '<td>' . esc_html($status) . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('job_tracker', 'wpjt_render_job_applications');