<?php
/**
 * Retrive markers data and marker info window to use in map
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
 
if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'homemap_catlist') {
    $post_taxonomy = geodir_get_taxonomies($_REQUEST['post_type']);
    $map_canvas_name = $_REQUEST['map_canvas'];
    $child_collapse = $_REQUEST['child_collapse'];
    echo home_map_taxonomy_walker($post_taxonomy, 0, true, 0, $map_canvas_name, $child_collapse, true);
    die;
}

// Send the content-type header with correct encoding
header("Content-type: text/javascript");

if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'cat') { // Retrives markers data for categories
    echo get_markers();
    exit;
} else if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'info') { // Retrives marker info window html
    /**
	 * @global object $wpdb WordPress database abstraction object.
	 * @global string $plugin_prefix Prefix of tha plugin
	 */
	global $wpdb, $plugin_prefix;

    if ($_REQUEST['m_id'] != '') {
        $pid = $_REQUEST['m_id'];
    } else {
        echo __('No marker data found', GEODIRECTORY_TEXTDOMAIN);
        exit;
    }

    if (isset($_REQUEST['post_preview']) && $_REQUEST['post_preview'] != '' && isset($_SESSION['listing'])) {
        $post = (object)unserialize($_SESSION['listing']);
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
 *
 * @global object $wpdb WordPress database abstraction object.
 * @global string $plugin_prefix Prefix of the plugin.
 * @global array  $geodir_cat_icons Array of the category icon urls.
 * 
 * @return string
 */
function get_markers()
{
    global $wpdb, $plugin_prefix, $geodir_cat_icons;

    $search = '';
    $main_query_array;

    $srcharr = array("'", "/", "-", '"', '\\');
    $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '');

    $post_type = isset($_REQUEST['gd_posttype']) ? $_REQUEST['gd_posttype'] : 'gd_place';

    $map_cat_ids_array = array('0');
    $cat_find_array = array(" FIND_IN_SET(%d, pd." . $post_type . "category)");

    $field_default_cat = '';
    if (isset($_REQUEST['cat_id']) && $_REQUEST['cat_id'] != '') {
        $map_cat_arr = trim($_REQUEST['cat_id'], ',');

        if (!empty($map_cat_arr)) {
            $field_default_cat .= "when (default_category IN (" . $map_cat_arr . ")) then default_category ";

            $map_cat_ids_array = explode(',', $map_cat_arr);
            $cat_find_array = array();
            foreach ($map_cat_ids_array as $cat_id) {
                $field_default_cat .= "when ( find_in_set($cat_id, `" . $post_type . "category`) > 0) then $cat_id ";
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

    if (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && $_REQUEST['search'] != __('Title', GEODIRECTORY_TEXTDOMAIN)) {
        $search .= " AND p.post_title like %s";
        $main_query_array[] = "%" . $_REQUEST['search'] . "%";
    }

    $gd_posttype = '';
    if (isset($_REQUEST['gd_posttype']) && $_REQUEST['gd_posttype'] != '') {
        $table = $plugin_prefix . $_REQUEST['gd_posttype'] . '_detail';
        $gd_posttype = " AND p.post_type = %s";
        $main_query_array[] = $_REQUEST['gd_posttype'];

    } else
        $table = $plugin_prefix . 'gd_place_detail';

    $join = $table . " as pd ";

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
        $event_select = ",pd.recurring_dates";
    } else {
        $event_select = "";
    }
    
	/**
	 * Filter the SQL SELECT clause to retrive fields data
	 *
	 * @since 1.0.0
	 *
	 * @param string $sql_select Row of SQL SELECT clause.
	 */
	$sql_select = 'SELECT pd.default_category,pd.' . $cat_type . ',pd.post_title,pd.post_id,pd.post_latitude,pd.post_longitude ' . $event_select;
	$select = apply_filters('geodir_home_map_listing_select', $sql_select);

    $catsql = $wpdb->prepare("$select $field_default_cat FROM "
        . $wpdb->posts . " as p,"
        . $join . " WHERE p.ID = pd.post_id
				AND p.post_status = 'publish' " . $search . $gd_posttype, $main_query_array);
    
	/**
	 * Filter the SQL query to retrive markers data
	 *
	 * @since 1.0.0
	 *
	 * @param string $catsql Row of SQL query.
	 * @param string $search Row of searched fields to use in WHERE clause.
	 */
	$catsql = apply_filters('geodir_home_map_listing_query', $catsql, $search);

    $catinfo = $wpdb->get_results($catsql);
    $cat_content_info = array();
    $content_data = array();
    $post_ids = array();

    if (!empty($catinfo)) {
        $geodir_cat_icons = geodir_get_term_icon();
        global $geodir_date_format;

        foreach ($catinfo as $catinfo_obj) {
            $icon = !empty($geodir_cat_icons) && isset($geodir_cat_icons[$catinfo_obj->default_category]) ? $geodir_cat_icons[$catinfo_obj->default_category] : '';

            $e_dates = '';
            if ($post_type == 'gd_event') {
                $event_arr = maybe_unserialize($catinfo_obj->recurring_dates);
                $e_arr = explode(",", $event_arr['event_recurring_dates']);

                $e = 0;
                foreach ($e_arr as $e_date) {
                    if (strtotime($e_date) >= strtotime(date("Y-m-d"))) {
                        $e++;
                        $e_dates .= ' :: ' . date($geodir_date_format, strtotime($e_date));
                        
						// only show 3 event dates
						if ($e == 3) {
                            break;
                        }
                    }
                }

                // if the event is old don't show it on the map
				if ($e_dates == '') {
                    continue;
                }
            }

            $post_title = $catinfo_obj->post_title . $e_dates;
            $title = str_replace($srcharr, $replarr, $post_title);

            $content_data[] = '{"id":"' . $catinfo_obj->post_id . '","t": "' . $title . '","lt": "' . $catinfo_obj->post_latitude . '","ln": "' . $catinfo_obj->post_longitude . '","mk_id":"' . $catinfo_obj->post_id . '_' . $catinfo_obj->default_category . '","i":"' . $icon . '"}';
            $post_ids[] = $catinfo_obj->post_id;
        }
    }

    if (!empty($content_data)) {
        $cat_content_info[] = implode(',', $content_data);
    }

    $totalcount = count(array_unique($post_ids));

    if (!empty($cat_content_info))
        return '[{"totalcount":"' . $totalcount . '",' . substr(implode(',', $cat_content_info), 1) . ']';
    else
        return '[{"totalcount":"0"}]';
}