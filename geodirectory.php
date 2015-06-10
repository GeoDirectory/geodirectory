<?php
/**
 * This is the main GeoDirectory plugin file, here we declare and call the important stuff
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/* 
Plugin Name: GeoDirectory
Plugin URI: http://wpgeodirectory.com/
Description: GeoDirectory plugin for wordpress.
Version: 1.4.8
Author: GeoDirectory
Author URI: http://wpgeodirectory.com
Requires at least: 3.1
Tested up to: 4.2
*/

/**
 * The current version number of GeoDirectory.
 *
 * @since 1.0.0
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 * @global string $plugin_file_name Base file name. 'geodirectory/geodirectory.php'.
 */
define("GEODIRECTORY_VERSION", "1.4.8");

if (!session_id()) session_start();

/*
 * CHECK FOR OLD COMPATIBILITY PACKS AND DISABLE IF THEY ARE ACTIVE
 */
if (is_admin()) {
    /**
     * Include WordPress core file so we can use core functions to check for active plugins.
     */
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

/*
 * Declare some global variables for later use.
 */
global $wpdb, $plugin_prefix, $geodir_addon_list, $plugin_file_name;
$plugin_prefix = $wpdb->prefix . 'geodir_';
$plugin_file_name = basename(plugin_dir_path(__FILE__)) . '/' . basename(__FILE__);

/*
 * This will store the cached post custom fields per package for each page load so not to run for each listing.
 */
$geodir_post_custom_fields_cache = array();

/**
 * Do not store any revisions (except the one autosave per post).
 */
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 0);


/*
 * Declare database table names. All since version 1.0.0
 */

/** Define the database name for the countries table. */
if (!defined('GEODIR_COUNTRIES_TABLE')) define('GEODIR_COUNTRIES_TABLE', $plugin_prefix . 'countries');
/** Define the database name for the custom fields table. */
if (!defined('GEODIR_CUSTOM_FIELDS_TABLE')) define('GEODIR_CUSTOM_FIELDS_TABLE', $plugin_prefix . 'custom_fields');
/** Define the database name for the icons table. */
if (!defined('GEODIR_ICON_TABLE')) define('GEODIR_ICON_TABLE', $plugin_prefix . 'post_icon');
/** Define the database name for the attachments table. */
if (!defined('GEODIR_ATTACHMENT_TABLE')) define('GEODIR_ATTACHMENT_TABLE', $plugin_prefix . 'attachments');
/** Define the database name for the review table. */
if (!defined('GEODIR_REVIEW_TABLE')) define('GEODIR_REVIEW_TABLE', $plugin_prefix . 'post_review');
/** Define the database name for the custom sort fields table. */
if (!defined('GEODIR_CUSTOM_SORT_FIELDS_TABLE')) define('GEODIR_CUSTOM_SORT_FIELDS_TABLE', $plugin_prefix . 'custom_sort_fields');


if ($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') {
    /**
     * This tries to disable cache on homepage as it can be very dynamic.
     */
    define('DONOTCACHEPAGE', TRUE);
}


/*
 * Localisation items.
 */
if (!defined('GEODIRECTORY_TEXTDOMAIN')) define('GEODIRECTORY_TEXTDOMAIN', 'geodirectory');

// Load geodirectory plugin textdomain.
add_action( 'plugins_loaded', 'geodir_load_textdomain' );

/**
 * Include all plugin functions.
 *
 * @since 1.0.0
 */
include_once('geodirectory_functions.php');
/**
 * Most actions/hooks are called from here.
 *
 * @since 1.0.0
 */
include_once('geodirectory_hooks_actions.php');
/**
 * Include all plugin widgets.
 *
 * @since 1.0.0
 */
include_once('geodirectory_widgets.php');
/**
 * Most JS and CSS in added or enqueued from here.
 *
 * @since 1.0.0
 */
include_once('geodirectory_template_tags.php');
/**
 * Most of the plugins templates are added from here via hooks.
 *
 * @since 1.0.0
 */
include_once('geodirectory_template_actions.php');


/*
 * Admin init + activation hooks
 */
if (is_admin()) {

    /**
     * Include functions used in admin area only.
     *
     * @since 1.0.0
     */
    require_once('geodirectory-admin/admin_functions.php');
    /**
     * Most actions/hooks used in admin area only are called from here.
     *
     * @since 1.0.0
     */
    require_once('geodirectory-admin/admin_hooks_actions.php');
    /**
     * Most admin JS and CSS is called from here.
     *
     * @since 1.0.0
     */
    require_once('geodirectory-admin/admin_template_tags.php');
    /**
     * Include any functions needed for upgrades.
     *
     * @since 1.0.0
     */
    require_once(geodir_plugin_path() . '/upgrade.php');
    if (get_option('geodir_installed') != 1) {
        /**
         * Include the plugin install file that sets up the databases and any options on first run.
         *
         * @since 1.0.0
         */
        require_once('geodirectory-admin/admin_install.php');
        register_activation_hook(__FILE__, 'geodir_activation');
    }
    register_deactivation_hook(__FILE__, 'geodir_deactivation');
    register_uninstall_hook(__FILE__, 'geodir_uninstall');

}
