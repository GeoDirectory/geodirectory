<?php
/**
 * Template for the success page after submitting a listing
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */

// call header
get_header();

###### WRAPPER OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_open', 'success-page', 'geodir-wrapper', '');

###### TOP CONTENT ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_top_content', 'success-page');

/**
 * Calls the top section widget area and the breadcrumbs on the add listing success page.
 *
 * @since 1.1.0
 */
do_action('geodir_success_before_main_content');

/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_before_main_content', 'success-page');

###### MAIN CONTENT WRAPPERS OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_open', 'success-page', 'geodir-wrapper-content', '');


###### MAIN CONTENT ######
/** This action is documented in geodirectory-templates/geodir-home.php */
do_action('geodir_add_page_content', 'before', 'success-page');

// this call the main page content
geodir_get_template_part('preview', 'success');

/** This action is documented in geodirectory-templates/geodir-home.php */
do_action('geodir_add_page_content', 'after', 'success-page');


###### MAIN CONTENT WRAPPERS CLOSE ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_after_main_content');
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_close', 'details-page');


###### SIDEBAR ######
/**
 * Adds the author page right sidebar to the success template page.
 *
 * @since 1.6.5
 */
do_action('geodir_author_sidebar_right');


###### WRAPPER CLOSE ######	
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_close', 'success-page');

get_footer();