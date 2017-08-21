<?php
/**
 * Geodirectory Custom Post Types/Taxonomies
 *
 * Inits custom post types and taxonomies
 *
 * @package     GeoDirectory
 * @category    Core
 * @author      WPGeoDirectory
 */

/**
 * Contains custom post types/taxonomies related functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
include_once('custom_taxonomy_hooks_actions.php');

/**
 * Returns listing nav menu Items.
 *
 * When WP Admin ->
 *      Geodirectory ->
 *      Design ->
 *      Navigation ->
 *      Show add listing navigation in menu and/or Show listings navigation in menu
 * checked this function returns listing and add listing links.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string The Menu HTML.
 */
function geodir_add_nav_menu_items()
{
    $items = '';
    // apply filter to add more navigations // -Filter-Location-Manager

    if (get_option('geodir_show_listing_nav')) {

        $menu_class = '';
        if (geodir_is_page('listing'))
            $menu_class = 'current-menu-item';


        //SHOW LISTING OF POST TYPE IN MAIN NAVIGATION
        $post_types = geodir_get_posttypes('object');
        $show_post_type_main_nav = get_option('geodir_add_posttype_in_main_nav');
        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $args) {
                if (!empty($show_post_type_main_nav)) {
                    if (in_array($post_type, $show_post_type_main_nav)) {
                        if (get_post_type_archive_link($post_type)) {
                            $menu_class = '';
                            if (geodir_get_current_posttype() == $post_type && geodir_is_page('listing'))
                                $menu_class = 'current-menu-item';
                            /**
                             * Filter the menu li class.
                             *
                             * @since 1.0.0
                             * @param string $menu_class The menu HTML class.
                             */
                            $li_class = apply_filters('geodir_menu_li_class', 'menu-item ' . $menu_class);
                            /**
                             * Filter the menu a class.
                             *
                             * @since 1.0.0
                             */
                            $a_class = apply_filters('geodir_menu_a_class', '');
                            $items .= '<li class="' . $li_class . '">
									<a href="' . get_post_type_archive_link($post_type) . '" class="' . $a_class . '">
										' . __(geodir_utf8_ucfirst($args->labels->name),'geodirectory') . '
									</a>
								</li>';
                        }
                    }
                }
            }
        }
        //END LISTING OF POST TYPE IN MAIN NAVIGATION

        $view_posttype_listing = get_option('geodir_add_posttype_in_listing_nav');
        $is_listing_sub_meny_exists = (!empty($view_posttype_listing)) ? true : false;
        if ($is_listing_sub_meny_exists) {
            /**
             * Filter the menu li class.
             *
             * @since 1.0.0
             * @param string $menu_class The menu HTML class.
             */
            $li_class = apply_filters('geodir_menu_li_class', 'menu-item menu-item-has-children menu-gd-listings ' . $menu_class);
            /**
             * Filter the sub menu li class.
             *
             * @since 1.0.0
             * @param string $menu_class The menu HTML class.
             */
            $sub_li_class = apply_filters('geodir_sub_menu_li_class', 'menu-item ' . $menu_class);
            /**
             * Filter the sub menu ul class.
             *
             * @since 1.0.0
             */
            $sub_ul_class = apply_filters('geodir_sub_menu_ul_class', 'sub-menu');
            /**
             * Filter the menu a class.
             *
             * @since 1.0.0
             */
            $a_class = apply_filters('geodir_menu_a_class', '');
            /**
             * Filter the sub menu a class.
             *
             * @since 1.0.0
             */
            $sub_a_class = apply_filters('geodir_sub_menu_a_class', '');
            $items .= '<li class="' . $li_class . '">
					<a href="#" class="' . $a_class . '">' . __('Listing', 'geodirectory') . '</a>
					<ul class="' . $sub_ul_class . '">';
            $post_types = geodir_get_posttypes('object');

            $show_listing_post_types = get_option('geodir_add_posttype_in_listing_nav');

            if (!empty($post_types)) {
                global $geodir_add_location_url;
                $geodir_add_location_url = true;
                foreach ($post_types as $post_type => $args) {
                    if (!empty($show_listing_post_types)) {
                        if (in_array($post_type, $show_listing_post_types)) {
                            if (get_post_type_archive_link($post_type)) {

                                $menu_class = '';
                                if (geodir_get_current_posttype() == $post_type && geodir_is_page('listing'))
                                    $menu_class = 'current-menu-item';

                                $items .= '<li class="' . $sub_li_class . '">
														<a href="' . get_post_type_archive_link($post_type) . '" class="' . $sub_a_class . '">
															' . __(geodir_utf8_ucfirst($args->labels->name),'geodirectory') . '
														</a>
													</li>';
                            }
                        }
                    }
                }
                $geodir_add_location_url = NULL;
            }

            $items .= '	</ul> ';
            /**
             * Filter called after the sub menu closing ul tag for dynamic added menu items.
             *
             * @since 1.5.9
             */
            $items .= apply_filters('geodir_menu_after_sub_ul','');
            $items .= '</li>';
        }
    }

    if (get_option('geodir_show_addlisting_nav')) {

        $menu_class = '';
        if (geodir_is_page('add-listing'))
            $menu_class = 'current-menu-item';

        //SHOW ADD LISTING POST TYPE IN MAIN NAVIGATION
        $post_types = geodir_get_posttypes('object');
        $show_add_listing_post_types_main_nav = get_option('geodir_add_listing_link_main_nav');
        $geodir_allow_posttype_frontend = get_option('geodir_allow_posttype_frontend');

        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $args) {
                if (!empty($geodir_allow_posttype_frontend)) {
                    if (in_array($post_type, $geodir_allow_posttype_frontend)) {
                        if (!empty($show_add_listing_post_types_main_nav)) {
                            if (in_array($post_type, $show_add_listing_post_types_main_nav)) {
                                if (geodir_get_addlisting_link($post_type)) {

                                    $menu_class = '';
                                    if (geodir_get_current_posttype() == $post_type && geodir_is_page('add-listing'))
                                        $menu_class = 'current-menu-item';
                                    /**
                                     * Filter the menu li class.
                                     *
                                     * @since 1.0.0
                                     * @param string $menu_class The menu HTML class.
                                     */
                                    $li_class = apply_filters('geodir_menu_li_class', 'menu-item ' . $menu_class);
                                    /**
                                     * Filter the menu a class.
                                     *
                                     * @since 1.0.0
                                     */
                                    $a_class = apply_filters('geodir_menu_a_class', '');
                                    $cpt_name = __($args->labels->singular_name, 'geodirectory');
                                    $items .= '<li class="' . $li_class . '">
											<a href="' . geodir_get_addlisting_link($post_type) . '" class="' . $a_class . '">
												' . sprintf( __('Add %s', 'geodirectory'), $cpt_name ) . '
											</a>
										</li>';
                                }
                            }
                        }
                    }
                }
            }
        }
        //END SHOW ADD LISTING POST TYPE IN MAIN NAVIGATION
    }

    $view_add_posttype_listing = get_option('geodir_add_listing_link_add_listing_nav');
    $is_add_listing_sub_meny_exists = (!empty($view_add_posttype_listing)) ? true : false;
    if ($is_add_listing_sub_meny_exists) {

        if (get_option('geodir_show_addlisting_nav')) {
            /**
             * Filter the menu li class.
             *
             * @since 1.0.0
             * @param string $menu_class The menu HTML class.
             */
            $li_class = apply_filters('geodir_menu_li_class', 'menu-item menu-item-has-children menu-gd-add-listing ' . $menu_class);
            /**
             * Filter the sub menu li class.
             *
             * @since 1.0.0
             * @param string $menu_class The menu HTML class.
             */
            $sub_li_class = apply_filters('geodir_sub_menu_li_class', 'menu-item ' . $menu_class);
            /**
             * Filter the sub menu ul class.
             *
             * @since 1.0.0
             */
            $sub_ul_class = apply_filters('geodir_sub_menu_ul_class', 'sub-menu');
            /**
             * Filter the menu a class.
             *
             * @since 1.0.0
             */
            $a_class = apply_filters('geodir_menu_a_class', '');
            /**
             * Filter the sub menu a class.
             *
             * @since 1.0.0
             */
            $sub_a_class = apply_filters('geodir_sub_menu_a_class', '');
            $items .= '<li  class="' . $li_class . '">
					<a href="#" class="' . $a_class . '">' . __('Add Listing', 'geodirectory') . '</a>
					<ul class="' . $sub_ul_class . '">';

            $post_types = geodir_get_posttypes('object');

            $show_add_listing_post_types = get_option('geodir_add_listing_link_add_listing_nav');

            if (!empty($post_types)) {
                foreach ($post_types as $post_type => $args) {
                    if (!empty($geodir_allow_posttype_frontend)) {
                        if (in_array($post_type, $geodir_allow_posttype_frontend)) {
                            if (!empty($show_add_listing_post_types)) {
                                if (in_array($post_type, $show_add_listing_post_types)) {
                                    if (geodir_get_addlisting_link($post_type)) {

                                        $menu_class = '';
                                        if (geodir_get_current_posttype() == $post_type && geodir_is_page('add-listing'))
                                            $menu_class = 'current-menu-item';
                                        /**
                                         * Filter the menu li class.
                                         *
                                         * @since 1.0.0
                                         * @param string $menu_class The menu HTML class.
                                         */
                                        $li_class = apply_filters('geodir_menu_li_class', 'menu-item ' . $menu_class);
                                        $cpt_name = __($args->labels->singular_name, 'geodirectory');
                                        $items .= '<li class="' . $sub_li_class . '">
														<a href="' . geodir_get_addlisting_link($post_type) . '" class="' . $sub_a_class . '">
															' . sprintf( __('Add %s', 'geodirectory'), $cpt_name ) . '
														</a>
													</li>';
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $items .= '	</ul> ';
            $items .= apply_filters('geodir_menu_after_sub_ul','');
            $items .= '</li>';

        }
    }
    // apply filter to add more navigations // -Filter-Location-Manager
    return $items;
}


/**
 * Appends listing menu items on all enabled menu locations.
 *
 * This function appends menu items with {@see geodir_add_nav_menu_items()} based on menu location settings.
 * WP Admin -> Geodirectory -> Design -> Navigation -> Show geodirectory navigation in selected menu locations.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $menu The menu HTML.
 * @param array $args The menu args.
 * @return string The menu HTML.
 */
function geodir_pagemenu_items($menu, $args)
{
    $locations = get_nav_menu_locations();
    $geodir_theme_location = get_option('geodir_theme_location_nav');
    $geodir_theme_location_nav = array();
    if (empty($locations) && empty($geodir_theme_location)) {
        $menu = str_replace("</ul></div>", geodir_add_nav_menu_items() . "</ul></div>", $menu);
        $geodir_theme_location_nav[] = $args['theme_location'];
        update_option('geodir_theme_location_nav', $geodir_theme_location_nav);
    }
    //else if(empty($geodir_theme_location)) // It means 'Show geodirectory navigation in selected menu locations' is not set yet.
//		$menu = str_replace("</ul></div>",geodir_add_nav_menu_items()."</ul></div>",$menu);
    else if (is_array($geodir_theme_location) && isset($args['theme_location']) && in_array($args['theme_location'], $geodir_theme_location))
        $menu = str_replace("</ul></div>", geodir_add_nav_menu_items() . "</ul></div>", $menu);

    return $menu;

}


/**
 * Appends listing menu items on given menu location.
 *
 * This function appends menu items with {@see geodir_add_nav_menu_items()} when the given menu location enabled on menu location settings.
 * WP Admin -> Geodirectory -> Design -> Navigation -> Show geodirectory navigation in selected menu locations.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $items The menu HTML.
 * @param object $args The menu args.
 * @return string The menu HTML.
 */
function geodir_menu_items($items, $args)
{

    $location = $args->theme_location;

    $geodir_theme_location = get_option('geodir_theme_location_nav');

    if (has_nav_menu($location) == '1' && is_array($geodir_theme_location) && in_array($location, $geodir_theme_location)) {

        $items = $items . geodir_add_nav_menu_items();
        return $items;

    } else {
        return $items;
    }
}

/**
 * Get array of all categories.
 *
 * Returns terms from all geodirectory taxonomies. {@see geodir_get_taxonomies()}
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @return array Category array.
 */
function geodir_get_category_all_array()
{
    global $wpdb;
    $return_array = array();

    $taxonomies = geodir_get_taxonomies();
    $taxonomies = implode("','", $taxonomies);
    $taxonomies = "'" . $taxonomies . "'";

    $pn_categories = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT $wpdb->terms.name as name, $wpdb->term_taxonomy.count as count, $wpdb->terms.term_id as cat_ID FROM $wpdb->term_taxonomy,  $wpdb->terms WHERE $wpdb->term_taxonomy.term_id = %d AND $wpdb->term_taxonomy.taxonomy in ( $taxonomies ) ORDER BY name",
            array($wpdb->terms . term_id)
        )
    );

    foreach ($pn_categories as $pn_categories_obj) {
        $return_array[] = array("id" => $pn_categories_obj->cat_ID,
            "title" => $pn_categories_obj->name,);
    }
    return $return_array;
}


/**
 * Get Current Post Type.
 *
 * @since 1.0.0
 * @since 1.6.18 Get the post type on map marker info request with preview mode.
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global object $post WordPress Post object.
 * @global string $geodir_post_type The post type.
 * @return string The post type.
 */
function geodir_get_current_posttype() {
    global $wp_query, $post, $geodir_post_type;

    $geodir_post_type = get_query_var('post_type');

    if (geodir_is_page('add-listing') || geodir_is_page('preview')) {
        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
            $geodir_post_type = get_post_type((int)$_REQUEST['pid']);
        elseif (isset($_REQUEST['listing_type']))
            $geodir_post_type = sanitize_text_field($_REQUEST['listing_type']);
    }

    if ((geodir_is_page('search') || geodir_is_page('author')) && isset($_REQUEST['stype']))
        $geodir_post_type = sanitize_text_field($_REQUEST['stype']);

    if (is_tax())
        $geodir_post_type = geodir_get_taxonomy_posttype();

    // Retrive post type for map marker html ajax request on preview page.
    if (empty($geodir_post_type) && defined('DOING_AJAX') && !empty($post)) {
        if (!empty($post->post_type)) {
            $geodir_post_type = $post->post_type;
        } else if (!empty($post->listing_type)) {
            $geodir_post_type = $post->listing_type;
        }
    }

    $all_postypes = geodir_get_posttypes();
    $all_postypes = stripslashes_deep($all_postypes);

    if (is_array($all_postypes) && !in_array($geodir_post_type, $all_postypes))
        $geodir_post_type = '';

    if( defined( 'DOING_AJAX' ) && isset($_REQUEST['stype'])){
        $geodir_post_type = sanitize_text_field($_REQUEST['stype']);
    }


    /**
     * Filter the default CPT return.
     *
     * @since 1.6.9
     */
    return apply_filters('geodir_get_current_posttype',$geodir_post_type);
}

/**
 * Get default Post Type.
 *
 * @since 1.6.9
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global string $geodir_post_type The post type.
 * @return string The post type.
 */
function geodir_get_default_posttype()
{
    $post_types = apply_filters( 'geodir_get_default_posttype', geodir_get_posttypes( 'object' ) );

    $stype = false;
    foreach ( $post_types as $post_type => $info ) {
        global $wpdb;
        $has_posts = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_status='publish' LIMIT 1", $post_type ) );
        if ( $has_posts ) {
            $stype = $post_type; break;
        }
    }

    if(!$stype){
        $stype = 'gd_place';
    }

    return $stype;
}

/**
 * Get list of geodirectory Post Types.
 *
 * @since 1.0.0
 * @since 1.5.1 options case added to get post type options array.
 * @package GeoDirectory
 * @param string $output The output Type.
 * @return array|object|string Post Types.
 */
function geodir_get_posttypes($output = 'names')
{
    $post_types = array();
    $post_types = get_option('geodir_post_types');
    $post_types = stripslashes_deep($post_types);
    if (!empty($post_types)) {
        switch ($output):
            case 'object':
            case 'Object':
                $post_types = json_decode(json_encode($post_types), FALSE);//(object)$post_types;
                break;
            case 'array':
            case 'Array':
                $post_types = (array)$post_types;
                break;
			case 'options':
                $post_types = (array)$post_types;
				
				$options = array();
				if (!empty($post_types)) {
					foreach ($post_types as $key => $info) {
						$options[$key] = __($info['labels']['singular_name'], 'geodirectory');
					}
				}
				$post_types = $options;
                break;
            default:
                $post_types = array_keys($post_types);
                break;
        endswitch;
    }

    if (!empty($post_types))
        return $post_types;
    else
        return array();
}

/**
 * Get Custom Post Type info.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $post_type The post type.
 * @return bool|array Post type details.
 */
function geodir_get_posttype_info($post_type = '')
{
    $post_types = array();
    $post_types = get_option('geodir_post_types');
    $post_types = stripslashes_deep($post_types);
    if (!empty($post_types) && $post_type != '') {
        return $post_types[$post_type];
    } else
        return false;
}

if (!function_exists('geodir_get_taxonomies')) {
    /**
     * Get all custom taxonomies.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $post_type The post type.
     * @param bool $tages_taxonomies Is this a tag taxonomy?. Default: false.
     * @return array|bool Taxonomies on success. false on failure.
     */
    function geodir_get_taxonomies($post_type = '', $tages_taxonomies = false) {
        $taxonomies = array();
        $gd_taxonomies = array();

        if ($taxonomies = get_option('geodir_taxonomies')) {
            $gd_taxonomies = array_keys($taxonomies);

            if ($post_type != '') {
                $gd_taxonomies = array();
            }

            $i = 0;
            foreach ($taxonomies as $taxonomy => $args) {
                if ($post_type != '' && $args['object_type'] == $post_type) {
                    $gd_taxonomies[] = $taxonomy;
                }

                if ($tages_taxonomies === false && substr($taxonomy , -5) == '_tags') {
                    if (array_search($taxonomy, $gd_taxonomies) !== false) {
                        unset($gd_taxonomies[array_search($taxonomy, $gd_taxonomies)]);
                    }
                }
            }

            $gd_taxonomies = array_values($gd_taxonomies);
        }

        /**
         * Filter the taxonomies.
         *
         * @since 1.0.0
         * @param array $gd_taxonomies The taxonomy array.
         */
        $taxonomies = apply_filters('geodir_taxonomy', $gd_taxonomies);

        if (!empty($taxonomies)) {
            return $taxonomies;
        } else {
            return false;
        }
    }
}

if (!function_exists(' geodir_get_categories_dl')) {
    /**
     * Get categories dropdown HTML.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $post_type The post type.
     * @param string $selected The selected value.
     * @param bool $tages_taxonomies Is this a tag taxonomy?. Default: false.
     * @param bool $echo Prints the HTML when set to true. Default: true.
     * @return void|string Dropdown HTML.
     */
    function  geodir_get_categories_dl($post_type = '', $selected = '', $tages_taxonomies = false, $echo = true)
    {

        $html = '';
        $taxonomies = geodir_get_taxonomies($post_type, $tages_taxonomies);

        $categories = get_terms($taxonomies);

        $html .= '<option value="0">' . __('All', 'geodirectory') . '</option>';

        foreach ($categories as $category_obj) {
            $select_opt = '';
            if ($selected == $category_obj->term_id) {
                $select_opt = 'selected="selected"';
            }
            $html .= '<option ' . $select_opt . ' value="' . $category_obj->term_id . '">'
                . geodir_utf8_ucfirst($category_obj->name) . '</option>';
        }

        if ($echo)
            echo $html;
        else
            return $html;
    }
}


/**
 * Get post type listing slug.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $object_type The post type.
 * @return bool|string Slug on success. false on failure.
 */
function geodir_get_listing_slug($object_type = '')
{

    $listing_slug = '';

    $post_types = get_option('geodir_post_types');
    $taxonomies = get_option('geodir_taxonomies');


    if ($object_type != '') {
        if (!empty($post_types) && array_key_exists($object_type, $post_types)) {

            $object_info = $post_types[$object_type];
            $listing_slug = $object_info['listing_slug'];
        } elseif (!empty($taxonomies) && array_key_exists($object_type, $taxonomies)) {
            $object_info = $taxonomies[$object_type];
            $listing_slug = $object_info['listing_slug'];
        }

    }

    if (!empty($listing_slug))
        return $listing_slug;
    else
        return false;
}


/**
 * Get a taxonomy post type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @param string $taxonomy The WordPress taxonomy string.
 * @return bool|string Post type on success. false on failure.
 */
function geodir_get_taxonomy_posttype($taxonomy = '')
{
    global $wp_query;

    $post_type = array();
    $taxonomies = array();

    if (!empty($taxonomy)) {
        $taxonomies[] = $taxonomy;
    } elseif (isset($wp_query->tax_query->queries)) {
        $tax_arr = $wp_query->tax_query->queries;
        //if tax query has 'relation' set then it will break wp_list_pluck so we remove it
        if(isset( $tax_arr['relation'])){unset( $tax_arr['relation']);}
        $taxonomies = wp_list_pluck($tax_arr, 'taxonomy');
    }

    if (!empty($taxonomies)) {
        foreach (geodir_get_posttypes() as $pt) {
            $object_taxonomies = $pt === 'attachment' ? get_taxonomies_for_attachments() : get_object_taxonomies($pt);
            if (array_intersect($taxonomies, $object_taxonomies))
                $post_type[] = $pt;
        }
    }

    if (!empty($post_type))
        return $post_type[0];
    else
        return false;
}

if (!function_exists('geodir_custom_taxonomy_walker')) {
    /**
     * Custom taxonomy walker function.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $cat_taxonomy The taxonomy name.
     * @param int $cat_parent The parent term ID.
     * @param bool $hide_empty Hide empty taxonomies? Default: false.
     * @param int $pading CSS padding in pixels.
     * @return string|void taxonomy HTML.
     */
    function geodir_custom_taxonomy_walker($cat_taxonomy, $cat_parent = 0, $hide_empty = false, $pading = 0)
    {
        global $cat_display, $post_cat, $exclude_cats;

        $search_terms = trim($post_cat, ",");

        $search_terms = explode(",", $search_terms);

        $cat_terms = get_terms($cat_taxonomy, array('parent' => $cat_parent, 'hide_empty' => $hide_empty, 'exclude' => $exclude_cats));

        $display = '';
        $onchange = '';
        $term_check = '';
        $main_list_class = '';
        $out = '';
        //If there are terms, start displaying
        if (count($cat_terms) > 0) {
            //Displaying as a list
            $p = $pading * 20;
            $pading++;


            if ((!geodir_is_page('listing')) || (is_search() && $_REQUEST['search_taxonomy'] == '')) {
                if ($cat_parent == 0) {
                    $list_class = 'main_list gd-parent-cats-list gd-cats-display-' . $cat_display;
                    $main_list_class = 'class="main_list_selecter"';
                } else {
                    //$display = 'display:none';
                    $list_class = 'sub_list gd-sub-cats-list';
                }
            }

            if ($cat_display == 'checkbox' || $cat_display == 'radio') {
                $p = 0;
                $out = '<div class="' . $list_class . ' gd-cat-row-' . $cat_parent . '" style="margin-left:' . $p . 'px;' . $display . ';">';
            }

            foreach ($cat_terms as $cat_term) {

                $checked = '';

                if (in_array($cat_term->term_id, $search_terms)) {
                    if ($cat_display == 'select' || $cat_display == 'multiselect')
                        $checked = 'selected="selected"';
                    else
                        $checked = 'checked="checked"';
                }

                if ($cat_display == 'radio')
                    $out .= '<span style="display:block" ><input type="radio" field_type="radio" name="post_category[' . $cat_term->taxonomy . '][]" ' . $main_list_class . ' alt="' . $cat_term->taxonomy . '" title="' . geodir_utf8_ucfirst($cat_term->name) . '" value="' . $cat_term->term_id . '" ' . $checked . $onchange . ' id="gd-cat-' . $cat_term->term_id . '" >' . $term_check . geodir_utf8_ucfirst($cat_term->name) . '</span>';
                elseif ($cat_display == 'select' || $cat_display == 'multiselect')
                    $out .= '<option ' . $main_list_class . ' style="margin-left:' . $p . 'px;" alt="' . $cat_term->taxonomy . '" title="' . geodir_utf8_ucfirst($cat_term->name) . '" value="' . $cat_term->term_id . '" ' . $checked . $onchange . ' >' . $term_check . geodir_utf8_ucfirst($cat_term->name) . '</option>';

                else {
                    $out .= '<span style="display:block"><input style="display:inline-block" type="checkbox" field_type="checkbox" name="post_category[' . $cat_term->taxonomy . '][]" ' . $main_list_class . ' alt="' . $cat_term->taxonomy . '" title="' . geodir_utf8_ucfirst($cat_term->name) . '" value="' . $cat_term->term_id . '" ' . $checked . $onchange . ' id="gd-cat-' . $cat_term->term_id . '" >' . $term_check . geodir_utf8_ucfirst($cat_term->name) . '</span>';
                }

                // Call recurson to print sub cats
                $out .= geodir_custom_taxonomy_walker($cat_taxonomy, $cat_term->term_id, $hide_empty, $pading);

            }

            if ($cat_display == 'checkbox' || $cat_display == 'radio')
                $out .= '</div>';

            return $out;
        }
        return;
    }
}

if (!function_exists('geodir_custom_taxonomy_walker2')) {
    /**
     * Custom taxonomy walker function.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $post WordPress Post object.
     * @global object $gd_session GeoDirectory Session object.
     * @param string $cat_taxonomy The taxonomy name.
     * @param string $cat_limit Number of categories to display.
     */
    function geodir_custom_taxonomy_walker2($cat_taxonomy, $cat_limit = '')
    {
        $post_category = '';
        $post_category_str = '';
        global $exclude_cats, $gd_session;

        $cat_exclude = '';
        if (is_array($exclude_cats) && !empty($exclude_cats))
            $cat_exclude = serialize($exclude_cats);

        if (isset($_REQUEST['backandedit'])) {
            $post = (object)$gd_session->get('listing');

            if (!is_array($post->post_category[$cat_taxonomy]))
                $post_category = $post->post_category[$cat_taxonomy];

            $post_categories = $post->post_category_str;
            if (!empty($post_categories) && array_key_exists($cat_taxonomy, $post_categories))
                $post_category_str = $post_categories[$cat_taxonomy];

        } elseif ((geodir_is_page('add-listing') && isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') || (is_admin())) {
            global $post;

            $post_category = geodir_get_post_meta($post->ID, $cat_taxonomy, true);
            if (empty($post_category) && isset($post->{$cat_taxonomy})) {
                $post_category = $post->{$cat_taxonomy};
            }

            $post_categories = get_post_meta($post->ID, 'post_categories', true);

            if (empty($post_category) && !empty($post_categories) && !empty($post_categories[$cat_taxonomy])) {
                foreach (explode(",", $post_categories[$cat_taxonomy]) as $cat_part) {
                    if (is_numeric($cat_part)) {
                        $cat_part_arr[] = $cat_part;
                    }
                }
                if (is_array($cat_part_arr)) {
                    $post_category = implode(',', $cat_part_arr);
                }
            }

            if (!empty($post_category)) {
                $cat1 = array_filter(explode(',', $post_category));
                $post_category = ',' . implode(',', $cat1) . ',';

            }

            if ($post_category != '' && is_array($exclude_cats) && !empty($exclude_cats)) {

                $post_category_upd = explode(',', $post_category);
                $post_category_change = '';
                foreach ($post_category_upd as $cat) {

                    if (!in_array($cat, $exclude_cats) && $cat != '') {
                        $post_category_change .= ',' . $cat;
                    }
                }
                $post_category = $post_category_change;
            }


            if (!empty($post_categories) && array_key_exists($cat_taxonomy, $post_categories)) {
                $post_category_str = $post_categories[$cat_taxonomy];
            }
        }

        echo '<input type="hidden" id="cat_limit" value="' . $cat_limit . '" name="cat_limit[' . $cat_taxonomy . ']"  />';

        echo '<input type="hidden" id="post_category" value="' . $post_category . '" name="post_category[' . $cat_taxonomy . ']"  />';

        echo '<input type="hidden" id="post_category_str" value="' . $post_category_str . '" name="post_category_str[' . $cat_taxonomy . ']"  />';


        ?>
        <div class="cat_sublist">
            <?php

            $post_id = isset($post->ID) ? $post->ID : '';

            if ((geodir_is_page('add-listing') || is_admin()) && !empty($post_categories[$cat_taxonomy])) {

                geodir_editpost_categories_html($cat_taxonomy, $post_id, $post_categories);
            }
            ?>
        </div>
        <script type="text/javascript">

            function show_subcatlist(main_cat, catObj) {
                if (main_cat != '') {
					var url = '<?php echo geodir_get_ajax_url();?>';
                    var cat_taxonomy = '<?php echo $cat_taxonomy;?>';
                    var cat_exclude = '<?php echo base64_encode($cat_exclude);?>';
                    var cat_limit = jQuery('#' + cat_taxonomy).find('#cat_limit').val();
					<?php if ((int)$cat_limit > 0) { ?>
					var selected = parseInt(jQuery('#' + cat_taxonomy).find('.cat_sublist > div.post_catlist_item').length);
					if (cat_limit != '' && selected > 0 && selected >= cat_limit && cat_limit != 0) {
						alert("<?php echo esc_attr(wp_sprintf(__('You have reached category limit of %d categories.', 'geodirectory'), (int)$cat_limit));?>");
						return false;
					}
					<?php } ?>
                    jQuery.post(url, {
                        geodir_ajax: 'category_ajax',
                        cat_tax: cat_taxonomy,
                        main_catid: main_cat,
                        exclude: cat_exclude
                    }, function (data) {
                        if (data != '') {
                            jQuery('#' + cat_taxonomy).find('.cat_sublist').append(data);

                            setTimeout(function () {
                                jQuery('#' + cat_taxonomy).find('.cat_sublist').find('.chosen_select').chosen();
                            }, 200);


                        }
                        maincat_obj = jQuery('#' + cat_taxonomy).find('.main_cat_list');

                        if (cat_limit != '' && jQuery('#' + cat_taxonomy).find('.cat_sublist .chosen_select').length >= cat_limit) {
                            maincat_obj.find('.chosen_select').chosen('destroy');
                            maincat_obj.hide();
                        } else {
                            maincat_obj.show();
                            maincat_obj.find('.chosen_select').chosen('destroy');
                            maincat_obj.find('.chosen_select').prop('selectedIndex', 0);
                            maincat_obj.find('.chosen_select').chosen();
                        }

                        update_listing_cat();

                    });
                }
                update_listing_cat();
            }

            function update_listing_cat(el) {
                var cat_taxonomy = '<?php echo $cat_taxonomy;?>';
                var cat_ids = '';
                var main_cat = '';
                var sub_cat = '';
                var post_cat_str = '';
                var cat_limit = jQuery('#' + cat_taxonomy).find('#cat_limit').val();
				
				var delEl = jQuery(el).closest('.post_catlist_item').find('input.listing_main_cat');
				if (typeof el != 'undefined' && jQuery(delEl).val()) {
					jQuery('.geodir_taxonomy_field').find('select > option[_hc="f"][value="'+jQuery(delEl).val()+'"]').attr('disabled', false);
				}
				jQuery('.geodir_taxonomy_field').find('input.listing_main_cat:checked').each(function() {
					var cV = jQuery(this).val();
					if (parseInt(cV) > 0) {
						jQuery('.geodir_taxonomy_field').find('select > option[_hc="f"][value="'+cV+'"]').attr('disabled', true);
					}
				});

                jQuery('#' + cat_taxonomy).find('.cat_sublist > div').each(function () {
                    main_cat = jQuery(this).find('.listing_main_cat').val();

                    if (jQuery(this).find('.chosen_select').length > 0)
                        sub_cat = jQuery(this).find('.chosen_select').val()

                    if (post_cat_str != '')
                        post_cat_str = post_cat_str + '#';

                    post_cat_str = post_cat_str + main_cat;

                    if (jQuery(this).find('.listing_main_cat').is(':checked')) {
                        cat_ids = cat_ids + ',' + main_cat;
                        post_cat_str = post_cat_str + ',y';

                        if (jQuery(this).find('.post_default_category input').is(':checked'))
                            post_cat_str = post_cat_str + ',d';

                    } else {
                        post_cat_str = post_cat_str + ',n';
                    }

                    if (sub_cat != '' && sub_cat) {
                        cat_ids = cat_ids + ',' + sub_cat;
                        post_cat_str = post_cat_str + ':' + sub_cat;
                    } else {
                        post_cat_str = post_cat_str + ':';
                    }

                });

                maincat_obj = jQuery('#' + cat_taxonomy).find('.main_cat_list');
                if (cat_limit != '' && jQuery('#' + cat_taxonomy).find('.cat_sublist > div.post_catlist_item').length >= cat_limit && cat_limit != 0) {
                    maincat_obj.find('.chosen_select').chosen('destroy');
                    maincat_obj.hide();
                } else {
                    maincat_obj.show();
                    maincat_obj.find('.chosen_select').chosen('destroy');
                    maincat_obj.find('.chosen_select').prop('selectedIndex', 0);
                    maincat_obj.find('.chosen_select').chosen();
                }

                maincat_obj.find('.chosen_select').trigger("chosen:updated");
                jQuery('#' + cat_taxonomy).find('#post_category').val(cat_ids);
                jQuery('#' + cat_taxonomy).find('#post_category_str').val(post_cat_str);
            }
            jQuery(function () {
                update_listing_cat();
            })


        </script>
        <?php
        if (!empty($post_categories) && array_key_exists($cat_taxonomy, $post_categories)) {
            $post_cat_str = $post_categories[$cat_taxonomy];
            $post_cat_array = explode("#", $post_cat_str);
            if (count($post_cat_array) >= $cat_limit && $cat_limit != 0)
                $style = "display:none;";
        }
        ?>
        <div class="main_cat_list" style=" <?php if (isset($style)) {
            echo $style;
        }?> ">
            <?php geodir_get_catlist($cat_taxonomy, 0);  // print main categories list
            ?>
        </div>
    <?php

    }
}

/**
 * Category Selection Interface in add/edit listing form.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $request_taxonomy The taxonomy name.
 * @param int $parrent The parent term ID.
 * @param bool|string $selected The selected value.
 * @param bool $main_selected Not yet implemented.
 * @param bool $default Is this the default category? Default: false.
 * @param string $exclude Excluded terms list. Serialized base64 encoded string.
 */
function geodir_addpost_categories_html($request_taxonomy, $parrent, $selected = false, $main_selected = true, $default = false, $exclude = '')
{
    global $exclude_cats;

    if ($exclude != '') {
        $exclude_cats = maybe_unserialize(base64_decode($exclude));

        if(is_array( $exclude_cats)){
            $exclude_cats = array_map( 'intval', $exclude_cats );
        }else{
            $exclude_cats = intval($exclude_cats);
        }

    }

    if ((is_array($exclude_cats) && !empty($exclude_cats) && !in_array($parrent, $exclude_cats)) ||
        (!is_array($exclude_cats) || empty($exclude_cats))
    ) {
        ?>

        <?php $main_cat = get_term($parrent, $request_taxonomy); ?>

        <div class="post_catlist_item" style="border:1px solid #CCCCCC; margin:5px auto; padding:5px;">
            <img alt="move icon" src="<?php echo geodir_plugin_url() . '/geodirectory-assets/images/move.png';?>"
                 onclick="jQuery(this).closest('div').remove();update_listing_cat(this);" align="right"/>
            <?php /* ?>
		<img src="<?php echo geodir_plugin_url().'/geodirectory-assets/images/move.png';?>" onclick="jQuery(this).closest('div').remove();show_subcatlist();" align="right" /> 
		<?php */ ?>

            <input type="checkbox" value="<?php echo $main_cat->term_id;?>" class="listing_main_cat"
                   onchange="if(jQuery(this).is(':checked')){jQuery(this).closest('div').find('.post_default_category').prop('checked',false).show();}else{jQuery(this).closest('div').find('.post_default_category').prop('checked',false).hide();};update_listing_cat()"
                   checked="checked" disabled="disabled"/>
       <span> 
        <?php printf(__('Add listing in %s category', 'geodirectory'), geodir_ucwords($main_cat->name));?>
        </span>
            <br/>

            <div class="post_default_category">
                <input type="radio" name="post_default_category" value="<?php echo $main_cat->term_id;?>"
                       onchange="update_listing_cat()" <?php if ($default) echo ' checked="checked" ';?>   />
        <span> 
        <?php printf(__('Set %s as default category', 'geodirectory'), geodir_ucwords($main_cat->name));?>
        </span>
            </div>

            <br/>
            <?php
            $cat_terms = get_terms($request_taxonomy, array('parent' => $main_cat->term_id, 'hide_empty' => false, 'exclude' => $exclude_cats));
            if (!empty($cat_terms)) { ?>
                <span> <?php printf(__('Add listing in category', 'geodirectory')); ?></span>
                <?php geodir_get_catlist($request_taxonomy, $parrent, $selected) ?>
            <?php } ?>
        </div>

    <?php }
}


/**
 * Categories HTML for edit post page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $request_taxonomy The taxonomy ID.
 * @param int $request_postid The post ID.
 * @param array $post_categories The post catagories.
 */
function geodir_editpost_categories_html($request_taxonomy, $request_postid, $post_categories)
{

    if (!empty($post_categories) && array_key_exists($request_taxonomy, $post_categories)) {
        $post_cat_str = $post_categories[$request_taxonomy];
        $post_cat_array = explode("#", $post_cat_str);
        if (is_array($post_cat_array)) {
            $post_cat_array = array_unique( $post_cat_array );

			foreach ($post_cat_array as $post_cat_html) {

                $post_cat_info = explode(":", $post_cat_html);
                $post_maincat_str = $post_cat_info[0];

                if (!empty($post_maincat_str)) {
                    $post_maincat_info = explode(",", $post_maincat_str);
                    $post_maincat_id = $post_maincat_info[0];
                    ($post_maincat_info[1] == 'y') ? $post_maincat_selected = true : $post_maincat_selected = false;
                    (end($post_maincat_info) == 'd') ? $post_maincat_default = true : $post_maincat_default = false;
                }
                $post_sub_catid = '';
                if (isset($post_cat_info[1]) && !empty($post_cat_info[1])) {
                    $post_sub_catid = (int)$post_cat_info[1];
                }

                geodir_addpost_categories_html($request_taxonomy, $post_maincat_id, $post_sub_catid, $post_maincat_selected, $post_maincat_default);

            }
        } else {

            $post_cat_info = explode(":", $post_cat_str);
            $post_maincat_str = $post_cat_info[0];

            $post_sub_catid = '';

            if (!empty($post_maincat_str)) {
                $post_maincat_info = explode(",", $post_maincat_str);
                $post_maincat_id = $post_maincat_info[0];
                ($post_maincat_info[1] == 'y') ? $post_maincat_selected = true : $post_maincat_selected = false;
                (end($post_maincat_info) == 'd') ? $post_maincat_default = true : $post_maincat_default = false;
            }

            if (isset($post_cat_info[1]) && !empty($post_cat_info[1])) {
                $post_sub_catid = (int)$post_cat_info[1];
            }

            geodir_addpost_categories_html($request_taxonomy, $post_maincat_id, $post_sub_catid, $post_maincat_selected, $post_maincat_default);

        }
    }
}

/**
 * Get terms of a taxonomy as dropdown.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $cat_taxonomy The taxonomy name.
 * @param int $parrent The parent term ID. Default: 0.
 * @param bool|string $selected The selected value. Default: false.
 */
function geodir_get_catlist($cat_taxonomy, $parrent = 0, $selected = false)
{
    global $exclude_cats;

    $cat_terms = get_terms($cat_taxonomy, array('parent' => $parrent, 'hide_empty' => false, 'exclude' => $exclude_cats));

    if (!empty($cat_terms)) {
        $onchange = '';
        $onchange = ' onchange="show_subcatlist(this.value, this)" ';

        $option_selected = '';
        if (!$selected)
            $option_slected = ' selected="selected" ';

        echo '<select field_type="select" id="' . sanitize_text_field($cat_taxonomy) . '" class="chosen_select" ' . $onchange . ' option-ajaxChosen="false" >';

        echo '<option value="" ' . $option_selected . ' >' . __('Select Category', 'geodirectory') . '</option>';

        foreach ($cat_terms as $cat_term) {
            $option_selected = '';
            if ($selected == $cat_term->term_id)
                $option_selected = ' selected="selected" ';

            // Count child terms
            $child_terms = get_terms( $cat_taxonomy, array( 'parent' => $cat_term->term_id, 'hide_empty' => false, 'exclude' => $exclude_cats, 'number' => 1 ) );
            $has_child = !empty( $child_terms ) ? 't' : 'f';

            echo '<option  ' . $option_selected . ' alt="' . $cat_term->taxonomy . '" title="' . geodir_utf8_ucfirst($cat_term->name) . '" value="' . $cat_term->term_id . '" _hc="' . $has_child . '" >' . geodir_utf8_ucfirst($cat_term->name) . '</option>';
        }
        echo '</select>';
    }
}

/**
 * Custom post type messages for admin actions.
 *
 * Replaces "Post" in the update messages for custom post types on the "Edit" post screen.
 * For example "Post updated. View Post." becomes "Place updated. View Place".
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post WordPress Post object.
 * @global int $post_ID WordPress Post ID.
 */
function geodir_custom_update_messages($messages)
{
    global $post, $post_ID;

    $post_types = get_post_types(array('show_ui' => true, '_builtin' => false), 'objects');

    foreach ($post_types as $post_type => $post_object) {

        $messages[$post_type] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(__('%s updated. <a href="%s">View %s</a>', 'geodirectory'), $post_object->labels->singular_name, esc_url(get_permalink($post_ID)), $post_object->labels->singular_name),
            2 => __('Custom field updated.', 'geodirectory'),
            3 => __('Custom field deleted.', 'geodirectory'),
            4 => sprintf(__('%s updated.', 'geodirectory'), $post_object->labels->singular_name),
            5 => isset($_GET['revision']) ? sprintf(__('%s restored to revision from %s', 'geodirectory'), $post_object->labels->singular_name, wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 => sprintf(__('%s published. <a href="%s">View %s</a>', 'geodirectory'), $post_object->labels->singular_name, esc_url(get_permalink($post_ID)), $post_object->labels->singular_name),
            7 => sprintf(__('%s saved.', 'geodirectory'), $post_object->labels->singular_name),
            8 => sprintf(__('%s submitted. <a target="_blank" href="%s">Preview %s</a>', 'geodirectory'), $post_object->labels->singular_name, esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))), $post_object->labels->singular_name),
            9 => sprintf(__('%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview %s</a>', 'geodirectory'), $post_object->labels->singular_name, date_i18n(__('M j, Y @ G:i', 'geodirectory'), strtotime($post->post_date)), esc_url(get_permalink($post_ID)), $post_object->labels->singular_name),
            10 => sprintf(__('%s draft updated. <a target="_blank" href="%s">Preview %s</a>', 'geodirectory'), $post_object->labels->singular_name, esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))), $post_object->labels->singular_name),
        );
    }

    return $messages;
}


/**
 * Register default custom Post Types and Taxonomies.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */

function geodir_register_defaults()
{

    global $wpdb;

    $menu_icon = geodir_plugin_url() . '/geodirectory-assets/images/favicon.ico';

    if (!$listing_slug = get_option('geodir_listing_prefix'))
        $listing_slug = 'places';

    /**
     * Taxonomies
     **/
    //if ( ! taxonomy_exists('gd_place_tags') )
    {

        $gd_placetags = array();
        $gd_placetags['object_type'] = 'gd_place';
        $gd_placetags['listing_slug'] = $listing_slug . '/tags';
        $gd_placetags['args'] = array(
            'public' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => $listing_slug . '/tags', 'with_front' => false, 'hierarchical' => true),
            'query_var' => true,

            'labels' => array(
                'name' => __('Place Tags', 'geodirectory'),
                'singular_name' => __('Place Tag', 'geodirectory'),
                'search_items' => __('Search Place Tags', 'geodirectory'),
                'popular_items' => __('Popular Place Tags', 'geodirectory'),
                'all_items' => __('All Place Tags', 'geodirectory'),
                'edit_item' => __('Edit Place Tag', 'geodirectory'),
                'update_item' => __('Update Place Tag', 'geodirectory'),
                'add_new_item' => __('Add New Place Tag', 'geodirectory'),
                'new_item_name' => __('New Place Tag Name', 'geodirectory'),
                'add_or_remove_items' => __('Add or remove Place tags', 'geodirectory'),
                'choose_from_most_used' => __('Choose from the most used Place tags', 'geodirectory'),
                'separate_items_with_commas' => __('Separate Place tags with commas', 'geodirectory'),
            ),
        );


        $geodir_taxonomies = get_option('geodir_taxonomies');
        $geodir_taxonomies['gd_place_tags'] = $gd_placetags;
        update_option('geodir_taxonomies', $geodir_taxonomies);


        // Update post types and delete tmp options
        flush_rewrite_rules();

    }

    //if ( ! taxonomy_exists('gd_placecategory') )
    {

        $gd_placecategory = array();
        $gd_placecategory['object_type'] = 'gd_place';
        $gd_placecategory['listing_slug'] = $listing_slug;
        $gd_placecategory['args'] = array(
            'public' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => $listing_slug, 'with_front' => false, 'hierarchical' => true),
            'query_var' => true,
            'labels' => array(
                'name' => __('Place Categories', 'geodirectory'),
                'singular_name' => __('Place Category', 'geodirectory'),
                'search_items' => __('Search Place Categories', 'geodirectory'),
                'popular_items' => __('Popular Place Categories', 'geodirectory'),
                'all_items' => __('All Place Categories', 'geodirectory'),
                'edit_item' => __('Edit Place Category', 'geodirectory'),
                'update_item' => __('Update Place Category', 'geodirectory'),
                'add_new_item' => __('Add New Place Category', 'geodirectory'),
                'new_item_name' => __('New Place Category', 'geodirectory'),
                'add_or_remove_items' => __('Add or remove Place categories', 'geodirectory'),
            ),
        );


        $geodir_taxonomies = get_option('geodir_taxonomies');
        $geodir_taxonomies['gd_placecategory'] = $gd_placecategory;
        update_option('geodir_taxonomies', $geodir_taxonomies);


        flush_rewrite_rules();
    }

    /**
     * Post Types
     **/

    //if ( ! post_type_exists('gd_place') )
    {

        $labels = array(
            'name' => __('Places', 'geodirectory'),
            'singular_name' => __('Place', 'geodirectory'),
            'add_new' => __('Add New', 'geodirectory'),
            'add_new_item' => __('Add New Place', 'geodirectory'),
            'edit_item' => __('Edit Place', 'geodirectory'),
            'new_item' => __('New Place', 'geodirectory'),
            'view_item' => __('View Place', 'geodirectory'),
            'search_items' => __('Search Places', 'geodirectory'),
            'not_found' => __('No Place Found', 'geodirectory'),
            'not_found_in_trash' => __('No Place Found In Trash', 'geodirectory'));

        $place_default = array(
            'labels' => $labels,
            'can_export' => true,
            'capability_type' => 'post',
            'description' => 'Place post type.',
            'has_archive' => $listing_slug,
            'hierarchical' => false,
            'map_meta_cap' => true,
            'menu_icon' => $menu_icon,
            'public' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $listing_slug , 'with_front' => false, 'hierarchical' => true, 'feeds' => true),
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', /*'revisions', 'post-formats'*/),
            'taxonomies' => array('gd_placecategory', 'gd_place_tags'));

        //Update custom post types
        $geodir_post_types = get_option('geodir_post_types');
        $geodir_post_types['gd_place'] = $place_default;
        update_option('geodir_post_types', $geodir_post_types);

        // Update post types and delete tmp options
        flush_rewrite_rules();
    }


    geodir_register_taxonomies();
    geodir_register_post_types();

    //die;

}

$gd_wpml_get_languages = "";
function gd_wpml_get_lang_from_url($url) {
    global $sitepress, $gd_wpml_get_languages;
    
    if (geodir_is_wpml()) {
        return $sitepress->get_language_from_url($url);
    }
    
    if (isset($_REQUEST['lang']) && $_REQUEST['lang']) {
        return $_REQUEST['lang'];
    }

    $url = str_replace(array("http://","https://"),"",$url);

    // site_url() seems to work better than get_bloginfo('url') here, WPML can change get_bloginfo('url') to add the lang.
    $site_url = str_replace(array("http://","https://"),"",site_url());

    $url = str_replace($site_url,"",$url);

    $segments = explode('/', trim($url, '/'));

    if ($gd_wpml_get_languages) {
        $langs = $gd_wpml_get_languages;
    } else {
        $gd_wpml_get_languages = $sitepress->get_active_languages();
    }

    if (isset($segments[0]) && $segments[0] && array_key_exists($segments[0], $gd_wpml_get_languages)) {
        return $segments[0];
    }

    return false;
}

function gd_wpml_slug_translation_turned_on($post_type) {

    global $sitepress;
    $settings = $sitepress->get_settings();
    return isset($settings['posts_slug_translation']['types'][$post_type])
    && $settings['posts_slug_translation']['types'][$post_type]
    && isset($settings['posts_slug_translation']['on'])
    && $settings['posts_slug_translation']['on'];
}


$comment_post_cache = array();
$gd_permalink_cache = array();
/**
 * Returns permalink structure using post link.
 *
 * @since 1.0.0
 * @since 1.5.9 Fix the broken links when domain name contain CPT and home page 
 *              is set to current location.
 * @since 1.6.18 Fix with WPML the location terms added twice when CPT slug is translated.
 * 
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global object $wp_query WordPress Query object.
 * @global object $post WordPress Post object.
 * @param string $post_link The post link.
 * @param object $post_obj The post object.
 * @param string $leavename Not yet implemented.
 * @param bool $sample Is this a sample post?.
 * @return string The post link.
 */
function geodir_listing_permalink_structure($post_link, $post_obj, $leavename, $sample)
{
    //echo $post_link."<br />".$sample ;


    global $wpdb, $wp_query, $plugin_prefix, $post, $comment_post_cache, $gd_permalink_cache;
    if (isset($post_obj->ID) && isset($post->ID) && $post_obj->ID == $post->ID) {
    } elseif (isset($post_obj->post_status) && $post_obj->post_status == 'auto-draft') {
        return $post_link;
    } else {
        $orig_post = $post;
        $post = $post_obj;
    }

    if (in_array($post->post_type, geodir_get_posttypes())) {

        // if we dont have a GD post then try to grab it
        if(!isset($post->default_category)){
            $gd_post = geodir_get_post_info($post->ID);
            if(!empty($gd_post)){
                $post = $gd_post;
            }
        }


        $post_types = get_option('geodir_post_types');
        $slug = $post_types[$post->post_type]['rewrite']['slug'];

        // Alter the CPT slug if WPML is set to do so
        if(geodir_wpml_is_post_type_translated($post->post_type)){
            if ( gd_wpml_slug_translation_turned_on( $post->post_type ) && $language_code = gd_wpml_get_lang_from_url($post_link)) {

                $org_slug = $slug;
                $slug = apply_filters( 'wpml_translate_single_string',
                    $slug,
                    'WordPress',
                    'URL slug: ' . $slug,
                    $language_code);

                if(!$slug){$slug = $org_slug;}

            }
        }

        if (function_exists('geodir_location_geo_home_link')) {
            remove_filter('home_url', 'geodir_location_geo_home_link', 100000);
        }
        
        // Fix slug problem when slug matches part of host or base url/ Ex: url -> www.abcxyz.com & slug -> xyz.
        $site_url = trailingslashit(get_bloginfo('url'));
        
        if (function_exists('geodir_location_geo_home_link')) {
            add_filter('home_url', 'geodir_location_geo_home_link', 100000, 2);
        }

        $fix_url = strpos($post_link, $site_url) === 0 ? true : false;
        if ($fix_url) {
            $post_link = str_replace($site_url, '', $post_link);
        }

        $post_link = trailingslashit(
            preg_replace(  "/" . preg_quote( $slug, "/" ) . "/", $slug ."/%gd_taxonomy%",$post_link, 1 )
        );

        if ($fix_url) {
            $post_link = $site_url . $post_link;
        }

        if (isset($comment_post_cache[$post->ID])) {
            $post = $comment_post_cache[$post->ID];
        }
        if (isset($gd_permalink_cache[$post->ID]) && $gd_permalink_cache[$post->ID] && !$sample) {
            $post_id = $post->ID;
            if (isset($orig_post)) {
                $post = $orig_post;
            }
            return $gd_permalink_cache[$post_id];
        }

        if (!isset($post->post_locations)) {
            $post_type = $post->post_type;
            $ID = $post->ID;
            $post2 = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * from " . $plugin_prefix . $post->post_type . "_detail WHERE post_id = %d ",
                    array($post->ID)
                )
            );

            $post = (object)array_merge((array)$post, (array)$post2);

            $comment_post_cache[$post->ID] = $post;
        }



        if (false !== strpos($post_link, '%gd_taxonomy%')) {

            if ( apply_filters("geodir_add_location_url_to_url",get_option('geodir_add_location_url'),$post->post_type,$post)) {
                $location_request = '';


                if (!empty($post->post_locations)) {
                    $geodir_arr_locations = explode(',', $post->post_locations);
                    if (count($geodir_arr_locations) == 3) {
                        $post->city_slug = str_replace('[', '', $geodir_arr_locations[0]);
                        $post->city_slug = str_replace(']', '', $post->city_slug);
                        $post->region_slug = str_replace('[', '', $geodir_arr_locations[1]);
                        $post->region_slug = str_replace(']', '', $post->region_slug);
                        $post->country_slug = str_replace('[', '', $geodir_arr_locations[2]);
                        $post->country_slug = str_replace(']', '', $post->country_slug);

                        $post_location = (object)array('country_slug' => $post->country_slug,
                            'region_slug' => $post->region_slug,
                            'city_slug' => $post->city_slug
                        );

                    } else
                        $post_location = geodir_get_location();


                } else {

                    $post_location_sql = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT post_locations from " . $plugin_prefix . $post->post_type . "_detail WHERE post_id = %d ",
                            array($post->ID)
                        )
                    );

                    if (!empty($post_location_sql) && is_array($post_location_sql) && !empty($post_location_sql[0]->post_locations)) {

                        $geodir_arr_locations = explode(',', $post_location_sql[0]->post_locations);
                        if (count($geodir_arr_locations) == 3) {
                            $post->city_slug = str_replace('[', '', $geodir_arr_locations[0]);
                            $post->city_slug = str_replace(']', '', $post->city_slug);
                            $post->region_slug = str_replace('[', '', $geodir_arr_locations[1]);
                            $post->region_slug = str_replace(']', '', $post->region_slug);
                            $post->country_slug = str_replace('[', '', $geodir_arr_locations[2]);
                            $post->country_slug = str_replace(']', '', $post->country_slug);

                            $post_location = (object)array('country_slug' => $post->country_slug,
                                'region_slug' => $post->region_slug,
                                'city_slug' => $post->city_slug
                            );

                        }
                    } else
                        $post_location = geodir_get_location();
                }


                if (!empty($post_location)) {
                    $country_slug = isset($post_location->country_slug) ? $post_location->country_slug : '';
					$region_slug = isset($post_location->region_slug) ? $post_location->region_slug : '';
					$city_slug = isset($post_location->city_slug) ? $post_location->city_slug : '';
					
					$geodir_show_location_url = get_option('geodir_show_location_url');
					
					$location_slug = array();
					if ($geodir_show_location_url == 'all') {
						$location_slug[] = $country_slug;
						$location_slug[] = $region_slug;
					} else if ($geodir_show_location_url == 'country_city') {
						$location_slug[] = $country_slug;
					} else if ($geodir_show_location_url == 'region_city') {
						$location_slug[] = $region_slug;
					}
					$location_slug[] = $city_slug;
					
					$location_request .= implode('/', $location_slug) . '/';
                }
            }

            if (get_option('geodir_add_categories_url')) {

                $term_request = '';
                $taxonomies = geodir_get_taxonomies($post->post_type);
                $taxonomies = !empty($taxonomies) && is_array($taxonomies) ? end($taxonomies) : '';

                if (!empty($post->default_category)) {
                    $post_terms = $post->default_category;
                } else {
                    $post_terms = '';

                    if(isset($_POST['post_default_category']) && $_POST['post_default_category']){
                        $post_terms = absint($_POST['post_default_category']);
                    }elseif(isset($_POST['post_category'][$taxonomies]) && $_POST['post_category'][$taxonomies]) {
                        $post_terms = is_array($_POST['post_category'][$taxonomies]) ? $_POST['post_category'][$taxonomies] : explode(",", trim($_POST['post_category'][$taxonomies], ","));
                        $post_terms = !empty($post_terms) ? absint($post_terms[0]) : 0;
                    }elseif (isset($post->{$taxonomies})) {
                        $post_terms = explode(",", trim($post->{$taxonomies}, ","));
                        $post_terms = $post_terms[0];
                    }

                    if (!$post_terms)
                        $post_terms = geodir_get_post_meta($post->ID, 'default_category', true);

                    if (!$post_terms) {
                        $post_terms = geodir_get_post_meta($post->ID, $taxonomies, true);

                        if ($post_terms) {
                            $post_terms = explode(",", trim($post_terms, ","));
                            $post_terms = $post_terms[0];
                        }
                    }
                }

                $term = get_term_by('id', $post_terms, $taxonomies);

                if (!empty($term))
                    $term_request = $term->slug;
                //$term_request = $term->slug.'/';
            }

            $request_term = '';
            $listingurl_separator = '';
            //$detailurl_separator = get_option('geodir_detailurl_separator');
            $detailurl_separator = '';
            if (isset($location_request) && $location_request != '' && isset($term_request) && $term_request != '') {
                $request_term = $location_request;
                //$listingurl_separator = get_option('geodir_listingurl_separator');
                //$request_term .= $listingurl_separator.'/'.$term_request;
                $request_term .= $term_request;

            } else {
                if (isset($location_request) && $location_request != '') $request_term = $location_request;

                if (isset($term_request) && $term_request != '') $request_term .= $term_request;
            }
            $request_term = trim($request_term, '/');
            
            // Fix with WPML the location terms added twice when CPT slug is translated.
            if ($sample && !empty($location_request) && geodir_is_wpml() && strpos($post_link, '%gd_taxonomy%/' . $request_term . $detailurl_separator) !== false) {
                $post_link = str_replace('%gd_taxonomy%/', '', $post_link);
            }
            
            if (!empty($request_term))
                $post_link = str_replace('%gd_taxonomy%', $request_term . $detailurl_separator, $post_link);
            else
                $post_link = str_replace('/%gd_taxonomy%', $request_term . $detailurl_separator, $post_link);
            //echo $post_link ;
        }
        // temp cache the permalink
        if (!$sample && (!isset($_REQUEST['geodir_ajax']) || (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] != 'add_listing'))) {
            $gd_permalink_cache[$post->ID] = $post_link;
        }
    }
    if (isset($orig_post)) {
        $post = $orig_post;
    }

    return $post_link;
}

/**
 * Returns the term link with parameters.
 *
 * @since 1.0.0
 * @since 1.5.7 Changes for the neighbourhood system improvement.
 * @since 1.6.11 Details page add locations to the term links.
 * @package GeoDirectory
 * @param string $termlink The term link
 * @param object $term Not yet implemented.
 * @param string $taxonomy The taxonomy name.
 * @return string The term link.
 */
function geodir_term_link($termlink, $term, $taxonomy) {
    $geodir_taxonomies = geodir_get_taxonomies('', true);

    if (isset($taxonomy) && !empty($geodir_taxonomies) && in_array($taxonomy, $geodir_taxonomies)) {
        global $geodir_add_location_url, $gd_session;
        $include_location = false;
        $request_term = array();
        $add_location_url = get_option('geodir_add_location_url');
        $location_manager = defined('POST_LOCATION_TABLE') ? true : false;

        $listing_slug = geodir_get_listing_slug($taxonomy);

        if ($geodir_add_location_url != NULL && $geodir_add_location_url != '') {
            if ($geodir_add_location_url && $add_location_url) {
                $include_location = true;
            }
        } elseif ($add_location_url && $gd_session->get('gd_multi_location') == 1) {
            $include_location = true;
        } elseif ($add_location_url && $location_manager && geodir_is_page('detail')) {
            $include_location = true;
        }

        if ($include_location) {
            global $post;
            
            $neighbourhood_active = $location_manager && get_option('location_neighbourhoods') ? true : false;
            
            if (geodir_is_page('detail') && isset($post->country_slug)) {
                $location_terms = array(
                    'gd_country' => $post->country_slug,
                    'gd_region' => $post->region_slug,
                    'gd_city' => $post->city_slug
                );
                
                if ($neighbourhood_active && !empty($location_terms['gd_city']) && $gd_ses_neighbourhood = $gd_session->get('gd_neighbourhood')) {
                    $location_terms['gd_neighbourhood'] = $gd_ses_neighbourhood;
                }
            } else {
                $location_terms = geodir_get_current_location_terms('query_vars');
            }

            $geodir_show_location_url = get_option('geodir_show_location_url');
            $location_terms = geodir_remove_location_terms($location_terms);

            if (!empty($location_terms)) {
                $url_separator = '';

                if (get_option('permalink_structure') != '') {
                    $old_listing_slug = '/' . $listing_slug . '/';
                    $request_term = implode("/", $location_terms);
                    $new_listing_slug = '/' . $listing_slug . '/' . $request_term . '/';

                    $termlink = substr_replace($termlink, $new_listing_slug, strpos($termlink, $old_listing_slug), strlen($old_listing_slug));
                } else {
                    $termlink = geodir_getlink($termlink, $request_term);
                }
            }
        }

        // Alter the CPT slug is WPML is set to do so
        /* we can replace this with the below function
        if(geodir_is_wpml()){
            global $sitepress;
            $post_type = str_replace("category","",$taxonomy);
            $termlink = $sitepress->post_type_archive_link_filter( $termlink, $post_type);
        }*/

        // Alter the CPT slug if WPML is set to do so
        if (geodir_is_wpml()) {
            $post_types = get_option('geodir_post_types');
            $post_type = str_replace("category","",$taxonomy);
            $post_type = str_replace("_tags","",$post_type);
            $slug = $post_types[$post_type]['rewrite']['slug'];
            if (geodir_wpml_is_post_type_translated($post_type) && gd_wpml_slug_translation_turned_on($post_type)) {
                global $sitepress;
                $default_lang = $sitepress->get_default_language();
                $language_code = gd_wpml_get_lang_from_url($termlink);
                if (!$language_code ) {
                    $language_code  = $default_lang;
                }

                $org_slug = $slug;
                $slug = apply_filters('wpml_translate_single_string', $slug, 'WordPress', 'URL slug: ' . $slug, $language_code);

                if (!$slug) {
                    $slug = $org_slug;
                }

                $termlink = trailingslashit(preg_replace("/" . preg_quote($org_slug, "/") . "/", $slug  ,$termlink, 1));
            }
        }
    }
    
    return $termlink;
}

/**
 * Returns the post type link with parameters.
 *
 * @since 1.0.0
 * @since 1.5.5 Fixed post type archive link for selected location.
 * @package GeoDirectory
 *
 * @global bool $geodir_add_location_url If true it will add location name in url.
 * @global object $post WordPress Post object.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param string $link The post link.
 * @param string $post_type The post type.
 * @return string The modified link.
 */
function geodir_posttype_link($link, $post_type) {
	global $geodir_add_location_url, $post, $gd_session;
	
	$location_terms = array();
	
	if (in_array($post_type, geodir_get_posttypes())) {
		if (get_option('geodir_add_location_url') && $gd_session->get('gd_multi_location') == 1) {
			if(geodir_is_page('detail') && !empty($post) && isset($post->country_slug)) {
                $location_terms = array(
                    'gd_country' => $post->country_slug,
                    'gd_region' => $post->region_slug,
                    'gd_city' => $post->city_slug
                );
            } else {
                $location_terms = geodir_get_current_location_terms('query_vars');
            }
			
			$location_terms = geodir_remove_location_terms($location_terms);
			
			if (!empty($location_terms)) {
				if (get_option('permalink_structure') != '') {
					$location_terms = implode("/", $location_terms);
					$location_terms = rtrim($location_terms, '/');
					
					$link .= urldecode($location_terms) . '/';
				} else {
					$link = geodir_getlink($link, $location_terms);
				}
			}
		}
	}
	
	return $link;
}

/**
 * Print or Get post type singular label.
 *
 * @since 1.0.0
 * @since 1.6.16 New $translate parameter added.
 * @package GeoDirectory
 * @param string $post_type The post type.
 * @param bool $echo Prints the label when set to true.
 * @param bool $translate Returns translated label if True. DefauT false.
 * @return void|string Label.
 */
function get_post_type_singular_label($post_type, $echo = false, $translate = false) {
    $obj_post_type = get_post_type_object($post_type);
    if (!is_object($obj_post_type)) {
        return;
    }
    
    $label = $translate ? __($obj_post_type->labels->singular_name, 'geodirectory') : $obj_post_type->labels->singular_name;
    
    if ($echo)
        echo $label;
    else
        return $label;
}

/**
 * Print or Get post type plural label.
 *
 * @since 1.0.0
 * @since 1.6.16 New $translate parameter added.
 * @package GeoDirectory
 * @param string $post_type The post type.
 * @param bool $echo Prints the label when set to true.
 * @param bool $translate Returns translated label if True. DefauT false.
 * @return void|string Label.
 */
function get_post_type_plural_label($post_type, $echo = false, $translate = false) {
    $all_postypes = geodir_get_posttypes();

    if (!in_array($post_type, $all_postypes))
        return false;

    $obj_post_type = get_post_type_object($post_type);
    
    $label = $translate ? __($obj_post_type->labels->name, 'geodirectory') : $obj_post_type->labels->name;
    
    if ($echo)
        echo $label;
    else
        return $label;
}

/**
 * Checks whether a term exists or not.
 *
 * Returns term data on success, bool when failure.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param int|string $term The term ID or slug.
 * @param string $taxonomy The taxonomy name.
 * @param int $parent Parent term ID.
 * @return bool|object Term data.
 */
function geodir_term_exists($term, $taxonomy = '', $parent = 0)
{
    global $wpdb;

    $select = "SELECT term_id FROM $wpdb->terms as t WHERE ";
    $tax_select = "SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE ";

    if (is_int($term)) {
        if (0 == $term)
            return 0;
        $where = 't.term_id = %d';
        if (!empty($taxonomy))
            return $wpdb->get_row($wpdb->prepare($tax_select . $where . " AND tt.taxonomy = %s", $term, $taxonomy), ARRAY_A);
        else
            return $wpdb->get_var($wpdb->prepare($select . $where, $term));
    }

    $term = trim(wp_unslash($term));

    if ('' === $slug = sanitize_title($term))
        return 0;

    $where = 't.slug = %s';

    $where_fields = array($slug);
    if (!empty($taxonomy)) {
        $parent = (int)$parent;
        if ($parent > 0) {
            $where_fields[] = $parent;
            $else_where_fields[] = $parent;
            $where .= ' AND tt.parent = %d';

        }

        $where_fields[] = $taxonomy;


        if ($result = $wpdb->get_row($wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $where AND tt.taxonomy = %s", $where_fields), ARRAY_A))
            return $result;

        return false;
    }

    if ($result = $wpdb->get_var($wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $where", $where_fields)))
        return $result;

    return false;
}

/**
 * Reset term icon values.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_get_term_icon_rebuild()
{

    update_option('gd_term_icons', '');

}

/**
 * Gets term icon using term ID.
 *
 * If term ID not passed returns all icons.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param int|bool $term_id The term ID.
 * @param bool $rebuild Force rebuild the icons when set to true.
 * @return mixed|string|void Term icon(s).
 */
function geodir_get_term_icon($term_id = false, $rebuild = false)
{
    global $wpdb;
    if (!$rebuild) {
        $terms_icons = get_option('gd_term_icons');
    } else {
        $terms_icons = array();
    }

    if (empty($terms_icons)) {
        $terms_icons = array();
        $default_icon_url = get_option('geodir_default_marker_icon');
        $taxonomy = geodir_get_taxonomies();
        $post_types = geodir_get_posttypes();
        $tax_arr = array();
        foreach ($post_types as $post_type) {
            $tax_arr[] = "'" . $post_type . "category'";
        }
        $tax_c = implode(',', $tax_arr);
        $terms = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy IN ($tax_c)");
        //$terms = get_terms( $taxonomy );

        if($terms) {
            foreach ($terms as $term) {
                $post_type = str_replace("category", "", $term->taxonomy);
                $a_terms[$post_type][] = $term;

            }
        }

        if($a_terms) {
            foreach ($a_terms as $pt => $t2) {

                foreach ($t2 as $term) {
                    $term_icon = geodir_get_tax_meta($term->term_id, 'ct_cat_icon', false, $pt);
                    if ($term_icon) {
                        $term_icon_url = $term_icon["src"];
                    } else {
                        $term_icon_url = $default_icon_url;
                    }
                    $terms_icons[$term->term_id] = $term_icon_url;
                }
            }
        }

        update_option('gd_term_icons', $terms_icons);
    }

    if ($term_id && isset($terms_icons[$term_id])) {
        return $terms_icons[$term_id];
    } elseif ($term_id && !isset($terms_icons[$term_id])) {
        return get_option('geodir_default_marker_icon');
    }

    if (is_ssl()) {
        $terms_icons = str_replace("http:","https:",$terms_icons );
    }

    return apply_filters('geodir_get_term_icons', $terms_icons, $term_id);
}