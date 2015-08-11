<?php
/**
 * Template functions that affect the output of most GeoDirectory pages
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
###############################################
########### DYNAMIC CONTENT ###################
###############################################

/**
 * Outputs the CSS from the compatibility settings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_styles()
{
    $tc = get_option('theme_compatibility_setting');
    echo "<style id='gd-compat-styles' type='text/css'>";
    echo $tc['geodir_theme_compat_css'];
    echo "</style>";
}

/**
 * Outputs the JS from the compatibility settings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_compat_script()
{
    $tc = get_option('theme_compatibility_setting');
    echo "<script>";
    echo $tc['geodir_theme_compat_js'];
    echo " </script>";
}

/**
 * Outputs the 'geodir_top_content_add' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_top_content_add_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    echo $tc['geodir_top_content_add'];
}

/**
 * Outputs the 'geodir_before_main_content_add' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_before_main_content_add_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    echo $tc['geodir_before_main_content_add'];
}

/**
 * Outputs the 'geodir_full_page_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_full_page_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_full_page_class_filter'];
}

/**
 * Outputs the 'geodir_before_widget_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_before_widget_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_before_widget_filter'];
}

/**
 * Outputs the 'geodir_after_widget_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_after_widget_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_after_widget_filter'];
}

/**
 * Outputs the 'geodir_before_title_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_before_title_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_before_title_filter'];
}

/**
 * Outputs the 'geodir_after_title_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_after_title_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_after_title_filter'];
}

/**
 * Outputs the 'geodir_menu_li_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_menu_li_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_menu_li_class_filter'];
}

/**
 * Outputs the 'geodir_sub_menu_ul_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_sub_menu_ul_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_sub_menu_ul_class_filter'];
}

/**
 * Outputs the 'geodir_sub_menu_li_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_sub_menu_li_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_sub_menu_li_class_filter'];
}

/**
 * Outputs the 'geodir_menu_a_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_menu_a_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_menu_a_class_filter'];
}

/**
 * Outputs the 'geodir_sub_menu_a_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_sub_menu_a_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_sub_menu_a_class_filter'];
}

/**
 * Outputs the 'geodir_location_switcher_menu_li_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_location_switcher_menu_li_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_location_switcher_menu_li_class_filter'];
}

/**
 * Outputs the 'geodir_location_switcher_menu_a_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_location_switcher_menu_a_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_location_switcher_menu_a_class_filter'];
}

/**
 * Outputs the 'geodir_location_switcher_menu_sub_ul_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_location_switcher_menu_sub_ul_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_location_switcher_menu_sub_ul_class_filter'];
}

/**
 * Outputs the 'geodir_location_switcher_menu_sub_li_class_filter' from the compatibility settings page.
 *
 * This is called via filter and should not really be used direct.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_location_switcher_menu_sub_li_class_dynamic()
{
    $tc = get_option('theme_compatibility_setting');
    return $tc['geodir_location_switcher_menu_sub_li_class_filter'];
}


add_action('setup_theme', 'geodir_content_actions_dynamic', 10);

/**
 * Changed the output settings depending on the compatibility settings.
 *
 * This function checks the theme compatibility settings and filters the output accordingly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_content_actions_dynamic()
{

    $tc = get_option('theme_compatibility_setting');
    if (empty($tc)) {
        return;
    }

    //php
    if (!empty($tc['geodir_theme_compat_code'])) {
        include_once('geodirectory-functions/compatibility/' . $tc['geodir_theme_compat_code'] . '.php');
    }

    //geodir_full_page_class
    if (!empty($tc['geodir_full_page_class_filter'])) {
        add_filter('geodir_full_page_class', 'geodir_full_page_class_dynamic', 10);
    }

    //widget before filter
    if (!empty($tc['geodir_before_widget_filter'])) {
        add_filter('geodir_before_widget', 'geodir_before_widget_dynamic', 10);
    }

    //widget after filter
    if (!empty($tc['geodir_after_widget_filter'])) {
        add_filter('geodir_after_widget', 'geodir_after_widget_dynamic', 10);
    }

    //widget before title filter
    if (!empty($tc['geodir_before_title_filter'])) {
        add_filter('geodir_before_title', 'geodir_before_title_dynamic', 10);
    }

    //widget before title filter
    if (!empty($tc['geodir_after_title_filter'])) {
        add_filter('geodir_after_title', 'geodir_after_title_dynamic', 10);
    }

    //menu li class
    if (!empty($tc['geodir_menu_li_class_filter'])) {
        add_filter('geodir_menu_li_class', 'geodir_menu_li_class_dynamic', 10);
    }

    //menu ul class
    if (!empty($tc['geodir_sub_menu_ul_class_filter'])) {
        add_filter('geodir_sub_menu_ul_class', 'geodir_sub_menu_ul_class_dynamic', 10);
    }

    //menu sub li class
    if (!empty($tc['geodir_sub_menu_li_class_filter'])) {
        add_filter('geodir_sub_menu_li_class', 'geodir_sub_menu_li_class_dynamic', 10);
    }

    //menu a class
    if (!empty($tc['geodir_menu_a_class_filter'])) {
        add_filter('geodir_menu_a_class', 'geodir_menu_a_class_dynamic', 10);
    }

    //menu sub a class
    if (!empty($tc['geodir_sub_menu_a_class_filter'])) {
        add_filter('geodir_sub_menu_a_class', 'geodir_sub_menu_a_class_dynamic', 10);
    }

    //location menu li class
    if (!empty($tc['geodir_location_switcher_menu_li_class_filter'])) {
        add_filter('geodir_location_switcher_menu_li_class', 'geodir_location_switcher_menu_li_class_dynamic', 10);
    }

    //location menu sub ul class
    if (!empty($tc['geodir_location_switcher_menu_sub_ul_class_filter'])) {
        add_filter('geodir_location_switcher_menu_sub_ul_class', 'geodir_location_switcher_menu_sub_ul_class_dynamic', 10);
    }

    //location menu sub li class
    if (!empty($tc['geodir_location_switcher_menu_sub_li_class_filter'])) {
        add_filter('geodir_location_switcher_menu_sub_li_class', 'geodir_location_switcher_menu_sub_li_class_dynamic', 10);
    }

    //location menu a class
    if (!empty($tc['geodir_location_switcher_menu_a_class_filter'])) {
        add_filter('geodir_location_switcher_menu_a_class', 'geodir_location_switcher_menu_a_class_dynamic', 10);
    }

    // compat styles
    if (!empty($tc['geodir_theme_compat_css'])) {
        add_action('wp_head', 'gd_compat_styles');
    }

    // compat js
    if (!empty($tc['geodir_theme_compat_js'])) {
        add_action('wp_footer', 'gd_compat_script');
    }


    // geodir_top_content_add
    if (!empty($tc['geodir_top_content_add'])) {
        add_action('geodir_top_content', 'geodir_top_content_add_dynamic', 10, 1);
    }

    // geodir_before_main_content_add
    if (!empty($tc['geodir_before_main_content_add'])) {
        add_action('geodir_before_main_content', 'geodir_before_main_content_add_dynamic', 10, 1);
    }


}

###############################################
########### DETAILS PAGE ACTIONS ##############
###############################################

// action for adding the wrapper div opening tag
add_action('geodir_wrapper_open', 'geodir_action_wrapper_open', 10, 3);

/**
 * Outputs the opening HTML wrapper div if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @param string $id Optional. The div id.
 * @param string $class Optional. The div class.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_wrapper_open($type = '', $id = '', $class = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_wrapper_open_replace'])) {
        $text = $tc['geodir_wrapper_open_replace'];
    } else {
        $text = '<div id="[id]" class="[class]">';
    }

    if (!empty($tc['geodir_wrapper_open_id'])) {
        $id = $tc['geodir_wrapper_open_id'];
    }
    if (!empty($tc['geodir_wrapper_open_class'])) {
        $class = $tc['geodir_wrapper_open_class'];
    }

    $text = str_replace(array("[id]", "[class]"), array($id, $class), $text);

    echo $text;
}

// action for adding the wrapperdiv closing tag
add_action('geodir_wrapper_close', 'geodir_action_wrapper_close', 10, 1);

/**
 * Outputs the closing HTML wrapper div if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_wrapper_close($type = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_wrapper_close_replace'])) {
        $text = $tc['geodir_wrapper_close_replace'];
    } else {
        $text = '</div><!-- wrapper ends here-->';
    }

    echo $text;
}

// action for adding the content div opening tag
add_action('geodir_wrapper_content_open', 'geodir_action_wrapper_content_open', 10, 3);
/**
 * Outputs the opening HTML content wrapper div if the compatibility settings are present.
 *
 * @param string $type Optional. Page type.
 * @param string $id Optional. The div id.
 * @param string $class Optional. The div class.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_wrapper_content_open($type = '', $id = '', $class = '')
{
    if ($type == 'home-page' && $width = get_option('geodir_width_home_contant_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'listings-page' && $width = get_option('geodir_width_listing_contant_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'search-page' && $width = get_option('geodir_width_search_contant_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'author-page' && $width = get_option('geodir_width_author_contant_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } else {
        $width_css = '';
    }

    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_wrapper_content_open_replace'])) {
        $text = $tc['geodir_wrapper_content_open_replace'];
    } else {
        $text = '<div id="[id]" class="[class]" role="main" [width_css]>';
    }

    if (!empty($tc['geodir_wrapper_content_open_id'])) {
        $id = $tc['geodir_wrapper_content_open_id'];
    }
    if (!empty($tc['geodir_wrapper_content_open_class'])) {
        $class = $tc['geodir_wrapper_content_open_class'];
    }

    $text = str_replace(array("[id]", "[class]", "[width_css]"), array($id, $class, $width_css), $text);

    echo $text;
}

// action for adding the primary div closing tag
add_action('geodir_wrapper_content_close', 'geodir_action_wrapper_content_close', 10, 1);
/**
 * Outputs the closing HTML content wrapper div if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_wrapper_content_close($type = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_wrapper_content_close_replace'])) {
        $text = $tc['geodir_wrapper_content_close_replace'];
    } else {
        $text = '</div><!-- content ends here-->';
    }
    echo $text;
}

// action for adding the <article> opening tag
add_action('geodir_article_open', 'geodir_action_article_open', 10, 4);
/**
 * Outputs the opening HTML article wrapper if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @param string $id Optional. The element id.
 * @param string $class Optional. The element class.
 * @param string $itemtype Optional. The element itemtype.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_article_open($type = '', $id = '', $class = '', $itemtype = '')
{
    $class = implode(" ", $class);
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_article_open_replace'])) {
        $text = $tc['geodir_article_open_replace'];
    } else {
        $text = '<article  id="[id]" class="[class]" itemscope itemtype="[itemtype]">';
    }

    if (!empty($tc['geodir_article_open_id'])) {
        $id = $tc['geodir_article_open_id'];
    }
    if (!empty($tc['geodir_article_open_class'])) {
        $class = $tc['geodir_article_open_class'];
    }

    $text = str_replace(array("[id]", "[class]", "[itemtype]"), array($id, $class, $itemtype), $text);

    echo $text;
}

// action for adding the primary div closing tag
add_action('geodir_article_close', 'geodir_action_article_close', 10, 1);
/**
 * Outputs the closing HTML article wrapper if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_article_close($type = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_article_close_replace'])) {
        $text = $tc['geodir_article_close_replace'];
    } else {
        $text = '</article><!-- article ends here-->';
    }
    echo $text;
}

// action for adding the sidebar opening tag
add_action('geodir_sidebar_right_open', 'geodir_action_sidebar_right_open', 10, 4);
/**
 * Outputs the opening HTML aside right sidebar wrapper if the compatibility settings are present.
 *
 * @param string $type Optional. Page type.
 * @param string $id Optional. The element id.
 * @param string $class Optional. The element class.
 * @param string $itemtype Optional. The element itemtype.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_right_open($type = '', $id = '', $class = '', $itemtype = '')
{
    if ($type == 'home-page' && $width = get_option('geodir_width_home_right_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'listings-page' && $width = get_option('geodir_width_listing_right_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'search-page' && $width = get_option('geodir_width_search_right_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'author-page' && $width = get_option('geodir_width_author_right_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } else {
        $width_css = '';
    }

    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_sidebar_right_open_replace'])) {
        $text = $tc['geodir_sidebar_right_open_replace'];
    } else {
        $text = '<aside  id="[id]" class="[class]" role="complementary" itemscope itemtype="[itemtype]" [width_css]>';
    }

    if (!empty($tc['geodir_sidebar_right_open_id'])) {
        $id = $tc['geodir_sidebar_right_open_id'];
    }
    if (!empty($tc['geodir_sidebar_right_open_class'])) {
        $class = $tc['geodir_sidebar_right_open_class'];
    }

    $text = str_replace(array("[id]", "[class]", "[itemtype]", "[width_css]"), array($id, $class, $itemtype, $width_css), $text);

    echo $text;
}

// action for adding the primary div closing tag
add_action('geodir_sidebar_right_close', 'geodir_action_sidebar_right_close', 10, 1);
/**
 * Outputs the closing HTML aside right sidebar wrapper if the compatibility settings are present.
 *
 * @param string $type Optional. Depreciated.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_right_close($type = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_sidebar_right_close_replace'])) {
        $text = $tc['geodir_sidebar_right_close_replace'];
    } else {
        $text = '</aside><!-- sidebar ends here-->';
    }
    echo $text;
}


// action for adding the details page top widget area
add_action('geodir_detail_before_main_content', 'geodir_action_geodir_set_preview_post', 8);
add_action('geodir_detail_before_main_content', 'geodir_action_geodir_preview_code', 9);
add_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
add_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);

/**
 * Set the $post value if previewing a post.
 *
 * @global object $post The current post object.
 * @global bool $preview True if the current page is a preview page. False if not.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_set_preview_post()
{
    global $post, $preview;
    $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend
    if (!$preview || $is_backend_preview) {
        return;
    }// bail if not previewing

    $listing_type = isset($_REQUEST['listing_type']) ? $_REQUEST['listing_type'] : '';

    $fields_info = geodir_get_custom_fields_type($listing_type);

    foreach ($_REQUEST as $pkey => $pval) {
        if ($pkey == 'geodir_video') {
            $tags = '<iframe>';
        } else if ($pkey == 'post_desc') {
            $tags = '<p><a><b><i><em><h1><h2><h3><h4><h5><ul><ol><li><img><div><del><ins><span><cite><code><strike><strong><blockquote>';
        } else if (is_array($fields_info) && isset($fields_info[$pkey]) && ($fields_info[$pkey] == 'textarea' || $fields_info[$pkey] == 'html')) {
            $tags = '<p><a><b><i><em><h1><h2><h3><h4><h5><ul><ol><li><img><div><del><ins><span><cite><code><strike><strong><blockquote>';
        } else if (is_array($_REQUEST[$pkey])) {
            $tags = 'skip_field';
        } else {
            $tags = '';
        }
        /**
         * Allows the filtering of the allowed HTML tags per field when submitting from frontend add listing page.
         *
         * @since 1.0.0
         * @param string $tags The allowed HTML tags for the field. Can be many things, for example the description allows these tags '<p><a><b><i><em><h1><h2><h3><h4><h5><ul><ol><li><img><div><del><ins><span><cite><code><strike><strong><blockquote>'.
         * @param string|array $pkey The field id/name. If array then value is set as "skip_field".
         */
        $tags = apply_filters('geodir_save_post_key', $tags, $pkey);

        if ($tags != 'skip_field') {
            $_REQUEST[$pkey] = strip_tags($_REQUEST[$pkey], $tags);
        }
    }

    $post = (object)$_REQUEST;

    if (isset($post->video)) {
        $post->video = stripslashes($post->video);
    }

    if (isset($post->Video2)) {
        $post->Video2 = stripslashes($post->Video2);
    }

    $post_type = $post->listing_type;
    $post_type_info = get_post_type_object($post_type);

    $listing_label = $post_type_info->labels->singular_name;

    $term_icon = '';

    if (!empty($post->post_category)) {
        foreach ($post->post_category as $post_taxonomy => $post_term) {

            if ($post_term != '' && !is_array($post_term)) {
                $post_term = explode(',', trim($post_term, ','));
            }

            if (is_array($post_term)) {
                $post_term = array_unique($post_term);
            }

            if (!empty($post_term)) {
                foreach ($post_term as $cat_id) {
                    $cat_id = trim($cat_id);

                    if ($cat_id != '') {
                        $term_icon = get_option('geodir_default_marker_icon');

                        if (isset($post->post_default_category) && $post->post_default_category == $cat_id) {
                            if ($term_icon_url = get_tax_meta($cat_id, 'ct_cat_icon', false, $post_type)) {
                                if (isset($term_icon_url['src']) && $term_icon_url['src'] != '')
                                    $term_icon = $term_icon_url['src'];
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    $post_latitude = isset($post->post_latitude) ? $post->post_latitude : '';
    $post_longitude = isset($post->post_longitude) ? $post->post_longitude : '';

    $srcharr = array("'", "/", "-", '"', '\\');
    $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '');

    $json_title = str_replace($srcharr, $replarr, $post->post_title);

    $json = '{';
    $json .= '"post_preview": "1",';
    $json .= '"t": "' . $json_title . '",';
    $json .= '"lt": "' . $post_latitude . '",';
    $json .= '"ln": "' . $post_longitude . '",';
    $json .= '"i":"' . $term_icon . '"';
    $json .= '}';

    $post->marker_json = $json;

    $_SESSION['listing'] = serialize($_REQUEST);

    // we need to define a few things to trick the setup_postdata
    if (!isset($post->ID)) {
        $post->ID = '';
        $post->post_author = '';
        $post->post_date = '';
        $post->post_content = '';
        $post->default_category = '';
        $post->post_type = '';
    }
    setup_postdata($post);
}

/**
 * Outputs the preview page top section containing the messages and submit buttons.
 *
 * @global bool $preview True if the current page is a preview page. False if not.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_preview_code()
{
    global $preview;

    $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend

    if (!$preview || $is_backend_preview) {
        return;
    }// bail if not previewing

    geodir_get_template_part('preview', 'buttons');
}

// action for adding the details page top widget area
add_action('geodir_sidebar_detail_top', 'geodir_action_geodir_sidebar_detail_top', 10, 1);
/**
 * Outputs the details page tops section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Detail page.
 *
 * @param string $class Optional. The class for the details page top section widget area.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_detail_top($class = '')
{
    if (get_option('geodir_show_detail_top_section')) { ?>
        <div
            class="<?php
            /**
             * Filter the div class for the wrapper of the full width widget areas.
             *
             * Allows you to filter the class of the div for the HTML Container wrapper for the full width widget areas referred to as "Top Section" or "Bottom Section" in the widget areas.
             *
             * @since 1.0.0
             * @param string $class The class of the div.
             * @param string $type The page type the widget area is being used on. Values can be 'geodir_detail_top', 'geodir_detail_bottom', 'geodir_listing_top', 'geodir_listing_bottom', 'Reg/Login Top Section',
             *               'geodir_author_top','geodir_author_bottom', 'geodir_search_top', 'geodir_search_bottom', 'geodir_home_top' or 'geodir_home_bottom'.
             */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_detail_top'); ?> <?php echo $class; ?>">
            <?php dynamic_sidebar('geodir_detail_top'); ?>
        </div>
    <?php }

}

// action for adding the details page top widget area
add_action('geodir_add_breadcrumb', 'geodir_breadcrumb', 10, 1);

// action for adding the details page top widget area
add_action('geodir_sidebar_detail_bottom_section', 'geodir_action_geodir_sidebar_detail_bottom_section', 10, 1);

/**
 * Outputs the details page bottom section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Detail page.
 *
 * @param string $class Optional. The class for the details page top section widget area.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_detail_bottom_section($class = '')
{
    if (get_option('geodir_show_detail_bottom_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_detail_bottom'); ?> <?php echo $class; ?>">
            <?php dynamic_sidebar('geodir_detail_bottom'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

/**
 * Outputs the details page sidebar widget area content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_details_sidebar_widget_area()
{
    dynamic_sidebar('geodir_detail_sidebar');
}

/**
 * Outputs the details page sidebar place details content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_details_sidebar_place_details()
{
    /**
     * Used to add items to the details page sidebar.
     *
     * @since 1.0.0
     */
    do_action('geodir_detail_page_sidebar');
}

add_action('geodir_detail_sidebar_inside', 'geodir_details_sidebar_place_details', 10);
add_action('geodir_detail_sidebar_inside', 'geodir_details_sidebar_widget_area', 20);

add_action('geodir_detail_sidebar', 'geodir_action_details_sidebar', 10);
/**
 * Outputs the details page sidebar content including all HTML wrappers.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_details_sidebar()
{
    // this adds the opening html tags to the primary div, this required the closing tag below :: ($type='',$id='',$class='',$itemtype='')
    if (get_option('geodir_detail_sidebar_left_section')) {
        /**
         * Called before the details page left sidebar is opened.
         *
         * This is used to add opening wrapper HTML to the details page left sidebar.
         *
         * @since 1.0.0
         * @param string $type The current page type. Values can be 'details-page', 'listings-page', 'author-page', 'search-page' or 'home-page'.
         * @param string $id Usually the ID of the sidebar wrapper. Values can be 'geodir-sidebar' or 'geodir-sidebar-left'.
         * @param string $class The class of the sidebar wrapper. 'geodir-sidebar-left geodir-details-sidebar-left'.
         * @param string $itemtype HTML itemtype 'http://schema.org/WPSideBar'.
         */
        do_action('geodir_sidebar_left_open', 'details-page', 'geodir-sidebar', 'geodir-sidebar-left geodir-details-sidebar-left', 'http://schema.org/WPSideBar');
        ?>
        <div class="geodir-content-left geodir-sidebar-wrap"><?php
        /**
         * Called inside the HTML wrapper of the details sidebar for either the left and right sidebar.
         *
         * This is used to add all info to the details page sidebars.
         *
         * @since 1.0.0
         */
        do_action('geodir_detail_sidebar_inside');
        ?></div><?php
        /**
         * Called after the details page left sidebar.
         *
         * This is used to add closing wrapper HTML to the details page left sidebar.
         *
         * @since 1.0.0
         * @param string $type The current page type. Values can be 'details-page', 'listings-page', 'author-page', 'search-page' or 'home-page'.
         */
        do_action('geodir_sidebar_left_close', 'details-page');
    } else {
        /**
         * Called before the details page right sidebar is opened.
         *
         * This is used to add opening wrapper HTML to the details page right sidebar.
         *
         * @since 1.0.0
         * @param string $type The current page type. Values can be 'details-page', 'listings-page', 'add-listing-page', 'author-page', 'search-page' or 'home-page'.
         * @param string $id Usually the ID of the sidebar wrapper. Values can be 'geodir-sidebar' or 'geodir-sidebar-right'.
         * @param string $class The class of the sidebar wrapper. 'geodir-sidebar-right geodir-details-sidebar-right'.
         * @param string $itemtype HTML itemtype 'http://schema.org/WPSideBar'.
         */
        do_action('geodir_sidebar_right_open', 'details-page', 'geodir-sidebar', 'geodir-sidebar-right geodir-details-sidebar-right', 'http://schema.org/WPSideBar');
        ?>
        <div class="geodir-content-right geodir-sidebar-wrap"><?php
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_detail_sidebar_inside');
        ?></div><?php
        /**
         * Called after the details page right sidebar.
         *
         * This is used to add closing wrapper HTML to the details page right sidebar.
         *
         * @since 1.0.0
         * @param string $type The current page type. Values can be 'details-page', 'listings-page', 'author-page', 'search-page' or 'home-page'.
         */
        do_action('geodir_sidebar_right_close', 'details-page');
    }
}

add_action('geodir_page_title', 'geodir_action_page_title', 10);
/**
 * Output the page title.
 *
 * Outputs the page title where the HTML wrappers classes are filterable.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_page_title()
{
    /**
     * Filter the page title HTML h1 class.
     *
     * @since 1.0.0
     * @param string $class The class to use. Default is 'entry-title fn'.
     */
    $class = apply_filters('geodir_page_title_class', 'entry-title fn');
    /**
     * Filter the page title HTML header wrapper class.
     *
     * @since 1.0.0
     * @param string $class The class to use. Default is 'entry-header'.
     */
    $class_header = apply_filters('geodir_page_title_header_class', 'entry-header');
    echo '<header class="' . $class_header . '"><h1 class="' . $class . '">' . stripslashes(get_the_title()) . '</h1></header>';
}


add_action('geodir_details_slider', 'geodir_action_details_slider', 10, 1);
/**
 * Output the details page slider HTML.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global bool $preview True of on a preview page. False if not.
 * @global object $post The current post object.
 */
function geodir_action_details_slider()
{
    global $preview, $post;

    $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // preview from backend

    if ($is_backend_preview && !empty($post) && !empty($post->ID) && !isset($post->post_images)) {
        $preview_get_images = geodir_get_images($post->ID, 'thumbnail', get_option('geodir_listing_no_img'));

        $preview_post_images = array();
        if ($preview_get_images) {
            foreach ($preview_get_images as $row) {
                $preview_post_images[] = $row->src;
            }
        }
        if (!empty($preview_post_images)) {
            $post->post_images = implode(',', $preview_post_images);
        }
    }

    if ($preview) {
        if (isset($post->post_images) && !empty($post->post_images)) {
            $post->post_images = trim($post->post_images, ",");
            $post_images = explode(",", $post->post_images);
        }

        $main_slides = '';
        $nav_slides = '';
        $slides = 0;

        if (empty($post_images)) {
            $default_img = '';
            $default_cat = '';

            if (isset($post->post_default_category)) {
                $default_cat = $post->post_default_category;
            }

            if ($default_catimg = geodir_get_default_catimage($default_cat, $post->listing_type)) {
                $default_img = $default_catimg['src'];
            } else if ($no_images = get_option('geodir_listing_no_img')) {
                $default_img = $no_images;
            }

            if (!empty($default_img)) {
                $post_images[] = $default_img;
            }
        }

        if (!empty($post_images)) {
            foreach ($post_images as $image) {
                if (!empty($image)) {
                    @list($width, $height) = getimagesize(trim($image));

                    if ($image && $width && $height) {
                        $image = (object)array('src' => $image, 'width' => $width, 'height' => $height);
                    }

                    if (isset($image->src)) {
                        if ($image->height >= 400) {
                            $spacer_height = 0;
                        } else {
                            $spacer_height = ((400 - $image->height) / 2);
                        }

                        $image_title = isset($image->title) ? $image->title : '';

                        $main_slides .= '<li><img src="' . geodir_plugin_url() . "/geodirectory-assets/images/spacer.gif" . '" alt="' . $image_title . '" title="' . $image_title . '" style="max-height:' . $spacer_height . 'px;margin:0 auto;" />';
                        $main_slides .= '<img src="' . $image->src . '" alt="' . $image_title . '" title="' . $image_title . '" style="max-height:400px;margin:0 auto;" /></li>';
                        $nav_slides .= '<li><img src="' . $image->src . '" alt="' . $image_title . '" title="' . $image_title . '" style="max-height:48px;margin:0 auto;" /></li>';
                        $slides++;
                    }
                }
            }// endfore
        } //end if
    } else {
        $main_slides = '';
        $nav_slides = '';
        $post_images = geodir_get_images($post->ID, 'thumbnail', get_option('geodir_listing_no_img'));
        $slides = 0;

        if (empty($post_images) && get_option('geodir_listing_no_img')) {
            $post_images = (object)array((object)array('src' => get_option('geodir_listing_no_img')));
        }

        if (!empty($post_images)) {
            foreach ($post_images as $image) {
                if ($image->height >= 400) {
                    $spacer_height = 0;
                } else {
                    $spacer_height = ((400 - $image->height) / 2);
                }

                $main_slides .= '<li><img src="' . geodir_plugin_url() . "/geodirectory-assets/images/spacer.gif" . '" alt="' . $image->title . '" title="' . $image->title . '" style="max-height:' . $spacer_height . 'px;margin:0 auto;" />';
                $main_slides .= '<img src="' . $image->src . '" alt="' . $image->title . '" title="' . $image->title . '" style="max-height:400px;margin:0 auto;" itemprop="image"/></li>';
                $nav_slides .= '<li><img src="' . $image->src . '" alt="' . $image->title . '" title="' . $image->title . '" style="max-height:48px;margin:0 auto;" /></li>';
                $slides++;
            }
        }// endfore
    }

    if (!empty($post_images)) {
        ?>
        <div class="geodir_flex-container">
            <div class="geodir_flex-loader"><i class="fa fa-refresh fa-spin"></i></div>
            <div id="geodir_slider" class="geodir_flexslider ">
                <ul class="geodir-slides clearfix"><?php echo $main_slides; ?></ul>
            </div>
            <?php if ($slides > 1) { ?>
                <div id="geodir_carousel" class="geodir_flexslider">
                    <ul class="geodir-slides clearfix"><?php echo $nav_slides; ?></ul>
                </div>
            <?php } ?>
        </div>
    <?php
    }
}

add_action('geodir_details_taxonomies', 'geodir_action_details_taxonomies', 10);
/**
 * Output link to the posts categories and tags.
 *
 * @global bool $preview True of on a preview page. False if not.
 * @global object $post The current post object.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_details_taxonomies()
{
    global $preview, $post;?>
    <p class="geodir_post_taxomomies clearfix">
    <?php
    $taxonomies = array();

    $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend

    if ($preview && !$is_backend_preview) {
        $post_type = $post->listing_type;
        $post_taxonomy = $post_type . 'category';
        $post->$post_taxonomy = $post->post_category[$post_taxonomy];
    } else {
        $post_type = $post->post_type;
        $post_taxonomy = $post_type . 'category';
    }
//{	
    $post_type_info = get_post_type_object($post_type);
    $listing_label = $post_type_info->labels->singular_name;

    if (!empty($post->post_tags)) {

        if (taxonomy_exists($post_type . '_tags')):
            $links = array();
            $terms = array();
            // to limit post tags
            $post_tags = trim($post->post_tags, ",");
            $post_id = isset($post->ID) ? $post->ID : '';
            /**
             * Filter the post tags.
             *
             * Allows you to filter the post tags output on the details page of a post.
             *
             * @since 1.0.0
             * @param string $post_tags A comma seperated list of tags.
             * @param int $post_id The current post id.
             */
            $post_tags = apply_filters('geodir_action_details_post_tags', $post_tags, $post_id);

            $post->post_tags = $post_tags;
            $post_tags = explode(",", trim($post->post_tags, ","));

            foreach ($post_tags as $post_term) {

                // fix slug creation order for tags & location
                $post_term = trim($post_term);

                $priority_location = false;
                if ($insert_term = term_exists($post_term, $post_type . '_tags')) {
                    $term = get_term_by('name', $post_term, $post_type . '_tags');
                } else {
                    $post_country = isset($_REQUEST['post_country']) && $_REQUEST['post_country'] != '' ? $_REQUEST['post_country'] : NULL;
                    $post_region = isset($_REQUEST['post_region']) && $_REQUEST['post_region'] != '' ? $_REQUEST['post_region'] : NULL;
                    $post_city = isset($_REQUEST['post_city']) && $_REQUEST['post_city'] != '' ? $_REQUEST['post_city'] : NULL;
                    $match_country = $post_country && sanitize_title($post_term) == sanitize_title($post_country) ? true : false;
                    $match_region = $post_region && sanitize_title($post_term) == sanitize_title($post_region) ? true : false;
                    $match_city = $post_city && sanitize_title($post_term) == sanitize_title($post_city) ? true : false;
                    if ($match_country || $match_region || $match_city) {
                        $priority_location = true;
                        $term = get_term_by('name', $post_term, $post_type . '_tags');
                    } else {
                        $insert_term = wp_insert_term($post_term, $post_type . '_tags');
                        $term = get_term_by('name', $post_term, $post_type . '_tags');
                    }
                }

                if (!is_wp_error($term) && is_object($term)) {

                    // fix tag link on detail page
                    if ($priority_location) {
                        $links[] = "<a href=''>$post_term</a>";
                    } else {
                        $links[] = "<a href='" . esc_attr(get_term_link($term->term_id, $term->taxonomy)) . "'>$term->name</a>";
                    }
                    $terms[] = $term;
                }
                //
            }
            if (!isset($listing_label)) {
                $listing_label = '';
            }
            $taxonomies[$post_type . '_tags'] = wp_sprintf('%s: %l', ucwords($listing_label . ' ' . __('Tags', GEODIRECTORY_TEXTDOMAIN)), $links, (object)$terms);
        endif;

    }

    if (!empty($post->$post_taxonomy)) {
        $links = array();
        $terms = array();
        $termsOrdered = array();
        if (!is_array($post->$post_taxonomy)) {
            $post_term = explode(",", trim($post->$post_taxonomy, ","));
        } else {
            $post_term = $post->$post_taxonomy;
        }

        $post_term = array_unique($post_term);
        if (!empty($post_term)) {
            foreach ($post_term as $post_term) {
                $post_term = trim($post_term);

                if ($post_term != ''):
                    $term = get_term_by('id', $post_term, $post_taxonomy);
                    if (is_object($term)) {
                        $links[] = "<a href='" . esc_attr(get_term_link($term, $post_taxonomy)) . "'>$term->name</a>";
                        $terms[] = $term;
                    }
                endif;
            }
            // order alphabetically
            asort($links);
            foreach (array_keys($links) as $key) {
                $termsOrdered[$key] = $terms[$key];
            }
            $terms = $termsOrdered;

        }

        if (!isset($listing_label)) {
            $listing_label = '';
        }
        $taxonomies[$post_taxonomy] = wp_sprintf('%s: %l', ucwords($listing_label . ' ' . __('Category', GEODIRECTORY_TEXTDOMAIN)), $links, (object)$terms);

    }


    if (isset($taxonomies[$post_taxonomy])) {
        echo '<span class="geodir-category">' . $taxonomies[$post_taxonomy] . '</span>';
    }

    if (isset($taxonomies[$post_type . '_tags']))
        echo '<span class="geodir-tags">' . $taxonomies[$post_type . '_tags'] . '</span>';

    ?>
    </p><?php
}

add_action('geodir_details_micordata', 'geodir_action_details_micordata', 10);

/**
 * Output the posts microdata in the source code.
 *
 * This micordata is used by things like Google as a standard way of declaring things like telephone numbers etc.
 *
 * @global bool $preview True of on a preview page. False if not.
 * @global object $post The current post object.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_details_micordata()
{
    global $post, $preview;
    if (!$preview) {
        $c_url = geodir_curPageURL();
        $c_url = strtok($c_url, "#");
        $c_url = strtok($c_url, "?");
        ?>
        <span style="display:none;" class="url"><?php echo $c_url;?></span>
        <span class="updated" style="display:none;"><?php the_modified_date('c');?></span>
        <span class="vcard author" style="display:none;">
	<span class="fn"><?php echo get_the_author(); ?></span>
    <span class="org"><?php the_title();?></span>
    <span class="role"><?php _e('Admin', GEODIRECTORY_TEXTDOMAIN)?></span>
</span>
        <meta itemprop="name" content="<?php the_title_attribute();?>"/>

        <meta itemprop="url" content="<?php echo $c_url;?>"/>
        <?php if ($post->geodir_contact) {
            echo '<meta itemprop="telephone" content="' . $post->geodir_contact . '" />';
        }
    }
}

add_action('geodir_details_tabs', 'geodir_show_detail_page_tabs', 10);


add_action('geodir_details_next_prev', 'geodir_action_details_next_prev', 10);
/**
 * Outputs the prev/next links of the post details page.
 *
 * This is called by a filter 'geodir_details_next_prev' and can be replaced.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_details_next_prev()
{
    ?>
    <div class="geodir-pos_navigation clearfix">
    <div
        class="geodir-post_left"><?php previous_post_link('%link', '' . __('Previous', GEODIRECTORY_TEXTDOMAIN), false) ?></div>
    <div
        class="geodir-post_right"><?php next_post_link('%link', __('Next', GEODIRECTORY_TEXTDOMAIN) . '', false) ?></div>
    </div><?php
}

/**
 * Outputs the action 'geodir_before_single_post' on the details page main content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 */
function geodir_action_before_single_post()
{
    global $post;
    /**
     * Called at the very start fo the details page output, before the title section.
     *
     * @since 1.0.0
     * @param object $post The current post object.
     * @global WP_Post|null $post The current post, if available.
     */
    do_action('geodir_before_single_post', $post); // extra action	
}

/**
 * Outputs the action 'geodir_after_single_post' on the details page main content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_after_single_post($post)
{
    /**
     * Called on the details page after the details page tabs section and before the next/prev buttons.
     *
     * @since 1.0.0
     * @param object $post The current post object.
     */
    do_action('geodir_after_single_post', $post); // extra action	
}

add_action('geodir_details_main_content', 'geodir_action_before_single_post', 10);
add_action('geodir_details_main_content', 'geodir_action_page_title', 20);
add_action('geodir_details_main_content', 'geodir_action_details_slider', 30);
add_action('geodir_details_main_content', 'geodir_action_details_taxonomies', 40);
add_action('geodir_details_main_content', 'geodir_action_details_micordata', 50);
add_action('geodir_details_main_content', 'geodir_show_detail_page_tabs', 60);
add_action('geodir_details_main_content', 'geodir_action_after_single_post', 70);
add_action('geodir_details_main_content', 'geodir_action_details_next_prev', 80);


###############################################
########### LISTINGS PAGE ACTIONS #############
###############################################
add_action('geodir_listings_page_title', 'geodir_action_listings_title', 10);
/**
 * Outputs the listings template title.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp The WordPress object.
 * @global string $term Current term slug.
 */
function geodir_action_listings_title()
{
    global $wp, $term;

    $gd_post_type = geodir_get_current_posttype();
    $post_type_info = get_post_type_object($gd_post_type);

    $add_string_in_title = __('All', GEODIRECTORY_TEXTDOMAIN) . ' ';
    if (isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite') {
        $add_string_in_title = __('My Favorite', GEODIRECTORY_TEXTDOMAIN) . ' ';
    }

    $list_title = $add_string_in_title . __(ucfirst($post_type_info->labels->name), GEODIRECTORY_TEXTDOMAIN);
    $single_name = $post_type_info->labels->singular_name;

    $taxonomy = geodir_get_taxonomies($gd_post_type, true);

    $gd_country = get_query_var('gd_country');
    $gd_region = get_query_var('gd_region');
    $gd_city = get_query_var('gd_city');

    if (!empty($term)) {
        $location_name = '';
        if ($gd_country != '' || $gd_region != '' || $gd_city != '') {
            if ($gd_country != '') {
                $location_name = geodir_sanitize_location_name('gd_country', $gd_country);
            }

            if ($gd_region != '') {
                $location_name = geodir_sanitize_location_name('gd_region', $gd_region);
            }

            if ($gd_city != '') {
                $location_name = geodir_sanitize_location_name('gd_city', $gd_city);
            }
        }

        $current_term = get_term_by('slug', $term, $taxonomy[0]);
        if (!empty($current_term)) {
            $current_term_name = __(ucfirst($current_term->name), GEODIRECTORY_TEXTDOMAIN);
            if ($current_term_name != '' && $location_name != '' && isset($current_term->taxonomy) && $current_term->taxonomy == $gd_post_type . 'category') {
                $location_last_char = substr($location_name, -1);
                $location_name_attach = strtolower($location_last_char) == 's' ? __("'", GEODIRECTORY_TEXTDOMAIN) : __("'s", GEODIRECTORY_TEXTDOMAIN);
                $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . ' ' . $location_name . $location_name_attach . ' ' . $current_term_name;
            } else {
                $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . $current_term_name . "'";
            }
        } else {
            if (count($taxonomy) > 1) {
                $current_term = get_term_by('slug', $term, $taxonomy[1]);

                if (!empty($current_term)) {
                    $current_term_name = __(ucfirst($current_term->name), GEODIRECTORY_TEXTDOMAIN);
                    if ($current_term_name != '' && $location_name != '' && isset($current_term->taxonomy) && $current_term->taxonomy == $gd_post_type . 'category') {
                        $location_last_char = substr($location_name, -1);
                        $location_name_attach = strtolower($location_last_char) == 's' ? __("'", GEODIRECTORY_TEXTDOMAIN) : __("'s", GEODIRECTORY_TEXTDOMAIN);
                        $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . ' ' . $location_name . $location_name_attach . ' ' . $current_term_name;
                    } else {
                        $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . $current_term_name . "'";
                    }
                }
            }
        }

    } else {
        $gd_country = (isset($wp->query_vars['gd_country']) && $wp->query_vars['gd_country'] != '') ? $wp->query_vars['gd_country'] : '';
        $gd_region = (isset($wp->query_vars['gd_region']) && $wp->query_vars['gd_region'] != '') ? $wp->query_vars['gd_region'] : '';
        $gd_city = (isset($wp->query_vars['gd_city']) && $wp->query_vars['gd_city'] != '') ? $wp->query_vars['gd_city'] : '';

        $gd_country_actual = $gd_region_actual = $gd_city_actual = '';

        if (function_exists('get_actual_location_name')) {
            $gd_country_actual = $gd_country != '' ? get_actual_location_name('country', $gd_country, true) : $gd_country;
            $gd_region_actual = $gd_region != '' ? get_actual_location_name('region', $gd_region) : $gd_region;
            $gd_city_actual = $gd_city != '' ? get_actual_location_name('city', $gd_city) : $gd_city;
        }

        if ($gd_city != '') {
            if ($gd_city_actual != '') {
                $gd_city = $gd_city_actual;
            } else {
                $gd_city = preg_replace('/-(\d+)$/', '', $gd_city);
                $gd_city = preg_replace('/[_-]/', ' ', $gd_city);
                $gd_city = __(ucwords($gd_city), GEODIRECTORY_TEXTDOMAIN);
            }

            $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . $gd_city . "'";
        } else if ($gd_region != '') {
            if ($gd_region_actual != '') {
                $gd_region = $gd_region_actual;
            } else {
                $gd_region = preg_replace('/-(\d+)$/', '', $gd_region);
                $gd_region = preg_replace('/[_-]/', ' ', $gd_region);
                $gd_region = __(ucwords($gd_region), GEODIRECTORY_TEXTDOMAIN);
            }

            $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . $gd_region . "'";
        } else if ($gd_country != '') {
            if ($gd_country_actual != '') {
                $gd_country = $gd_country_actual;
            } else {
                $gd_country = preg_replace('/-(\d+)$/', '', $gd_country);
                $gd_country = preg_replace('/[_-]/', ' ', $gd_country);
                $gd_country = __(ucwords($gd_country), GEODIRECTORY_TEXTDOMAIN);
            }

            $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . $gd_country . "'";
        }
    }

    if (is_search()) {
        $list_title = __('Search', GEODIRECTORY_TEXTDOMAIN) . ' ' . __(ucfirst($post_type_info->labels->name), GEODIRECTORY_TEXTDOMAIN) . __(' For :', GEODIRECTORY_TEXTDOMAIN) . " '" . get_search_query() . "'";
    }
    /** This action is documented in geodirectory_template_actions.php */
    $class = apply_filters('geodir_page_title_class', 'entry-title fn');
    /** This action is documented in geodirectory_template_actions.php */
    $class_header = apply_filters('geodir_page_title_header_class', 'entry-header');

    echo '<header class="' . $class_header . '"><h1 class="' . $class . '">' .
        /**
         * Filter the listing page title.
         *
         * @since 1.0.0
         * @param string $list_title The title for the category page.
         */
        apply_filters('geodir_listing_page_title', wptexturize($list_title)) . '</h1></header>';
}

add_action('geodir_listings_page_description', 'geodir_action_listings_description', 10);
/**
 * Outputs the listings page description HTML.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 */
function geodir_action_listings_description()
{
    global $wp_query;
    $current_term = $wp_query->get_queried_object();

    $gd_post_type = geodir_get_current_posttype();
    if (isset($current_term->term_id) && $current_term->term_id != '') {

        $term_desc = term_description($current_term->term_id, $gd_post_type . '_tags');
        $saved_data = stripslashes(get_tax_meta($current_term->term_id, 'ct_cat_top_desc', false, $gd_post_type));
        if ($term_desc && !$saved_data) {
            $saved_data = $term_desc;
        }
        /**
         * Apply the core filter `the_content` filter to the variable string.
         *
         * This is a WordPress core filter that does many things.
         *
         * @since 1.0.0
         * @param string $var The string to apply the filter to.
         */
        $cat_description = apply_filters('the_content', $saved_data);
        if ($cat_description) {
            ?>

            <div class="term_description"><?php echo $cat_description;?></div> <?php
        }

    }
}

// action for adding the listings page top widget area
add_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);
add_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);

// action for adding the details page top widget area
add_action('geodir_sidebar_listings_top', 'geodir_action_geodir_sidebar_listings_top', 10);
/**
 * Outputs the listings page top section widget area if enabled.
 *
 * Can be enabled disabled from GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_listings_top()
{
    if (get_option('geodir_show_listing_top_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_listing_top'); ?>">
            <?php dynamic_sidebar('geodir_listing_top'); ?>
        </div><!-- clearfix ends here-->
    <?php }

}

// action for adding the sidebar opening tag
add_action('geodir_sidebar_left_open', 'geodir_action_sidebar_left_open', 10, 4);
/**
 * Outputs the listings page left sidebar opening HTML wrapper if enabled.
 *
 * Can be enabled disabled from GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $type Optional. The page type.
 * @param int $id Optional. The id for the HTML element.
 * @param string $class Optional. The class for the HTML element.
 * @param string $itemtype Optional The itemtype property of the HTML element.
 */
function geodir_action_sidebar_left_open($type = '', $id = '', $class = '', $itemtype = '')
{
    if ($type == 'home-page' && $width = get_option('geodir_width_home_left_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'listings-page' && $width = get_option('geodir_width_listing_left_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'search-page' && $width = get_option('geodir_width_search_left_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } elseif ($type == 'author-page' && $width = get_option('geodir_width_author_left_section')) {
        $width_css = 'style="width:' . $width . '%;"';
    } else {
        $width_css = '';
    }

    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_sidebar_left_open_replace'])) {
        $text = $tc['geodir_sidebar_left_open_replace'];
    } else {
        $text = '<aside  id="[id]" class="[class]" role="complementary" itemscope itemtype="[itemtype]" [width_css]>';
    }

    if (!empty($tc['geodir_sidebar_left_open_id'])) {
        $id = $tc['geodir_sidebar_left_open_id'];
    }
    if (!empty($tc['geodir_sidebar_left_open_class'])) {
        $class = $tc['geodir_sidebar_left_open_class'];
    }

    $text = str_replace(array("[id]", "[class]", "[itemtype]", "[width_css]"), array($id, $class, $itemtype, $width_css), $text);

    echo $text;
}

// action for adding the primary div closing tag
add_action('geodir_sidebar_left_close', 'geodir_action_sidebar_left_close', 10, 1);
/**
 * Outputs the listings page left sidebar closing HTML wrapper if enabled.
 *
 * Can be enabled disabled from GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $type Optional. Depreciated.
 */
function geodir_action_sidebar_left_close($type = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_sidebar_left_close_replace'])) {
        $text = $tc['geodir_sidebar_left_close_replace'];
    } else {
        $text = '</aside><!-- sidebar ends here-->';
    }
    echo $text;
}

/**
 * Outputs the listings page left sidebar content including inner wrapper if enabled.
 *
 * Can be enabled disabled from GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_listing_left_section()
{
    if (get_option('geodir_show_listing_left_section')) { ?>
        <div class="geodir-content-left geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_listing_left_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_listings_sidebar_left_inside', 'geodir_listing_left_section', 10);

add_action('geodir_listings_sidebar_left', 'geodir_action_listings_sidebar_left', 10);
/**
 * Outputs the listings left sidebar via action call.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_listings_sidebar_left()
{
    if (get_option('geodir_show_listing_left_section')) {
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_open', 'listings-page', 'geodir-sidebar-left', 'geodir-sidebar-left geodir-listings-sidebar-left', 'http://schema.org/WPSideBar');
        /**
         * Calls the listings page (category) left sidebar content.
         *
         * All the content for the listings page left sidebar is added via this hook.
         *
         * @since 1.0.0
         */
        do_action('geodir_listings_sidebar_left_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_close', 'listings-page');
    }
}

/**
 * Outputs the listings page right sidebar content including inner wrapper if enabled.
 *
 * Can be enabled disabled from GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_listing_right_section()
{
    if (get_option('geodir_show_listing_right_section')) { ?>
        <div class="geodir-content-right geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_listing_right_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_listings_sidebar_right_inside', 'geodir_listing_right_section', 10);

add_action('geodir_listings_sidebar_right', 'geodir_action_listings_sidebar_right', 10);
/**
 * Outputs the listings right sidebar via action call.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_listings_sidebar_right()
{
    if (get_option('geodir_show_listing_right_section')) {
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_open', 'listings-page', 'geodir-sidebar-right', 'geodir-sidebar-right geodir-listings-sidebar-right', 'http://schema.org/WPSideBar');
        /**
         * Calls the listings page (category) right sidebar content.
         *
         * All the content for the listings page right sidebar is added via this hook.
         *
         * @since 1.0.0
         */
        do_action('geodir_listings_sidebar_right_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_close', 'listings-page');
    }
}


// action for adding the sidebar opening tag
add_action('geodir_main_content_open', 'geodir_action_main_content_open', 10, 3);
/**
 * Outputs the main content opening HTML elements.
 *
 * @param string $type Optional. Depreciated.
 * @param string $id Optional. The HTML element id.
 * @param string $class Optional. The HTML element class.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_main_content_open($type = '', $id = '', $class = '')
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_main_content_open_replace'])) {
        $text = $tc['geodir_main_content_open_replace'];
    } else {
        $text = '<main id="[id]" class="[class]" role="main">';
    }

    if (!empty($tc['geodir_main_content_open_id'])) {
        $id = $tc['geodir_main_content_open_id'];
    }
    if (!empty($tc['geodir_main_content_open_class'])) {
        $class = $tc['geodir_main_content_open_class'];
    }

    $text = str_replace(array("[id]", "[class]"), array($id, $class), $text);

    echo $text;
}

// action for adding the primary div closing tag
add_action('geodir_main_content_close', 'geodir_action_main_content_close', 10);
/**
 * Outputs the main content closing HTML elements.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_main_content_close()
{
    $tc = get_option('theme_compatibility_setting');
    if (!empty($tc['geodir_main_content_close_replace'])) {
        $text = $tc['geodir_main_content_close_replace'];
    } else {
        $text = '</main><!-- main ends here-->';
    }
    echo $text;
}

/**
 * Calls the listing template part.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $gridview_columns The girdview style of the listings.
 */
function geodir_action_listings_content_inside()
{
    global $gridview_columns;
    $listing_view = get_option('geodir_listing_view');
    if (strstr($listing_view, 'gridview')) {
        $gridview_columns = $listing_view;
        $listing_view_exp = explode('_', $listing_view);
        $listing_view = $listing_view_exp[0];
    }
    geodir_get_template_part('listing', 'listview');
}

add_action('geodir_listings_content_inside', 'geodir_action_listings_content_inside', 10);
add_action('geodir_listings_content_inside', 'geodir_pagination', 20);


add_action('geodir_listings_content', 'geodir_action_listings_content', 10);
/**
 * Builds and outputs the listings content via actions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_listings_content()
{
    /**
     * This is used to add HTML opening wrappers to the main content of most pages including listings, author, search, home etc.
     *
     * @since 1.0.0
     * @param string $type The page type. Values can be 'listings-page', 'author-page', 'search-page' or 'home-page'.
     * @param string $id The id for the div. Usually 'geodir-main-content'.
     * @param string $class The class for the div. Values can be 'listings-page', 'author-page', 'search-page' or 'home-page'.
     * @see 'geodir_main_content_close' Where the oposing closing tag is added.
     */
    do_action('geodir_main_content_open', 'listings-page', 'geodir-main-content', 'listings-page');
    echo '<div class="clearfix">';
    /**
     * Called before the listings page content, inside the outer wrapper. Used on listings pages and search and author pages.
     *
     * @since 1.0.0
     */
    do_action('geodir_before_listing');
    echo '</div>';

    /**
     * This actions calls the listings list content. Used on listings pages and search and author pages.
     *
     * @since 1.0.0
     */
    do_action('geodir_listings_content_inside');

    /**
     * Called after the listings content, inside the outer wrapper HTML. Used on listings pages and search and author pages.
     *
     * @since 1.0.0
     */
    do_action('geodir_after_listing');

    /**
     * This is used to add HTML closing wrappers to the main content of most pages including listings, author, search, home etc.
     *
     * @since 1.0.0
     * @see 'geodir_main_content_open' Where the oposing opening tag is added.
     */
    do_action('geodir_main_content_close', 'listings-page');
}


add_action('geodir_sidebar_listings_bottom_section', 'geodir_action_sidebar_listings_bottom_section', 10);
/**
 * Outputs the listings page bottom widget area if enabled.
 *
 * Can be enabled/disabled via GD>Design>Listings page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_listings_bottom_section()
{
    if (get_option('geodir_show_listing_bottom_section')) { ?>
        <div class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_listing_bottom'); ?>">
            <?php dynamic_sidebar('geodir_listing_bottom'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

###############################################
######## ADD LISTINGS PAGE ACTIONS ############
###############################################


add_action('geodir_add_listing_page_title', 'geodir_action_add_listing_page_title', 10);
/**
 * Outputs the add listings page title with HTML wrappers of which most can be filtered.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_add_listing_page_title()
{
    if (isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] != '')
        $listing_type = $_REQUEST['listing_type'];
    /** This action is documented in geodirectory_template_actions.php */
    $class = apply_filters('geodir_page_title_class', 'entry-title fn');
    /** This action is documented in geodirectory_template_actions.php */
    $class_header = apply_filters('geodir_page_title_header_class', 'entry-header');
    echo '<header class="' . $class_header . '"><h1 class="' . $class . '">';

    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
        $post_type_info = geodir_get_posttype_info(get_post_type($_REQUEST['pid']));
        /**
         * Filter the add listing page title.
         *
         * @since 1.0.0
         * @param string $title The page title. This is usually Edit/Add followed by the post type name.
         */
        echo apply_filters('geodir_add_listing_page_title_text', (ucwords(__('Edit', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info['labels']['singular_name'], GEODIRECTORY_TEXTDOMAIN))));
    } elseif (isset($listing_type)) {
        $post_type_info = geodir_get_posttype_info($listing_type);
        /** This action is documented in geodirectory_template_actions.php */
        echo apply_filters('geodir_add_listing_page_title_text', (ucwords(__('Add', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info['labels']['singular_name'], GEODIRECTORY_TEXTDOMAIN))));
    } else {
        /** This action is documented in geodirectory_template_actions.php */
        apply_filters('geodir_add_listing_page_title_text', the_title());
    }
    echo '</h1></header>';
}

add_action('geodir_add_listing_page_mandatory', 'geodir_action_add_listing_page_mandatory', 10);
/**
 * Outputs the add listing page mandatory message.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_add_listing_page_mandatory()
{?>
    <p class="geodir-note "><span class="geodir-required">*</span>&nbsp;<?php echo INDICATES_MANDATORY_FIELDS_TEXT;?></p>
<?php
}

add_action('geodir_add_listing_form', 'geodir_action_add_listing_form', 10);
/**
 * Outputs the add listing form HTML content.
 *
 * Other things are needed to output a working add listing form, you should use the add listing shortcode if needed.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $current_user Current user object.
 * @global object $post The current post object.
 * @global object $post_images Image objects of current post if available.
 */
function geodir_action_add_listing_form()
{
    global $cat_display, $post_cat, $current_user;
    $page_id = get_the_ID();
    $post = '';
    $title = '';
    $desc = '';
    $kw_tags = '';
    $required_msg = '';
    $submit_button = '';

    if (isset($_REQUEST['ajax_action'])) $ajax_action = $_REQUEST['ajax_action']; else $ajax_action = 'add';

    $thumb_img_arr = array();
    $curImages = '';

    if (isset($_REQUEST['backandedit'])) {
        global $post;
        $post = (object)unserialize($_SESSION['listing']);
        $listing_type = $post->listing_type;
        $title = $post->post_title;
        $desc = $post->post_desc;
        /*if(is_array($post->post_category) && !empty($post->post_category))
			$post_cat = $post->post_category;
		else*/
        $post_cat = $post->post_category;

        $kw_tags = $post->post_tags;
        $curImages = isset($post->post_images) ? $post->post_images : '';
    } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {

        global $post, $post_images;

        /*query_posts(array('p'=>$_REQUEST['pid']));
		if ( have_posts() ) while ( have_posts() ) the_post(); global $post,$post_images;*/

        $post = geodir_get_post_info($_REQUEST['pid']);
        $thumb_img_arr = geodir_get_images($post->ID);
        if ($thumb_img_arr) {
            foreach ($thumb_img_arr as $post_img) {
                $curImages .= $post_img->src . ',';
            }
        }

        $listing_type = $post->post_type;
        $title = $post->post_title;
        $desc = $post->post_content;
        //$post_cat = $post->categories;
        $kw_tags = $post->post_tags;
        $kw_tags = implode(",", wp_get_object_terms($post->ID, $listing_type . '_tags', array('fields' => 'names')));
    } else {
        $listing_type = $_REQUEST['listing_type'];
    }

    if ($current_user->ID != '0') {
        $user_login = true;
    }


    ?>
    <form name="propertyform" id="propertyform" action="<?php echo get_page_link(geodir_preview_page_id());?>"
          method="post" enctype="multipart/form-data">
        <input type="hidden" name="preview" value="<?php echo $listing_type;?>"/>
        <input type="hidden" name="listing_type" value="<?php echo $listing_type;?>"/>
        <?php if ($page_id) { ?><input type="hidden" name="add_listing_page_id"
                                       value="<?php echo $page_id; ?>" /><?php }?>
        <?php if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') { ?>
            <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid']; ?>"/>
        <?php } ?>
        <?php if (isset($_REQUEST['backandedit'])) { ?>
            <input type="hidden" name="backandedit" value="<?php echo $_REQUEST['backandedit']; ?>"/>
        <?php } ?>
        <?php
        /**
         * Called at the very top of the add listing page form for frontend.
         *
         * This is called just before the "Enter Listing Details" text.
         *
         * @since 1.0.0
         */
        do_action('geodir_before_detail_fields');?>

        <h5><?php echo LISTING_DETAILS_TEXT;?></h5>

        <?php
        /**
         * Called at the top of the add listing page form for frontend.
         *
         * This is called after the "Enter Listing Details" text.
         *
         * @since 1.0.0
         */
        do_action('geodir_before_main_form_fields');?>

        <div id="geodir_post_title_row" class="required_field geodir_form_row clearfix">
            <label><?php echo PLACE_TITLE_TEXT;?><span>*</span> </label>
            <input type="text" field_type="text" name="post_title" id="post_title" class="geodir_textfield"
                   value="<?php echo esc_attr(stripslashes($title)); ?>"/>
            <span class="geodir_message_error"><?php _e($required_msg, GEODIRECTORY_TEXTDOMAIN);?></span>
        </div>

        <?php
        $show_editor = get_option('geodir_tiny_editor_on_add_listing');

        $desc = $show_editor ? stripslashes($desc) : esc_attr(stripslashes($desc));
        $desc_limit = '';
        /**
         * Filter the add listing description field character limit number.
         *
         * @since 1.0.0
         * @param int $desc_limit The amount of characters to limit the description to.
         */
        $desc_limit = apply_filters('geodir_description_field_desc_limit', $desc_limit);
        /**
         * Filter the add listing description field text.
         *
         * @since 1.0.0
         * @param string $desc The text for the description field.
         * @param int $desc_limit The character limit numer if any.
         */
        $desc = apply_filters('geodir_description_field_desc', $desc, $desc_limit);
        $desc_limit_msg = '';
        /**
         * Filter the add listing description limit message.
         *
         * This is the message shown if there is a limit applied to the amount of characters the description can use.
         *
         * @since 1.0.0
         * @param string $desc_limit_msg The limit message string if any.
         * @param int $desc_limit The character limit numer if any.
         */
        $desc_limit_msg = apply_filters('geodir_description_field_desc_limit_msg', $desc_limit_msg, $desc_limit);
        ?>
        <?php
        /**
         * Called on the add listing page form for frontend just before the description field.
         *
         * @since 1.0.0
         */
        do_action('geodir_before_description_field'); ?>
        <div id="geodir_post_desc_row" class="<?php if ($desc_limit != '0') {
            echo 'required_field';
        }?> geodir_form_row clearfix">
            <label><?php echo PLACE_DESC_TEXT;?><span><?php if ($desc_limit != '0') {
                        echo '*';
                    }?></span> </label>
            <?php
            if (!empty($show_editor) && in_array($listing_type, $show_editor)) {

                $editor_settings = array('media_buttons' => false, 'textarea_rows' => 10);?>

                <div class="editor" field_id="post_desc" field_type="editor">
                    <?php wp_editor($desc, "post_desc", $editor_settings); ?>
                </div>
            <?php if ($desc_limit != '') { ?>
                <script
                    type="text/javascript">jQuery('textarea#post_desc').attr('maxlength', "<?php echo $desc_limit;?>");</script>
            <?php } ?>
            <?php } else { ?>
                <textarea field_type="textarea" name="post_desc" id="post_desc" class="geodir_textarea"
                          maxlength="<?php echo $desc_limit; ?>"><?php echo $desc; ?></textarea>
            <?php } ?>
            <?php if ($desc_limit_msg != '') { ?>
                <span class="geodir_message_note"><?php echo $desc_limit_msg; ?></span>
            <?php } ?>
            <span class="geodir_message_error"><?php echo _e($required_msg, GEODIRECTORY_TEXTDOMAIN);?></span>
        </div>
        <?php
        /**
         * Called on the add listing page form for frontend just after the description field.
         *
         * @since 1.0.0
         */
        do_action('geodir_after_description_field'); ?>
        <?php
        $kw_tags = esc_attr(stripslashes($kw_tags));
        $kw_tags_count = TAGKW_TEXT_COUNT;
        $kw_tags_msg = TAGKW_MSG;
        /**
         * Filter the add listing tags character limit.
         *
         * @since 1.0.0
         * @param int $kw_tags_count The character count limit if any.
         */
        $kw_tags_count = apply_filters('geodir_listing_tags_field_tags_count', $kw_tags_count);
        /**
         * Filter the add listing tags field value.
         *
         * You can use the $_REQUEST values to check if this is a go back and edit value etc.
         *
         * @since 1.0.0
         * @param string $kw_tags The tag field value, usually a comma separated list of tags.
         * @param int $kw_tags_count The character count limit if any.
         */
        $kw_tags = apply_filters('geodir_listing_tags_field_tags', $kw_tags, $kw_tags_count);
        /**
         * Filter the add listing tags field message text.
         *
         * @since 1.0.0
         * @param string $kw_tags_msg The message shown under the field.
         * @param int $kw_tags_count The character count limit if any.
         */
        $kw_tags_msg = apply_filters('geodir_listing_tags_field_tags_msg', $kw_tags_msg, $kw_tags_count);
        ?>
        <?php
        /**
         * Called on the add listing page form for frontend just before the tags field.
         *
         * @since 1.0.0
         */
        do_action('geodir_before_listing_tags_field');
        ?>
        <div id="geodir_post_tags_row" class="geodir_form_row clearfix">
            <label><?php echo TAGKW_TEXT; ?></label>
            <input name="post_tags" id="post_tags" value="<?php echo $kw_tags; ?>" type="text" class="geodir_textfield"
                   maxlength="<?php echo $kw_tags_count;?>"/>
            <span class="geodir_message_note"><?php echo $kw_tags_msg;?></span>
        </div>
        <?php
        /**
         * Called on the add listing page form for frontend just after the tags field.
         *
         * @since 1.0.0
         */
        do_action('geodir_after_listing_tags_field'); ?>

        <?php


        $package_info = array();

        $package_info = geodir_post_package_info($package_info, $post);

        geodir_get_custom_fields_html($package_info->pid, 'all', $listing_type);?>


        <?php
        // adjust values here
        $id = "post_images"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls

        $multiple = true; // allow multiple files upload

        $width = geodir_media_image_large_width(); // If you want to automatically resize all uploaded images then provide width here (in pixels)

        $height = geodir_media_image_large_height(); // If you want to automatically resize all uploaded images then provide height here (in pixels)

        $thumb_img_arr = array();
        $totImg = 0;
        if (isset($_REQUEST['backandedit']) && empty($_REQUEST['pid'])) {
            $post = (object)unserialize($_SESSION['listing']);
            if (isset($post->post_images))
                $curImages = trim($post->post_images, ",");


            if ($curImages != '') {
                $curImages_array = explode(',', $curImages);
                $totImg = count($curImages_array);
            }

            $listing_type = $post->listing_type;

        } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            $post = geodir_get_post_info($_REQUEST['pid']);
            $listing_type = $post->post_type;
            $thumb_img_arr = geodir_get_images($_REQUEST['pid']);

        } else {
            $listing_type = $_REQUEST['listing_type'];
        }


        if (!empty($thumb_img_arr)) {
            foreach ($thumb_img_arr as $img) {
                //$curImages = $img->src.",";
            }

            $totImg = count((array)$thumb_img_arr);
        }

        if ($curImages != '')
            $svalue = $curImages; // this will be initial value of the above form field. Image urls.
        else
            $svalue = '';

        $image_limit = $package_info->image_limit;
        $show_image_input_box = ($image_limit != '0');
        /**
         * Filter to be able to show/hide the image upload section of the add listing form.
         *
         * @since 1.0.0
         * @param bool $show_image_input_box Set true to show. Set false to not show.
         * @param string $listing_type The custom post type slug.
         */
        $show_image_input_box = apply_filters('geodir_image_uploader_on_add_listing', $show_image_input_box, $listing_type);
        if ($show_image_input_box) {
            ?>

            <h5 id="geodir_form_title_row" class="geodir-form_title"> <?php echo PRO_PHOTO_TEXT;?>
                <?php if ($image_limit == 1) {
                    echo '<br /><small>(' . __('You can upload', GEODIRECTORY_TEXTDOMAIN) . ' ' . $image_limit . ' ' . __('image with this package', GEODIRECTORY_TEXTDOMAIN) . ')</small>';
                } ?>
                <?php if ($image_limit > 1) {
                    echo '<br /><small>(' . __('You can upload', GEODIRECTORY_TEXTDOMAIN) . ' ' . $image_limit . ' ' . __('images with this package', GEODIRECTORY_TEXTDOMAIN) . ')</small>';
                } ?>
                <?php if ($image_limit == '') {
                    echo '<br /><small>(' . __('You can upload unlimited images with this package', GEODIRECTORY_TEXTDOMAIN) . ')</small>';
                } ?>
            </h5>

            <div class="geodir_form_row clearfix" id="<?php echo $id; ?>dropbox" align="center"
                 style="border:1px solid #ccc; min-height:100px; height:auto; padding:10px;">
                <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>"/>
                <input type="hidden" name="<?php echo $id; ?>image_limit" id="<?php echo $id; ?>image_limit"
                       value="<?php echo $image_limit; ?>"/>
                <input type="hidden" name="<?php echo $id; ?>totImg" id="<?php echo $id; ?>totImg"
                       value="<?php echo $totImg; ?>"/>

                <div
                    class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>"
                    id="<?php echo $id; ?>plupload-upload-ui">
                    <h4><?php _e('Drop files to upload', GEODIRECTORY_TEXTDOMAIN);?></h4><br/>
                    <input id="<?php echo $id; ?>plupload-browse-button" type="button"
                           value="<?php esc_attr_e('Select Files', GEODIRECTORY_TEXTDOMAIN); ?>" class="geodir_button"/>
                    <span class="ajaxnonceplu"
                          id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
                    <?php if ($width && $height): ?>
                        <span class="plupload-resize"></span>
                        <span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
                        <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
                    <?php endif; ?>
                    <div class="filelist"></div>
                </div>

                <div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?> clearfix"
                     id="<?php echo $id; ?>plupload-thumbs" style="border-top:1px solid #ccc; padding-top:10px;">
                </div>
                <span
                    id="upload-msg"><?php _e('Please drag &amp; drop the images to rearrange the order', GEODIRECTORY_TEXTDOMAIN);?></span>
                <span id="<?php echo $id; ?>upload-error" style="display:none"></span>
            </div>

        <?php } ?>

        <?php
        /**
         * Called on the add listing page form for frontend just after the image upload field.
         *
         * @since 1.0.0
         */
        do_action('geodir_after_main_form_fields');?>


        <!-- add captcha code -->

        <script>
            <!--<script>-->
            document.write('<inp' + 'ut type="hidden" id="geodir_sp' + 'amblocker_top_form" name="geodir_sp' + 'amblocker" value="64"/>')
        </script>
        <noscript>
            <div>
                <label><?php _e('Type 64 into this box', GEODIRECTORY_TEXTDOMAIN);?></label>
                <input type="text" id="geodir_spamblocker_top_form" name="geodir_spamblocker" value="" maxlength="10"/>
            </div>
        </noscript>
        <input type="text" id="geodir_filled_by_spam_bot_top_form" name="geodir_filled_by_spam_bot" value=""/>


        <!-- end captcha code -->

        <div id="geodir-add-listing-submit" class="geodir_form_row clear_both" align="center" style="padding:2px;">
            <input type="submit" value="<?php echo PRO_PREVIEW_BUTTON;?>"
                   class="geodir_button" <?php echo $submit_button;?>/>
            <span class="geodir_message_note"
                  style="padding-left:0px;"> <?php _e('Note: You will be able to see a preview in the next page', GEODIRECTORY_TEXTDOMAIN);?></span>
        </div>

    </form>
    <?php
    wp_reset_query();
}

/**
 * Output the add listing sidebar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_add_listing_sidebar_widget_area()
{
    dynamic_sidebar('geodir_add_listing_sidebar');
}

add_action('geodir_add_listing_sidebar_inside', 'geodir_add_listing_sidebar_widget_area', 10);

add_action('geodir_add_listing_sidebar', 'geodir_action_add_listing_sidebar', 10);

/**
 * Output the add listing sidebar including all HTML wrappers.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_add_listing_sidebar()
{
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_sidebar_right_open', 'add-listing-page', 'geodir-sidebar', 'geodir-sidebar-right', 'http://schema.org/WPSideBar');
    /**
     * This is used to add the content to the add listing page sidebar.
     *
     * @since 1.0.0
     */
    do_action('geodir_add_listing_sidebar_inside');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_sidebar_right_close', 'details-page');
}

###############################################
######## SIGNUP/REG PAGE ACTIONS ##############
###############################################

// action for adding the details page top widget area
add_action('geodir_sidebar_signup_top', 'geodir_action_geodir_sidebar_signup_top', 10);
/**
 * Output the signup/register page top section widget area.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_signup_top()
{
    ?>
    <div
        class="<?php
        /** This action is documented in geodirectory_template_actions.php */
        echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'Reg/Login Top Section'); ?>">
        <?php dynamic_sidebar('Reg/Login Top Section');?>
    </div><!-- clearfix ends here-->
<?php
}


// action for adding the details page top widget area
add_action('geodir_signup_forms', 'geodir_action_signup_forms', 10);
/**
 * Output the signup and register forms with included JS to make them work properly.
 *
 * @global bool $user_login True if user is logged in. False if not.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_signup_forms()
{

    global $user_login;

    ?>
    <script type="text/javascript">
        <?php if ( $user_login ) { ?>
        setTimeout(function () {
            try {
                d = document.getElementById('user_pass');
                d.value = '';
                d.focus();
            } catch (e) {
            }
        }, 200);
        <?php } else { ?>
        try {
            document.getElementById('user_login').focus();
        } catch (e) {
        }
        <?php } ?>
    </script>
    <script type="text/javascript">
        <?php if ( $user_login ) { ?>
        setTimeout(function () {
            try {
                d = document.getElementById('user_pass');
                d.value = '';
                d.focus();
            } catch (e) {
            }
        }, 200);
        <?php } else { ?>
        try {
            document.getElementById('user_login').focus();
        } catch (e) {
        }
        <?php } ?>
    </script><?php

    global $errors;
    if (isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'claim')
        $errors->add('claim_login', LOGIN_CLAIM);

    if (!empty($errors)) {
        foreach ($errors as $errorsObj) {
            foreach ($errorsObj as $key => $val) {
                for ($i = 0; $i < count($val); $i++) {
                    echo "<div class=sucess_msg>" . $val[$i] . '</div>';
                    $registration_error_msg = 1;
                }
            }
        }
    }

    if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'login' && isset($_REQUEST['page1']) && $_REQUEST['page1'] == 'sign_in') {
        ?>

        <div class="login_form">
            <?php
            /**
             * Contains login form template.
             *
             * @since 1.0.0
             */
            include(geodir_plugin_path() . "/geodirectory-templates/login_frm.php"); ?>
        </div>

    <?php } elseif (isset($_REQUEST['page']) && $_REQUEST['page'] == 'login' && isset($_REQUEST['page1']) && $_REQUEST['page1'] == 'sign_up') { ?>

        <div class="registration_form">
            <?php
            /**
             * Contains registration form template.
             *
             * @since 1.0.0
             */
            include(geodir_plugin_path() . "/geodirectory-templates/reg_frm.php"); ?>
        </div>

    <?php } else { ?>

        <div class="login_form_l">
            <?php
            /**
             * Contains login form template.
             *
             * @since 1.0.0
             */
            include(geodir_plugin_path() . "/geodirectory-templates/login_frm.php"); ?>
        </div>
        <div class="registration_form_r">
            <?php
            /**
             * Contains registration form template.
             *
             * @since 1.0.0
             */
            include(geodir_plugin_path() . "/geodirectory-templates/reg_frm.php"); ?>
        </div>

    <?php }?>
    <script type="text/javascript">
        try {
            document.getElementById('user_login').focus();
        } catch (e) {
        }
    </script>


    <?php if ((isset($errors->errors['invalidcombo']) && $errors->errors['invalidcombo'] != '') || (isset($errors->errors['empty_username']) && $errors->errors['empty_username'] != '')) { ?>
    <script type="text/javascript">document.getElementById('lostpassword_form').style.display = '';</script>
<?php }
}

###############################################
########### AUTHOR PAGE ACTIONS ###############
###############################################

add_action('geodir_author_page_title', 'geodir_action_author_page_title', 10);
/**
 * Output the author page title including HTML wrappers.
 *
 * @global string $term Current term slug.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_author_page_title()
{
    global $term;

    $gd_post_type = geodir_get_current_posttype();
    $post_type_info = get_post_type_object($gd_post_type);

    $add_string_in_title = __('All', GEODIRECTORY_TEXTDOMAIN) . ' ';
    if (isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite') {
        $add_string_in_title = __('My Favorite', GEODIRECTORY_TEXTDOMAIN) . ' ';
    }

    $list_title = $add_string_in_title . $post_type_info->labels->name;
    $single_name = $post_type_info->labels->singular_name;

    $taxonomy = geodir_get_taxonomies($gd_post_type);

    if (!empty($term)) {
        $current_term = get_term_by('slug', $term, $taxonomy[0]);
        if (!empty($current_term))
            $list_title .= __(' in', GEODIRECTORY_TEXTDOMAIN) . " '" . ucwords($current_term->name) . "'";
    }


    if (is_search()) {
        $list_title = __('Search', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info->labels->name, GEODIRECTORY_TEXTDOMAIN) . __(' For :', GEODIRECTORY_TEXTDOMAIN) . " '" . get_search_query() . "'";

    }
    /** This action is documented in geodirectory_template_actions.php */
    $class = apply_filters('geodir_page_title_class', 'entry-title fn');
    /** This action is documented in geodirectory_template_actions.php */
    $class_header = apply_filters('geodir_page_title_header_class', 'entry-header');
    echo '<header class="' . $class_header . '"><h1 class="' . $class . '">' .
        /**
         * Filter the author page title text.
         *
         * @since 1.0.0
         * @param string $list_title The title for the page.
         */
        apply_filters('geodir_author_page_title_text', wptexturize($list_title)) . '</h1></header>';
}


// action for adding the details page top widget area
add_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
add_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);

/**
 * Output the author page top sections widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_author_top()
{
    if (get_option('geodir_show_author_top_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_author_top'); ?>">
            <?php dynamic_sidebar('geodir_author_top'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

/**
 * Output the author page left sidebar if enabled.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_author_left_section()
{
    if (get_option('geodir_show_author_left_section')) { ?>
        <div class="geodir-content-left geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_author_left_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_author_sidebar_left_inside', 'geodir_author_left_section', 10);

add_action('geodir_author_sidebar_left', 'geodir_action_author_sidebar_left', 10);

/**
 * Build the content via hooks for the author page left sidebar.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_author_sidebar_left()
{
    if (get_option('geodir_show_author_left_section')) {
// this adds the opening html tags to the primary div, this required the closing tag below :: ($type='',$id='',$class='',$itemtype='')
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_open', 'author-page', 'geodir-sidebar-left', 'geodir-sidebar-left geodir-listings-sidebar-left', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the author page left sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_author_sidebar_left_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_close', 'author-page');
    }
}

/**
 * Output the author page right sidebar if enabled.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_author_right_section()
{
    if (get_option('geodir_show_author_right_section')) { ?>
        <div class="geodir-content-right geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_author_right_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_author_sidebar_right_inside', 'geodir_author_right_section', 10);

add_action('geodir_author_sidebar_right', 'geodir_action_author_sidebar_right', 10);
/**
 * Build the content via hooks for the author page right sidebar.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_author_sidebar_right()
{
    if (get_option('geodir_show_author_right_section')) {
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_open', 'author-page', 'geodir-sidebar-right', 'geodir-sidebar-right geodir-listings-sidebar-right', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the author page right sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_author_sidebar_right_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_close', 'author-page');
    }
}

/**
 * Calls and outputs the template for the author page content section.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $gridview_columns The girdview style of the listings.
 */
function geodir_action_author_content_inside()
{
    global $gridview_columns;
    $listing_view = get_option('geodir_author_view');
    if (strstr($listing_view, 'gridview')) {
        $gridview_columns = $listing_view;
        $listing_view_exp = explode('_', $listing_view);
        $listing_view = $listing_view_exp[0];
    }
    geodir_get_template_part('listing', 'listview');
}

add_action('geodir_author_content_inside', 'geodir_action_author_content_inside', 10);
add_action('geodir_author_content_inside', 'geodir_pagination', 20);

add_action('geodir_author_content', 'geodir_action_author_content', 10);
/**
 * Build the content via hooks for the author page content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_author_content()
{
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_open', 'author-page', 'geodir-main-content', 'author-page');
    echo '<div class="clearfix">';
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_before_listing');
    echo '</div>';
    /**
     * This is used to add the content to the author page main content.
     *
     * @since 1.0.0
     */
    do_action('geodir_author_content_inside');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_after_listing');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_close', 'author-page');
}

add_action('geodir_sidebar_author_bottom_section', 'geodir_action_sidebar_author_bottom_section', 10);
/**
 * Output the author page bottom sections widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Author page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_author_bottom_section()
{
    if (get_option('geodir_show_author_bottom_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_author_bottom'); ?>">
            <?php dynamic_sidebar('geodir_author_bottom'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

###############################################
########### SEARCH PAGE ACTIONS ###############
###############################################

add_action('geodir_search_page_title', 'geodir_action_search_page_title', 10);
/**
 * Output the search page title including HTML wrappers.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_search_page_title()
{
    $gd_post_type = geodir_get_current_posttype();
    $post_type_info = get_post_type_object($gd_post_type);

    if (is_search()) {
        $list_title = __('Search', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info->labels->name, GEODIRECTORY_TEXTDOMAIN) . __(' For :', GEODIRECTORY_TEXTDOMAIN) . " '" . get_search_query() . "'";

    }
    /** This action is documented in geodirectory_template_actions.php */
    $class = apply_filters('geodir_page_title_class', 'entry-title fn');
    /** This action is documented in geodirectory_template_actions.php */
    $class_header = apply_filters('geodir_page_title_header_class', 'entry-header');
    echo '<header class="' . $class_header . '"><h1 class="' . $class . '">' .
        /** This action is documented in geodirectory_template_actions.php */
        apply_filters('geodir_listing_page_title', wptexturize($list_title)) . '</h1></header>';
}

// action for adding the listings page top widget area
add_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
add_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
/**
 * Output the search page top section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_search_top()
{
    if (get_option('geodir_show_search_top_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_search_top'); ?>">
            <?php dynamic_sidebar('geodir_search_top'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

/**
 * Output the search page left sidebar widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_search_left_section()
{
    if (get_option('geodir_show_search_left_section')) { ?>
        <div class="geodir-content-left geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_search_left_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_search_sidebar_left_inside', 'geodir_search_left_section', 10);

add_action('geodir_search_sidebar_left', 'geodir_action_search_sidebar_left', 10);
/**
 * Build the content for the search page left sidebar via hooks.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_search_sidebar_left()
{
    if (get_option('geodir_show_search_left_section')) {
// this adds the opening html tags to the primary div, this required the closing tag below :: ($type='',$id='',$class='',$itemtype='')
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_open', 'search-page', 'geodir-sidebar-left', 'geodir-sidebar-left geodir-listings-sidebar-left', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the search page left sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_search_sidebar_left_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_close', 'search-page');
    }
}

/**
 * Output the search page right sidebar widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_search_right_section()
{
    if (get_option('geodir_show_search_right_section')) { ?>
        <div class="geodir-content-right geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_search_right_sidebar'); ?>
        </div>
    <?php }
}

add_action('geodir_search_sidebar_right_inside', 'geodir_search_right_section', 10);

add_action('geodir_search_sidebar_right', 'geodir_action_search_sidebar_right', 10);
/**
 * Build the content for the search page right sidebar via hooks.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_search_sidebar_right()
{
    if (get_option('geodir_show_search_right_section')) {
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_open', 'search-page', 'geodir-sidebar-right', 'geodir-sidebar-right geodir-listings-sidebar-right', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the search page right sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_search_sidebar_right_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_close', 'search-page');
    }
}


add_action('geodir_sidebar_search_bottom_section', 'geodir_action_sidebar_search_bottom_section', 10);
/**
 * Output the search page bottom section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_search_bottom_section()
{
    if (get_option('geodir_show_search_bottom_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_search_bottom'); ?>">
            <?php dynamic_sidebar('geodir_search_bottom'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

/**
 * Calls and outputs the template for the search page content section.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $gridview_columns The girdview style of the listings.
 */
function geodir_action_search_content_inside()
{
    global $gridview_columns;
    $listing_view = get_option('geodir_search_view');
    if (strstr($listing_view, 'gridview')) {
        $gridview_columns = $listing_view;
        $listing_view_exp = explode('_', $listing_view);
        $listing_view = $listing_view_exp[0];
    }
    geodir_get_template_part('listing', 'listview');
}

add_action('geodir_search_content_inside', 'geodir_action_search_content_inside', 10);
add_action('geodir_search_content_inside', 'geodir_pagination', 20);

add_action('geodir_search_content', 'geodir_action_search_content', 10);

/**
 * Build the content via hooks for the search page content.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_search_content()
{
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_open', 'search-page', 'geodir-main-content', 'search-page');
    echo '<div class="clearfix">';
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_before_listing');
    echo '</div>';
    /**
     * This is used to add the content to the search page main content.
     *
     * @since 1.0.0
     */
    do_action('geodir_search_content_inside');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_after_listing');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_close', 'search-page');
}

###############################################
############# HOME PAGE ACTIONS ###############
###############################################
// action for adding the details page top widget area
add_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
add_action('geodir_location_before_main_content', 'geodir_breadcrumb', 20);

add_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
add_action('geodir_home_before_main_content', 'geodir_breadcrumb', 20);

/**
 * Output the home page top section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_geodir_sidebar_home_top()
{
    if (get_option('geodir_show_home_top_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_home_top'); ?>">
            <?php dynamic_sidebar('geodir_home_top'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

/**
 * Output the home page left sidebar widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_home_left_section()
{
    if (get_option('geodir_show_home_left_section')) { ?>
        <div class="geodir-content-left geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_home_left'); ?>
        </div>
    <?php }
}

add_action('geodir_home_sidebar_left_inside', 'geodir_home_left_section', 10);

add_action('geodir_location_sidebar_left', 'geodir_action_home_sidebar_left', 10);
add_action('geodir_home_sidebar_left', 'geodir_action_home_sidebar_left', 10);

/**
 * Build the content for the home page left sidebar via hooks.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_home_sidebar_left()
{
    if (get_option('geodir_show_home_left_section')) {
// this adds the opening html tags to the primary div, this required the closing tag below :: ($type='',$id='',$class='',$itemtype='')
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_open', 'home-page', 'geodir-sidebar-left', 'geodir-sidebar geodir-sidebar-left geodir-listings-sidebar-left', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the home page left sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_home_sidebar_left_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_left_close', 'home-page');
    }
}

/**
 * Output the home page right sidebar widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_home_right_section()
{
    if (get_option('geodir_show_home_right_section')) { ?>
        <div class="geodir-content-right geodir-sidebar-wrap">
            <?php dynamic_sidebar('geodir_home_right'); ?>
        </div>
    <?php }
}

add_action('geodir_home_sidebar_right_inside', 'geodir_home_right_section', 10);

add_action('geodir_location_sidebar_right', 'geodir_action_home_sidebar_right', 10);
add_action('geodir_home_sidebar_right', 'geodir_action_home_sidebar_right', 10);
/**
 * Build the content for the home page right sidebar via hooks.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_home_sidebar_right()
{
    if (get_option('geodir_show_home_right_section')) {
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_open', 'home-page', 'geodir-sidebar-right', 'geodir-sidebar-right geodir-listings-sidebar-right', 'http://schema.org/WPSideBar');
        /**
         * This is used to add the content to the home page right sidebar (if active).
         *
         * @since 1.0.0
         */
        do_action('geodir_home_sidebar_right_inside');
        /** This action is documented in geodirectory_template_actions.php */
        do_action('geodir_sidebar_right_close', 'home-page');
    }
}

/**
 * Build and output the content of the home page via hooks.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_home_content_inside()
{
    dynamic_sidebar('geodir_home_content');
}

add_action('geodir_home_content_inside', 'geodir_action_home_content_inside', 10);
add_action('geodir_home_content_inside', 'geodir_pagination', 20);

add_action('geodir_location_content', 'geodir_action_home_content', 10);
add_action('geodir_home_content', 'geodir_action_home_content', 10);
/**
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_home_content()
{
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_open', 'home-page', 'geodir-main-content', 'home-page');
    /**
     * This called before the home page main content.
     *
     * @since 1.0.0
     */
    do_action('geodir_before_home_content');
    /**
     * This is used to add the content to the home page main content.
     *
     * @since 1.0.0
     */
    do_action('geodir_home_content_inside');
    /**
     * This is called after the homepage main content.
     *
     * @since 1.0.0
     */
    do_action('geodir_after_home_content');
    /** This action is documented in geodirectory_template_actions.php */
    do_action('geodir_main_content_close', 'home-page');
}

add_action('geodir_sidebar_location_bottom_section', 'geodir_action_sidebar_home_bottom_section', 10);
add_action('geodir_sidebar_home_bottom_section', 'geodir_action_sidebar_home_bottom_section', 10);
/**
 * Output the home page bottom section widget area if enabled.
 *
 * Can be enabled/disabled from GD>Design>Home page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_action_sidebar_home_bottom_section()
{
    if (get_option('geodir_show_home_bottom_section')) { ?>
        <div
            class="<?php
            /** This action is documented in geodirectory_template_actions.php */
            echo apply_filters('geodir_full_page_class', 'geodir_full_page clearfix', 'geodir_home_bottom'); ?>">
            <?php dynamic_sidebar('geodir_home_bottom'); ?>
        </div><!-- clearfix ends here-->
    <?php }
}

add_filter('geodir_filter_widget_listings_fields', 'geodir_function_widget_listings_fields');
add_filter('geodir_filter_widget_listings_join', 'geodir_function_widget_listings_join');
add_filter('geodir_filter_widget_listings_where', 'geodir_function_widget_listings_where');
add_filter('geodir_filter_widget_listings_orderby', 'geodir_function_widget_listings_orderby');
add_filter('geodir_filter_widget_listings_limit', 'geodir_function_widget_listings_limit');

/* add class for listing row */
add_filter('geodir_post_view_extra_class', 'geodir_core_post_view_extra_class');

// filter for listing page title
add_filter('geodir_listing_page_title', 'geodir_filter_listing_page_title', 1, 1);

/**
 * Output the home page title including HTML wrappers.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $list_title The home page title.
 * @return string
 */
function geodir_filter_listing_page_title($list_title)
{
    if (is_search() && trim(get_search_query()) == '') {
        $gd_post_type = geodir_get_current_posttype();
        $post_type_info = get_post_type_object($gd_post_type);

        $list_title = __('Search', GEODIRECTORY_TEXTDOMAIN) . ' ' . __(ucfirst($post_type_info->labels->name), GEODIRECTORY_TEXTDOMAIN) . __(' :', GEODIRECTORY_TEXTDOMAIN);
    }
    return $list_title;
}