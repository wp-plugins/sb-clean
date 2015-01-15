<?php
require SB_CLEAN_INC_PATH . '/sb-plugin-install.php';
if(!sb_clean_check_core()) {
    return;
}
require SB_CLEAN_INC_PATH . '/sb-plugin-functions.php';

require SB_CLEAN_INC_PATH . '/sb-plugin-hook.php';

require SB_CLEAN_INC_PATH . '/sb-plugin-admin.php';

require SB_CLEAN_INC_PATH . '/sb-plugin-ajax.php';