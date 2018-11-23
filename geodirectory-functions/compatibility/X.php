<?php
/**
 * X theme compatibility functions.
 *
 * This file lets the GeoDirectory Plugin use the X theme HTML wrappers to fit and work perfectly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */


//return;

// call
add_action('after_setup_theme', 'geodir_x_action_calls', 11);
/**
 * Action calls for X theme compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_action_calls()
{

    /* ACTIONS
    ****************************************************************************************/

    // Add body class for styling purposes
    add_filter('body_class', 'geodir_x_body_class');

    // HOME TOP SIDEBAR
    //remove_action( 'geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10 );
    //remove_action( 'geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10 );
    //add_action( 'geodir_wrapper_open', 'geodir_x_home_sidebar', 5 );
    add_action('geodir_before_search_form', 'geodir_x_search_container_open');
    add_action('geodir_after_search_form', 'geodir_x_search_container_close');

    // WRAPPER OPEN ACTIONS
    remove_action('geodir_wrapper_open', 'geodir_action_wrapper_open', 10);
    add_action('geodir_wrapper_open', 'geodir_x_action_wrapper_open', 9);

    // WRAPPER CLOSE ACTIONS
    remove_action('geodir_wrapper_close', 'geodir_action_wrapper_close', 10);
    add_action('geodir_wrapper_close', 'geodir_x_action_wrapper_close', 11);

    // WRAPPER CONTENT OPEN ACTIONS
    remove_action('geodir_wrapper_content_open', 'geodir_action_wrapper_content_open', 10);
    add_action('geodir_wrapper_content_open', 'geodir_x_action_wrapper_content_open', 9, 3);

    // WRAPPER CONTENT CLOSE ACTIONS
    remove_action('geodir_wrapper_content_close', 'geodir_action_wrapper_content_close', 10);
    add_action('geodir_wrapper_content_close', 'geodir_x_action_wrapper_content_close', 11);

    // SIDEBAR RIGHT OPEN ACTIONS
    remove_action('geodir_sidebar_right_open', 'geodir_action_sidebar_right_open', 10);
    add_action('geodir_sidebar_right_open', 'geodir_x_action_sidebar_right_open', 10, 4);

    // SIDEBAR RIGHT CLOSE ACTIONS
    remove_action('geodir_sidebar_right_close', 'geodir_action_sidebar_right_close', 10);
    add_action('geodir_sidebar_right_close', 'geodir_x_action_sidebar_right_close', 10, 1);

    // REMOVE BREADCRUMBS
    remove_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_home_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_location_before_main_content', 'geodir_breadcrumb', 20);

    // make top section wide
    remove_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
    remove_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
    remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
    remove_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);

    add_action('geodir_wrapper_open', 'gd_X_compat_add_top_section_back', 5);


} // Close geodir_x_action_calls

/* FUNCTIONS
****************************************************************************************/

/**
 * Adds top section based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_X_compat_add_top_section_back()
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


// ADD BODY CLASS

/**
 * Add body class for styling purposes.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $classes Class array.
 * @return array Modified class array.
 */
function geodir_x_body_class($classes)
{
    $classes[] = 'geodir-x';

    if(isset($_REQUEST['geodir_search'])){
        $classes[] = 'geodir-x-search';
    }
    return $classes;
}

/**
 * replace gd home top sidebar after header.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 */
function geodir_x_home_sidebar()
{
    //if ( geodir_is_geodir_page() ) {
    global $wp;
    if ($wp->query_vars['page_id'] == geodir_location_page_id() || is_home() && !geodir_is_page('login')) {
        echo '<div class="x-main full">';
        dynamic_sidebar('geodir_home_top');
        echo '</div>';
    }
    //}
}

/**
 * add opening wrap to searchbar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_search_container_open()
{
    echo '<div class="x-container-fluid x-container max">';
}

/**
 * add closing wrap to searchbar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_search_container_close()
{
    echo '</div>';
}

/**
 * wrapper open functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_action_wrapper_open()
{
    $stack = x_get_stack();

    if ($stack == 'integrity') {
        echo '<div class="x-container-fluid x-container max width offset">';
    } elseif ($stack == 'renew') {
        echo '<div class="x-container-fluid x-container max width offset cf">';
    } elseif ($stack == 'icon') {
        echo '<div class="x-main full" role="main">';
    } elseif ($stack == 'ethos') {
        echo '<div class="x-container-fluid x-container max width main"><div class="offset cf">';
    }else{
        echo '<div class="x-container max width offset">';
    }
}

/**
 * wrapper close functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_action_wrapper_close()
{
    $stack = x_get_stack();
    if ($stack == 'ethos') {
        echo '</div></div>';
    } else {
        echo '</div>';
    }
}

/**
 * wrapper content open functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $type Page type.
 * @param string $id The id of the HTML element.
 * @param string $class The class of the HTML element.
 */
function geodir_x_action_wrapper_content_open($type = '', $id = '', $class = '')
{
    $content_class = ' entry-wrap ';

    echo '<div class="x-main left ' . $class . $content_class . ' " role="main">';
}

/**
 * wrapper content close functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_x_action_wrapper_content_close()
{
    echo '</div>';
}

/**
 * sidebar right open functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $type Page type.
 * @param string $id The id of the HTML element.
 * @param string $class The class of the HTML element.
 * @param string $itemtype HTML itemtype 'https://schema.org/WPSideBar'.
 */
function geodir_x_action_sidebar_right_open($type = '', $id = '', $class = '', $itemtype = '')
{
    echo '<aside class="x-sidebar right" role="complementary" itemscope itemtype="' . $itemtype . '">';
}

/**
 * sidebar right close functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $type Page type.
 */
function geodir_x_action_sidebar_right_close($type = '')
{
    echo '</aside>';
}

add_filter('geodir_breadcrumb', 'geodir_x_breadcrumb');
/**
 * modify breadcrumb.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $breadcrumb Breadcrumb HTML.
 * @return string Modified breadcrumb HTML.
 */
function geodir_x_breadcrumb($breadcrumb)
{
    $breadcrumb = str_replace('<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs">', '', $breadcrumb);
    $breadcrumb = str_replace('<li>', '', $breadcrumb);
    $breadcrumb = str_replace('</li>', '', $breadcrumb);
    $breadcrumb = str_replace('Home', '<span class="home"><i class="x-icon-home"></i></span>', $breadcrumb);
    $breadcrumb = str_replace('</ul></div>', '', $breadcrumb);
    return $breadcrumb;
}

add_filter('geodir_breadcrumb_separator', 'geodir_x_breadcrumb_separator');
/**
 * modify breadcrumb separator.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $separator The breadcrumb separator HTML.
 * @return string Modified breadcrumb separator HTML.
 */
function geodir_x_breadcrumb_separator($separator)
{
    $separator = str_replace(' > ', ' <span class="delimiter"><i class="x-icon-angle-right"></i></span> ', $separator);
    return $separator;
}

if (!function_exists('x_breadcrumbs')) :
    /**
     * breadcrumbs.
     *
     * @since 1.0.0
     * @package GeoDirectory
     */
    function x_breadcrumbs()
    {

        if (x_get_option('x_breadcrumb_display', '1')) {

            //
            // 1. Delimiter between crumbs.
            // 2. Output text for the "Home" link.
            // 3. Link to the home page.
            // 4. Tag before the current crumb.
            // 5. Tag after the current crumb.
            // 6. Get page title.
            // 7. Get blog title.
            // 8. Get shop title.
            //

            GLOBAL $post,$wp;

            if (geodir_is_page('detail') || geodir_is_page('listing') || (isset($wp->query_vars['page_id']) && $wp->query_vars['page_id'] == geodir_location_page_id())) {
                geodir_breadcrumb();
            } else {

                $stack = x_get_stack();
                $delimiter = ' <span class="delimiter"><i class="x-icon-angle-right"></i></span> '; // 1
                $home_text = '<span class="home"><i class="x-icon-home"></i></span>';               // 2
                $home_link = home_url();                                                            // 3
                $current_before = '<span class="current">';                                              // 4
                $current_after = '</span>';                                                             // 5
                $page_title = get_the_title();                                                       // 6
                $blog_title = get_the_title(get_option('page_for_posts', true));                 // 7
                $shop_title = get_theme_mod('x_' . $stack . '_shop_title');                        // 8

                if (function_exists('woocommerce_get_page_id')) {
                    $shop_url = x_get_shop_link();
                    $shop_link = '<a href="' . $shop_url . '">' . $shop_title . '</a>';
                }

                if (is_front_page()) {
                    echo '<div class="x-breadcrumbs">' . $current_before . $home_text . $current_after . '</div>';
                } elseif (is_home()) {
                    echo '<div class="x-breadcrumbs"><a href="' . $home_link . '">' . $home_text . '</a>' . $delimiter . $current_before . $blog_title . $current_after . '</div>';
                } else {
                    echo '<div class="x-breadcrumbs"><a href="' . $home_link . '">' . $home_text . '</a>' . $delimiter;
                    if (is_category()) {
                        $the_cat = get_category(get_query_var('cat'), false);
                        if ($the_cat->parent != 0) echo get_category_parents($the_cat->parent, TRUE, $delimiter);
                        echo $current_before . single_cat_title('', false) . $current_after;
                    } elseif (x_is_product_category()) {
                        echo $shop_link . $delimiter . $current_before . single_cat_title('', false) . $current_after;
                    } elseif (x_is_product_tag()) {
                        echo $shop_link . $delimiter . $current_before . single_tag_title('', false) . $current_after;
                    } elseif (is_search()) {
                        echo $current_before . __('Search Results for ', '__x__') . '&#8220;' . get_search_query() . '&#8221;' . $current_after;
                    } elseif (is_singular('post')) {
                        if (get_option('page_for_posts') == is_front_page()) {
                            echo $current_before . $page_title . $current_after;
                        } else {
                            echo '<a href="' . get_permalink(get_option('page_for_posts')) . '" title="' . esc_attr(__('See All Posts', '__x__')) . '">' . $blog_title . '</a>' . $delimiter . $current_before . $page_title . $current_after;
                        }
                    } elseif (x_is_portfolio()) {
                        echo $current_before . get_the_title() . $current_after;
                    } elseif (x_is_portfolio_item()) {
                        $link = x_get_parent_portfolio_link();
                        $title = x_get_parent_portfolio_title();
                        echo '<a href="' . $link . '" title="' . esc_attr(__('See All Posts', '__x__')) . '">' . $title . '</a>' . $delimiter . $current_before . $page_title . $current_after;
                    } elseif (x_is_product()) {
                        echo $shop_link . $delimiter . $current_before . $page_title . $current_after;
                    } elseif (is_page() && !$post->post_parent) {
                        echo $current_before . $page_title . $current_after;
                    } elseif (is_page() && $post->post_parent) {
                        $parent_id = $post->post_parent;
                        $breadcrumbs = array();
                        while ($parent_id) {
                            $page = get_page($parent_id);
                            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                            $parent_id = $page->post_parent;
                        }
                        $breadcrumbs = array_reverse($breadcrumbs);
                        for ($i = 0; $i < count($breadcrumbs); $i++) {
                            echo $breadcrumbs[$i];
                            if ($i != count($breadcrumbs) - 1) echo $delimiter;
                        }
                        echo $delimiter . $current_before . $page_title . $current_after;
                    } elseif (is_tag()) {
                        echo $current_before . single_tag_title('', false) . $current_after;
                    } elseif (is_author()) {
                        GLOBAL $author;
                        $userdata = get_userdata($author);
                        echo $current_before . __('Posts by ', '__x__') . '&#8220;' . $userdata->display_name . $current_after . '&#8221;';
                    } elseif (is_404()) {
                        echo $current_before . __('404 (Page Not Found)', '__x__') . $current_after;
                    } elseif (is_archive()) {
                        if (x_is_shop()) {
                            echo $current_before . $shop_title . $current_after;
                        } else {
                            echo $current_before . __('Archives ', '__x__') . $current_after;
                        }
                    }
                    if (get_query_var('paged')) {
                        echo ' <span class="current" style="white-space: nowrap;">(' . __('Page', '__x__') . ' ' . get_query_var('paged') . ')</span>';
                    }
                    echo '</div>';
                }

            }

        }
    } // ends my geodir check
endif;


add_filter('geodir_location_switcher_menu_li_class', 'geodir_x_location_switcher_menu_li_class', 10, 1);
/**
 * add class to gd menu items.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $class The class of the HTML element.
 * @return string Modified class.
 */
function geodir_x_location_switcher_menu_li_class($class)
{
    $class .= " menu-item-has-children ";
    return $class;
}

add_filter('geodir_sub_menu_li_class', 'geodir_x_sub_menu_li_class', 10, 1);
/**
 * add class to gd sub menu items.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $class The class of the HTML element.
 * @return string Modified class.
 */
function geodir_x_sub_menu_li_class($class)
{
    $class .= " menu-item-has-children ";
    return $class;
}
