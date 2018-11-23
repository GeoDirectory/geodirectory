<?php
/**
 * Genesis theme compatibility functions.
 *
 * This file lets the GeoDirectory Plugin use the Genesis theme HTML wrappers to fit and work perfectly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
add_action('after_setup_theme', 'gd_compat_php_genesis', 11);
/**
 * Action calls for genesis theme compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_php_genesis()
{
// REPLACE GENESIS BREADCRUMBS WITH GD BREADCRUMBS
    remove_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_home_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_location_before_main_content', 'geodir_breadcrumb', 20);


    // make top section wide
    remove_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
    remove_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
    remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
    remove_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);

    // REMOVE PAGE TITLES
    remove_action('geodir_listings_page_title', 'geodir_action_listings_title', 10);
    remove_action('geodir_search_page_title', 'geodir_action_search_page_title', 10);
    remove_action('geodir_author_page_title', 'geodir_action_author_page_title', 10);


}

add_action('genesis_after_header', 'geodir_replace_breadcrumb', 20);

add_action('genesis_after_header', 'gd_genesis_compat_left_sidebars', 5);
/**
 * Left sidebar compatibility actions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_genesis_compat_left_sidebars()
{

    if (is_page_geodir_home() || geodir_is_page('home')) {
        remove_action('geodir_home_sidebar_left', 'geodir_action_home_sidebar_left', 10);
        add_action('geodir_wrapper_close', 'geodir_action_home_sidebar_left', 11);
    } elseif (geodir_is_page('location')) {
        remove_action('geodir_location_sidebar_left', 'geodir_action_home_sidebar_left', 10);
        add_action('geodir_wrapper_close', 'geodir_action_home_sidebar_left', 11);
    } elseif (geodir_is_page('listing')) {
        remove_action('geodir_listings_sidebar_left', 'geodir_action_listings_sidebar_left', 10);
        add_action('geodir_wrapper_close', 'geodir_action_listings_sidebar_left', 11);
    } elseif (geodir_is_page('detail') && get_option('geodir_detail_sidebar_left_section')) {
        //remove_action( 'geodir_detail_sidebar', 'geodir_action_details_sidebar', 10 );
        //add_action( 'geodir_wrapper_close', 'geodir_action_details_sidebar', 11 );
    } elseif (geodir_is_page('search')) {
        remove_action('geodir_search_sidebar_left', 'geodir_action_search_sidebar_left', 10);
        add_action('geodir_wrapper_close', 'geodir_action_search_sidebar_left', 11);
    } elseif (geodir_is_page('author')) {
        remove_action('geodir_author_sidebar_left', 'geodir_action_author_sidebar_left', 10);
        add_action('geodir_wrapper_close', 'geodir_action_author_sidebar_left', 11);
    }


}


add_filter('body_class', 'geodir_set_body_scs', 100);
/**
 * Add body class for styling purposes.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $classes Class array.
 * @return array Modified class array.
 */
function geodir_set_body_scs($classes)
{
    $remove_class = false;
    $new_class = '';
    if (is_page_geodir_home() || geodir_is_page('location') || geodir_is_page('home')) {
        $remove_class = true;
        if (get_option('geodir_show_home_left_section')) {
            $new_class .= 'sidebar-';
        }
        if (get_option('geodir_show_home_contant_section')) {
            $new_class .= 'content';
        }
        if (get_option('geodir_show_home_right_section')) {
            $new_class .= '-sidebar';
        }
    } elseif (geodir_is_page('listing')) {
        $remove_class = true;
        if (get_option('geodir_show_listing_left_section')) {
            $new_class .= 'sidebar-';
        }
        $new_class .= 'content';
        if (get_option('geodir_show_listing_right_section')) {
            $new_class .= '-sidebar';
        }
    } elseif (geodir_is_page('detail')) {
        $remove_class = true;
        if (get_option('geodir_detail_sidebar_left_section')) {
            $new_class .= 'sidebar-content gd-details-sidebar-left';
        } else {
            $new_class .= 'content-sidebar';
        }
    } elseif (geodir_is_page('search')) {
        $remove_class = true;
        if (get_option('geodir_show_search_left_section')) {
            $new_class .= 'sidebar-';
        }
        $new_class .= 'content';
        if (get_option('geodir_show_search_right_section')) {
            $new_class .= '-sidebar';
        }
    } elseif (geodir_is_page('author')) {
        $remove_class = true;
        if (get_option('geodir_show_author_left_section')) {
            $new_class .= 'sidebar-';
        }
        $new_class .= 'content';
        if (get_option('geodir_show_author_right_section')) {
            $new_class .= '-sidebar';
        }
    } elseif (geodir_is_page('add-listing')) {
        $remove_class = true;
        $new_class .= 'content-sidebar';
    }

    if ($remove_class) {
        $classes = array_diff($classes, array('content-sidebar', 'sidebar-content', 'content-sidebar-sidebar', 'sidebar-sidebar-content', 'sidebar-content-sidebar', 'full-width-content'));
        if ( $new_class == 'content' ) {
            $new_class = 'content-no-sidebar';
            $classes[] = 'full-width-content';
        }
        $classes[] = $new_class;
    }

    return $classes;
}

add_action('genesis_after_header', 'gd_genesis_compat_add_top_section_back', 11);
/**
 * Adds top section based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_genesis_compat_add_top_section_back()
{

    if (is_page_geodir_home() || geodir_is_page('location') || geodir_is_page('home')) {
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

/**
 * replace genesis breadcrumbs function.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_replace_breadcrumb()
{
    if (is_front_page() && geodir_is_page('home') && !geodir_is_page('login')) {
    } else {
        echo '<div class="geodir-breadcrumb-bar"><div class="wrap">';
        geodir_breadcrumb();
        echo '</div></div>';
    }
}

// Force Full Width on signup page
add_action('genesis_meta', 'geodir_genesis_meta');
/**
 * force full width layout on signup page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_genesis_meta()
{
    if (geodir_is_page('login')) {
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
    }
}

add_action('geodir_add_listing_page_title', 'geodir_add_listing_page_title_genesis_before', 8);
/**
 * add listing page title before wrapper.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_add_listing_page_title_genesis_before()
{

    echo "<div class='entry' >";
}


add_action('geodir_add_listing_form', 'geodir_add_listing_form_genesis_after', 20);
/**
 * add listing page title after wrapper.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_add_listing_form_genesis_after()
{

    echo "</div>";
}


add_action('geodir_signup_forms', 'geodir_add_listing_page_title_genesis_before', 8);
add_action('geodir_signup_forms', 'geodir_add_listing_form_genesis_after', 20);


//add_action( 'genesis_after_header', 'gd_genesis_current_page_title_bar', 25 );
/**
 * Current page title bar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_genesis_current_page_title_bar()
{


    $title = '';
    $subtitle = '';

    if (geodir_is_page('listing')) {
        echo '<div class="wrap gd-title-wrap">';
        geodir_action_listings_title();
        echo '</div>';
    }

    if (geodir_is_page('add-listing')) {
        echo '<div class="wrap gd-title-wrap">';
        geodir_action_add_listing_page_title();
        echo '</div>';
    }

    if (geodir_is_page('author')) {
        echo '<div class="wrap gd-title-wrap">';
        geodir_action_author_page_title();
        echo '</div>';
    }

    if (geodir_is_page('detail') || geodir_is_page('preview')) {
        echo '<div class="wrap gd-title-wrap">';
        echo get_the_title();
        echo '</div>';
    }

    if (geodir_is_page('search')) {
        echo '<div class="wrap gd-title-wrap">';
        geodir_action_search_page_title();
        echo '</div>';
    }
}


add_action('geodir_before_listing', 'gd_genesis_listing_page_title_bar', 9);
/**
 * Listing page title bar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_genesis_listing_page_title_bar()
{
    geodir_action_listings_title();
    //geodir_action_listings_description();
}


add_action('after_setup_theme', 'gd_compat_php_genesis_geo_1280_fix', 11);
/**
 * fix for geo-1280.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_php_genesis_geo_1280_fix()
{
    if (function_exists('geo1280_search_bar')) {
        remove_action('genesis_after_header', 'geo1280_search_bar', 20);
        add_action('genesis_after_header', 'geo1280_search_bar_fix', 4);

        //

        remove_action('genesis_after_header', 'geodir_replace_breadcrumb', 20);
        remove_action('genesis_before_content_sidebar_wrap', 'geodir_replace_breadcrumb', 20);
        add_action('geodir_wrapper_open', 'geodir_replace_breadcrumb', 105);

        remove_action('genesis_before_content_sidebar_wrap', 'geo1280_page_title', 10);
        add_action('geodir_wrapper_open', 'geo1280_page_title', 101);
    }
}

/**
 * fix for geo-1280 search bar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geo1280_search_bar_fix()
{

    echo '<div class="geo1280-placeholder"></div>';
    if (is_active_sidebar('search-bar')) {
        genesis_widget_area('search-bar', array(
            'before' => '<div class="search-bar widget-area"><div class="wrap">',
            'after' => '</div></div>',
        ));
    }
}
