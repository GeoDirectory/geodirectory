<?php
/**
 * Function includes, this file calls all the separate function files
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
 
/**
 * Contains class & functions for geodirectory session usage.
 *
 * @since 1.5.7
 */
include_once('geodirectory-functions/geodir-class-session.php');

/**
 * Contains helper functions used to make like easier for theme/plugin devs.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/helper_functions.php');
/**
 * Contains functions used for user info.
 *
 * @since 1.5.9
 */
include_once('geodirectory-functions/user_functions.php');
/**
 * Contains functions used for ajax calls.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/ajax_handler_functions.php');
/**
 * Contains non specific general functions used by the plugin.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/general_functions.php');
/**
 * Contains functions used by hooks and actions.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/custom_functions.php');
/**
 * Contains functions/filters/hooks mostly used to alter the database queries.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/listing_filters.php');
/**
 * Contains functions for calling the templates for the plugin.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/template_functions.php');
/**
 * Contains functions for registration and sign in.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/signup_function.php');
/**
 * Contains functions that are specifically related to post output page.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/post_functions.php');
/**
 * Contains functions that are specifically related to taxonomy output pages (category and tags).
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/taxonomy_functions.php');
/**
 * Contains functions outputting the custom fields html inputs.
 *
 * @since 1.6.6
 */
include_once('geodirectory-functions/custom_fields_input_functions.php');
/**
 * Contains functions outputting the custom fields html.
 *
 * @since 1.6.6
 */
include_once('geodirectory-functions/custom_fields_output_functions.php');
/**
 * Contains functions for predefined custom fields.
 *
 * @since 1.6.9
 */
include_once('geodirectory-functions/custom_fields_predefined.php');
/**
 * Contains functions for building and storing custom fields.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/custom_fields_functions.php');
/**
 * Contains functions related to comments and reviews.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/comments_functions.php');
/**
 * Contains functions used for storing category meta information (cat icons, default image).
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/cat-meta-functions/cat_meta.php');
/**
 * Contains functions used for building and outputting google maps.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/map-functions/map_functions.php');
/**
 * Contains functions used determining locations and saving location information.
 *
 * @since 1.0.0
 */
include_once('geodirectory-functions/location_functions.php');
/**
 * Contains functions used for displaying Google analytics.
 *
 * @since 1.1.4
 */
include_once('geodirectory-functions/google_analytics.php');
/**
 * Contains functions used to call shortcodes.
 *
 * @since 1.3.7
 */
include_once('geodirectory_shortcodes.php');

global $wp_version;
// If curent WP Version >= 4.9.6.
if ( version_compare( $wp_version, '4.9.6', '>=' ) ) {
	include_once( 'geodirectory-functions/privacy/abstract-geodir-privacy.php' );
	include_once( 'geodirectory-functions/privacy/class-geodir-privacy.php' );
}