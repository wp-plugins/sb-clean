<?php
$options = sb_clean_get_option();

function sb_clean_media_file_name($filename) {
    $filename = SB_PHP::remove_vietnamese(SB_PHP::lowercase($filename));
    return $filename;
}
add_filter('sanitize_file_name', 'sb_clean_media_file_name', 10);

function sb_clean_custom_activation() {
    sb_clean_wp_files();
    sb_clean_default_image();
}
add_action('sb_clean_activation', 'sb_clean_custom_activation');

function sb_clean_self_ping(&$links) {
    $home = get_option('home');
    foreach($links as $l => $link) {
        if( 0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'sb_clean_self_ping');

function sb_clean_shortcode_from_excerpt($excerpt) {
    $excerpt = trim(preg_replace('|\[(.+?)\](.+?\[/\\1\])?|s', '', $excerpt));
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = trim(trim($excerpt, '&nbsp;'));
    $excerpt = wpautop($excerpt);
    return trim($excerpt);
}
add_filter('the_excerpt', 'sb_clean_shortcode_from_excerpt');

function sb_clean_pre_upload_file( $file ){
    $file['name'] = SB_PHP::remove_vietnamese(SB_PHP::lowercase($file['name']));
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'sb_clean_pre_upload_file' );

function sb_clean_default_image_sizes($sizes) {
    if(isset($sizes['thumbnail'])) unset($sizes['thumbnail']);
    if(isset($sizes['medium'])) unset($sizes['medium']);
    if(isset($sizes['large'])) unset($sizes['large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sb_clean_default_image_sizes');

function sb_clean_autop_in_shortcode() {
    remove_filter('the_content', 'wpautop');
    add_filter('the_content', 'wpautop' , 99);
    add_filter('the_content', 'shortcode_unautop', 100);
}
sb_clean_autop_in_shortcode();

$result = isset($options['clean']['head_meta']) ? $options['clean']['head_meta'] : 1;
if((bool)$result) {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}

function sb_clean_admin_style_and_script() {
    wp_enqueue_script('sb-clean-admin', SB_CLEAN_URL . '/js/sb-clean-admin-script.js', array('jquery'), false, true);
}
add_action('admin_enqueue_scripts', 'sb_clean_admin_style_and_script');