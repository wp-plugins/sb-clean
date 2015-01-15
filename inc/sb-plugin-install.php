<?php
function sb_clean_check_core() {
    $activated_plugins = get_option('active_plugins');
    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
    return $sb_core_installed;
}

function sb_clean_activation() {
    if(!current_user_can('activate_plugins')) {
        return;
    }
    do_action('sb_clean_activation');
}
register_activation_hook(SB_CLEAN_FILE, 'sb_clean_activation');

function sb_clean_check_admin_notices() {
    if(!sb_clean_check_core()) {
        unset($_GET['activate']);
        printf('<div class="error"><p><strong>' . __('Error', 'sb-clean') . ':</strong> ' . __('The plugin with name %1$s has been deactivated because of missing %2$s plugin', 'sb-clean') . '.</p></div>', '<strong>SB Clean</strong>', sprintf('<a target="_blank" href="%s" style="text-decoration: none">SB Core</a>', 'https://wordpress.org/plugins/sb-core/'));
        deactivate_plugins(SB_CLEAN_BASENAME);
    }
}
if(!empty($GLOBALS['pagenow']) && 'plugins.php' === $GLOBALS['pagenow']) {
    add_action('admin_notices', 'sb_clean_check_admin_notices', 0);
}

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