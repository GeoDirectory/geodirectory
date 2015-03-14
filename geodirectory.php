<?php
/* 
Plugin Name: GeoDirectory
Plugin URI: http://wpgeodirectory.com/
Description: GeoDirectory plugin for wordpress.
Version: 1.4.0
Author: GeoDirectory
Author URI: http://wpgeodirectory.com
Requires at least: 3.1
Tested up to: 4.1.1
*/

define("GEODIRECTORY_VERSION", "1.4.0");

if (!session_id()) session_start();

///CHECK FOR OLD COMPATIBILITY PACKS AND DISABLE IF THEY ARE ACTIVE
if (is_admin()) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    if (is_plugin_active('geodirectory-genesis-compatibility-pack/geodir_genesis_compatibility.php')) {
        deactivate_plugins('geodirectory-genesis-compatibility-pack/geodir_genesis_compatibility.php');
    }

    if (is_plugin_active('geodirectory-x-theme-compatibility-pack/geodir_x_compatibility.php')) {
        deactivate_plugins('geodirectory-x-theme-compatibility-pack/geodir_x_compatibility.php');
    }

    if (is_plugin_active('geodirectory-enfold-theme-compatibility-pack/geodir_enfold_compatibility.php')) {
        deactivate_plugins('geodirectory-enfold-theme-compatibility-pack/geodir_enfold_compatibility.php');
    }

    if (is_plugin_active('geodir_avada_compatibility/geodir_avada_compatibility.php')) {
        deactivate_plugins('geodir_avada_compatibility/geodir_avada_compatibility.php');
    }

    if (is_plugin_active('geodir_compat_pack_divi/geodir_divi_compatibility.php')) {
        deactivate_plugins('geodir_compat_pack_divi/geodir_divi_compatibility.php');
    }

}


/**
 * Global
 **/
global $wpdb, $plugin_prefix, $geodir_addon_list, $plugin_file_name;
$plugin_prefix = $wpdb->prefix . 'geodir_';

$plugin_file_name = basename(plugin_dir_path(__FILE__)) . '/' . basename(__FILE__);
/**
 * Constants
 **/
if (!defined('WP_POST_REVISIONS'))
    define('WP_POST_REVISIONS', 0);

$geodir_post_custom_fields_cache = array();// This will store the cached post custome fields per package for each page load so not to run for each listing

// depreciated //if (!defined('GEODIRECTORY_TEMPLATE_URL')) define('GEODIRECTORY_TEMPLATE_URL', 'geodirectory/');	

/* ---- Table Names ---- */
if (!defined('GEODIR_COUNTRIES_TABLE')) define('GEODIR_COUNTRIES_TABLE', $plugin_prefix . 'countries');
if (!defined('GEODIR_CUSTOM_FIELDS_TABLE')) define('GEODIR_CUSTOM_FIELDS_TABLE', $plugin_prefix . 'custom_fields');
if (!defined('GEODIR_ICON_TABLE')) define('GEODIR_ICON_TABLE', $plugin_prefix . 'post_icon');
if (!defined('GEODIR_ATTACHMENT_TABLE')) define('GEODIR_ATTACHMENT_TABLE', $plugin_prefix . 'attachments');
if (!defined('GEODIR_REVIEW_TABLE')) define('GEODIR_REVIEW_TABLE', $plugin_prefix . 'post_review');
if (!defined('GEODIR_CUSTOM_SORT_FIELDS_TABLE')) define('GEODIR_CUSTOM_SORT_FIELDS_TABLE', $plugin_prefix . 'custom_sort_fields');


// disable cache on homepage
if ($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') {
    define('DONOTCACHEPAGE', TRUE);
}

/**
 * Localisation
 **/
if (!defined('GEODIRECTORY_TEXTDOMAIN')) define('GEODIRECTORY_TEXTDOMAIN', 'geodirectory');
$locale = apply_filters('plugin_locale', get_locale(), GEODIRECTORY_TEXTDOMAIN);
load_textdomain(GEODIRECTORY_TEXTDOMAIN, WP_LANG_DIR . '/' . GEODIRECTORY_TEXTDOMAIN . '/' . GEODIRECTORY_TEXTDOMAIN . '-' . $locale . '.mo');
load_plugin_textdomain(GEODIRECTORY_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/geodirectory-languages');
require_once('language.php'); // Define language constants


/**
 * Include core files
 **/
include_once('geodirectory_functions.php');
include_once('geodirectory_hooks_actions.php');
include_once('geodirectory_widgets.php');
include_once('geodirectory_template_tags.php');
include_once('geodirectory_template_actions.php');


/**
 * Admin init + activation hooks
 **/
if (is_admin()) :

    require_once('geodirectory-admin/admin_functions.php');
    require_once('geodirectory-admin/admin_hooks_actions.php');
    require_once('geodirectory-admin/admin_template_tags.php');
    require_once(geodir_plugin_path() . '/upgrade.php');
    if (get_option('geodir_installed') != 1) {
        require_once('geodirectory-admin/admin_install.php');
        register_activation_hook(__FILE__, 'geodir_activation');
    }
    register_deactivation_hook(__FILE__, 'geodir_deactivation');
    register_uninstall_hook(__FILE__, 'geodir_uninstall');

endif;
