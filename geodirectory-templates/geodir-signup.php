<?php
/**
 * Template for the GD register/signup page
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */

/*
 * If user is not signed in, redirect home or to redirect location.
 */
if (get_current_user_id()) {

    $_redirect = home_url();
    if (isset($_REQUEST['redirect_add_listing'])) {
        $_redirect = $_REQUEST['redirect_add_listing'];
    } elseif (!empty($_REQUEST['redirect_to'])) {
        $_redirect = $_REQUEST['redirect_to'];
    }

    wp_safe_redirect($_redirect , 302);
    exit;
}

// call header
get_header();

###### WRAPPER OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_open', 'signup-page', 'geodir-wrapper', '');

###### TOP CONTENT ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_top_content', 'signup-page');

/**
 * Calls the top section widget area and the breadcrumbs on the register/signin page.
 *
 * @since 1.1.0
 */
do_action('geodir_signin_before_main_content');

/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_before_main_content', 'signup-page');

###### MAIN CONTENT WRAPPERS OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_open', 'signup-page', 'geodir-wrapper-content', 'geodir-content-fullwidth');

/**
 * Adds the register/signin page top section widget area to the register/signin template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_sidebar_signup_top');

###### MAIN CONTENT ######
/** This action is documented in geodirectory-templates/geodir-home.php */
do_action('geodir_add_page_content', 'before', 'signup-page');

/**
 * Adds the register/signin page main content like the signin box and the register box to the register/signin template page.
 *
 * @since 1.1.0
 */
do_action('geodir_signup_forms');

/** This action is documented in geodirectory-templates/geodir-home.php */
do_action('geodir_add_page_content', 'after', 'signup-page');

###### MAIN CONTENT WRAPPERS CLOSE ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_close', 'signup-page');

###### WRAPPER CLOSE ######	
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_close', 'signup-page');

get_footer();