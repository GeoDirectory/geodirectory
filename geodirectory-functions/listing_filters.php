<?php
/**
 * Contains all function for filtering listing.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
 
/**
 * Starts session if not started.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global bool $geodir_add_location_url If true it will add location name in url.
 * @deprecated 1.5.7 $_SESSION no longer used.
 */
function geodir_session_start()
{
    if (!session_id()) session_start();
    global $geodir_add_location_url;

    $geodir_add_location_url = NULL;
}

/**
 * Set geodir page variable in WP_Query instance.
 *
 * @since 1.0.0
 * @package GeoDirectory
 *
 * @param WP_Query $query The WP_Query instance.
 * @return WP_Query
 */
function geodir_modified_query($query)
{
    if ($query->is_main_query() && (
            (geodir_is_page('add-listing') && isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
            || geodir_is_page('listing')
            || geodir_is_page('author')
            || geodir_is_page('search')
            || geodir_is_page('detail'))
    ) {

        $query->set('is_geodir_loop', true);
    }

    return $query;
}

/**
 * Sets the location request parameters.
 *
 * @since 1.0.0
 *
 * @global object $wp_query WordPress Query object.
 * @global object $wpdb WordPress Database object.
 * @global string $geodir_post_type The post type.
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 * @global string $table Listing table name.
 * @global float $dist Distance value to be filtered.
 * @global string $mylat Current latitude.
 * @global string $mylon Current longitude.
 * @global string $s Search keyword.
 * @global string $snear Nearest location to search.
 * @global string $s_A Extra parameters.
 * @global string $s_SA Extra parameters.
 */
function set_listing_request()
{
    global $wp_query, $wpdb, $geodir_post_type, $table, $dist, $mylat, $mylon, $s, $snear, $s, $s_A, $s_SA;


    // fix woocommerce shop products filtered by language for GD + WPML + Woocommerce
    if (!geodir_is_geodir_page()) {
        return;
    }

    /* remove all pre filters */
    remove_all_filters('query');
    remove_all_filters('posts_search');
    remove_all_filters('posts_fields');
    remove_all_filters('posts_join');
    remove_all_filters('posts_orderby');
    remove_all_filters('posts_where');


    if ((is_search() && isset($_REQUEST['geodir_search']) && $_REQUEST['geodir_search'] != '')):

        if (isset($_REQUEST['scat']) && $_REQUEST['scat'] == 'all') $_REQUEST['scat'] = '';
        //if(isset($_REQUEST['s']) && $_REQUEST['s'] == '+') $_REQUEST['s'] = '';

        if (isset($_REQUEST['sdist'])) {
            ($_REQUEST['sdist'] != '0' && $_REQUEST['sdist'] != '') ? $dist = esc_attr($_REQUEST['sdist']) : $dist = 25000;
        } elseif (get_option('geodir_search_dist') != '') {
            $dist = get_option('geodir_search_dist');

        } else {
            $dist = 25000;
        } //  Distance

        if (isset($_REQUEST['sgeo_lat'])) {
            $mylat = (float)esc_attr($_REQUEST['sgeo_lat']);
        } else {
            $mylat = (float)geodir_get_current_city_lat();
        } //  Latatude

        if (isset($_REQUEST['sgeo_lon'])) {
            $mylon = (float)esc_attr($_REQUEST['sgeo_lon']);
        } else {
            $mylon = (float)geodir_get_current_city_lng();
        } //  Distance

        if (isset($_REQUEST['snear'])) {
            $snear = trim(esc_attr($_REQUEST['snear']));
        }

        if (isset($_REQUEST['s'])) {
            $s = trim(esc_attr(wp_strip_all_tags($_REQUEST['s'])));
        }

        if ($snear == 'NEAR ME') {
            $ip = $_SERVER['REMOTE_ADDR'];
            $addr_details = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
            $mylat = stripslashes(ucfirst($addr_details[geoplugin_latitude]));
            $mylon = stripslashes(ucfirst($addr_details[geoplugin_longitude]));
        }


        if (strstr($s, ',')) {
            $s_AA = str_replace(" ", "", $s);
            $s_A = explode(",", $s_AA);
            $s_A = implode('","', $s_A);
            $s_A = '"' . $s_A . '"';
        } else {
            $s_A = '"' . $s . '"';
        }

        if (strstr($s, ' ')) {
            $s_SA = explode(" ", $s);
        } else {
            $s_SA = '';
        }

    endif;



}


/**
 * GeoDirectory Listing loop filters.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global string $table Listing table name.
 * @todo $wp_query declared twice - fix it.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param object $query Current query object.
 * @return object Modified query object.
 */
function geodir_listing_loop_filter($query)
{
    global $wp_query, $geodir_post_type, $table, $plugin_prefix, $table, $term;

    // fix wp_reset_query for popular post view widget
    if (!geodir_is_geodir_page()) {
        return;
    }

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
                //print_r($wp_query) ;
            }
        }

    }
    if (isset($query->query_vars['is_geodir_loop']) && $query->query_vars['is_geodir_loop']) {

        $table = $plugin_prefix . $geodir_post_type . '_detail';

        add_filter('posts_fields', 'geodir_posts_fields', 1);
        add_filter('posts_join', 'geodir_posts_join', 1);
        geodir_post_where();
        if (!is_admin())
            add_filter('posts_orderby', 'geodir_posts_orderby', 1);

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
 * Listing fields filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $table Listing table name.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param string $fields Fields query string.
 * @return string Modified fields query string.
 */
function geodir_posts_fields($fields) {
    global $wp_query, $wpdb, $geodir_post_type, $table, $plugin_prefix, $dist, $mylat, $mylon, $snear, $gd_session;

    // Filter-Location-Manager to add location table.
    $fields .= ", " . $table . ".* ";
    
	if ($snear != '' || $gd_session->get('all_near_me')) {
        $DistanceRadius = geodir_getDistanceRadius(get_option('geodir_search_dist_1'));
        
		if ($gd_session->get('all_near_me')) {
            $mylat = $gd_session->get('user_lat');
            $mylon = $gd_session->get('user_lon');
        }

        $fields .= " , (" . $DistanceRadius . " * 2 * ASIN(SQRT( POWER(SIN((ABS($mylat) - ABS(" . $table . ".post_latitude)) * pi()/180 / 2), 2) +COS(ABS($mylat) * pi()/180) * COS( ABS(" . $table . ".post_latitude) * pi()/180) *POWER(SIN(($mylon - " . $table . ".post_longitude) * pi()/180 / 2), 2) )))as distance ";
    }

    global $s;
    if (is_search() && isset($_REQUEST['geodir_search']) && $s && trim($s) != '') {
        $keywords = explode(" ", $s);

        if(is_array($keywords) && $klimit = get_option('geodir_search_word_limit')){
            foreach($keywords as $kkey=>$kword){
                if(mb_strlen($kword, 'UTF-8')<=$klimit){
                    unset($keywords[$kkey]);
                }
            }
        }


        if (count($keywords) > 1) {
            $parts = array(
                'AND' => 'gd_alltitlematch_part',
                'OR' => 'gd_titlematch_part'
            );
            $gd_titlematch_part = "";
            foreach ($parts as $key => $part) {
                $gd_titlematch_part .= " CASE WHEN ";
                $count = 0;
                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);
                    $keyword  = wp_specialchars_decode($keyword ,ENT_QUOTES);
					$count++;
                    if ($count < count($keywords)) {
                       // $gd_titlematch_part .= $wpdb->posts . ".post_title LIKE '%%" . $keyword . "%%' " . $key . " ";
						$gd_titlematch_part .= "( " . $wpdb->posts . ".post_title LIKE '" . $keyword . "' OR " . $wpdb->posts . ".post_title LIKE '" . $keyword . "%%' OR " . $wpdb->posts . ".post_title LIKE '%% " . $keyword . "%%' ) " . $key . " ";
                    } else {
                        //$gd_titlematch_part .= $wpdb->posts . ".post_title LIKE '%%" . $keyword . "%%' ";
						$gd_titlematch_part .= "( " . $wpdb->posts . ".post_title LIKE '" . $keyword . "' OR " . $wpdb->posts . ".post_title LIKE '" . $keyword . "%%' OR " . $wpdb->posts . ".post_title LIKE '%% " . $keyword . "%%' ) ";
                    }
                }
                $gd_titlematch_part .= "THEN 1 ELSE 0 END AS " . $part . ",";
            }
        } else {
            $gd_titlematch_part = "";
        }
        $s = stripslashes_deep( $s );
        $s = wp_specialchars_decode($s,ENT_QUOTES);
		$fields .= $wpdb->prepare(", CASE WHEN " . $table . ".is_featured='1' THEN 1 ELSE 0 END AS gd_featured, CASE WHEN " . $wpdb->posts . ".post_title LIKE %s THEN 1 ELSE 0 END AS gd_exacttitle," . $gd_titlematch_part . " CASE WHEN ( " . $wpdb->posts . ".post_title LIKE %s OR " . $wpdb->posts . ".post_title LIKE %s OR " . $wpdb->posts . ".post_title LIKE %s ) THEN 1 ELSE 0 END AS gd_titlematch, CASE WHEN ( " . $wpdb->posts . ".post_content LIKE %s OR " . $wpdb->posts . ".post_content LIKE %s OR " . $wpdb->posts . ".post_content LIKE %s OR " . $wpdb->posts . ".post_content LIKE %s ) THEN 1 ELSE 0 END AS gd_content", array($s, $s, $s . '%', '% ' . $s . '%', $s, $s . ' %', '% ' . $s . ' %', '% ' . $s));
    }

    return $fields;
}


/**
 * Listing tables join filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $table Listing table name.
 * @return string Modified join query.
 */
function geodir_posts_join($join)
{
    global $wpdb, $geodir_post_type, $table, $table_prefix, $plugin_prefix;

    ########### WPML ###########

    if (function_exists('icl_object_id')) {
        global $sitepress;
        $lang_code = ICL_LANGUAGE_CODE;
        $default_lang_code = $sitepress->get_default_language();
        if ($lang_code) {
            $join .= "JOIN " . $table_prefix . "icl_translations icl_t ON icl_t.element_id = " . $table_prefix . "posts.ID";
        }

    }
    ########### WPML ###########

    $join .= " INNER JOIN " . $table . " ON (" . $table . ".post_id = $wpdb->posts.ID)  ";
    //===old code start
    //$join .= " INNER JOIN ".POST_LOCATION_TABLE." ON (".$table.".post_location_id = ".POST_LOCATION_TABLE.".location_id)  " ;//===old code end

    return $join;
}


/**
 * Listing orderby filters.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $wp_query WordPress Query object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $table Listing table name.
 * @param string $orderby The orderby query string.
 * @return string Modified orderby query.
 */
function geodir_posts_orderby($orderby)
{
    global $wpdb, $wp_query, $geodir_post_type, $table, $plugin_prefix, $snear, $default_sort;

    $sort_by = '';
    $orderby = ' ';

    if (get_query_var('order_by'))
        $sort_by = get_query_var('order_by');

    /*if(isset($wp_query->tax_query->queries) && $wp_query->tax_query->queries){
        $current_term = $wp_query->get_queried_object();
    }

    if(isset($current_term->term_id)){

        $current_term->term_id;

        if(get_tax_meta($current_term->term_id,'ct_cat_sort')){
            $sort_by = get_tax_meta($current_term->term_id,'ct_cat_sort');
        }
    }*/


    if ($snear != '') {
        $orderby .= " distance,";
    }

    if (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '' && is_main_query())
        $sort_by = esc_attr($_REQUEST['sort_by']);


    if ($sort_by == '') {
        $default_sort = geodir_get_posts_default_sort($geodir_post_type);
        if (!empty($default_sort))
            $sort_by = $default_sort;
    }

    /*
    if search by term & no location then order always "relevance"
    if search by location then order always "nearest"
    */
    if (is_main_query() && geodir_is_page('search')) {
        $search_term = get_query_var('s');

        if (trim($search_term) != '' && !isset($_REQUEST['sort_by'])) {
            $sort_by = 'az';
        }

        if ($snear != '') {
            $sort_by = 'nearest';
        }
    }

    switch ($sort_by):
        case 'newest':
            $orderby = "$wpdb->posts.post_date desc, ";
            break;
        case 'oldest':
            $orderby = "$wpdb->posts.post_date asc, ";
            break;
        case 'low_review':
        case 'rating_count_asc':
            $orderby = $table . ".rating_count ASC, " . $table . ".overall_rating ASC, ";
            break;
        case 'high_review':
        case 'rating_count_desc':
			$orderby = $table . ".rating_count DESC, " . $table . ".overall_rating DESC, ";
            break;
        case 'low_rating':
            $orderby = "( " . $table . ".overall_rating  ) ASC, " . $table . ".rating_count ASC,  ";
            break;
        case 'high_rating':
            $orderby = " " . $table . ".overall_rating DESC, " . $table . ".rating_count DESC, ";
            break;
        case 'featured':
            $orderby = $table . ".is_featured asc, ";
            break;
        case 'nearest':
            $orderby = " distance asc, ";
            break;
        case 'farthest':
            $orderby = " distance desc, ";
            break;
        case 'random':
            $orderby = " rand(), ";
            break;
        case 'az':
            $orderby = "$wpdb->posts.post_title asc, ";
            break;
        default:

            break;
    endswitch;

    global $s;

    if (is_search() && isset($_REQUEST['geodir_search']) && $s && trim($s) != '') {
        $keywords = explode(" ", $s);
        if(is_array($keywords) && $klimit = get_option('geodir_search_word_limit')){
            foreach($keywords as $kkey=>$kword){
                if(mb_strlen($kword, 'UTF-8')<=$klimit){
                    unset($keywords[$kkey]);
                }
            }
        }
        if ($sort_by == 'nearest' || $sort_by == 'farthest') {
            if (count($keywords) > 1) {
                $orderby = $orderby . " ( gd_titlematch * 2 + gd_featured * 5 + gd_exacttitle * 10 + gd_alltitlematch_part * 100 + gd_titlematch_part * 50 + gd_content * 1.5) DESC, ";
            } else {
                $orderby = $orderby . " ( gd_titlematch * 2 + gd_featured * 5 + gd_exacttitle * 10 + gd_content * 1.5) DESC, ";
            }
        } else {
            if (count($keywords) > 1) {
                $orderby = "( gd_titlematch * 2 + gd_featured * 5 + gd_exacttitle * 10 + gd_alltitlematch_part * 100 + gd_titlematch_part * 50 + gd_content * 1.5) DESC, " . $orderby;
            } else {
                $orderby = "( gd_titlematch * 2 + gd_featured * 5 + gd_exacttitle * 10 + gd_content * 1.5) DESC, " . $orderby;
            }
        }
    }

    /**
     * Filter order by SQL.
     *
     * @since 1.0.0
     * @param string $orderby The orderby query string.
     * @param string $sort_by Sortby query string.
     * @param string $table Listing table name.
     */
    $orderby = apply_filters('geodir_posts_order_by_sort', $orderby, $sort_by, $table);

    $orderby .= $table . ".is_featured asc, $wpdb->posts.post_date desc, $wpdb->posts.post_title ";

    return $orderby;
}


/**
 * Listing orderby custom sort.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $orderby The orderby query string.
 * @param string $sort_by Sortby query string.
 * @param string $table Listing table name.
 * @return string Modified orderby query.
 */
function geodir_posts_order_by_custom_sort($orderby, $sort_by, $table)
{

    global $wpdb;

    if ($sort_by != '' && !is_search()) {

        $sort_array = explode('_', $sort_by);

        $sort_by_count = count($sort_array);

        $order = $sort_array[$sort_by_count - 1];

        if ($sort_by_count > 1 && ($order == 'asc' || $order == 'desc')) {

            $sort_by = str_replace('_' . $order, '', $sort_by);

            switch ($sort_by):

                case 'post_date':
                case 'comment_count':

                    $orderby = "$wpdb->posts." . $sort_by . " " . $order . ", ".$table . ".overall_rating " . $order . ", ";
                    break;

                case 'distance':
                    $orderby = $sort_by . " " . $order . ", ";
                    break;


                // sort by rating
                case 'overall_rating':
                    $orderby = " " . $table . "." . $sort_by . "  " . $order . ", " . $table . ".rating_count " . $order . ", ";

                    break;


                default:
                    if (geodir_column_exist($table, $sort_by)) {
						$orderby = $table . "." . $sort_by . " " . $order . ", ";
					}
                    break;

            endswitch;

        }

    }

    return $orderby;
}

/**
 * Listing where filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table Listing table name.
 */
function geodir_post_where()
{


    global $wpdb, $geodir_post_type, $table, $s, $snear;

    if (!is_admin()) {

        if (geodir_is_page('add-listing') && isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            add_filter('posts_where', 'geodir_edit_listing_where', 1);

        } elseif ((is_search() && $_REQUEST['geodir_search'])) {

            add_filter('posts_where', 'searching_filter_where', 1);

            if ($snear != '')
                add_filter('posts_where', 'searching_filter_where', 1);

            add_filter('posts_orderby', 'geodir_posts_orderby', 1);

        } elseif (geodir_is_page('author')) {

            add_filter('posts_where', 'author_filter_where', 1);

        }

        //if (!geodir_is_page('detail'))
            add_filter('posts_where', 'geodir_default_where', 1);/**/

        //add_filter( 'user_has_cap', 'geodir_preview_post_cap', 10, 3 );// let subscribers edit their own posts

    }
}

/**
 * Let subscribers edit their own posts.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $allcaps An array of all the role's capabilities.
 * @param array $caps Actual capabilities for meta capability.
 * @param array $args Optional parameters passed to has_cap(), typically object ID.
 * @return array Modified capabilities array.
 */
function geodir_preview_post_cap($allcaps, $caps, $args)
{
    $user_id = get_current_user_id();
    if ($user_id && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] != '' && isset($_REQUEST['p']) && $_REQUEST['p'] != '' && $args[0] == 'edit_post' && $_REQUEST['p'] == $args[2]) {

        $allcaps['edit_posts'] = true;
    }
    //print_r($allcaps);
    return $allcaps;
}


/**
 * Edit Listing where filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $where The where query string.
 * @return string Modified where query string.
 */
function geodir_edit_listing_where($where)
{
    global $wpdb;
    $where = $wpdb->prepare(" AND $wpdb->posts.ID = %d ", $_REQUEST['pid']);
    return $where;
}


/**
 * Listing location where filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 * @param string $where The where query string.
 * @return string Modified where query string.
 */
function geodir_default_where($where)
{
    global $wp_query, $wpdb;

    //print_r($wp_query);
    ########### WPML ###########

    if (function_exists('icl_object_id')) {
        global $sitepress, $table_prefix;
        $lang_code = ICL_LANGUAGE_CODE;
        $default_lang_code = $sitepress->get_default_language();
        $q_post_type = isset($wp_query->query['post_type']) ? $wp_query->query['post_type'] : '';
        //echo '##########'.$q_post_type;
        if ($lang_code && $q_post_type) {
            $where .= " AND icl_t.language_code = '$lang_code' AND icl_t.element_type IN('post_" . $q_post_type . "') ";
            //$where .= " AND icl_t.language_code = '$lang_code' ";
        }

    }
    ########### WPML ###########


    return $where = str_replace("0 = 1", "1=1", $where);

    /* ====== old code start ===
    $where = str_replace("0 = 1", "1=1", $where);
    $country = get_query_var('gd_country');
    $region = get_query_var('gd_region');
    $city = get_query_var('gd_city');
    $neighbourhood = get_query_var('gd_neighbourhood');


    if($country != '')
        $where .= " AND ".POST_LOCATION_TABLE.".country_slug = '".$country."' ";

    if($region != '')
        $where .= " AND ".POST_LOCATION_TABLE.".region_slug = '".$region."' ";

    if($city != '')
        $where .= " AND ".POST_LOCATION_TABLE.".city_slug = '".$city."' ";

    if($neighbourhood != '')
        $where .= " AND ".$table.".post_neighbourhood = '".$neighbourhood."' ";

    return $where;
    /* === old code end ===*/

}


/**
 * Listing search where filter.
 *
 * @since 1.0.0
 * @since 1.5.4 Modified to fix unable to find listings for a particular keyword in the content.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global string $table Listing table name.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param string $where The where query string.
 * @return string Modified where query string.
 */
function searching_filter_where($where) {
    global $wpdb, $geodir_post_type, $table, $plugin_prefix, $dist, $mylat, $mylon, $s, $snear, $s_A, $s_SA, $search_term, $gd_session;
	
    $search_term = 'OR';
    $search_term = 'AND';
    $geodir_custom_search = '';
    $category_search_range = '';

    if (is_single() && get_query_var('post_type')) {
		return $where;
	}

    if (is_tax()) {
		return $where;
	}
	
	$s = trim($s);
    $s  = wp_specialchars_decode($s ,ENT_QUOTES);
    $s_A = wp_specialchars_decode($s_A ,ENT_QUOTES);

    $where = '';
    $better_search_terms = '';
    if (isset($_REQUEST['stype']))
        $post_types = esc_attr(wp_strip_all_tags($_REQUEST['stype']));
    else
        $post_types = 'gd_place';

    if ($s != '') {
        $keywords = explode(" ", $s);
        if(is_array($keywords) && $klimit = get_option('geodir_search_word_limit')){
            foreach($keywords as $kkey=>$kword){
                if(mb_strlen($kword, 'UTF-8')<=$klimit){
                    unset($keywords[$kkey]);
                }
            }
        }

        if (!empty($keywords)) {
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                $keyword  = wp_specialchars_decode($keyword ,ENT_QUOTES);
                if ($keyword != '') {
                    /**
                     * Filter the search query keywords SQL.
                     *
                     * @since 1.5.9
                     * @package GeoDirectory
                     * @param string $better_search_terms The query values, default: `' OR ( ' . $wpdb->posts . '.post_title LIKE "' . $keyword . '" OR ' . $wpdb->posts . '.post_title LIKE "' . $keyword . '%" OR ' . $wpdb->posts . '.post_title LIKE "% ' . $keyword . '%" )'`.
                     * @param array $keywords The array of keywords for the query.
                     * @param string $keyword The single keyword being searched.
                     */
					$better_search_terms .= apply_filters("geodir_search_better_search_terms",' OR ( ' . $wpdb->posts . '.post_title LIKE "' . $keyword . '" OR ' . $wpdb->posts . '.post_title LIKE "' . $keyword . '%" OR ' . $wpdb->posts . '.post_title LIKE "% ' . $keyword . '%" )',$keywords,$keyword);
                }
            }
        }
    }

    /* get taxonomy */
    $taxonomies = geodir_get_taxonomies($post_types, true);
    if($taxonomies) {
        $taxonomies = implode("','", $taxonomies);
        $taxonomies = "'" . $taxonomies . "'";
    }else{$taxonomies='';}

    $content_where = $terms_where = '';
	if ($s != '') {
        /**
         * Filter the search query content where values.
         *
         * @since 1.5.0
         * @package GeoDirectory
         * @param string $content_where The query values, default: `" OR ($wpdb->posts.post_content LIKE \"$s\" OR $wpdb->posts.post_content LIKE \"$s%\" OR $wpdb->posts.post_content LIKE \"% $s%\" OR $wpdb->posts.post_content LIKE \"%>$s%\" OR $wpdb->posts.post_content LIKE \"%\n$s%\") ") "`.
         */
		$content_where = apply_filters("geodir_search_content_where"," OR ($wpdb->posts.post_content LIKE \"$s\" OR $wpdb->posts.post_content LIKE \"$s%\" OR $wpdb->posts.post_content LIKE \"% $s%\" OR $wpdb->posts.post_content LIKE \"%>$s%\" OR $wpdb->posts.post_content LIKE \"%\n$s%\") ");
        /**
         * Filter the search query term values.
         *
         * @since 1.5.0
         * @package GeoDirectory
         * @param string $terms_where The separator, default: `" AND ($wpdb->terms.name LIKE \"$s\" OR $wpdb->terms.name LIKE \"$s%\" OR $wpdb->terms.name LIKE \"% $s%\" OR $wpdb->terms.name IN ($s_A)) "`.
         */
        $terms_where = apply_filters("geodir_search_terms_where"," AND ($wpdb->terms.name LIKE \"$s\" OR $wpdb->terms.name LIKE \"$s%\" OR $wpdb->terms.name LIKE \"% $s%\" OR $wpdb->terms.name IN ($s_A)) ");
	}
		
    if ($snear != '') {


        if (is_numeric($gd_session->get('near_me_range')) && !isset($_REQUEST['sdist'])) {
            $dist = $gd_session->get('near_me_range');
        }
        $lon1 = $mylon - $dist / abs(cos(deg2rad($mylat)) * 69);
        $lon2 = $mylon + $dist / abs(cos(deg2rad($mylat)) * 69);
        $lat1 = $mylat - ($dist / 69);
        $lat2 = $mylat + ($dist / 69);

        $rlon1 = is_numeric(min($lon1, $lon2)) ? min($lon1, $lon2) : '';
        $rlon2 = is_numeric(max($lon1, $lon2)) ? max($lon1, $lon2) : '';
        $rlat1 = is_numeric(min($lat1, $lat2)) ? min($lat1, $lat2) : '';
        $rlat2 = is_numeric(max($lat1, $lat2)) ? max($lat1, $lat2) : '';



	    $where .= " AND ( ( $wpdb->posts.post_title LIKE \"$s\" $better_search_terms)
			                    $content_where 
								OR ($wpdb->posts.ID IN( 
										SELECT $wpdb->term_relationships.object_id as post_id 
										FROM $wpdb->term_taxonomy,  $wpdb->terms, $wpdb->term_relationships 
										WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id
										AND $wpdb->term_relationships.term_taxonomy_id =  $wpdb->term_taxonomy.term_taxonomy_id
										AND $wpdb->term_taxonomy.taxonomy in ({$taxonomies})
										$terms_where 
										)
									) 
							)
						AND $wpdb->posts.post_type in ('{$post_types}')
						AND ($wpdb->posts.post_status = 'publish')
						AND ( " . $table . ".post_latitude between $rlat1 and $rlat2 )
						AND ( " . $table . ".post_longitude between $rlon1 and $rlon2 ) ";

        if (isset($_REQUEST['sdist']) && $_REQUEST['sdist'] != 'all') {
            $DistanceRadius = geodir_getDistanceRadius(get_option('geodir_search_dist_1'));
            $where .= " AND CONVERT((" . $DistanceRadius . " * 2 * ASIN(SQRT( POWER(SIN((ABS($mylat) - ABS(" . $table . ".post_latitude)) * pi()/180 / 2), 2) +COS(ABS($mylat) * pi()/180) * COS( ABS(" . $table . ".post_latitude) * pi()/180) *POWER(SIN(($mylon - " . $table . ".post_longitude) * pi()/180 / 2), 2) ))),DECIMAL(64,4)) <= " . $dist;
        }

    } else {
        $where .= " AND (	( $wpdb->posts.post_title LIKE \"$s\" $better_search_terms)
                            $content_where  
							OR ( $wpdb->posts.ID IN(	
									SELECT $wpdb->term_relationships.object_id as post_id                     
									FROM $wpdb->term_taxonomy,  $wpdb->terms, $wpdb->term_relationships
								WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id
								AND $wpdb->term_relationships.term_taxonomy_id =  $wpdb->term_taxonomy.term_taxonomy_id
								AND $wpdb->term_taxonomy.taxonomy in ( {$taxonomies} )
								$terms_where 
								)
						) 
					) 
				AND $wpdb->posts.post_type in ('$post_types')
				AND ($wpdb->posts.post_status = 'publish') ";
    }
	
	########### WPML ###########
    if ( function_exists( 'icl_object_id' ) ) {       
		$lang_code = ICL_LANGUAGE_CODE;
		
		if ($lang_code && $post_types) {
            $where .= " AND icl_t.language_code = '".$lang_code."' AND icl_t.element_type IN('post_" . $post_types . "') ";
        }
    }
    ########### WPML ###########
	
    return $where;
}


/**
 * Where filter for author listing.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table Listing table name.
 * @param string $where The where query string.
 * @return string Modified where query string.
 */
function author_filter_where($where) {
    global $wpdb, $geodir_post_type, $table, $curr;

    $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
    $user_id = !empty($curauth->ID) ? (int)$curauth->ID : 0;
    
    if (isset($_REQUEST['stype'])) {
        $where = $wpdb->prepare(" AND $wpdb->posts.post_type IN (%s) ",$_REQUEST['stype']);
    } else {
        $where = " AND $wpdb->posts.post_type IN ('gd_place') ";
    }

    if ($user_id > 0) {
        if (isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite') {
            $user_fav_posts = get_user_meta($user_id, 'gd_user_favourite_post', true);
            $user_fav_posts = !empty($user_fav_posts) && is_array($user_fav_posts) ? implode("','", $user_fav_posts) : '-1';
            $where .= " AND $wpdb->posts.ID IN ('$user_fav_posts')";
        } else
            $where .= " AND $wpdb->posts.post_author = $user_id";

        if ($user_id == (int)get_current_user_id()) {
            $where .= " AND $wpdb->posts.post_status IN ('publish','draft','pending') ";
        } else {
            $where .= " AND $wpdb->posts.post_status = 'publish' ";
        }
    } else {
        $where .= " AND $wpdb->posts.post_author = '-1' AND $wpdb->posts.post_status = 'publish' ";
    }

    ########### WPML ###########
    if (function_exists('icl_object_id')) {
        $lang_code = ICL_LANGUAGE_CODE;
        if ($lang_code) {
            $where .= " AND icl_t.language_code='" . $lang_code . "' ";
        }

    }
    ########### WPML ###########
    
    return $where;
}

/**
 * advanced join filter for popular post view widget.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global string $table Listing table name.
 * @param string $join The join query.
 * @return string Modified join query.
 */
function geodir_filter_widget_join($join)
{
    global $wp_query, $table;
    if (!empty($wp_query->query['with_pics_only'])) {
        $join .= " LEFT JOIN " . GEODIR_ATTACHMENT_TABLE . " ON ( " . GEODIR_ATTACHMENT_TABLE . ".post_id=" . $table . ".post_id AND " . GEODIR_ATTACHMENT_TABLE . ".mime_type LIKE '%image%' )";
    }
    return $join;
}

/**
 * advanced where filter for popular post view widget.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global string $table Listing table name.
 * @param string $where The where query string.
 * @return string Modified where query string.
 */
function geodir_filter_widget_where($where)
{
    global $wp_query, $table;
    if (!empty($wp_query->query['show_featured_only'])) {
        $where .= " AND " . $table . ".is_featured = '1'";
    }
    if (!empty($wp_query->query['show_special_only'])) {
        $where .= " AND ( " . $table . ".geodir_special_offers != '' AND " . $table . ".geodir_special_offers IS NOT NULL )";
    }
    if (!empty($wp_query->query['with_pics_only'])) {
        $where .= " AND " . GEODIR_ATTACHMENT_TABLE . ".ID IS NOT NULL GROUP BY " . $table . ".post_id";
    }
    if (!empty($wp_query->query['with_videos_only'])) {
        $where .= " AND ( " . $table . ".geodir_video != '' AND " . $table . ".geodir_video IS NOT NULL )";
    }
    return $where;
}


function geodir_related_posts_fields($fields) {
    global $wp_query, $wpdb, $table, $post;

    $fields .= ", " . $table . ".* ";

    $DistanceRadius = geodir_getDistanceRadius(get_option('geodir_search_dist_1'));

    $mylat = $post->post_latitude;
    $mylon = $post->post_longitude;

    $fields .= " , (" . $DistanceRadius . " * 2 * ASIN(SQRT( POWER(SIN((ABS($mylat) - ABS(" . $table . ".post_latitude)) * pi()/180 / 2), 2) +COS(ABS($mylat) * pi()/180) * COS( ABS(" . $table . ".post_latitude) * pi()/180) *POWER(SIN(($mylon - " . $table . ".post_longitude) * pi()/180 / 2), 2) )))as distance ";
    return $fields;
}
function geodir_related_posts_fields_filter($query) {
    if ( isset($query->query_vars['is_geodir_loop']) && $query->query_vars['is_geodir_loop']
        && isset($query->query_vars['order_by']) && $query->query_vars['order_by'] == 'nearest'
        && isset($query->query_vars['related_listings']) && $query->query_vars['related_listings']
    ) {
        add_filter('posts_fields', 'geodir_related_posts_fields', 1);
    }
}
add_action('pre_get_posts', 'geodir_related_posts_fields_filter', 1);