<?php
/*-----------------------------------------------------------------------------------*/
/*  Term and review count functions
/*-----------------------------------------------------------------------------------*/
function has_lm_plugin_enabled() {
    if( is_plugin_active('geodir_location_manager/geodir_location_manager.php') ){
        $multi_loc = true;
    } else {
        $multi_loc = false;
    }
    return $multi_loc;
}

function geodir_filter_listings_where_set_loc( $term_id, $taxonomy, $post_type, $location, $count_type ) {
	global $wpdb, $plugin_prefix;

	$tax_query = array(
		'taxonomy' => $taxonomy,
		'field' => 'id',
		'terms' => $term_id
	);

	$tax_query = array( $tax_query );

	$table = $plugin_prefix . $post_type . '_detail';
	$fields = $wpdb->posts . ".*, " . $table . ".*";

	$join = "INNER JOIN " . $table ." ON (" . $table .".post_id = " . $wpdb->posts . ".ID)";

	$where = " AND ( " . $wpdb->posts . ".post_status = 'publish' )
    AND " . $wpdb->posts . ".post_type = '" . $post_type . "'
    AND FIND_IN_SET('[" . $location . "]', post_locations)";

	if ( !empty( $tax_query ) ) {
		$tax_queries = get_tax_sql( $tax_query, $wpdb->posts, 'ID' );

		if ( !empty( $tax_queries['join'] ) && !empty( $tax_queries['where'] ) ) {
			$where .= $tax_queries['where'];
			$join .= $tax_queries['join'];
		}
	}
	$where = $where != '' ? " WHERE 1=1 " . $where : '';

	$groupby = " GROUP BY $wpdb->posts.ID ";
	$orderby = $wpdb->posts . ".post_title ASC";
	$orderby = $orderby != '' ? " ORDER BY " . $orderby : '';

	$sql =  "SELECT SQL_CALC_FOUND_ROWS " . $fields . " FROM " . $wpdb->posts . "
        " . $join . "
        " . $where . "
        " . $groupby . "
        " . $orderby;

	$rows = $wpdb->get_results($sql);
	if ($count_type == 'review_count') {
		$count = 0;
		foreach($rows as $post) {
			$count = $count + $post->comment_count;
		}
	} elseif ($count_type == 'term_count') {
		$count = count($rows);
	}
	return $count;
}

function geodir_insert_term_count_by_loc($location_name, $location_type, $count_type, $row_id=null, $multi_loc=true) {
    global $wpdb;
	$post_types = geodir_get_posttypes();
	$term_array = array();
	foreach($post_types as $post_type) {
		$taxonomy = geodir_get_taxonomies($post_type);
		$taxonomy = $taxonomy[0];

        $args = array(
            'hide_empty' => false
        );

		$terms = get_terms($taxonomy, $args);
		foreach ($terms as $term) {
			$count = geodir_filter_listings_where_set_loc($term->term_id, $taxonomy, $post_type, $location_name, $count_type);
			$term_array[$term->term_id] = $count;
		}
	}

	$data = serialize($term_array);

	if($multi_loc) {
		if ( $row_id ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE " . GEODIR_TERM_META . " set
                    " . $count_type . " = %s WHERE id=" . $row_id . "",
					array( $data )
				)
			);
		} else {
			$wpdb->query(
				$wpdb->prepare(
					"INSERT into " . GEODIR_TERM_META . " set
                    location_type = %s,
                    location_name = %s,
                    " . $count_type . " = %s",
					array( $location_type, $location_name, $data )
				)
			);
		}
	} else {
		update_option('geodir_'.$count_type.'_'.$location_type, $data);
	}
	return $term_array;
}

function geodir_get_loc_term_count($count_type = 'term_count', $location_name=null, $location_type=null, $force_update=false, $custom_table=true) {
	//accepted count type: term_count, review_count
	global $wpdb;

	$multi_loc = has_lm_plugin_enabled();

	if (!$location_name || !$location_type) {
		$loc = array();
		if($multi_loc) {
			$loc = geodir_get_current_location_terms();
		} else {
            $loc_data = geodir_get_default_location();
            $loc['gd_city'] = $loc_data->city_slug;
            $loc['gd_region'] = $loc_data->region_slug;
            $loc['gd_country'] = $loc_data->country_slug;
        }

		if (isset($loc['gd_city']) && $loc['gd_city'] != '') {
			$location_name = $loc['gd_city'];
			$location_type = 'gd_city';
		} elseif (isset($loc['gd_region']) && $loc['gd_region'] != '') {
			$location_name = $loc['gd_region'];
			$location_type = 'gd_region';
		} elseif (isset($loc['gd_country']) && $loc['gd_country'] != '') {
			$location_name = $loc['gd_country'];
			$location_type = 'gd_country';
		}
	}

    if ($location_name && $location_type) {

		if($multi_loc && $custom_table) {
			$sql = $wpdb->prepare( "SELECT * FROM " . GEODIR_TERM_META . " WHERE location_type=%s AND location_name=%s LIMIT 1", array( $location_type, $location_name ) );
			$row = $wpdb->get_row( $sql );

			if ( $row ) {
				if ( $force_update ) {
					return geodir_insert_term_count_by_loc( $location_name, $location_type, $count_type, $row->id );
				} else {
					if ( $row->$count_type ) {
						$data = unserialize( $row->$count_type );
						return $data;
					} else {
						return geodir_insert_term_count_by_loc( $location_name, $location_type, $count_type, $row->id );
					}
				}

			} else {
				return geodir_insert_term_count_by_loc( $location_name, $location_type, $count_type );
			}
		} else {
			$array = get_option('geodir_'.$count_type.'_'.$location_type);
			if ($array) {
				if ( $force_update ) {
					return geodir_insert_term_count_by_loc( $location_name, $location_type, $count_type, null, false );
				} else {
					$data = unserialize( $array );
                    var_dump($data);
					return $data;
				}
			} else {
				return geodir_insert_term_count_by_loc( $location_name, $location_type, $count_type, null, false );
			}
		}
	}
}

function geodir_term_post_count_update($post_id, $post) {
	$geodir_posttypes = geodir_get_posttypes();

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if( !wp_is_post_revision( $post_id ) && isset($post->post_type) && in_array($post->post_type,$geodir_posttypes )) {

		//if ( !wp_verify_nonce( $_POST['geodir_post_info_noncename'], 'geodirectory/geodirectory-admin/admin_functions.php' ) )
		//    return;

		$country = isset($_REQUEST['post_country']) ? $_REQUEST['post_country'] : '';
		$region = isset($_REQUEST['post_region']) ? $_REQUEST['post_region'] : '';
		$city = isset($_REQUEST['post_city']) ? $_REQUEST['post_city'] : '';
		$country_slug = create_location_slug($country);
		$region_slug = create_location_slug($region);
		$city_slug = create_location_slug($city);

		$loc = array();
		$loc['gd_city'] = $country_slug;
		$loc['gd_region'] = $region_slug;
		$loc['gd_country'] = $city_slug;

		foreach($loc as $key => $value) {
			if ($value != '') {
				geodir_get_loc_term_count('term_count', $value, $key, true);
			}
		}
	}
}
add_action( 'save_post', 'geodir_term_post_count_update', 100, 2);

function geodir_term_count_update_on_loc_change() {

    if(isset($_REQUEST['is_default']) && $_REQUEST['is_default'] == '1') {
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : '';
        $region = isset($_REQUEST['region']) ? $_REQUEST['region'] : '';
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
        $country_slug = create_location_slug($country);
        $region_slug = create_location_slug($region);
        $city_slug = create_location_slug($city);

        $loc = array();
        $loc['gd_city'] = $country_slug;
        $loc['gd_region'] = $region_slug;
        $loc['gd_country'] = $city_slug;

        foreach($loc as $key => $value) {
            if ($value != '') {
                geodir_get_loc_term_count('term_count', $value, $key, true, false);
                geodir_get_loc_term_count('review_count', $value, $key, true, false);
            }
        }
    }
}
add_action('geodir_update_options_default_location_settings', 'geodir_term_count_update_on_loc_change', 100);

function geodir_term_review_count_update($post_id) {
	$geodir_posttypes = geodir_get_posttypes();
	$post = get_post($post_id);
	if (isset($post->post_type) && in_array($post->post_type,$geodir_posttypes )) {
		$locations = geodir_get_post_meta( $post_id, 'post_locations' );
		if ( $locations ) {
			$array = explode( ',', $locations );
			var_dump( $array );
			$reversed = array_reverse( $array );
			$count    = count( $reversed );
			$keys     = null;
			if ( $count == 1 ) {
				$keys = array( 'gd_country' );
			} elseif ( $count == 2 ) {
				$keys = array( 'gd_country', 'gd_region' );
			} elseif ( $count == 3 ) {
				$keys = array( 'gd_country', 'gd_region', 'gd_city' );
			}
			$locs = array_combine( $keys, $reversed );
			foreach ( $locs as $key => $value ) {
				$value = str_replace( array( '[', ']' ), '', $value );
				geodir_get_loc_term_count( 'review_count', $value, $key, true );
			}
		}
	}
	return;
}
add_action( 'wp_update_comment_count', 'geodir_term_review_count_update', 100, 1);
