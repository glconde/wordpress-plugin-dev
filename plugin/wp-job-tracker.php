<?php
/*
Plugin Name: WP Job Tracker
Description: A WordPress plugin for tracking job applications.
Version: 0.1.0
Author: George Louie Conde
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_notices', function () {
    echo '<div class="notice notice-success is-dismissible"><p>WP Job Tracker is active.</p></div>';
});

require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'admin/list-columns.php';