<?php
/**
 * Avada theme compatibility functions.
 *
 * This file lets the GeoDirectory Plugin use the Avada theme HTML wrappers to fit and work perfectly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
add_filter('geodir_breadcrumb', 'gd_strip_breadcrumb_wrappers');
/**
 * strips the gd breadcrumb wrappers.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $breadcrumb Old breadcrumb HTML.
 * @return string Modified breadcrumb HTML.
 */
function gd_strip_breadcrumb_wrappers($breadcrumb)
{
    $breadcrumb = str_replace(array("<li>","</li>"), "", $breadcrumb);
    $breadcrumb = str_replace('<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs">', '<ul class="fusion-breadcrumbs"><li>', $breadcrumb);
    $breadcrumb = str_replace('</ul></div>', '</li></ul>', $breadcrumb);
    return $breadcrumb;
}

add_filter('geodir_breadcrumb_separator', 'gd_change_breadcrumb_separator');
/**
 * change the gd breadcrumb separator.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $separator The breadcrumb separator HTML.
 * @return string Modified breadcrumb separator HTML.
 */
function gd_change_breadcrumb_separator($separator)
{
    $separator = ' / ';
    return $separator;
}

add_action('avada_override_current_page_title_bar','gd_avada_current_page_title_bar_change');
/**
 * new title bar functions for gd pages.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $c_pageID Current page ID.
 */
function gd_avada_current_page_title_bar_change($c_pageID)
{
    if (geodir_is_geodir_page()) {
        gd_avada_current_page_title_bar();
    }else{
        avada_current_page_title_bar( $c_pageID );
    }

}

/**
 * Avada current page title bar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_avada_current_page_title_bar()
{
    ob_start();
    geodir_breadcrumb();
    $secondary_content = ob_get_contents();
    ob_get_clean();

    $title = '';
    $subtitle = '';

    if (geodir_is_page('listing')) {
        ob_start(); // Start buffering;
        geodir_action_listings_title();
        $title = ob_get_clean();
        avada_page_title_bar($title, $subtitle, $secondary_content);
    }

    if (geodir_is_page('add-listing')) {
        ob_start(); // Start buffering;
        geodir_action_add_listing_page_title();
        $title = ob_get_clean();
        avada_page_title_bar($title, $subtitle, $secondary_content);
    }

    if (geodir_is_page('author')) {
        ob_start(); // Start buffering;
        geodir_action_author_page_title();
        $title = ob_get_clean();
        avada_page_title_bar($title, $subtitle, $secondary_content);
    }
    

    if (geodir_is_page('detail') || geodir_is_page('preview')) {
        if ( $title = get_post_meta( get_the_ID(), 'pyre_page_title_custom_text', true ) ) {}
        else {
            $title = get_the_title();
        }
        avada_page_title_bar($title, $subtitle, $secondary_content);
    }

    if (geodir_is_page('search')) {
        ob_start(); // Start buffering;
        geodir_action_search_page_title();
        $title = ob_get_clean();
        avada_page_title_bar($title, $subtitle, $secondary_content);
    }
}

/**
 * Action calls for avada theme compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_php_avada()
{
    // change widget wrappers
    //add_filter( 'geodir_before_widget', 'geodir_before_widget_compat',10,1 );
    //add_filter( 'geodir_after_widget', 'geodir_after_widget_compat',10,1 );

    // REMOVE BREADCRUMB
    remove_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_home_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_location_before_main_content', 'geodir_breadcrumb', 20);

    // REMOVE PAGE TITLES
    remove_action('geodir_listings_page_title', 'geodir_action_listings_title', 10);
    remove_action('geodir_add_listing_page_title', 'geodir_action_add_listing_page_title', 10);
    remove_action('geodir_details_main_content', 'geodir_action_page_title', 20);
    remove_action('geodir_search_page_title', 'geodir_action_search_page_title', 10);
    remove_action('geodir_author_page_title', 'geodir_action_author_page_title', 10);

    // make top section wide
    remove_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
    remove_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
    remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
    remove_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);

    //gd_compat_add_top_section_back();

}

add_action('avada_before_main', 'gd_compat_add_top_section_back', 10);
/**
 * Adds top section based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_add_top_section_back() {
    if (geodir_is_page('home') || geodir_is_page('location')) {
        geodir_action_geodir_sidebar_home_top();
    } elseif (geodir_is_page('listing')) {
        geodir_action_geodir_sidebar_listings_top();
    } elseif (geodir_is_page('detail')) {
        geodir_action_geodir_sidebar_detail_top();
    } elseif (geodir_is_page('search')) {
        geodir_action_geodir_sidebar_search_top();
    } elseif (geodir_is_page('author')) {
        geodir_action_geodir_sidebar_author_top();
    }
}

add_filter('body_class', 'gd_compat_body_class');
/**
 * Add body class for styling purposes.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $classes Class array.
 * @return array Modified class array.
 */
function gd_compat_body_class($classes)
{
    if (geodir_is_geodir_page()) {
        $classes[] = 'wpgeo-avada';
    } else {
        $classes[] = '';
    }
    return $classes;
}


/**
 * Avada before widget compatibility HTML.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $var Not being used.
 * @return string HTML.
 */
function geodir_before_widget_compat($var)
{
    return '<div id="%1$s" class="geodir-widget %2$s">';
}

/**
 * Avada after widget compatibility HTML.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $var Not being used.
 * @return string HTML.
 */
function geodir_after_widget_compat($var)
{
    return '</div>';
}

add_filter('geodir_search_form_class', 'geodir_search_form_class_avada');
/**
 * Avada search form class.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $class Class string.
 * @return string Appended class string.
 */
function geodir_search_form_class_avada($class)
{
    $class .= ' search';
    return $class;
}


// run Avada compat
gd_compat_php_avada();

// Avada sets the search page to use wither post or page, we need it to be 'any'
function gd_avada_search_filter( $query ) {
    if ( geodir_is_page('search') && is_search() && $query->is_search) {
            $query->set('post_type', 'any');
    }
    return $query;
}
if ( ! is_admin() ) {
    add_filter( 'pre_get_posts', 'gd_avada_search_filter',11 );
}