<?php
/**
 * Hook and filter actions used by the plugin
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 * @global string $plugin_file_name Plugin main file name. 'geodirectory/geodirectory.php'.
 */

/**
 * Return the GeoDirectory ajax specific url.
 *
 * This is used to run GeoDirectory specific functions via ajax.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string The GeoDirectory ajax URL.
 */
function geodir_get_ajax_url()
{
    return admin_url('admin-ajax.php?action=geodir_ajax_action');
}

/////////////////////
/* ON INIT ACTIONS */
/////////////////////

add_action('init', 'geodir_on_init', 1);

add_action('init', 'geodir_add_post_filters');

//add_action('init', 'geodir_init_defaults');

add_action('init', 'geodir_allow_post_type_frontend');

add_action('init', 'geodir_register_taxonomies', 1);

add_action('init', 'geodir_register_post_types', 2);

add_filter('geodir_post_type_args', 'geodir_post_type_args_modify', 0, 2);

//add_action( 'init', 'geodir_flush_rewrite_rules', 99 );

add_action('init', 'geodir_custom_post_status');

add_action('widgets_init', 'geodir_register_sidebar'); // Takes care of widgets

global $geodir_addon_list;
/**
 * Build an array of installed addons.
 *
 * This filter builds an array of installed addons which can be used to check what exactly is installed.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $geodir_addon_list The array of installed plugins $geodir_addon_list['geodir_location_manager'].
 */
apply_filters('geodir_build_addon_list', $geodir_addon_list);

add_action('wp_ajax_geodir_ajax_action', "geodir_ajax_handler");

add_action('wp_ajax_nopriv_geodir_ajax_action', 'geodir_ajax_handler');

/* Pluploader */
add_action('wp_ajax_plupload_action', "geodir_plupload_action");

add_action('wp_ajax_nopriv_plupload_action', 'geodir_plupload_action'); // call for not logged in ajax

////////////////////////
/* Widget Initialization */
////////////////////////

add_action('widgets_init', 'register_geodir_widgets');

////////////////////////
/* REWRITE RULES */
////////////////////////

add_filter('rewrite_rules_array', 'geodir_listing_rewrite_rules');

////////////////////////
/* QUERY VARS */
////////////////////////

add_filter('query_vars', 'geodir_add_location_var');
add_filter('query_vars', 'geodir_add_geodir_page_var');
add_action('wp', 'geodir_add_page_id_in_query_var'); // problem fix in wordpress 3.8
if (get_option('permalink_structure') != '')
    add_filter('parse_request', 'geodir_set_location_var_in_session_in_core');

add_filter('parse_query', 'geodir_modified_query');


////////////////////////
/* ON WP LOAD ACTIONS */
////////////////////////

//add_action( 'wp_loaded','geodir_flush_rewrite_rules' );
add_action('wp_loaded', 'geodir_on_wp_loaded', 10);

add_action('wp', 'geodir_on_wp', 10);


/////////////////////////////
/* ON WP HEAD ACTIONS */
/////////////////////////////

add_action('wp_head', 'geodir_header_scripts');

// add_action('admin_head', 'geodir_header_scripts'); // Removed since 1.5.0

add_action('wp_head', 'geodir_init_map_jason'); // Related to MAP

add_action('wp_head', 'geodir_init_map_canvas_array'); // Related to MAP

add_action('wp_head', 'geodir_restrict_widget'); // Related to widgets

//////////////////////////////
/* ENQUEUE SCRIPTS AND STYLES */
//////////////////////////////

add_action('wp_enqueue_scripts', 'geodir_templates_scripts');

add_action('wp_enqueue_scripts', 'geodir_templates_styles', 8);

////////////////////////
/* ON MAIN NAVIGATION */
////////////////////////
add_filter('wp_nav_menu_items', 'geodir_menu_items', 100, 2);

add_filter('wp_page_menu', 'geodir_pagemenu_items', 100, 2);


/////////////////////////
/* ON TEMPLATE INCLUDE */
/////////////////////////

add_filter('template_include', 'geodir_template_loader',9);

/////////////////////////
/* CATEGORY / TAXONOMY / CUSTOM POST ACTIONS */
/////////////////////////

//add_action('edited_term','geodir_update_markers_oncatedit',10,3);

add_filter('term_link', 'geodir_get_term_link', 10, 3);

add_filter('post_type_archive_link', 'geodir_get_posttype_link', 10, 2);

add_filter('post_type_link', 'geodir_listing_permalink_structure', 10, 4);

////////////////////////
/* POST AND LOOP ACTIONS */
////////////////////////
if (!is_admin()) {
    add_action('pre_get_posts', 'geodir_exclude_page', 100); /// Will help to exclude virtual page from everywhere
    add_filter('wp_list_pages_excludes', 'exclude_from_wp_list_pages', 100);
    /** Exclude Virtual Pages From Pages List **/
    add_action('pre_get_posts', 'set_listing_request', 0);
    add_action('pre_get_posts', 'geodir_listing_loop_filter', 1);
    add_filter('excerpt_more', 'geodir_excerpt_more', 1000);
    add_filter('excerpt_length', 'geodir_excerpt_length', 1000);
    add_action('the_post', 'create_marker_jason_of_posts'); // Add marker in json array, Map related filter
}


add_action('set_object_terms', 'geodir_set_post_terms', 10, 4);

add_action('transition_post_status', 'geodir_update_poststatus', 10, 3);

add_action('before_delete_post', 'geodir_delete_listing_info', 10, 1);


////////////////////////
/* WP REVIEW COUNT ACTIONS */
////////////////////////

add_action('geodir_update_postrating', 'geodir_term_review_count_force_update_single_post', 100,1);
//add_action('geodir_update_postrating', 'geodir_term_review_count_force_update', 100);
add_action('transition_post_status', 'geodir_term_review_count_force_update', 100,3);
//add_action('created_term', 'geodir_term_review_count_force_update', 100);
add_action('edited_term', 'geodir_term_review_count_force_update', 100);
add_action('delete_term', 'geodir_term_review_count_force_update', 100);

////////////////////////
/* WP CAT META UPDATE ACTIONS */
////////////////////////
add_action('gd_tax_meta_updated', 'geodir_get_term_icon_rebuild', 5000);
////////////////////////
/* WP FOOTER ACTIONS */
////////////////////////

add_action('wp_footer', 'geodir_footer_scripts'); /* Footer Scripts loader */

add_action('wp_footer', 'send_marker_jason_to_js'); // Show map for listings with markers


add_action('admin_footer', 'geodir_localize_all_js_msg');

add_action('wp_footer', 'geodir_localize_all_js_msg');

add_action('admin_head-media-upload-popup', 'geodir_localize_all_js_msg');
add_action('customize_controls_print_footer_scripts', 'geodir_localize_all_js_msg');

add_action('wp_head', 'geodir_add_meta_keywords');
add_action('wp_head', 'geodir_google_analytics_tracking_code');

/* Sharelocation scripts */
//global $geodir_addon_list;
//if(!empty($geodir_addon_list) && array_key_exists('geodir_sharelocation_manager', $geodir_addon_list) && $geodir_addon_list['geodir_sharelocation_manager'] == 'yes') { 
add_action('wp_footer', 'geodir_add_sharelocation_scripts');
//}


/**
 * Save and update GeoDirectory navigation settings per theme.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $newname The theme name.
 * @ignore
 */
function geodir_unset_prev_theme_nav_location($newname)
{
    $geodir_theme_location = get_option('geodir_theme_location_nav_' . $newname);
    if ($geodir_theme_location) {
        update_option('geodir_theme_location_nav', $geodir_theme_location);
    } else {
        update_option('geodir_theme_location_nav', '');
    }
}

/// add action for theme switch to blank previous theme navigation location setting
add_action("switch_theme", "geodir_unset_prev_theme_nav_location", 10, 2);

/**
 * Contains functions/hooks for setting up the CPT and taxonomies for the plugin.
 *
 * @since 1.0.0
 */
require_once('geodirectory-functions/custom_taxonomy_hooks_actions.php');

/**
 * Includes the file that adds filters/functions to change the database queries.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_add_post_filters()
{
    /**
     * Contains all function for filtering listing.
     *
     * @since 1.0.0
     * @package GeoDirectory
     */
    include_once('geodirectory-functions/listing_filters.php');
    
    // Theme My Login compatibility fix
    if ( isset( $_REQUEST['geodir_search'] ) && class_exists( 'Theme_My_Login' ) ) {
        remove_action( 'pre_get_posts', array( Theme_My_Login::get_object(), 'pre_get_posts' ) );
    }
    
    if ( isset( $_REQUEST['geodir_search'] ) ) {
        add_filter( 'geodir_filter_widget_listings_fields', 'geodir_search_widget_location_filter_fields', 100, 3 );
        add_filter( 'geodir_filter_widget_listings_orderby', 'geodir_search_widget_location_filter_orderby', 100, 3 );
    }
}


if (!function_exists('geodir_init_defaults')) {
    /**
     * Calls the function to register the GeoDirectory default CPT and taxonomies.
     *
     * @since 1.0.0
     * @package GeoDirectory
     */
    function geodir_init_defaults()
    {
        if (function_exists('geodir_register_defaults')) {

            geodir_register_defaults();

        }

    }
}


/* Sidebar */
add_action('geodir_sidebar', 'geodir_get_sidebar', 10);


/* Pagination in loop-store */
add_action('geodir_pagination', 'geodir_pagination', 10);


/** Add Custom Menu Items **/


/** Replaces "Post" in the update messages for custom post types on the "Edit" post screen. **/
add_filter('post_updated_messages', 'geodir_custom_update_messages');


// CALLED ON 'sidebars_widgets' FILTER

if (!function_exists('geodir_restrict_widget')) {
    /**
     * Sets global values to be able to tell if the current page is a GeoDirectory listing page or a GeoDirectory details page.
     *
     * @global bool $is_listing Sets the global value to true if on a GD category page. False if not.
     * @global bool $is_single_place Sets the global value to true if on a GD details (post) page. False if not.
     * @since 1.0.0
     * @package GeoDirectory
     */
    function geodir_restrict_widget()
    {
        global $is_listing, $is_single_place;

        // set is listing	
        (geodir_is_page('listing')) ? $is_listing = true : $is_listing = false;

        // set is single place
        (geodir_is_page('place')) ? $is_single_place = true : $is_single_place = false;


    }
}


/////// GEO DIRECOTORY CUSTOM HOOKS ///

add_action('geodir_before_tab_content', 'geodir_before_tab_content');// this function is in custom_functions.php and it is used to wrap detail page tab content 
add_action('geodir_after_tab_content', 'geodir_after_tab_content');// this function is in custom_functions.php and it is used to wrap detail page tab content

// Detail page sidebar content 
add_action('geodir_detail_page_sidebar', 'geodir_detail_page_sidebar_content_sorting', 1);
/**
 * Builds an array of elements for the details (post) page sidebar.
 *
 * Builds an array of functions to be called in the details page (post) sidebar, this array can be changed via hook or filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_detail_page_sidebar_content_sorting()
{
    $arr_detail_page_sidebar_content =
        /**
         * An array of functions to be called to be displayed on the details (post) page sidebar.
         *
         * This filter can be used to remove sections of the details page sidebar,
         * add new sections or rearrange the order of the sections.
         *
         * @param array array('geodir_social_sharing_buttons','geodir_share_this_button','geodir_detail_page_google_analytics','geodir_edit_post_link','geodir_detail_page_review_rating','geodir_detail_page_more_info') The array of functions that will be called.
         * @since 1.0.0
         */
        apply_filters('geodir_detail_page_sidebar_content',
            array('geodir_social_sharing_buttons',
                'geodir_detail_page_google_analytics',
                'geodir_edit_post_link',
                'geodir_detail_page_review_rating',
                'geodir_detail_page_more_info'
            ) // end of array 
        ); // end of apply filter
    if (!empty($arr_detail_page_sidebar_content)) {
        foreach ($arr_detail_page_sidebar_content as $content_function) {
            if (function_exists($content_function)) {
                add_action('geodir_detail_page_sidebar', $content_function);
            }
        }
    }
}

add_action('geodir_after_edit_post_link', 'geodir_add_to_favourite_link', 1);

/**
 * Outputs the add to favourite line for the current post if not add listing preview page.
 *
 * @global object $post The current post object.
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * since 1.0.0
 * @package GeoDirectory
 */
function geodir_add_to_favourite_link()
{
    global $post, $preview;
    if (!$preview && geodir_is_page('detail')) {
        ?>
        <p class="edit_link">
            <?php geodir_favourite_html($post->post_author, $post->ID); ?>
        </p>
    <?php
    }
}

/**
 * Outputs social buttons.
 *
 * Outputs social sharing buttons twitter,facebook and google plus into a containing div if not on the add listing preview page.
 *
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_social_sharing_buttons()
{
    global $preview;
    ob_start(); // Start  buffering;
    /**
     * This action is called before the social buttons twitter,facebook and google plus are output in a containing div.
     *
     * @since 1.0.0
     */
    do_action('geodir_before_social_sharing_buttons');
    if (!$preview) {
        ?>
        <div class="likethis">
            <?php geodir_twitter_tweet_button(); ?>
            <?php geodir_fb_like_button(); ?>
            <?php geodir_google_plus_button(); ?>
        </div>
    <?php
    }// end of if, if its a preview or not

    /**
     * This action is called after the social buttons twitter,facebook and google plus are output in a containing div.
     *
     * @since 1.0.0
     */
    do_action('geodir_after_social_sharing_buttons');
    $content_html = ob_get_clean();
    if (trim($content_html) != '')
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-social-sharing">' . $content_html . '</div>';
    if ((int)get_option('geodir_disable_tfg_buttons_section') != 1) {
        /**
         * Filter the geodir_social_sharing_buttons() function content.
         *
         * @param string $content_html The output html of the geodir_social_sharing_buttons() function.
         */
        echo $content_html = apply_filters('geodir_social_sharing_buttons_html', $content_html);
    }


}


/**
 * Outputs the edit post link.
 *
 * Outputs the edit post link if the current logged in user owns the post.
 *
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * @global WP_Post|null $post The current post, if available.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_edit_post_link()
{
    global $post, $preview;
    ob_start(); // Start buffering;
    /**
     * This is called before the edit post link html in the function geodir_edit_post_link()
     *
     * @since 1.0.0
     */
    do_action('geodir_before_edit_post_link');
    if (!$preview) {
        $is_current_user_owner = geodir_listing_belong_to_current_user();
        
        if ($is_current_user_owner) {
            $post_id = $post->ID;
            
            if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                $post_id = (int)$_REQUEST['pid'];
            }

            $postlink = get_permalink(geodir_add_listing_page_id());
            $editlink = geodir_getlink($postlink, array('pid' => $post_id), false);
            echo ' <p class="edit_link"><i class="fa fa-pencil"></i> <a href="' . esc_url($editlink) . '">' . __('Edit this Post', 'geodirectory') . '</a></p>';
        }
    }// end of if, if its a preview or not
    /**
     * This is called after the edit post link html in the function geodir_edit_post_link()
     *
     * @since 1.0.0
     */
    do_action('geodir_after_edit_post_link');
    $content_html = ob_get_clean();
    if (trim($content_html) != '')
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-user-links">' . $content_html . '</div>';
    if ((int)get_option('geodir_disable_user_links_section') != 1) {
        /**
         * Filter the geodir_edit_post_link() function content.
         *
         * @param string $content_html The output html of the geodir_edit_post_link() function.
         */
        echo $content_html = apply_filters('geodir_edit_post_link_html', $content_html);
    }
}

/**
 * Outputs the google analytics section on details page.
 *
 * Outputs the google analytics html if the current logged in user owns the post.
 *
 * @global WP_Post|null $post The current post, if available.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_detail_page_google_analytics()
{
    global $post,$preview;
    if($preview){return '';}
    $package_info = array();
    $package_info = geodir_post_package_info($package_info, $post);

    $id = trim(get_option('geodir_ga_account_id'));

    if (!$id) {
        return; //if no Google Analytics ID then bail.
    }

    ob_start(); // Start buffering;
    /**
     * This is called before the edit post link html in the function geodir_detail_page_google_analytics()
     *
     * @since 1.0.0
     */
    do_action('geodir_before_google_analytics');
    
    $refresh_time = get_option('geodir_ga_refresh_time', 5);
    /**
     * Filter the time interval to check & refresh new users results.
     *
     * @since 1.5.9
     *
     * @param int $refresh_time Time interval to check & refresh new users results.
     */
    $refresh_time = apply_filters('geodir_google_analytics_refresh_time', $refresh_time);
    $refresh_time = absint($refresh_time * 1000);
    
    $hide_refresh = get_option('geodir_ga_auto_refresh');
    
    $auto_refresh = $hide_refresh && $refresh_time && $refresh_time > 0 ? 1 : 0;
    if (get_option('geodir_ga_stats') && is_user_logged_in() &&  (isset($package_info->google_analytics) && $package_info->google_analytics == '1') && (get_current_user_id()==$post->post_author || current_user_can( 'manage_options' )) ) {
        $page_url = urlencode($_SERVER['REQUEST_URI']);
        ?>
        <script type="text/javascript">
            var gd_gaTimeOut;
            var gd_gaTime = parseInt('<?php echo $refresh_time;?>');
            var gd_gaHideRefresh = <?php echo (int)$hide_refresh;?>;
            var gd_gaAutoRefresh = <?php echo $auto_refresh;?>;
            ga_data1 = false;
            ga_data2 = false;
            ga_data3 = false;
            ga_data4 = false;
            ga_data5 = false;
            ga_data6 = false;
            ga_au = 0;
            jQuery(document).ready(function() {              
                // Set some global Chart.js defaults.
                Chart.defaults.global.animationSteps = 60;
                Chart.defaults.global.animationEasing = 'easeInOutQuart';
                Chart.defaults.global.responsive = true;
                Chart.defaults.global.maintainAspectRatio = false;
                
                jQuery('.gdga-show-analytics').click(function(e){
                    jQuery(this).hide();
                    jQuery('.gdga-analytics-box').show();
                    gdga_weekVSweek();
                    gdga_realtime(true);
                });

                if (gd_gaAutoRefresh !== 1) {
                    jQuery('.fa#gdga-loader-icon').click(function(e){
                        gdga_refresh();
                        clearTimeout(gd_gaTimeOut);
                        gdga_realtime();
                    });
                }
            });

            function gdga_weekVSweek() {
                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=thisweek'); ?>", success: function(result){
                    ga_data1 = jQuery.parseJSON(result);
                    if(ga_data1.error){jQuery('#ga_stats').html(result);return;}
                    gd_renderWeekOverWeekChart();
                }});

                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=lastweek'); ?>", success: function(result){
                    ga_data2 = jQuery.parseJSON(result);
                    gd_renderWeekOverWeekChart();
                }});
            }

            function gdga_yearVSyear() {
                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=thisyear'); ?>", success: function(result){
                    ga_data3 = jQuery.parseJSON(result);
                    if(ga_data3.error){jQuery('#ga_stats').html(result);return;}

                    gd_renderYearOverYearChart()
                }});

                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=lastyear'); ?>", success: function(result){
                    ga_data4 = jQuery.parseJSON(result);
                    gd_renderYearOverYearChart()
                }});
            }

            function gdga_country() {
                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=country'); ?>", success: function(result){
                    ga_data5 = jQuery.parseJSON(result);
                    if(ga_data5.error){jQuery('#ga_stats').html(result);return;}
                    gd_renderTopCountriesChart();
                }});
            }

            function gdga_realtime(dom_ready) {
                jQuery.ajax({url: "<?php echo admin_url('admin-ajax.php?action=gdga&ga_page='.$page_url.'&ga_type=realtime'); ?>", success: function(result) {
                    ga_data6 = jQuery.parseJSON(result);
                    if (ga_data6.error) {
                        jQuery('#ga_stats').html(result);
                        return;
                    }
                    gd_renderRealTime(dom_ready);
                }});
            }

            function gd_renderRealTime(dom_ready) {
                if (typeof dom_ready === 'undefined') {
                    gdga_refresh(true);
                }
                ga_au_old = ga_au;

                ga_au = ga_data6.totalsForAllResults["rt:activeUsers"];
                if (ga_au>ga_au_old) {
                    jQuery('.gd-ActiveUsers').addClass( "is-increasing");
                }

                if (ga_au<ga_au_old) {
                    jQuery('.gd-ActiveUsers').addClass( "is-decreasing");
                }
                
                jQuery('.gd-ActiveUsers-value').html(ga_au);

                if (gd_gaTime > 0 && gd_gaAutoRefresh === 1) {
                    // check for new users every 5 seconds
                    gd_gaTimeOut = setTimeout(function() {
                        jQuery('.gd-ActiveUsers').removeClass("is-increasing is-decreasing");
                        gdga_realtime();
                    }, gd_gaTime);
                }
            }

            /**
             * Draw the a chart.js doughnut chart with data from the specified view that
             * compares sessions from mobile, desktop, and tablet over the past two
             * weeks.
             */
            function gd_renderTopCountriesChart() {
                if (ga_data5) {
                    response = ga_data5;
                    ga_data5 = false;
                } else {
                    return;
                }

                jQuery('#gdga-chart-container').show();
                jQuery('#gdga-legend-container').show();
                gdga_refresh(true);
                jQuery('#gdga-select-analytic').show();
                
                var data = [];
                var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

                if (response.rows) {
                    response.rows.forEach(function (row, i) {
                        data.push({
                            label: row[0],
                            value: +row[1],
                            color: colors[i]
                        });
                    });

                    new Chart(makeCanvas('gdga-chart-container')).Doughnut(data);
                    generateLegend('gdga-legend-container', data);
                } else {
                    gdga_noResults();
                }
            }

            function gdga_noResults() {
                jQuery('#gdga-chart-container').html('<?php _e('No results available','geodirectory');?>');
                jQuery('#gdga-legend-container').html('');
            }

            /**
             * Draw the a chart.js bar chart with data from the specified view that
             * overlays session data for the current year over session data for the
             * previous year, grouped by month.
             */
            function gd_renderYearOverYearChart() {
                if (ga_data3 && ga_data4) {
                    thisYear = ga_data3;
                    lastYear = ga_data4;
                    ga_data3 = false;
                    ga_data4 = false;
                } else {
                    return;
                }

                jQuery('#gdga-chart-container').show();
                jQuery('#gdga-legend-container').show();
                gdga_refresh(true);
                jQuery('#gdga-select-analytic').show();

                // Adjust `now` to experiment with different days, for testing only...
                var now = moment(); // .subtract(3, 'day');

                Promise.all([thisYear, lastYear]).then(function(results) {
                    var data1 = results[0].rows.map(function(row) { return +row[2]; });
                    var data2 = results[1].rows.map(function(row) { return +row[2]; });
                    //var labelsN = results[0].rows.map(function(row) { return +row[1]; });

                    var labels = ['<?php _e('Jan', 'geodirectory');?>',
                        '<?php _e('Feb', 'geodirectory');?>',
                        '<?php _e('Mar', 'geodirectory');?>',
                        '<?php _e('Apr', 'geodirectory');?>',
                        '<?php _e('May', 'geodirectory');?>',
                        '<?php _e('Jun', 'geodirectory');?>',
                        '<?php _e('Jul', 'geodirectory');?>',
                        '<?php _e('Aug', 'geodirectory');?>',
                        '<?php _e('Sep', 'geodirectory');?>',
                        '<?php _e('Oct', 'geodirectory');?>',
                        '<?php _e('Nov', 'geodirectory');?>',
                        '<?php _e('Dec', 'geodirectory');?>'];

                    // Ensure the data arrays are at least as long as the labels array.
                    // Chart.js bar charts don't (yet) accept sparse datasets.
                    for (var i = 0, len = labels.length; i < len; i++) {
                        if (data1[i] === undefined) data1[i] = null;
                        if (data2[i] === undefined) data2[i] = null;
                    }

                    var data = {
                        labels : labels,
                        datasets : [
                            {
                                label: '<?php _e('Last Year', 'geodirectory');?>',
                                fillColor : "rgba(220,220,220,0.5)",
                                strokeColor : "rgba(220,220,220,1)",
                                data : data2
                            },
                            {
                                label: '<?php _e('This Year', 'geodirectory');?>',
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : data1
                            }
                        ]
                    };

                    new Chart(makeCanvas('gdga-chart-container')).Bar(data);
                    generateLegend('gdga-legend-container', data.datasets);
                }).catch(function(err) {
                    console.error(err.stack);
                })
            }

            /**
             * Draw the a chart.js line chart with data from the specified view that
             * overlays session data for the current week over session data for the
             * previous week.
             */
            function gd_renderWeekOverWeekChart() {
                if(ga_data1 && ga_data2){
                    thisWeek = ga_data1;
                    lastWeek = ga_data2;
                    ga_data1 = false;
                    ga_data2 = false;
                }else{
                    return;
                }

                jQuery('#gdga-chart-container').show();
                jQuery('#gdga-legend-container').show();
                gdga_refresh(true);
                jQuery('#gdga-select-analytic').show();

                // Adjust `now` to experiment with different days, for testing only...
                var now = moment();

                Promise.all([thisWeek, lastWeek]).then(function(results) {
                    var data1 = results[0].rows.map(function(row) { return +row[2]; });
                    var data2 = results[1].rows.map(function(row) { return +row[2]; });
                    var labels = results[1].rows.map(function(row) { return +row[0]; });

                    <?php
                    // Here we list the shorthand days of the week so it can be used in translation.
                    __("Mon",'geodirectory');
                    __("Tue",'geodirectory');
                    __("Wed",'geodirectory');
                    __("Thu",'geodirectory');
                    __("Fri",'geodirectory');
                    __("Sat",'geodirectory');
                    __("Sun",'geodirectory');
                    ?>

                    labels = [
                        "<?php _e(date('D', strtotime("+1 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+2 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+3 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+4 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+5 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+6 day")),'geodirectory'); ?>",
                        "<?php _e(date('D', strtotime("+7 day")),'geodirectory'); ?>"
                    ];

                    var data = {
                        labels : labels,
                        datasets : [
                            {
                                label: '<?php _e('Last Week', 'geodirectory');?>',
                                fillColor : "rgba(220,220,220,0.5)",
                                strokeColor : "rgba(220,220,220,1)",
                                pointColor : "rgba(220,220,220,1)",
                                pointStrokeColor : "#fff",
                                data : data2
                            },
                            {
                                label: '<?php _e('This Week', 'geodirectory');?>',
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,1)",
                                pointColor : "rgba(151,187,205,1)",
                                pointStrokeColor : "#fff",
                                data : data1
                            }
                        ]
                    };

                    new Chart(makeCanvas('gdga-chart-container')).Line(data);
                    generateLegend('gdga-legend-container', data.datasets);
                });
            }

            /**
             * Create a new canvas inside the specified element. Set it to be the width
             * and height of its container.
             * @param {string} id The id attribute of the element to host the canvas.
             * @return {RenderingContext} The 2D canvas context.
             */
            function makeCanvas(id) {
                var container = document.getElementById(id);
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');

                container.innerHTML = '';
                canvas.width = container.offsetWidth;
                canvas.height = container.offsetHeight;
                container.appendChild(canvas);

                return ctx;
            }

            /**
             * Create a visual legend inside the specified element based off of a
             * Chart.js dataset.
             * @param {string} id The id attribute of the element to host the legend.
             * @param {Array.<Object>} items A list of labels and colors for the legend.
             */
            function generateLegend(id, items) {
                var legend = document.getElementById(id);
                legend.innerHTML = items.map(function(item) {
                    var color = item.color || item.fillColor;
                    var label = item.label;
                    return '<li><i style="background:' + color + '"></i>' + label + '</li>';
                }).join('');
            }

            function gdga_select_option() {
                jQuery('#gdga-select-analytic').hide();
                gdga_refresh();

                gaType = jQuery('#gdga-select-analytic').val();

                if (gaType=='weeks') {
                    gdga_weekVSweek();
                } else if (gaType=='years') {
                    gdga_yearVSyear();
                } else if(gaType=='country') {
                    gdga_country();
                }
            }
            
            function gdga_refresh(stop) {
                if (typeof stop !== 'undefined' && stop) {
                    if (gd_gaAutoRefresh === 1 || gd_gaHideRefresh == 1) {
                        jQuery('#gdga-loader-icon').hide();
                    } else {
                        jQuery('#gdga-loader-icon').removeClass('fa-spin');
                    }
                } else {
                    if (gd_gaAutoRefresh === 1 || gd_gaHideRefresh == 1) {
                        jQuery('#gdga-loader-icon').show();
                    } else {
                        if (!jQuery('#gdga-loader-icon').hasClass('fa-spin')) {
                            jQuery('#gdga-loader-icon').addClass('fa-spin');
                        }
                    }
                }
            }
        </script>
        <style>
            .geodir-details-sidebar-google-analytics {
                min-height: 60px;
            }
            #ga_stats #gd-active-users-container {
                float: right;
                margin: 0 0 10px;
            }

            #gdga-select-analytic {
                clear: both;
            }

            #ga_stats #ga-analytics-title{
                float: left;
                font-weight: bold;
            }

            #ga_stats #gd-active-users-container{
                float: right;
            }
            .Chartjs {
                font-size: .85em
            }

            .Chartjs-figure {
                height: 200px;
                width: 100%;
                display: none;
            }

            .Chartjs-legend {
                list-style: none;
                margin: 0;
                padding: 1em 0 0;
                text-align: center;
                width: 100%;
                display: none;
            }

            .Chartjs-legend>li {
                display: inline-block;
                padding: .25em .5em
            }

            .Chartjs-legend>li>i {
                display: inline-block;
                height: 1em;
                margin-right: .5em;
                vertical-align: -.1em;
                width: 1em
            }

            @media (min-width: 570px) {
                .Chartjs-figure {
                    margin-right:1.5em
                }
            }

            .gd-ActiveUsers {
                background: #f3f2f0;
                border: 1px solid #d4d2d0;
                border-radius: 4px;
                font-weight: 300;
                padding: .5em 1.5em;
                white-space: nowrap
            }

            .gd-ActiveUsers-value {
                display: inline-block;
                font-weight: 600;
                margin-right: -.25em
            }

            .gd-ActiveUsers.is-increasing {
                -webkit-animation: increase 3s;
                animation: increase 3s
            }

            .gd-ActiveUsers.is-decreasing {
                -webkit-animation: decrease 3s;
                animation: decrease 3s
            }

            @-webkit-keyframes increase {
                10% {
                    background-color: #eaffea;
                    border-color: hsla(120,100%,25%,.5);
                    color: hsla(120,100%,25%,1)
                }
            }

            @keyframes increase {
                10% {
                    background-color: #eaffea;
                    border-color: hsla(120,100%,25%,.5);
                    color: hsla(120,100%,25%,1)
                }
            }

            @-webkit-keyframes decrease {
                10% {
                    background-color: #ffeaea;
                    border-color: hsla(0,100%,50%,.5);
                    color: red
                }
            }

            @keyframes decrease {
                10% {
                    background-color: #ffeaea;
                    border-color: hsla(0,100%,50%,.5);
                    color: red
                }
            }
            .fa#gdga-loader-icon {
                margin: 0 10px 0 -10px;
                color: #333333;
                cursor: pointer;
                -webkit-animation-duration:1.5s;
                animation-duration:1.5s;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <button type="button" class="gdga-show-analytics"><?php _e('Show Google Analytics', 'geodirectory');?></button>
        <span id="ga_stats" class="gdga-analytics-box" style="display:none">
            <div id="ga-analytics-title"><?php _e("Analytics", 'geodirectory');?></div>
            <div id="gd-active-users-container">
                <div class="gd-ActiveUsers"><i id="gdga-loader-icon" class="fa fa-refresh fa-spin" title="<?php esc_attr_e("Refresh", 'geodirectory');?>"></i><?php _e("Active Users:", 'geodirectory');?>
                    <b class="gd-ActiveUsers-value">0</b>
                </div>
            </div>
            <select id="gdga-select-analytic" onchange="gdga_select_option();" style="display: none;">
                <option value="weeks"><?php _e("Last Week vs This Week", 'geodirectory');?></option>
                <option value="years"><?php _e("This Year vs Last Year", 'geodirectory');?></option>
                <option value="country"><?php _e("Top Countries", 'geodirectory');?></option>
            </select>
            <div class="Chartjs-figure" id="gdga-chart-container"></div>
            <ol class="Chartjs-legend" id="gdga-legend-container"></ol>
        </span>

    <?php
    }
    /**
     * This is called after the edit post link html in the function geodir_detail_page_google_analytics()
     *
     * @since 1.0.0
     */
    do_action('geodir_after_google_analytics');
    $content_html = ob_get_clean();
    if (trim($content_html) != '')
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-google-analytics">' . $content_html . '</div>';
    if ((int)get_option('geodir_disable_google_analytics_section') != 1) {
        /**
         * Filter the geodir_edit_post_link() function content.
         *
         * @param string $content_html The output html of the geodir_edit_post_link() function.
         */
        echo $content_html = apply_filters('geodir_google_analytic_html', $content_html);
    }
}

/**
 * Output the current post overall review and a small image compatible with google hreviews.
 *
 * @global WP_Post|null $post The current post, if available.
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * @global object $post_images Image objects of current post if available.
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @deprecated 1.6.3 Use geodir_action_details_micordata()
 * @see geodir_action_details_micordata()
 * @package GeoDirectory
 */
function geodir_detail_page_review_rating()
{
    global $post, $preview, $post_images;
    
    if (!empty($post->ID) && geodir_cpt_has_rating_disabled((int)$post->ID)) {
        return;
    }
    ob_start(); // Start  buffering;
    /**
     * This is called before the rating html in the function geodir_detail_page_review_rating().
     *
     * This is called outside the check for an actual rating and the check for preview page.
     *
     * @since 1.0.0
     */
    do_action('geodir_before_detail_page_review_rating');

    $comment_count = geodir_get_review_count_total($post->ID);
    $post_avgratings = geodir_get_post_rating($post->ID);

    if ($post_avgratings != 0 && !$preview) {
        /**
         * This is called before the rating html in the function geodir_detail_page_review_rating().
         *
         * This is called inside the check for an actual rating and the check for preview page.
         *
         * @since 1.0.0
         * @param float $post_avgratings Average rating for the current post.
         * @param int $post->ID Current post ID.
         */
        do_action('geodir_before_review_rating_stars_on_detail', $post_avgratings, $post->ID);

        $html = '<p style=" float:left;">';
        $html .= geodir_get_rating_stars($post_avgratings, $post->ID);
        $html .= '<div class="average-review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">';
        $post_avgratings = (is_float($post_avgratings) || (strpos($post_avgratings, ".", 1) == 1 && strlen($post_avgratings) > 3)) ? number_format($post_avgratings, 1, '.', '') : $post_avgratings;
       
	   $reviews_text = $comment_count > 1 ? __("reviews", 'geodirectory') : __("review", 'geodirectory');
	   
	   $html .= '<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><span class="rating" itemprop="average" content="' . $post_avgratings . '">' . $post_avgratings . '</span> / <span itemprop="best" content="5">5</span> ' . __("based on", 'geodirectory') . ' </span><span class="count" itemprop="count" content="' . $comment_count . '">' . $comment_count . ' ' . $reviews_text . '</span><br />';

        $html .= '<span class="item">';
        $html .= '<span class="fn" itemprop="itemreviewed">' . $post->post_title . '</span>';

        if ($post_images) {
            foreach ($post_images as $img) {
                $post_img = $img->src;
                break;
            }
        }

        if (isset($post_img) && $post_img) {
            $html .= '<br /><img src="' . $post_img . '" class="photo" alt="' . esc_attr($post->post_title) . '" itemprop="photo" content="' . $post_img . '" class="photo" />';
        }

        $html .= '</span>';

        echo $html .= '</div>';
        /**
         * This is called after the rating html in the function geodir_detail_page_review_rating().
         *
         * This is called inside the check for an actual rating and the check for preview page.
         *
         * @since 1.0.0
         * @param float $post_avgratings Average rating for the current post.
         * @param int $post->ID Current post ID.
         */
        do_action('geodir_after_review_rating_stars_on_detail', $post_avgratings, $post->ID);
    }
    /**
     * This is called before the rating html in the function geodir_detail_page_review_rating().
     *
     * This is called outside the check for an actual rating and the check for preview page.
     *
     * @since 1.0.0
     */
    do_action('geodir_after_detail_page_review_rating');
    $content_html = ob_get_clean();
    if (trim($content_html) != '') {
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-rating">' . $content_html . '</div>';
    }
    if ((int)get_option('geodir_disable_rating_info_section') != 1) {
        /**
         * Filter the geodir_detail_page_review_rating() function content.
         *
         * @since 1.0.0
         * @param string $content_html The output html of the geodir_detail_page_review_rating() function.
         */
        echo $content_html = apply_filters('geodir_detail_page_review_rating_html', $content_html);
    }
}

/**
 * This outputs the info section of the details page.
 *
 * This outputs the info section of the details page which includes all the post custom fields.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_detail_page_more_info()
{
    ob_start(); // Start  buffering;
    /**
     * This is called before the info section html.
     *
     * @since 1.0.0
     */
    do_action('geodir_before_detail_page_more_info');
    if ($geodir_post_detail_fields = geodir_show_listing_info('detail')) {
        echo $geodir_post_detail_fields;
    }
    /**
     * This is called after the info section html.
     *
     * @since 1.0.0
     */
    do_action('geodir_after_detail_page_more_info');

    $content_html = ob_get_clean();
    if (trim($content_html) != '')
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-listing-info">' . $content_html . '</div>';
    if ((int)get_option('geodir_disable_listing_info_section') != 1) {
        /**
         * Filter the output html for function geodir_detail_page_more_info().
         *
         * @since 1.0.0
         * @param string $content_html The output html of the geodir_detail_page_more_info() function.
         */
        echo $content_html = apply_filters('geodir_detail_page_more_info_html', $content_html);
    }
}


/**
 * Outputs translated JS text strings.
 *
 * This function outputs text strings used in JS files as a json array of strings so they can be translated and still be used in JS files.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_localize_all_js_msg()
{// check_ajax_referer function is used to make sure no files are uploaded remotely but it will fail if used between https and non https so we do the check below of the urls
    if (str_replace("https", "http", admin_url('admin-ajax.php')) && !empty($_SERVER['HTTPS'])) {
        $ajax_url = admin_url('admin-ajax.php');
    } elseif (!str_replace("https", "http", admin_url('admin-ajax.php')) && empty($_SERVER['HTTPS'])) {
        $ajax_url = admin_url('admin-ajax.php');
    } elseif (str_replace("https", "http", admin_url('admin-ajax.php')) && empty($_SERVER['HTTPS'])) {
        $ajax_url = str_replace("https", "http", admin_url('admin-ajax.php'));
    } elseif (!str_replace("https", "http", admin_url('admin-ajax.php')) && !empty($_SERVER['HTTPS'])) {
        $ajax_url = str_replace("http", "https", admin_url('admin-ajax.php'));
    }
	
	/**
	 * Filter the allowed image type extensions for post images.
	 *
	 * @since 1.4.7.1
	 * @param string $allowed_img_types The image type extensions array.
	 */
	$allowed_img_types = apply_filters('geodir_allowed_post_image_exts', array('jpg', 'jpeg', 'jpe', 'gif', 'png'));
	
    $default_marker_icon = get_option('geodir_default_marker_icon');
    $default_marker_size = geodir_get_marker_size($default_marker_icon, array('w' => 20, 'h' => 34));
    $default_marker_width = $default_marker_size['w'];
    $default_marker_height = $default_marker_size['h'];
    
    $arr_alert_msg = array(
        'geodir_plugin_url' => geodir_plugin_url(),
        'geodir_admin_ajax_url' => $ajax_url,
        'custom_field_not_blank_var' => __('HTML Variable Name must not be blank', 'geodirectory'),
        'custom_field_not_special_char' => __('Please do not use special character and spaces in HTML Variable Name.', 'geodirectory'),
        'custom_field_unique_name' => __('HTML Variable Name should be a unique name.', 'geodirectory'),
        'custom_field_delete' => __('Are you wish to delete this field?', 'geodirectory'),
        //start not show alert msg
        'tax_meta_class_succ_del_msg' => __('File has been successfully deleted.', 'geodirectory'),
        'tax_meta_class_not_permission_to_del_msg' => __('You do NOT have permission to delete this file.', 'geodirectory'),
        'tax_meta_class_order_save_msg' => __('Order saved!', 'geodirectory'),
        'tax_meta_class_not_permission_record_img_msg' => __('You do not have permission to reorder images.', 'geodirectory'),
        'address_not_found_on_map_msg' => __('Address not found for:', 'geodirectory'),
        // end not show alert msg
        'my_place_listing_del' => __('Are you sure you wish to delete this listing?', 'geodirectory'),
        'my_main_listing_del' => __('Deleting the main listing of a franchise will turn all franchises in regular listings. Are you sure wish to delete this main listing?', 'geodirectory'),
        //start not show alert msg
        'rating_error_msg' => __('Error : please retry', 'geodirectory'),
        'listing_url_prefix_msg' => __('Please enter listing url prefix', 'geodirectory'),
        'invalid_listing_prefix_msg' => __('Invalid character in listing url prefix', 'geodirectory'),
        'location_url_prefix_msg' => __('Please enter location url prefix', 'geodirectory'),
        'invalid_location_prefix_msg' => __('Invalid character in location url prefix', 'geodirectory'),
        'location_and_cat_url_separator_msg' => __('Please enter location and category url separator', 'geodirectory'),
        'invalid_char_and_cat_url_separator_msg' => __('Invalid character in location and category url separator', 'geodirectory'),
        'listing_det_url_separator_msg' => __('Please enter listing detail url separator', 'geodirectory'),
        'invalid_char_listing_det_url_separator_msg' => __('Invalid character in listing detail url separator', 'geodirectory'),
        'loading_listing_error_favorite' => __('Error loading listing.', 'geodirectory'),
        'geodir_field_id_required' => __('This field is required.', 'geodirectory'),
        'geodir_valid_email_address_msg' => __('Please enter valid email address.', 'geodirectory'),
        'geodir_default_marker_icon' => $default_marker_icon,
        'geodir_default_marker_w' => $default_marker_width,
        'geodir_default_marker_h' => $default_marker_height,
        'geodir_latitude_error_msg' => GEODIR_LATITUDE_ERROR_MSG,
        'geodir_longgitude_error_msg' => GEODIR_LOGNGITUDE_ERROR_MSG,
        'geodir_default_rating_star_icon' => get_option('geodir_default_rating_star_icon'),
        'gd_cmt_btn_post_reply' => __('Post Reply', 'geodirectory'),
        'gd_cmt_btn_reply_text' => __('Reply text', 'geodirectory'),
        'gd_cmt_btn_post_review' => __('Post Review', 'geodirectory'),
        'gd_cmt_btn_review_text' => __('Review text', 'geodirectory'),
        'gd_cmt_err_no_rating' => __("Please select star rating, you can't leave a review without stars.", 'geodirectory'),
        /* on/off dragging for phone devices */
        'geodir_onoff_dragging' => get_option('geodir_map_onoff_dragging') ? true : false,
        'geodir_is_mobile' => wp_is_mobile() ? true : false,
        'geodir_on_dragging_text' => __('Enable Dragging', 'geodirectory'),
        'geodir_off_dragging_text' => __('Disable Dragging', 'geodirectory'),
        'geodir_err_max_file_size' => __('File size error : You tried to upload a file over %s', 'geodirectory'),
        'geodir_err_file_upload_limit' => __('You have reached your upload limit of %s files.', 'geodirectory'),
        'geodir_err_pkg_upload_limit' => __('You may only upload %s files with this package, please try again.', 'geodirectory'),
        'geodir_action_remove' => __('Remove', 'geodirectory'),
		'geodir_txt_all_files' => __('Allowed files', 'geodirectory'),
		'geodir_err_file_type' => __('File type error. Allowed file types: %s', 'geodirectory'),
		'gd_allowed_img_types' => !empty($allowed_img_types) ? implode(',', $allowed_img_types) : '',
		'geodir_txt_form_wait' => __('Wait...', 'geodirectory'),
		'geodir_txt_form_searching' => __('Searching...', 'geodirectory'),
		'fa_rating' => (int)get_option('geodir_reviewrating_enable_font_awesome') == 1 ? 1 : '',
		'reviewrating' => defined('GEODIRREVIEWRATING_VERSION') ? 1 : '',
        'multirating' => defined('GEODIRREVIEWRATING_VERSION') && get_option('geodir_reviewrating_enable_rating') ? true : false,
        'geodir_map_name' => geodir_map_name(),
        'osmStart' => __('Start', 'geodirectory'),
        'osmVia' => __('Via {viaNumber}', 'geodirectory'),
        'osmEnd' => __('Enter Your Location', 'geodirectory'),
        'ga_delete_check' => __('Do you wish to Deauthorize and break Analytics?', 'geodirectory'),
        'geoMyLocation' => __('My Location', 'geodirectory'),
        'geoErrUNKNOWN_ERROR' => addslashes(__('Unable to find your location', 'geodirectory')),
        'geoErrPERMISSION_DENINED' => addslashes(__('Permission denied in finding your location', 'geodirectory')),
        'geoErrPOSITION_UNAVAILABLE' => addslashes(__('Your location is currently unknown', 'geodirectory')),
        'geoErrBREAK' => addslashes(__('Attempt to find location took too long', 'geodirectory')),
        'geoErrDEFAULT' => addslashes(__('Location detection not supported in browser', 'geodirectory')),
    );

    /**
     * Filters the translated JS strings from function geodir_localize_all_js_msg().
     *
     * With this filter you can add, remove or change translated JS strings.
     * You should add your own translations to this if you are building an addon rather than adding another script block.
     *
     * @since 1.0.0
     */
    $arr_alert_msg = apply_filters('geodir_all_js_msg', $arr_alert_msg);

    foreach ($arr_alert_msg as $key => $value) {
        if (!is_scalar($value))
            continue;
        $arr_alert_msg[$key] = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
    }

    $script = "var geodir_all_js_msg = " . json_encode($arr_alert_msg) . ';';
    echo '<script>';
    echo $script;
    echo '</script>';
}

add_action('admin_bar_menu', 'geodir_admin_bar_site_menu', 31);
/**
 * Add GeoDirectory link to the WordPress admin bar.
 *
 * This function adds a link to the GeoDirectory backend to the WP admin bar via a hook.
 *    add_action('admin_bar_menu', 'geodir_admin_bar_site_menu', 31);
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param object $wp_admin_bar The admin bar object.
 */
function geodir_admin_bar_site_menu($wp_admin_bar)
{
    if (get_option("geodir_installed")) {
        if (current_user_can('manage_options')) {
            $wp_admin_bar->add_menu(array('parent' => 'appearance', 'id' => 'geodirectory', 'title' => __('GeoDirectory', 'geodirectory'), 'href' => admin_url('?page=geodirectory')));
        }
    }
}

add_action('geodir_before_listing', 'geodir_display_sort_options'); /*function in custom_functions.php*/

add_filter('geodir_posts_order_by_sort', 'geodir_posts_order_by_custom_sort', 0, 3);

add_filter('geodir_advance_custom_fields_heading', 'geodir_advance_customfields_heading', 0, 2);


add_action('switch_theme', 'geodir_store_sidebars');

/**
 * Stores the GeoDirectory widget locations in the theme widget areas.
 *
 * This function loops through the GeoDirectory widgets and saves their locations in the widget areas to an option
 * so they can be restored later. This is called via hook.
 *    add_action('switch_theme', 'geodir_store_sidebars');
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global array $geodir_sidebars List of geodirectory sidebars.
 * @global array $sidebars_widgets List of geodirectory sidebar widgets.
 */
function geodir_store_sidebars()
{
    global $geodir_sidebars;
    global $sidebars_widgets;

    if (!is_array($sidebars_widgets))
        $sidebars_widgets = wp_get_sidebars_widgets();
    $geodir_old_sidebars = array();

    if (is_array($geodir_sidebars)) {
        foreach ($geodir_sidebars as $val) {
            if (is_array($sidebars_widgets)) {
                if (array_key_exists($val, $sidebars_widgets))
                    $geodir_old_sidebars[$val] = $sidebars_widgets[$val];
                else
                    $geodir_old_sidebars[$val] = array();
            }
        }
    }
    update_option('geodir_sidebars', $geodir_old_sidebars);
    geodir_option_version_backup('geodir_sidebars');

}

add_action('after_switch_theme', 'geodir_restore_sidebars');
/**
 * Restore sidebars.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global array $sidebars_widgets List of geodirectory sidebar widgets.
 */
function geodir_restore_sidebars()
{
    global $sidebars_widgets;

    if (!is_array($sidebars_widgets))
        $sidebars_widgets = wp_get_sidebars_widgets();

    if (is_array($sidebars_widgets)) {
        $geodir_old_sidebars = get_option('geodir_sidebars');
        if (is_array($geodir_old_sidebars)) {
            foreach ($geodir_old_sidebars as $key => $val) {
                if(0 === strpos($key, 'geodir_'))// if gd widget
                {
                    $sidebars_widgets[$key] = $geodir_old_sidebars[$key];
                }


            }
        }

    }

    update_option('sidebars_widgets', $sidebars_widgets);
    update_option('geodir_sidebars', '');
}

add_action('geodir_after_listing_post_gridview', 'geodir_after_listing_post_gridview');
/**
 * Set gridview columns value.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $gridview_columns The girdview style of the listings.
 */
function geodir_after_listing_post_gridview()
{
    global $gridview_columns;

    $gridview_columns = '';

}

/*
add_filter('script_loader_src' , 'geodir_script_loader_src');

function geodir_script_loader_src($url)
{
	if (strstr($url, "maps") !== false) {
       echo  $url = str_replace("&amp;", "&", $url); // or $url = $original_url
    }
	return $url ;
}*/

add_filter('clean_url', 'so_handle_038', 99, 3);
/**
 * Clean url.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $url Url.
 * @param string $original_url Original url.
 * @param string $_context Context.
 * @return string Modified url.
 */
function so_handle_038($url, $original_url, $_context)
{
    if (strstr($url, "maps.google.com/maps/api/js") !== false) {
        $url = str_replace("&#038;", "&amp;", $url); // or $url = $original_url
    }

    return $url;
}


add_action('geodir_after_main_form_fields', 'geodir_after_main_form_fields', 1);
/**
 * Add html after main form fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 * @global object $gd_session GeoDirectory Session object.
 */
function geodir_after_main_form_fields() {
	global $gd_session;
	
    if (get_option('geodir_accept_term_condition')) {
        global $post;
        $term_condition = '';
        if (isset($_REQUEST['backandedit'])) {
            $post = (object)$gd_session->get('listing');
            $term_condition = isset($post->geodir_accept_term_condition) ? $post->geodir_accept_term_condition : '';
        }

        ?>
        <div id="geodir_accept_term_condition_row" class="required_field geodir_form_row clearfix">
            <label>&nbsp;</label>

            <div class="geodir_taxonomy_field" style="float:left; width:70%;">
				<span style="display:block"> 
				<input class="main_list_selecter" type="checkbox" <?php if ($term_condition == '1') {
                    echo 'checked="checked"';
                } ?> field_type="checkbox" name="geodir_accept_term_condition" id="geodir_accept_term_condition"
                       class="geodir_textfield" value="1"
                       style="display:inline-block"/><a href="<?php $terms_page = get_option('geodir_term_condition_page'); if($terms_page){ echo get_permalink($terms_page);}?>" target="_blank"><?php _e('Please accept our terms and conditions', 'geodirectory'); ?></a>
				</span>
            </div>
            <span class="geodir_message_error"><?php if (isset($required_msg)) {
                    _e($required_msg, 'geodirectory');
                } ?></span>
        </div>
    <?php

    }
}


/* ------------------------------START CODE FOR HIDE/DISPLAY TABS */

add_filter('geodir_detail_page_tab_is_display', 'geodir_detail_page_tab_is_display', 0, 2);

/**
 * Check whether custom field should be displayed or not, on the details page tab.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 * @global object $post_images Image objects of current post if available.
 * @global string $video The video embed content.
 * @global string $special_offers Special offers content.
 * @global string $related_listing Related listing html.
 * @global string $geodir_post_detail_fields Detail field html.
 * @param bool $is_display Old display value.
 * @param string $tab Tab type.
 * @return bool New display value. If display returns true.
 */
function geodir_detail_page_tab_is_display($is_display, $tab)
{
    global $post, $post_images, $video, $special_offers, $related_listing, $geodir_post_detail_fields;

    if ($tab == 'post_profile') {
        /** This action is documented in geodirectory_template_actions.php */
        $desc_limit = apply_filters('geodir_description_field_desc_limit', '');
        
        if (!($desc_limit === '' || (int)$desc_limit > 0)) {
            $is_display = false;
        }
    }
    
    if ($tab == 'post_info')
        $is_display = (!empty($geodir_post_detail_fields)) ? true : false;
    
    if ($tab == 'post_images')
        $is_display = (!empty($post_images)) ? true : false;

    if ($tab == 'post_video')
        $is_display = (!empty($video)) ? true : false;

    if ($tab == 'special_offers')
        $is_display = (!empty($special_offers)) ? true : false;

    if ($tab == 'reviews')
        $is_display = (geodir_is_page('detail')) ? true : false;

    if ($tab == 'related_listing') {
       $message = __('No listings found which match your selection.', 'geodirectory');
       
       /** This action is documented in geodirectory-functions/template_functions.php */
       $message = apply_filters('geodir_message_listing_not_found', $message, 'listing-listview', false);
       
       $is_display = ((strpos($related_listing, $message) !== false || $related_listing == '' || !geodir_is_page('detail'))) ? false : true;
    }

    return $is_display;
}


add_action('wp', 'geodir_changes_in_custom_fields_table');
add_action('wp_admin', 'geodir_changes_in_custom_fields_table');

/**
 * Geodirectory updated custom field table(add field and change show in sidebar value in db).
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_changes_in_custom_fields_table() {
    global $wpdb, $plugin_prefix;
	
	// Remove unused virtual page
	$listings_page_id = (int)get_option('geodir_listing_page');
	if ($listings_page_id) {
		$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->posts . " WHERE ID=%d AND post_name = %s AND post_type=%s", array($listings_page_id, 'listings', 'page')));
        delete_option('geodir_listing_page');
	}

    if (!get_option('geodir_changes_in_custom_fields_table')) {
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . GEODIR_CUSTOM_FIELDS_TABLE . " SET is_default=%s, is_admin=%s WHERE is_default=%s",
                array('1', '1', 'admin')
            )
        );


        /* --- terms meta value set --- */

        update_option('geodir_default_marker_icon', geodir_plugin_url() . '/geodirectory-functions/map-functions/icons/pin.png');

        $options_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "options WHERE option_name LIKE %s", array('%tax_meta_%')));

        if (!empty($options_data)) {

            foreach ($options_data as $optobj) {

                $option_val = str_replace('tax_meta_', '', $optobj->option_name);

                $taxonomies_data = $wpdb->get_results($wpdb->prepare("SELECT taxonomy FROM " . $wpdb->prefix . "term_taxonomy WHERE taxonomy LIKE %s AND term_id=%d", array('%category%', $option_val)));

                if (!empty($taxonomies_data)) {

                    foreach ($taxonomies_data as $taxobj) {

                        $taxObject = get_taxonomy($taxobj->taxonomy);
                        $post_type = $taxObject->object_type[0];

                        $opt_value = 'tax_meta_' . $post_type . '_' . $option_val;

                        $duplicate_data = $wpdb->get_var($wpdb->prepare("SELECT option_id FROM " . $wpdb->prefix . "options WHERE option_name=%s", array('tax_meta_' . $option_val)));

                        if ($duplicate_data) {

                            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "options SET	option_name=%s WHERE option_id=%d", array($opt_value, $optobj->option_id)));

                        } else {

                            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "options (option_name,option_value,autoload) VALUES (%s, %s, %s)", array($opt_value, $optobj->option_value, $optobj->autoload)));

                        }

                    }

                }

            }
        }

        update_option('geodir_changes_in_custom_fields_table', '1');

    }

}


add_filter('geodir_location_slug_check', 'geodir_location_slug_check');
/**
 * Check location slug.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 * @param string $slug Term slug.
 * @return string Modified term slug.
 */
function geodir_location_slug_check($slug)
{

    global $wpdb, $table_prefix;

    $slug_exists = $wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $table_prefix . "terms WHERE slug=%s", array($slug)));

    if ($slug_exists) {

        $suffix = 1;
        do {
            $alt_location_name = _truncate_post_slug($slug, 200 - (strlen($suffix) + 1)) . "-$suffix";
            $location_slug_check = $wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $table_prefix . "terms WHERE slug=%s", array($alt_location_name)));
            $suffix++;
        } while ($location_slug_check && $suffix < 100);

        $slug = $alt_location_name;

    }

    return $slug;

}


add_action('edited_term', 'geodir_update_term_slug', '1', 3);
add_action('create_term', 'geodir_update_term_slug', '1', 3);


/**
 * Update term slug.
 *
 * @since 1.0.0
 * @since 1.5.3 Modified to update tag in detail table when tag updated.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $table_prefix WordPress Database Table prefix.
 * @param int|string $term_id The term ID.
 * @param int $tt_id term Taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function geodir_update_term_slug($term_id, $tt_id, $taxonomy)
{

    global $wpdb, $plugin_prefix, $table_prefix;

    $tern_data = get_term_by('id', $term_id, $taxonomy);

    $slug = $tern_data->slug;

    /**
     * Filter if a term slug exists.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param bool $bool Default: false.
     * @param string $slug The term slug.
     * @param int $term_id The term ID.
     */
    $slug_exists = apply_filters('geodir_term_slug_is_exists', false, $slug, $term_id);

    if ($slug_exists) {

        $suffix = 1;
        do {
            $new_slug = _truncate_post_slug($slug, 200 - (strlen($suffix) + 1)) . "-$suffix";

            /** This action is documented in geodirectory_hooks_actions.php */
            $term_slug_check = apply_filters('geodir_term_slug_is_exists', false, $new_slug, $term_id);

            $suffix++;
        } while ($term_slug_check && $suffix < 100);

        $slug = $new_slug;

        //wp_update_term( $term_id, $taxonomy, array('slug' => $slug) );

        $wpdb->query($wpdb->prepare("UPDATE " . $table_prefix . "terms SET slug=%s WHERE term_id=%d", array($slug, $term_id)));

    }
	
	// Update tag in detail table.
	$taxonomy_obj = get_taxonomy($taxonomy);
	$post_type = !empty($taxonomy_obj) ? $taxonomy_obj->object_type[0] : NULL;
	
	$post_types = geodir_get_posttypes();
	if ($post_type && in_array($post_type, $post_types) && $post_type . '_tags' == $taxonomy) {		
		$posts_obj = $wpdb->get_results($wpdb->prepare("SELECT object_id FROM " . $wpdb->term_relationships . " WHERE term_taxonomy_id = %d", array($tt_id)));
		
		if (!empty($posts_obj)) {
			foreach ($posts_obj as $post_obj) {
				$post_id = $post_obj->object_id;
				
				$raw_tags = wp_get_object_terms($post_id, $post_type . '_tags', array('fields' => 'names'));
				$post_tags = !empty($raw_tags) ? implode(',', $raw_tags) : '';
				
				$listing_table = $plugin_prefix . $post_type . '_detail';
				$wpdb->query($wpdb->prepare("UPDATE " . $listing_table . " SET post_tags=%s WHERE post_id =%d", array($post_tags, $post_id)));
			}
		}
	}
}


add_filter('geodir_term_slug_is_exists', 'geodir_term_slug_is_exists', 0, 3); //in core plugin
/**
 * Check whether a term slug exists or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 * @param bool $slug_exists Default: false.
 * @param string $slug Term slug.
 * @param int $term_id The term ID.
 * @return bool true when exists. false when not exists.
 */
function geodir_term_slug_is_exists($slug_exists, $slug, $term_id)
{

    global $wpdb, $table_prefix;

    $default_location = geodir_get_default_location();

    $country_slug = $default_location->country_slug;
    $region_slug = $default_location->region_slug;
    $city_slug = $default_location->city_slug;

    if ($country_slug == $slug || $region_slug == $slug || $city_slug == $slug)
        return $slug_exists = true;

    if ($wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $table_prefix . "terms WHERE slug=%s AND term_id != %d", array($slug, $term_id))))
        return $slug_exists = true;

    return $slug_exists;
}



add_filter('pre_get_document_title', 'geodir_custom_page_title', 100);
add_filter('wp_title', 'geodir_custom_page_title', 100, 2);
/**
 * Set custom page title.
 *
 * @since 1.0.0
 * @since 1.6.18 Option added to disable overwrite by Yoast SEO titles & metas on GD pages.
 * @package GeoDirectory
 * @global object $wp WordPress object.
 * @param string $title Old title.
 * @param string $sep Title separator.
 * @return string Modified title.
 */
function geodir_custom_page_title($title = '', $sep = '')
{
    global $wp;
    if ((class_exists('WPSEO_Frontend') || class_exists('All_in_One_SEO_Pack')) && !geodir_disable_yoast_seo_metas()) {
        return $title;
    }

    if ($sep == '') {
        /**
         * Filter the page title separator.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param string $sep The separator, default: `|`.
         */
        $sep = apply_filters('geodir_page_title_separator', '|');
    }


    $gd_page = '';
    if(geodir_is_page('home')){
        $gd_page = 'home';
        $title = (get_option('geodir_meta_title_homepage')) ? get_option('geodir_meta_title_homepage') : $title;
    }
    elseif(geodir_is_page('detail')){
        $gd_page = 'detail';
        $title = (get_option('geodir_meta_title_detail')) ? get_option('geodir_meta_title_detail') : $title;
    }
    elseif(geodir_is_page('pt')){
        $gd_page = 'pt';
        $title = (get_option('geodir_meta_title_pt')) ? get_option('geodir_meta_title_pt') : $title;
    }
    elseif(geodir_is_page('listing')){
        $gd_page = 'listing';
        $title = (get_option('geodir_meta_title_listing')) ? get_option('geodir_meta_title_listing') : $title;
    }
    elseif(geodir_is_page('location')){
        $gd_page = 'location';
        $title = (get_option('geodir_meta_title_location')) ? get_option('geodir_meta_title_location') : $title;
    }
    elseif(geodir_is_page('search')){
        $gd_page = 'search';
        $title = (get_option('geodir_meta_title_search')) ? get_option('geodir_meta_title_search') : $title;
    }
    elseif(geodir_is_page('add-listing')){
        $gd_page = 'add-listing';
        $title = (get_option('geodir_meta_title_add-listing')) ? get_option('geodir_meta_title_add-listing') : $title;
    }
    elseif(geodir_is_page('author')){
        $gd_page = 'author';
        $title = (get_option('geodir_meta_title_author')) ? get_option('geodir_meta_title_author') : $title;
    }
    elseif(geodir_is_page('login')){
        $gd_page = 'login';
        $title = (get_option('geodir_meta_title_login')) ? get_option('geodir_meta_title_login') : $title;
    }
    elseif(geodir_is_page('listing-success')){
        $gd_page = 'listing-success';
        $title = (get_option('geodir_meta_title_listing-success')) ? get_option('geodir_meta_title_listing-success') : $title;
    }


    /**
     * Filter page meta title to replace variables.
     *
     * @since 1.5.4
     * @param string $title The page title including variables.
     * @param string $gd_page The GeoDirectory page type if any.
     * @param string $sep The title separator symbol.
     */
    return apply_filters('geodir_seo_meta_title', __($title, 'geodirectory'), $gd_page, $sep);

}


//add_action('init', 'geodir_set_post_attachment'); // we need to make a tool somewhere to run this function maybe via ajax or something in batch form, it is crashing servers with lots of listings

/**
 * set attachments for all geodir posts.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_set_post_attachment()
{

    if (!get_option('geodir_set_post_attachments')) {

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        $all_postypes = geodir_get_posttypes();

        foreach($all_postypes as $post_type){
            $args = array(
                'posts_per_page' => -1,
                'post_type' => $post_type,
                'post_status' => 'publish');

            $posts_array = get_posts($args);

            if (!empty($posts_array)) {

                foreach ($posts_array as $post) {

                    geodir_set_wp_featured_image($post->ID);

                }

            }
        }


        update_option('geodir_set_post_attachments', '1');

    }

}


/*   --------- geodir remove url seperator ------- */

add_action('init', 'geodir_remove_url_seperator');
/**
 * Remove url separator.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_remove_url_seperator()
{

    if (!get_option('geodir_remove_url_seperator')) {

        if (get_option('geodir_listingurl_separator'))
            delete_option('geodir_listingurl_separator');

        if (get_option('geodir_detailurl_separator'))
            delete_option('geodir_detailurl_separator');

        flush_rewrite_rules(false);

        update_option('geodir_remove_url_seperator', '1');

    }

}

add_filter('geodir_permalink_settings', 'geodir_remove_url_seperator_form_permalink_settings', 0, 1);

/**
 * Remove url separator from permalink settings.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $permalink_arr The permalink settings array ( Geodirectory -> Permalinks ).
 * @return array Modified permalink array.
 */
function geodir_remove_url_seperator_form_permalink_settings($permalink_arr)
{
    foreach ($permalink_arr as $key => $value) {

        if ($value['id'] == 'geodir_listingurl_separator' || $value['id'] == 'geodir_detailurl_separator')
            unset($permalink_arr[$key]);

    }

    return $permalink_arr;

}

if (!is_admin()) {
    add_filter('posts_results', 'geodir_set_status_draft_to_publish_for_own_post');
}
/**
 * Set status from draft to publish.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 * @param object $post Post object.
 * @return object Modified post object.
 */
function geodir_set_status_draft_to_publish_for_own_post($post)
{
    $user_id = get_current_user_id();

    if(!$user_id){return $post;}

    $gd_post_types = geodir_get_posttypes();

    if (!empty($post) && $post[0]->post_author == $user_id && in_array($post[0]->post_type, $gd_post_types) && !isset($_REQUEST['fl_builder'])) {
        $post[0]->post_status = 'publish';
    }
    return $post;
}


add_filter('geodir_detail_page_tab_list_extend', 'geodir_detail_page_tab_headings_change');
/**
 * Detail Page Tab Headings Change.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $tabs_arr {
 *    Attributes of the Tabs array.
 *
 *    @type array $post_profile {
 *        Attributes of post_profile.
 *
 *        @type string $heading_text    Tab Heading. Default "Profile".
 *        @type bool   $is_active_tab   Is this tab active? Default true.
 *        @type bool   $is_display      Display this tab? Default true.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $post_info {
 *        Attributes of post_info.
 *
 *        @type string $heading_text    Tab Heading. Default "More Info".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default false.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $post_images {
 *        Attributes of post_images.
 *
 *        @type string $heading_text    Tab Heading. Default "Photo".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default true.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $post_video {
 *        Attributes of post_video.
 *
 *        @type string $heading_text    Tab Heading. Default "Video".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default false.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $special_offers {
 *        Attributes of special_offers.
 *
 *        @type string $heading_text    Tab Heading. Default "Special Offers".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default false.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $post_map {
 *        Attributes of post_map.
 *
 *        @type string $heading_text    Tab Heading. Default "Map".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default true.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *    @type array $reviews {
 *        Attributes of reviews.
 *
 *        @type string $heading_text    Tab Heading. Default "Reviews".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default true.
 *        @type string $tab_content     Tab content. Default "review display".
 *
 *    }
 *    @type array $related_listing {
 *        Attributes of related_listing.
 *
 *        @type string $heading_text    Tab Heading. Default "Related Listing".
 *        @type bool   $is_active_tab   Is this tab active? Default false.
 *        @type bool   $is_display      Display this tab? Default true.
 *        @type string $tab_content     Tab content. Default "".
 *
 *    }
 *
 * }
 * @return array Modified tabs array.
 */
function geodir_detail_page_tab_headings_change($tabs_arr)
{
    global $wpdb;

    $post_type = geodir_get_current_posttype();

    $all_postypes = geodir_get_posttypes();

    if (!empty($tabs_arr) && $post_type != '' && in_array($post_type, $all_postypes)) {

        if (array_key_exists('post_video', $tabs_arr)) {

            $field_title = $wpdb->get_var($wpdb->prepare("select site_title from " . GEODIR_CUSTOM_FIELDS_TABLE . " where htmlvar_name = %s and post_type = %s ", array('geodir_video', $post_type)));

            if (isset($tabs_arr['post_video']['heading_text']) && $field_title != '')
                $tabs_arr['post_video']['heading_text'] = $field_title;
        }

        if (array_key_exists('special_offers', $tabs_arr)) {

            $field_title = $wpdb->get_var($wpdb->prepare("select site_title from " . GEODIR_CUSTOM_FIELDS_TABLE . " where htmlvar_name = %s and post_type = %s ", array('geodir_special_offers', $post_type)));

            if (isset($tabs_arr['special_offers']['heading_text']) && $field_title != '')
                $tabs_arr['special_offers']['heading_text'] = $field_title;
        }

    }

    return $tabs_arr;

}

add_action('init', 'geodir_remove_template_redirect_actions', 100);
/**
 * Remove template redirect options.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_remove_template_redirect_actions()
{
    if (geodir_is_page('login')){
        remove_all_actions('template_redirect');
        remove_action('init', 'avia_modify_front', 10);
    }
}



/* ---------- temp function to delete media post */

add_action('delete_attachment', 'geodirectory_before_featured_image_delete');

/**
 * temp function to delete media post.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $attachment_id Attachment ID.
 * @return bool|void Returns false on failure.
 */
function geodirectory_before_featured_image_delete($attachment_id)
{

    global $wpdb, $plugin_prefix;

    $post_id = get_post_field('post_parent', $attachment_id);

    $attachment_url = wp_get_attachment_url($attachment_id);

    if ($post_id > 0 && (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')) {

        $post_type = get_post_type($post_id);

        $all_postypes = geodir_get_posttypes();

        if (!in_array($post_type, $all_postypes) || !is_admin())
            return false;

        $uploads = wp_upload_dir();

        $split_img_path = explode($uploads['baseurl'], $attachment_url);

        $split_img_file_path = isset($split_img_path[1]) ? $split_img_path[1] : '';

        $wpdb->query(
            $wpdb->prepare("DELETE FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE post_id = %d AND file=%s ",
                array($post_id, $split_img_file_path)
            )
        );

        $attachment_data = $wpdb->get_row(
            $wpdb->prepare("SELECT ID, MIN(`menu_order`) FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE post_id=%d",
                array($post_id)
            )
        );

        if (!empty($attachment_data)) {
            $wpdb->query("UPDATE " . GEODIR_ATTACHMENT_TABLE . " SET menu_order=1 WHERE ID=" . $attachment_data->ID);
        }


        $table_name = $plugin_prefix . $post_type . '_detail';

        $wpdb->query("UPDATE " . $table_name . " SET featured_image='' WHERE post_id =" . $post_id);

        geodir_set_wp_featured_image($post_id);

    }

}


//add_action('wp', 'geodir_temp_set_post_attachment'); //WTF 

/**
 * temp function to set post attachment.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_temp_set_post_attachment()
{

    global $wpdb, $plugin_prefix;

    $all_postypes = geodir_get_posttypes();

    foreach ($all_postypes as $posttype) {

        $tablename = $plugin_prefix . $posttype . '_detail';

        $get_post_data = $wpdb->get_results("SELECT post_id FROM " . $tablename);

        if (!empty($get_post_data)) {

            foreach ($get_post_data as $data) {

                $post_id = $data->post_id;

                $attachment_data = $wpdb->get_results("SELECT * FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE post_id =" . $post_id . " AND file!=''");

                if (!empty($attachment_data)) {

                    foreach ($attachment_data as $attach) {

                        $file_info = pathinfo($attach->file);

                        $sub_dir = '';
                        if ($file_info['dirname'] != '.' && $file_info['dirname'] != '..')
                            $sub_dir = stripslashes_deep($file_info['dirname']);

                        $uploads = wp_upload_dir(trim($sub_dir, '/')); // Array of key => value pairs
                        $uploads_path = $uploads['basedir'];

                        $file_name = $file_info['basename'];

                        $img_arr['path'] = $uploads_path . $sub_dir . '/' . $file_name;

                        if (!file_exists($img_arr['path'])) {

                            $wpdb->query("DELETE FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE ID=" . $attach->ID);

                        }

                    }

                    $attachment_data = $wpdb->get_row("SELECT ID, MIN(`menu_order`) FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE post_id=" . $post_id . " GROUP BY post_id");

                    if (!empty($attachment_data)) {

                        if ($attachment_data->ID)
                            $wpdb->query("UPDATE " . GEODIR_ATTACHMENT_TABLE . " SET menu_order=1 WHERE ID=" . $attachment_data->ID);

                    } else {

                        if (has_post_thumbnail($post_id)) {

                            $post_thumbnail_id = get_post_thumbnail_id($post_id);

                            wp_delete_attachment($post_thumbnail_id);

                        }

                    }

                    $wpdb->query("UPDATE " . $tablename . " SET featured_image='' WHERE post_id =" . $post_id);

                    geodir_set_wp_featured_image($post_id);

                }

            }

        }

    }

}


/* -------- GEODIR FUNCTION TO UPDATE geodir_default_rating_star_icon ------ */

add_action('init', 'geodir_default_rating_star_icon');

/**
 * Update default rating star icon.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_default_rating_star_icon()
{

    if (!get_option('geodir_default_rating_star_icon')) {
        update_option('geodir_default_rating_star_icon', geodir_plugin_url() . '/geodirectory-assets/images/stars.png');
    }

}


/* ------- GET CURRENT USER POST LISTING -------*/
/**
 * Get user's post listing count.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @return array User listing count for each post type.
 */
function geodir_user_post_listing_count($user_id = 0)
{
    global $wpdb, $plugin_prefix, $current_user;
    if(!$user_id){
        $user_id = $current_user->ID;
    }

    $all_posts = get_option('geodir_listing_link_user_dashboard');

    $user_listing = array();
    if ($user_id && is_array($all_posts) && !empty($all_posts)) {
        foreach ($all_posts as $ptype) {
            $total_posts = $wpdb->get_var("SELECT count( ID ) FROM " . $wpdb->prefix . "posts WHERE post_author=" . $user_id . " AND post_type='" . $ptype . "' AND ( post_status = 'publish' OR post_status = 'draft' OR post_status = 'private' OR post_status = 'pending' )");

            if ($total_posts > 0) {
                $user_listing[$ptype] = $total_posts;
            }
        }
    }

    return $user_listing;
}




add_filter('geodir_detail_page_tab_list_extend', 'geodir_detail_page_custom_field_tab');
/**
 * Details page tab custom fields.
 *
 * @since 1.0.0
 * @since 1.5.7 Custom fields option values added to db translation.
 *              Changes to display url fields title.
 * @package GeoDirectory
 * @global object $post The current post object.
 * @param array $tabs_arr Tabs array {@see geodir_detail_page_tab_headings_change()}.
 * @return array Modified tabs array.
 */
function geodir_detail_page_custom_field_tab($tabs_arr)
{
    global $post;

    $post_type = geodir_get_current_posttype();
    $all_postypes = geodir_get_posttypes();

    if (!empty($tabs_arr) && $post_type != '' && in_array($post_type, $all_postypes) && (geodir_is_page('detail') || geodir_is_page('preview'))) {
        $package_info = array();
        $package_info = geodir_post_package_info($package_info, $post);
        $post_package_id = !empty($package_info->pid) ? $package_info->pid : '';
        $fields_location = 'owntab';

        $custom_fields = geodir_post_custom_fields($post_package_id, 'all', $post_type, $fields_location);
        //remove video and special offers if it is already set to show
        if(isset($tabs_arr['post_video']['is_display']) && $tabs_arr['post_video']['is_display']){
            $unset_video = true;
        }

        if(isset($tabs_arr['special_offers']['is_display']) && $tabs_arr['special_offers']['is_display']){
            $unset_special_offers = true;
        }
        if(isset($unset_video) || isset($unset_special_offers) && !empty($custom_fields)){
            foreach($custom_fields as $key => $custom_field){
                if($custom_field['name']=='geodir_video' && isset($unset_video)){
                    unset($custom_fields[$key]);
                }
                if($custom_field['name']=='geodir_special_offers' && isset($unset_special_offers)){
                    unset($custom_fields[$key]);
                }
            }
        }


        if (!empty($custom_fields)) {
            $parse_custom_fields = array();
            foreach ($custom_fields as $field) {
                $field = stripslashes_deep($field); // strip slashes
                $type = $field;
                $field_name = $field['htmlvar_name'];
                if (empty($geodir_post_info) && geodir_is_page('preview') && $field_name != '' && !isset($post->{$field_name}) && isset($_REQUEST[$field_name])) {
                    $post->{$field_name} = $_REQUEST[$field_name];
                }

                if (isset($field['show_in']) && strpos($field['show_in'], '[owntab]') !== false  && ((isset($post->{$field_name}) && $post->{$field_name} != '') || $field['type'] == 'fieldset' || $field['type'] == 'address') && in_array($field['type'], array('text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file','address','taxonomy'))) {
                    if ($type['type'] == 'datepicker' && ($post->{$type['htmlvar_name']} == '' || $post->{$type['htmlvar_name']} == '0000-00-00')) {
                        continue;
                    }

                    $parse_custom_fields[] = $field;
                }
            }
            $custom_fields = $parse_custom_fields;
        }
        //print_r($custom_fields);
        if (!empty($custom_fields)) {

            global $field_set_start;

            $post = stripslashes_deep($post); // strip slashes
            
            $field_set_start = 0;
            $fieldset_count = 0;
            $fieldset = '';
            $total_fields = count($custom_fields);
            $count_field = 0;
            $fieldset_arr = array();
            $i = 0;
            $geodir_post_info = isset($post->ID) && !empty($post->ID) ? geodir_get_post_info($post->ID) : NULL;

            foreach ($custom_fields as $field) {
                $count_field++;
                $field_name = $field['htmlvar_name'];
                if (empty($geodir_post_info) && geodir_is_page('preview') && $field_name != '' && !isset($post->{$field_name}) && isset($_REQUEST[$field_name])) {
                    $post->{$field_name} = $_REQUEST[$field_name];
                }

                if (isset($field['show_in']) && strpos($field['show_in'], '[owntab]') !== false && ((isset($post->{$field_name}) && $post->{$field_name} != '') || $field['type'] == 'fieldset' || $field['type'] == 'address') && in_array($field['type'], array('text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file','address','taxonomy'))) {
                    $label = $field['site_title'] != '' ? $field['site_title'] : $field['admin_title'];
                    $site_title = trim($field['site_title']);
                    $type = $field;
                    $variables_array = array();

                    if ($type['type'] == 'datepicker' && ($post->{$type['htmlvar_name']} == '' || $post->{$type['htmlvar_name']} == '0000-00-00')) {
                        continue;
                    }

                    if ($type['type'] != 'fieldset') {
                        $i++;
                        $variables_array['post_id'] = $post->ID;
                        $variables_array['label'] = __($type['site_title'], 'geodirectory');
                        $variables_array['value'] = isset($post->{$type['htmlvar_name']}) ? $post->{$type['htmlvar_name']} : '';

                    }else{
                        $i = 0;
                        $fieldset_count++;
                        $field_set_start = 1;
                        $fieldset_arr[$fieldset_count]['htmlvar_name'] = 'gd_tab_' . $fieldset_count;
                        $fieldset_arr[$fieldset_count]['label'] = $label;
                    }


                    if(isset($type['extra_fields'])){$extra_fields= $type['extra_fields'];}
                    $type = stripslashes_deep($type); // strip slashes
                    if(isset($type['extra_fields'])){$type['extra_fields'] = $extra_fields;}
                    $html = '';
                    $html_var = isset($type['htmlvar_name']) ? $type['htmlvar_name'] : '';
                    if($html_var=='post'){$html_var='post_address';}
                    $field_icon = geodir_field_icon_proccess($type);
                    $filed_type = $type['type'];

                    /**
                     * Filter the output for custom fields.
                     *
                     * Here we can remove or add new functions depending on the field type.
                     *
                     * @param string $html The html to be filtered (blank).
                     * @param string $fields_location The location the field is to be show.
                     * @param array $type The array of field values.
                     */
                    $html = apply_filters("geodir_custom_field_output_{$filed_type}",$html,$fields_location,$type);


                    /**
                     * Filter custom field output in tab.
                     *
                     * @since 1.5.6
                     *
                     * @param string $html_var The HTML variable name for the field.
                     * @param string $html Custom field unfiltered HTML.
                     * @param array $variables_array Custom field variables array.
                     */
                    $html = apply_filters("geodir_tab_show_{$html_var}", $html, $variables_array);

                    $fieldset_html = '';
                    if ($field_set_start == 1) {
                        $add_html = false;
                        if ($type['type'] == 'fieldset' && $fieldset_count > 1) {
                            if ($fieldset != '') {
                                $add_html = true;
                                $label = $fieldset_arr[$fieldset_count - 1]['label'];
                                $htmlvar_name = $fieldset_arr[$fieldset_count - 1]['htmlvar_name'];
                            }
                            $fieldset_html = $fieldset;
                            $fieldset = '';
                        } else {
                            $fieldset .= $html;
                            if ($total_fields == $count_field && $fieldset != '') {
                                $add_html = true;
                                $label = $fieldset_arr[$fieldset_count]['label'];
                                $htmlvar_name = $fieldset_arr[$fieldset_count]['htmlvar_name'];
                                $fieldset_html = $fieldset;
                            }
                        }

                        if ($add_html) {
                            $tabs_arr[$htmlvar_name] = array(
                                'heading_text' => __($label, 'geodirectory'),
                                'is_active_tab' => false,
                                /**
                                 * Filter if a custom field should be displayed on the details page tab.
                                 *
                                 * @since 1.0.0
                                 * @param string $htmlvar_name The field HTML var name.
                                 */
                                'is_display' => apply_filters('geodir_detail_page_tab_is_display', true, $htmlvar_name),
                                'tab_content' => '<div class="geodir-company_info field-group xxx">' . $fieldset_html . '</div>'
                            );
                        }
                    } else {
                        if ($html != '') {
                            $tabs_arr[$html_var] = array(
                                'heading_text' => __($label, 'geodirectory'),
                                'is_active_tab' => false,
                                /** This action is documented in geodirectory_hooks_actions.php */
                                'is_display' => apply_filters('geodir_detail_page_tab_is_display', true, $field['htmlvar_name']),
                                'tab_content' => $html
                            );
                        }
                    }
                }
            }
        }
    }
    return $tabs_arr;
}

/* display add listing page for wpml */
add_filter('option_geodir_add_listing_page', 'get_page_id_geodir_add_listing_page', 10, 2);

/*
 * hook action for post updated
 */
add_action('post_updated', 'geodir_function_post_updated', 16, 3);


add_action('geodir_after_edit_post_link_on_listing', 'geodir_add_post_status_author_page', 11);

/**
 * Adds post status on author page when the author is current user.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $post The current post object.
 */
function geodir_add_post_status_author_page()
{
    global $wpdb, $post;

    $html = '';
    if (get_current_user_id()) {

        $is_author_page = apply_filters('geodir_post_status_is_author_page', geodir_is_page('author'));
        if ($is_author_page && !empty($post) && isset($post->post_author) && $post->post_author == get_current_user_id()) {

            // we need to query real status direct as we dynamically change the status for author on author page so even non author status can view them.
            $real_status = $wpdb->get_var("SELECT post_status from $wpdb->posts WHERE ID=$post->ID");
            $status = "<strong>(";
            $status_icon = '<i class="fa fa-play"></i>';
            if ($real_status == 'publish') {
                $status .= __('Published', 'geodirectory');
            }elseif ($real_status == 'pending') {
                $status .= __('Awaiting Review', 'geodirectory');
            } else {
                $status .= __('Not published', 'geodirectory');
                $status_icon = '<i class="fa fa-pause"></i>';
            }
            $status .= ")</strong>";

            $html = '<span class="geodir-post-status">' . $status_icon . ' <font class="geodir-status-label">' . __('Status: ', 'geodirectory') . '</font>' . $status . '</span>';
        }
    }

    if ($html != '') {
        /**
         * Filter the post status text on the author page.
         *
         * @since 1.0.0
         * @param string $html The HTML of the status.
         */
        echo apply_filters('geodir_filter_status_text_on_author_page', $html);
    }


}

add_action('init', 'geodir_init_no_rating', 100);
/**
 * remove rating stars fields if disabled.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 */
function geodir_init_no_rating() {
    if (geodir_rating_disabled_post_types()) {
        add_filter('geodir_get_sort_options', 'geodir_no_rating_get_sort_options', 100, 2);
    }
}

/**
 * Skip overall rating sort option when rating disabled.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 * @param array $options Sort options array.
 * @param string $post_type The post type.
 * @return array Modified sort options array.
 */
function geodir_no_rating_get_sort_options($options, $post_type = '') {
    if (!empty($post_type) && geodir_cpt_has_rating_disabled($post_type)) {
        $new_options = array();
        
        if (!empty($options)) {
            foreach ($options as $option) {
                if (is_object($option) && isset($option->htmlvar_name) && $option->htmlvar_name == 'overall_rating') {
                    continue;
                }
                $new_options[] = $option;
            }

            $options = $new_options;
        }
    }

    return $options;
}

/**
 * Add body class for current active map.
 *
 * @since 1.6.16
 * @package GeoDirectory
 * @param array $classes The class array of the HTML element.
 * @return array Modified class array.
 */
function geodir_body_class_active_map($classes = array()) {
    $classes[] = 'gd-map-' . geodir_map_name();

    return $classes;
}
add_filter('body_class', 'geodir_body_class_active_map', 100);

/**
 * Add body class for current active map.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $class The class of the HTML element.
 * @return string Modified class string.
 */
function geodir_admin_body_class_active_map($class = '') {    
    $class .= ' gd-map-' . geodir_map_name();

    return $class;
}
add_filter('admin_body_class', 'geodir_admin_body_class_active_map', 100);

add_filter('geodir_load_db_language', 'geodir_load_custom_field_translation');

add_filter('geodir_load_db_language', 'geodir_load_cpt_text_translation');

/**
 * Get the geodirectory notification subject & content texts for translation.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param  array $translation_texts Array of text strings.
 * @return array Translation texts.
 */
function geodir_load_gd_options_text_translation($translation_texts = array()) {
    $translation_texts = !empty( $translation_texts ) && is_array( $translation_texts ) ? $translation_texts : array();

    $gd_options = array('geodir_post_submited_success_email_subject_admin', 'geodir_post_submited_success_email_content_admin', 'geodir_post_submited_success_email_subject', 'geodir_post_submited_success_email_content', 'geodir_forgot_password_subject', 'geodir_forgot_password_content', 'geodir_registration_success_email_subject', 'geodir_registration_success_email_content', 'geodir_post_published_email_subject', 'geodir_post_published_email_content', 'geodir_email_friend_subject', 'geodir_email_friend_content', 'geodir_email_enquiry_subject', 'geodir_email_enquiry_content', 'geodir_post_added_success_msg_content', 'geodir_post_edited_email_subject_admin', 'geodir_post_edited_email_content_admin');

    /**
     * Filters the geodirectory option names that requires to add for translation.
     *
     * @since 1.5.7
     * @package GeoDirectory
     *
     * @param  array $gd_options Array of option names.
     */
    $gd_options = apply_filters('geodir_gd_options_for_translation', $gd_options);
    $gd_options = array_unique($gd_options);

    if (!empty($gd_options)) {
        foreach ($gd_options as $gd_option) {
            if ($gd_option != '' && $option_value = get_option($gd_option)) {
                $option_value = is_string($option_value) ? stripslashes_deep($option_value) : '';
                
                if ($option_value != '' && !in_array($option_value, $translation_texts)) {
                    $translation_texts[] = stripslashes_deep($option_value);
                }
            }
        }
    }

    $translation_texts = !empty($translation_texts) ? array_unique($translation_texts) : $translation_texts;

    return $translation_texts;
}

add_filter('geodir_load_db_language', 'geodir_load_gd_options_text_translation');
add_filter('geodir_action_get_request_info', 'geodir_attach_parent_categories', 0, 1);
add_filter('geodir_show_listing_post_excerpt', 'geodir_show_listing_post_excerpt', 10, 3);
add_filter('gd_rating_form_html', 'geodir_font_awesome_rating_form_html', 10, 2);
add_filter('geodir_get_rating_stars_html', 'geodir_font_awesome_rating_stars_html', 10, 3);
add_action('wp_head', 'geodir_font_awesome_rating_css');
add_action('admin_head', 'geodir_font_awesome_rating_css');
add_action('wp_insert_post', 'geodir_on_wp_insert_post', 10, 3);

add_filter('get_comments_link', 'gd_get_comments_link', 10, 2);
function gd_get_comments_link($comments_link, $post_id) {
    $post_type = get_post_type($post_id);

    $all_postypes = geodir_get_posttypes();
    if (in_array($post_type, $all_postypes)) {
        $comments_link = str_replace('#comments', '#reviews', $comments_link);
        $comments_link = str_replace('#respond', '#reviews', $comments_link);
    }

    return $comments_link;
}


/**
 * Add a class to theme menus so we can adjust the z-index.
 *
 * We add a class and adjust the z-index so menus don't hide behind maps and menus on second lines
 * don't overlap submenus of first line menus.
 *
 * @package GeoDirectory
 * @since 1.6.3
 * @param array $args The array of menu arguments.
 * @return array The modified arguments.
 */
function geodir_add_nav_menu_class( $args )
{

        if(isset($args['menu_class'])){
            $args['menu_class'] = $args['menu_class']." gd-menu-z";
        }
    
    return $args;
}

add_filter( 'wp_nav_menu_args', 'geodir_add_nav_menu_class' );

/**
 * Filters WordPress locale ID.
 *
 * Load current WPML language when editing the GD CPT.
 *
 * @since 1.6.16
 * @package GeoDirectory
 *
 * @param string $locale The locale ID.
 * @return string Filtered locale ID.
 */
function geodir_wpml_filter_locale($locale) {
    global $sitepress;
    
    $post_type = !empty($_REQUEST['post_type']) ? $_REQUEST['post_type'] : (!empty($_REQUEST['post']) ? get_post_type($_REQUEST['post']) : '');
    
    if (!empty($sitepress) && $sitepress->is_post_edit_screen() && $post_type && in_array($post_type, geodir_get_posttypes()) && $current_lang = $sitepress->get_current_language()) {
        $locale = $sitepress->get_locale($current_lang);
    }
    
    return $locale;
}

/**
 * Set WordPress locale filter.
 *
 * @since 1.6.16
 * @package GeoDirectory
 */
function geodir_wpml_set_filter() {
    if (geodir_is_wpml()) {
        global $sitepress;
        
        if ($sitepress->get_setting('sync_comments_on_duplicates')) {
            add_action('comment_post', 'gepdir_wpml_sync_comment', 100, 1);
        }
        
        add_action('geodir_after_save_listing', 'geodir_wpml_duplicate_listing', 100, 2);
        add_action( 'geodir_edit_post_link_html', 'geodir_wpml_frontend_duplicate_listing', 0, 1 );
        if (is_admin()) {
            add_filter( 'geodir_design_settings', 'geodir_wpml_duplicate_settings', 10, 1 );
        }
    }
}
add_filter('plugins_loaded', 'geodir_wpml_set_filter');

/**
 * Filters the WPML language switcher urls for GeoDirectory pages.
 *
 * @since 1.6.16
 *
 * @param array    $languages WPML active languages.
 * @return array Filtered languages.
 */
function geodir_wpml_filter_ls_languages($languages) {    
    if (geodir_is_geodir_page()) {        
        $keep_vars = array();
        
        if (geodir_is_page('add-listing')) {
            $keep_vars = array('listing_type', 'package_id');
        } else if (geodir_is_page('search')) {
            $keep_vars = array('geodir_search', 'stype', 'snear', 'set_location_type', 'set_location_val', 'sgeo_lat', 'sgeo_lon');
        } else if (geodir_is_page('author')) {
            $keep_vars = array('geodir_dashbord', 'stype', 'list');
        } else if (geodir_is_page('login')) {
            $keep_vars = array('forgot', 'signup');
        }        
        
        if (!empty($keep_vars)) {
            foreach ( $languages as $code => $url) {
                $filter_url = $url['url'];
                
                foreach ($keep_vars as $var) {
                    if (isset($_GET[$var]) && !is_array($_GET[$var])) {
                        $filter_url = remove_query_arg(array($var), $filter_url);
                        $filter_url = add_query_arg(array($var => $_GET[$var]), $filter_url);
                    }
                }
                
                if ($filter_url != $url['url']) {
                    $languages[$code]['url'] = $filter_url;
                }
            }
        }
    }

    return $languages;
}
add_filter( 'icl_ls_languages', 'geodir_wpml_filter_ls_languages', 11, 1 );

/**
 * Remove Yoast SEO hook if disabled on GD pages.
 *
 * @since 1.6.18
 *
 */
function geodir_remove_yoast_seo_metas(){
    if ( class_exists( 'WPSEO_Frontend' ) && geodir_is_geodir_page() && geodir_disable_yoast_seo_metas() ) {
        $wpseo = WPSEO_Frontend::get_instance();
        
        remove_action( 'wp_head', array( $wpseo, 'metadesc' ), 6 );
        remove_action( 'wp_head', array( $wpseo, 'metakeywords' ), 11 );
        remove_filter( 'pre_get_document_title', array( $wpseo, 'title' ), 15 );
        remove_filter( 'wp_title', array( $wpseo, 'title' ), 15, 3 );
        remove_filter( 'thematic_doctitle', array( $wpseo, 'title' ), 15 );
        remove_filter( 'woo_title', array( $wpseo, 'fix_woo_title' ), 99 );
        
        remove_action( 'template_redirect', 'wpseo_frontend_head_init', 999 );
    }
}

/**
 * Set the WPML language for AJAX requests for non logged user.
 *
 * Custom AJAX requests always return the default language content.
 *
 * @since 1.6.18
 *
 * @global object $sitepress Sitepress WPML object.
 *
 */
 function geodir_wpml_ajax_set_guest_lang() {    
    if ( geodir_is_wpml() && wpml_is_ajax() && !is_user_logged_in() ) {
        if ( empty( $_GET['lang'] ) && !( !empty( $_SERVER['REQUEST_URI'] ) && preg_match( '@\.(css|js|png|jpg|gif|jpeg|bmp)@i', basename( preg_replace( '@\?.*$@', '', $_SERVER['REQUEST_URI'] ) ) ) ) ) {
            global $sitepress;
            
            $referer = wp_get_referer();
            
            $current_lang = $sitepress->get_current_language();
            $referrer_lang = $sitepress->get_language_from_url( $referer );
            
            if ( $referrer_lang && $current_lang != $referrer_lang ) {
                $_GET['lang'] = $referrer_lang;
            }
        }
    }
}
add_action( 'plugins_loaded', 'geodir_wpml_ajax_set_guest_lang', -1 );

/**
 * Change country slug czech-republic to czechia and redirect.
 *
 * @since 1.6.18
 *
 * @param object $wp The WordPress object.
 */
function geodir_check_redirect($wp) {
    if (is_404() || (!empty($wp->query_vars['error']) && $wp->query_vars['error'] == '404')) {
        $current_url = geodir_curPageURL();
        $search = 'czech-republic';
        $replace = 'czechia';        
        
        $has_slash = substr($current_url, -1);
        if ($has_slash != "/") {
            $current_url .= '/';
        }
        
        $redirect = false;
        if (strpos($current_url, '/' . $search . '/') !== false) {
            $redirect = true;
            $current_url = preg_replace('/\/' . $search . '\//', '/' . $replace . '/', $current_url, 1);
        }
        
        if ($has_slash != "/") {
            $current_url = trim($current_url, '/');
        }
        
        if (strpos($current_url, 'gd_country=' . $search) !== false) {
            $redirect = true;
            $current_url = str_replace('gd_country=' . $search, 'gd_country=' . $replace, $current_url);
        }

        if ($redirect) {
            wp_redirect($current_url);
            exit;
        }
    }
}
add_action('parse_request', 'geodir_check_redirect', 101, 1);

/**
 * Filters the unique post slug.
 *
 * @since 1.6.20
 *
 * @global object $wpdb WordPress Database object.
 * @global object $sitepress Sitepress WPML object.
 *
 * @param string $slug          The post slug.
 * @param int    $post_ID       Post ID.
 * @param string $post_status   The post status.
 * @param string $post_type     Post type.
 * @param int    $post_parent   Post parent ID
 * @param string $original_slug The original post slug.
 */
function geodir_check_post_to_term_slug( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
    global $wpdb, $sitepress;
    
    if ( $post_type && strpos( $post_type, 'gd_' ) === 0 ) {
        $wpml_post_join = "";
        $wpml_post_where = "";
        $wpml_term_join = "";
        $wpml_term_where = "";
        
        if (geodir_wpml_is_post_type_translated($post_type)) {
            $post_language = $post_ID ? $sitepress->post_translations()->get_element_lang_code($post_ID) : $sitepress->get_current_language();
            $post_language = $post_language ? $post_language : $sitepress->post_translations()->get_save_post_lang($post_ID, $sitepress);
            if (!$post_language) {
                $post_language = $sitepress->get_current_language();
            }
            
            $wpml_post_join = " JOIN {$wpdb->prefix}icl_translations AS icl_t ON p.ID = icl_t.element_id AND icl_t.element_type = CONCAT('post_', p.post_type)";
            $wpml_post_where = " AND icl_t.language_code = '" . $post_language ."'";
            
            $wpml_term_join = " JOIN {$wpdb->prefix}icl_translations AS icl_t ON icl_t.element_id = tt.term_taxonomy_id AND icl_t.element_type = CONCAT('tax_', tt.taxonomy)";
            $wpml_term_where = " AND icl_t.language_code = '" . $post_language ."'";
        }

        $term_slug_check = $wpdb->get_var( $wpdb->prepare( "SELECT t.slug FROM $wpdb->terms AS t LEFT JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id {$wpml_term_join} WHERE t.slug = '%s' AND ( tt.taxonomy = '" . $post_type . "category' OR tt.taxonomy = '" . $post_type . "_tags' ) {$wpml_term_where} LIMIT 1", $slug ) );

        if ( $term_slug_check ) {
            $suffix = 1;
            
            do {
                $alt_slug = _truncate_post_slug( $original_slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
                
                $term_check = $wpdb->get_var( $wpdb->prepare( "SELECT t.slug FROM $wpdb->terms AS t LEFT JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id {$wpml_term_join} WHERE t.slug = '%s' AND ( tt.taxonomy = '" . $post_type . "category' OR tt.taxonomy = '" . $post_type . "_tags' ) {$wpml_term_where} LIMIT 1", $alt_slug ) );
                
                $post_check = !$term_check && $wpdb->get_var( $wpdb->prepare( "SELECT p.post_name FROM $wpdb->posts p {$wpml_post_join} WHERE p.post_name = %s AND p.post_type = %s AND p.ID != %d {$wpml_term_where} LIMIT 1", $alt_slug, $post_type, $post_ID ) );
                
                $term_slug_check = $term_check || $post_check;
                
                $suffix++;
            } while ( $term_slug_check );
            
            $slug = $alt_slug;
        }
    }
    
    return $slug;
}
add_filter( 'wp_unique_post_slug', 'geodir_check_post_to_term_slug', 101, 6 );

/**
 * Check whether a post name with slug exists or not.
 *
 * @since 1.6.20
 *
 * @global object $wpdb WordPress Database object.
 * @global array $gd_term_post_type Cached array for term post type.
 * @global array $gd_term_taxonomy Cached array for term taxonomy.
 * @global object $sitepress Sitepress WPML object.
 *
 * @param bool $slug_exists Default: false.
 * @param string $slug Term slug.
 * @param int $term_id The term ID.
 * @return bool true when exists. false when not exists.
 */
function geodir_check_term_to_post_slug( $slug_exists, $slug, $term_id ) {
    global $wpdb, $gd_term_post_type, $gd_term_taxonomy, $sitepress;
    
    if ( $slug_exists ) {
        return $slug_exists;
    }
    
    if ( !empty( $gd_term_taxonomy ) && isset($gd_term_taxonomy[$term_id]) ) {
        $taxonomy = $gd_term_taxonomy[$term_id];
    } else {
        $taxonomy = $wpdb->get_var( $wpdb->prepare( "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %d LIMIT 1", $term_id ) );
        $gd_term_taxonomy[$term_id] = $taxonomy;
    }
    
    if ( empty($taxonomy) ) {
        return $slug_exists;
    }
    
    if ( !empty( $gd_term_post_type ) && $gd_term_post_type[$term_id] ) {
        $post_type = $gd_term_post_type[$term_id];
    } else {
        $taxonomy_obj = get_taxonomy( $taxonomy );
        $post_type = !empty( $taxonomy_obj->object_type ) ? $taxonomy_obj->object_type[0] : NULL;
    }
    
    $wpml_post_join = "";
    $wpml_post_where = "";
    
    if (geodir_wpml_is_taxonomy_translated($taxonomy) || geodir_wpml_is_post_type_translated($post_type)) {
        $term_language = $term_id ? geodir_get_language_for_element($term_id, 'tax_' . $taxonomy) : $sitepress->get_current_language();
        if (!$term_language) {
            $term_language = $sitepress->get_current_language();
        }
        
        $wpml_post_join = " JOIN {$wpdb->prefix}icl_translations AS icl_t ON p.ID = icl_t.element_id AND icl_t.element_type = CONCAT('post_', p.post_type)";
        $wpml_post_where = " AND icl_t.language_code = '" . $term_language ."'";
    }
    
    if ( $post_type && $wpdb->get_var( $wpdb->prepare( "SELECT p.post_name FROM $wpdb->posts p {$wpml_post_join} WHERE p.post_name = %s AND p.post_type = %s  {$wpml_post_where} LIMIT 1", $slug, $post_type ) ) ) {
        $slug_exists = true;
    }

    return $slug_exists;
}
add_filter( 'geodir_term_slug_is_exists', 'geodir_check_term_to_post_slug', 10, 3 );