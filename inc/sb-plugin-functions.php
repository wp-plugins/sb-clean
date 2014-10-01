<?php
defined('ABSPATH') OR exit;

function sb_clean_check_core() {
    $activated_plugins = get_option('active_plugins');

    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);

    if(!$sb_core_installed) {
        $sb_plugins = array(SB_CLEAN_BASENAME);
        $activated_plugins = get_option('active_plugins');
        $activated_plugins = array_diff($activated_plugins, $sb_plugins);
        update_option('active_plugins', $activated_plugins);
    }
    return $sb_core_installed;
}

sb_clean_check_core();

function sb_clean_activation() {
    if(!sb_clean_check_core()) {
        wp_die(sprintf(__('You must install plugin %1$s first! Click here to %2$s.', 'sb-clean'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a href="%1$s">%2$s</a>', admin_url('plugins.php'), __('go back', 'sb-clean'))));
    }
    do_action('sb_clean_activation');
}
register_activation_hook( SB_CLEAN_FILE, 'sb_clean_activation' );

function sb_clean_settings_link($links) {
    if(sb_clean_check_core()) {
        $settings_link = sprintf('<a href="admin.php?page=sb_clean">%s</a>', __('Settings', 'sb-clean'));
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links_' . SB_CLEAN_BASENAME, 'sb_clean_settings_link');

function sb_clean_textdomain() {
    load_plugin_textdomain('sb-clean', false, SB_CLEAN_DIRNAME . '/languages/');
}
add_action('plugins_loaded', 'sb_clean_textdomain');

$options = get_option('sb_options');

function sb_clean_wp_files() {
    $file_path = trailingslashit(ABSPATH) . 'readme.html';
    SB_PHP::delete_file($file_path);
    $file_path = trailingslashit(ABSPATH) . 'license.txt';
    SB_PHP::delete_file($file_path);
}

$result = isset($options["clean"]["head_meta"]) ? $options["clean"]["head_meta"] : 1;
if((bool)$result) {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}

$result = isset($options["clean"]["wpdb"]) ? $options["clean"]["wpdb"] : 1;
if((bool)$result) {
    unset($GLOBALS['wpdb']->dbpassword);
    unset($GLOBALS['wpdb']->dbname);
}

function sb_clean_default_image() {
    update_option('image_default_align', 'center' );
    update_option('image_default_link_type', 'none' );
    update_option('image_default_size', 'large' );
}

function sb_clean_media_file_name($filename) {
    $filename = SB_PHP::remove_vietnamese(SB_PHP::lowercase($filename));
    return $filename;
}
add_filter('sanitize_file_name', 'sb_clean_media_file_name', 10);

function sb_clean_pre_upload_file( $file ){
    $file['name'] = SB_PHP::remove_vietnamese(SB_PHP::lowercase($file['name']));
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'sb_clean_pre_upload_file' );

function sb_clean_default_image_sizes( $sizes ) {
    if(isset($sizes['thumbnail'])) unset( $sizes['thumbnail'] );
    if(isset($sizes['medium'])) unset( $sizes['medium'] );
    if(isset($sizes['large'])) unset( $sizes['large'] );
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sb_clean_default_image_sizes');

function sb_clean_autop_in_shortcode() {
    remove_filter( 'the_content', 'wpautop' );
    add_filter( 'the_content', 'wpautop' , 99);
    add_filter( 'the_content', 'shortcode_unautop',100 );
}
sb_clean_autop_in_shortcode();

function sb_clean_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, $home ) ) {
            unset($links[$l]);
        }
    }
}
add_action( 'pre_ping', 'sb_clean_self_ping' );

function sb_clean_shortcode_from_excerpt( $excerpt ) {
    $excerpt = trim(preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $excerpt));
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = trim(trim($excerpt, '&nbsp;'));
    $excerpt = wpautop($excerpt);
    return trim($excerpt);
}
add_filter( 'the_excerpt', 'sb_clean_shortcode_from_excerpt' );

function sb_clean_custom_activation() {
    sb_clean_wp_files();
    sb_clean_default_image();
}
add_action('sb_clean_activation', 'sb_clean_custom_activation');

require SB_CLEAN_INC_PATH . '/sb-plugin-load.php';