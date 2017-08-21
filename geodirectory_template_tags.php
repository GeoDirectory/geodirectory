<?php
/**
 * Template tag functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/**
 * Contains all map related templates used by the plugin.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
include_once('geodirectory-functions/map-functions/map_template_tags.php');

/**
 * Dequeue flexslider javscript.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
/*
function geodir_core_dequeue_script()
{
    wp_dequeue_script('flexslider');
}
add_action('wp_print_scripts', 'geodir_core_dequeue_script', 100);
*/

/**
 * Handles loading of all geodirectory javascripts and its dependencies.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_templates_scripts()
{
    $is_detail_page = false;
    $geodir_map_name = geodir_map_name();

    if((is_single() && geodir_is_geodir_page()) || (is_page() && geodir_is_page('preview') )) {
        $is_detail_page = true;
    }

    wp_enqueue_script('jquery');

    wp_register_script('geodirectory-script', geodir_plugin_url() . '/geodirectory-assets/js/geodirectory.min.js', array(), GEODIRECTORY_VERSION);
    wp_enqueue_script('geodirectory-script');

    $geodir_vars_data = array(
        'siteurl' => get_option('siteurl'),
        'geodir_plugin_url' => geodir_plugin_url(),
        'geodir_lazy_load' => get_option('geodir_lazy_load',1),
        'geodir_ajax_url' => geodir_get_ajax_url(),
        'geodir_gd_modal' => (int)get_option('geodir_disable_gb_modal'),
        'is_rtl' => is_rtl() ? 1 : 0 // fix rtl issue
    );

    /**
     * Filter the `geodir_var` data array that outputs the  wp_localize_script() translations and variables.
     *
     * This is used by addons to add JS translatable variables.
     *
     * @since 1.4.4
     * @param array $geodir_vars_data {
     *    geodir var data used by addons to add JS translatable variables.
     *
     *    @type string $siteurl Site url.
     *    @type string $geodir_plugin_url Geodirectory core plugin url.
     *    @type string $geodir_ajax_url Geodirectory plugin ajax url.
     *    @type int $geodir_gd_modal Disable GD modal that displays slideshow images in popup?.
     *    @type int $is_rtl Checks if current locale is RTL.
     *
     * }
     */
    $geodir_vars_data = apply_filters('geodir_vars_data',$geodir_vars_data);

    wp_localize_script('geodirectory-script', 'geodir_var', $geodir_vars_data);

    wp_register_script('geodirectory-jquery-flexslider-js', geodir_plugin_url() . '/geodirectory-assets/js/jquery.flexslider.min.js', array(), GEODIRECTORY_VERSION,true);
    if($is_detail_page){wp_enqueue_script('geodirectory-jquery-flexslider-js');}

    wp_register_script('geodirectory-lightbox-jquery', geodir_plugin_url() . '/geodirectory-assets/js/jquery.lightbox-0.5.min.js', array(), GEODIRECTORY_VERSION,true);
    wp_enqueue_script('geodirectory-lightbox-jquery');

    wp_register_script('geodirectory-jquery-simplemodal', geodir_plugin_url() . '/geodirectory-assets/js/jquery.simplemodal.min.js', array(), GEODIRECTORY_VERSION,true);
    if ($is_detail_page) {
        wp_enqueue_script('geodirectory-jquery-simplemodal');
    }

    if (in_array($geodir_map_name, array('auto', 'google'))) {
        $map_lang = "&language=" . geodir_get_map_default_language();
        $map_key = "&key=" . geodir_get_map_api_key();
        /**
         * Filter the variables that are added to the end of the google maps script call.
         *
         * This i used to change things like google maps language etc.
         *
         * @since 1.0.0
         * @param string $var The string to filter, default is empty string.
         */
        $map_extra = apply_filters('geodir_googlemap_script_extra', '');
        wp_enqueue_script('google-maps-api', 'https://maps.google.com/maps/api/js?' . $map_lang . $map_key . $map_extra , '', NULL);
        
        // Overlapping Marker Spiderfier
        wp_register_script('geodirectory-g-overlappingmarker-script', geodir_plugin_url() . '/geodirectory-assets/jawj/oms.min.js', array(), GEODIRECTORY_VERSION);
        wp_enqueue_script('geodirectory-g-overlappingmarker-script');
    }
    
    if ($geodir_map_name == 'osm') {
        // Leaflet OpenStreetMap
        wp_register_style('geodirectory-leaflet-style', geodir_plugin_url() . '/geodirectory-assets/leaflet/leaflet.css', array(), GEODIRECTORY_VERSION);
        wp_enqueue_style('geodirectory-leaflet-style');
            
        wp_register_script('geodirectory-leaflet-script', geodir_plugin_url() . '/geodirectory-assets/leaflet/leaflet.min.js', array(), GEODIRECTORY_VERSION);
        wp_enqueue_script('geodirectory-leaflet-script');
        
        wp_register_script('geodirectory-leaflet-geo-script', geodir_plugin_url() . '/geodirectory-assets/leaflet/osm.geocode.min.js', array(), GEODIRECTORY_VERSION);
        wp_enqueue_script('geodirectory-leaflet-geo-script');
        
        if ($is_detail_page) {
            wp_register_style('geodirectory-leaflet-routing-style', geodir_plugin_url() . '/geodirectory-assets/leaflet/routing/leaflet-routing-machine.css', array(), GEODIRECTORY_VERSION);
            wp_enqueue_style('geodirectory-leaflet-routing-style');
                
            wp_register_script('geodirectory-leaflet-routing-script', geodir_plugin_url() . '/geodirectory-assets/leaflet/routing/leaflet-routing-machine.min.js', array(), GEODIRECTORY_VERSION);
            wp_enqueue_script('geodirectory-leaflet-routing-script');
        }
        
        // Overlapping Marker Spiderfier Leaflet
        wp_register_script('geodirectory-o-overlappingmarker-script', geodir_plugin_url() . '/geodirectory-assets/jawj/oms-leaflet.min.js', array(), GEODIRECTORY_VERSION);
        wp_enqueue_script('geodirectory-o-overlappingmarker-script');
    }
    wp_enqueue_script( 'jquery-ui-autocomplete' );
    
    wp_register_script('geodirectory-goMap-script', geodir_plugin_url() . '/geodirectory-assets/js/goMap.min.js', array(), GEODIRECTORY_VERSION, true);
    wp_enqueue_script('geodirectory-goMap-script');

    wp_register_script('chosen', geodir_plugin_url() . '/geodirectory-assets/js/chosen.jquery.min.js', array(), GEODIRECTORY_VERSION);
    wp_enqueue_script('chosen');

    wp_register_script('geodirectory-choose-ajax', geodir_plugin_url() . '/geodirectory-assets/js/ajax-chosen.min.js', array(), GEODIRECTORY_VERSION);
    wp_enqueue_script('geodirectory-choose-ajax');

    wp_enqueue_script('geodirectory-jquery-ui-timepicker-js', geodir_plugin_url() . '/geodirectory-assets/js/jquery.ui.timepicker.min.js', array('jquery-ui-datepicker', 'jquery-ui-slider', 'jquery-effects-core', 'jquery-effects-slide'), '', true);

    if (is_page() && geodir_is_page('add-listing')) {
        // SCRIPT FOR UPLOAD
        wp_enqueue_script('plupload-all');
        wp_enqueue_script('jquery-ui-sortable');

        wp_register_script('geodirectory-plupload-script', geodir_plugin_url() . '/geodirectory-assets/js/geodirectory-plupload.min.js#asyncload', array(), GEODIRECTORY_VERSION,true);
        wp_enqueue_script('geodirectory-plupload-script');
        // SCRIPT FOR UPLOAD END

        // check_ajax_referer function is used to make sure no files are uplaoded remotly but it will fail if used between https and non https so we do the check below of the urls
        if (str_replace("https", "http", admin_url('admin-ajax.php')) && !empty($_SERVER['HTTPS'])) {
            $ajax_url = admin_url('admin-ajax.php');
        } elseif (!str_replace("https", "http", admin_url('admin-ajax.php')) && empty($_SERVER['HTTPS'])) {
            $ajax_url = admin_url('admin-ajax.php');
        } elseif (str_replace("https", "http", admin_url('admin-ajax.php')) && empty($_SERVER['HTTPS'])) {
            $ajax_url = str_replace("https", "http", admin_url('admin-ajax.php'));
        } elseif (!str_replace("https", "http", admin_url('admin-ajax.php')) && !empty($_SERVER['HTTPS'])) {
            $ajax_url = str_replace("http", "https", admin_url('admin-ajax.php'));
        } else {
            $ajax_url = admin_url('admin-ajax.php');
        }

        // place js config array for plupload
        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,browserplus,gears,html4',
            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
            'container' => 'plupload-upload-ui', // will be adjusted per uploader
            'drop_element' => 'dropbox', // will be adjusted per uploader
            'file_data_name' => 'async-upload', // will be adjusted per uploader
            'multiple_queues' => true,
            'max_file_size' => geodir_max_upload_size(),
            'url' => $ajax_url,
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files', 'geodirectory'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multi_selection' => false, // will be added per uploader
            // additional post data to send to our ajax hook
            'multipart_params' => array(
                '_ajax_nonce' => "", // will be added per uploader
                'action' => 'plupload_action', // the ajax action name
                'imgid' => 0 // will be added per uploader
            )
        );
        $base_plupload_config = json_encode($plupload_init);

        $gd_plupload_init = array('base_plupload_config' => $base_plupload_config,
            'upload_img_size' => geodir_max_upload_size());

        wp_localize_script('geodirectory-plupload-script', 'gd_plupload', $gd_plupload_init);

        wp_enqueue_script('geodirectory-listing-validation-script', geodir_plugin_url() . '/geodirectory-assets/js/listing_validation.min.js#asyncload');
    } // End if for add place page

    wp_register_script('geodirectory-post-custom-js', geodir_plugin_url() . '/geodirectory-assets/js/post.custom.min.js#asyncload', array(), GEODIRECTORY_VERSION, true);
    if ($is_detail_page) {
		wp_enqueue_script('geodirectory-post-custom-js');
	}

    // font awesome rating script
	if (get_option('geodir_reviewrating_enable_font_awesome')) {
		wp_register_script('geodir-barrating-js', geodir_plugin_url() . '/geodirectory-assets/js/jquery.barrating.min.js', array(), GEODIRECTORY_VERSION, true);
		wp_enqueue_script('geodir-barrating-js');
	} else { // default rating script
		wp_register_script('geodir-jRating-js', geodir_plugin_url() . '/geodirectory-assets/js/jRating.jquery.min.js', array(), GEODIRECTORY_VERSION, true);
		wp_enqueue_script('geodir-jRating-js');
	}

    wp_register_script('geodir-on-document-load', geodir_plugin_url() . '/geodirectory-assets/js/on_document_load.min.js#asyncload', array(), GEODIRECTORY_VERSION, true);
    wp_enqueue_script('geodir-on-document-load');

    wp_register_script('google-geometa', geodir_plugin_url() . '/geodirectory-assets/js/geometa.min.js#asyncload', array(), GEODIRECTORY_VERSION, true);
    wp_enqueue_script('google-geometa');
}

/**
 * Loads custom CSS and JS on header.
 *
 * WP Admin -> Geodirectory -> Design -> Scripts -> Custom style css code.
 * WP Admin -> Geodirectory -> Design -> Scripts -> Header script code.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_header_scripts()
{
    echo '<style>' . stripslashes(get_option('geodir_coustem_css')) . '</style>';
    echo stripslashes(get_option('geodir_header_scripts'));
}

/**
 * Loads Google Analytics JS on header.
 *
 * WP Admin -> Geodirectory -> General -> Google Analytics -> Google analytics tracking code.
 *
 * @since 1.6.16
 * @package GeoDirectory
 */
function geodir_google_analytics_tracking_code()
{
    if(get_option('geodir_ga_add_tracking_code') && get_option('geodir_ga_account_id')){?>

        <script>
            (function(i,s,o,g,r,a,m){ i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo esc_attr(get_option('geodir_ga_account_id'));?>', 'auto');
            <?php if(get_option('geodir_ga_anonymize_ip')){echo "ga('set', 'anonymizeIP', true);";}?>
            ga('send', 'pageview');

        </script>

        <?php
    }elseif(get_option('geodir_ga_tracking_code') && !get_option('geodir_ga_account_id')){
        echo stripslashes(get_option('geodir_ga_tracking_code'));
    }
}

/**
 * Loads custom JS in footer.
 *
 * WP Admin -> Geodirectory -> Design -> Scripts -> Footer script code.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_footer_scripts()
{

    echo stripslashes(get_option('geodir_footer_scripts'));

    /*
     * Apple suck and can't/won't fix bugs: https://bugs.webkit.org/show_bug.cgi?id=136041
     *
     * Flexbox wont wrap on ios for search form items
     */
    if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/iPad|iPod|iPhone|Safari/', $_SERVER['HTTP_USER_AGENT'] ) ) {
        echo "<style>body .geodir-listing-search.gd-search-bar-style .geodir-loc-bar .clearfix.geodir-loc-bar-in .geodir-search .gd-search-input-wrapper{flex:50 1 auto !important;min-width: initial !important;width:auto !important;}.geodir-filter-container .geodir-filter-cat{width:auto !important;}</style>";
    }
}


/**
 * Adds async tag to javascript for faster page loading.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $url The javascript file url.
 * @return string The modified javascript string.
 */
function geodir_add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#asyncload', '', $url);
    else
        return str_replace('#asyncload', '', $url)."' async='async";
}
add_filter('clean_url', 'geodir_add_async_forscript', 11, 1);

/**
 * Handles loading of all geodirectory stylesheets and its dependencies.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_templates_styles()
{

    wp_register_style('geodir-core-scss', geodir_plugin_url() . '/geodirectory-assets/css/gd_core_frontend.css', array(), GEODIRECTORY_VERSION);
    wp_enqueue_style('geodir-core-scss');
    wp_register_style('geodir-core-scss-footer', geodir_plugin_url() . '/geodirectory-assets/css/gd_core_frontend_footer.css', array(), GEODIRECTORY_VERSION);

    if(is_rtl()){
    wp_register_style('geodirectory-frontend-rtl-style', geodir_plugin_url() . '/geodirectory-assets/css/rtl-frontend.css', array(), GEODIRECTORY_VERSION);
    wp_enqueue_style('geodirectory-frontend-rtl-style');
    }

    wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), GEODIRECTORY_VERSION);
    wp_enqueue_style('font-awesome');


}


/**
 * Returns geodirectory sidebar.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_get_sidebar()
{
    get_sidebar('geodirectory');
}

/**
 * Returns paginated HTML string based on the given parameters.
 *
 * @since 1.0.0
 * @since 1.5.5 Fixed pagination links when location selected.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $wp_query WordPress Query object.
 * @param string $before The HTML to prepend.
 * @param string $after The HTML to append.
 * @param string $prelabel The previous link label.
 * @param string $nxtlabel The next link label.
 * @param int $pages_to_show Number of pages to display on the pagination. Default: 5.
 * @param bool $always_show Do you want to show the pagination always? Default: false.
 */
function geodir_pagination($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
    global $wp_query, $posts_per_page, $wpdb, $paged, $blog_id;

    if (empty($prelabel)) {
        $prelabel = '<strong>&lt;</strong>';
    }

    if (empty($nxtlabel)) {
        $nxtlabel = '<strong>&gt;</strong>';
    }

    $half_pages_to_show = round($pages_to_show / 2);

    if (geodir_is_page('home')) // dont apply default  pagination for geodirectory home page.
        return;

    if (!is_single()) {
        if (function_exists('geodir_location_geo_home_link')) {
            remove_filter('home_url', 'geodir_location_geo_home_link', 100000);
        }
        $numposts = $wp_query->found_posts;

        $max_page = ceil($numposts / $posts_per_page);

        if (empty($paged)) {
            $paged = 1;
        }
        
        $post_type = geodir_get_current_posttype();
        $listing_type_name = get_post_type_plural_label($post_type);
        if (geodir_is_page('listing') || geodir_is_page('search')) {            
            $term = array();
            
            if (is_tax()) {
                $term_id = get_queried_object_id();
                $taxonomy = get_query_var('taxonomy');

                if ($term_id && $post_type && get_query_var('taxonomy') == $post_type . 'category' ) {
                    $term = get_term($term_id, $post_type . 'category');
                }
            }
            
            if (geodir_is_page('search') && !empty($_REQUEST['s' . $post_type . 'category'])) {
                $taxonomy_search = $_REQUEST['s' . $post_type . 'category'];
                
                if (!is_array($taxonomy_search)) {
                    $term = get_term((int)$taxonomy_search, $post_type . 'category');
                } else if(is_array($taxonomy_search) && count($taxonomy_search) == 1) { // single category search
                    $term = get_term((int)$taxonomy_search[0], $post_type . 'category');
                }
            }
            
            if (!empty($term) && !is_wp_error($term)) {
                $listing_type_name = $term->name;
            }
        }

        if ($max_page > 1 || $always_show) {            
            // Extra pagination info
            $geodir_pagination_more_info = get_option('geodir_pagination_advance_info');
            $start_no = ( $paged - 1 ) * $posts_per_page + 1;
            $end_no = min($paged * $posts_per_page, $numposts);

            if ($geodir_pagination_more_info != '') {
                if ($listing_type_name) {
                    $listing_type_name = __($listing_type_name, 'geodirectory');
                    $pegination_desc = wp_sprintf(__('Showing %s %d-%d of %d', 'geodirectory'), $listing_type_name, $start_no, $end_no, $numposts);
                } else {
                    $pegination_desc = wp_sprintf(__('Showing listings %d-%d of %d', 'geodirectory'), $start_no, $end_no, $numposts);
                }
                $pagination_info = '<div class="gd-pagination-details">' . $pegination_desc . '</div>';
                /**
                 * Adds an extra pagination info above/under pagination.
                 *
                 * @since 1.5.9
                 *
                 * @param string $pagination_info Extra pagination info content.
                 * @param string $listing_type_name Listing results type.
                 * @param string $start_no First result number.
                 * @param string $end_no Last result number.
                 * @param string $numposts Total number of listings.
                 * @param string $post_type The post type.
                 */
                $pagination_info = apply_filters('geodir_pagination_advance_info', $pagination_info, $listing_type_name, $start_no, $end_no, $numposts, $post_type);
                
                if ($geodir_pagination_more_info == 'before') {
                    $before = $before . $pagination_info;
                } else if ($geodir_pagination_more_info == 'after') {
                    $after = $pagination_info . $after;
                }
            }
            
            echo "$before <div class='Navi gd-navi'>";
            if ($paged >= ($pages_to_show - 1)) {
                echo '<a href="' . str_replace('&paged', '&amp;paged', get_pagenum_link()) . '">&laquo;</a>';
            }
            previous_posts_link($prelabel);
            for ($i = $paged - $half_pages_to_show; $i <= $paged + $half_pages_to_show; $i++) {
                if ($i >= 1 && $i <= $max_page) {
                    if ($i == $paged) {
                        echo "<strong class='on'>$i</strong>";
                    } else {
                        echo ' <a href="' . str_replace('&paged', '&amp;paged', get_pagenum_link($i)) . '">' . $i . '</a> ';
                    }
                }
            }
            next_posts_link($nxtlabel, $max_page);
            if (($paged + $half_pages_to_show) < ($max_page)) {
                echo '<a href="' . str_replace('&paged', '&amp;paged', get_pagenum_link($max_page)) . '">&raquo;</a>';
            }
            echo "</div> $after";
        }
        
        if (function_exists('geodir_location_geo_home_link')) {
            add_filter('home_url', 'geodir_location_geo_home_link', 100000, 2);
        }
    }
}

/**
 * Prints listing search related javascript.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_listingsearch_scripts()
{
    if (get_option('gd_search_dist') != '') {
        $dist = get_option('gd_search_dist');
    } else {
        $dist = 500;
    }
    $dist_dif = 1000;

    if ($dist <= 5000) $dist_dif = 500;
    if ($dist <= 1000) $dist_dif = 100;
    if ($dist <= 500) $dist_dif = 50;
    if ($dist <= 100) $dist_dif = 10;
    if ($dist <= 50) $dist_dif = 5;

    ?>
    <script type="text/javascript">

        jQuery(function ($) {
            $("#distance_slider").slider({
                range: true,
                values: [0, <?php echo ($_REQUEST['sdist']!='') ? sanitize_text_field($_REQUEST['sdist']) : "0"; ?>],
                min: 0,
                max: <?php echo $dist; ?>,
                step: <?php echo $dist_dif; ?>,
                slide: function (event, ui) {
                    $("#sdist").val(ui.values[1]);
                    $("#sdist_span").html(ui.values[1]);
                }
            });

            $("#sdist").val($("#distance_slider").slider("values", 1));
            $("#sdist_span").html($("#distance_slider").slider("values", 1));

        });

        /*jQuery(".showFilters").click(function () {
         jQuery(".gdFilterOptions").slideToggle("slow");
         });*/

        jQuery("#cat_all").click(function () {
            jQuery('.cat_check').attr('checked', this.checked);
        });

        jQuery(".cat_check").click(function () {
            if (jQuery(".cat_check").length == jQuery(".cat_check:checked").length) {
                jQuery("#cat_all").attr("checked", "checked");
            } else {
                jQuery("#cat_all").removeAttr("checked");
            }
        });


        jQuery(window).load(function () {
            if (jQuery(".cat_check").length == jQuery(".cat_check:checked").length) {
                jQuery("#cat_all").attr("checked", "checked");
            } else {
                jQuery("#cat_all").removeAttr("checked");
            }
        });

    </script>
<?php
}

/**
 * Prints location related javascript.
 *
 * @since 1.0.0
 * @since 1.6.16 Fix: Single quote in default city name causes problem in add new city.
 * @package GeoDirectory
 */
function geodir_add_sharelocation_scripts() {
    $default_search_for_text = SEARCH_FOR_TEXT;
    if (get_option('geodir_search_field_default_text'))
        $default_search_for_text = __(get_option('geodir_search_field_default_text'), 'geodirectory');

    $default_near_text = NEAR_TEXT;
    if (get_option('geodir_near_field_default_text'))
        $default_near_text = __(get_option('geodir_near_field_default_text'), 'geodirectory');
    
    $search_location = geodir_get_default_location();
    
    $default_search_for_text = addslashes(stripslashes($default_search_for_text));
    $default_near_text = addslashes(stripslashes($default_near_text));
    $city = !empty($search_location) ? addslashes(stripslashes($search_location->city)) : '';
    ?>
    <script type="text/javascript">
        var default_location = '<?php echo $city ;?>';
        var latlng;
        var address;
        var dist = 0;
        var Sgeocoder = (typeof google!=='undefined' && typeof google.maps!=='undefined') ? new google.maps.Geocoder() : {};

		function geodir_setup_submit_search() {
            jQuery('.geodir_submit_search').unbind('click');// unbind any other click events
			jQuery('.geodir_submit_search').click(function(e) {

                e.preventDefault();

				var s = ' ';
				var $form = jQuery(this).closest('form');

				if (jQuery("#sdist input[type='radio']:checked").length != 0) dist = jQuery("#sdist input[type='radio']:checked").val();
				if (jQuery('.search_text', $form).val() == '' || jQuery('.search_text', $form).val() == '<?php echo $default_search_for_text;?>') jQuery('.search_text', $form).val(s);
				
				// Disable location based search for disabled location post type.
				if (jQuery('.search_by_post', $form).val() != '' && typeof gd_cpt_no_location == 'function') {
					if (gd_cpt_no_location(jQuery('.search_by_post', $form).val())) {
						jQuery('.snear', $form).remove();
						jQuery('.sgeo_lat', $form).remove();
						jQuery('.sgeo_lon', $form).remove();
						jQuery('select[name="sort_by"]', $form).remove();
						jQuery($form).submit();
						return;
					}
				}
				
				if (dist > 0 || (jQuery('select[name="sort_by"]').val() == 'nearest' || jQuery('select[name="sort_by"]', $form).val() == 'farthest') || (jQuery(".snear", $form).val() != '' && jQuery(".snear", $form).val() != '<?php echo $default_near_text;?>')) {
					geodir_setsearch($form);
				} else {
					jQuery(".snear", $form).val('');
					jQuery($form).submit();
				}
			});
		}

        jQuery(document).ready(function() {
            geodir_setup_submit_search();
            //setup advanced search form on form ajax load
            jQuery("body").on("geodir_setup_search_form", function(){
                geodir_setup_submit_search();
            });
        });
        
		function geodir_setsearch($form) {
			if ((dist > 0 || (jQuery('select[name="sort_by"]', $form).val() == 'nearest' || jQuery('select[name="sort_by"]', $form).val() == 'farthest')) && (jQuery(".snear", $form).val() == '' || jQuery(".snear", $form).val() == '<?php echo $default_near_text;?>')) jQuery(".snear", $form).val(default_location);
			geocodeAddress($form);
		}

        function updateSearchPosition(latLng, $form) {
            if (window.gdMaps === 'google') {
                jQuery('.sgeo_lat').val(latLng.lat());
                jQuery('.sgeo_lon').val(latLng.lng());
            } else if (window.gdMaps === 'osm') {
                jQuery('.sgeo_lat').val(latLng.lat);
                jQuery('.sgeo_lon').val(latLng.lon);
            }
            jQuery($form).submit(); // submit form after insering the lat long positions
        }

        function geocodeAddress($form) {
            // Call the geocode function
            Sgeocoder = window.gdMaps == 'google' ? new google.maps.Geocoder() : null;

            if (jQuery('.snear', $form).val() == '' || ( jQuery('.sgeo_lat').val() != '' && jQuery('.sgeo_lon').val() != ''  ) || jQuery('.snear', $form).val().match("^<?php _e('In:','geodirectory');?>")) {
                if (jQuery('.snear', $form).val().match("^<?php _e('In:','geodirectory');?>")) {
                    jQuery(".snear", $form).val('');
                }
                jQuery($form).submit();
            } else {
                var address = jQuery(".snear", $form).val();

                if (jQuery('.snear', $form).val() == '<?php echo $default_near_text;?>') {
                    initialise2();
                } else {
                    <?php
                    $near_add = get_option('geodir_search_near_addition');
                    /**
                     * Adds any extra info to the near search box query when trying to geolocate it via google api.
                     *
                     * @since 1.0.0
                     */
                    $near_add2 = apply_filters('geodir_search_near_addition', '');
                    ?>
                    if (window.gdMaps === 'google') {
                        Sgeocoder.geocode({'address': address<?php echo ($near_add ? '+", ' . $near_add . '"' : '') . $near_add2;?>},
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    updateSearchPosition(results[0].geometry.location, $form);
                                } else {
                                    alert("<?php esc_attr_e('Search was not successful for the following reason :', 'geodirectory');?>" + status);
                                }
                            });
                    } else if (window.gdMaps === 'osm') {
                        geocodePositionOSM(false, address, false, false, 
                            function(geo) {
                                if (typeof geo !== 'undefined' && geo.lat && geo.lon) {
                                    updateSearchPosition(geo, $form);
                                } else {
                                    alert("<?php esc_attr_e('Search was not successful for the requested address.', 'geodirectory');?>");
                                }
                            });
                    } else {
                        jQuery($form).submit();
                    }
                }
            }
        }

        function initialise2() {
            if (!window.gdMaps) {
                return;
            }
            
            if (window.gdMaps === 'google') {
                var latlng = new google.maps.LatLng(56.494343, -4.205446);
                var myOptions = {
                    zoom: 4,
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                    disableDefaultUI: true
                }
            } else if (window.gdMaps === 'osm') {
                var latlng = new L.LatLng(56.494343, -4.205446);
                var myOptions = {
                    zoom: 4,
                    mapTypeId: 'TERRAIN',
                    disableDefaultUI: true
                }
            }
            try { prepareGeolocation(); } catch (e) {}
            doGeolocation();
        }

        function doGeolocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(positionSuccess, positionError);
            } else {
                positionError(-1);
            }
        }

        function positionError(err) {
            var msg;
            switch (err.code) {
                case err.UNKNOWN_ERROR:
                    msg = "<?php _e('Unable to find your location','geodirectory');?>";
                    break;
                case err.PERMISSION_DENINED:
                    msg = "<?php _e('Permission denied in finding your location','geodirectory');?>";
                    break;
                case err.POSITION_UNAVAILABLE:
                    msg = "<?php _e('Your location is currently unknown','geodirectory');?>";
                    break;
                case err.BREAK:
                    msg = "<?php _e('Attempt to find location took too long','geodirectory');?>";
                    break;
                default:
                    msg = "<?php _e('Location detection not supported in browser','geodirectory');?>";
            }
            jQuery('#info').html(msg);
        }

        function positionSuccess(position) {
            var coords = position.coords || position.coordinate || position;
            jQuery('.sgeo_lat').val(coords.latitude);
            jQuery('.sgeo_lon').val(coords.longitude);

            jQuery('.geodir-listing-search').submit();
        }

        /**
         * On unload page do some cleaning so back button cache does not store these values.
         */
        window.onunload = function(){
            if(jQuery('.sgeo_lat').length ){
                jQuery('.sgeo_lat').val('');
                jQuery('.sgeo_lon').val('');
            }
        };

    </script>
<?php
}


/**
 * Displays badges on the listings pages over the thumbnail.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $which The badge type.
 * @param object $post The post object.
 * @param string $link The link to the post.
 * @return string|void The link HTML.
 */
function geodir_show_badges_on_image($which, $post, $link)
{
    $return = '';
    switch ($which) {
        case 'featured':
            /**
             * Filter the featured image badge html that appears in the listings pages over the thumbnail.
             *
             * @since 1.0.0
             * @param object $post The post object.
             * @param string $link The link to the post.
             */
            $return = apply_filters('geodir_featured_badge_on_image', '<a href="' . $link . '"><span class="geodir_featured_img">&nbsp;</span></a>',$post,$link);
            break;
        case 'new' :
            /**
             * Filter the new image badge html that appears in the listings pages over the thumbnail.
             *
             * @since 1.0.0
             * @param object $post The post object.
             * @param string $link The link to the post.
             */
            $return = apply_filters('geodir_new_badge_on_image', '<a href="' . $link . '"><span class="geodir_new_listing">&nbsp;</span></a>',$post,$link);
            break;

    }
    
    return $return;
}

/**
 * Dequeue scripts to fix JS conflicts.
 *
 * @since 1.6.22
 */
function geodir_fix_script_conflict() {
    if ( wp_script_is( 'flexslider', 'enqueued' ) && wp_script_is( 'geodirectory-jquery-flexslider-js', 'enqueued' ) ) {
        wp_dequeue_script( 'flexslider' );
    }
}
add_action( 'wp_enqueue_scripts', 'geodir_fix_script_conflict', 100 );