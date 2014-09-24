<?php
/*
Plugin Name: SB Clean
Plugin URI: http://hocwp.net/
Description: SB Clean is a plugin that allows to clean up your WordPress site.
Author: SB Team
Version: 1.0.0
Author URI: http://hocwp.net/
*/

define("SB_CLEAN_PATH", untrailingslashit(plugin_dir_path( __FILE__ )));

function sb_clean_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=sb_clean">Settings</a>';
  array_unshift($links, $settings_link); 
  return $links; 
}
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'sb_clean_settings_link' );

require_once(SB_CLEAN_PATH."/admin/sb-admin.php");
require SB_CLEAN_PATH . "/sb-plugin-admin.php";

$options = get_option("sb_options");

function sb_clean_wp_files() {
    $file_path = trailingslashit(ABSPATH).'readme.html';
    SB_PHP::delete_file($file_path);
    $file_path = trailingslashit(ABSPATH).'license.txt';
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

function sb_clean_activate() {
    sb_clean_wp_files();
    sb_clean_default_image();
}
register_activation_hook( __FILE__, 'sb_clean_activate' );