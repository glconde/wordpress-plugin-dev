<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpjt_register_job_application_post_type(): void
{
    register_post_type('job_application', [
        'labels' => [
            'name' => 'Job Applications',
            'singular_name' => 'Job Application',
            'add_new_item' => 'Add New Job Application',
            'edit_item' => 'Edit Job Application',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'editor'],
        'show_in_rest' => true,
    ]);
}

add_action('init', 'wpjt_register_job_application_post_type');