<?php
/**
 * All shortcode related functions
 *
 * @since 1.0.0
 *
 * @package GeoDirectory
 */
 
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Validate and parse the measurement value.
 *
 * @since 1.0.0
 *
 * @param string $value Input value to validate measurement.
 * @return string The measurement value in valid format.
 */
function gdsc_validate_measurements($value)
{
    if ((strlen($value) - 1) == strpos(trim($value), '%')) {
        // $value is entered as a percent, so it can't be less than 0 or more than 100
        $value = preg_replace('/\D/', '', $value);
        if (100 < $value) {
            $value = 100;
        }
        // Re-add the percent symbol
        $value = $value . '%';
    } elseif ((strlen($value) - 2) == strpos(trim($value), 'px')) {
        // Get the absint & re-add the 'px'
        $value = preg_replace('/\D/', '', $value) . 'px';
    } else {
        $value = preg_replace('/\D/', '', $value);
    }

    return $value;
}

/**
 * Validate and parse the google map parameters.
 *
 * @since 1.0.0
 * @since 1.5.2 Added TERRAIN map type.
 *
 * @param string $value Input value to validate measurement.
 * @return string The measurement valud in valid format.
 */
function gdsc_validate_map_args($params)
{

    $params['width'] = gdsc_validate_measurements($params['width']);
    $params['height'] = gdsc_validate_measurements($params['height']);

    // Only accept our 4 maptypes. Otherwise, revert to the default.
    if (!(in_array(geodir_strtoupper($params['maptype']), array('HYBRID', 'SATELLITE', 'ROADMAP', 'TERRAIN')))) {
        $params['maptype'] = 'ROADMAP';
    } else {
        $params['maptype'] = geodir_strtoupper($params['maptype']);
    }

    // Zoom accepts a value between 1 and 19
    $params['zoom'] = absint($params['zoom']);
    if (19 < $params['zoom']) {
        $params['zoom'] = '19';
    }
    if (0 == $params['zoom']) {
        $params['zoom'] = '1';
    }

    // Child_collapse must be boolean
    $params['child_collapse'] = gdsc_to_bool_val($params['child_collapse']);

    // Scrollwheel must be boolean
    $params['scrollwheel'] = gdsc_to_bool_val($params['scrollwheel']);

    // Scrollwheel must be boolean
    $params['autozoom'] = gdsc_to_bool_val($params['autozoom']);

    return $params;
}

/**
 * Check the boolean true or false.
 *
 * Checks a variable to see if it should be considered a boolean true or false.
 *     Also takes into account some text-based representations of true of false,
 *     such as 'false','N','yes','on','off', etc.
 *
 * @since 1.0.0
 *
 * @param mixed $in The variable to check
 * @param bool $strict If set to false, consider everything that is not false to be true.
 *
 * @return bool The boolean equivalent or null
 */
function gdsc_to_bool_val($in, $strict = false)
{
    $out = null;

    // if not strict, we only have to check if something is false
    if (in_array($in, array(
        'false',
        'False',
        'FALSE',
        'no',
        'No',
        'n',
        'N',
        '0',
        'off',
        'Off',
        'OFF',
        false,
        0,
        null
    ), true)) {
        $out = false;
    } else if ($strict) {
        // if strict, check the equivalent true values
        if (in_array($in, array(
            'true',
            'True',
            'TRUE',
            'yes',
            'Yes',
            'y',
            'Y',
            '1',
            'on',
            'On',
            'ON',
            true,
            1
        ), true)) {
            $out = true;
        }
    } else {
        // not strict? let the regular php bool check figure it out (will
        //     largely default to true)
        $out = ($in ? true : false);
    }

    return $out;
}

/**
 * Check the post type valid or not.
 *
 * @since 1.0.0
 *
 * @param string $incoming_post_type Post type.
 * @return bool The boolean equivalent or null.
 */
function gdsc_is_post_type_valid($incoming_post_type)
{
    $post_types = geodir_get_posttypes();
    $post_types = array_map('geodir_strtolower', $post_types);
    $post_type_found = false;
    foreach ($post_types as $type) {
        if (geodir_strtolower($incoming_post_type) == geodir_strtolower($type)) {
            $post_type_found = true;
        }
    }

    return $post_type_found;
}

/**
 * Adds the filetrs and gets the query.
 *
 * @since 1.0.0
 *
 * @global object $wp_query WordPress Query object.
 * @todo $wp_query declared twice - fix it.
 * @global string $geodir_post_type Post type.
 * @global string $table Listing table name.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $term Term object.
 *
 * @param string $query Database query.
 * @return string Query.
 */
function gdsc_listing_loop_filter($query)
{
    global $wp_query, $geodir_post_type, $table, $plugin_prefix, $term;

    $geodir_post_type = geodir_get_current_posttype();

    if (isset($wp_query->tax_query->queries) && $wp_query->tax_query->queries) {
        $taxonomies = wp_list_pluck($wp_query->tax_query->queries, 'taxonomy');

        if (isset($wp_query->query[$taxonomies[0]])) {
            $request_term = explode("/", $wp_query->query[$taxonomies[0]]);
            $request_term = end($request_term);
            if (!term_exists($request_term)) {
                $args = array('number' => '1',);
                $terms_arr = get_terms($taxonomies[0], $args);
                foreach ($terms_arr as $location_term) {
                    $term_arr = $location_term;
                    $term_arr->name = geodir_ucwords(str_replace('-', ' ', $request_term));
                }
                $wp_query->queried_object_id = 1;
                $wp_query->queried_object = $term_arr;
            }
        }

    }
    if (isset($query->query_vars['is_geodir_loop']) && $query->query_vars['is_geodir_loop']) {

        $table = $plugin_prefix . $geodir_post_type . '_detail';

        add_filter('posts_fields', 'geodir_posts_fields', 1);
        add_filter('posts_join', 'geodir_posts_join', 1);
        geodir_post_where();
        if (!is_admin()) {
            add_filter('posts_orderby', 'geodir_posts_orderby', 1);
        }

        // advanced filter for popular post view widget
        global $wp_query;
        if (!is_admin()) {
            if (!empty($wp_query->query['with_pics_only'])) {
                add_filter('posts_join', 'geodir_filter_widget_join', 1000);
            }
            add_filter('posts_where', 'geodir_filter_widget_where', 1000);
        }

    }

    return $query;
}

/**
 * Get the category id from category name/slug.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type.
 * @param string $category Post category.
 * @return int Term id.
 */
function gdsc_manage_category_choice($post_type, $category)
{
    if (0 == $category || '' == $category) {
        return '';
    }

    if (!(gdsc_is_post_type_valid($post_type))) {
        return '';
    }

    $taxonomies = geodir_get_taxonomies($post_type);

    $categories = get_terms(array('taxonomy' => $taxonomies[0]));

    $cat_id = 0;

    foreach ($categories as $cat) {
        if (is_numeric($category)) {
            if (absint($category) == $cat->term_id) {
                $cat_id = $cat->term_id;
                break;
            }
        } else {
            if ($category == $cat->slug) {
                $cat_id = $cat->term_id;
                break;
            }

            if ($category == $cat->name) {
                $cat_id = $cat->term_id;
                break;
            }
        }
    }

    return $cat_id;
}

// @todo: Extract this
// This is wrong, it should be in JS and CSS files.
if (!(function_exists('geodir_home_map_add_script'))) {
	/**
	 * Adds the script in the page footer for the home page google map.
	 *
	 * @since 1.0.0
     * @return string Print the script in page footer.
	 */
	function geodir_home_map_add_script()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                geoDirMapSlide();
                jQuery(window).resize(function () {
                    jQuery('.geodir_map_container.geodir-map-home-page').each(function () {
                        jQuery(this).find('.geodir-map-posttype-list').css({'width': 'auto'});
                        jQuery(this).find('.map-places-listing ul.place-list').css({'margin-left': '0px'});
                        geoDirMapPrepare(this);
                    });
                });
            });
            function geoDirMapPrepare($thisMap) {
                var $objMpList = jQuery($thisMap).find('.geodir-map-posttype-list');
                var $objPlList = jQuery($thisMap).find('.map-places-listing ul.place-list');
                var wArrL = parseFloat(jQuery($thisMap).find('.geodir-map-navigation .geodir-leftarrow').outerWidth(true));
                var wArrR = parseFloat(jQuery($thisMap).find('.geodir-map-navigation .geodir-rightarrow').outerWidth(true));
                var ptw1 = parseFloat($objMpList.outerWidth(true));
                $objMpList.css({'margin-left': wArrL + 'px'});
                $objMpList.attr('data-width', ptw1);
                ptw1 = ptw1 - (wArrL + wArrR);
                $objMpList.width(ptw1);
                var ptw = $objPlList.width();
                var ptw2 = 0;
                $objPlList.find('li').each(function () {
                    var ptw21 = jQuery(this).outerWidth(true);
                    ptw2 += parseFloat(ptw21);
                });
                var doMov = parseFloat(ptw * 0.75);
                ptw2 = ptw2 + ( ptw2 * 0.05 );
                var maxMargin = ptw2 - ptw;
                $objPlList.attr('data-domov', doMov);
                $objPlList.attr('data-maxMargin', maxMargin);
            }
            function geoDirMapSlide() {
                jQuery('.geodir_map_container.geodir-map-home-page').each(function () {
                    var $thisMap = this;
                    geoDirMapPrepare($thisMap);
                    var $objPlList = jQuery($thisMap).find('.map-places-listing ul.place-list');
                    jQuery($thisMap).find('.geodir-leftarrow a').click(function (e) {
                        e.preventDefault();
                        var cm = $objPlList.css('margin-left');
                        var doMov = parseFloat($objPlList.attr('data-domov'));
                        var maxMargin = parseFloat($objPlList.attr('data-maxMargin'));
                        cm = parseFloat(cm);
                        if (cm == 0 || maxMargin < 0) {
                            return;
                        }
                        domargin = cm + doMov;
                        if (domargin > 0) {
                            domargin = 0;
                        }
                        $objPlList.animate({'margin-left': domargin + 'px'}, 1000);
                    });
                    jQuery($thisMap).find('.geodir-rightarrow a').click(function (e) {
                        e.preventDefault();
                        var cm = $objPlList.css('margin-left');
                        var doMov = parseFloat($objPlList.attr('data-domov'));
                        var maxMargin = parseFloat($objPlList.attr('data-maxMargin'));
                        cm = parseFloat(cm);
                        domargin = cm - doMov;
                        if (cm == ( maxMargin * -1 ) || maxMargin < 0) {
                            return;
                        }
                        if (( domargin * -1 ) > maxMargin) {
                            domargin = maxMargin * -1;
                        }
                        $objPlList.animate({'margin-left': domargin + 'px'}, 1000);
                    });
                });
            }
        </script>
    <?php
    }
}

/**
 * Adds the script in the page footer for the popular category widget.
 *
 * @since 1.0.0
 * @return string Print the script in page footer.
 */
function geodir_popular_category_add_scripts()
{
    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $('.geodir-showcat').click(function () {
                var objCat = $(this).closest('.geodir-category-list-in');
                $(objCat).find('li.geodir-pcat-hide').removeClass('geodir-hide');
                $(objCat).find('a.geodir-showcat').addClass('geodir-hide');
                $(objCat).find('a.geodir-hidecat').removeClass('geodir-hide');
            });
            $('.geodir-hidecat').click(function () {
                var objCat = $(this).closest('.geodir-category-list-in');
                $(objCat).find('li.geodir-pcat-hide').addClass('geodir-hide');
                $(objCat).find('a.geodir-hidecat').addClass('geodir-hide');
                $(objCat).find('a.geodir-showcat').removeClass('geodir-hide');
            });
        });
    </script>
<?php
}

/**
 * Get the listing layout name from layout parameter.
 *
 * @since 1.0.0
 *
 * @param string $layout_choice Listing layout.
 * @return string Layout name.
 */
function gdsc_validate_layout_choice($layout_choice)
{
    switch (geodir_strtolower($layout_choice)) {
        case 'list';
        case 'one';
        case 'one_column';
        case 'onecolumn';
        case '1';
            $layout_choice = 'list';
            break;
        case 'gridview_onehalf';
        case 'two';
        case 'two_column';
        case 'two_columns';
        case 'twocolumn';
        case 'twocolumns';
        case '2';
            $layout_choice = 'gridview_onehalf';
            break;
        case 'gridview_onethird';
        case 'three';
        case 'three_column';
        case 'three_columns';
        case 'threecolumn';
        case 'threecolumns';
        case '3';
            $layout_choice = 'gridview_onethird';
            break;
        case 'gridview_onefourth';
        case 'four';
        case 'four_column';
        case 'four_columns';
        case 'fourcolumn';
        case 'fourcolumns';
        case '4';
            $layout_choice = 'gridview_onefourth';
            break;
        case 'gridview_onefifth';
        case 'five';
        case 'five_column';
        case 'five_columns';
        case 'fivecolumn';
        case 'fivecolumns';
        case '5';
            $layout_choice = 'gridview_onefifth';
            break;
        default:
            $layout_choice = 'gridview_onehalf';
            break;
    }

    return $layout_choice;
}

/**
 * Validate & get the correct sorting option.
 *
 * @since 1.0.0
 * @since 1.6.18 Allow order by custom field in widget listings results sorting.
 *
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 *
 * @param string $sort_choice Listing sort option.
 * @param string $post_type Post type to validate custom field sort.
 * @return string Listing sort.
 */
function gdsc_validate_sort_choice($sort_choice, $post_type = '')
{
    global $plugin_prefix;

    $sorts = array(
        'az',
        'latest',
        'featured',
        'high_review',
        'high_rating',
        'random',
    );

    if (in_array($sort_choice, $sorts)) {
        return $sort_choice;
    }

    if (!empty($post_type)) {
        $table = $plugin_prefix . $post_type . '_detail';
        
        if (!geodir_prepare_custom_sorting($sort_choice, $table)) {
            $sort_choice = '';
        }
    }

    if (empty($post_type) || empty($sort_choice)) {
        $sort_choice = 'latest';
    }

    return $sort_choice;
}

/**
 * Validate & get the listing layout width.
 *
 * @since 1.0.0
 *
 * @param string $width_choice Listing width.
 * @return int|null Listing width or empty value.
 */
function gdsc_validate_listing_width($width_choice)
{
    if (!(empty($width_choice))) {
        $width_choice = absint($width_choice);
    } else {
        return '';
    }

    if (100 < $width_choice) {
        $width_choice = 100;
    }

    // If listing_width is too narrow, it won't work, arbitrarily set to 10% here
    if (10 > $width_choice) {
        $width_choice = 10;
    }

    return $width_choice;
}

/**
 * Validate & get the event list filter.
 *
 * @since 1.0.0
 *
 * @param string $filter_choice Event filter option.
 * @return string Event filter option.
 */
function gdsc_validate_list_filter_choice($filter_choice)
{
    $filters = array(
        'all',
        'today',
        'upcoming',
        'past',
    );

    if (!(in_array($filter_choice, $filters))) {
        $filter_choice = 'all';
    }

    return $filter_choice;
}

/**
 * Get the geodirectory listings.
 *
 * @since 1.4.2
 * @since 1.6.5 $tags parameter added.
 * @since 1.6.18 New attributes added in gd_listings shortcode to filter user favorite listings.
 * @since 1.6.24 listing_width parameter does not working.
 *
 * @global string $gridview_columns_widget The girdview style of the listings for widget.
 * @global bool $geodir_is_widget_listing Is this a widget listing?. Default: false.
 * @global bool   $geodir_event_widget_listview Check that current listview is event.
 * @global object $post The current post object.
 * @global array $map_jason Map data in json format.
 * @global array $map_canvas_arr Map canvas array.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param array $args Array of arguements to filter listings.
 * @return string Listings HTML content.
 */
function geodir_sc_gd_listings_output($args = array()) {
    $title				 = !empty($args['title']) ? __($args['title'], 'geodirectory') : '';
	$post_type 			 = !empty($args['post_type']) ? $args['post_type'] : 'gd_place';
	$category 			 = !empty($args['category']) ? $args['category'] : '0';
	$post_number		 = !empty($args['post_number']) ? $args['post_number'] : 10;
	$add_location_filter = !empty($args['add_location_filter']) ? true : false;
	$list_sort 			 = !empty($args['list_sort']) ? $args['list_sort'] : 'latest';
	$character_count	 = isset($args['character_count']) ? $args['character_count'] : '';
	$layout 			 = !empty($args['layout']) ? $args['layout'] : 'gridview_onehalf';
	$listing_width 		 = !empty($args['listing_width']) ? $args['listing_width'] : '';
	$with_pagination 	 = !empty($args['with_pagination']) ? true : false;
	$event_type 	 	 = !empty($args['event_type']) ? $args['event_type'] : '';
    $shortcode_content   = !empty($args['shortcode_content']) ? trim($args['shortcode_content']) : '';
    $tags                = !empty($args['tags']) ? $args['tags'] : array();
    /**
     * Filter the content text displayed when no listings found.
     *
     * @since 1.6.0
     *
     * @param string $shortcode_content The shortcode content text.
     * @param array $args Array of arguements to filter listings.
     */
    $shortcode_content = apply_filters('geodir_sc_gd_listings_not_found_content', $shortcode_content, $args);
		
	$top_pagination 	 = $with_pagination && !empty($args['top_pagination']) ? true : false;
	$bottom_pagination 	 = $with_pagination && !empty($args['bottom_pagination']) ? true : false;
	
	$shortcode_atts		 = !empty($args['shortcode_atts']) ? $args['shortcode_atts'] : array();

	// ajax mode
	$geodir_ajax		 = !empty($args['geodir_ajax']) ? true : false;
	$pageno 	 		 = $geodir_ajax && !empty($args['pageno']) ? $args['pageno'] : 1;
	
	$query_args = array(
        'posts_per_page' => $post_number,
        'is_geodir_loop' => true,
        'gd_location' => $add_location_filter,
        'post_type' => $post_type,
        'order_by' => $list_sort,
		'pageno' => $pageno
    );

    if ($character_count >= 0) {
        $query_args['excerpt_length'] = $character_count;
    }
    
    if (!empty($args['post_author'])) {
        $query_args['post_author'] = $args['post_author'];
    }

    if (!empty($args['show_featured_only'])) {
        $query_args['show_featured_only'] = 1;
    }

    if (!empty($args['show_special_only'])) {
        $query_args['show_special_only'] = 1;
    }

    if (!empty($args['with_pics_only'])) {
        $query_args['with_pics_only'] = 0;
        $query_args['featured_image_only'] = 1;
    }

    if (!empty($args['with_videos_only'])) {
        $query_args['with_videos_only'] = 1;
    }
    
    if (!empty($args['show_favorites_only'])) {
        $query_args['show_favorites_only'] = 1;
        $query_args['favorites_by_user'] = !empty($args['favorites_by_user']) ? $args['favorites_by_user'] : 0;
    }
    $with_no_results = !empty($args['without_no_results']) ? false : true;

    if (!empty($category) && isset($category[0]) && $category[0] != '0') {
        $category_taxonomy = geodir_get_taxonomies($post_type);

        ######### WPML #########
        if (geodir_wpml_is_taxonomy_translated($category_taxonomy[0])) {
            $category = gd_lang_object_ids($category, $category_taxonomy[0]);
        }
        ######### WPML #########

        $tax_query = array(
            'taxonomy' => $category_taxonomy[0],
            'field' => 'id',
            'terms' => $category
        );

        $query_args['tax_query'] = array($tax_query);
    }
    
    if (!empty($tags)) {
        // Clean tags
        if (!is_array($tags)) {
            $comma = _x(',', 'tag delimiter');
            if ( ',' !== $comma ) {
                $tags = str_replace($comma, ',', $tags);
            }
            $tags = explode(',', trim($tags, " \n\t\r\0\x0B,"));
            $tags = array_map('trim', $tags);
        }
        
        if (!empty($tags)) {
            $tag_query = array(
                'taxonomy' => $post_type . '_tags',
                'field' => 'name',
                'terms' => $tags
            );

            if (!empty($query_args['tax_query'])) {
                $query_args['tax_query'][] = $tag_query;
            } else {
                $query_args['tax_query'] = array($tag_query);
            }
        }
    }

    global $gridview_columns_widget, $geodir_is_widget_listing;

    if ($post_type == 'gd_event' && function_exists('geodir_event_get_widget_events')) {
		global $geodir_event_widget_listview;
		$geodir_event_widget_listview = true;
		
		if ($event_type && in_array($event_type, array('past', 'today', 'upcoming'))) {
			$query_args['geodir_event_type'] = $event_type;
		}
				
		$total_posts = geodir_event_get_widget_events($query_args, true);
		$widget_listings = $total_posts > 0 ? geodir_event_get_widget_events($query_args) : array();
	} else {
		$total_posts = geodir_get_widget_listings($query_args, true);
		$widget_listings = $total_posts > 0 ? geodir_get_widget_listings($query_args) : array();
	}
	$current_gridview_columns_widget = $gridview_columns_widget;
    $identifier = ' gd-wgt-pagi-' . mt_rand();
    ob_start();
	if (!empty($widget_listings) || $with_no_results) {
		if (!$geodir_ajax) {
        /**
         * Called before the shortcode [gd_listings] content is output.
         *
         * @since 1.0.0
         */
        do_action('geodir_before_sc_gd_listings');
		?>
        <div class="geodir_locations geodir_location_listing geodir-sc-gd-listings <?php echo $identifier;?>">
            <?php if ($title != '') { ?>
            <div class="geodir_list_heading clearfix">
                <?php echo $title; ?>
            </div>
			<?php } ?>
            <div class="gd-sc-loader">
                <div class="gd-sc-content">
            <?php }
            if (!(empty($widget_listings) && !empty($shortcode_content))) {
                if (strstr($layout, 'gridview')) {
                    $listing_view_exp = explode('_', $layout);
                    $gridview_columns_widget = $layout;
                    $layout = $listing_view_exp[0];
                } else {
                    $gridview_columns_widget = '';
                }

                /**
                 * Filter the widget listing listview template.
                 *
                 * @since 1.0.0
                 *
                 * @param string The template file to display listing.
                 */
                $template = apply_filters("geodir_template_part-widget-listing-listview", geodir_locate_template('widget-listing-listview'));
                            
                global $post, $map_jason, $map_canvas_arr, $gd_session;

                $current_post = $post;
                $current_map_jason = $map_jason;
                $current_map_canvas_arr = $map_canvas_arr;
                $geodir_is_widget_listing = true;
                $gd_session->un_set('gd_listing_view');

                if ($with_pagination && $top_pagination) {				
                    echo geodir_sc_listings_pagination($total_posts, $post_number, $pageno);
                }

                /**
                 * Includes listing listview template.
                 *
                 * @since 1.0.0
                 */
                include($template);
                
                if ($with_pagination && $bottom_pagination) {				
                    echo geodir_sc_listings_pagination($total_posts, $post_number, $pageno);
                }

                $geodir_is_widget_listing = false;

                $GLOBALS['post'] = $current_post;
                if (!empty($current_post)) {
                    setup_postdata($current_post);
                }
                $map_jason = $current_map_jason;
                $map_canvas_arr = $current_map_canvas_arr;
                global $gridview_columns_widget;
                $gridview_columns_widget = $current_gridview_columns_widget;
            } else {
                echo $shortcode_content;
            }
			?>
			<?php
            if (!$geodir_ajax) { 
			?>
            </div><p class="geodir-sclisting-loading" style="display:none;"><i class="fa fa-cog fa-spin"></i></p></div>
<script type="text/javascript">
jQuery(document).on('click', '.<?php echo trim($identifier);?> .gd-wgt-page', function(e) {
    var container = jQuery( '.<?php echo trim($identifier);?>');
    var obj = this;
    var pid = parseInt(jQuery(this).data('page'));
    var items = jQuery(obj).closest('.gd-sc-content');
    var loading = jQuery('.geodir-sclisting-loading', container);
    
    if (!pid > 0 || !(items && typeof items != 'undefined')) {
        return false;
    }
    
    var scatts = "<?php echo addslashes(json_encode($shortcode_atts));?>";
    
    var data = {
      'action': 'geodir_sclistings',
      'geodir_sclistings_nonce': '<?php echo wp_create_nonce("geodir-sclistings-nonce"); ?>',
      'scatts': scatts,
      'geodir_ajax': true,
      'pageno': pid
    };
    
    jQuery(document).ajaxStop(function() {
        jQuery(items).css({'opacity': '1'});
        loading.hide();
    });

    jQuery(items).css({'opacity': '0.4'});
    loading.show();

    jQuery.post(geodir_var.geodir_ajax_url, data, function(response) {
        if (response && response != '0') {
            loading.hide();
            jQuery(items).html(response);
            <?php
              /**
               * if lazyload images enabled then refresh them once ajax page changed.
               */
              if (get_option('geodir_lazy_load', 1)) { ?>
              geodir_init_lazy_load();
              <?php } ?>
        }
    });
});
</script>
</div>
		<?php } ?>
    <?php
    }
	$output = ob_get_contents();
    ob_end_clean();

    return trim($output);
}

/**
 * Get pagination for gd_listings shortcode.
 *
 * @since 1.4.2
 *
 * @global string $gridview_columns_widget Layout type of listing.
 * @global bool   $geodir_is_widget_listing Check that current listview is widget listing.
 * @global bool   $geodir_event_widget_listview Check that current listview is event.
 * @global null|WP_Post $post Post object.
 * @global array $map_jason Map data in json format.
 * @global array $map_canvas_arr Map canvas array.
 *
 * @param int    $total_posts Total items count.
 * @param int    $posts_per_page Number items displayed on one page.
 * @param int    $pageno Current page number.
 * @param string $before Display html before pagination. Default empty.
 * @param string $after Display html after pagination. Default empty.
 * @param string $prelabel Previous page label. Default empty.
 * @param string $nxtlabel Next page label. Default empty.
 * @param int    $pages_to_show Number pages to visible in pagination. Default 5.
 * @param bool   $always_show Always display pagination when pagination not required.
 * @return string Listings pagination HTML content.
 */
function geodir_sc_listings_pagination($total_posts, $posts_per_page, $pageno, $before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
    if (empty($prelabel)) {
        $prelabel = '<strong>&laquo;</strong>';
    }

    if (empty($nxtlabel)) {
        $nxtlabel = '<strong>&raquo;</strong>';
    }

    $half_pages_to_show = round($pages_to_show / 2);

	$numposts = $total_posts;

	$max_page = ceil($numposts / $posts_per_page);

	if (empty($pageno)) {
		$pageno = 1;
	}

	ob_start();
	if ($max_page > 1 || $always_show) {
		// Extra pagination info
		$geodir_pagination_more_info = get_option('geodir_pagination_advance_info');
		$start_no = ( $pageno - 1 ) * $posts_per_page + 1;
		$end_no = min($pageno * $posts_per_page, $numposts);
		
		if ($geodir_pagination_more_info != '') {
			$pagination_info = '<div class="gd-pagination-details gd-pagination-details-' . $geodir_pagination_more_info . '">' . wp_sprintf(__('Showing listings %d-%d of %d', 'geodirectory'), $start_no, $end_no, $numposts) . '</div>';
			
			if ($geodir_pagination_more_info == 'before') {
				$before = $before . $pagination_info;
			} else if ($geodir_pagination_more_info == 'after') {
				$after = $pagination_info . $after;
			}
		}
			
		echo "<div class='gd-pagi-container'> $before <div class='Navi geodir-ajax-pagination'>";
		if ($pageno > 1) {
			echo '<a class="gd-page-sc-fst gd-wgt-page" data-page="1" href="javascript:void(0);">&laquo;</a>&nbsp;';
		}
		
		if (($pageno - 1) > 0) {
            echo '<a class="gd-page-sc-prev gd-wgt-page" data-page="' . (int)($pageno - 1) . '" href="javascript:void(0);">' . $prelabel . '</a>&nbsp;';
		}
		
		for ($i = $pageno - $half_pages_to_show; $i <= $pageno + $half_pages_to_show; $i++) {
			if ($i >= 1 && $i <= $max_page) {
				if ($i == $pageno) {
					echo "<strong class='on' class='gd-page-sc-act'>$i</strong>";
				} else {
					echo ' <a class="gd-page-sc-no gd-wgt-page" data-page="' . (int)$i . '" href="javascript:void(0);">' . $i . '</a> ';
				}
			}
		}
		
		if (($pageno + 1) <= $max_page) {
			echo '&nbsp;<a class="gd-page-sc-nxt gd-wgt-page" data-page="' . (int)($pageno + 1) . '" href="javascript:void(0);">' . $nxtlabel . '</a>';
		}
		
		if ($pageno < $max_page) {
			echo '&nbsp;<a class="gd-page-sc-lst gd-wgt-page" data-page="' . (int)$max_page . '" href="javascript:void(0);">&raquo;</a>';
		}
		echo "</div> $after </div>";
	}
	$output = ob_get_contents();
    ob_end_clean();

    return trim($output);
}

/**
 * Get the listings by using ajax request.
 *
 * @since 1.4.2
 *
 * @return string Listings HTML content.
 */
function geodir_sclistings_callback() {
    check_ajax_referer('geodir-sclistings-nonce', 'geodir_sclistings_nonce');
    //set variables
    $scatts = isset($_POST['scatts']) ? $_POST['scatts'] : NULL;
    $pageno = isset($_POST['pageno']) ? absint($_POST['pageno']) : 1;
	
	$shortcode_atts = !empty($scatts) ? (array)json_decode(stripslashes_deep($scatts)) : NULL;
	
	if (!empty($shortcode_atts) && is_array($shortcode_atts)) {
		$shortcode_atts['pageno'] = $pageno;
		$shortcode_atts['geodir_ajax'] = true;
		
		echo geodir_sc_gd_listings($shortcode_atts);
	} else {
		echo 0;
	}
    wp_die();
}
add_action('wp_ajax_geodir_sclistings', 'geodir_sclistings_callback');
add_action('wp_ajax_nopriv_geodir_sclistings', 'geodir_sclistings_callback');