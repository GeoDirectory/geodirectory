<?php
/**
 * Jupiter theme compatibility functions.
 *
 * This file lets the GeoDirectory Plugin use the Jupiter theme HTML wrappers to fit and work perfectly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
add_action('after_setup_theme', 'jupiter_action_calls', 11);
/**
 * Action calls for jupiter theme compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function jupiter_action_calls()
{
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


    // CAHNGE PAGE TITLES
    remove_action('page_title', 'mk_page_title');
    add_action('page_title', 'gd_mk_page_title');
    // CHANGE BREADCRUMS FOR GD PAGES
    remove_action('theme_breadcrumbs', 'mk_theme_breadcrumbs');
    add_action('theme_breadcrumbs', 'gd_mk_theme_breadcrumbs');


}


/**
 * Adds breadcrumb based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_mk_theme_breadcrumbs()
{

    if (is_page_geodir_home() || geodir_is_page('location') || geodir_is_page('home')) {
        jupiter_geodir_breadcrumb();
    } elseif (geodir_is_page('listing')) {
        jupiter_geodir_breadcrumb();
    } elseif (geodir_is_page('detail')) {
        jupiter_geodir_breadcrumb();
    } elseif (geodir_is_page('search')) {
        jupiter_geodir_breadcrumb();
    } elseif (geodir_is_page('author')) {
        jupiter_geodir_breadcrumb();
    } else {
        mk_theme_breadcrumbs();
    }
}

/**
 * Adds page title based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 */
function gd_mk_page_title()
{
    global $wp;


    if (is_page_geodir_home() || geodir_is_page('location') || geodir_is_page('home')) {
        jupiter_geodir_page_title();
    } elseif (geodir_is_page('listing')) {
        ob_start(); // Start buffering;
        geodir_action_listings_title();
        $gd_title = ob_get_clean();
        $title_p = explode('">', $gd_title);
        $title = str_replace('</h1></header>', "", $title_p[2]);
        jupiter_geodir_page_title($title);
    } elseif (geodir_is_page('search')) {
        ob_start(); // Start buffering;
        geodir_action_listings_title();
        $gd_title = ob_get_clean();
        $title_p = explode('">', $gd_title);
        $title = str_replace('</h1></header>', "", $title_p[2]);
        jupiter_geodir_page_title($title);
    } elseif (geodir_is_page('author')) {
        ob_start(); // Start buffering;
        geodir_action_author_page_title();
        $gd_title = ob_get_clean();
        $gd_title = str_replace('<h1>', "", $gd_title);
        $gd_title = str_replace('</h1>', "", $gd_title);
        jupiter_geodir_page_title($gd_title);
    } else {
        mk_page_title();
    }


}


/**
 * Jupiter breadcrumb compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 */
function jupiter_geodir_breadcrumb()
{
    $item = '';
    ob_start(); // Start buffering;
    geodir_breadcrumb();
    $gd_crums = ob_get_clean();
    if ($gd_crums) {
        $gd_crums = str_replace('<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs"><li>', "", $gd_crums);
        $gd_crums = str_replace('</li></ul></div>', "", $gd_crums);
        $gd_crums = str_replace('&nbsp;>&nbsp;', " > ", $gd_crums);
        $gd_crums = str_replace('</li><li>', "", $gd_crums);
        $gd_crums = explode(" > ", $gd_crums);
        $trail_end = array_pop($gd_crums);
        $gd_crums['trail_end'] = $trail_end;
        //print_r($gd_crums);exit;
        //print_r($trail);
        $item = $gd_crums;

    }
    if (!$item) {
        return;
    }
    global $mk_options, $post;
    $post_id = global_get_post_id();

    if ($post_id) {
        $local_skining = get_post_meta($post_id, '_enable_local_backgrounds', true);
        $breadcrumb_skin = get_post_meta($post_id, '_breadcrumb_skin', true);
        if ($local_skining == 'true' && !empty($breadcrumb_skin)) {
            $breadcrumb_skin_class = $breadcrumb_skin;
        } else {
            $breadcrumb_skin_class = $mk_options['breadcrumb_skin'];
        }
    } else {
        $breadcrumb_skin_class = $mk_options['breadcrumb_skin'];
    }


    $delimiter = ' &#47; ';

    echo '<div id="mk-breadcrumbs"><div class="mk-breadcrumbs-inner ' . $breadcrumb_skin_class . '-skin">';

    echo implode($delimiter, $item);
    echo "</div></div>";

}

/**
 * Add page title and subtitle.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $title Page title.
 * @param string $subtitle Page subtitle.
 */
function jupiter_geodir_page_title($title = '', $subtitle = '')
{
    global $mk_options;

    $post_id = global_get_post_id();
    $shadow_css = '';
    if ($mk_options['page_title_shadow'] == 'true') {
        $shadow_css = 'mk-drop-shadow';
    }

    $align = !empty($align) ? $align : 'left';

    //$title = 'xxxx';
    echo '<section id="mk-page-introduce" class="intro-' . $align . '">';
    echo '<div class="mk-grid">';
    if (!empty($title)) {
        echo '<h1 class="page-introduce-title ' . $shadow_css . '">' . $title . '</h1>';

    }

    if (!empty($subtitle)) {
        echo '<div class="page-introduce-subtitle">';
        echo $subtitle;
        echo '</div>';
    }
    if ($mk_options['disable_breadcrumb'] == 'true') {
        if (get_post_meta($post_id, '_disable_breadcrumb', true) != 'false') {
            /**
             * Calls the theme breadcrumbs for Jupiter theme.
             *
             * @since 1.4.0
             */
            do_action('theme_breadcrumbs', $post_id);
        }
    }

    echo '<div class="clearboth"></div></div></section>';


}
