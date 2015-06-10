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

add_action('init', 'geodir_session_start');

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
/* Widget Initalizaion */
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


/////////////////////////////
/* ON WP HEADE ACTIONS */
/////////////////////////////

add_action('wp_head', 'geodir_header_scripts');

add_action('admin_head', 'geodir_header_scripts');


add_action('wp_head', 'geodir_init_map_jason'); // Related to MAP

add_action('wp_head', 'geodir_init_map_canvas_array'); // Related to MAP

add_action('wp_head', 'geodir_restrict_widget'); // Related to widgets

//////////////////////////////
/* ENQUE SCRIPTS AND STYLES */
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
    add_action('pre_get_posts', 'geodir_exclude_page', 100); /// Will help to exclude virtural page from everywhere
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

add_action('geodir_update_postrating', 'geodir_term_review_count_force_update', 100);
add_action('transition_post_status', 'geodir_term_review_count_force_update', 100);
add_action('created_term', 'geodir_term_review_count_force_update', 100);
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
    include_once('geodirectory-functions/listing_filters.php');
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
 * Builds an array fo functions to be called in the details page (post) sidebar, this array can be changed via hook or filter.
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
                'geodir_share_this_button',
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
 * Outputs the share this button.
 *
 * Outputs the share this button html into a containing div if not on the add listing preview page.
 *
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_share_this_button()
{
    global $preview;
    ob_start(); // Start buffering;
    /**
     * This is called before the share this html in the function geodir_share_this_button()
     *
     * @since 1.0.0
     */
    do_action('geodir_before_share_this_button');
    if (!$preview) {
        ?>
        <div class="share clearfix">
            <?php geodir_share_this_button_code(); ?>
        </div>
    <?php
    }// end of if, if its a preview or not
    /**
     * This is called after the share this html in the function geodir_share_this_button()
     *
     * @since 1.0.0
     */
    do_action('geodir_after_share_this_button');
    $content_html = ob_get_clean();
    if (trim($content_html) != '')
        $content_html = '<div class="geodir-company_info geodir-details-sidebar-sharethis">' . $content_html . '</div>';
    if ((int)get_option('geodir_disable_sharethis_button_section') != 1) {
        /**
         * Filter the geodir_share_this_button() function content.
         *
         * @param string $content_html The output html of the geodir_share_this_button() function.
         * @since 1.0.0
         */
        echo $content_html = apply_filters('geodir_share_this_button_html', $content_html);
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
        //if(is_user_logged_in() && $post->post_author == get_current_user_id())

        $is_current_user_owner = geodir_listing_belong_to_current_user();
        if ($is_current_user_owner) {


            $post_id = $post->ID;
            if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {

                $post_id = $_REQUEST['pid'];
            }

            $postlink = get_permalink(geodir_add_listing_page_id());
            $editlink = geodir_getlink($postlink, array('pid' => $post_id), false);
            echo ' <p class="edit_link"><i class="fa fa-pencil"></i> <a href="' . $editlink . '">' . __('Edit this Post', GEODIRECTORY_TEXTDOMAIN) . '</a></p>';
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
    global $post;
    $package_info = array();
    $package_info = geodir_post_package_info($package_info, $post);

    ob_start(); // Start buffering;
    /**
     * This is called before the edit post link html in the function geodir_detail_page_google_analytics()
     *
     * @since 1.0.0
     */
    do_action('geodir_before_google_analytics');
    if (get_option('geodir_ga_stats') && get_edit_post_link() && is_user_logged_in() && (isset($package_info->google_analytics) && $package_info->google_analytics == '1')) {
        $page_url = $_SERVER['REQUEST_URI'];
        //$page_url = "/";
        ?>

        <script type="text/javascript">
            ga_data1 = false;
            ga_data2 = false;
            ga_data3 = false;
            ga_data4 = false;
            ga_data5 = false;
            ga_data6 = false;
            ga_au = 0;
            jQuery(document).ready(function () {

                gdga_weekVSweek();

                // Set some global Chart.js defaults.
                Chart.defaults.global.animationSteps = 60;
                Chart.defaults.global.animationEasing = 'easeInOutQuart';
                Chart.defaults.global.responsive = true;
                Chart.defaults.global.maintainAspectRatio = false;

                gdga_realtime();
            });

            function gdga_weekVSweek(){

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=thisweek", success: function(result){
                    ga_data1 = jQuery.parseJSON(result);
                    if(ga_data1.error){jQuery('#ga_stats').html(result);return;}
                    gd_renderWeekOverWeekChart();
                }});

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=lastweek", success: function(result){
                    ga_data2 = jQuery.parseJSON(result);
                    gd_renderWeekOverWeekChart();
                }});


            }

            function gdga_yearVSyear(){

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=thisyear", success: function(result){
                    ga_data3 = jQuery.parseJSON(result);
                    if(ga_data3.error){jQuery('#ga_stats').html(result);return;}

                    gd_renderYearOverYearChart()
                }});

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=lastyear", success: function(result){
                    ga_data4 = jQuery.parseJSON(result);
                    gd_renderYearOverYearChart()
                }});


            }

            function gdga_country(){

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=country", success: function(result){
                    ga_data5 = jQuery.parseJSON(result);
                    if(ga_data5.error){jQuery('#ga_stats').html(result);return;}
                    gd_renderTopCountriesChart();
                }});

            }

            function gdga_realtime(){

                jQuery.ajax({url: "<?php echo get_bloginfo('url').'/?ptype=ga&ga_page='.$page_url; ?>&ga_type=realtime", success: function(result){
                    ga_data6 = jQuery.parseJSON(result);
                    if(ga_data6.error){jQuery('#ga_stats').html(result);return;}
                    gd_renderRealTime();
                }});

            }

            function gd_renderRealTime(){
                ga_au_old = ga_au;

                ga_au = ga_data6.totalsForAllResults["rt:activeUsers"];

                    if(ga_au>ga_au_old){
                        jQuery('.gd-ActiveUsers').addClass( "is-increasing");
                    }

                    if(ga_au<ga_au_old){
                        jQuery('.gd-ActiveUsers').addClass( "is-decreasing");
                    }
                    jQuery('.gd-ActiveUsers-value').html(ga_au);

                // check for new users every 5 seconds
                setTimeout(function (){
                    jQuery('.gd-ActiveUsers').removeClass( "is-increasing is-decreasing" );
                    gdga_realtime();
                }, 5000);
            }


            /**
             * Draw the a chart.js doughnut chart with data from the specified view that
             * compares sessions from mobile, desktop, and tablet over the past two
             * weeks.
             */
            function gd_renderTopCountriesChart(){

                if(ga_data5){
                    response = ga_data5;
                    ga_data5 = false;
                }else{
                    return;
                }

                jQuery('#gdga-chart-container').show();
                jQuery('#gdga-legend-container').show();
                jQuery('#gdga-loader-icon').hide();
                jQuery('#gdga-select-analytic').show();


                        var data = [];
                        var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

                        response.rows.forEach(function(row, i) {
                            data.push({
                                label: row[0],
                                value: +row[1],
                                color: colors[i]
                            });
                        });

                        new Chart(makeCanvas('gdga-chart-container')).Doughnut(data);
                        generateLegend('gdga-legend-container', data);

            }


            /**
             * Draw the a chart.js bar chart with data from the specified view that
             * overlays session data for the current year over session data for the
             * previous year, grouped by month.
             */
            function gd_renderYearOverYearChart(){

                if(ga_data3 && ga_data4){
                    thisYear = ga_data3;
                    lastYear = ga_data4;
                    ga_data3 = false;
                    ga_data4 = false;

                }else{
                    return;
                }

                jQuery('#gdga-chart-container').show();
                jQuery('#gdga-legend-container').show();
                jQuery('#gdga-loader-icon').hide();
                jQuery('#gdga-select-analytic').show();

                // Adjust `now` to experiment with different days, for testing only...
                var now = moment(); // .subtract(3, 'day');



                Promise.all([thisYear, lastYear]).then(function(results) {
                    var data1 = results[0].rows.map(function(row) { return +row[2]; });
                    var data2 = results[1].rows.map(function(row) { return +row[2]; });
                    //var labelsN = results[0].rows.map(function(row) { return +row[1]; });



                    var labels = ['<?php _e('Jan', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Feb', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Mar', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Apr', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('May', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Jun', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Jul', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Aug', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Sep', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Oct', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Nov', GEODIRECTORY_TEXTDOMAIN);?>',
                        '<?php _e('Dec', GEODIRECTORY_TEXTDOMAIN);?>'];


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
                                label: '<?php _e('Last Year', GEODIRECTORY_TEXTDOMAIN);?>',
                                fillColor : "rgba(220,220,220,0.5)",
                                strokeColor : "rgba(220,220,220,1)",
                                data : data2
                            },
                            {
                                label: '<?php _e('This Year', GEODIRECTORY_TEXTDOMAIN);?>',
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : data1
                            }
                        ]
                    };

                    new Chart(makeCanvas('gdga-chart-container')).Bar(data);
                    generateLegend('gdga-legend-container', data.datasets);
                })
                    .catch(function(err) {
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
            jQuery('#gdga-loader-icon').hide();
                jQuery('#gdga-select-analytic').show();



                // Adjust `now` to experiment with different days, for testing only...
                var now = moment();

                Promise.all([thisWeek, lastWeek]).then(function(results) {

                    var data1 = results[0].rows.map(function(row) { return +row[2]; });
                    var data2 = results[1].rows.map(function(row) { return +row[2]; });
                    var labels = results[1].rows.map(function(row) { return +row[0]; });

                    labels = labels.map(function(label) {
                        return moment(label, 'YYYYMMDD').format('ddd');
                    });

                    var data = {
                        labels : labels,
                        datasets : [
                            {
                                label: '<?php _e('Last Week', GEODIRECTORY_TEXTDOMAIN);?>',
                                fillColor : "rgba(220,220,220,0.5)",
                                strokeColor : "rgba(220,220,220,1)",
                                pointColor : "rgba(220,220,220,1)",
                                pointStrokeColor : "#fff",
                                data : data2
                            },
                            {
                                label: '<?php _e('This Week', GEODIRECTORY_TEXTDOMAIN);?>',
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



            function gdga_select_option(){


                jQuery('#gdga-select-analytic').hide();
                jQuery('#gdga-loader-icon').show();

                gaType = jQuery('#gdga-select-analytic').val();

                if(gaType=='weeks'){
                    gdga_weekVSweek();

                }else if(gaType=='years'){
                    gdga_yearVSyear();

                }else if(gaType=='country'){
                    gdga_country();
                }

            }


        </script>

        <style>
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
        </style>
        <script src="https://ga-dev-tools.appspot.com/public/javascript/Chart.min.js"></script>
        <script src="https://ga-dev-tools.appspot.com/public/javascript/moment.min.js"></script>


        <span id="ga_stats">
            <div id="ga-analytics-title"><?php _e("Analytics", GEODIRECTORY_TEXTDOMAIN);?></div>

            <div id="gd-active-users-container">
                <div class="gd-ActiveUsers"><?php _e("Active Users:", GEODIRECTORY_TEXTDOMAIN);?>
                    <b class="gd-ActiveUsers-value">0</b>
                </div>
            </div>

            <select id="gdga-select-analytic" onchange="gdga_select_option();" style="display: none;">
                <option value="weeks"><?php _e("Last Week vs This Week", GEODIRECTORY_TEXTDOMAIN);?></option>
                <option value="years"><?php _e("This Year vs Last Year", GEODIRECTORY_TEXTDOMAIN);?></option>
                <option value="country"><?php _e("Top Countries", GEODIRECTORY_TEXTDOMAIN);?></option>
            </select>
            <img alt="loader icon" id="gdga-loader-icon" src="<?php echo geodir_plugin_url() . '/geodirectory-assets/images/ajax-loader.gif'; ?>" />
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
 * @package GeoDirectory
 */
function geodir_detail_page_review_rating()
{
    global $post, $preview, $post_images;
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
         * @param float $post_avgratings Average rating for the surrent post.
         * @param int $post->ID Current post ID.
         */
        do_action('geodir_before_review_rating_stars_on_detail', $post_avgratings, $post->ID);

        $html = '<p style=" float:left;">';
        $html .= geodir_get_rating_stars($post_avgratings, $post->ID);
        $html .= '<div class="average-review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">';
        $post_avgratings = (is_float($post_avgratings) || (strpos($post_avgratings, ".", 1) == 1 && strlen($post_avgratings) > 3)) ? number_format($post_avgratings, 1, '.', '') : $post_avgratings;
       
	   $reviews_text = $comment_count > 1 ? __("reviews", GEODIRECTORY_TEXTDOMAIN) : __("review", GEODIRECTORY_TEXTDOMAIN);
	   
	   $html .= '<span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><span class="rating" itemprop="average" content="' . $post_avgratings . '">' . $post_avgratings . '</span> / <span itemprop="best" content="5">5</span> ' . __("based on", GEODIRECTORY_TEXTDOMAIN) . ' </span><span class="count" itemprop="count" content="' . $comment_count . '">' . $comment_count . ' ' . $reviews_text . '</span><br />';

        $html .= '<span class="item">';
        $html .= '<span class="fn" itemprop="itemreviewed">' . $post->post_title . '</span>';

        if ($post_images) {
            foreach ($post_images as $img) {
                $post_img = $img->src;
                break;
            }
        }

        if (isset($post_img) && $post_img) {
            $html .= '<br /><img src="' . $post_img . '" class="photo hreview-img" alt="' . esc_attr($post->post_title) . '" itemprop="photo" content="' . $post_img . '" class="photo hreview-img" />';
        }

        $html .= '</span>';

        echo $html .= '</div>';
        /**
         * This is called after the rating html in the function geodir_detail_page_review_rating().
         *
         * This is called inside the check for an actual rating and the check for preview page.
         *
         * @since 1.0.0
         * @param float $post_avgratings Average rating for the surrent post.
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
 * This outputs the info section fo the details page which includes all the post custom fields.
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
    if ($geodir_post_detail_fields = geodir_show_listing_info()) {
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
 * This function outputs text strings used in JS fils as a json array of strings so they can be translated and still be used in JS files.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_localize_all_js_msg()
{// check_ajax_referer function is used to make sure no files are uplaoded remotly but it will fail if used between https and non https so we do the check below of the urls
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
	
    $arr_alert_msg = array(
        'geodir_plugin_url' => geodir_plugin_url(),
        'geodir_admin_ajax_url' => $ajax_url,
        'custom_field_not_blank_var' => __('HTML Variable Name must not be blank', GEODIRECTORY_TEXTDOMAIN),
        'custom_field_not_special_char' => __('Please do not use special character and spaces in HTML Variable Name.', GEODIRECTORY_TEXTDOMAIN),
        'custom_field_unique_name' => __('HTML Variable Name should be a unique name.', GEODIRECTORY_TEXTDOMAIN),
        'custom_field_delete' => __('Are you wish to delete this field?', GEODIRECTORY_TEXTDOMAIN),
        //start not show alert msg
        'tax_meta_class_succ_del_msg' => __('File has been successfully deleted.', GEODIRECTORY_TEXTDOMAIN),
        'tax_meta_class_not_permission_to_del_msg' => __('You do NOT have permission to delete this file.', GEODIRECTORY_TEXTDOMAIN),
        'tax_meta_class_order_save_msg' => __('Order saved!', GEODIRECTORY_TEXTDOMAIN),
        'tax_meta_class_not_permission_record_img_msg' => __('You do not have permission to reorder images.', GEODIRECTORY_TEXTDOMAIN),
        'address_not_found_on_map_msg' => __('Address not found for:', GEODIRECTORY_TEXTDOMAIN),
        // end not show alert msg
        'my_place_listing_del' => __('Are you wish to delete this listing?', GEODIRECTORY_TEXTDOMAIN),
        //start not show alert msg
        'rating_error_msg' => __('Error : please retry', GEODIRECTORY_TEXTDOMAIN),
        'listing_url_prefix_msg' => __('Please enter listing url prefix', GEODIRECTORY_TEXTDOMAIN),
        'invalid_listing_prefix_msg' => __('Invalid character in listing url prefix', GEODIRECTORY_TEXTDOMAIN),
        'location_url_prefix_msg' => __('Please enter location url prefix', GEODIRECTORY_TEXTDOMAIN),
        'invalid_location_prefix_msg' => __('Invalid character in location url prefix', GEODIRECTORY_TEXTDOMAIN),
        'location_and_cat_url_separator_msg' => __('Please enter location and category url separator', GEODIRECTORY_TEXTDOMAIN),
        'invalid_char_and_cat_url_separator_msg' => __('Invalid character in location and category url separator', GEODIRECTORY_TEXTDOMAIN),
        'listing_det_url_separator_msg' => __('Please enter listing detail url separator', GEODIRECTORY_TEXTDOMAIN),
        'invalid_char_listing_det_url_separator_msg' => __('Invalid character in listing detail url separator', GEODIRECTORY_TEXTDOMAIN),
        'loading_listing_error_favorite' => __('Error loading listing.', GEODIRECTORY_TEXTDOMAIN),
        'geodir_field_id_required' => __('This field is required.', GEODIRECTORY_TEXTDOMAIN),
        'geodir_valid_email_address_msg' => __('Please enter valid email address.', GEODIRECTORY_TEXTDOMAIN),
        'geodir_default_marker_icon' => get_option('geodir_default_marker_icon'),
        'geodir_latitude_error_msg' => GEODIR_LATITUDE_ERROR_MSG,
        'geodir_longgitude_error_msg' => GEODIR_LOGNGITUDE_ERROR_MSG,
        'geodir_default_rating_star_icon' => get_option('geodir_default_rating_star_icon'),
        'gd_cmt_btn_post_reply' => __('Post Reply', GEODIRECTORY_TEXTDOMAIN),
        'gd_cmt_btn_reply_text' => __('Reply text', GEODIRECTORY_TEXTDOMAIN),
        'gd_cmt_btn_post_review' => __('Post Review', GEODIRECTORY_TEXTDOMAIN),
        'gd_cmt_btn_review_text' => __('Review text', GEODIRECTORY_TEXTDOMAIN),
        'gd_cmt_err_no_rating' => __("Please select star rating, you can't leave a review without stars.", GEODIRECTORY_TEXTDOMAIN),
        /* on/off dragging for phone devices */
        'geodir_onoff_dragging' => get_option('geodir_map_onoff_dragging') ? true : false,
        'geodir_on_dragging_text' => __('Enable Dragging', GEODIRECTORY_TEXTDOMAIN),
        'geodir_off_dragging_text' => __('Disable Dragging', GEODIRECTORY_TEXTDOMAIN),
        'geodir_err_max_file_size' => __('File size error : You tried to upload a file over %s', GEODIRECTORY_TEXTDOMAIN),
        'geodir_err_file_upload_limit' => __('You have reached your upload limit of %s files.', GEODIRECTORY_TEXTDOMAIN),
        'geodir_err_pkg_upload_limit' => __('You may only upload %s files with this package, please try again.', GEODIRECTORY_TEXTDOMAIN),
        'geodir_action_remove' => __('Remove', GEODIRECTORY_TEXTDOMAIN),
		'geodir_txt_all_files' => __('Allowed files', GEODIRECTORY_TEXTDOMAIN),
		'geodir_err_file_type' => __('File type error. Allowed file types: %s', GEODIRECTORY_TEXTDOMAIN),
		'gd_allowed_img_types' => !empty($allowed_img_types) ? implode(',', $allowed_img_types) : '',
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
        $wp_admin_bar->add_menu(array('parent' => 'appearance', 'id' => 'geodirectory', 'title' => __('GeoDirectory', GEODIRECTORY_TEXTDOMAIN), 'href' => admin_url('?page=geodirectory')));
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
                //if(array_key_exists($key, $sidebars_widgets))
                {
                    $sidebars_widgets[$key] = $geodir_old_sidebars[$key];
                }


            }
        }

        // now clear all non geodiretory sidebars 
        foreach ($sidebars_widgets as $key => $val) {
            if (!array_key_exists($key, $geodir_old_sidebars)) {
                $sidebars_widgets[$key] = array();
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
 */
function geodir_after_main_form_fields()
{

    if (get_option('geodir_accept_term_condition')) {
        global $post;
        $term_condition = '';
        if (isset($_REQUEST['backandedit'])) {
            $post = (object)unserialize($_SESSION['listing']);
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
                       style="display:inline-block"/><?php echo __(stripslashes(get_option('geodir_term_condition_content')), GEODIRECTORY_TEXTDOMAIN); ?>
				</span>
            </div>
            <span class="geodir_message_error"><?php if (isset($required_msg)) {
                    _e($required_msg, GEODIRECTORY_TEXTDOMAIN);
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

    if ($tab == 'related_listing')
        $is_display = ((strpos($related_listing, __('No listings found which match your selection.', GEODIRECTORY_TEXTDOMAIN)) !== false || $related_listing == '' || !geodir_is_page('detail'))) ? false : true;


    return $is_display;
}


global $plugin_file_name;
add_action('after_plugin_row_' . $plugin_file_name, 'geodir_after_core_plugin_row', 3, 3);


/**
 * Add an action to show a message on core plugin row for deactivation.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $plugin_file Plugin file path.
 * @param array $plugin_data Plugin data.
 * @param string $status Plugin status.
 */
function geodir_after_core_plugin_row($plugin_file, $plugin_data, $status)
{
    //echo $plugin_file . " " .  $plugin_data . " " . $status ;
    if (is_plugin_active($plugin_file)) {
        $wp_list_table = _get_list_table('WP_Plugins_List_Table');

        echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="geodir-plugin-row-warning">';
        _e('Deactivate all GeoDirectory dependent add-ons first before deactivating GeoDirectory.', GEODIRECTORY_TEXTDOMAIN);
        echo '</div></td></tr>';
    }
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

    // updated custom field table(add field to show custom field as a tab)
    /*if (!$wpdb->get_var("SHOW COLUMNS FROM ".GEODIR_CUSTOM_FIELDS_TABLE." WHERE field = 'show_as_tab'")) {
		$wpdb->query("ALTER TABLE `".GEODIR_CUSTOM_FIELDS_TABLE."` ADD `show_as_tab` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `show_on_detail`");
	}
	
	if (!$wpdb->get_var("SHOW COLUMNS FROM ".GEODIR_CUSTOM_FIELDS_TABLE." WHERE field = 'for_admin_use'")) {
		$wpdb->query("ALTER TABLE `".GEODIR_CUSTOM_FIELDS_TABLE."` ADD `for_admin_use` ENUM( '0', '1' ) NOT NULL DEFAULT '0'");
	}*/

    if (!get_option('geodir_changes_in_custom_fields_table')) {

        $post_types = geodir_get_posttypes();

        /*if(!$wpdb->get_var("SHOW COLUMNS FROM ".GEODIR_CUSTOM_FIELDS_TABLE." WHERE field = 'is_admin'"))
					$wpdb->query("ALTER TABLE `".GEODIR_CUSTOM_FIELDS_TABLE."` ADD `is_admin` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `is_default`");*/

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


add_filter('wp_title', 'geodir_custom_page_title', 100, 2);
/**
 * Set custom page title.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 * @param string $title Old title.
 * @param string $sep Title separator.
 * @return string Modified title.
 */
function geodir_custom_page_title($title = '', $sep = '')
{
    global $wp;

    if ($sep == '') {
        /**
         * Filter the pae title separator.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param string $sep The separator, default: `|`.
         */
        $sep = apply_filters('geodir_page_title_separator', '|');
    }

    if ($title == '') {
        $sitename = get_bloginfo('name');
        $site_description = get_bloginfo('description');
        $title = $sitename . ' ' . $sep . ' ' . $site_description;
    }

    if (is_search() && isset($_REQUEST['geodir_search'])) {
        $all_postypes = geodir_get_posttypes();
        $keyword = esc_sql(strip_tags(get_query_var('s')));
        $stype = esc_sql(strip_tags($_REQUEST['stype']));
        $snear = esc_sql(strip_tags($_REQUEST['snear']));

        if ($stype && in_array($stype, $all_postypes)) {
            $title = $keyword;

            if (!empty($stype)) {
                $posttype_obj = get_post_type_object($stype);
                $title = $title . ' ' . $sep . ' ' . ucfirst($posttype_obj->labels->name);
            }

            if (!empty($snear)) {
                $title = $title . ' ' . $sep . ' ' . __('Near', GEODIRECTORY_TEXTDOMAIN) . ' ' . $snear;
            }

            $sitename = get_bloginfo('name');
            $title = $title . ' ' . $sep . ' ' . __('Search Results', GEODIRECTORY_TEXTDOMAIN) . ' ' . $sep . ' ' . $sitename;

        }
     }
    //print_r($wp->query_vars) ;
    if (isset($wp->query_vars['pagename']) && $wp->query_vars['pagename'] != '')
        $page = get_page_by_path($wp->query_vars['pagename']);
    if (!empty($page)) {
        $listing_page_id = geodir_add_listing_page_id();
        if ($listing_page_id != '' && $page->ID == $listing_page_id) {
            if (isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] != '') {
                $listing_type = $_REQUEST['listing_type'];
                $post_type_info = geodir_get_posttype_info($listing_type);
                if (!empty($title)) {
                    $title_array = explode($sep, $title);
                    $title_array[0] = ucwords(__('Add', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info['labels']['singular_name'], GEODIRECTORY_TEXTDOMAIN)) . ' ';
                    $title = implode($sep, $title_array);
                } else
                    $title = ucwords(__('Add', GEODIRECTORY_TEXTDOMAIN) . ' ' . __($post_type_info['labels']['singular_name'], GEODIRECTORY_TEXTDOMAIN));
                //$title .= " " . $gd_country . $gd_region . $gd_city  . "$sep ";
            }
        }
    }
    return $title;

}


//add_action('init', 'geodir_set_post_attachment'); // we need to make a tool somwhere to run this function maybe via ajax or something in batch form, it is crashing servers with lots of listings

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

        $args = array(
            'posts_per_page' => -1,
            'post_type' => $all_postypes,
            'post_status' => 'publish');

        $posts_array = get_posts($args);

        if (!empty($posts_array)) {

            foreach ($posts_array as $post) {

                geodir_set_wp_featured_image($post->ID);

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
 * @param array $permalink_arr The permalink array.
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
    global $wp;
    $user_id = get_current_user_id();

    if (!empty($post) && $post[0]->post_author == $user_id) {
        $post[0]->post_status = 'publish';
    }
    //print_r($post) ;
    return $post;
}


add_filter('geodir_detail_page_tab_list_extend', 'geodir_detail_page_tab_headings_change');
/**
 * Detail Page Tab Headings Change.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $tabs_arr Tabs array.
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
    if (isset($_REQUEST['geodir_signup'])) {
        remove_all_actions('template_redirect');
        remove_action('init', 'avia_modify_front', 10);
    }
}

add_filter('wpseo_title', 'geodir_post_type_archive_title', 11, 1);

add_filter('post_type_archive_title', 'geodir_post_type_archive_title', 10, 1);
/**
 * add location variables in geodirectory title parameter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $wp_query WordPress Query object.
 * @global object $wp WordPress object.
 * @param string $title The title parameter.
 * @return string Modified post title.
 */
function geodir_post_type_archive_title($title)
{
    global $wp_query, $wp, $wpdb;
    $title = str_replace("Location", __('Location', GEODIRECTORY_TEXTDOMAIN), $title);
    $wpseo_edit = false;
    $current_term = $wp_query->get_queried_object();

    if (!isset($current_term->ID)) {
        if (empty($current_term) || !is_object($current_term)) {
            $current_term = new stdClass();
        }
        $current_term->ID = '';
    }

    if (geodir_is_geodir_page() && (is_tax() || $current_term->ID == get_option('geodir_location_page') || (is_archive() && !$current_term->ID && !(is_tax() || $current_term->ID == get_option('geodir_location_page'))))) {
        $title = $title != '' ? __($title, GEODIRECTORY_TEXTDOMAIN) : '';
        ####### FIX FOR YOAST SEO START ########
        $separator_options = array(
            'sc-dash' => '-',
            'sc-ndash' => '&ndash;',
            'sc-mdash' => '&mdash;',
            'sc-middot' => '&middot;',
            'sc-bull' => '&bull;',
            'sc-star' => '*',
            'sc-smstar' => '&#8902;',
            'sc-pipe' => '|',
            'sc-tilde' => '~',
            'sc-laquo' => '&laquo;',
            'sc-raquo' => '&raquo;',
            'sc-lt' => '&lt;',
            'sc-gt' => '&gt;',
        );


        $wpseo = get_option('wpseo_titles');

        if (is_array($wpseo) && is_plugin_active('wordpress-seo/wp-seo.php')) {
            $sep = $separator_options[$wpseo['separator']];
            $title_parts = explode(' ' . $sep . ' ', $title, 2);
            $title = $title_parts[0];
            $wpseo_edit = true;
        }
        ####### FIX FOR YOAST SEO END ########
		
		$gd_post_type = geodir_get_current_posttype();
        $post_type_info = get_post_type_object($gd_post_type);

        $location_array = geodir_get_current_location_terms('query_vars', $gd_post_type);
        if (!empty($location_array)) {
            $location_titles = array();
            $actual_location_name = function_exists('get_actual_location_name') ? true : false;
            $location_array = array_reverse($location_array);

            foreach ($location_array as $location_type => $location) {
                $gd_location_link_text = preg_replace('/-(\d+)$/', '', $location);
                $gd_location_link_text = preg_replace('/[_-]/', ' ', $gd_location_link_text);

                $location_name = ucwords($gd_location_link_text);
                $location_name = __($location_name, GEODIRECTORY_TEXTDOMAIN);

                if ($actual_location_name) {
                    $location_type = strpos($location_type, 'gd_') === 0 ? substr($location_type, 3) : $location_type;
                    $location_name = get_actual_location_name($location_type, $location, true);
                }

                $location_titles[] = $location_name;
            }
            if (!empty($location_titles)) {
                $location_titles = array_unique($location_titles);
                $title .= __(' in ', GEODIRECTORY_TEXTDOMAIN) . implode(", ", $location_titles);
            }
        }

        /*if( get_query_var( $gd_post_type . 'category' ) ) {
			$gd_taxonomy = $gd_post_type . 'category';
			$taxonomy_title = __( ' with category ', GEODIRECTORY_TEXTDOMAIN );
		}
		else if( get_query_var( $gd_post_type . '_tags' ) ) {
			$gd_taxonomy = $gd_post_type . '_tags';
			$taxonomy_title = __( ' with tag ', GEODIRECTORY_TEXTDOMAIN );
		}*/

        if (!empty($gd_taxonomy)) {
            $taxonomy_titles = array();
            $term_array = explode("/", trim($wp->query_vars[$gd_taxonomy], "/"));

            if (!empty($term_array)) {
                foreach ($term_array as $term) {
                    $term_link_text = preg_replace('/-(\d+)$/', '', $term);
                    $term_link_text = preg_replace('/[_-]/', ' ', $term_link_text);
                }

                //$title .= ' ' . ucwords( $term_link_text );
                $taxonomy_titles[] = ucwords($term_link_text);
            }
        }

        if (!empty($taxonomy_titles)) {
            $taxonomy_titles = array_unique($taxonomy_titles);
            $title .= (!empty($location_titles) ? $taxonomy_title : __(' in ', GEODIRECTORY_TEXTDOMAIN));
            $title .= implode(", ", $taxonomy_titles);
        }
    }

    ####### FIX FOR YOAST SEO START ########	
    if ($wpseo_edit && isset($title_parts[1])) {
        $title = $title . ' ' . $sep . ' ' . $title_parts[1];
    }
    ####### FIX FOR YOAST SEO END ########

    return $title;
}

add_filter('single_post_title', 'geodir_single_post_title', 10, 2);
/**
 * add location variables in geodirectory title parameter for single post.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 * @param string $title The title parameter.
 * @param object $post Post object.
 * @return string Modified post title.
 */
function geodir_single_post_title($title, $post)
{
    global $wp;
    if ($post->post_title == 'Location' && geodir_is_geodir_page()) {
        $title = defined('GD_LOCATION') ? GD_LOCATION : __('Location', GEODIRECTORY_TEXTDOMAIN);

        $location_array = geodir_get_current_location_terms('query_vars');

        if (!empty($location_array)) {
            $location_array = array_reverse($location_array);
            $actual_location_name = function_exists('get_actual_location_name') ? true : false;

            foreach ($location_array as $location_type => $location) {
                $gd_location_link_text = preg_replace('/-(\d+)$/', '', $location);
                $gd_location_link_text = preg_replace('/[_-]/', ' ', $gd_location_link_text);

                $gd_location_link_text = ucwords($gd_location_link_text);
                $gd_location_link_text = __($gd_location_link_text, GEODIRECTORY_TEXTDOMAIN);

                if ($actual_location_name) {
                    $location_type = strpos($location_type, 'gd_') === 0 ? substr($location_type, 3) : $location_type;
                    $gd_location_link_text = get_actual_location_name($location_type, $location, true);
                }

                $title .= ' ' . $gd_location_link_text;
            }

            $gd_post_type = geodir_get_current_posttype();
            $post_type_info = get_post_type_object($gd_post_type);

            if (get_query_var($gd_post_type . 'category')) {
                $gd_taxonomy = $gd_post_type . 'category';
            } else if (get_query_var($gd_post_type . '_tags')) {
                $gd_taxonomy = $gd_post_type . '_tags';
            }

            if (!empty($gd_taxonomy)) {
                $term_array = explode("/", trim($wp->query_vars[$gd_taxonomy], "/"));

                if (!empty($term_array)) {
                    foreach ($term_array as $term) {
                        $term_link_text = preg_replace('/-(\d+)$/', '', $term);
                        $term_link_text = preg_replace('/[_-]/', ' ', $term_link_text);
                    }

                    $title .= ' ' . ucwords($term_link_text);
                }
            }
        }
    }
    return $title;
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
                        $uploads_baseurl = $uploads['baseurl'];
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
function geodir_user_post_listing_count()
{
    global $wpdb, $plugin_prefix, $current_user;

    $user_id = $current_user->ID;
    $all_postypes = geodir_get_posttypes();
    $all_posts = get_option('geodir_listing_link_user_dashboard');

    $user_listing = array();
    if (is_array($all_posts) && !empty($all_posts)) {
        foreach ($all_posts as $ptype) {
            $total_posts = $wpdb->get_var("SELECT count( ID )
											FROM " . $wpdb->prefix . "posts
											WHERE post_author=" . $user_id . " AND post_type='" . $ptype . "' AND ( post_status = 'publish' OR post_status = 'draft' OR post_status = 'private' )");

            if ($total_posts > 0) {
                $user_listing[$ptype] = $total_posts;
            }
        }
    }

    return $user_listing;
}


/* ------- GET CURRENT USER FAVOURITE LISTING -------*/
/**
 * Get user's favorite listing count.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @return array User listing count for each post type.
 */
function geodir_user_favourite_listing_count()
{
    global $wpdb, $plugin_prefix, $current_user;

    $user_id = $current_user->ID;
    $all_postypes = geodir_get_posttypes();
    $user_favorites = get_user_meta($user_id, 'gd_user_favourite_post', true);
    $all_posts = get_option('geodir_favorite_link_user_dashboard');

    $user_listing = array();
    if (is_array($all_posts) && !empty($all_posts) && is_array($user_favorites) && !empty($user_favorites)) {
        $user_favorites = "'" . implode("','", $user_favorites) . "'";

        foreach ($all_posts as $ptype) {
            $total_posts = $wpdb->get_var("SELECT count( ID )
											FROM " . $wpdb->prefix . "posts
											WHERE  post_type='" . $ptype . "' AND post_status = 'publish' AND ID IN (" . $user_favorites . ")");

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
 * @package GeoDirectory
 * @global object $post The current post object.
 * @param array $tabs_arr Tabs array.
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
        $post_package_id = $package_info->pid;
        $fields_location = 'detail';

        $custom_fields = geodir_post_custom_fields($post_package_id, 'default', $post_type, $fields_location);
        if (!empty($custom_fields)) {
            $parse_custom_fields = array();
            foreach ($custom_fields as $field) {
                $field = stripslashes_deep($field); // strip slashes
				
				$type = $field;
                $field_name = $field['htmlvar_name'];
                if (empty($geodir_post_info) && geodir_is_page('preview') && $field_name != '' && !isset($post->$field_name) && isset($_REQUEST[$field_name])) {
                    $post->$field_name = $_REQUEST[$field_name];
                }

                if (isset($field['show_as_tab']) && $field['show_as_tab'] == 1 && ((isset($post->$field_name) && $post->$field_name != '') || $field['type'] == 'fieldset') && in_array($field['type'], array('text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file'))) {
                    if ($type['type'] == 'datepicker' && ($post->$type['htmlvar_name'] == '' || $post->$type['htmlvar_name'] == '0000-00-00')) {
                        continue;
                    }

                    $parse_custom_fields[] = $field;
                }
            }
            $custom_fields = $parse_custom_fields;
        }

        if (!empty($custom_fields)) {
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
                if (empty($geodir_post_info) && geodir_is_page('preview') && $field_name != '' && !isset($post->$field_name) && isset($_REQUEST[$field_name])) {
                    $post->$field_name = $_REQUEST[$field_name];
                }

                if (isset($field['show_as_tab']) && $field['show_as_tab'] == 1 && ((isset($post->$field_name) && $post->$field_name != '') || $field['type'] == 'fieldset') && in_array($field['type'], array('text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file'))) {
                    $label = $field['site_title'] != '' ? $field['site_title'] : $field['admin_title'];
                    $site_title = trim($field['site_title']);
                    $type = $field;
                    $html = '';
                    $html_var = $field_name;
                    $field_icon = '';
                    $variables_array = array();

                    if ($type['type'] == 'datepicker' && ($post->$type['htmlvar_name'] == '' || $post->$type['htmlvar_name'] == '0000-00-00')) {
                        continue;
                    }

                    if ($type['type'] != 'fieldset') {
                        $i++;
                        $variables_array['post_id'] = $post->ID;
                        $variables_array['label'] = __($type['site_title'], GEODIRECTORY_TEXTDOMAIN);
                        $variables_array['value'] = '';
                        $variables_array['value'] = $post->$type['htmlvar_name'];
                    }

                    if (strpos($type['field_icon'], 'http') !== false) {
                        $field_icon = ' background: url(' . $type['field_icon'] . ') no-repeat left center;background-size:18px 18px;padding-left: 21px;';
                    } elseif (strpos($type['field_icon'], 'fa fa-') !== false) {
                        $field_icon = '<i class="' . $type['field_icon'] . '"></i>';
                    }

                    switch ($type['type']) {
                        case 'fieldset': {
                            $i = 0;
                            $fieldset_count++;
                            $field_set_start = 1;
                            $fieldset_arr[$fieldset_count]['htmlvar_name'] = 'gd_tab_' . $fieldset_count;
                            $fieldset_arr[$fieldset_count]['label'] = $label;
                        }
                            break;
                        case 'url': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {

                                if ($type['name'] == 'geodir_facebook') {
                                    $field_icon_af = '<i class="fa fa-facebook-square"></i>';
                                } elseif ($type['name'] == 'geodir_twitter') {
                                    $field_icon_af = '<i class="fa fa-twitter-square"></i>';
                                } else {
                                    $field_icon_af = '<i class="fa fa-link"></i>';
                                }

                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            if (!strstr($post->$type['htmlvar_name'], 'http'))
                                $website = 'http://' . $post->$type['htmlvar_name'];
                            else
                                $website = $post->$type['htmlvar_name'];

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            // all search engines that use the nofollow value exclude links that use it from their ranking calculation
                            $rel = strpos($website, get_site_url()) !== false ? '' : 'rel="nofollow"';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '"><span class="geodir-i-website" style="' . $field_icon . '">' . $field_icon_af . ' <a href="' . $website . '" target="_blank" ' . $rel . ' ><strong>' .
                                /**
                                 * Filer the custom field website name.
                                 *
                                 * @since 1.0.0
                                 * @param string $type['site_title'] The field name default: "Website".
                                 * @param string $website The website address.
                                 * @param int $post->ID The post ID.
                                 */
                                apply_filters('geodir_custom_field_website_name', stripslashes(__($type['site_title'], GEODIRECTORY_TEXTDOMAIN)), $website, $post->ID) . '</strong></a></span></div>';
                        }
                            break;
                        case 'phone': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '<i class="fa fa-phone"></i>';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-contact" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . stripslashes($post->$type['htmlvar_name']) . '</div>';
                        }
                            break;
                        case 'time': {
                            $value = '';
                            if ($post->$type['htmlvar_name'] != '')
                                //$value = date('h:i',strtotime($post->$type['htmlvar_name']));
                                $value = date(get_option('time_format'), strtotime($post->$type['htmlvar_name']));

                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '<i class="fa fa-clock-o"></i>';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-time" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . stripslashes($value) . '</div>';
                        }
                            break;
                        case 'datepicker': {
                            $date_format = geodir_default_date_format();
                            if ($type['extra_fields'] != '') {
                                $date_format = unserialize($type['extra_fields']);
                                $date_format = $date_format['date_format'];
                            }

                            $search = array('dd', 'mm', 'yy');
                            $replace = array('d', 'm', 'Y');

                            $date_format = str_replace($search, $replace, $date_format);

                            $post_htmlvar_value = $date_format == 'd/m/Y' ? str_replace('/', '-', $post->$type['htmlvar_name']) : $post->$type['htmlvar_name']; // PHP doesn't work well with dd/mm/yyyy format

                            $value = '';
                            if ($post->$type['htmlvar_name'] != '')
                                $value = date($date_format, strtotime($post_htmlvar_value));

                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '<i class="fa fa-calendar"></i>';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-datepicker" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . $value . '</div>';
                        }
                            break;
                        case 'text': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-text" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . stripslashes($post->$type['htmlvar_name']) . '</div>';
                        }
                            break;
                        case 'radio': {
                            if ($post->$type['htmlvar_name'] != '') {
                                if ($post->$type['htmlvar_name'] == 'f' || $post->$type['htmlvar_name'] == '0') {
                                    $html_val = __('No', GEODIRECTORY_TEXTDOMAIN);
                                } else if ($post->$type['htmlvar_name'] == 't' || $post->$type['htmlvar_name'] == '1') {
                                    $html_val = __('Yes', GEODIRECTORY_TEXTDOMAIN);
                                }

                                if (strpos($field_icon, 'http') !== false) {
                                    $field_icon_af = '';
                                } else if ($field_icon == '') {
                                    $field_icon_af = '';
                                } else {
                                    $field_icon_af = $field_icon;
                                    $field_icon = '';
                                }

                                $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                                $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-radio" style="' . $field_icon . '">' . $field_icon_af;

                                if ($field_set_start == 1 && $site_title != '') {
                                    $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                                }

                                $html .= ' </span>' . $html_val . '</div>';
                            }
                        }
                            break;
                        case 'checkbox': {
                            $html_var = $type['htmlvar_name'];
                            $html_val = $type['htmlvar_name'];

                            if ((int)$post->$html_var == 1) {

                                if ($post->$type['htmlvar_name'] == '1') {
                                    $html_val = __('Yes', GEODIRECTORY_TEXTDOMAIN);
                                } else {
                                    $html_val = __('No', GEODIRECTORY_TEXTDOMAIN);
                                }

                                if (strpos($field_icon, 'http') !== false) {
                                    $field_icon_af = '';
                                } else if ($field_icon == '') {
                                    $field_icon_af = '';
                                } else {
                                    $field_icon_af = $field_icon;
                                    $field_icon = '';
                                }

                                $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                                $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-checkbox" style="' . $field_icon . '">' . $field_icon_af;

                                if ($field_set_start == 1 && $site_title != '') {
                                    $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                                }

                                $html .= ' </span>' . $html_val . '</div>';
                            }
                        }
                            break;
                        case 'select': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-select" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . stripslashes($post->$type['htmlvar_name']) . '</div>';
                        }
                            break;
                        case 'multiselect': {
                            if (is_array($post->$type['htmlvar_name'])) {
                                $post->$type['htmlvar_name'] = implode(', ', $post->$type['htmlvar_name']);
                            }

                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }


                            $option_values = explode(',', $post->$type['htmlvar_name']);

                            if ($type['option_values']) {
                                if (strstr($type['option_values'], "/")) {
                                    $option_values = array();
                                    $field_values = explode(',', $type['option_values']);
                                    foreach ($field_values as $data) {
                                        $val = explode('/', $data);
                                        if (isset($val[1]) && in_array($val[1], explode(',', $post->$type['htmlvar_name'])))
                                            $option_values[] = $val[0];
                                    }
                                }
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-select" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>';

                            if (count($option_values) > 1) {
                                $html .= '<ul>';
                                foreach ($option_values as $val) {
                                    $html .= '<li>' . stripslashes($val) . '</li>';
                                }
                                $html .= '</ul>';
                            } else {
                                $html .= stripslashes(trim($post->$type['htmlvar_name'], ','));
                            }
                            $html .= '</div>';
                        }
                            break;
                        case 'email': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '<i class="fa fa-envelope"></i>';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-email" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . stripslashes($post->$type['htmlvar_name']) . '</div>';
                        }
                            break;
                        case 'textarea': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-text" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= '</span>' . wpautop(stripslashes($post->$type['htmlvar_name'])) . '</div>';
                        }
                            break;
                        case 'html': {
                            if (strpos($field_icon, 'http') !== false) {
                                $field_icon_af = '';
                            } elseif ($field_icon == '') {
                                $field_icon_af = '';
                            } else {
                                $field_icon_af = $field_icon;
                                $field_icon = '';
                            }

                            $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';

                            $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . '" style="clear:both;"><span class="geodir-i-text" style="' . $field_icon . '">' . $field_icon_af;
                            if ($field_set_start == 1 && $site_title != '') {
                                $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                            }
                            $html .= ' </span>' . wpautop(stripslashes($post->$type['htmlvar_name'])) . '</div>';
                        }
                            break;
                        case 'file': {
                            $html_var = $type['htmlvar_name'];

                            if (!empty($post->$type['htmlvar_name'])) {
                                $files = explode(",", $post->$type['htmlvar_name']);

                                if (!empty($files)) {
                                    $extra_fields = !empty($type['extra_fields']) ? maybe_unserialize($type['extra_fields']) : NULL;
							   		$allowed_file_types = !empty($extra_fields['gd_file_types']) && is_array($extra_fields['gd_file_types']) && !in_array("*", $extra_fields['gd_file_types'] ) ? $extra_fields['gd_file_types'] : '';
							   
									$file_paths = '';
                                    foreach ($files as $file) {
                                        if (!empty($file)) {
                                            $filetype = wp_check_filetype($file);
                                            $image_name_arr = explode('/', $file);
                                            $curr_img_dir = $image_name_arr[count($image_name_arr) - 2];
                                            $filename = end($image_name_arr);
                                            $img_name_arr = explode('.', $filename);

                                            $arr_file_type = wp_check_filetype($filename);
                                            if (empty($arr_file_type['ext']) || empty($arr_file_type['type'])) {
												continue;
											}
											$uploaded_file_type = $arr_file_type['type'];
											$uploaded_file_ext = $arr_file_type['ext'];
											
											if (!empty($allowed_file_types) && !in_array($uploaded_file_ext, $allowed_file_types)) {
												continue; // Invalid file type.
											}

                                            //$allowed_file_types = array('application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'text/plain');
											$image_file_types = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/x-icon');

                                            // If the uploaded file is image
                                            if (in_array($uploaded_file_type, $image_file_types)) {
                                                $file_paths .= '<div class="geodir-custom-post-gallery" class="clearfix">';
                                                $file_paths .= geodir_show_image(array('src' => $file), 'thumbnail', false, false);
                                                //$file_paths .= '<img src="'.$file.'"  />';
                                                $file_paths .= '</div>';
                                            } else {
                                               $ext_path = '_' . $html_var . '_';
                                               $filename = explode($ext_path, $filename);
                                               $file_paths .= '<a href="' . $file . '" target="_blank">' . $filename[count($filename) - 1] . '</a>';
                                            }
                                        }
                                    }

                                    if (strpos($field_icon, 'http') !== false) {
                                        $field_icon_af = '';
                                    } else if ($field_icon == '') {
                                        $field_icon_af = '';
                                    } else {
                                        $field_icon_af = $field_icon;
                                        $field_icon = '';
                                    }

                                    $geodir_odd_even = $field_set_start == 1 && $i % 2 == 0 ? 'geodir_more_info_even' : 'geodir_more_info_odd';


                                    $html = '<div class="geodir_more_info ' . $geodir_odd_even . ' ' . $type['css_class'] . ' ' . $type['htmlvar_name'] . ' geodir-custom-file-box" style="clear:both;"><span class="geodir-i-file" style="display:inline-block;vertical-align:top;padding-right:14px;' . $field_icon . '">' . $field_icon_af;

                                    if ($field_set_start == 1 && $site_title != '') {
                                        $html .= ' ' . __($site_title, GEODIRECTORY_TEXTDOMAIN) . ': ';
                                    }

                                    $html .= ' </span>' . $file_paths . '</div>';
                                }
                            }
                        }
                            break;
                    }
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
                                'heading_text' => __($label, GEODIRECTORY_TEXTDOMAIN),
                                'is_active_tab' => false,
                                /**
                                 * Filter if a custom field should be displayed on the details page tab.
                                 *
                                 * @since 1.0.0
                                 * @param string $htmlvar_name The field HTML var name.
                                 */
                                'is_display' => apply_filters('geodir_detail_page_tab_is_display', true, $htmlvar_name),
                                'tab_content' => '<div class="geodir-company_info field-group">' . $fieldset_html . '</html>'
                            );
                        }
                    } else {
                        if ($html != '') {
                            $tabs_arr[$field['htmlvar_name']] = array(
                                'heading_text' => __($label, GEODIRECTORY_TEXTDOMAIN),
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
        if (geodir_is_page('author') && !empty($post) && isset($post->post_author) && $post->post_author == get_current_user_id()) {

            // we need to query real status direct as we dynamically change the status for author on author page so even non author status can view them.
            $real_status = $wpdb->get_var("SELECT post_status from $wpdb->posts WHERE ID=$post->ID");
            $status = "<strong>(";
            $status_icon = '<i class="fa fa-play"></i>';
            if ($real_status == 'publish') {
                $status .= __('Published', GEODIRECTORY_TEXTDOMAIN);
            } else {
                $status .= __('Not published', GEODIRECTORY_TEXTDOMAIN);
                $status_icon = '<i class="fa fa-pause"></i>';
            }
            $status .= ")</strong>";

            $html = '<span class="geodir-post-status">' . $status_icon . ' <font class="geodir-status-label">' . __('Status: ', GEODIRECTORY_TEXTDOMAIN) . '</font>' . $status . '</span>';
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
 * @package GeoDirectory
 */
function geodir_init_no_rating()
{
    if (get_option('geodir_disable_rating')) {
        remove_action('comment_form_logged_in_after', 'geodir_comment_rating_fields');
        remove_action('comment_form_before_fields', 'geodir_comment_rating_fields');
        remove_action('comment_form_logged_in_after', 'geodir_reviewrating_comment_rating_fields');
        remove_action('comment_form_before_fields', 'geodir_reviewrating_comment_rating_fields');
        remove_action('add_meta_boxes_comment', 'geodir_comment_add_meta_box');
        remove_action('add_meta_boxes', 'geodir_reviewrating_comment_metabox', 13);
        remove_filter('comment_text', 'geodir_wrap_comment_text', 10);

        add_action('comment_form_logged_in_after', 'geodir_no_rating_rating_fields');
        add_action('comment_form_before_fields', 'geodir_no_rating_rating_fields');
        add_filter('comment_text', 'geodir_no_rating_comment_text', 100, 2);
        add_filter('geodir_detail_page_review_rating_html', 'geodir_no_rating_review_rating_html', 100);
        add_filter('geodir_get_sort_options', 'geodir_no_rating_get_sort_options', 100, 2);
    }
}

/**
 * Modify rating fields when rating disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 */
function geodir_no_rating_rating_fields()
{
    global $post;

    $post_types = geodir_get_posttypes();

    if (!empty($post) && isset($post->post_type) && in_array($post->post_type, $post_types)) {
        if (is_plugin_active('geodir_review_rating_manager/geodir_review_rating_manager.php')) {
            echo '<input type="hidden" value="1" name="geodir_rating[overall]" />';
            if (get_option('geodir_reviewrating_enable_images')) {
                geodir_reviewrating_rating_img_html();
            }
        } else {
            echo '<input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="1" />';
        }
    }
}

/**
 * Returns normal comment text when rating disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $content Comment text.
 * @param string|object $comment Comment object.
 * @return string Comment HTML.
 */
function geodir_no_rating_comment_text($content, $comment = '')
{
    if (!is_admin()) {
        return '<div class="description">' . $content . '</div>';
    } else {
        return $content;
    }
}

/**
 * Remove rating HTML when rating disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $content HTML content.
 * @return null
 */
function geodir_no_rating_review_rating_html($content = '')
{
    return NULL;
}

/**
 * Skip overall rating sort option when rating disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $options Sort options array.
 * @param string $post_type The post type.
 * @return array Modified sort options array.
 */
function geodir_no_rating_get_sort_options($options, $post_type = '')
{
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

    return $options;
}

add_filter('geodir_all_js_msg', 'geodir_all_js_msg_no_rating', 100);
/**
 * skip rating stars validation if rating stars disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $msg Message array.
 * @return array Modified message array.
 */
function geodir_all_js_msg_no_rating($msg = array())
{
    if (get_option('geodir_disable_rating')) {
        $msg['gd_cmt_no_rating'] = true;
    }

    return $msg;
}

add_filter('body_class', 'geodir_body_class_no_rating', 100);
/**
 * add body class when rating stars if disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $classes The class array of the HTML element.
 * @return array Modified class array.
 */
function geodir_body_class_no_rating($classes = array())
{
    if (get_option('geodir_disable_rating')) {
        $classes[] = 'gd-no-rating';
    }

    return $classes;
}

add_filter('admin_body_class', 'geodir_admin_body_class_no_rating', 100);
/**
 * Adds class to disable rating.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $class The class of the HTML element.
 * @return string Modified class string.
 */
function geodir_admin_body_class_no_rating($class = '')
{
    if (get_option('geodir_disable_rating')) {
        $class .= ' gd-no-rating';
    }

    return $class;
}

add_action('wp_head', 'geodir_wp_head_no_rating');
add_action('admin_head', 'geodir_wp_head_no_rating');
/**
 * hide rating stars if disabled.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_wp_head_no_rating()
{
    if (get_option('geodir_disable_rating')) {
        echo '<style>body .geodir-rating, body .geodir-bubble-rating, body .gd_ratings_module_box{display:none!important;}</style>';
        echo '<script type="text/javascript">jQuery(function(){jQuery(".gd_rating_show").parent(".geodir-rating").remove();});</script>';
    }
}

add_filter('geodir_load_db_language', 'geodir_load_custom_field_translation');

/**
 * Meta description for search page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_search_meta_desc($html) {
    if (is_search() && isset($_REQUEST['geodir_search'])) {
        $all_postypes = geodir_get_posttypes();
        $keyword = esc_sql(strip_tags(get_query_var('s')));
        $stype = esc_sql(strip_tags($_REQUEST['stype']));
        $snear = esc_sql(strip_tags($_REQUEST['snear']));

        if ($stype && in_array($stype, $all_postypes)) {
            $desc = __('Search results for', GEODIRECTORY_TEXTDOMAIN);

            if(!empty($keyword)) {
                $desc = $desc . ' ' . $keyword;
            }

            if(!empty($stype)) {
                $posttype_obj = get_post_type_object( $stype );
                $desc = $desc . ' ' . __('in', GEODIRECTORY_TEXTDOMAIN) . ' ' . $posttype_obj->labels->name;
            }

            if(!empty($snear)) {
                $desc = $desc . ' ' . __('near', GEODIRECTORY_TEXTDOMAIN) . ' ' . $snear;
            }
            $html = '<meta name="description" content="' . $desc . '" />';
        }
    }
    return $html;
}
add_filter('geodir_seo_meta_description', 'geodir_search_meta_desc', 10, 1);