<?php
/**
 * Retrive markers data and marker info window to use in map
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

// Enable map cache if set
if(get_option('geodir_enable_map_cache')){
    add_filter('geodir_get_markers_cache','geodir_get_map_cache');
    add_filter('geodir_markers_json','geodir_save_map_cache',10);
}



/**
 * Get the map cache if it exists.
 *
 * @param string $cache The JSON string cache if exists.
 * @since 1.6.22
 * @return string
 */
function geodir_get_map_cache($cache){

    // if a search is going on then dont even try to check for cache.
    if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
        return $cache;
    }

    $url_params = array();
    $url_params[] = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
    $url_params[] = isset($_REQUEST['zl']) ? $_REQUEST['zl'] : '';
    $url_params[] = isset($_REQUEST['gd_map_h']) ? $_REQUEST['gd_map_h'] : '';
    $url_params[] = isset($_REQUEST['gd_map_w']) ? $_REQUEST['gd_map_w'] : '';
    $url_params[] = isset($_REQUEST['gd_posttype']) ? $_REQUEST['gd_posttype'] : '';
    $url_params[] = isset($_REQUEST['lat_ne']) ? $_REQUEST['lat_ne'] : '';
    $url_params[] = isset($_REQUEST['lon_ne']) ? $_REQUEST['lon_ne'] : '';
    $url_params[] = isset($_REQUEST['lat_sw']) ? $_REQUEST['lat_sw'] : '';
    $url_params[] = isset($_REQUEST['lon_sw']) ? $_REQUEST['lon_sw'] : '';
    $url_params[] = isset($_REQUEST['gd_country']) ? $_REQUEST['gd_country'] : '';
    $url_params[] = isset($_REQUEST['gd_region']) ? $_REQUEST['gd_region'] : '';
    $url_params[] = isset($_REQUEST['gd_city']) ? $_REQUEST['gd_city'] : '';
    $url_params[] = isset($_REQUEST['gd_neighbourhood']) ? $_REQUEST['gd_neighbourhood'] : '';

    $file_name = sanitize_file_name( md5( implode("-",$url_params) )  );

    $blog_id = get_current_blog_id();
    if($blog_id>1){
        $file_name = $blog_id."_".$file_name;
    }

    $file_path = realpath(dirname(__FILE__))."/map-cache/";


    if(file_exists($file_path.$file_name.".json")){


        // do the cache delete stuff
        $cache_time = get_option('geodir_map_cache');
        if(!$cache_time){
            $cache_time = time();
            update_option('geodir_map_cache', $cache_time);
        }

        if((time() - $cache_time) > 86400){ // delete the cache every 24 hours
            geodir_delete_map_cache();
        }

        ob_start();
        readfile($file_path.$file_name.".json"); // readfile is quicker then file get contents
        return ob_get_clean();
    }

    return $cache;
}


/**
 * Save the map cache to a json file.
 *
 * @param string $map_json The map marker json.
 * @since 1.6.22
 * @return mixed
 */
function geodir_save_map_cache($map_json){

    // if a search is going on then dont even try to check for cache.
    if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
        return $map_json;
    }

    $url_params = array();
    $url_params[] = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
    $url_params[] = isset($_REQUEST['zl']) ? $_REQUEST['zl'] : '';
    $url_params[] = isset($_REQUEST['gd_map_h']) ? $_REQUEST['gd_map_h'] : '';
    $url_params[] = isset($_REQUEST['gd_map_w']) ? $_REQUEST['gd_map_w'] : '';
    $url_params[] = isset($_REQUEST['gd_posttype']) ? $_REQUEST['gd_posttype'] : '';
    $url_params[] = isset($_REQUEST['lat_ne']) ? $_REQUEST['lat_ne'] : '';
    $url_params[] = isset($_REQUEST['lon_ne']) ? $_REQUEST['lon_ne'] : '';
    $url_params[] = isset($_REQUEST['lat_sw']) ? $_REQUEST['lat_sw'] : '';
    $url_params[] = isset($_REQUEST['lon_sw']) ? $_REQUEST['lon_sw'] : '';
    $url_params[] = isset($_REQUEST['gd_country']) ? $_REQUEST['gd_country'] : '';
    $url_params[] = isset($_REQUEST['gd_region']) ? $_REQUEST['gd_region'] : '';
    $url_params[] = isset($_REQUEST['gd_city']) ? $_REQUEST['gd_city'] : '';
    $url_params[] = isset($_REQUEST['gd_neighbourhood']) ? $_REQUEST['gd_neighbourhood'] : '';

    $file_name = sanitize_file_name( md5( implode("-",$url_params) )  );

    $blog_id = get_current_blog_id();
    if($blog_id>1){
        $file_name = $blog_id."_".$file_name;
    }

    $file_path = realpath(dirname(__FILE__))."/map-cache/";


    global $wp_filesystem;
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
    $wp_filesystem->put_contents(
        $file_path.$file_name.".json",
        $map_json,
        FS_CHMOD_FILE // predefined mode settings for WP files
    );


    return $map_json;

}


if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'homemap_catlist') {
    global $gd_session;
    $gd_post_type = sanitize_text_field($_REQUEST['post_type']);
    $gd_session->set('homemap_catlist_ptype', $gd_post_type);
    $post_taxonomy = geodir_get_taxonomies($gd_post_type);
    $map_canvas_name = sanitize_text_field($_REQUEST['map_canvas']);
    $child_collapse = (bool)$_REQUEST['child_collapse'];
    echo home_map_taxonomy_walker($post_taxonomy, 0, true, 0, $map_canvas_name, $child_collapse, true);
    die;
}

// Send the content-type header with correct encoding
header("Content-type: text/javascript; charset=utf-8");

if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'cat') { // Retrives markers data for categories
    echo get_markers();
    exit;
} else if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'info') { // Retrives marker info window html
    /**
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @global object $gd_session GeoDirectory Session object.
     */
    global $wpdb, $plugin_prefix, $gd_session;

    if ($_REQUEST['m_id'] != '') {
        $pid = (int)$_REQUEST['m_id'];
    } else {
        echo __('No marker data found', 'geodirectory');
        exit;
    }

    if (isset($_REQUEST['post_preview']) && $_REQUEST['post_preview'] != '' && $gd_ses_listing = $gd_session->get('listing')) {
        $post = (object)$gd_ses_listing;
        echo geodir_get_infowindow_html($post, $_REQUEST['post_preview']);
    } else {
        $geodir_post_type = get_post_type($pid);

        $table = $plugin_prefix . $geodir_post_type . '_detail';

        $sql = $wpdb->prepare("SELECT * FROM " . $table . " WHERE post_id = %d", array($pid));

        $postinfo = $wpdb->get_results($sql);

        $data_arr = array();

        if ($postinfo) {
            $srcharr = array("'", "/", "-", '"', '\\');
            $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '');

            foreach ($postinfo as $postinfo_obj) {
                echo geodir_get_infowindow_html($postinfo_obj);
            }
        }
    }
    exit;
}

/**
 * Retrive markers data to use in map
 *
 * @since 1.0.0
 * @since 1.5.7 Fixed non recurring events markers.
 * @since 1.6.1 Marker icons size added.
 *
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global array  $geodir_cat_icons Array of the category icon urls.
 * @global array  $gd_marker_sizes Array of the marker icons sizes.
 * 
 * @return string
 */
function get_markers() {
    
    global $wpdb, $plugin_prefix, $geodir_cat_icons, $gd_marker_sizes,$gd_session;


    /**
     * Filter to allow for any map caching to be output before queries.
     *
     * @since 1.6.22
     */
    $map_cache = apply_filters('geodir_get_markers_cache','');
    if($map_cache){
        return $map_cache;
        wp_die();
    }



    $search = '';

    $srcharr = array("'", "/", "-", '"', '\\', '&#39;');
    $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '', "&prime;");

    $post_type = isset($_REQUEST['gd_posttype']) ? $_REQUEST['gd_posttype'] : 'gd_place';

    $map_cat_ids_array = array('0');
    $cat_find_array = array(" FIND_IN_SET(%d, pd." . $post_type . "category)");


    $field_default_cat = '';
    if (isset($_REQUEST['cat_id']) && $_REQUEST['cat_id'] != '') {
        $map_cat_arr = trim($_REQUEST['cat_id'], ',');

        if (!empty($map_cat_arr)) {
            $field_default_cat .= "WHEN (default_category IN (" . $map_cat_arr . ")) THEN default_category ";

            $map_cat_ids_array = explode(',', $map_cat_arr);
            $cat_find_array = array();
            foreach ($map_cat_ids_array as $cat_id) {
                $field_default_cat .= "WHEN (FIND_IN_SET($cat_id, `" . $post_type . "category`) > 0) THEN $cat_id ";
                $cat_find_array[] = " FIND_IN_SET(%d, pd." . $post_type . "category)";
                $main_query_array[] = $cat_id;
            }

        }
    }

    if (!empty($field_default_cat))
        $field_default_cat = '';

    if (!empty($cat_find_array))
        $search .= "AND (" . implode(' OR ', $cat_find_array) . ")";

    $main_query_array = $map_cat_ids_array;
  
    if (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && $_REQUEST['search'] != __('Title', 'geodirectory')) {
        $search .= " AND p.post_title LIKE %s";
        $main_query_array[] = "%" . $_REQUEST['search'] . "%";
    }

    /**
     * Filter the marker query search SQL, values are replaces with %s or %d.
     *
     * @since 1.5.3
     *
     * @param string $search The SQL query for search/where.
     */
    $search = apply_filters('geodir_marker_search', $search);
    /**
     * Filter the marker query search SQL values %s and %d, this is an array of values.
     *
     * @since 1.5.3
     *
     * @param array $main_query_array The SQL query values for search/where.
     */
    $main_query_array = apply_filters('geodir_marker_main_query_array', $main_query_array);

    $gd_posttype = '';
    if (isset($_REQUEST['gd_posttype']) && $_REQUEST['gd_posttype'] != '') {
        $table = $plugin_prefix . $_REQUEST['gd_posttype'] . '_detail';
        $gd_posttype = " AND p.post_type = %s";
        $main_query_array[] = $_REQUEST['gd_posttype'];

    } else
        $table = $plugin_prefix . 'gd_place_detail';

    $join = ", " . $table . " AS pd ";

    /**
	 * Filter the SQL JOIN clause for the markers data
	 *
	 * @since 1.0.0
	 *
	 * @param string $join Row of SQL JOIN clause to join table.
	 */
	$join = apply_filters('geodir_home_map_listing_join', $join);
    
	/**
	 * Filter the searched fields for the markers data
	 *
	 * @since 1.0.0
	 *
	 * @param string $search Row of searched fields to use in WHERE clause.
	 */
	$search = apply_filters('geodir_home_map_listing_where', $search);
    $search = str_replace(array("'%", "%'"), array("'%%", "%%'"), $search);
    $cat_type = $post_type . 'category';
    if ($post_type == 'gd_event') {
        $event_select = ", pd.recurring_dates, pd.is_recurring";
    } else {
        $event_select = "";
    }

    $sql_select = 'SELECT pd.default_category, pd.' . $cat_type . ', pd.post_title, pd.post_id, pd.post_latitude, pd.post_longitude' . $event_select;
    /**
	 * Filter the SQL SELECT clause to retrive fields data
	 *
	 * @since 1.0.0
	 *
	 * @param string $sql_select Row of SQL SELECT clause.
	 */
	$select = apply_filters('geodir_home_map_listing_select', $sql_select);
	
	$groupby = " GROUP BY pd.post_id";
	/**
	 * Filter the SQL GROUP BY clause to retrive map markers data.
	 *
	 * @since 1.5.7
	 *
	 * @param string $groupby Row of SQL GROUP BY clause.
	 */
	$groupby = apply_filters('geodir_home_map_listing_groupby', $groupby);

    $catsql = $wpdb->prepare("$select $field_default_cat FROM " . $wpdb->posts . " as p" . $join . " WHERE p.ID = pd.post_id AND p.post_status = 'publish' " . $search . $gd_posttype . $groupby , $main_query_array);
    
	/**
	 * Filter the SQL query to retrive markers data
	 *
	 * @since 1.0.0
	 *
	 * @param string $catsql Row of SQL query.
	 * @param string $search Row of searched fields to use in WHERE clause.
	 */
	$catsql = apply_filters('geodir_home_map_listing_query', $catsql, $search);

//    global $gd_session;
//    print_r($gd_session);
//    print_r($_SESSION);

    $catinfo = $wpdb->get_results($catsql);
	
    $cat_content_info = array();
    $content_data = array();
    $post_ids = array();

    /**
     * Called before marker data is processed into JSON.
     *
     * Called before marker data is processed into JSON, this action can be used to change the format or add/remove markers.
     *
     * @since 1.5.3
     * @param object $catinfo The posts object containing all marker data.
     * @see 'geodir_after_marker_post_process'
     */
    $catinfo = apply_filters('geodir_before_marker_post_process', $catinfo);

    /**
     * Called before marker data is processed into JSON.
     *
     * Called before marker data is processed into JSON, this action can be used to change the format or add/remove markers.
     *
     * @since 1.4.9
     * @param object $catinfo The posts object containing all marker data.
     * @see 'geodir_after_marker_post_process'
     */
    do_action('geodir_before_marker_post_process_action', $catinfo);

    // Sort any posts into a ajax array
    if (!empty($catinfo)) {
        $geodir_cat_icons = geodir_get_term_icon();
        global $geodir_date_time_format, $geodir_date_format, $geodir_time_format;

        $today = strtotime(date_i18n('Y-m-d'));
        $show_dates = $post_type == 'gd_event' ? (int)get_option('geodir_event_infowindow_dates_count', 1) : 0;
        
        foreach ($catinfo as $catinfo_obj) {
            $post_title = $catinfo_obj->post_title;
            
            if ($post_type == 'gd_event' && !empty($catinfo_obj->recurring_dates) && $show_dates > 0) {
                $event_dates = '';
                $recurring_data = isset($catinfo_obj->recurring_dates) ? maybe_unserialize($catinfo_obj->recurring_dates) : array();

                $post_info = geodir_get_post_info($catinfo_obj->post_id);
                
                if (!empty($catinfo_obj->is_recurring) && !empty($recurring_data) && !empty($recurring_data['is_recurring']) && geodir_event_recurring_pkg($post_info)) {
                    $starttimes = '';
                    $endtimes = '';
                    $astarttimes = array();
                    $aendtimes = array();
                    if ( !isset( $recurring_data['repeat_type'] ) ) {
                        $recurring_data['repeat_type'] = 'custom';
                    }
                    $repeat_type = isset( $recurring_data['repeat_type'] ) && in_array( $recurring_data['repeat_type'], array( 'day', 'week', 'month', 'year', 'custom' ) ) ? $recurring_data['repeat_type'] : 'year'; // day, week, month, year, custom
                    $different_times = isset( $recurring_data['different_times'] ) && !empty( $recurring_data['different_times'] ) ? true : false;
        
                    $recurring_dates = explode(',', $recurring_data['event_recurring_dates']);
                    
                    if ( !empty( $recurring_dates ) ) {
                        if ( empty( $recurring_data['all_day'] ) ) {
                            if ( $repeat_type == 'custom' && $different_times ) {
                                $astarttimes = isset( $recurring_data['starttimes'] ) ? $recurring_data['starttimes'] : array();
                                $aendtimes = isset( $recurring_data['endtimes'] ) ? $recurring_data['endtimes'] : array();
                            } else {
                                $starttimes = isset( $recurring_data['starttime'] ) ? $recurring_data['starttime'] : '';
                                $endtimes = isset( $recurring_data['endtime'] ) ? $recurring_data['endtime'] : '';
                            }
                        }
                        
                        $e = 0;
                        foreach( $recurring_dates as $key => $date ) {
                            if ( $repeat_type == 'custom' && $different_times ) {
                                if ( !empty( $astarttimes ) && isset( $astarttimes[$key] ) ) {
                                    $starttimes = $astarttimes[$key];
                                    $endtimes = $aendtimes[$key];
                                } else {
                                    $starttimes = '';
                                    $endtimes = '';
                                }
                            }
                            
                            $duration = isset( $recurring_data['duration_x'] ) && (int)$recurring_data['duration_x'] > 0 ? (int)$recurring_data['duration_x'] : 1;
                            $duration--;
                            $enddate = date_i18n( 'Y-m-d', strtotime( $date . ' + ' . $duration . ' day' ) );
                            
                            // Hide past dates
                            if ( strtotime( $enddate ) < $today ) {
                                continue;
                            }
                                    
                            $sdate = strtotime( $date . ' ' . $starttimes );
                            $edate = strtotime( $enddate . ' ' . $endtimes );
                                        
                            $start_date = date_i18n( $geodir_date_time_format, $sdate );
                            $end_date = date_i18n( $geodir_date_time_format, $edate );
                            
                            $same_day = false;
                            $full_day = false;
                            $same_datetime = false;
                            
                            if ( $starttimes == $endtimes && ( $starttimes == '' || $starttimes == '00:00:00' || $starttimes == '00:00' ) ) {
                                $full_day = true;
                            }
                            
                            if ( $start_date == $end_date && $full_day ) {
                                $same_datetime = true;
                            }

                            $link_date = date_i18n( 'Y-m-d', $sdate );
                            $title_date = date_i18n( $geodir_date_format, $sdate );
                            if ( $full_day ) {
                                $start_date = $title_date;
                                $end_date = date_i18n( $geodir_date_format, $edate );
                            }
                            
                            if ( !$same_datetime && !$full_day && date_i18n( 'Y-m-d', $sdate ) == date_i18n( 'Y-m-d', $edate ) ) {
                                $same_day = true;
                                
                                $start_date .= ' - ' . date_i18n( $geodir_time_format, $edate );
                            }
                            
                            $event_dates .= ' :: ' . $start_date;
                        
                            if ( !$same_day && !$same_datetime ) {
                                $event_dates .= ' ' . __( 'to', 'geodirectory' ) . ' ' . $end_date;
                            }
                            
                            $e++;
                            
                            if ($show_dates > 0 && $e == $show_dates) { // only show 3 event dates
                                break;
                            }
                        }
                    }
                } else {
                    $start_date = isset( $recurring_data['event_start'] ) ? $recurring_data['event_start'] : '';
                    $end_date = isset( $recurring_data['event_end'] ) ? $recurring_data['event_end'] : $start_date;
                    $all_day = isset( $recurring_data['all_day'] ) && !empty( $recurring_data['all_day'] ) ? true : false;
                    $starttime = isset( $recurring_data['starttime'] ) ? $recurring_data['starttime'] : '';
                    $endtime = isset( $recurring_data['endtime'] ) ? $recurring_data['endtime'] : '';
                
                    $event_recurring_dates = explode( ',', $recurring_data['event_recurring_dates'] );
                    $starttimes = isset( $recurring_data['starttimes'] ) && !empty( $recurring_data['starttimes'] ) ? $recurring_data['starttimes'] : array();
                    $endtimes = isset( $recurring_data['endtimes'] ) && !empty( $recurring_data['endtimes'] ) ? $recurring_data['endtimes'] : array();
                    
                    if ( !geodir_event_is_date( $start_date ) && !empty( $event_recurring_dates ) ) {
                        $start_date = $event_recurring_dates[0];
                    }
                                
                    if ( strtotime( $end_date ) < strtotime( $start_date ) ) {
                        $end_date = $start_date;
                    }
                    
                    if ($end_date != '' && strtotime($end_date) >= $today) {
                        if ( $starttime == '' && !empty( $starttimes ) ) {
                            $starttime = $starttimes[0];
                            $endtime = $endtimes[0];
                        }
                        
                        $same_day = false;
                        $one_day = false;
                        if ( $start_date == $end_date && $all_day ) {
                            $one_day = true;
                        }

                        if ( $all_day ) {
                            $start_datetime = strtotime( $start_date );
                            $end_datetime = strtotime( $end_date );
                            
                            $start_date = date_i18n( $geodir_date_format, $start_datetime );
                            $end_date = date_i18n( $geodir_date_format, $end_datetime );
                            if ( $start_date == $end_date ) {
                                $one_day = true;
                            }
                        } else {
                            if ( $start_date == $end_date && $starttime == $endtime ) {
                                $end_date = date_i18n( 'Y-m-d', strtotime( $start_date . ' ' . $starttime . ' +1 day' ) );
                                $one_day = false;
                            }
                            $start_datetime = strtotime( $start_date . ' ' . $starttime );
                            $end_datetime = strtotime( $end_date . ' ' . $endtime );
                            
                            $start_date = date_i18n( $geodir_date_time_format, $start_datetime );
                            $end_date = date_i18n( $geodir_date_time_format, $end_datetime );
                        }

                        if ( !$one_day && date_i18n( 'Y-m-d', $start_datetime ) == date_i18n( 'Y-m-d', $end_datetime ) ) {
                            $same_day = true;
                            
                            $start_date .= ' - ' . date_i18n( $geodir_time_format, $end_datetime );
                        }
                        
                        $event_dates .= ' :: ' . $start_date;
                        
                        if ( !$same_day && !$one_day ) {
                            $event_dates .= ' ' . __( 'to', 'geodirectory' ) . ' ' . $end_date;
                        }
                    }
                }

                if (empty($event_dates)) {
                    continue;
                }
                
                $post_title .= $event_dates;
            }

            $map_cat_ids_array;
            $default_cat = isset($catinfo_obj->default_category) ? $catinfo_obj->default_category : '';

            // if single cat lets just show that icon
            if(is_array($map_cat_ids_array) && count($map_cat_ids_array)==1){
                $default_cat = (int)$map_cat_ids_array[0];
            }

            $icon = !empty($geodir_cat_icons) && isset($geodir_cat_icons[$default_cat]) ? $geodir_cat_icons[$default_cat] : '';
            $mark_extra = (isset($catinfo_obj->marker_extra)) ? $catinfo_obj->marker_extra : '';
            $title = str_replace($srcharr, $replarr, $post_title);
            
            if ($icon != '') {
                $gd_marker_sizes = empty($gd_marker_sizes) ? array() : $gd_marker_sizes;
                
                if (isset($gd_marker_sizes[$icon])) {
                    $icon_size = $gd_marker_sizes[$icon];
                } else {
                    $icon_size = geodir_get_marker_size($icon);
                    $gd_marker_sizes[$icon] = $icon_size;
                }               
            } else {
                $icon_size = array('w' => 36, 'h' => 45);
            }

            $content_data[] = '{"id":"' . $catinfo_obj->post_id . '","t": "' . $title . '","lt": "' . $catinfo_obj->post_latitude . '","ln": "' . $catinfo_obj->post_longitude . '","mk_id":"' . $catinfo_obj->post_id . '_' . $default_cat . '","i":"' . $icon . '","w":"' . $icon_size['w'] . '","h":"' . $icon_size['h'] . '"'.$mark_extra.'}';
            $post_ids[] = $catinfo_obj->post_id;
        }
    }

    /**
     * Called after marker data is processed into JSON.
     *
     * Called after marker data is processed into JSON, this action can be used to change the format or add/remove markers.
     *
     * @since 1.4.9
     * @param array $content_data The array containing all markers in JSON format.
     * @param object $catinfo The posts object containing all marker data.
     * @see 'geodir_before_marker_post_process'
     */
    do_action('geodir_after_marker_post_process', $content_data, $catinfo);

    if (!empty($content_data)) {
        $cat_content_info[] = implode(',', $content_data);
    }

    $totalcount = count(array_unique($post_ids));

    if (!empty($cat_content_info)) {
        $map_json = '[{"totalcount":"' . $totalcount . '",' . substr(implode(',', $cat_content_info), 1) . ']';
    }
    else {
        $map_json =  '[{"totalcount":"0"}]';
    }


    /**
     * Filter the marker json return.
     *
     * @since 1.6.22
     * @param string $map_json The JSON string of the map markers results.
     */
    return apply_filters('geodir_markers_json',$map_json);
}


