<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('pro_popup_enable_analytics');
delete_option('pro_popup_global_cookie_duration');
delete_option('pro_popup_disable_on_mobile');
delete_option('pro_popup_debug_mode');

// Delete custom post type posts
$popups = get_posts([
    'post_type' => 'pro_popup',
    'numberposts' => -1,
    'post_status' => 'any',
]);

foreach ($popups as $popup) {
    wp_delete_post($popup->ID, true);
}

// Delete database table
global $wpdb;
$table_name = $wpdb->prefix . 'pro_popup_stats';
$wpdb->query("DROP TABLE IF EXISTS $table_name");