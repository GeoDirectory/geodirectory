<?php
/**
 * Template functions
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/**
 * Locates template based on the template type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $post_type The post type.
 * @global object $wp WordPress object.
 * @global object $post WordPress post object.
 * @param string $template The template type.
 * @return bool|string The template path.
 */
function geodir_locate_template($template = '')
{
    global $post_type, $wp, $post;
    $fields = array();

    switch ($template):
        case 'signup':
            return $template = locate_template(array("geodirectory/geodir-signup.php"));
            break;
        case 'add-listing':
            $gd_post_types = geodir_get_posttypes();
            
            if (!(!empty($post_type) && in_array($post_type, $gd_post_types))) {
                $post_type = '';
            }
            
            $sc_post_type = '';
            if (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'gd_add_listing')) {
                $listing_page_id = $post->ID;
                
                $regex_pattern = get_shortcode_regex();
                preg_match('/'.$regex_pattern.'/s', $post->post_content, $regex_matches);
                
                if (!empty($regex_matches) && isset($regex_matches[2]) == 'gd_add_listing' && isset($regex_matches[3])) {
                    $shortcode_atts = shortcode_parse_atts($regex_matches[3]);
                    $sc_post_type = !empty($shortcode_atts) && isset($shortcode_atts['listing_type']) && !empty($shortcode_atts['listing_type']) ? $shortcode_atts['listing_type'] : '';
                }
            } else {
                $listing_page_id = geodir_add_listing_page_id();
            }
            
            $is_wpml = geodir_is_wpml() ? true : false;

            if ($listing_page_id != '' && (is_page($listing_page_id) || ($is_wpml && !empty($wp->query_vars['page_id']))) && isset($_REQUEST['listing_type'])
                && in_array($_REQUEST['listing_type'], $gd_post_types)) {
                $post_type = sanitize_text_field($_REQUEST['listing_type']);
            }
            
            if (empty($post_type) && !isset($_REQUEST['pid'])) {
                $pagename = $wp->query_vars['pagename'];
                
                if (!empty($gd_post_types)) {
                    $post_type = $gd_post_types[0];
                }
                
                if ($sc_post_type != '') {
                    $post_type = $sc_post_type;
                }
                
                if (empty($post_type) && !empty($gd_post_types)) {
                    $post_type = $gd_post_types[0];
                }
                
                if ($is_wpml && !empty($wp->query_vars['page_id'])) {
                    wp_redirect(geodir_getlink(get_permalink($wp->query_vars['page_id']), array('listing_type' => $post_type)));
                } else {
                    wp_redirect(trailingslashit(get_site_url()) . $pagename . '/?listing_type=' . $post_type);
                }
                gd_die();
            }
            return $template = locate_template(array("geodirectory/add-{$post_type}.php", "geodirectory/add-listing.php"));
            break;
        case 'success':
            $success_page_id = geodir_success_page_id();
            if ($success_page_id != '' && is_page($success_page_id) && isset($_REQUEST['listing_type'])
                && in_array($_REQUEST['listing_type'], geodir_get_posttypes())
            )
                $post_type = sanitize_text_field($_REQUEST['listing_type']);
            return $template = locate_template(array("geodirectory/{$post_type}-success.php", "geodirectory/listing-success.php"));
            break;
        case 'detail':
        case 'preview':
            if (in_array(get_post_type(), geodir_get_posttypes()))
                $post_type = get_post_type();
            return $template = locate_template(array("geodirectory/single-{$post_type}.php", "geodirectory/listing-detail.php"));
            break;
        case 'listing':
            $templates = array();
            if (is_post_type_archive() && in_array(get_post_type(), geodir_get_posttypes())) {
                $post_type = get_post_type();
                $templates[] = "geodirectory/archive-$post_type.php";
            }


            if (is_tax() && geodir_get_taxonomy_posttype()) {
                $query_obj = get_queried_object();
                $curr_taxonomy = isset($query_obj->taxonomy) ? $query_obj->taxonomy : '';
                $curr_term = isset($query_obj->slug) ? $query_obj->slug : '';
                $templates[] = "geodirectory/taxonomy-$curr_taxonomy-$curr_term.php";
                $templates[] = "geodirectory/taxonomy-$curr_taxonomy.php";
            }

            $templates[] = "geodirectory/geodir-listing.php";

            return $template = locate_template($templates);
            break;
        case 'information':
            return $template = locate_template(array("geodirectory/geodir-information.php"));
            break;
        case 'author':
            return $template = locate_template(array("geodirectory/geodir-author.php"));
            break;
        case 'search':
            return $template = locate_template(array("geodirectory/geodir-search.php"));
            break;
        case 'location':
            return $template = locate_template(array("geodirectory/geodir-location.php"));
            break;
        case 'geodir-home':
            return $template = locate_template(array("geodirectory/geodir-home.php"));
            break;
        case 'listing-listview':
            $template = locate_template(array("geodirectory/listing-listview.php"));
            if (!$template) {
                $template = geodir_plugin_path() . '/geodirectory-templates/listing-listview.php';
            }
            return $template;
            break;
        case 'widget-listing-listview':
            $template = locate_template(array("geodirectory/widget-listing-listview.php"));
            if (!$template) {
                $template = geodir_plugin_path() . '/geodirectory-templates/widget-listing-listview.php';
            }
            return $template;
            break;
    endswitch;

    return false;

}

/**
 * Loads template based on the current page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @todo $wp_query declared twice - fix it.
 * @global object $post The current post object.
 * @global object $current_user Current user object.
 * @param string $template The template path.
 * @return bool|string The template path.
 */
function geodir_template_loader($template)
{

    global $wp_query;

    /**
     * Filter the custom page list.
     *
     * @since 1.0.0
     */
    $geodir_custom_page_list = apply_filters('geodir_set_custom_pages', array(
        'geodir_signup_page' =>
            apply_filters('geodir_set_custom_signup_page', false),
        'geodir_add_listing_page' =>
            apply_filters('geodir_set_custom_add_listing_page', false),
        'geodir_preview_page' =>
            apply_filters('geodir_set_custom_preview_page', false),
        'geodir_listing_success_page' =>
            apply_filters('geodir_set_custom_listing_success_page', false),
        'geodir_listing_detail_page' =>
            apply_filters('geodir_set_custom_listing_detail_page', false),
        'geodir_listing_page' =>
            apply_filters('geodir_set_custom_listing_page', false),
        'geodir_search_page' =>
            apply_filters('geodir_set_custom_search_page', false),
        'geodir_author_page' =>
            apply_filters('geodir_set_custom_author_page', false),
        'geodir_home_map_page' =>
            apply_filters('geodir_set_custom_home_map_page', false)
    ));


    if (geodir_is_page('login') || $geodir_custom_page_list['geodir_signup_page']) {

        $template = geodir_locate_template('signup');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-signup.php';

        /**
         * Filter the signup template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_signup', $template);
    }

    if (geodir_is_page('add-listing') || $geodir_custom_page_list['geodir_add_listing_page']) {
        if (!geodir_is_default_location_set()) {
            global $information;
            $information = sprintf(__('Please %sclick here%s to set a default location, this will make the plugin work properly.', 'geodirectory'), '<a href=\'' . admin_url('admin.php?page=geodirectory&tab=default_location_settings') . '\'>', '</a>');

            $template = geodir_locate_template('information');

            if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-information.php';
            /**
             * Filter the information template path.
             *
             * @since 1.0.0
             * @param string $template The template path.
             */
            return $template = apply_filters('geodir_template_information', $template);
        }
        // check if pid exists in the record if yes then check if this post belongs to the user who is logged in.
        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            /// WPML
            if (geodir_wpml_is_post_type_translated(get_post_type((int)$_GET['pid'])) && $duplicate_of = wpml_get_master_post_from_duplicate((int)$_GET['pid'])) {
                global $sitepress;
                
                $lang_of_duplicate = geodir_get_language_for_element($duplicate_of, 'post_' . get_post_type($duplicate_of));
                $sitepress->switch_lang($lang_of_duplicate, true);
        
                $redirect_to = get_permalink(geodir_add_listing_page_id());
                $_GET['pid'] = $duplicate_of;
                if (!empty($_GET)) {
                    $redirect_to = add_query_arg($_GET, $redirect_to);
                }
                wp_redirect($redirect_to);
                exit;
            }
            /// WPML
            
            global $information;
            $information = __('This listing does not belong to your account, please check the listing id carefully.', 'geodirectory');
            $is_current_user_owner = geodir_listing_belong_to_current_user();
            if (!$is_current_user_owner) {
                $template = geodir_locate_template('information');

                if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-information.php';
                /**
                 * Filter the information template path.
                 *
                 * @since 1.0.0
                 * @param string $template The template path.
                 */
                return $template = apply_filters('geodir_template_information', $template);
            }


        }

        //geodir_is_login(true);
        global $current_user;
        if (!$current_user->ID) {
            wp_redirect(geodir_login_url(array('redirect_add_listing'=>urlencode(geodir_curPageURL()))), 302);
            exit;
        }

        $template = geodir_locate_template('add-listing');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/add-listing.php';
        /**
         * Filter the add listing template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_add_listing', $template);
    }


    if (geodir_is_page('preview') || $geodir_custom_page_list['geodir_preview_page']) {
        global $preview;
        $preview = true;

        $template = geodir_locate_template('preview');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/listing-detail.php';
        /**
         * Filter the preview template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_preview', $template);

    }


    if (geodir_is_page('listing-success') || $geodir_custom_page_list['geodir_listing_success_page']) {

        $template = geodir_locate_template('success');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/listing-success.php';
        /**
         * Filter the success template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_success', $template);

    }

    if (geodir_is_page('detail') || $geodir_custom_page_list['geodir_listing_detail_page']) {

        $template = geodir_locate_template('detail');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/listing-detail.php';
        /**
         * Filter the detail template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_detail', $template);

    }

    if (geodir_is_page('listing') || $geodir_custom_page_list['geodir_listing_page']) {

        $template = geodir_locate_template('listing');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-listing.php';
        /**
         * Filter the listing template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_listing', $template);

    }

    if (geodir_is_page('search') || $geodir_custom_page_list['geodir_search_page']) {

        $template = geodir_locate_template('search');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-search.php';
        /**
         * Filter the search template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_search', $template);

    }

    if (geodir_is_page('author') || $geodir_custom_page_list['geodir_author_page']) {

        $template = geodir_locate_template('author');

        if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-author.php';
        /**
         * Filter the author template path.
         *
         * @since 1.0.0
         * @param string $template The template path.
         */
        return $template = apply_filters('geodir_template_author', $template);

    }

    if ( geodir_is_page('home') || geodir_is_page('location')) {

        global $post, $wp_query;

        if (geodir_is_page('home') || ('page' == get_option('show_on_front') && isset($post->ID) && $post->ID == get_option('page_on_front'))
            || (is_home() && !$wp_query->is_posts_page)
        ) {

            $template = geodir_locate_template('geodir-home');

            if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-home.php';
            /**
             * Filter the home page template path.
             *
             * @since 1.0.0
             * @param string $template The template path.
             */
            return $template = apply_filters('geodir_template_homepage', $template);

        } elseif (geodir_is_page('location')) {

            $template = geodir_locate_template('location');

            if (!$template) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-location.php';
            /**
             * Filter the location template path.
             *
             * @since 1.0.0
             * @param string $template The template path.
             */
            return $template = apply_filters('geodir_template_location', $template);

        } else
            return $template;

    }

    return $template;
}

/**
 * Locates template part based on the template slug.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 * @global object $geodirectory Not yet implemented.
 * @param string $slug The template slug.
 * @param null $name The template name.
 */
function geodir_get_template_part($slug = '', $name = NULL)
{
    global $geodirectory, $post;
    /**
     * Called at the start for the geodir_get_template_part() function.
     *
     * Used dynamic hook name: geodir_get_template_part_{$slug}
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $slug The template slug.
     * @param string $name The template name.
     */
    do_action("geodir_get_template_part_{$slug}", $slug, $name);
    $templates = array();
    $name = (string)$name;
    if ('' !== $name) {
        $template_name = "{$slug}-{$name}.php";

    } else {
        $template_name = "{$slug}.php";
    }

    if (!locate_template(array("geodirectory/" . $template_name))) :
        /**
         * Filter the template part with slug and name.
         *
         * @since 1.0.0
         * @param string $template_name The template name.
         */
        $template = apply_filters("geodir_template_part-{$slug}-{$name}", geodir_plugin_path() . '/geodirectory-templates/' . $template_name);
        /**
         * Includes the template part with slug and name.
         *
         * @since 1.0.0
         */
        include($template);
    else:
        locate_template(array("geodirectory/" . $template_name), true, false);
    endif;

}

/**
 * Appends extra HTML classes to the post class.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 * @param string $class The old class string.
 * @param string|array $all_postypes The GD post types.
 * @return string The modified post class.
 */
function geodir_core_post_view_extra_class($class, $all_postypes = '')
{
    global $post;

    if (!$all_postypes) {
        $all_postypes = geodir_get_posttypes();
    }

    $gdp_post_id = !empty($post) && isset($post->ID) ? $post->ID : NULL;
    $gdp_post_type = $gdp_post_id > 0 && isset($post->post_type) ? $post->post_type : NULL;
    $gdp_post_type = $gdp_post_type != '' && !empty($all_postypes) && in_array($gdp_post_type, $all_postypes) ? $gdp_post_type : NULL;

    if ($gdp_post_id && $gdp_post_type) {
        $append_class = 'gd-post-' . $gdp_post_type;
        $append_class .= isset($post->is_featured) && $post->is_featured > 0 ? ' gd-post-featured' : '';
        $class = $class != '' ? $class . ' ' . $append_class : $append_class;
    }

    return $class;
}

/**
 * Display message when no listing result found.
 *
 * @since 1.5.5
 * @package GeoDirectory
 *
 * @param string $template_listview Optional. Listing listview template. Ex: listing-listview, widget-listing-listview,
                 gdevents_widget_listview, link-business-listview. Default: 'listing-listview'.
 * @param bool $favorite Listing Optional. Are favorite listings results? Default: false.
 */
function geodir_display_message_not_found_on_listing($template_listview = 'listing-listview', $favorite = false) {
    if ($favorite) {
		$message = __('No favorite listings found which match your selection.', 'geodirectory');
	} else {
		$message = __('No listings found which match your selection.', 'geodirectory');
	}
	
	/**
	 * Filter the no listing found message.
	 *
	 * @since 1.5.5
	 * @param string $template_listview Listing listview template.
	 * @param bool $favorite Are favorite listings results?
	 */
	$message = apply_filters('geodir_message_listing_not_found', $message, $template_listview, $favorite);
	
	echo '<li class="no-listing">' . $message . '</li>';
}

/**
 * Strips </li><li> tags from Breadcrumb HTML to wrap breadcrumb html.
 *
 * Using </li><li> breaks the links to a new line when window size is small(ex: in mobile device).
 *
 * @since 1.5.5
 * @param string $breadcrumb Breadcrumb HTML.
 * @param string $separator Breadcrumb separator.
 * @return string Breadcrumb HTML.
 */
function geodir_strip_breadcrumb_li_wrappers($breadcrumb, $separator) {
	$breadcrumb = str_replace(array('</li><li>', '</li> <li>'), '', $breadcrumb);
	
	return $breadcrumb;
}

/**
 * Get listing listview class for current column length.
 *
 * @since 1.5.7
 * @param int $columns Column length(ex: 1,2,3,4,5). Default empty.
 * @return string Listing listview class.
 */
function geodir_convert_listing_view_class($columns = '') {
	$class = '';
	
	switch ((int)$columns) {
		case 1:
			$class = '';
		break;
		case 2:
			$class = 'gridview_onehalf';
		break;
		case 3:
			$class = 'gridview_onethird';
		break;
		case 4:
			$class = 'gridview_onefourth';
		break;
		case 5:
			$class = 'gridview_onefifth';
		break;
		default:
			$class = '';
		break;
	}
	
	return $class;
}

/**
 * Filter to hide the listing excerpt.
 *
 * @since 1.5.7
 * @param bool $display Display the excerpt or not.
 * @param string $view The view type, Ex: 'listview'.
 * @param object $post The post object.
 * @return bool Modified value for display the excerpt.
 */
function geodir_show_listing_post_excerpt($display, $view, $post) {
	if ($view == 'listview') {
		if (geodir_is_page('author')) {
			$word_limit = get_option('geodir_author_desc_word_limit');
		} else {
			$word_limit = get_option('geodir_desc_word_limit');
		}
		
		if ($word_limit !== '' && ($word_limit == 0 || $word_limit == '0')) {
			$display = false;
		}
	}
	return $display;
}

/**
 * Replace the font awesome rating icons in comment form.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param string $html Rating icons html.
 * @param array $star_texts Rating icons labels.
 * @param int|null $default Default rating value to get selected.
 * @return string Rating icons html content.
 */
function geodir_font_awesome_rating_form_html($html, $star_texts = array(), $default = '') {
	if (get_option('geodir_reviewrating_enable_font_awesome') == '1') {
		$html = '<select class="gd-fa-rating">';
		$html .= '<option value=""></option>';
		if (!empty($star_texts) && is_array($star_texts)) {
			foreach ($star_texts as $i => $text) {
				$html .= '<option ' . selected((int)($i + 1), (int)$default, false) . ' value="' . (int)($i + 1) . '">' . $text . '</option>';
			}
		} else {
			$html .= '<option value="1">1</option>';
			$html .= '<option value="2">2</option>';
			$html .= '<option value="3">3</option>';
			$html .= '<option value="4">4</option>';
			$html .= '<option value="5">5</option>';
		}
		$html .= '</select>';
	}

	return $html;
}

/**
 * Display the font awesome rating icons in place of default rating images.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param string $html Rating icons html.
 * @param float $rating Current rating value.
 * @param int $star_count Total rating stars. Default 5.
 * @return string Rating icons html content.
 */
function geodir_font_awesome_rating_stars_html($html, $rating, $star_count = 5) {
	if (get_option('geodir_reviewrating_enable_font_awesome') == '1') {
		$rating = min($rating, $star_count);
		$full_stars = floor( $rating );
		$half_stars = ceil( $rating - $full_stars );
		$empty_stars = $star_count - $full_stars - $half_stars;
		
		$html = '<div class="gd-star-rating gd-fa-star-rating">';
		$html .= str_repeat( '<i class="fa fa-star gd-full-star"></i>', $full_stars );
		$html .= str_repeat( '<i class="fa fa-star-o fa-star-half-full gd-half-star"></i>', $half_stars );
		$html .= str_repeat( '<i class="fa fa-star-o gd-empty-star"></i>', $empty_stars);
		$html .= '</div>';
	}

	return $html;
}

/**
 * Adds the style for the font awesome rating icons.
 *
 * @since 1.5.7
 * @package GeoDirectory
 */
function geodir_font_awesome_rating_css() {
	// Font awesome rating style
	if (get_option('geodir_reviewrating_enable_font_awesome') == '1') {
		$full_color = get_option('geodir_reviewrating_fa_full_rating_color', '#757575');
		if ($full_color != '#757575') {
			echo '<style type="text/css">.br-theme-fontawesome-stars .br-widget a.br-active:after,.br-theme-fontawesome-stars .br-widget a.br-selected:after,
			.gd-star-rating i.fa {color:' . stripslashes($full_color) . '!important;}</style>';
		}
	}
}