<?php
function sb_clean_post_revision_ajax_callback() {
    if(SB_User::is_admin()) {
        SB_Post::clean_all_revision();
    }
    die();
}
add_action('wp_ajax_sb_clean_post_revision', 'sb_clean_post_revision_ajax_callback');