<?php
function sb_clean_get_option() {
    if(class_exists('SB_Option')) {
        return SB_Option::get();
    } else {
        return get_option('sb_options');
    }
}

$options = sb_clean_get_option();

function sb_clean_wp_files() {
    $file_path = trailingslashit(ABSPATH) . 'readme.html';
    SB_PHP::delete_file($file_path);
    $file_path = trailingslashit(ABSPATH) . 'license.txt';
    SB_PHP::delete_file($file_path);
}

$result = isset($options['clean']['wpdb']) ? $options['clean']['wpdb'] : 1;
if((bool)$result) {
    unset($GLOBALS['wpdb']->dbpassword);
    unset($GLOBALS['wpdb']->dbname);
}

function sb_clean_default_image() {
    update_option('image_default_align', 'center');
    update_option('image_default_link_type', 'none');
    update_option('image_default_size', 'large');
}