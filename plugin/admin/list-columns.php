<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_add_job_application_columns(array $columns): array
{
    $columns['wpjt_company'] = 'Company';
    $columns['wpjt_status'] = 'Status';

    return $columns;
}

add_filter('manage_job_application_posts_columns', 'wpjt_add_job_application_columns');

function wpjt_render_job_application_columns(string $column, int $post_id): void
{
    if ($column === 'wpjt_company') {
        echo esc_html(get_post_meta($post_id, '_wpjt_company', true));
    }

    if ($column === 'wpjt_status') {
        echo esc_html(get_post_meta($post_id, '_wpjt_status', true));
    }
}

add_action('manage_job_application_posts_custom_column', 'wpjt_render_job_application_columns', 10, 2);