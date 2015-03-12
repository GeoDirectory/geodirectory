<?php

/**
 * Listing functions
 * 
 * @package		GeoDirectory
 * @category	functions
 * @author		WPGeoDirectory
**/

/*
	Set post category structure
*/
function geodir_set_postcat_structure($post_id, $taxonomy, $default_cat = '' , $category_str = ''){
	
	$post_cat_ids = geodir_get_post_meta($post_id,$taxonomy);
	if(!empty($post_cat_ids))
		$post_cat_array = explode(",", trim($post_cat_ids,","));
	
	if(!isset($default_cat) || empty($default_cat))
	{ $default_cat =  isset($post_cat_array[0]) ? $post_cat_array[0] : '';}
	
	geodir_save_post_meta($post_id, 'default_category', $default_cat);	
		
	if(isset($category_str) && empty($category_str))
	{
		
		$post_cat_str = '';
		$post_categories = array();
		if(isset($post_cat_array) && is_array($post_cat_array) && !empty($post_cat_array)){
			$post_cat_str = implode(",y:#", $post_cat_array);
			$post_cat_str .= ",y:";
			$post_cat_str = substr_replace($post_cat_str,',y,d:', strpos($post_cat_str,',y:'), strlen(',y:'));
		}
		$post_categories[$taxonomy] = $post_cat_str;
		$category_str = $post_categories;
	}
	
	$change_cat_str = $category_str[$taxonomy];
	
	$default_pos = strpos($change_cat_str, 'd:');
	
	if ($default_pos === false) {
			
		$change_cat_str = str_replace($default_cat.',y:', $default_cat.',y,d:', $change_cat_str);
		
	}
	
	$category_str[$taxonomy] = $change_cat_str;
	
	update_post_meta($post_id, 'post_categories', $category_str);		
	
}


/**
 * Save Listing
 */
 
if ( !function_exists( 'geodir_save_listing' ) ) {
function geodir_save_listing( $request_info = array(), $dummy = false ) {
	global $wpdb, $current_user; 
	
	$last_post_id = '';

	if( isset( $_SESSION['listing'] ) && !$dummy) {
		$request_info = array();
		$request_session = unserialize( $_SESSION['listing'] );
		$request_info = array_merge( $_REQUEST, $request_session );
	} else if ( !isset( $_SESSION['listing'] ) && !$dummy ) {
		global $post;
		$request_info['pid'] = $post->ID;
		$request_info['post_title'] = $request_info['post_title'];
		$request_info['listing_type'] = $post->post_type;
		$request_info['post_desc'] = $request_info['content'];
	} else if( !$dummy ) {
		return false;
	}
	
	$request_info = apply_filters( 'geodir_action_get_request_info', $request_info );	
	
	// Check if we need to save post location as new location 
	$location_result = geodir_get_default_location();

	if ($location_result->location_id > 0 ) {
		if ( isset( $request_info['post_city'] ) && isset( $request_info['post_region'] ) ) {
			$request_info['post_location'] = array( 
												'city' => $request_info['post_city'], 
												'region' => isset($request_info['post_region']) ? $request_info['post_region'] : '', 
												'country' => isset($request_info['post_country']) ? $request_info['post_country'] : '',
												'geo_lat' => isset($request_info['post_latitude']) ? $request_info['post_latitude'] : '',
												'geo_lng' => isset($request_info['post_longitude']) ? $request_info['post_longitude'] : ''
											);
											
			$post_location_info = $request_info['post_location'];
			
			if ( $location_id = geodir_add_new_location( $post_location_info ) ) {
				$post_location_id = $location_id;
			}			
		} else {
			$post_location_id = $location_result->location_id;
		}
	} else {
		$post_location_id = $location_result->location_id;
	}
	
	if ( $dummy ) {
		$post_status = 'publish';
	} else {
		$post_status = geodir_new_post_default_status();
	}	
	
	if ( isset( $request_info['pid'] ) && $request_info['pid'] != '' ) { 
		$post_status = get_post_status( $request_info['pid'] );	
	}

	/* fix change of slug on every title edit */
	if ( !isset( $request_info['post_name'] ) ) {
		$request_info['post_name'] = $request_info['post_title'];
		
		if ( !empty( $request_info['pid'] ) ) {
			$post_info = get_post( $request_info['pid'] );
			
			if ( !empty( $post_info ) && isset( $post_info->post_name ) ) {
				$request_info['post_name'] = $post_info->post_name;
			}
		}
	}

	$post = array(
				'post_content'   => $request_info['post_desc'],
				'post_status'    => $post_status,
				'post_title'     => $request_info['post_title'],
				'post_name'      => $request_info['post_name'],
				'post_type'      => $request_info['listing_type']
			);

	do_action_ref_array( 'geodir_before_save_listing', $post );

	$send_post_submit_mail = false;
	if ( isset( $request_info['pid'] ) && $request_info['pid'] != '' ){	
		$post['ID'] = $request_info['pid'];
		
		$last_post_id =  wp_update_post( $post );
	} else {
		$last_post_id =  wp_insert_post( $post );
		
		if ( !$dummy && $last_post_id ) {
			$send_post_submit_mail = true; // we move post_submit email from here so the rest of the variables are added to the db first(was breaking permalink in email)
			//geodir_sendEmail('','',$current_user->user_email,$current_user->display_name,'','',$request_info,'post_submit',$last_post_id,$current_user->ID);
		}
	}

	$post_tags = '';
	if(!isset($request_info['post_tags'])){
			
		$post_type = $request_info['listing_type'];
		$post_tags = implode(",",wp_get_object_terms($last_post_id,$post_type.'_tags' ,array('fields'=>'names')));
			
	}
	
	$gd_post_info = array(
						"post_title"	=> $request_info['post_title'],
						"post_tags"		=> isset($request_info['post_tags']) ? $request_info['post_tags'] : $post_tags,
						"post_status"	=> $post_status,
						"post_location_id"=> $post_location_id,
						"claimed"		=> isset($request_info['claimed']) ? $request_info['claimed']: '',
						"businesses"	=> isset($request_info['a_businesses']) ? $request_info['a_businesses'] : '',
						"submit_time"	=> time(),
						"submit_ip"		=> $_SERVER['REMOTE_ADDR'],
					);
						
		$payment_info = array();	
		$package_info = array();

		$package_info = (array)geodir_post_package_info( $package_info ,$post );
		
		$post_package_id = geodir_get_post_meta($last_post_id,'package_id');
			
		if(!empty($package_info) && !$post_package_id){	
			if(isset($package_info['days']) && $package_info['days'] != 0){
				$payment_info['expire_date'] = date('Y-m-d', strtotime("+".$package_info['days']." days"));
			}else{$payment_info['expire_date'] = 'Never'; }
			
			$payment_info['package_id'] = $package_info['pid'];
			$payment_info['alive_days'] = $package_info['days'];
			$payment_info['is_featured'] = $package_info['is_featured'];	
		
			$gd_post_info = array_merge($gd_post_info,$payment_info);
		}
		
		$custom_metaboxes = geodir_post_custom_fields('','all',$request_info['listing_type']);
		
		foreach($custom_metaboxes as $key=>$val):
		
		$name = $val['name'];
		$type = $val['type'];
		$extrafields = $val['extra_fields'];


		if(trim($type) == 'address')
		{
			$prefix = $name.'_';
			$address = $prefix.'address';

			if(isset($request_info[$address]) && $request_info[$address] != '')
			{ $gd_post_info[$address] = $request_info[$address]; }
			
			if($extrafields != '')
			{
				$extrafields = unserialize($extrafields);
				
				
				if(!isset($request_info[$prefix.'city']) || $request_info[$prefix.'city'] == ''){
					
					$location_result = geodir_get_default_location();
											
					$gd_post_info[$prefix.'city'] = $location_result->city; 
					$gd_post_info[$prefix.'region'] = $location_result->region;
				 	$gd_post_info[$prefix.'country'] =  $location_result->country;
					
					$gd_post_info['post_locations'] =  '['.$location_result->city_slug.'],['.$location_result->region_slug.'],['.$location_result->country_slug.']'; // set all overall post location
					
				}else{
					
					$gd_post_info[$prefix.'city'] = $request_info[$prefix.'city']; 
					$gd_post_info[$prefix.'region'] = $request_info[$prefix.'region']; 
					$gd_post_info[$prefix.'country'] = $request_info[$prefix.'country'];
				
					//----------set post locations when import dummy data-------
					$location_result = geodir_get_default_location();

					$gd_post_info['post_locations'] =  '['.$location_result->city_slug.'],['.$location_result->region_slug.'],['.$location_result->country_slug.']'; // set all overall post location
					//-----------------------------------------------------------------
					
				}


				if(isset($extrafields['show_zip']) && $extrafields['show_zip'] && isset($request_info[$prefix.'zip']))
				{ $gd_post_info[$prefix.'zip'] = $request_info[$prefix.'zip']; }

			
				
				if(isset($extrafields['show_map']) && $extrafields['show_map']){ 
				
					if(isset($request_info[$prefix.'latitude']) && $request_info[$prefix.'latitude'] != '')
					{ $gd_post_info[$prefix.'latitude'] = $request_info[$prefix.'latitude']; }
					
					if(isset($request_info[$prefix.'longitude']) && $request_info[$prefix.'longitude'] != '')
					{ $gd_post_info[$prefix.'longitude'] = $request_info[$prefix.'longitude']; }
					
					if(isset($request_info[$prefix.'mapview']) && $request_info[$prefix.'mapview'] != '')
					{ $gd_post_info[$prefix.'mapview'] = $request_info[$prefix.'mapview']; }
					
					if(isset($request_info[$prefix.'mapzoom']) && $request_info[$prefix.'mapzoom'] != '')
					{ $gd_post_info[$prefix.'mapzoom'] = $request_info[$prefix.'mapzoom']; }
				
				}
				
				// show lat lng
				if(isset($extrafields['show_latlng']) && $extrafields['show_latlng'] && isset($request_info[$prefix.'latlng']))
				{ $gd_post_info[$prefix.'latlng'] = $request_info[$prefix.'latlng']; }
			}
			
		}
		elseif(trim($type) == 'file')
		{
			if(isset($request_info[$name])){
			$request_files = array();
			if($request_info[$name] != '')
			$request_files = explode(",",$request_info[$name]);
			
			geodir_save_post_file_fields($last_post_id,$name,$request_files);
			
			}
		}elseif(trim($type) == 'datepicker'){
			$datetime = '';
			if($request_info[$name] != ''){
				$date_format = geodir_default_date_format();
				if( isset( $val['extra_fields'] ) && $val['extra_fields'] != '' ){
					$extra_fields = unserialize( $val['extra_fields'] );
					$date_format = isset( $extra_fields['date_format'] ) && $extra_fields['date_format'] != '' ? $extra_fields['date_format'] : $date_format;
				}
				
				$search = array('dd', 'mm', 'yy');
				$replace = array('d', 'm', 'Y');
				
				$date_format = str_replace( $search, $replace, $date_format );
				
				$post_htmlvar_value = $date_format == 'd/m/Y' ? str_replace( '/', '-', $request_info[$name] ) : $request_info[$name]; // PHP doesn't work well with dd/mm/yyyy format
							
				$datetime = date( "Y-m-d", strtotime( $post_htmlvar_value ) );
			}
			$gd_post_info[$name] = $datetime;
		} else if( $type == 'multiselect' ) {
			if ( isset( $request_info[$name] ) ) {
				$gd_post_info[$name] = $request_info[$name];
			} else {
				if ( isset( $request_info['gd_field_' . $name] ) ) {
					$gd_post_info[$name] = ''; /* fix de-select for multiselect */
				}
			}
		} else if( isset( $request_info[$name] ) ) {
			$gd_post_info[$name] = $request_info[$name];
		}
		
		endforeach;
		
		if(isset($request_info['post_dummy']) && $request_info['post_dummy'] != '')
		{ $gd_post_info['post_dummy'] = $request_info['post_dummy']; }
		
		// Save post detail info in detail table
		if(!empty($gd_post_info)){ 
			geodir_save_post_info($last_post_id, $gd_post_info); 
		}	
		
		
		// Set categories to the listing
		if(isset($request_info['post_category']) && !empty($request_info['post_category']) )
		{	
			$post_category = array();
			
			foreach($request_info['post_category'] as $taxonomy => $cat){
					
					if($dummy)
						$post_category = $cat;
					else{
						
						if( !is_array($cat) && strstr($cat,',') )
							$cat = explode(',',$cat);
						
						if(!empty($cat) && is_array($cat))	
						$post_category = array_map('intval', $cat);
					}	
					
					wp_set_object_terms($last_post_id, $post_category, $taxonomy  );
			}	
			
			$post_default_category = isset($request_info['post_default_category']) ? $request_info['post_default_category'] : '';
			 
			$post_category_str = isset($request_info['post_category_str']) ? $request_info['post_category_str'] : '';
			geodir_set_postcat_structure($last_post_id,$taxonomy,$post_default_category,$post_category_str);
			
		}
		
		$post_tags = '';
		// Set tags to the listing
		if(isset($request_info['post_tags']) && !is_array($request_info['post_tags']) && !empty($request_info['post_tags']) ){
			$post_tags = explode(",",$request_info['post_tags']);	
		}elseif(isset($request_info['post_tags']) && is_array($request_info['post_tags'])){
			if($dummy)
				$post_tags = $request_info['post_tags'];
		}else{
			if($dummy)
				$post_tags = array($request_info['post_title']);
		}
		
		if( is_array($post_tags) ){
			$taxonomy = $request_info['listing_type'].'_tags' ;
			wp_set_object_terms($last_post_id, $post_tags,$taxonomy);
		}	
		
		
		// Insert attechment
	
		if( isset($request_info['post_images']) && !is_wp_error($last_post_id) )
		{
			if(!$dummy)
			{
				$tmpimgArr = trim($request_info['post_images'],",");
				$tmpimgArr = explode(",",$tmpimgArr);
				geodir_save_post_images($last_post_id,$tmpimgArr, $dummy);
			}
			else
				geodir_save_post_images($last_post_id,$request_info['post_images'], $dummy);
				
		}elseif(!isset($request_info['post_images']) || $request_info['post_images'] == '')
		{
			
			/* Delete Attachments
			$postcurr_images = geodir_get_images($last_post_id);
			
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ".GEODIR_ATTACHMENT_TABLE." WHERE `post_id` = %d",
					array($last_post_id)
				)
			);
			geodir_remove_attachments($postcurr_images);
			
			$gd_post_featured_img = array();
			$gd_post_featured_img['featured_image'] = '';
			geodir_save_post_info($last_post_id, $gd_post_featured_img); 
			*/
		
		}
		
		geodir_remove_temp_images();
		geodir_set_wp_featured_image($last_post_id);
		do_action('geodir_after_save_listing',$last_post_id,$request_info);
		
		//die;
		
		if($send_post_submit_mail){ // if new post send out email
			geodir_sendEmail('','',$current_user->user_email,$current_user->display_name,'','',$request_info,'post_submit',$last_post_id,$current_user->ID);
		}
		return $last_post_id;
		
	}	
	
}


/**
 * Get post custome fields
 */
function geodir_get_post_info($post_id = '') {
	
	global $wpdb,$plugin_prefix,$post,$post_info;  
	
	if($post_id == '' &&  !empty($post)) 
		$post_id = $post->ID;
	
	$post_type = get_post_type( $post_id );
	
	$all_postypes = geodir_get_posttypes();

	if(!in_array($post_type, $all_postypes))
		return false;
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	// Apply Filter to change Post info // Filter-Location-Manager
	$query = apply_filters('geodir_post_info_query' ,"SELECT p.*,pd.* FROM ".$wpdb->posts." p,".$table." pd
			  WHERE p.ID = pd.post_id 
			  AND post_id = ".$post_id );
	
	$post_detail = $wpdb->get_row($query); 
	
	return (!empty($post_detail)) ? $post_info = $post_detail : $post_info = false;
	
}


/**
 * Save listing info
 */ 
if (!function_exists('geodir_save_post_info')) { 
function geodir_save_post_info($post_id, $postinfo_array = array()){ 
	global $wpdb,$plugin_prefix;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	$postmeta = apply_filters('geodir_listinginfo_request', $postinfo_array, $post_id );
	
	if(!empty($postmeta) && $post_id  )
	{
		$post_meta_set_query = '';
		
		foreach($postmeta as $mkey=>$mval)
		{
			if(geodir_column_exist($table,$mkey) ){
				if(is_array($mval))
				{ $mval = implode(",",$mval); }
				
				$post_meta_set_query .= $mkey." = '".$mval."', ";
			}	
		}
		
		$post_meta_set_query = trim($post_meta_set_query,", ");
		
		$post_meta_set_query = str_replace('%', '%%',  $post_meta_set_query);// escape %
		
		do_action('geodir_before_save_listinginfo',$postinfo_array, $post_id);
		
		if($wpdb->get_var($wpdb->prepare("SELECT post_id from ".$table." where post_id = %d",array($post_id))))
		{
			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE ".$table." SET ".$post_meta_set_query." where post_id =%d",
					array($post_id)
				)
			);
			
			
		}else
		{
			
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO ".$table." SET post_id = %d,".$post_meta_set_query,
					array($post_id)
				)
			);
			
		}
		
		do_action('geodir_after_save_listinginfo',$postinfo_array, $post_id);
		
		return true;	
	}else
		return false;
	
}
}
 

/**
 * Save post custome fields
 */ 
 
if (!function_exists('geodir_save_post_meta')) { 
function geodir_save_post_meta($post_id, $postmeta = '',$meta_value = ''){ 
	
	global $wpdb,$plugin_prefix;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	if( $postmeta != '' && geodir_column_exist($table,$postmeta) && $post_id  ){
		
		if(is_array($meta_value))
		{ $meta_value = implode(",",$meta_value); }
		
		if($wpdb->get_var($wpdb->prepare("SELECT post_id from ".$table." where post_id = %d",array($post_id)))){
			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE ".$table." SET ".$postmeta." = '".$meta_value."' where post_id =%d",
					array($post_id)
				)
			);
		
		}else{
			
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO ".$table." SET post_id = %d, ".$postmeta." = '".$meta_value."'",
					array($post_id)
				)
			);
		}
		
		
		
	}else
		return false;
}
}

/**
 * Delete post custome fields
 */ 
 
if (!function_exists('geodir_delete_post_meta')) { 
function geodir_delete_post_meta($post_id,$postmeta){ 
	
	global $wpdb,$plugin_prefix;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	if(is_array($postmeta) && !empty($postmeta) && $post_id )
	{
		$post_meta_set_query = '';
		
		foreach($postmeta as $mkey)
		{
			if($mval != '')
				$post_meta_set_query .= $mkey." = '', ";
		}
		
		$post_meta_set_query = trim($post_meta_set_query,", ");
		
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table." WHERE field = '".$postmeta."'"  ) != '')
		{
			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE ".$table." SET ".$post_meta_set_query." where post_id = %d",
					array($post_id) 
				)
			);
			
			return true;
		}		
		
	}elseif($postmeta != '' && $post_id)
	{
		if($wpdb->get_var("SHOW COLUMNS FROM ".$table." WHERE field = '".$postmeta."'"  ) != '')
		{
			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE ".$table." SET ".$postmeta ."= '' where post_id = %d",
					array($post_id)
				)
			);
			
			return true;
		}
				
	}else
		return false;
}
}



/**
 * Get post custome meta
 */
 
if (!function_exists('geodir_get_post_meta')) {
function geodir_get_post_meta( $post_id, $meta_key, $single = false ){
	if(!$post_id){return false;}
	global $wpdb,$plugin_prefix;
	
	$all_postypes = geodir_get_posttypes();
	
	$post_type = get_post_type( $post_id );
	
	if(!in_array($post_type, $all_postypes))
		return false;
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	if($wpdb->get_var("SHOW COLUMNS FROM ".$table." WHERE field = '".$meta_key."'"  ) != '')
	{
		
		if($meta_value = $wpdb->get_var($wpdb->prepare("SELECT ".$meta_key." from ".$table." where post_id = %d",array($post_id))))
		{
			$meta_value = maybe_serialize( $meta_value );
			return $meta_value;
		}else
			return false;
		
	}else
		return false;	
}
}


/**
 * Save post attachments
 */
 
if (!function_exists('geodir_save_post_images')) {
function geodir_save_post_images($post_id = 0, $post_image = array(), $dummy = false){
	
	global $wpdb,$plugin_prefix,$current_user;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	$post_images = geodir_get_images($post_id);
	
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE ".$table." SET featured_image = '' where post_id =%d",
			array($post_id) 
		)
	);
	
	$invalid_files = $post_images;
	$valid_file_ids = array();
	$valid_files_condition = '';
	$geodir_uploaddir = '';
	
	$remove_files = array();
	
	if(!empty($post_image))
	{
		
		$uploads = wp_upload_dir(); 
		$uploads_dir = $uploads['path'];	
		
		$geodir_uploadpath = $uploads['path'];
		$geodir_uploadurl = $uploads['url']; 	
		$sub_dir = isset($uploads['subdir']) ? $uploads['subdir'] : '';
		
		$invalid_files = array();
		$postcurr_images = array();
		
		for($m=0;$m < count($post_image);$m++)
		{
			$menu_order = $m+1;
			
			$file_path ='';
			/* --------- start ------- */
			
			$split_img_path = explode($uploads['baseurl'], $post_image[$m]);
			
			$split_img_file_path = isset($split_img_path[1]) ? $split_img_path[1] : '';
			
			
			if(!$find_image = $wpdb->get_var($wpdb->prepare("SELECT ID FROM ".GEODIR_ATTACHMENT_TABLE." WHERE file=%s AND post_id = %d",array($split_img_file_path,$post_id))))
			{
				
				/* --------- end ------- */
				$curr_img_url =  $post_image[$m];
				
				$image_name_arr = explode('/',$curr_img_url);
				
				$count_image_name_arr = count($image_name_arr)-2;
				
				$count_image_name_arr = ($count_image_name_arr >= 0 ) ? $count_image_name_arr : 0;
				
				$curr_img_dir = $image_name_arr[$count_image_name_arr];
								
				$filename = end( $image_name_arr );
				if ( strpos( $filename, '?' ) !== false ) {
    				list( $filename ) = explode( '?', $filename );
				}

				$curr_img_dir = str_replace( $uploads['baseurl'], "", $curr_img_url );
				$curr_img_dir = str_replace( $filename, "", $curr_img_dir );
				
				$img_name_arr = explode( '.', $filename );
				
				$file_title = isset( $img_name_arr[0] ) ? $img_name_arr[0] : $filename;
				if ( !empty( $img_name_arr ) && count( $img_name_arr ) > 2 ) {
					$new_img_name_arr = $img_name_arr;
					if ( isset( $new_img_name_arr[count( $img_name_arr )-1] ) ) {
						unset( $new_img_name_arr[count( $img_name_arr )-1] );
						$file_title = implode( '.', $new_img_name_arr );
					}
				}
				$file_title = sanitize_file_name( $file_title );
				$file_name = sanitize_file_name( $filename );
				
				$arr_file_type = wp_check_filetype( $filename );
				
				$uploaded_file_type = $arr_file_type['type'];
								
				// Set an array containing a list of acceptable formats
				$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
				
				// If the uploaded file is the right format
				if(in_array($uploaded_file_type, $allowed_file_types)) 
				{
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}					
					
					if(!is_dir($geodir_uploadpath)) {
						mkdir($geodir_uploadpath);
					}
					
					$external_img = false;
					if (strpos($curr_img_url,$uploads['baseurl']) !== false) {
					}
					else{
						$external_img = true;
					}
					
					if($dummy || $external_img ){
						$uploaded_file = array();
						$uploaded =  (array)fetch_remote_file($curr_img_url);
						
						if(empty($uploaded['error'])){
							$new_name = basename($uploaded['file']);
							$uploaded_file = $uploaded;
						}	
						$external_img = false;
					} else {
						$new_name = $post_id . '_' . $file_name;
						
						if ( $curr_img_dir == $sub_dir ) {	
							$img_path = $geodir_uploadpath.'/'.$filename;
							$img_url = $geodir_uploadurl.'/'.$filename;
						} else {
							$img_path = $uploads_dir.'/temp_'.$current_user->data->ID.'/'.$filename;
							$img_url = $uploads['url'].'/temp_'.$current_user->data->ID.'/'.$filename;
						}	
						
						$uploaded_file = '';
						
						if( file_exists($img_path) ){
							$uploaded_file = copy($img_path, $geodir_uploadpath.'/'.$new_name);$file_path ='';
						} else if (file_exists($uploads['basedir'].$curr_img_dir.$filename)) {
							$uploaded_file = true;
							$file_path = $curr_img_dir.'/'.$filename;
						}
						
						if($curr_img_dir != $geodir_uploaddir && file_exists($img_path))	
							unlink($img_path);
					}
										
					if(!empty($uploaded_file)) 
					{
						if(!isset($file_path) || !$file_path){$file_path = $sub_dir.'/'.$new_name;}
						
						$postcurr_images[] = $uploads['baseurl'].$file_path; 
						
						if($menu_order == 1){
					
							$wpdb->query($wpdb->prepare("UPDATE ".$table." SET featured_image = %s where post_id =%d", array($file_path,$post_id)));
						
						}
						
						// Set up options array to add this file as an attachment
						$attachment = array(); 
						$attachment['post_id'] = $post_id;
						$attachment['title'] = $file_title;
						$attachment['content'] = '';
						$attachment['file'] = $file_path;					
						$attachment['mime_type'] = $uploaded_file_type;
						$attachment['menu_order'] = $menu_order;
						$attachment['is_featured'] = 0;
						 
						$attachment_set = '';
						
						foreach($attachment as $key=>$val)
						{
							if($val != '')
							$attachment_set .= $key." = '".$val."', ";
						}
						
						$attachment_set = trim($attachment_set,", ");
						
						$wpdb->query("INSERT INTO ".GEODIR_ATTACHMENT_TABLE." SET ".$attachment_set);
						
						$valid_file_ids[] = $wpdb->insert_id;
					} 
					
				}
				
				
			}else
			{
				$valid_file_ids[] = $find_image;
				
				$postcurr_images[] = $post_image[$m];
				
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE ".GEODIR_ATTACHMENT_TABLE." SET menu_order = %d where file =%s AND post_id =%d",
						array($menu_order,$split_img_path[1],$post_id)
					)
				);

				if($menu_order == 1)
					$wpdb->query($wpdb->prepare("UPDATE ".$table." SET featured_image = %s where post_id =%d", array($split_img_path[1],$post_id)));
							
			}
			
			
		}
		
		if(!empty($valid_file_ids)){
			
			$remove_files = $valid_file_ids;
			
			$remove_files_length = count($remove_files);
			$remove_files_format = array_fill(0, $remove_files_length, '%d');
			$format = implode(',', $remove_files_format);	
			$valid_files_condition = " ID NOT IN ($format) AND ";
		
		}
		
		//Get and remove all old images of post from database to set by new order

		if(!empty($post_images)){
		
			foreach($post_images as $img){
			
				if(!in_array($img->src, $postcurr_images)){
				
					$invalid_files[] = (object)array('src' => $img->src);
					
				}
				
			}
			
		}
		
		$invalid_files = (object) $invalid_files;
	}
	
	$remove_files[] = $post_id;
	
	$wpdb->query($wpdb->prepare("DELETE FROM ".GEODIR_ATTACHMENT_TABLE." WHERE ".$valid_files_condition." post_id = %d",$remove_files));
	
	if(!empty($invalid_files))
			geodir_remove_attachments($invalid_files);
			
			
}
}

/**
*Remove users Temp images 
**/
function geodir_remove_temp_images(){
	
	global $current_user;
	
	$uploads = wp_upload_dir(); 
	$uploads_dir = $uploads['path'];
	
/*	if(is_dir($uploads_dir.'/temp_'.$current_user->data->ID)){
					
			$dirPath = $uploads_dir.'/temp_'.$current_user->data->ID;
			if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
				$dirPath .= '/';
			}
			$files = glob($dirPath . '*', GLOB_MARK);
			foreach ($files as $file) {
				if (is_dir($file)) {
					self::deleteDir($file);
				} else {
					unlink($file);
				}
			}
			rmdir($dirPath);
	}	*/
	
	$dirname = $uploads_dir.'/temp_'.$current_user->data->ID;
	geodir_delete_directory($dirname);
}


function geodir_delete_directory($dirname) {
	$dir_handle = '';
	if (is_dir($dirname))
	 $dir_handle = opendir($dirname);
	if (!$dir_handle)
			return false;
	while($file = readdir($dir_handle)) {
			 if ($file != "." && $file != "..") {
						if (!is_dir($dirname."/".$file))
							unlink($dirname."/".$file);
						else
							geodir_delete_directory($dirname.'/'.$file);
			 }
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
	
}


/**
 * Remove post attachments
 */
if (!function_exists('geodir_remove_attachments')) {
function geodir_remove_attachments($postcurr_images = array()){
	// Unlink all past images of post
	if(!empty($postcurr_images)){
		
		$uploads = wp_upload_dir(); 
		$uploads_dir = $uploads['path'];	
		
		foreach($postcurr_images as $postimg){
			$image_name_arr = explode('/',$postimg->src);
			$filename = end($image_name_arr);
			if( file_exists($uploads_dir.'/'.$filename) )
				unlink($uploads_dir.'/'.$filename);
		}
		
	} // endif 
	// Unlink all past images of post end
}
}

/**
 * Gets the post featured image
 */ 
 
if (!function_exists('geodir_get_featured_image')) {
function geodir_get_featured_image( $post_id = '', $size = '' ,$no_image = false, $file = false ) {
	
	global $wpdb,$plugin_prefix,$post;
	
	if(isset($post->ID) && isset($post->post_type) && $post->ID == $post_id){
	$post_type = $post->post_type;	
	}else{
	$post_type = get_post_type( $post_id );
	}
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	if(!$file){
		if(isset($post->featured_image)){$file =	$post->featured_image;}
		else{$file =	$wpdb->get_var($wpdb->prepare("SELECT featured_image FROM ".$table." WHERE post_id = %d", array($post_id)));}
		}
	
	if ( $file != NULL && $file!= '' && ( ($uploads = wp_upload_dir()) && false === $uploads['error'] )){
	   
	 $img_arr = array();
	 
	 $file_info = pathinfo($file);
			$sub_dir = '';
		if($file_info['dirname'] != '.' && $file_info['dirname'] != '..')
			$sub_dir = stripslashes_deep($file_info['dirname']);
		
			$uploads = wp_upload_dir(trim($sub_dir, '/')); // Array of key => value pairs	
			$uploads_baseurl = $uploads['baseurl'];
			$uploads_path = $uploads['path'];
	
	
			$file_name =  $file_info['basename'];
		
			$uploads_url = $uploads_baseurl.$sub_dir;
			$img_arr['src'] = $uploads_url.'/'.$file_name ;
			$img_arr['path'] = $uploads_path.'/'.$file_name ;
			@list($width, $height) = getimagesize($img_arr['path']);
			$img_arr['width'] = $width;
			$img_arr['height'] = $height;
			$img_arr['title'] = '';
	
	   		
	}elseif($post_images = geodir_get_images($post_id, $size, $no_image,1)){ 
		
		foreach($post_images as $image){
			return $image;
		}
		
	}elseif($no_image){	
		
		$img_arr = array();
		
		$default_img = '';
		if(isset($post->default_category) && $post->default_category){$default_cat = $post->default_category;}
		else{$default_cat = geodir_get_post_meta($post_id, 'default_category', true);}
		
		if($default_catimg = geodir_get_default_catimage($default_cat,$post_type))
			$default_img = $default_catimg['src'];
		elseif($no_image){
			$default_img = get_option('geodir_listing_no_img');
		}
		
		if(!empty($default_img)){
			
			$uploads = wp_upload_dir(); // Array of key => value pairs	
			$uploads_baseurl = $uploads['baseurl'];
			$uploads_path = $uploads['path'];
			
			$img_arr = array();
			
			$file_info = pathinfo($default_img);
			
			$file_name =  $file_info['basename'];
			
			$img_arr['src'] = $default_img;
			$img_arr['path'] = $uploads_path.'/'.$file_name ;
			
			@list($width, $height) = getimagesize($img_arr['path']);
			$img_arr['width'] = $width;
			$img_arr['height'] = $height;
			
			$img_arr['title'] = ''; // add the title to the array
			
		}
		 
	}
	
	if ( !empty($img_arr) )
			return (object)$img_arr;//return (object)array( 'src' => $file_url, 'path' => $file_path );
	else 
		return false; 
	
}
}

 
/**
 * Gets the post featured image
 */ 
 
if (!function_exists('geodir_show_featured_image')) {
function geodir_show_featured_image( $post_id = '', $size = 'thumbnail', $no_image = false, $echo = true, $fimage = false ) {
	
	$image = geodir_get_featured_image( $post_id, $size, $no_image, $fimage);
	
	
	$html = geodir_show_image( $image, $size, $no_image, false);
	
	if( !empty($html) && $echo){
		echo $html;
	}elseif( !empty($html) ){
		return $html;
	}else 
		return false; 
	
}
}

/**
 * Gets the post images
 */ 

if (!function_exists('geodir_get_images')) {
function geodir_get_images($post_id = 0, $img_size='', $no_images =false, $add_featured = true, $limit=''){
	
	global $wpdb;
	if($limit){$limit_q = " LIMIT $limit ";}else{$limit_q = '';}
	$not_featured = '';
	$sub_dir = '';
	if(!$add_featured)
		$not_featured = " AND is_featured = 0 ";
	
	$arrImages =	$wpdb->get_results(
									$wpdb->prepare(
										"SELECT * FROM ".GEODIR_ATTACHMENT_TABLE." WHERE mime_type like %s AND post_id = %d". $not_featured . " ORDER BY menu_order ASC, ID DESC $limit_q ",
										array('%image%',$post_id)
									)
								);
		
	$counter = 0;
	$return_arr = array();
	
	
	
	if(!empty($arrImages)) 
	{		
	   foreach($arrImages as $attechment)
	   {
			$img_arr = array();
			$img_arr['id'] = $attechment->ID;
			
			$file_info = pathinfo($attechment->file);
			
			if($file_info['dirname'] != '.' && $file_info['dirname'] != '..')
				$sub_dir = stripslashes_deep($file_info['dirname']);
			
			$uploads = wp_upload_dir(trim($sub_dir, '/')); // Array of key => value pairs	
			$uploads_baseurl = $uploads['baseurl'];
			$uploads_path = $uploads['path'];
	
			
			
			$file_name =  $file_info['basename'];
		
			$uploads_url = $uploads_baseurl.$sub_dir;
			$img_arr['src'] = $uploads_url.'/'.$file_name ;
			$img_arr['path'] = $uploads_path.'/'.$file_name ;
			@list($width, $height) = getimagesize($img_arr['path']);
			$img_arr['width'] = $width;
			$img_arr['height'] = $height;
			
			$img_arr['file'] = $file_name; // add the title to the array
			$img_arr['title'] = $attechment->title; // add the title to the array
			$img_arr['content'] = $attechment->content; // add the description to the array
			
			$return_arr[] = (object)$img_arr;
			
			
			$counter++;
			
	   }
	   return (object)$return_arr;
	}
	else if($no_images){
		$default_img = '';
		$default_cat = geodir_get_post_meta($post_id, 'default_category', true);
		$post_type = get_post_type( $post_id );
		if($default_catimg = geodir_get_default_catimage($default_cat,$post_type))
			$default_img = $default_catimg['src'];
		elseif($no_images){
			$default_img = get_option('geodir_listing_no_img');
		}
		
		if(!empty($default_img)){
			
			$uploads = wp_upload_dir(); // Array of key => value pairs	
			$uploads_baseurl = $uploads['baseurl'];
			$uploads_path = $uploads['path'];
			
			$img_arr = array();
			
			$file_info = pathinfo($default_img);
			
			$file_name =  $file_info['basename'];
		
			$img_arr['src'] = $default_img;
			$img_arr['path'] = $uploads_path.'/'.$file_name ;
			
			@list($width, $height) = getimagesize($img_arr['path']);
			$img_arr['width'] = $width;
			$img_arr['height'] = $height;
			
			$img_arr['file'] = $file_name; // add the title to the array
			$img_arr['title'] = $file_name; // add the title to the array
			$img_arr['content'] = $file_name; // add the description to the array
			
			$return_arr[] = (object)$img_arr;
			
			return $return_arr;
			
		}else
			return false;
				
	}
	
}
}


/**
 * Show image
 */ 
 
if (!function_exists('geodir_show_image')) {
function geodir_show_image( $request = array(), $size = 'thumbnail' ,$no_image = false, $echo = true ) {
	
	$image = new stdClass();
	
	$html = '';
	if( !empty($request) ){
		
		if(!is_object($request))
			$request = (object)$request;
			
		@list($width, $height) = getimagesize($request->path);
		$image->src = $request->src ;
		$image->width = $width;
		$image->height = $height;
		
		$max_size = (object)geodir_get_imagesize($size);
		
		if( !is_wp_error($max_size) ){
			
			
			if( $image->width ){
				if( $image->height >= $image->width ){
					$width_per = round(((($image->width*($max_size->h/$image->height))/$max_size->w)*100),2);
				}elseif($image->width < ($max_size->h) ){
					$width_per = round((($image->width/$max_size->w)*100),2);
				}else
					$width_per = 100;
			}
			
			//$html = '<div class="geodir_thumbnail" style="background-image:url(\''.$image->src.'\');"></div>';

			//$html = '<div class="geodir_thumbnail"><img style="max-height:'. $max_size->h .'px;" alt="place image" src="' . $image->src . '"  /></div>';
			//print_r($_REQUEST);
			if(is_admin() && !isset($_REQUEST['geodir_ajax'])):
				$html = '<div class="geodir_thumbnail"><img style="max-height:'. $max_size->h .'px;" alt="place image" src="' . $image->src . '"  /></div>';
			else : 
				$html = '<div class="geodir_thumbnail" style="background-image:url(\''.$image->src.'\');"></div>';
			endif;
			
			/*$html = '<div style="text-align: center;max-height:'.$max_size->h.'px;line-height:'.$max_size->h.'px;">';
			
			$html .= '<img src="' . $image->src . '" style="vertical-align:middle;max-height:'. $max_size->h .'px;margin:-2px auto 0;';
			
			$html .= 'max-width:'.$width_per.'%';
			
			$html .= '" /></div>'; */
			
		}
		
	}
	
	if( !empty($html) && $echo){
		echo $html;
	}elseif( !empty($html) ){
		return $html;
	}else 
		return false; 
	
}
}

/**
 * Set post Categories
 **/

if (!function_exists('geodir_set_post_terms')) {
function geodir_set_post_terms($post_id, $terms, $tt_ids, $taxonomy){
	global $wpdb,$plugin_prefix;

	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	if( in_array( $post_type, geodir_get_posttypes() ) && !wp_is_post_revision( $post_id ) ) {
		
		if ( strstr( $taxonomy, 'tag' ) ) {
			if ( isset( $_POST['action'] ) && $_POST['action'] == 'inline-save' ) {
				geodir_save_post_meta( $post_id, 'post_tags', $terms );
			}
		} elseif($taxonomy == $post_type.'category') {
			$srcharr = array('"','\\');
			$replarr = array("&quot;",''); 
			
			$post_obj = get_post($post_id);
			
			$cat_ids = array('0');
			if(is_array($tt_ids))
				$cat_ids = $tt_ids;
			
			
			if(!empty($cat_ids)){
				$cat_ids_array = $cat_ids;
				$cat_ids_length = count($cat_ids_array);
				$cat_ids_format = array_fill(0, $cat_ids_length, '%d');
				$format = implode(',', $cat_ids_format);	
				
				$cat_ids_array_del = $cat_ids_array;
				$cat_ids_array_del[] = $post_id;
				
				$wpdb->get_var(
					$wpdb->prepare(
						"DELETE from ".GEODIR_ICON_TABLE." WHERE cat_id NOT IN ($format) AND post_id = %d ",
						$cat_ids_array_del
					)
				);
				
				
				$post_term =	$wpdb->get_col(
												$wpdb->prepare(
													"SELECT term_id FROM ".$wpdb->term_taxonomy ." WHERE term_taxonomy_id IN($format) GROUP BY term_id",
													$cat_ids_array
												)
											);
			
			}
			
			$post_marker_json = '';
			
			if(!empty($post_term)):
			
				foreach($post_term as $cat_id):
					
					$term_icon_url = get_tax_meta($cat_id,'ct_cat_icon', false, $post_type);
					$term_icon = isset($term_icon_url['src']) ? $term_icon_url['src'] : '';
					
					$post_title = $post_obj->title;
					$title = str_replace($srcharr,$replarr,$post_title);
					
					$lat = geodir_get_post_meta($post_id,'post_latitude',true);
					$lng = geodir_get_post_meta($post_id,'post_longitude',true);
							
					$timing = ' - '.date('D M j, Y', strtotime(geodir_get_post_meta($post_id,'st_date',true)));			
					$timing .= ' - '.geodir_get_post_meta($post_id,'st_time',true);			
					
					$json ='{';
					$json .= '"id":"'.$post_id.'",';
					$json .= '"lat_pos": "'.$lat.'",';
					$json .= '"long_pos": "'.$lng.'",';
					$json .= '"marker_id":"'.$post_id.'_'.$cat_id.'",';
					$json .= '"icon":"'.$term_icon.'",';
					$json .= '"group":"catgroup'.$cat_id.'"';
					$json .= '}';
					
					
					if($cat_id == geodir_get_post_meta($post_id, 'default_category',true))
						$post_marker_json  = $json; 
					
					
					if($wpdb->get_var($wpdb->prepare("SELECT post_id from ".GEODIR_ICON_TABLE." WHERE post_id = %d AND cat_id = %d",array($post_id,$cat_id) )))
					{
						
						$json_query = $wpdb->prepare("UPDATE ".GEODIR_ICON_TABLE." SET 
										post_title = %s, 
										json = %s 
										WHERE post_id = %d AND cat_id = %d ",
										array($post_title,$json,$post_id,$cat_id));
										
					}else{
						
						$json_query = $wpdb->prepare("INSERT INTO ".GEODIR_ICON_TABLE." SET 
										post_id = %d, 
										post_title = %s, 
										cat_id = %d,
										json = %s",
										array($post_id,$post_title,$cat_id,$json));
										
					}
					
					$wpdb->query($json_query);
					
				endforeach;			
			
			endif;
			
			if(!empty($post_term) && is_array($post_term))
			{
				$categories = implode(',', $post_term);
				
				if($categories != '' && $categories != 0) $categories = ','.$categories.',';
				
				if(empty($post_marker_json))
					$post_marker_json = isset($json) ? $json : '';
				
				if($wpdb->get_var($wpdb->prepare("SELECT post_id from ".$table." where post_id = %d",array($post_id))))
				{
	
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE ".$table." SET 
								".$taxonomy." = %s, 
								marker_json = %s
								where post_id = %d",
								array($categories,$post_marker_json,$post_id)
							)
						);
						
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'inline-save'){
						
							$categories = trim($categories,',');
							
							if($categories){
								
								$categories = explode(',', $categories);
								
								$default_category = geodir_get_post_meta($post_id,'default_category',true);
								
								if(!in_array($default_category, $categories)){
									
									$wpdb->query(
										$wpdb->prepare(
											"UPDATE ".$table." SET 
											default_category = %s
											where post_id = %d",
											array($categories[0],$post_id)
										)
									);
									
									$default_category = $categories[0];
									
								}
								
								if($default_category == '')
									$default_category = $categories[0];
								
								geodir_set_postcat_structure($post_id,$taxonomy,$default_category,'');
			
							}
	
						}
						
									
				}else
				{
						
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO ".$table." SET 
								post_id = %d, 
								".$taxonomy." = %s,
								marker_json = %s ",
								
								array($post_id,$categories,$post_marker_json)
							)
						);
				}
			}
		}
	}
}
}


/**
 * Set post Map Marker Info Html
 **/
if (!function_exists('geodir_get_infowindow_html')) {
	function geodir_get_infowindow_html($postinfo_obj, $post_preview = '') {
		global $preview;
		$srcharr = array("'","/","-",'"','\\');
		$replarr = array("&prime;","&frasl;","&ndash;","&ldquo;",'');
		
		if (isset($_SESSION['listing']) && isset($post_preview) && $post_preview != '') {	
			$ID = '';
			$plink = '';
			
			if (isset($postinfo_obj->pid)) {
				$ID = $postinfo_obj->pid;
				$plink = get_permalink($ID);
			}
		
			$title = str_replace($srcharr,$replarr,($postinfo_obj->post_title));
			$lat = $postinfo_obj->post_latitude;
			$lng = $postinfo_obj->post_longitude;
			$address = str_replace($srcharr,$replarr,($postinfo_obj->post_address));
			$contact = str_replace($srcharr,$replarr,($postinfo_obj->geodir_contact));
			$timing = str_replace($srcharr,$replarr,($postinfo_obj->geodir_timing));
		} else {
			$ID = $postinfo_obj->post_id;
			$title = str_replace($srcharr,$replarr,htmlentities($postinfo_obj->post_title, ENT_COMPAT, 'UTF-8')); // fix by Stiofan
			$plink = get_permalink($ID);
			$lat = htmlentities(geodir_get_post_meta($ID,'post_latitude',true));
			$lng = htmlentities(geodir_get_post_meta($ID,'post_longitude',true));
			$address = str_replace($srcharr,$replarr,htmlentities(geodir_get_post_meta($ID,'post_address',true), ENT_COMPAT, 'UTF-8')); // fix by Stiofan
			$contact = str_replace($srcharr,$replarr,htmlentities(geodir_get_post_meta($ID,'geodir_contact',true), ENT_COMPAT, 'UTF-8'));
			$timing = str_replace($srcharr,$replarr,(geodir_get_post_meta($ID,'geodir_timing',true)));
		}
	
		// filter field as per price package
		global $geodir_addon_list;
		if (isset($geodir_addon_list['geodir_payment_manager']) && $geodir_addon_list['geodir_payment_manager']=='yes') {
			$post_type = get_post_type($ID);
			$package_id = isset($postinfo_obj->package_id) && $postinfo_obj->package_id ? $postinfo_obj->package_id : NULL;		
			$field_name = 'geodir_contact';
			if (!check_field_visibility($package_id, $field_name, $post_type)) {
				$contact = '';
			}
			
			$field_name = 'geodir_timing';
			if (!check_field_visibility($package_id, $field_name, $post_type)) {
				$timing = '';
			}
		}
	
		if ($lat && $lng) {
			ob_start(); ?>
			<div class="bubble">
				<div style="position: relative;margin:5px 0px;">
				<?php
			$comment_count = '';
			$rating_star = '';
			if ($ID != '') {
				$rating_star = '';
				$comment_count = geodir_get_review_count_total($ID);
				
				if (!$preview) {
					$post_avgratings = geodir_get_post_rating($ID);
					
					$rating_star = geodir_get_rating_stars($post_avgratings,$ID,false);
					$rating_star = apply_filters('geodir_review_rating_stars_on_infowindow', $rating_star, $post_avgratings, $ID);
				}
			}
			?>	
			<div class="geodir-bubble_desc">
				<h4>
					<a href="<?php if($plink!= ''){ echo $plink;}else{ echo 'javascript:void(0);';}?>"><?php echo $title;?></a>
				</h4>
            <?php
			if (isset($_SESSION['listing']) && isset($post_preview) && $post_preview != '') {
				$post_images = array();
				if (!empty($postinfo_obj->post_images)) {
					$post_images = explode(",",$postinfo_obj->post_images);
				}
				
				if (!empty($post_images)) {
				?>
				<div class="geodir-bubble_image"><a href="<?php if($plink!= ''){ echo $plink;}else{ echo 'javascript:void(0);';}?>"><img alt="bubble image" style="max-height:50px;" src="<?php echo $post_images[0];?>" /></a></div>
				<?php
				}
			} else {
				if ($image = geodir_show_featured_image($ID,'widget-thumb',true,false,$postinfo_obj->featured_image)) {
				?>
				<div class="geodir-bubble_image" ><a href="<?php echo $plink;?>"><?php echo $image; ?></a></div>
				<?php 
				}
			}
			?>
			<div class="geodir-bubble-meta-side">
				<span class="geodir_address"><i class="fa fa-home"></i> <?php echo $address;?></span>
				<?php if($contact){?><span class="geodir_contact"><i class="fa fa-phone"></i> <?php echo $contact;?></span><?php }?>
				<?php if($timing){?><span class="geodir_timing"><i class="fa fa-clock-o"></i> <?php echo $timing;?></span><?php }?>
			</div>
			<?php
				if (isset($postinfo_obj->recurring_dates)) {
					$recuring_data = unserialize($postinfo_obj->recurring_dates);
					$output = '';
					$output .= '<div class="geodir_event_schedule">';	
					
					$event_recurring_dates = explode(',', $recuring_data['event_recurring_dates']);
					
					$starttimes = isset($recuring_data['starttime']) ? $recuring_data['starttime'] : '';
					$endtimes = isset($recuring_data['endtime']) ? $recuring_data['endtime'] : '';
					$e=0;		
					foreach ($event_recurring_dates as $key => $date) {
						
						if(strtotime($date) < strtotime(date("Y-m-d"))){continue;} // if the event is old don't show it on the map
						$e++;
						if($e==2){break;}// only show 3 event dates
						$output .=  '<p>';
						//$geodir_num_dates++;
						if(isset($recuring_data['different_times']) && $recuring_data['different_times'] == '1'){
							$starttimes = isset($recuring_data['starttimes'][$key]) ? $recuring_data['starttimes'][$key] : '';
							$endtimes = isset($recuring_data['endtimes'][$key]) ? $recuring_data['endtimes'][$key] : '';
						}	
						
						$sdate = strtotime($date.' '.$starttimes);
						$edate = strtotime($date.' '.$endtimes);
						
						if($starttimes > $endtimes){
							$edate = strtotime($date.' '.$endtimes . " +1 day");
						}
						
						

						global $geodir_date_time_format; 	
						
						$output .=  '<i class="fa fa-caret-right"></i>'.date($geodir_date_time_format, $sdate);
							//$output .=  __(' To', GEODIREVENTS_TEXTDOMAIN).' ';
							$output .= '<br />';
						$output .=  '<i class="fa fa-caret-left"></i>'.date($geodir_date_time_format, $edate);//.'<br />';
						$output .=  '</p>';	
					}
					
					$output .= '</div>';
					
					echo $output;
		}
		
		
					if($ID){
		
						$post_author = isset($postinfo_obj->post_author) ? $postinfo_obj->post_author : get_post_field( 'post_author', $ID );
						?>

              <div class="geodir-bubble-meta-bottom">
                  <span class="geodir-bubble-rating"><?php echo $rating_star;?></span>
                  
                  <span class="geodir-bubble-fav"><?php echo geodir_favourite_html($post_author,$ID);?></span>
                  <span class="geodir-bubble-reviews"><a href="<?php echo get_comments_link( $ID ); ?>" class="geodir-pcomments"><i class="fa fa-comments"></i>
                            <?php echo get_comments_number( $ID ); ?>
                                     </a></span>
              </div>
 
       <?php }?> 
			       
			</div>             					
		</div>
	</div>
	<?php 
	$html = ob_get_clean();
	$html = apply_filters('geodir_custom_infowindow_html' ,$html ,$postinfo_obj, $post_preview   ) ;
	return $html;
	}
}
}




/**
 * Update post status
 */ 

if (!function_exists('geodir_new_post_default_status')) {
function geodir_new_post_default_status()
{
	if(get_option('geodir_new_post_default_status'))
		return get_option('geodir_new_post_default_status');
	else
		return 'publish';

}}

if(!function_exists('geodir_change_post_status')){
function geodir_change_post_status($post_id = '', $status = '')
{
	global $wpdb,$plugin_prefix;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE ".$table." SET post_status=%s WHERE post_id=%d",
			array($status,$post_id)
		)
	);
	
	
	
}
}

function geodir_set_post_status($pid,$status) 
{
	if($pid)
	{
		global $wpdb;
		$my_post = array();
		$my_post['post_status'] = $status; 
		$my_post['ID'] = $pid;
		$last_postid = wp_update_post($my_post);
	}
}


function geodir_update_poststatus($new_status, $old_status, $post){
	global $wpdb;	
	
	$geodir_posttypes = geodir_get_posttypes();
	
	if ( ! wp_is_post_revision( $post->ID )  && in_array($post->post_type,$geodir_posttypes )  ){
	
		geodir_change_post_status($post->ID,$new_status);
	}
}


/**
 * Update post info
 */ 
if (!function_exists('geodir_update_listing_info')) {
function geodir_update_listing_info($updatingpost,$temppost){
	
	global $wpdb,$plugin_prefix;
	
	$post_type = get_post_type( $post_id );
	
	$table = $plugin_prefix . $post_type . '_detail';
	
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE ".$table." SET `post_id` = %d WHERE `post_id` = %d",
			array($updatingpost,$temppost)
		)
	);
	
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE ".GEODIR_ICON_TABLE." SET `post_id` = %d WHERE `post_id` = %d",
			array($updatingpost,$temppost)
		)
	);
	
	/* Update Attachments*/
	
	$wpdb->query(
	$wpdb->prepare(
		"UPDATE ".GEODIR_ATTACHMENT_TABLE." SET `post_id` = %d WHERE `post_id` = %d",
		array($updatingpost,$temppost)
	)
	);
	
}
}


/**
 * Delete Listing
 **/

if (!function_exists('geodir_delete_listing_info')) {
function geodir_delete_listing_info($deleted_postid, $force = false){
	global $wpdb,$plugin_prefix;

	// check for multisite deletions
	if (strpos($plugin_prefix,$wpdb->prefix) !== false) {}else{return;}

	$post_type = get_post_type( $deleted_postid );

	$all_postypes = geodir_get_posttypes();

	if(!in_array($post_type, $all_postypes))
		return false;
	
	$table = $plugin_prefix . $post_type . '_detail';

	/* Delete custom post meta*/	
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM ".$table." WHERE `post_id` = %d",
			array($deleted_postid)
		)
	);
	
	/* Delete post map icons*/
	
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM ".GEODIR_ICON_TABLE." WHERE `post_id` = %d",
			array($deleted_postid)
		)
	);
	
	/* Delete Attachments*/
	$postcurr_images = geodir_get_images($deleted_postid);
	
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM ".GEODIR_ATTACHMENT_TABLE." WHERE `post_id` = %d",
			array($deleted_postid)
		)
	);
	geodir_remove_attachments($postcurr_images);
		
}
}


/**
 * This function would add listing to favorite listing 
**/
if (!function_exists('geodir_add_to_favorite')) {
function geodir_add_to_favorite($post_id){

	global $current_user;
	
	$user_meta_data = array();
	
	$user_meta_data = get_user_meta($current_user->data->ID,'gd_user_favourite_post', true);
	
	if(empty($user_meta_data) || (!empty($user_meta_data) && !in_array($post_id, $user_meta_data))){
		$user_meta_data[]=$post_id;
	}
	
	update_user_meta($current_user->data->ID, 'gd_user_favourite_post', $user_meta_data);
	
	do_action('geodir_before_add_from_favorite', $post_id);
	
	echo '<a href="javascript:void(0);" title="'.REMOVE_FAVOURITE_TEXT.'" class="geodir-addtofav geodir-removetofav-icon" onclick="javascript:addToFavourite(\''.$post_id.'\',\'remove\');"><i class="fa fa-heart"></i> '.UNFAVOURITE_TEXT.'</a>';
	
	do_action('geodir_after_add_from_favorite', $post_id);

}
}

/**
 * This function would remove the favorited property earlier
**/
if (!function_exists('geodir_remove_from_favorite')) {
function geodir_remove_from_favorite($post_id){

	global $current_user;
	
	$user_meta_data = array();

	$user_meta_data = get_user_meta($current_user->data->ID,'gd_user_favourite_post', true);

	if(!empty($user_meta_data)){
					
		if(($key = array_search($post_id, $user_meta_data)) !== false) {
				unset($user_meta_data[$key]);
		}
	
	}
	
	update_user_meta($current_user->data->ID, 'gd_user_favourite_post', $user_meta_data); 	
	
	do_action('geodir_before_remove_from_favorite', $post_id);
	
	echo '<a href="javascript:void(0);"  title="'.ADD_FAVOURITE_TEXT.'" class="geodir-addtofav geodir-addtofav-icon" onclick="javascript:addToFavourite(\''.$post_id.'\',\'add\');"><i class="fa fa-heart"></i> '.FAVOURITE_TEXT.'</a>';
	
	do_action('geodir_after_remove_from_favorite', $post_id);

}
}

/**
 * This function would disply the html content for add to favorite or remove from favorite 
**/

if (!function_exists('geodir_favourite_html')) {
function geodir_favourite_html($user_id,$post_id){

	global $current_user,$post;
	
	$user_meta_data =  '';
	if(isset($current_user->data->ID))
		$user_meta_data = get_user_meta($current_user->data->ID,'gd_user_favourite_post', true);

	if(!empty($user_meta_data) && in_array($post_id,$user_meta_data))
	{
		?><span class="geodir-addtofav favorite_property_<?php echo $post_id;?>"  ><a class="geodir-removetofav-icon" href="javascript:void(0);" onclick="javascript:addToFavourite(<?php echo $post_id;?>,'remove');" title="<?php echo REMOVE_FAVOURITE_TEXT;?>"><i class="fa fa-heart"></i> <?php echo UNFAVOURITE_TEXT;?></a>   </span><?php

	}else{
			
			if(!isset($current_user->data->ID) || $current_user->data->ID=='')
			{
				$script_text = 'javascript:window.location.href=\''.home_url('/?geodir_signup=true&amp;page1=sign_up').'\'' ;
			}
			else
				$script_text ='javascript:addToFavourite('.$post_id.',\'add\')';

		?><span class="geodir-addtofav favorite_property_<?php echo $post_id;?>"><a class="geodir-addtofav-icon" href="javascript:void(0);" onclick="<?php echo $script_text ;?>" title="<?php echo ADD_FAVOURITE_TEXT;?>"><i class="fa fa-heart"></i> <?php echo FAVOURITE_TEXT;?></a></span>
	<?php }     
} 
}  



function geodir_get_cat_postcount($term = array()){ 

	if(!empty($term)){
		
		global $wpdb,$plugin_prefix;
		
		$where = '';
		$join = '';
		if(get_query_var('gd_country') != '' || get_query_var('gd_region') != '' || get_query_var('gd_city') != '')
		{
			$taxonomy_obj = get_taxonomy($term->taxonomy);
			
			$post_type = $taxonomy_obj->object_type[0];
			
			$table = $plugin_prefix . $post_type . '_detail';
			
			$join = apply_filters('geodir_cat_post_count_join', $join, $term);
			$where = apply_filters('geodir_cat_post_count_where', $where, $term);
			
			$count_query = "SELECT count(post_id) FROM 
							".$table." as pd ".$join." 
							WHERE pd.post_status='publish' AND FIND_IN_SET('".$term->term_id."',".$term->taxonomy.") ". $where;
				
			$cat_post_count = $wpdb->get_var($count_query);
			if(empty($cat_post_count) || is_wp_error($cat_post_count) )	
				$cat_post_count = 0;
						
			return $cat_post_count;
			
		}else
				
			return $term->count;
	}
	return false;
	
} 


//ALLOW ADD POST TYPE FROM FRONT END

function geodir_allow_post_type_frontend()
{
	$geodir_allow_posttype_frontend = get_option('geodir_allow_posttype_frontend');
	
	if(	!is_admin() && isset($_REQUEST['listing_type']) 
		&& !empty($geodir_allow_posttype_frontend) 
		&& !in_array($_REQUEST['listing_type'], $geodir_allow_posttype_frontend)){
		
		wp_redirect( home_url() ); exit;
		
	}
	
}

// Changing excerpt length

function geodir_excerpt_length($length)
{
	global $wp_query;
	if(isset($wp_query->query_vars['is_geodir_loop']) && $wp_query->query_vars['is_geodir_loop'] && get_option('geodir_desc_word_limit'))
		$length = get_option('geodir_desc_word_limit');
	elseif(get_query_var('excerpt_length'))
			$length = get_query_var('excerpt_length');
			
	if(geodir_is_page('author') && get_option('geodir_author_desc_word_limit'))
		$length = get_option('geodir_author_desc_word_limit');
	
	return $length;
}

// Changing excerpt more

function geodir_excerpt_more($more) {
global $post;
return ' <a href="'.get_permalink($post->ID).'">'.READ_MORE_TXT.'</a>';
}


function geodir_update_markers_oncatedit($term_id, $tt_id, $taxonomy){
   global $plugin_prefix,$wpdb;
   
   $gd_taxonomies = geodir_get_taxonomies();
   
   if(is_array($gd_taxonomies) && in_array($taxonomy,$gd_taxonomies)){
   	
		$geodir_post_type = geodir_get_taxonomy_posttype($taxonomy);
		$table = $plugin_prefix . $geodir_post_type . '_detail';
		
		$path_parts = pathinfo($_REQUEST['ct_cat_icon']['src']);
		$term_icon = $path_parts['dirname'].'/cat_icon_'.$term_id.'.png';
		
		$posts = 	$wpdb->get_results(
								$wpdb->prepare(
									"SELECT post_id,post_title,post_latitude,post_longitude,default_category FROM ".$table." WHERE FIND_IN_SET(%s,%1\$s ) ",
									array($term_id,$taxonomy)
								)
							);
		
		if(!empty($posts)):	
		foreach($posts as $post_obj){
			
			$lat = $post_obj->post_latitude;
			$lng = $post_obj->post_longitude;
			
			$json ='{';
			$json .= '"id":"'.$post_obj->post_id.'",';
			$json .= '"lat_pos": "'.$lat.'",';
			$json .= '"long_pos": "'.$lng.'",';
			$json .= '"marker_id":"'.$post_obj->post_id.'_'.$term_id.'",';
			$json .= '"icon":"'.$term_icon.'",';
			$json .= '"group":"catgroup'.$term_id.'"';
			$json .= '}';
			
			if($post_obj->default_category == $term_id){
				
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE ".$table." SET marker_json = %s where post_id = %d",
						array($json,$post_obj->post_id) 
					)
				);
			}
			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE ".GEODIR_ICON_TABLE." SET json = %s WHERE post_id = %d AND cat_id = %d",
					array($json,$post_obj->post_id,$term_id)
				)
			);
			
		}
		
		
		endif;
		
   }
   
}

function geodir_get_listing_author($listing_id='')
{
	if($listing_id=='')
	{
		if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
		{
			$listing_id = $_REQUEST['pid'];
		}
	}
	$listing= get_post(strip_tags($listing_id));
	$listing_author_id = $listing->post_author;
	return $listing_author_id ;
}


function geodir_lisiting_belong_to_user($listing_id , $user_id)
{
	$listing_author_id = geodir_get_listing_author($listing_id) ;
	if($listing_author_id == $user_id)
		return true;
	else
		return false;
	
}

function geodir_listing_belong_to_current_user($listing_id='', $exclude_admin = true)
{
	global $current_user;
	if($exclude_admin)
	{
		foreach($current_user->caps as $key =>$caps)
		{ 
			if(strtolower($key) == 'administrator')
			{
				return true;
				break;
			}
		}
	}
	
	return  geodir_lisiting_belong_to_user($listing_id ,  $current_user->ID);
}


function geodir_only_supportable_attachments_remove($file){
	
		global $wpdb;
		
		$matches = array();
		
		$pattern = '/-\d+x\d+\./';
		preg_match($pattern, $file, $matches, PREG_OFFSET_CAPTURE);
		
		if(empty($matches))
			return '';
		else
			return $file;
	
}

//geodir_set_wp_featured_image(16);
// Written by Vikas on 13-06-2014 to set first image and wordpress post's featured image 
function geodir_set_wp_featured_image($post_id)
{
	
	global $wpdb, $plugin_prefix;
	$uploads = wp_upload_dir();
//	print_r($uploads ) ;
	$post_first_image =	$wpdb->get_results(
									$wpdb->prepare(
										"SELECT * FROM ".GEODIR_ATTACHMENT_TABLE." WHERE post_id = %d and menu_order = 1  ",	array($post_id)
									)
								);
	
	$old_attachment_name = '';
	$post_thumbnail_id = '';
	if(has_post_thumbnail( $post_id )){
		
		if(has_post_thumbnail( $post_id )){
		
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			
			$old_attachment_name = basename ( get_attached_file( $post_thumbnail_id ) );
			
		}
	}
	
	if(!empty($post_first_image))
	{
		
		$post_type = get_post_type( $post_id ) ;
		
		$table_name = $plugin_prefix.$post_type.'_detail';
			
		$wpdb->query("UPDATE ".$table_name." SET featured_image='".$post_first_image[0]->file."' WHERE post_id =".$post_id);
		
		$new_attachment_name = basename ( $post_first_image[0]->file );
		
		if(strtolower($new_attachment_name) != strtolower($old_attachment_name)){
			
			if(has_post_thumbnail( $post_id ) && $post_thumbnail_id != '' && (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'delete')){
				
				add_filter( 'wp_delete_file', 'geodir_only_supportable_attachments_remove' );
				
				wp_delete_attachment( $post_thumbnail_id );
			
			}
			$filename =$uploads['basedir'] . $post_first_image[0]->file ;
			
			$attachment = array(
				'post_mime_type' => $post_first_image[0]->mime_type ,
				'guid' => $uploads['baseurl'] . $post_first_image[0]->file,
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '',  $post_first_image[0]->title ),
				'post_content' => ''
			);
			
			
			$id = wp_insert_attachment( $attachment, $filename, $post_id );
			
			if ( ! is_wp_error( $id ) ) {
			
				set_post_thumbnail($post_id,$id);
				
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $filename ) );
				
			}
			
		}
		
	}
	else
	{
		//set_post_thumbnail($post_id,-1);
		
		if(has_post_thumbnail( $post_id ) && $post_thumbnail_id != '' && (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'delete'))
				wp_delete_attachment( $post_thumbnail_id );
		
	}
}


/*
Function to copy custom meta inof on WPML copy.
*/


function gd_copy_original_translation(){
if(function_exists('icl_object_id')){ 
global $wpdb,$table_prefix,$plugin_prefix;
$post_id = 	absint($_POST['post_id']);
 $upload_dir = wp_upload_dir(); 
	$post_type = get_post_type( $_POST['post_id'] );
	$table = $plugin_prefix . $post_type . '_detail';
	
	$post_arr =	$wpdb->get_results($wpdb->prepare(
										"SELECT * FROM $wpdb->posts p JOIN ".$table." gd ON gd.post_id=p.ID WHERE p.ID=%d LIMIT 1",
										array($post_id)
									)
								,ARRAY_A);
	
	$arrImages =	$wpdb->get_results(
									$wpdb->prepare(
										"SELECT * FROM ".GEODIR_ATTACHMENT_TABLE." WHERE mime_type like %s AND post_id = %d ORDER BY menu_order ASC, ID DESC ",
										array('%image%',$post_id)
									)
								);
if($arrImages){
	$image_arr = array();
	foreach($arrImages as $img){
		$image_arr[] = $upload_dir['baseurl'].$img->file;
	}
	$comma_separated = implode(",", $image_arr);
	$post_arr[0]['post_images']=$comma_separated;
}


$cats = $post_arr[0][$post_arr[0]['post_type'].'category'];
$cat_arr = array_filter(explode(",",$cats));
$trans_cat = array();
foreach($cat_arr as $cat){
$trans_cat[] = 	icl_object_id($cat, $post_arr[0]['post_type'].'category', false);
}


$post_arr[0]['categories']=array_filter($trans_cat);
//print_r($image_arr);
	//print_r($arrImages);
	//echo $_REQUEST['lang'];
//print_r($post_arr);
//print_r($trans_cat);
echo json_encode($post_arr[0]);

}
die();
}


add_action('wp_ajax_gd_copy_original_translation', 'gd_copy_original_translation');
//add_action('wp_ajax_nopriv_dc_update_profile', 'dc_update_profile_callback');




function geodir_get_custom_fields_type($listing_type = ''){
	
	global $wpdb;
	
	if($listing_type == '')
		$listing_type = 'gd_place';
	
	$fields_info = array();
	
	$get_data = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT htmlvar_name, field_type, extra_fields FROM ".GEODIR_CUSTOM_FIELDS_TABLE." WHERE post_type=%s AND is_active='1'",
			array($listing_type)
		)
	);
	
	if(!empty($get_data)){
		
		foreach($get_data as $data){
			
			if($data->field_type == 'address'){
				
				$extra_fields = unserialize($data->extra_fields);
				
				$prefix = $data->htmlvar_name.'_';
				
				$fields_info[$prefix.'address'] = $data->field_type;
				
				if(isset($extra_fields['show_zip']) && $extra_fields['show_zip'])
					$fields_info[$prefix.'zip'] = $data->field_type;
					
			}else{
				
				$fields_info[$data->htmlvar_name] = $data->field_type;
				
			}
			
		}
		
	}
	
	return apply_filters('geodir_get_custom_fields_type', $fields_info, $listing_type);
}

/* 
 * Called when post updated
 */
function geodir_function_post_updated($post_ID, $post_after, $post_before) {
	$post_type = get_post_type( $post_ID );
	
	if ($post_type != '' && in_array($post_type, geodir_get_posttypes())) {
		// send notification to client when post moves from draft to publish
		if (!empty($post_after->post_status) && $post_after->post_status == 'publish' && !empty($post_before->post_status) && ($post_before->post_status == 'draft' || $post_before->post_status == 'auto-draft')) {
			$post_author_id = !empty($post_after->post_author) ? $post_after->post_author : NULL;
			$post_author_data = get_userdata($post_author_id);
			
			$to_name = '';
			if (!empty($post_author_data)) {
				if (!empty($post_author_data->display_name)) {
					$to_name = $post_author_data->display_name;
				} else if ($post_author_data->user_nicename) {
					$to_name = $post_author_data->user_nicename;
				} else {
					$to_name = $post_author_data->user_login;
				}
			}
			
			$from_email = geodir_get_site_email_id();
			$from_name = get_site_emailName();
			$to_email = $post_author_data->user_email;
			
			if (!is_email($to_email) && !empty($post_author_data->user_email)) {
				$to_email = $post_author_data->user_email;
			}

			$message_type = 'listing_published';
			
			if (get_option('geodir_post_published_email_subject')=='') {
				update_option('geodir_post_published_email_subject', __('Listing Published Successfully', GEODIRECTORY_TEXTDOMAIN) );
			}
			
			if (get_option('geodir_post_published_email_content')=='') {
				update_option('geodir_post_published_email_content', __("<p>Dear [#client_name#],</p><p>Your listing [#listing_link#] has been published. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>", GEODIRECTORY_TEXTDOMAIN));
			}
			
			do_action('geodir_before_listing_published_email', $post_after, $post_before);
			if (is_email($to_email)) {
				geodir_sendEmail($from_email, $from_name, $to_email, $to_name, '', '', '', $message_type, $post_ID);
			}
			do_action('geodir_after_listing_published_email', $post_after, $post_before);
		}
	}
}