<?php
/*
Plugin Name: SB Clean
Plugin URI: http://hocwp.net/
Description: SB Clean is a plugin that allows to clean up your WordPress site.
Author: SB Team
Version: 1.0.8
Author URI: http://hocwp.net/
Text Domain: sb-clean
Domain Path: /languages/
*/

if(defined('SB_THEME_VERSION') && version_compare(SB_THEME_VERSION, '1.7.0', '>=')) {
    return;
}

define('SB_CLEAN_FILE', __FILE__);

define('SB_CLEAN_PATH', untrailingslashit(plugin_dir_path(SB_CLEAN_FILE)));

define('SB_CLEAN_URL', plugins_url('', SB_CLEAN_FILE));

define('SB_CLEAN_INC_PATH', SB_CLEAN_PATH . '/inc');

define('SB_CLEAN_BASENAME', plugin_basename(SB_CLEAN_FILE));

define('SB_CLEAN_DIRNAME', dirname(SB_CLEAN_BASENAME));

require SB_CLEAN_INC_PATH . '/sb-plugin-load.php';