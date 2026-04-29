<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_register_dashboard_widget(): void
{
    wp_add_dashboard_widget(
        'wpjt_dashboard_widget',
        'Job Application Summary',
        'wpjt_render_dashboard_widget'
    );
}

add_action('wp_dashboard_setup', 'wpjt_register_dashboard_widget');

function wpjt_render_dashboard_widget(): void
{
    $statuses = ['Interested', 'Applied', 'Interviewing', 'Offer', 'Rejected'];

    echo '<ul>';

    foreach ($statuses as $status) {

        $query = new WP_Query([
            'post_type' => 'job_application',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_wpjt_status',
                    'value' => $status,
                    'compare' => '='
                ]
            ],
            'fields' => 'ids',
            'posts_per_page' => -1
        ]);

        $count = $query->found_posts;

        echo '<li><strong>' . esc_html($status) . ':</strong> ' . esc_html($count) . '</li>';
    }

    echo '</ul>';
}