<?php

/*
  Plugin Name: DOT's Functionality
  Plugin URI: http://www.digitaldots.com.mm
  Description: All of the important functionality of this site belongs here.
  Version: 1.0
  Author: Digital Dots
  Author URI: http://www.digitaldots.com.mm
 */

define('THEME_FUNCTIONALITY_URL', plugin_dir_url(__FILE__));
define('THEME_FUNCTIONALITY_PATH', plugin_dir_path(__FILE__));
define('THEME_FUNCTIONALITY_BASENAME', plugin_basename(__FILE__));
define('ASSET_VERSION', '0.3');

/* * **************************
 * * THEME option and prefix **
 * *************************** */
define('THEME_PREFIX', '_dd_');
define('THEME_OPTIONS', THEME_PREFIX . 'options');
define('THEME_OPTION_GROUP', THEME_PREFIX . 'option_group');

//only for admin section
if (is_admin()) {
    include_once(THEME_FUNCTIONALITY_PATH . "admin/theme-options.php");
    //include_once(THEME_FUNCTIONALITY_PATH . "admin/custom-image-size.php");
}
//only for login page
elseif (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
    include_once(THEME_FUNCTIONALITY_PATH . "frontend/customise-login.php");
}
//for everything else
//include_once(THEME_FUNCTIONALITY_PATH . "admin/meta-boxes.class.php");

include_once(THEME_FUNCTIONALITY_PATH . "admin/customise-dashboard.php");
//include_once(THEME_FUNCTIONALITY_PATH . "admin/custom-post-type.class.php");
include_once(THEME_FUNCTIONALITY_PATH . "frontend/footer-script.php");
include_once(THEME_FUNCTIONALITY_PATH . "common/custom-functions.php");
//include_once(THEME_FUNCTIONALITY_PATH . "common/widget.class.php");
//include_once(THEME_FUNCTIONALITY_PATH . "common/lessc.inc.php");
//include_once(THEME_FUNCTIONALITY_PATH . "common/minifycss.php");
//include_once(THEME_FUNCTIONALITY_PATH . "admin/custom-meta-boxes/example-functions.php");




function theme_construct() {
//    if ( ! class_exists( 'cmb_Meta_Box' ) ){                
//		include_once(THEME_FUNCTIONALITY_PATH . "admin/custom-meta-boxes/init.php");
//        
//    }
    global $THEME_OPTIONS;
    $THEME_OPTIONS = get_option(THEME_OPTIONS);  	
}
add_action('init', 'theme_construct', 10);

/* add style/scripts */

function custom_admin_js_css() {
    wp_enqueue_script('custom-admin-js', THEME_FUNCTIONALITY_URL . 'assets/js/custom-admin.js', 'jquery', '', true);
}

add_action('admin_head', 'custom_admin_js_css');


######################## SECURITY ##############################################
// Protect WordPress Against Malicious URL Requests
// http://perishablepress.com/
################################################################################

global $user_ID;

if ($user_ID) {
    if (!current_user_can('level_10')) {
        if (strlen($_SERVER['REQUEST_URI']) > 255 ||
            strpos($_SERVER['REQUEST_URI'], "eval(") ||
            strpos($_SERVER['REQUEST_URI'], "CONCAT") ||
            strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
            strpos($_SERVER['REQUEST_URI'], "base64")) {
            @header("HTTP/1.1 414 Request-URI Too Long");
            @header("Status: 414 Request-URI Too Long");
            @header("Connection: Close");
            @exit;
        }
    }
}



