<?php
/**
 * Template for the details (post) page
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */

header("X-XSS-Protection: 0"); // IE requirement
// call header
get_header();

###### WRAPPER OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_open', 'details-page', 'geodir-wrapper', '');

###### TOP CONTENT ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_top_content', 'details-page');

/**
 * Calls the top section widget area and the breadcrumbs on the details page.
 *
 * @since 1.1.0
 */
do_action('geodir_detail_before_main_content');

/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_before_main_content', 'details-page');

###### SIDEBAR ON LEFT ######
if (get_option('geodir_detail_sidebar_left_section')) {
    /**
     * Adds the details page sidebar to the details template page.
     *
     * @since 1.1.0
     */
    do_action('geodir_detail_sidebar');
}

###### MAIN CONTENT WRAPPERS OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_open', 'details-page', 'geodir-wrapper-content', '');

/**
 * Adds the opening HTML wrapper for the article on the details page.
 *
 * @since 1.1.0
 * @global object $post The current post object.
 * @global object $post_images Image objects of current post if available.
 * @param string $type Page type.
 * @param string $id The id of the HTML element.
 * @param string $class The class of the HTML element.
 * @param string $itemtype The itemtype value of the HTML element.
 * @see 'geodir_article_close'
 */
do_action('geodir_article_open', 'details-page', 'post-' . get_the_ID(), get_post_class(), 'http://schema.org/LocalBusiness');

###### MAIN CONTENT ######
// this call the main page content
if (have_posts() && !$preview) {
    the_post();
    global $post, $post_images;
    /**
     * Calls the details page main content on the details template page.
     *
     * @since 1.1.0
     * @param object $post The current post object.
     */
    do_action('geodir_details_main_content', $post);
} elseif ($preview) {
    /**
     * Called on the details page if the page is being previewed.
     *
     * This sets the value of `$post` to the preview values before the main content is called.
     *
     * @since 1.1.0
     */
    do_action('geodir_action_geodir_set_preview_post'); // set the $post to the preview values
    /** This action is documented in geodirectory-templates/listing-detail.php */
    do_action('geodir_details_main_content', $post);
}


###### MAIN CONTENT WRAPPERS CLOSE ######
/**
 * Adds the closing HTML wrapper for the article on the details page.
 *
 * @since 1.1.0
 * @param string $type Page type.
 * @see 'geodir_article_open'
 */
do_action('geodir_article_close', 'details-page');

/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_after_main_content');

/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_close', 'details-page');

###### SIDEBAR ON RIGHT ######
if (!get_option('geodir_detail_sidebar_left_section')) {
    /** This action is documented in geodirectory-templates/listing-detail.php */
    do_action('geodir_detail_sidebar');
}


###### WRAPPER CLOSE ######	
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_close', 'details-page');

###### BOTTOM SECTION WIDGET AREA ######
/**
 * Adds the details page bottom section widget area to the details template page.
 *
 * @since 1.1.0
 */
do_action('geodir_sidebar_detail_bottom_section', '');


get_footer();      