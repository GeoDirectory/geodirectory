<?php
/**
 * This is the main GeoDirectory plugin file, here we declare and call the important stuff
 *
 * @package     GeoDirectory
 * @copyright   2016 AyeCode Ltd
 * @license     GPL-2.0+
 * @since       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: GeoDirectory
 * Plugin URI: https://wpgeodirectory.com/
 * Description: GeoDirectory plugin for wordpress.
 * Version: 1.6.23
 * Author: GeoDirectory
 * Author URI: https://wpgeodirectory.com
 * Text Domain: geodirectory
 * Domain Path: /geodirectory-languages
 * Requires at least: 3.1
 * Tested up to: 4.8
 */

/**
 * The current version number of GeoDirectory.
 *
 * @since 1.0.0
 */
define("GEODIRECTORY_VERSION", "1.6.23");

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

/**
 * Declare some global variables for later use.
 *
 * @since 1.0.0
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 * @global string $plugin_file_name Base file name. 'geodirectory/geodirectory.php'.
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

/**
 * Define constants
 */
if(!defined('GEODIRECTORY_PLUGIN_DIR')) define('GEODIRECTORY_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

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

/*
 * Define our Google Analytic app settings
 */
if (!defined('GEODIR_GA_CLIENTID')) define('GEODIR_GA_CLIENTID', '687912069872-sdpsjssrdt7t3ao1dnv1ib71hkckbt5s.apps.googleusercontent.com');
if (!defined('GEODIR_GA_CLIENTSECRET')) define('GEODIR_GA_CLIENTSECRET', 'yBVkDpqJ1B9nAETHy738Zn8C'); //don't worry - this don't need to be secret in our case
if (!defined('GEODIR_GA_REDIRECT')) define('GEODIR_GA_REDIRECT', 'urn:ietf:wg:oauth:2.0:oob');
if (!defined('GEODIR_GA_SCOPE')) define('GEODIR_GA_SCOPE', 'https://www.googleapis.com/auth/analytics');//.readonly


/*
 * Localisation items.
 */
if (!defined('GEODIRECTORY_TEXTDOMAIN')) define('GEODIRECTORY_TEXTDOMAIN', 'geodirectory');

// Load geodirectory plugin textdomain.
add_action( 'init', 'geodir_load_textdomain' );

/*
 * A function to log GD errors no matter the type given.
 *
 * This function will log GD errors if the WP_DEBUG constant is true, it can be filtered.
 *
 * @since 1.5.7
 * @param mixed $log The thing that should be logged.
 * @package GeoDirectory
 */
function geodir_error_log($log){
    /*
     * A filter to override the WP_DEBUG setting for function geodir_error_log().
     *
     * @since 1.5.7
     */
    $should_log = apply_filters( 'geodir_log_errors', WP_DEBUG);
    if ( true === $should_log ) {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}
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
if (is_admin() || defined( 'GD_TESTING_MODE' ) || ( defined( 'WP_CLI' ) && WP_CLI )) {

    /**
     * Include functions used in admin area only.
     *
     * @since 1.0.0
     */
    require_once('geodirectory-admin/admin_functions.php');
    /**
     * Most actions/hooks used in admin area only are called from here.
     *
     * @since 1.6.11
     */
    require_once('geodirectory-admin/admin_dummy_data_functions.php');
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
     * Include Google Analytics Class.
     *
     * @since 1.6.11
     */
    require_once('geodirectory-admin/class.analytics.stats.php');
    /**
     * Include any functions needed for upgrades.
     *
     * @since 1.0.0
     */
    require_once(geodir_plugin_path() . '/upgrade.php');
    if (get_option('geodir_installed') != 1) {
        /**
         * Define language constants, here as they are not loaded yet.
         *
         * @since 1.0.0
         */
        require_once(geodir_plugin_path() . '/language.php');
        /**
         * Include the plugin install file that sets up the databases and any options on first run.
         *
         * @since 1.0.0
         */
        require_once('geodirectory-admin/admin_install.php');
        register_activation_hook(__FILE__, 'geodir_activation');
    }
    register_deactivation_hook(__FILE__, 'geodir_deactivation');

    /*
     * Show a upgrade warning message if applicable.
     *
     * @since 1.5.6
     */
    global $pagenow;
    if ( 'plugins.php' === $pagenow )
    {
        // Better update message
        $file   = basename( __FILE__ );
        $folder = basename( dirname( __FILE__ ) );
        $hook = "in_plugin_update_message-{$folder}/{$file}";
        add_action( $hook, 'geodire_admin_upgrade_notice', 20, 2 );
    }

}