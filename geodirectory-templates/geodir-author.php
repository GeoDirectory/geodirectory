<?php
/**
 * Template for the author page
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */

// get header
get_header();

###### WRAPPER OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_open', 'author-page', 'geodir-wrapper', '');

###### TOP CONTENT ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_top_content', 'author-page');
/**
 * Calls the top section widget area and the breadcrumbs on the author page.
 *
 * @since 1.1.0
 */
do_action('geodir_author_before_main_content');
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_before_main_content', 'author-page');

/**
 * Adds the title to the author page.
 *
 * This action adds the title to the author page.
 *
 * @since 1.1.0
 */
do_action('geodir_author_page_title');
// action, author page description
/**
 * Called after the page title, can add a description to the page.
 *
 * @since 1.1.0
 */
do_action('geodir_author_page_description');


###### SIDEBAR ######
/**
 * Adds the author page left sidebar to the author template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_author_sidebar_left');

###### MAIN CONTENT WRAPPERS OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_open', 'author-page', 'geodir-wrapper-content', '');


###### MAIN CONTENT ######
/**
 * Calls the author page content on the author template page.
 *
 * @since 1.1.0
 */
do_action('geodir_author_content');


###### MAIN CONTENT WRAPPERS CLOSE ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_close', 'author-page');

###### SIDEBAR ######
/**
 * Adds the author page right sidebar to the author template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_author_sidebar_right');

###### WRAPPER CLOSE ######	
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_close', 'author-page');

###### BOTTOM SECTION WIDGET AREA ######
/**
 * Adds the author page bottom widget area to the author template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_sidebar_author_bottom_section');

get_footer();  