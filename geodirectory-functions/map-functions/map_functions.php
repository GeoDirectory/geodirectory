<?php
/**
 * All map related functions used by the plugin
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/**
 * Sets global value for map data to use in the map.
 *
 * @since 1.0.0
 *
 * @global array $map_jason An empty array.
 */
function  geodir_init_map_jason()
{
    global $map_jason;
    $map_jason = array();
}

/**
 * Sets global value for map data to use in the map.
 *
 * @since 1.0.0
 *
 * @global array $map_canvas_arr An empty array.
 */
function geodir_init_map_canvas_array()
{
    global $map_canvas_arr;
    $map_canvas_arr = array();
}

/**
 * Create marker json for map script
 *
 * @since 1.0.0
 *
 * @global object $wpdb WordPress database abstraction object.
 * @global array  $map_jason Map json data
 * @global bool   $add_post_in_marker_array Display marker for post on map when it is true.
 * @global array  $geodir_cat_icons Array of the category icon urls.
 *
 * @param null|WP_Post $post WP_Post object.
 */
function create_marker_jason_of_posts($post)
{
    global $wpdb, $map_jason, $add_post_in_marker_array, $geodir_cat_icons;

    if (!empty($post) && isset($post->ID) && $post->ID > 0 && (is_main_query() || $add_post_in_marker_array) && $post->marker_json != '') {
        $srcharr = array("'", "/", "-", '"', '\\');
        $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '');

        $geodir_cat_icons = geodir_get_term_icon();
        $icon = !empty($geodir_cat_icons) && isset($geodir_cat_icons[$post->default_category]) ? $geodir_cat_icons[$post->default_category] : '';

        $post_title = $post->post_title;
        $title = str_replace($srcharr, $replarr, $post_title);

        $map_jason[] = '{"id":"' . $post->ID . '","t": "' . $title . '","lt": "' . $post->post_latitude . '","ln": "' . $post->post_longitude . '","mk_id":"' . $post->ID . '_' . $post->default_category . '","i":"' . $icon . '"}';
    }
}

/**
 * Send json data to script to use in listing map
 *
 * @since 1.0.0
 *
 * @global array $map_jason Map json data
 * @global array $map_canvas_arr Array of map canvas data.
 */
function send_marker_jason_to_js()
{
    global $map_jason, $map_canvas_arr;

    if (is_array($map_canvas_arr) && !empty($map_canvas_arr)) {
        foreach ($map_canvas_arr as $canvas => $jason) {
            if (is_array($map_jason) && !empty($map_jason)) {
                $canvas_jason = $canvas . "_jason";
                $map_canvas_arr[$canvas] = array_unique($map_jason);
                unset($cat_content_info);
                $cat_content_info[] = implode(',', $map_canvas_arr[$canvas]);
                $totalcount = count(array_unique($map_jason));
                if (!empty($cat_content_info))
                    $canvas_jason = '[{"totalcount":"' . $totalcount . '",' . substr(implode(',', $cat_content_info), 1) . ']';
                else
                    $canvas_jason = '[{"totalcount":"0"}]';

                $map_canvas_jason_args = array($canvas . '_jason' => $canvas_jason);
                
				/**
				 * Filter the canvas map json data
				 *
				 * @since 1.0.0
				 *
				 * @param array $map_canvas_arr Array of map canvas json data.
				 */
				$map_canvas_jason_args = apply_filters('geodir_map_canvas_jason_' . $canvas, $map_canvas_jason_args);

                wp_localize_script('geodir-map-widget', $canvas . '_jason_args', $map_canvas_jason_args);
            } else {
                $canvas_jason = '[{"totalcount":"0"}]';
                $map_canvas_jason_args = array($canvas . '_jason' => $canvas_jason);
                
				/**
				 * Filter the canvas map json data
				 *
				 * @since 1.0.0
				 *
				 * @param array $map_canvas_arr Array of map canvas json data.
				 */
				$map_canvas_jason_args = apply_filters('geodir_map_canvas_jason_' . $canvas, $map_canvas_jason_args);
                wp_localize_script('geodir-map-widget', $canvas . '_jason_args', $map_canvas_jason_args);
            }
        }
    }
}

/**
 * Home map Taxonomy walker
 *
 * @since 1.0.0
 *
 * @global array $cat_count No. of catregories in list.
 * @global array $geodir_cat_icons Array of the category icon urls.
 *
 * @param string $cat_taxonomy	The taxonomy for which to retrieve catregories.
 * @param int    $cat_parent Parent category to retrieve its child categories. Default 0.
 * @param bool   $hide_empty Whether to don't display category which has no posts. Default true.
 * @param int    $pading Css padding value to styling the category display. Default 0. 
 * @param string $map_canvas_name Canvas name of the map.
 * @param bool   $child_collapse Whether to display child categories collapsed.
 * @param bool   $is_home_map If home page map then true otherwise false. Default false. 
 * @return string|void Html content of list of categories to display on map.
 */
function home_map_taxonomy_walker($cat_taxonomy, $cat_parent = 0, $hide_empty = true, $pading = 0, $map_canvas_name = '', $child_collapse, $is_home_map = false)
{
    global $cat_count, $geodir_cat_icons;

    $exclude_categories = get_option('geodir_exclude_cat_on_map');
    $exclude_categories_new = get_option('geodir_exclude_cat_on_map_upgrade');

    // check if exclude categories saved before fix of categories identical names
    if ($exclude_categories_new) {
        $gd_cat_taxonomy = isset($cat_taxonomy[0]) ? $cat_taxonomy[0] : '';
        $exclude_categories = !empty($exclude_categories[$gd_cat_taxonomy]) && is_array($exclude_categories[$gd_cat_taxonomy]) ? array_unique($exclude_categories[$gd_cat_taxonomy]) : array();
    }

    $exclude_cat_str = implode(',', $exclude_categories);

    if ($exclude_cat_str == '') {
        $exclude_cat_str = '0';
    }

    $cat_terms = get_terms($cat_taxonomy, array('parent' => $cat_parent, 'exclude' => $exclude_cat_str, 'hide_empty ' => $hide_empty));

    $main_list_class = '';
    // If there are terms, start displaying
    if (count($cat_terms) > 0) {
        // Displaying as a list
        $p = $pading * 15;
        $pading++;

        if ($cat_parent == 0) {
            $list_class = 'main_list';
            $display = '';
        } else {
            $list_class = 'sub_list';
            $display = !$child_collapse ? '' : 'display:none';
        }

        $out = '<ul class="treeview ' . $list_class . '" style="margin-left:' . $p . 'px;' . $display . ';">';

        $geodir_cat_icons = geodir_get_term_icon();

        foreach ($cat_terms as $cat_term):
            $post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : 'gd_place';
            $icon = !empty($geodir_cat_icons) && isset($geodir_cat_icons[$cat_term->term_id]) ? $geodir_cat_icons[$cat_term->term_id] : '';

            if (!in_array($cat_term->term_id, $exclude_categories)):
                //Secret sauce.  Function calls itself to display child elements, if any
                $checked = 'checked="checked"';

                // Untick the category by default on home map
                if ($is_home_map && $geodir_home_map_untick = get_option('geodir_home_map_untick')) {
                    if (!empty($geodir_home_map_untick) && in_array($post_type . '_' . $cat_term->term_id, $geodir_home_map_untick)) {
                        $checked = '';
                    }
                }

                $term_check = '<input type="checkbox" ' . $checked . ' class="group_selector ' . $main_list_class . '"';
                $term_check .= ' name="' . $map_canvas_name . '_cat[]" group="catgroup' . $cat_term->term_id . '"';
                $term_check .= ' alt="' . $cat_term->taxonomy . '" title="' . esc_attr(ucfirst($cat_term->name)) . '" value="' . $cat_term->term_id . '" onclick="javascript:build_map_ajax_search_param(\'' . $map_canvas_name . '\',false)">';
                $term_check .= '<img height="15" width="15" alt="" src="' . $icon . '" title="' . ucfirst($cat_term->name) . '"/>';
                $out .= '<li>' . $term_check . '<label>' . ucfirst($cat_term->name) . '</label><i class="fa fa-long-arrow-down"></i>';
            endif;

            // get sub category by recursion
            $out .= home_map_taxonomy_walker($cat_taxonomy, $cat_term->term_id, $hide_empty, $pading, $map_canvas_name, $child_collapse, $is_home_map);
            $out .= '</li>';

        endforeach;

        $out .= '</ul>';

        return $out;
    } else {
        if ($cat_parent == 0)
            return _e('No category', GEODIRECTORY_TEXTDOMAIN);
    }
    return;
}
?>