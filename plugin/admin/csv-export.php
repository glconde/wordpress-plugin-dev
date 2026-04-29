<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_register_csv_export_page(): void
{
    add_submenu_page(
        'edit.php?post_type=job_application',
        'Export Applications',
        'Export CSV',
        'manage_options',
        'wpjt-export-csv',
        'wpjt_render_csv_export_page'
    );
}

add_action('admin_menu', 'wpjt_register_csv_export_page');

function wpjt_render_csv_export_page(): void
{
    $export_url = wp_nonce_url(
        admin_url('admin-post.php?action=wpjt_export_csv'),
        'wpjt_export_csv'
    );

    echo '<div class="wrap">';
    echo '<h1>Export Job Applications</h1>';
    echo '<p>Download all published job applications as a CSV file.</p>';
    echo '<a class="button button-primary" href="' . esc_url($export_url) . '">Download CSV</a>';
    echo '</div>';
}

function wpjt_handle_csv_export(): void
{
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to export job applications.');
    }

    check_admin_referer('wpjt_export_csv');

    $query = new WP_Query([
        'post_type' => 'job_application',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=job-applications.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Title', 'Company', 'Status', 'Notes']);

    while ($query->have_posts()) {
        $query->the_post();

        fputcsv($output, [
            get_the_title(),
            get_post_meta(get_the_ID(), '_wpjt_company', true),
            get_post_meta(get_the_ID(), '_wpjt_status', true),
            wp_strip_all_tags(get_the_content()),
        ]);
    }

    wp_reset_postdata();

    fclose($output);
    exit;
}

add_action('admin_post_wpjt_export_csv', 'wpjt_handle_csv_export');