<?php

/**
 * Deactivate geodirectory
 */
function geodir_deactivation() {
	
	// Update installed variable
	update_option( "geodir_installed", 0 );
	
	// Remove rewrite rules and then recreate rewrite rules.
	flush_rewrite_rules();
}


function geodir_uninstall(){

	delete_option('geodir_default_data_installed');
	
}

 
/**
 * Enque Admin Styles
 **/

if (!function_exists('geodir_admin_styles')) {
	function geodir_admin_styles(){ 
		
		wp_register_style( 'geodirectory-admin-css', geodir_plugin_url().'/geodirectory-assets/css/admin.css',array(),GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodirectory-admin-css' );
		
		wp_register_style( 'geodirectory-frontend-style', geodir_plugin_url().'/geodirectory-assets/css/style.css',array(),GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodirectory-frontend-style' );
		
		wp_register_style( 'geodir-chosen-style', geodir_plugin_url() .'/geodirectory-assets/css/chosen.css' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_style( 'geodir-chosen-style' );
		
		wp_register_style( 'geodirectory-jquery-ui-timepicker-css', geodir_plugin_url().'/geodirectory-assets/ui/jquery.ui.timepicker.css' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_style( 'geodirectory-jquery-ui-timepicker-css' );
		
		wp_register_style( 'geodir-jslider-style', geodir_plugin_url() .'/geodirectory-assets/css/jslider.css' ,array(),GEODIRECTORY_VERSION);
	wp_enqueue_style( 'geodir-jslider-style' );
		
		wp_register_style( 'geodirectory-jquery-ui-css', geodir_plugin_url().'/geodirectory-assets/ui/jquery-ui.css' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_style( 'geodirectory-jquery-ui-css' );
		
		wp_register_style( 'geodirectory-custom-fields-css', geodir_plugin_url().'/geodirectory-assets/css/custom_field.css' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_style( 'geodirectory-custom-fields-css' );
		
		wp_register_style( 'geodirectory-pluplodar-css', geodir_plugin_url().'/geodirectory-assets/css/pluploader.css',array(),GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodirectory-pluplodar-css' );
		
		wp_register_style( 'geodir-rating-style', geodir_plugin_url() .'/geodirectory-assets/css/jRating.jquery.css',array(),GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodir-rating-style' );
		
		wp_register_style( 'geodir-rtl-style', geodir_plugin_url() . '/geodirectory-assets/css/rtl.css', array(), GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodir-rtl-style' );	
		
	}
}	

if (!function_exists('geodir_admin_styles_req')) {
	function geodir_admin_styles_req(){ 

		wp_register_style( 'geodirectory-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), GEODIRECTORY_VERSION );
		wp_enqueue_style( 'geodirectory-font-awesome');
	}
}

/**
 * Enque Admin Scripts
 **/
if (!function_exists('geodir_admin_scripts'))
{
	function geodir_admin_scripts()
	{
		
		wp_enqueue_script('jquery'); 		
		/*wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script('jquery-ui-slider'); */
		
		
		wp_enqueue_script( 'geodirectory-jquery-ui-timepicker-js',geodir_plugin_url().'/geodirectory-assets/ui/jquery.ui.timepicker.js',array( 'jquery-ui-datepicker','jquery-ui-slider' ),'',true  );
		
		wp_register_script( 'chosen', geodir_plugin_url().'/geodirectory-assets/js/chosen.jquery.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'chosen');
		
		wp_register_script( 'geodirectory-choose-ajax', geodir_plugin_url().'/geodirectory-assets/js/ajax-chosen.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodirectory-choose-ajax' );
    
		if(isset($_REQUEST['listing_type'])){
		wp_register_script( 'geodirectory-custom-fields-script', geodir_plugin_url().'/geodirectory-assets/js/custom_fields.js' ,array(),GEODIRECTORY_VERSION);}
		
		wp_enqueue_script( 'geodirectory-custom-fields-script' );
		$plugin_path = geodir_plugin_url().'/geodirectory-functions/cat-meta-functions';
		
		wp_enqueue_script( 'tax-meta-clss', $plugin_path . '/js/tax-meta-clss.js', array( 'jquery' ), null, true );
		
		$map_lang="&language=" . geodir_get_map_default_language() ; 
		$map_extra = apply_filters( 'geodir_googlemap_script_extra', '' );
		wp_enqueue_script('geodirectory-googlemap-script', '//maps.google.com/maps/api/js?sensor=false'.$map_lang.$map_extra,'',NULL);
        
		wp_register_script( 'geodirectory-goMap-script', geodir_plugin_url().'/geodirectory-assets/js/goMap.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodirectory-goMap-script' );
		
		wp_register_script( 'geodir-jRating-js', geodir_plugin_url() .'/geodirectory-assets/js/jRating.jquery.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodir-jRating-js' );
		
		wp_register_script( 'geodir-on-document-load', geodir_plugin_url() .'/geodirectory-assets/js/on_document_load.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodir-on-document-load' );
		
		
		// SCRIPT FOR UPLOAD
		wp_enqueue_script('plupload-all');
		wp_enqueue_script('jquery-ui-sortable');	 
		
		wp_register_script( 'geodirectory-plupload-script', geodir_plugin_url().'/geodirectory-assets/js/geodirectory-plupload.js' ,array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodirectory-plupload-script' );
		
		// SCRIPT FOR UPLOAD END
		
		
		
		// place js config array for plupload
		$plupload_init = array(
			'runtimes' => 'html5,silverlight,flash,html4',
			'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
			'container' => 'plupload-upload-ui', // will be adjusted per uploader
			'drop_element' => 'dropbox', // will be adjusted per uploader
			'file_data_name' => 'async-upload', // will be adjusted per uploader
			'multiple_queues' => true,
			'max_file_size' => geodir_max_upload_size(),
			'url' => admin_url('admin-ajax.php'),
			'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
			'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
			'filters' => array(array('title' => __('Allowed Files' ,  GEODIRECTORY_TEXTDOMAIN ), 'extensions' => '*')),
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
		
		
		$thumb_img_arr = array();
		
		if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
			$thumb_img_arr = geodir_get_images($_REQUEST['pid']);
		
		$totImg = '';
		$image_limit ='';
		if(!empty($thumb_img_arr))
		{
			foreach($thumb_img_arr as $img){
				$curImages = $img->src.",";
			}	
			
			$totImg = count($thumb_img_arr);
		}	
		
		
		$gd_plupload_init = array( 	'base_plupload_config' => $base_plupload_config,
									'totalImg' => $totImg,
									'image_limit' => $image_limit,
									'upload_img_size' => geodir_max_upload_size() ); 
		
		wp_localize_script( 'geodirectory-plupload-script', 'gd_plupload', $gd_plupload_init );
		
		$ajax_cons_data = array( 'url' => __( admin_url('admin-ajax.php') ) ); 
		wp_localize_script( 'geodirectory-custom-fields-script', 'geodir_admin_ajax', $ajax_cons_data );
		
		
		       
		wp_register_script( 'geodirectory-admin-script', geodir_plugin_url().'/geodirectory-assets/js/admin.js',array(),GEODIRECTORY_VERSION);
		wp_enqueue_script( 'geodirectory-admin-script' );
		
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
		
		$screen = get_current_screen();
		if($screen->base=='post' && in_array($screen->post_type,geodir_get_posttypes())){
		wp_enqueue_script( 'geodirectory-listing-validation-script', geodir_plugin_url().'/geodirectory-assets/js/listing_validation_admin.js' );
		}
		
		$ajax_cons_data = array( 'url' => __( get_option('siteurl').'?geodir_ajax=true' ) ); 
		wp_localize_script( 'geodirectory-admin-script', 'geodir_ajax', $ajax_cons_data );
				
	}
}



/**
 * Admin Menus
 * 
 * Sets up the admin menus in wordpress.
 */
if (!function_exists('geodir_admin_menu')) {
	function geodir_admin_menu() {
		global $menu, $geodirectory;
		
		if ( current_user_can( 'manage_options' ) ) $menu[] = array( '', 'read', 'separator-geodirectory', '', 'wp-menu-separator geodirectory' );
			
			add_menu_page(__('Geodirectory',  GEODIRECTORY_TEXTDOMAIN ), __('Geodirectory',  GEODIRECTORY_TEXTDOMAIN ), 'manage_options','geodirectory' , 'geodir_admin_panel', geodir_plugin_url() . '/geodirectory-assets/images/favicon.ico','55.1984');
		
			
	}       
}


/**
 * Order admin menus
 */
if (!function_exists('geodir_admin_menu_order')) {
function geodir_admin_menu_order( $menu_order ) {
	
	// Initialize our custom order array
	$geodir_menu_order = array();

	// Get the index of our custom separator
	$geodir_separator = array_search( 'separator-geodirectory', $menu_order );
	
	// Get index of posttype menu
	$post_types = geodir_get_posttypes();
	if(!empty($post_types)){
	foreach($post_types as $post_type){
		$geodir_posts = array_search( "edit.php?post_type={$post_type}", $menu_order );
	}	}

	// Loop through menu order and do some rearranging
	foreach ( $menu_order as $index => $item ) :

		if ( ( ( 'geodirectory' ) == $item ) ) :
			$geodir_menu_order[] = 'separator-geodirectory';
			if(!empty($post_types)){
			foreach($post_types as $post_type){
				$geodir_menu_order[] = 'edit.php?post_type='.$post_type;
			}}
			$geodir_menu_order[] = $item;
			
			unset( $menu_order[$geodir_separator] );
			//unset( $menu_order[$geodir_places] );
		elseif ( !in_array( $item, array( 'separator-geodirectory' ) ) ) :
			$geodir_menu_order[] = $item;
		endif;

	endforeach;
	
	// Return order
	return $geodir_menu_order;
}
}

if (!function_exists('geodir_admin_custom_menu_order')) {
function geodir_admin_custom_menu_order() {
	if ( !current_user_can( 'manage_options' ) ) return false;
	return true;
}
}



// Function to show success or error message on admin option form submittion
function geodir_before_admin_panel()
{
	if(isset($_REQUEST['installed']) && $_REQUEST['installed'] != '')
	{
		 echo '<div id="message" class="updated fade">
                        <p style="float:right;">' . __( 'Like Geodirectory?' ,  GEODIRECTORY_TEXTDOMAIN ).' <a href="http://wordpress.org/extend/plugins/Geodirectory/" target="_blank">'.__('Support us by leaving a rating!', GEODIRECTORY_TEXTDOMAIN ) . '</a></p>
                        <p><strong>' . __( 'Geodirectory has been installed and setup. Enjoy :)', GEODIRECTORY_TEXTDOMAIN ) . '</strong></p>
                </div>';
        
	}
	
	if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != '')
	{
		switch ($_REQUEST['msg'])
		{
			case 'success':
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Your settings have been saved.', GEODIRECTORY_TEXTDOMAIN ) . '</strong></p></div>';
				flush_rewrite_rules( false );
			
				break;
				
		}
	}
	
	if(!geodir_is_default_location_set())
	{
		echo '<div class="updated fade"><p><strong>' . sprintf( __( 'Please %sclick here%s to set a default location, this will make the plugin work properly.', GEODIRECTORY_TEXTDOMAIN ) , '<a href=\'' .admin_url('admin.php?page=geodirectory&tab=default_location_settings').'\'>' , '</a>') . '</strong></p></div>';
		
	}
	
	if(!function_exists('curl_init'))
	{
		echo '<div class="error"><p><strong>' .  __( 'CURL is not installed on this server, this can cause problems, please ask your server admin to install it.', GEODIRECTORY_TEXTDOMAIN ) . '</strong></p></div>';
		
	}
}

function geodir_handle_option_form_submit($current_tab)
{
	global $geodir_settings;
	if(file_exists(dirname(__FILE__). '/option-pages/'.$current_tab.'_array.php'))
	{
		include_once('option-pages/'.$current_tab.'_array.php');
	}
	if( isset( $_POST ) && $_POST  && isset($_REQUEST['page']) && $_REQUEST['page']=='geodirectory') :
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'geodir-settings' ) ) die( __( 'Action failed. Please refresh the page and retry.', GEODIRECTORY_TEXTDOMAIN) ); 
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce-'.$current_tab], 'geodir-settings-'.$current_tab ) ) die( __( 'Action failed. Please refresh the page and retry.', GEODIRECTORY_TEXTDOMAIN ) );
		if( !empty($geodir_settings[$current_tab]) )
			geodir_update_options( $geodir_settings[$current_tab] );
		
		do_action( 'geodir_update_options',$geodir_settings );
		do_action( 'geodir_update_options_' . $current_tab, $geodir_settings[$current_tab]);
		
		flush_rewrite_rules( false );
		
		$current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
		
		$redirect_url =admin_url('admin.php?page=geodirectory&tab='.$current_tab.'&active_tab='.$_REQUEST['active_tab'].'&msg=success');
		
		wp_redirect($redirect_url);
		exit();
	endif;
	
	
}







//-----Funtion for install GeoDirectory dummy data------

if(!function_exists('geodir_autoinstall_admin_header') && get_option('geodir_installed')){
		
	function geodir_autoinstall_admin_header($post_type = 'gd_place'){
		
		global $wpdb,$plugin_prefix;
		
		if(!geodir_is_default_location_set())
		{
			echo '<div class="updated fade"><p><strong>' . sprintf( __( 'Please %sclick here%s to set a default location, this will help to set location of all dummy data.', GEODIRECTORY_TEXTDOMAIN ) , '<a href=\'' .admin_url('admin.php?page=geodirectory&tab=default_location_settings').'\'>' , '</a>') . '</strong></p></div>';
		}
		else
		{
			
			$geodir_url = admin_url().'admin.php?page=geodirectory&tab=general_settings&active_tab=';
			
			$post_counts = 	$wpdb->get_var("SELECT count(post_id) FROM ".$plugin_prefix.$post_type."_detail WHERE post_dummy='1'");
			
			if($post_counts > 0)
			{
				$nonce = wp_create_nonce( 'geodir_dummy_posts_delete_noncename' );
				
				$dummy_msg = '<div id="" class="geodir_auto_install updated highlight fade"><p><b>'.SAMPLE_DATA_SHOW_MSG.'</b><br /><a id="geodir_dummy_delete" class="button_delete" onclick="geodir_autoinstall(this,\'geodir_dummy_delete\',\''.$nonce.'\',\''.$post_type.'\')" href="javascript:void(0);" redirect_to="'.$geodir_url .'"  >'.DELETE_BTN_SAMPLE_MSG.'</a></p></div>';
				$dummy_msg.= '<div id="" style="display:none;" class="geodir_show_progress updated highlight fade"><p><b>'.GEODIR_SAMPLE_DATA_DELETE_MSG.'</b><br><img src="'.geodir_plugin_url().'/geodirectory-assets/images/loadingAnimation.gif" /></p></div>';
			}
			else
			{
				$options_list = '';
				for($option=1;$option<=30;$option++){
					$selected = ''; 	
					if($option == 10)
						$selected = 'selected="selected"';
						
					$options_list.='<option '.$selected.' value="'.$option.'">'.$option.'</option>'; 
				}
				
				$nonce = wp_create_nonce( 'geodir_dummy_posts_insert_noncename' );
				
				$dummy_msg = '<div id="" class="geodir_auto_install updated highlight fade"><p><b>'.AUTO_INSATALL_MSG.'</b><br /><select class="selected_sample_data">'.$options_list.'</select><a id="geodir_dummy_insert" class="button_insert" href="javascript:void(0);" onclick="geodir_autoinstall(this,\'geodir_dummy_insert\',\''.$nonce.'\',\''.$post_type.'\')"   redirect_to="'.$geodir_url .'" >'.INSERT_BTN_SAMPLE_MSG.'</a></p></div>';
				$dummy_msg.= '<div id="" style="display:none;" class="geodir_show_progress updated highlight fade"><p><b>'.GEODIR_SAMPLE_DATA_IMPORT_MSG.'</b><br><img src="'.geodir_plugin_url().'/geodirectory-assets/images/loadingAnimation.gif" /><br><span class="dummy_post_inserted"></span></div>';
				
			}
			echo $dummy_msg;
		?>
      <script>
	  <?php 
	  	
	  	$default_location = geodir_get_default_location();
		$city =  isset($default_location->city) ? $default_location->city : '';
		$region =isset($default_location->region) ? $default_location->region : '';
		$country =isset($default_location->country) ? $default_location->country : '';
		$city_latitude =isset($default_location->city_latitude) ? $default_location->city_latitude : '';
		$city_longitude =isset($default_location->city_longitude) ? $default_location->city_longitude : '';
		
	  ?>
	  	var geocoder = new google.maps.Geocoder();
		var CITY_ADDRESS = '<?php echo $city.','.$region.','.$country;?>';
		var bound_lat_lng;
		var lat = <?php echo $city_latitude; ?>;
		var lng = <?php echo $city_longitude; ?>;
		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode( { 'address': CITY_ADDRESS }, 
				function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) 
						{
							 // Bounds for North America
						//	 alert((results[0].geometry.bounds==null))
							if(results[0].geometry.bounds==null)
							{
								bound_lat_lng1 = String(results[0].geometry.viewport.getSouthWest());
								bound_lat_lng1 =bound_lat_lng1.replace(/[()]/g,"");
								
								bound_lat_lng2 = String(results[0].geometry.viewport.getNorthEast());
								bound_lat_lng2 =bound_lat_lng2.replace(/[()]/g,"");
								bound_lat_lng2 = bound_lat_lng1 + "," + bound_lat_lng2;
								bound_lat_lng = bound_lat_lng2.split(',');
							}
							else
							{	
								bound_lat_lng = String(results[0].geometry.bounds);
							
								bound_lat_lng = bound_lat_lng.replace(/[()]/g,"");
							
								bound_lat_lng = bound_lat_lng.split(',');
							}
							
							strictBounds = new google.maps.LatLngBounds(
								 new google.maps.LatLng(bound_lat_lng[0], bound_lat_lng[1]), 
								 new google.maps.LatLng(bound_lat_lng[2], bound_lat_lng[3])
							 );
					
						} else {
						alert("<?php _e('Geocode was not successful for the following reason:',GEODIRECTORY_TEXTDOMAIN);?> " + status);
						}
			});
			
	
	var dummy_post_index=1 ;
	function geodir_autoinstall(obj, id, nonce, posttype){
	
	var active_tab = jQuery(obj).closest('form').find('dl dd.gd-tab-active').attr('id');
	
		var total_dummy_post_count = jQuery('#sub_'+active_tab).find('.selected_sample_data').val();
		
		if(id=='geodir_dummy_delete'){
			if(confirm('<?php _e('Are you sure you want to delete dummy data?' , GEODIRECTORY_TEXTDOMAIN); ?>')){
				jQuery('#sub_'+active_tab).find('.geodir_auto_install').hide();
				jQuery('#sub_'+active_tab).find('.geodir_show_progress').show();
				jQuery.post('<?php echo geodir_get_ajax_url(); ?>&geodir_autofill='+id+'&posttype='+posttype+'&_wpnonce='+nonce,
				function(data) {
					window.location.href=jQuery('#'+id).attr('redirect_to')+active_tab;
				});
				return true;
			}else{
				return false;
			}
		}
		else
		{
			
			jQuery('#sub_'+active_tab).find('.geodir_auto_install').hide();
			jQuery('#sub_'+active_tab).find('.geodir_show_progress').show();
			jQuery.post('<?php echo geodir_get_ajax_url(); ?>&geodir_autofill='+id+'&posttype='+posttype+'&insert_dummy_post_index=' + dummy_post_index+'&city_bound_lat1='+bound_lat_lng[0] + '&city_bound_lng1=' + bound_lat_lng[1] + '&city_bound_lat2=' + bound_lat_lng[2] + '&city_bound_lng2=' + bound_lat_lng[3]+'&_wpnonce='+nonce,
				function(data) {
					
					jQuery(obj).closest('form').find('.dummy_post_inserted').html('<?php _e('Dummy post(s) inserted:',GEODIRECTORY_TEXTDOMAIN);?> ' +  dummy_post_index + ' <?php _e('of' ,GEODIRECTORY_TEXTDOMAIN); ?> '+total_dummy_post_count+'' );
					dummy_post_index++;
					if(dummy_post_index<=total_dummy_post_count)
						geodir_autoinstall(obj,id, nonce, posttype);
					else
					{
						window.location.href=jQuery('#'+id).attr('redirect_to')+active_tab;
					}
					
			});
		}
				
	}
	</script>
        <?php
		}
	}
}

function geodir_insert_dummy_posts()
{
	geodir_default_taxonomies();
	
	ini_set('max_execution_time', 999999); //300 seconds = 5 minutes
	
	global $wpdb,$current_user;

	include_once('place_dummy_post.php');
	
}

// function for delete GeoDirectory dummy data
function geodir_delete_dummy_posts()
{
	global $wpdb, $plugin_prefix;
	
	
	$post_ids =	$wpdb->get_results("SELECT post_id FROM ".$plugin_prefix."gd_place_detail WHERE post_dummy='1'");
	

	foreach($post_ids as $post_ids_obj)
	{
		wp_delete_post($post_ids_obj->post_id);
	}

    //double check posts are deleted
    $wpdb->get_results("DELETE FROM ".$plugin_prefix."gd_place_detail WHERE post_dummy='1'");
}

/**
 * Default taxonomies
 * 
 * Adds the default terms for taxonomies - placecategory. Modify at your own risk.
 */ 
function geodir_default_taxonomies()
{
	 
	global $wpdb,$dummy_image_path;
		
	$category_array = array('Attractions','Hotels','Restaurants','Food Nightlife','Festival','Videos','Feature');
	
	$last_catid = isset($last_catid) ? $last_catid : '';
	
	$last_term = get_term($last_catid, 'gd_placecategory');
			
	$uploads = wp_upload_dir(); // Array of key => value pairs
	//print_r($uploads) ;
	for($i=0;$i < count($category_array); $i++)
	{
		$parent_catid = 0;
		if(is_array($category_array[$i]))
		{
			$cat_name_arr = $category_array[$i];
			for($j=0;$j < count($cat_name_arr);$j++)
			{
				$catname = $cat_name_arr[$j];
				
				if(!term_exists( $catname, 'gd_placecategory' )){
					$last_catid = wp_insert_term( $catname, 'gd_placecategory', $args = array('parent'=>$parent_catid) );
		
					if($j==0)
					{
						$parent_catid = $last_catid;
					}
					
					
					if(geodir_dummy_folder_exists())
						$dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy/cat_icon";
					else
						$dummy_image_url = 'http://www.wpgeodirectory.com/dummy/cat_icon';
					$catname = str_replace(' ', '_', $catname);	
					$uploaded =  (array)fetch_remote_file("$dummy_image_url/".$catname.".png");
					
					if(empty($uploaded['error']))
					{	
						$new_path = $uploaded['file'];
						$new_url = $uploaded['url'];
					}
					
					$wp_filetype = wp_check_filetype(basename($new_path), null );
				    
				    $attachment = array(
					 'guid' => $uploads['baseurl'] . '/' . basename( $new_path ), 
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title' => preg_replace('/\.[^.]+$/', '', basename($new_path)),
					 'post_content' => '',
					 'post_status' => 'inherit'
				    );
				    $attach_id = wp_insert_attachment( $attachment, $new_path );
				  	
					// you must first include the image.php file
				    // for the function wp_generate_attachment_metadata() to work
				    require_once(ABSPATH . 'wp-admin/includes/image.php');
				    $attach_data = wp_generate_attachment_metadata( $attach_id, $new_path );
				    wp_update_attachment_metadata( $attach_id, $attach_data );
					
					if(!get_tax_meta($last_catid['term_id'], 'ct_cat_icon', false, 'gd_place'))
					{update_tax_meta($last_catid['term_id'], 'ct_cat_icon', array( 'id' => 'icon', 'src' => $new_url), 'gd_place');}
				}
			}
			
		}else
		{
			$catname = $category_array[$i];
			
			if(!term_exists( $catname, 'gd_placecategory' ))
			{
				$last_catid = wp_insert_term( $catname, 'gd_placecategory' );
				
				if(geodir_dummy_folder_exists())
					$dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy/cat_icon";
				else
					$dummy_image_url = 'http://www.wpgeodirectory.com/dummy/cat_icon';
				$catname = str_replace(' ', '_', $catname);		
				$uploaded = (array) fetch_remote_file("$dummy_image_url/".$catname.".png");
				
				if(empty($uploaded['error']))
				{	
					$new_path = $uploaded['file'];
					$new_url = $uploaded['url'];
				}
				
				$wp_filetype = wp_check_filetype(basename($new_path), null );
				    
				    $attachment = array(
					 'guid' => $uploads['baseurl'] .  '/' . basename( $new_path ), 
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title' => preg_replace('/\.[^.]+$/', '', basename($new_path)),
					 'post_content' => '',
					 'post_status' => 'inherit'
				    );
					
					$attach_id = wp_insert_attachment( $attachment, $new_path );

				  	
					// you must first include the image.php file
				    // for the function wp_generate_attachment_metadata() to work
				    require_once(ABSPATH . 'wp-admin/includes/image.php');
				    $attach_data = wp_generate_attachment_metadata( $attach_id, $new_path );
				    wp_update_attachment_metadata( $attach_id, $attach_data );
				
				if(!get_tax_meta($last_catid['term_id'], 'ct_cat_icon', false, 'gd_place'))
					{update_tax_meta($last_catid['term_id'], 'ct_cat_icon', array( 'id' => $attach_id, 'src' => $new_url), 'gd_place');}
			}
		}
		
	}
}

/**
 * Update options
 * 
 * Updates the options on the geodirectory settings pages. Returns true if saved.
 */
function geodir_update_options($options, $dummy = false) {
	//print_r($_POST);exit;
   	if((!isset($_POST) || !$_POST) && !$dummy) return false;
   
  	foreach ($options as $value) {
		if($dummy && isset($value['std']))
			$_POST[$value['id']] = $value['std'];
		
		
		if (isset($value['type']) && $value['type']=='checkbox') :
			
			if(isset($value['id']) && isset($_POST[$value['id']])) {
				update_option($value['id'], $_POST[$value['id']] );
			} else {
				update_option($value['id'], 0);
			}
			
		elseif (isset($value['type']) && $value['type']=='image_width') :
				
			if(isset($value['id']) && isset($_POST[$value['id'].'_width'])) {
				update_option($value['id'].'_width', $_POST[$value['id'].'_width']);
				update_option($value['id'].'_height', $_POST[$value['id'].'_height']);
				if (isset($_POST[$value['id'].'_crop'])) :
					update_option($value['id'].'_crop', 1);
				else :
					update_option($value['id'].'_crop', 0);
				endif;
			} else {
				update_option($value['id'].'_width', $value['std']);
				update_option($value['id'].'_height', $value['std']);
				update_option($value['id'].'_crop', 1);
			}	
	   
		elseif (isset($value['type']) && $value['type']=='map') :
				$post_types = array();
				$categories = array();
				$i=0;
				
				if( !empty( $_POST['home_map_post_types'] ) ) :
					foreach( $_POST['home_map_post_types'] as $post_type ) :
							$post_types[] = $post_type;
					endforeach;
				endif;
				
				update_option( 'geodir_exclude_post_type_on_map', $post_types );
				
				if( !empty( $_POST['post_category'] ) ) :
					foreach( $_POST['post_category'] as $texonomy => $cat_arr ) :
						$categories[$texonomy] = array();
						foreach( $cat_arr as $category ) :
							$categories[$texonomy][] = $category;
						endforeach;
						$categories[$texonomy] = !empty( $categories[$texonomy] ) ? array_unique( $categories[$texonomy] ) : array();
					endforeach;
				endif;
				update_option( 'geodir_exclude_cat_on_map', $categories );
				update_option( 'geodir_exclude_cat_on_map_upgrade', 1 );
		elseif (isset($value['type']) && $value['type']=='map_default_settings') :
		
				
				if(!empty($_POST['geodir_default_map_language'])):
					update_option('geodir_default_map_language', $_POST['geodir_default_map_language']);
				endif;
				
				
				if(!empty($_POST['geodir_default_map_search_pt'])):
					update_option('geodir_default_map_search_pt', $_POST['geodir_default_map_search_pt']);
				endif;
				
				
						
		elseif (isset($value['type']) && $value['type']=='file') :


				if(isset($_POST[$value['id'].'_remove']) && $_POST[$value['id'].'_remove']){// if remove is set then remove the file

					if(get_option($value['id'])){
						$image_name_arr = explode('/',get_option($value['id']));
						$noimg_name = end($image_name_arr);
						$img_path = $uploads['path'].'/'.$noimg_name;
						if( file_exists($img_path) )
							unlink($img_path);
					}

					update_option($value['id'],'');
				}

				$uploadedfile = isset($_FILES[$value['id']]) ? $_FILES[$value['id']] : '';
				$filename = isset($_FILES[$value['id']]['name']) ? $_FILES[$value['id']]['name'] : '';
				
				if(!empty($filename)):
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$uplaods = array();
					
					foreach($uploadedfile as $key => $uplaod):
						if($key=='name'):
							$uplaods[$key] = $filename;
						else :
							$uplaods[$key] = $uplaod;
						endif;
					endforeach;
					
					$uploads = wp_upload_dir();
					
					if(get_option($value['id'])){
						$image_name_arr = explode('/',get_option($value['id']));
						$noimg_name = end($image_name_arr);
						$img_path = $uploads['path'].'/'.$noimg_name;
						if( file_exists($img_path) )
							unlink($img_path);
					}
					
					$upload_overrides = array( 'test_form' => false );
					$movefile = wp_handle_upload( $uplaods, $upload_overrides );
					
					update_option($value['id'], $movefile['url']);
					
				endif;
				
				if(!get_option($value['id']) && isset($value['value']) ):
					update_option($value['id'], $value['value']);
				endif;
				
					
		else :
			// same menu setting per theme.
			if(isset($value['id']) && $value['id']=='geodir_theme_location_nav' && isset($_POST[$value['id']])){$theme = wp_get_theme();update_option('geodir_theme_location_nav_'.$theme->name, $_POST[$value['id']]);}
			
			if(isset($value['id']) && isset($_POST[$value['id']])) {
				update_option($value['id'], $_POST[$value['id']]);
			} else {
				delete_option($value['id']);
			}
		
		endif;
	}
	if($dummy )
		unset($_POST);
	return true;
	
}

//create custom fields for place
function places_custom_fields_tab($tabs){
	
	$geodir_post_types = get_option( 'geodir_post_types' );
	
	if(!empty($geodir_post_types))
	{
		 
		 foreach($geodir_post_types as $geodir_post_type => $geodir_posttype_info):
		 		if($geodir_post_type=='gd_place' && get_option('geodir_disable_place_tax')){continue;}
				$listing_slug = $geodir_posttype_info['labels']['singular_name'];
				
		 		$tabs[$geodir_post_type.'_fields_settings'] = array( 
																			'label' =>__( ucfirst($listing_slug).' Settings', GEODIRECTORY_TEXTDOMAIN ),
																			'subtabs' => array(
																				array('subtab' => 'custom_fields',
																							'label' =>__( 'Custom Fields', GEODIRECTORY_TEXTDOMAIN),
																							'request' => array('listing_type'=>$geodir_post_type)),
																				array('subtab' => 'sorting_options',
																							'label' =>__( 'Sorting Options', GEODIRECTORY_TEXTDOMAIN),
																							'request' => array('listing_type'=>$geodir_post_type)),
																				),
																			'tab_index' =>9,  
																			'request' => array('listing_type'=>$geodir_post_type) 
																			);
					
		 endforeach;
		 
	}
	
	return $tabs; 
}


function geodir_tools_setting_tab($tabs)
{
	$tabs['tools_settings'] = array('label'=> __( 'GD Tools', GEODIRECTORY_TEXTDOMAIN 		) ) ;
	return $tabs ;
}

function geodir_compatibility_setting_tab($tabs)
{
	$tabs['compatibility_settings'] = array('label'=> __( 'Theme Compatibility', GEODIRECTORY_TEXTDOMAIN 		) ) ;
	return $tabs ;
}


function geodir_extend_geodirectory_setting_tab($tabs)
{
	$tabs['extend_geodirectory_settings'] = array('label'=> __( 'Extend Geodirectory', GEODIRECTORY_TEXTDOMAIN 		) , 'url'=>'http://wpgeodirectory.com' , 'target' => '_blank') ;
	return $tabs ;
}


if (!function_exists('geodir_edit_post_columns')) {
	function geodir_edit_post_columns( $columns ) {
		
		$new_columns = array('location' => __( 'Location (ID)' ,GEODIRECTORY_TEXTDOMAIN),
							'categorys' => __( 'Categories',GEODIRECTORY_TEXTDOMAIN ));
		
		if (($offset = array_search('author', array_keys($columns))) === false) // if the key doesn't exist
		{
			$offset = 0; // should we prepend $array with $data?
			$offset = count($columns); // or should we append $array with $data? lets pick this one...
		}
	
		$columns = array_merge(array_slice($columns, 0, $offset), $new_columns, array_slice($columns, $offset));
		
		$columns = array_merge( $columns, array( 'expire' => __( 'Expires' , GEODIRECTORY_TEXTDOMAIN ) ) );
		
		return $columns;
	}
}


if (!function_exists('geodir_manage_post_columns')) {
	function geodir_manage_post_columns( $column, $post_id ) {
		global $post,$wpdb;

		switch( $column ):
			/* If displaying the 'city' column. */
			case 'location' :
				$location_id = geodir_get_post_meta( $post->ID, 'post_location_id', true );
				$location = geodir_get_location( $location_id );
				/* If no city is found, output a default message. */
				if ( empty( $location) ) {
					_e( 'Unknown' , GEODIRECTORY_TEXTDOMAIN );
				} else {
					/* If there is a city id, append 'city name' to the text string. */
					$add_location_id = $location_id > 0 ? ' ('.$location_id.')' : '';
					echo ( __( $location->country, GEODIRECTORY_TEXTDOMAIN ) . '-' . $location->region . '-' . $location->city . $add_location_id );
				}
			break;		
					
			/* If displaying the 'expire' column. */
			case 'expire' :
					$expire_date = geodir_get_post_meta($post->ID,'expire_date',true);
					$d1 = $expire_date; // get expire_date
					$d2 = date('Y-m-d'); // get current date
					$state = __('days left' , GEODIRECTORY_TEXTDOMAIN);
					$date_diff_text ='';
					$expire_class = 'expire_left';
					if($expire_date!='Never'){
					if(strtotime($d1) < strtotime($d2)){$state = __('days overdue' , GEODIRECTORY_TEXTDOMAIN); $expire_class = 'expire_over';}
					$date_diff = round(abs(strtotime($d1)-strtotime($d2))/86400); // get the differance in days
					$date_diff_text = '<br /><span class="'.$expire_class.'">('.$date_diff.' '.$state.')</span>';
					} 
					/* If no expire_date is found, output a default message. */
					if ( empty( $expire_date ) )
						echo __( 'Unknown' , GEODIRECTORY_TEXTDOMAIN );
					/* If there is a expire_date, append 'days left' to the text string. */
					else
						echo $expire_date.$date_diff_text;
			break;
			
			/* If displaying the 'categorys' column. */
			case 'categorys' :
		
					/* Get the categorys for the post. */
					
				
					
					$terms = wp_get_object_terms( $post_id, get_object_taxonomies($post) );
					
					/* If terms were found. */
					if ( !empty( $terms ) ) {
						$out = array();
						/* Loop through each term, linking to the 'edit posts' page for the specific term. */
						foreach ( $terms as $term ) {
							if(!strstr($term->taxonomy,'tag')){
							$out[] = sprintf( '<a href="%s">%s</a>',
								esc_url( add_query_arg( array( 'post_type' => $post->post_type, $term->taxonomy => $term->slug ), 'edit.php' ) ),
								esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'display' ) )
							);
							}
						}
						/* Join the terms, separating them with a comma. */
						echo (join( ', ', $out ));
					}
					/* If no terms were found, output a default message. */
					else {
						_e( 'No Categories', GEODIRECTORY_TEXTDOMAIN );
					}
			break;
		
		endswitch;
}}


if (!function_exists('geodir_post_sortable_columns')) {
function geodir_post_sortable_columns( $columns ) {

	$columns['expire'] = 'expire';

	return $columns;
}}


function geodir_post_information_save( $post_id )  
{

	global $wpdb,$current_user,$post;
	
	//unsetting the listing session here causes problems, it should be the last thing to be done.
    /*if(isset($_SESSION['listing'])){
		unset($_SESSION['listing']);
	}*/
	
	$geodir_posttypes = geodir_get_posttypes();
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	if( !wp_is_post_revision( $post_id ) && 
		isset($post->post_type) && in_array($post->post_type,$geodir_posttypes ) ):
		
		if(isset($_REQUEST['_status']))
			geodir_change_post_status( $post_id, $_REQUEST['_status']);
		
		/*if ( !wp_verify_nonce( $_POST['geodir_post_setting_noncename'], plugin_basename( __FILE__ ) ) )
		return;*/
		
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'trash' || $_REQUEST['action'] == 'untrash'))
			return;
		
		if ( !wp_verify_nonce( $_POST['geodir_post_info_noncename'], plugin_basename( __FILE__ ) ) )
		return;
		
		/*if ( !wp_verify_nonce( $_POST['geodir_post_addinfo_noncename'], plugin_basename( __FILE__ ) ) )
		return;*/
		
		if ( !wp_verify_nonce( $_POST['geodir_post_attachments_noncename'], plugin_basename( __FILE__ ) ) )
		return;
		
	
		geodir_save_listing($_REQUEST);
		
		
	endif;
}

//-----Funtion for insert csv post data------

if(!function_exists('geodir_insert_csv_post_data') && get_option('geodir_installed')){
		
	function geodir_insert_csv_post_data(){
		global $wpdb,$plugin_prefix;
		
		$svalue = '';
		?>
        <script type="text/javascript">
    	jQuery(document).ready(function(){
				jQuery("#import_data").click(function() {
					var geodir_import_data = jQuery('.geodir_import_data').val();
					var csv_filename = jQuery('.csv_filename').val();
					if(csv_filename!='')
					{
						jQuery.post('<?php echo geodir_get_ajax_url(); ?>&geodir_import_data='+geodir_import_data+'&filename='+csv_filename, function(data) {
								//alert(data);
								window.location.href=data;
						});
					}else{ alert('<?php echo PLZ_SELECT_CSV_FILE; ?>'); }
				});
				
				jQuery(".uploadcsv_button").click(function() {
					setInterval(function(){
						var checkvalue = jQuery('.csv_filename').val();
						if (checkvalue!= ''){
							jQuery('.csv_button_div').show();
						}
					},1000);
				});
				
				
			});
		jQuery(document).ready(function(){
			jQuery(".gd_uploadcsv_button").click(function() {
				setInterval(function() {
					var checkvalue = jQuery('.gd_csv_filename').val();
					if (checkvalue != '') {
						jQuery('.gd_csv_button_div').show();
					}
				}, 1000);
			});
		});
    </script>
		<?php 
		
		if(isset($_REQUEST['active_tab']) && $_REQUEST['active_tab']=='csv_upload_settings'){
		
			if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='success'){ $rowcount = $_REQUEST['rowcount']; $uploads = wp_upload_dir();  ?>
					
			<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204); margin-left:0px; margin-top:0px; margin-bottom:10px;" >
				
				<?php
				
				if($_REQUEST['invalidcount'] == 0 && $_REQUEST['blank_address']==0 && $_REQUEST['invalid_post_type'] == 0 && $_REQUEST['invalid_title'] == 0){
					
					echo '<p>'.CSV_INSERT_DATA.'</p>';
				
				}
				
				echo '<p>';
				printf(CSV_TOTAL_RECORD, $rowcount);
				echo '</p>';
				
				if(isset($_REQUEST['invalidcount']) && $_REQUEST['invalidcount'] > 0){
					
					echo '<p>';
					printf(CSV_INVALID_DEFUALT_ADDRESS, $_REQUEST['invalidcount'], $_REQUEST['total_records']);
					echo '</p>';
				}
				
				if(isset($_REQUEST['blank_address']) && $_REQUEST['blank_address'] > 0){
					
					echo '<p>'; 
					printf(CSV_INVALID_TOTAL_RECORD, $_REQUEST['blank_address'], $_REQUEST['total_records']);
					echo '</p>';
					
				}
				
				if(isset($_REQUEST['invalid_post_type']) && $_REQUEST['invalid_post_type'] > 0){
					
					echo '<p>';
					printf(CSV_INVALID_POST_TYPE, $_REQUEST['invalid_post_type'], $_REQUEST['total_records']);	
					echo '</p>';
				
				}
				
				if(isset($_REQUEST['invalid_title']) && $_REQUEST['invalid_title'] > 0){
					
					echo '<p>';
					printf(CSV_BLANK_POST_TITLE, $_REQUEST['invalid_title'], $_REQUEST['total_records']);	
					echo '</p>';
				
				}
				
				if(isset($_REQUEST['upload_files']) && $_REQUEST['upload_files'] > 0){
					
					echo '<p>';
					printf(CSV_TRANSFER_IMG_FOLDER, $uploads['subdir']);	
					echo '</p>';
				
				}
				
				
				?>			
				
			</div>
			
		<?php }?>
		
		<?php if(isset($_REQUEST['emsg']) && $_REQUEST['emsg']=='wrong'){ ?>
		
			<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204); margin-left:0px; margin-top:0px; margin-bottom:10px;  color:#FF0000;" >
				<p><?php echo CSV_INVAILD_FILE; ?></p>
			
			</div>
			
		<?php }?>
		
		 <?php if(isset($_REQUEST['emsg'])=='csvonly'){ ?>
		
			<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204); margin-left:0px; margin-top:0px; margin-bottom:10px; color:#FF0000;" >
				<p><?php echo CSV_UPLOAD_ONLY; ?></p>
			
			</div>
			
		<?php }
	}  ?>
    <?php /* ?>
    <table class="form-table">
        <tbody>
            <tr valign="top" class="single_select_page">
                <th class="titledesc" scope="row"><?php echo SELECT_CSV_FILE;?></th>
                <td class="forminp">
                
					<?php  
                    $id = "import_image"; 
                    $multiple = false; // allow multiple files upload
                    ?>
                 	<div class="gtd-formfeild">
                        <div class="gtd-form_row clearfix" id="<?php echo $id; ?>dropbox">
                        
                        <div class="plupload-upload-uic hide-if-no-js" id="<?php echo $id; ?>plupload-upload-ui">
                        <input type="text" readonly="readonly" name="<?php echo $id; ?>" class="csv_filename" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" /><input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php echo SELECT_UPLOAD_CSV; ?>" class="uploadcsv_button" /><br />
                       <a href="<?php echo geodir_plugin_url() . '/geodirectory-assets/place_listing.csv'?>" ><?php _e("Download sample csv", GEODIRECTORY_TEXTDOMAIN)?></a>
                       <?php do_action('geodir_sample_csv_download_link'); ?>
											 
                        <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span><br /><br />
    						<div class="filelist"></div>
         
                        </div>
                   </div>
                	</div>
                    <span id="upload-error" style="display:none"></span>
                	<span class="description"></span><br />
                    <div class="csv_button_div" style="display:none;">
                    <input type="hidden" class="geodir_import_data" name="geodir_import_data" value="save" />					        			<input type="button" value="<?php echo CSV_IMPORT_DATA; ?>" id="import_data" class="button-primary" name="save">				</div>
                    
                </td>
            </tr> 
                      
        </tbody>
    </table>
	<?php */ ?>
<?php
$id = "gd_import_csv";
$multiple = false; // allow multiple files upload
$uploads = wp_upload_dir();
?>
<style>
.gd-csv-form-table .filelist div.file:last-child{display:block!important;opacity:1!important}
.gd-import-progress{margin-top:10px}
.gd-import-file{background:#ececec;border:1px solid #ccc;clear:both;margin-bottom:4px;padding:5px;}
.gd-fileprogress{background:#b7c53d;height:5px;width:0;margin-top:3px}
.gd-csv-form-table .fileprogress{margin-top:3px} 
#gd-import-msg{padding-top:12px}
#gd-import-msg #message{background-color:rgb(255, 251, 204);}
</style>
<script type="text/javascript">
var timout;
function gdPrepareImport() {
	jQuery('#gd-import-msg').hide();
	var gd_prepared = jQuery('#gd_prepared').val();
	var uploadedFile = jQuery('#gd_import_csv').val();
	if (gd_prepared == uploadedFile) {
		gdContinueImport();
		jQuery('#gd_import_data').attr('disabled', 'disabled');
	} else {
		var gddata = new Array();
		gddata = {
			'uploadedFile': uploadedFile,
		}
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				'action': 'gdImportCsv',
				'gddata': gddata,
				'task': 'prepare'
			},
			success: function(data) {
				if (typeof data == 'object') {
					if (data.error) {
						jQuery('#gd-import-msg').find('#message').removeClass('updated').addClass('error').html('<p>' + data.error + '</p>');
						jQuery('#gd-import-msg').show();
					} else {
						jQuery('#gd_tmpcount').val('0');
						jQuery('#gd_tot_records').val(data.rows);
						jQuery('#gd_checktotal').val(data.rows);
						jQuery('#gd_prepared').val(uploadedFile);
						
						jQuery('#gd_rowcount').val('0');
						jQuery('#gd_invalidcount').val('0');
						jQuery('#gd_blank_address').val('0');
						jQuery('#gd_upload_files').val('0');
						jQuery('#gd_invalid_post_type').val('0');
						jQuery('#gd_invalid_title').val('0');
						jQuery('#gd_total_records').val('0');
						
						gdStartImport();
					}
				}
			},
			error: function(errorThrown) {
				console.log(errorThrown);
			}
		});
	}
}
function gdTerminateImport() {
	console.log('gdTerminateImport()');
	jQuery('#gd_terminateaction').val('terminate');
	jQuery('#gd_import_data').hide();
	jQuery('#gd_stop_import').hide();
	jQuery('#gd_process_data').hide();
	jQuery('#gd_continue_data').show();
}
function gdContinueImport() {
	var tmpCnt = jQuery('#gd_tmpcount').val();
	var tot_no_of_records = jQuery('#gd_tot_records').val();
	if (parseInt(tmpCnt) > parseInt(tot_no_of_records)) {
		jQuery('#gd_stop_import').hide();
	} else {
		jQuery('#gd_stop_import').show();
	}
	jQuery('#gd_import_data').show();
	jQuery('#gd_import_data').attr('disabled', 'disabled');
	jQuery('#gd_process_data').css({
		'display': 'inline-block'
	});
	jQuery('#gd_continue_data').hide();
	jQuery('#gd_terminateaction').val('continue');
	clearTimeout(timout);
	timout = setTimeout(function() {
		gdStartImport();
	}, 0);
}
function gdStartImport(siteurl) {
	var importlimit = 1;
	var get_requested_count = importlimit;
	var tot_no_of_records = jQuery('#gd_checktotal').val();
	var uploadedFile = jQuery('#gd_import_csv').val();
	var tmpCnt = jQuery('#gd_tmpcount').val();
	var no_of_tot_records = jQuery('#gd_tot_records').val();
	
	if (!uploadedFile) {
		jQuery('#gd_import_data').removeAttr('disabled');
		jQuery('#gd_import_data').show();
		jQuery('#gd_stop_import').hide();
		jQuery('#gd_process_data').hide();
		jQuery('#gd-import-progress').hide();
		jQuery('.gd-fileprogress').width(0);
		jQuery('#gd-import-done').text('0');
		jQuery('#gd-import-total').text('0');
		jQuery('#gd-import-perc').text('0%');
		
		jQuery('.gd-csv-form-table .filelist .file').remove();
		jQuery('#gd-import-msg').find('#message').removeClass('updated').addClass('error').html("<p><?php echo esc_attr( PLZ_SELECT_CSV_FILE );?></p>");
		jQuery('#gd-import-msg').show();
		
		return false;
	}
	
	jQuery('#gd-import-total').text(tot_no_of_records);
	jQuery('#gd_stop_import').show();
	jQuery('#gd_process_data').css({
		'display': 'inline-block'
	});
	jQuery('#gd-import-progress').show();
	if ((parseInt(no_of_tot_records) / 100) > 0) {
		importlimit = parseInt(parseInt(no_of_tot_records) / 100);
	}
	if (importlimit == 1) {
		if (parseInt(no_of_tot_records) > 50) {
			importlimit = 5;
		} else if (parseInt(no_of_tot_records) > 10 && parseInt(no_of_tot_records) < 51) {
			importlimit = 2;
		}
	}
	if (importlimit > 10) {
		importlimit = 10;
	}
	if (importlimit < 1) {
		importlimit = 1;
	}
	
	get_requested_count = importlimit;
	
	var tempCount = parseInt(tmpCnt);
	var totalCount = parseInt(tot_no_of_records);
	if (tempCount >= totalCount) {
		jQuery('#gd_import_data').removeAttr('disabled');
		jQuery('#gd_import_data').show();
		jQuery('#gd_stop_import').hide();
		jQuery('#gd_process_data').hide();
		gd_showStatusMsg();
		jQuery('#gd_import_csv').val('');
		jQuery('#gd_prepared').val('');
				
		return false;
	}
	jQuery('#gd-import-msg').hide();
	var gddata = new Array();
	gddata = {
		'importlimit': importlimit,
		'totRecords': tot_no_of_records,
		'uploadedFile': uploadedFile,
		'tmpcount': tmpCnt,
	}
	var tmpLoc = jQuery('#tmpLoc').val();
	
	var gd_rowcount = parseInt(jQuery('#gd_rowcount').val());
	var gd_invalidcount = parseInt(jQuery('#gd_invalidcount').val());
	var gd_blank_address = parseInt(jQuery('#gd_blank_address').val());
	var gd_upload_files = parseInt(jQuery('#gd_upload_files').val());
	var gd_invalid_post_type = parseInt(jQuery('#gd_invalid_post_type').val());
	var gd_invalid_title = parseInt(jQuery('#gd_invalid_title').val());
	var gd_total_records = parseInt(jQuery('#gd_total_records').val());
	
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		dataType: 'json',
		data: {
			'action': 'gdImportCsv',
			'gddata': gddata,
			'siteurl': siteurl,
		},
		success: function(data) {
			if (typeof data == 'object') {
				if (data.error) {
					jQuery('#gd_import_data').removeAttr('disabled');
					jQuery('#gd_import_data').show();
					jQuery('#gd_stop_import').hide();
					jQuery('#gd_process_data').hide();
					jQuery('#gd-import-msg').find('#message').removeClass('updated').addClass('error').html('<p>' + data.error + '</p>');
					jQuery('#gd-import-msg').show();
				} else {
					gd_rowcount = gd_rowcount + parseInt(data.rowcount);
					gd_invalidcount = gd_invalidcount + parseInt(data.invalidcount);
					gd_blank_address = gd_blank_address + parseInt(data.blank_address);
					gd_upload_files = gd_upload_files + parseInt(data.upload_files);
					gd_invalid_post_type = gd_invalid_post_type + parseInt(data.invalid_post_type);
					gd_invalid_title = gd_invalid_post_type + parseInt(data.invalid_title);
					gd_total_records = gd_total_records + parseInt(data.total_records);
					
					jQuery('#gd_rowcount').val(gd_rowcount);
					jQuery('#gd_invalidcount').val(gd_invalidcount);
					jQuery('#gd_blank_address').val(gd_blank_address);
					jQuery('#gd_upload_files').val(gd_upload_files);
					jQuery('#gd_invalid_post_type').val(gd_invalid_post_type);
					jQuery('#gd_invalid_title').val(gd_invalid_title);
					jQuery('#gd_total_records').val(gd_total_records);
					
					if (parseInt(tmpCnt) == parseInt(tot_no_of_records)) {
						jQuery('#gd-import-done').text(tot_no_of_records);
						jQuery('#gd-import-perc').text('100%');
						jQuery('.gd-fileprogress').css({
							'width': '100%'
						});
						jQuery('#gd_import_csv').val('');
						jQuery('#gd_prepared').val('');
						gd_showStatusMsg();
						jQuery('#gd_stop_import').hide();
					}
					if (parseInt(tmpCnt) < parseInt(tot_no_of_records)) {
						var terminate_action = jQuery('#gd_terminateaction').val();
						if (terminate_action == 'continue') {
							//console.log('Limit: ' + importlimit + ', Total: ' + tot_no_of_records);
							var nTmpCnt = parseInt(tmpCnt) + parseInt(importlimit);
							nTmpCnt = nTmpCnt > tot_no_of_records ? tot_no_of_records : nTmpCnt;
							
							jQuery('#gd_tmpcount').val(nTmpCnt);
							
							jQuery('#gd-import-done').text(nTmpCnt);
							if (parseInt(tot_no_of_records) > 0) {
								var percentage = ((parseInt(nTmpCnt) / parseInt(tot_no_of_records)) * 100);
								percentage = percentage > 100 ? 100 : percentage;
								jQuery('#gd-import-perc').text(parseInt(percentage) + '%');
								jQuery('.gd-fileprogress').css({
									'width': percentage + '%'
								});
							}
					
							clearTimeout(timout);
							timout = setTimeout(function() {
								gdStartImport();
							}, 0);
						} else {
							jQuery('#gd_import_data').hide();
							jQuery('#gd_stop_import').hide();
							jQuery('#gd_process_data').hide();
							jQuery('#gd_continue_data').show();
							return false;
						}
					} else {
						jQuery('#gd_import_data').removeAttr('disabled');
						jQuery('#gd_import_data').show();
						jQuery('#gd_stop_import').hide();
						jQuery('#gd_process_data').hide();
						return false;
					}
				}
			}
		},
		error: function(errorThrown) {
			jQuery('#gd_import_data').removeAttr('disabled');
			jQuery('#gd_import_data').show();
			jQuery('#gd_stop_import').hide();
			jQuery('#gd_process_data').hide();
			console.log(errorThrown);
		}
	});
}
function gd_showStatusMsg() {
	var gd_rowcount = parseInt(jQuery('#gd_rowcount').val());
	var gd_invalidcount = parseInt(jQuery('#gd_invalidcount').val());
	var gd_blank_address = parseInt(jQuery('#gd_blank_address').val());
	var gd_upload_files = parseInt(jQuery('#gd_upload_files').val());
	var gd_invalid_post_type = parseInt(jQuery('#gd_invalid_post_type').val());
	var gd_invalid_title = parseInt(jQuery('#gd_invalid_title').val());
	var gd_total_records = parseInt(jQuery('#gd_total_records').val());
	
	var gdMsg = '<p></p>';
	if (gd_invalidcount == 0 && gd_blank_address == 0 && gd_invalid_post_type == 0 && gd_invalid_title == 0) {
		gdMsg += "<p><?php echo esc_attr( CSV_INSERT_DATA );?></p>";
	}
		
	var msgParse = '<p><?php echo addslashes( CSV_TOTAL_RECORD );?></p>';
	gdMsg += msgParse.replace("%s", gd_rowcount);
	
	if (gd_invalidcount > 0) {
		msgParse = msgParse = '<p><?php echo sprintf( CSV_INVALID_DEFUALT_ADDRESS, '%s', '%d' );?></p>';
		msgParse = msgParse.replace("%s", gd_invalidcount);
		msgParse = msgParse.replace("%d", gd_total_records);
		gdMsg += msgParse;
	}
	if (gd_blank_address > 0) {
		msgParse = '<p><?php echo addslashes( sprintf( CSV_INVALID_TOTAL_RECORD, '%s', '%d' ) );?></p>';
		msgParse = msgParse.replace("%s", gd_blank_address);
		msgParse = msgParse.replace("%d", gd_total_records);
		gdMsg += msgParse;
	}
	if (gd_invalid_post_type > 0) {
		msgParse = '<p><?php echo addslashes( sprintf( CSV_INVALID_POST_TYPE, '%s', '%d' ) );?></p>';
		msgParse = msgParse.replace("%s", gd_invalid_post_type);
		msgParse = msgParse.replace("%d", gd_total_records);
		gdMsg += msgParse;
	}
	if (gd_invalid_title > 0) {
		msgParse = '<p><?php echo addslashes( sprintf( CSV_BLANK_POST_TITLE, '%s', '%d' ) );?></p>';
		msgParse = msgParse.replace("%s", gd_invalid_title);
		msgParse = msgParse.replace("%d", gd_total_records);
		gdMsg += msgParse;
	}
	if (gd_upload_files > 0) {
		gdMsg += '<p><?php echo addslashes( sprintf( CSV_TRANSFER_IMG_FOLDER, $uploads['subdir'] ) );?></p>';
	}
	gdMsg += '<p></p>';
	jQuery('#gd-import-msg').find('#message').removeClass('error').addClass('updated').html(gdMsg);
	jQuery('#gd-import-msg').show();
}
</script>
<table class="form-table gd-csv-form-table">
  <tbody>
    <tr valign="top" class="single_select_page">
      <th class="titledesc" scope="row"><?php echo SELECT_CSV_FILE;?></th>
      <td class="forminp">
        <div class="gtd-formfeild">
          <div class="gtd-form_row clearfix" id="<?php echo $id; ?>dropbox">
            <div class="plupload-upload-uic hide-if-no-js" id="<?php echo $id; ?>plupload-upload-ui">
              <input type="text" readonly="readonly" name="<?php echo $id; ?>" class="gd_csv_filename" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
              <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php echo SELECT_UPLOAD_CSV; ?>" class="gd_uploadcsv_button" />
              <br />
              <a href="<?php echo geodir_plugin_url() . '/geodirectory-assets/place_listing.csv'?>" ><?php _e("Download sample csv", GEODIRECTORY_TEXTDOMAIN)?></a>
              <?php do_action('geodir_sample_csv_download_link'); ?>
              <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span><br />
              <br />
              <div class="filelist"></div>
            </div>
          </div>
        </div>
        <span id="<?php echo $id; ?>upload-error" style="display:none"></span> <span class="description"></span><br />
        <div class="gd_csv_button_div" style="display:none;">
          <input type="hidden" class="geodir_import_file" name="geodir_import_file" value="save" />
          <input onclick="gdPrepareImport()" type="button" value="<?php echo CSV_IMPORT_DATA; ?>" id="gd_import_data" class="button-primary" name="save">
		  <input onclick="gdContinueImport()" type="button" value="Continue Import Data" id="gd_continue_data" class="button-primary" style="display:none" />
		  <input type="button" value="<?php _e( "Terminate Import Data", GEODIRECTORY_TEXTDOMAIN );?>" id="gd_stop_import" class="button-primary" name="gd_stop_import" style="display:none" onclick="gdTerminateImport()" />
		  <div id="gd_process_data" style="display:none"><span class="spinner" style="display:inline-block;margin:0 5px 0 5px;float:left"></span><?php _e( "Wait, processing import data...", GEODIRECTORY_TEXTDOMAIN );?></div>
        </div>
		<div id="gd_importer" style="display:none">
		  <input type="hidden" id="gd_tmpcount" value="0" />
		  <input type="hidden" id="gd_tot_records" value="100" />
		  <input type="hidden" id="gd_checktotal" value="100" />
		  <input type="hidden" id="gd_terminateaction" value="continue" />
		  <input type="hidden" id="gd_prepared" value="continue" />
		  <input type="hidden" id="gd_rowcount" value="0" />
		  <input type="hidden" id="gd_invalidcount" value="0" />
		  <input type="hidden" id="gd_blank_address" value="0" />
		  <input type="hidden" id="gd_upload_files" value="0" />
		  <input type="hidden" id="gd_invalid_post_type" value="0" />
		  <input type="hidden" id="gd_invalid_title" value="0" />
		  <input type="hidden" id="gd_total_records" value="0" />
		</div>
		<div class="gd-import-progress" id="gd-import-progress" style="display:none"><div class="gd-import-file"><b><?php _e( "Import Data Status :", GEODIRECTORY_TEXTDOMAIN );?> </b><font id="gd-import-done">0</font> / <font id="gd-import-total">0</font>&nbsp;( <font id="gd-import-perc">0%</font> )<div class="gd-fileprogress"></div></div></div>
		<div class="gd-import-msg" id="gd-import-msg" style="display:none"><div id="message" class="message fade"></div></div>
		</td>
    </tr>
  </tbody>
</table>
     
	<?php }
}

/*--------Geodirectory Import Data Functions----------------*/
if (!function_exists('geodir_import_data')) {

	function geodir_import_data() {
	
		global $wpdb, $plugin_prefix, $current_user;
		
		if(isset($_REQUEST['geodir_import_data']) && !empty($_REQUEST['geodir_import_data']))
		{
			$uploads = wp_upload_dir(); 
			$uploads_dir = $uploads['path'];
		
			$curr_img_url = $_REQUEST['filename'];
			
			$image_name_arr = explode('/',$curr_img_url);
			
			$filename = end($image_name_arr);
			
			$target_path = $uploads_dir.'/temp_'.$current_user->data->ID.'/'.$filename;
			
			$destination_path = $uploads_dir.'/temp_'.$current_user->data->ID;
			
			$csv_target_path = $target_path;
			
			ini_set('auto_detect_line_endings', true);
			
			$fd = fopen ($target_path, "rt");
			
			$total_records = 0;
			$rowcount = 0;
			$address_invalid = 0;
			$blank_address = 0;
			$upload_files = 0;
			$invalid_post_type = 0;
			$invalid_title = 0;
			
			$customKeyarray = array();
			
			$gd_post_info = array();
			
			$post_location = array();
			$countpost = 0;
			
			$uploaded_file_type = pathinfo($filename, PATHINFO_EXTENSION);
			
			$extensionarr = array('csv','CSV');
			
			if(in_array($uploaded_file_type,$extensionarr)){
			
				while (!feof ($fd))
				{
					$buffer = fgetcsv($fd, 40960);
					
					if($rowcount == 0)
					{
						for($k=0;$k<count($buffer);$k++)
						{ $customKeyarray[$k] = $buffer[$k]; }
						
						if($customKeyarray[0]==''){
						echo $geodir_url = admin_url().'admin.php?page=geodirectory&tab=general_settings&active_tab=csv_upload_settings&emsg=wrong';
						exit;	
						}
					}
					elseif(!empty($buffer))
					{
						
						$total_records++;
						
						$post_title = addslashes($buffer[0]);
						$current_post_author = $buffer[1];
						$post_desc = addslashes($buffer[2]);
						$post_cat = array();
						$catids_arr = array();
						$post_cat = trim($buffer[3]); // comma seperated category name
						
						if($post_cat)
						{
							$post_cat_arr = explode(',',$post_cat);
							
							for($c=0;$c<count($post_cat_arr);$c++){
								$catid = wp_kses_normalize_entities(trim($post_cat_arr[$c]));
								if(!empty($buffer[5])){
									if(in_array($buffer[5],geodir_get_posttypes())){
										$p_taxonomy = geodir_get_taxonomies(addslashes($buffer[5]));
										if(get_term_by( 'name', $catid, $p_taxonomy[0] )){
											$cat = get_term_by( 'name', $catid, $p_taxonomy[0]);
											$catids_arr[] = $cat->slug;
										}
										elseif(get_term_by( 'slug', $catid, $p_taxonomy[0] )){
											$cat = get_term_by( 'slug', $catid, $p_taxonomy[0]);
											$catids_arr[] = $cat->slug;
										}
									}
								}
							}
						}
						
						if(!$catids_arr){ $catids_arr[] = 1;}
						
						$post_tags = trim($buffer[4]); // comma seperated tags
						
						$tag_arr = '';
						if($post_tags){
							
							$tag_arr = explode(',',$post_tags);	
						}
						
						$table = $plugin_prefix . $buffer[5] . '_detail'; // check table in database
						
						$error = '';
						if($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table){
							
							$invalid_post_type++;
							continue;
							
						}
							
						if($post_title!='')
						{
							
							$menu_order = 0;
							
							$image_folder_name = 'uplaod/';
							
							$image_names = array();
							
							for($c=5;$c<count($customKeyarray);$c++)
							{
								$gd_post_info[$customKeyarray[$c]] = addslashes($buffer[$c]);
								
								if($customKeyarray[$c]=='IMAGE')
								{
									$buffer[$c] = trim($buffer[$c]) ;
									if(!empty($buffer[$c]))
									{
										$image_names[] = $buffer[$c];
									}
									
								}
								
								if($customKeyarray[$c]=='alive_days'){
									if($buffer[$c] != '0' && $buffer[$c]!=''){
										$submitdata = date('Y-m-d');
										$gd_post_info['expire_date'] = date('Y-m-d', strtotime($submitdata."+".addslashes($buffer[$c])." days"));
									}else{$gd_post_info['expire_date'] = 'Never'; }
								}
								
								
								if($customKeyarray[$c]=='post_city')
								{ $post_city = addslashes($buffer[$c]); }
								
								if($customKeyarray[$c]=='post_region')
								{ $post_region = addslashes($buffer[$c]); }
								
								if($customKeyarray[$c]=='post_country')
								{ $post_country = addslashes($buffer[$c]); }
								
								if($customKeyarray[$c]=='post_latitude')
								{ $post_latitude = addslashes($buffer[$c]); }
								
								if($customKeyarray[$c]=='post_longitude')
								{ $post_longitude = addslashes($buffer[$c]); }
								
							}
							
							/* ================ before array create ============== */
							
						$location_result = geodir_get_default_location();
							
						if((!isset($gd_post_info['post_city']) || $gd_post_info['post_city'] == '') || (!isset($gd_post_info['post_region']) || $gd_post_info['post_region'] == '') || (!isset($gd_post_info['post_country']) || $gd_post_info['post_country'] == '') || (!isset($gd_post_info['post_address']) || $gd_post_info['post_address']=='') || (!isset($gd_post_info['post_latitude']) || $gd_post_info['post_latitude'] == '') || (!isset($gd_post_info['post_longitude']) || $gd_post_info['post_longitude'] == '')){
								
								$blank_address++;
								continue;
								
							}elseif($location_result->location_id == 0){
								
								if((strtolower($gd_post_info['post_city']) != strtolower($location_result->city)) || 
								(strtolower($gd_post_info['post_region']) != strtolower($location_result->region)) || 
								(strtolower($gd_post_info['post_country']) != strtolower($location_result->country))){
									
									$address_invalid++;
									continue;
									
								}
								
							}
							
							
							$my_post['post_title'] = $post_title;
							$my_post['post_content'] = $post_desc;
							$my_post['post_type'] = addslashes($buffer[5]);
							$my_post['post_author'] = $current_post_author;
							$my_post['post_status'] = 'publish';
							$my_post['post_category'] = $catids_arr;
							$my_post['post_tags'] = $tag_arr;
							
							
							$gd_post_info['post_tags'] = $tag_arr;
							$gd_post_info['post_title'] = $post_title;
							$gd_post_info['post_status'] = 'publish';
							$gd_post_info['submit_time'] = time();
							$gd_post_info['submit_ip'] = $_SERVER['REMOTE_ADDR'];
							
							
							$last_postid = wp_insert_post( $my_post );
							$countpost++;
							
							
							// Check if we need to save post location as new location 
							
							if($location_result->location_id > 0 )
							{
								if(isset($post_city) && isset($post_region))
								{
								
									$request_info['post_location'] = array( 'city' => $post_city, 
																	'region' => $post_region, 
																	'country' => $post_country,
																	'geo_lat' => $post_latitude,
																	'geo_lng' => $post_longitude );
																	
									$post_location_info = $request_info['post_location'];
									if($location_id = geodir_add_new_location($post_location_info))
										$post_location_id = $location_id;
									
								}
								else{
									$post_location_id = 0;
									}	
							}
							else{
								$post_location_id = 0;
							}
							
							/* ------- get default package info ----- */
							$payment_info = array();	
							$package_info = array();
							
							$package_info = (array)geodir_post_package_info($package_info , '', $buffer[5]);
							$package_id = '';
							if(isset($gd_post_info['package_id']) && $gd_post_info['package_id'] != ''){
								$package_id = $gd_post_info['package_id'];
							}
								
							if(!empty($package_info)){	
								$payment_info['package_id'] = $package_info['pid'];
								if(isset($package_info['alive_days']) && $package_info['alive_days'] != 0){
									$payment_info['expire_date'] = date('Y-m-d', strtotime("+".$package_info['alive_days']." days"));
								}else{$payment_info['expire_date'] = 'Never'; }
							
								$gd_post_info = array_merge($gd_post_info,$payment_info);
							}
							
							
							$gd_post_info['post_location_id'] = $post_location_id;
							
							$post_type = get_post_type( $last_postid );
							
							$table = $plugin_prefix . $post_type . '_detail';
							
							geodir_save_post_info($last_postid, $gd_post_info);
							
							if(!empty($image_names))
							{
								$upload_files++;
								$menu_order = 1;
								foreach($image_names as $image_name){
									
									$img_name_arr = explode('.',$image_name);
									
									$uploads = wp_upload_dir(); 
									$sub_dir = $uploads['subdir'];
									
									$arr_file_type = wp_check_filetype($image_name);
									$uploaded_file_type = $arr_file_type['type'];
									
									$attachment = array(); 
									$attachment['post_id'] = $last_postid;
									$attachment['title'] = $img_name_arr[0];
									$attachment['content'] = '';
									$attachment['file'] = $sub_dir.'/'.$image_name;					
									$attachment['mime_type'] = $uploaded_file_type;
									$attachment['menu_order'] = $menu_order;
									$attachment['is_featured'] = 0;
									
									$attachment_set = '';
									
									foreach($attachment as $key=>$val){
										if($val != '')
										$attachment_set .= $key." = '".$val."', ";
									}
									
									$attachment_set = trim($attachment_set,", ");
									
									$wpdb->query("INSERT INTO ".GEODIR_ATTACHMENT_TABLE." SET ".$attachment_set);
									
									if($menu_order == 1){
										
										$post_type = get_post_type( $last_postid );
							
										$wpdb->query($wpdb->prepare("UPDATE ".$table." SET featured_image = %s where post_id =%d", array($sub_dir.'/'.$image_name,$last_postid)));
									
									}
									
									$menu_order++;
								}
							}
							
							$gd_post_info['package_id'] = $package_id;
							
							do_action('geodir_after_save_listing',$last_postid,$gd_post_info);
							
							if(!empty($buffer[5])){
								if(in_array($buffer[5],geodir_get_posttypes())){
								
									$taxonomies = geodir_get_posttype_info(addslashes($buffer[5]));
									wp_set_object_terms($last_postid, $my_post['post_tags'], $taxonomy=$taxonomies['taxonomies'][1]);
									wp_set_object_terms($last_postid, $my_post['post_category'], $taxonomy=$taxonomies['taxonomies'][0]);
									
									
									$post_default_category = isset($my_post['post_default_category']) ? $my_post['post_default_category'] : '';
			 						
									$post_category_str = isset($my_post['post_category_str']) ? $my_post['post_category_str'] : '';
									geodir_set_postcat_structure($last_postid,$taxonomy,$post_default_category,$post_category_str);
			
								}
								
							}
							 
						}else{$invalid_title++;}
					}				
					$rowcount++;
				}
				fclose($fd);
				//unlink($csv_target_path);
				//rmdir($destination_path);
				if(!empty($filename))
					geodir_remove_temp_images();
				
				
				echo $geodir_url = admin_url().'admin.php?page=geodirectory&tab=general_settings&active_tab=csv_upload_settings&msg=success&rowcount='.$countpost.'&invalidcount='.$address_invalid.'&blank_address='.$blank_address.'&upload_files='.$upload_files.'&invalid_post_type='.$invalid_post_type.'&invalid_title='.$invalid_title.'&total_records='.$total_records;
				exit;
			}else{
				echo $geodir_url = admin_url().'admin.php?page=geodirectory&tab=general_settings&active_tab=csv_upload_settings&emsg=csvonly';
				exit;
			}
		}
		
	}
}

/**
 * Admin fields
 * 
 * Loops though the geodirectory options array and outputs each field.
 */
function geodir_admin_fields($options){
	global $geodirectory;
	
	$first_title = true;
	$tab_id = '';
		$i = 0;
    foreach ($options as $value) :
    	if (!isset( $value['name'] ) ) $value['name'] = '';
    	if (!isset( $value['class'] )) $value['class'] = '';
    	if (!isset( $value['css'] )) $value['css'] = '';
    	if (!isset( $value['std'] )) $value['std'] = '';
        $desc ='';
		switch($value['type']) :
			case 'dummy_installer':
				$post_type = isset($value['post_type']) ? $value['post_type'] : 'gd_place';
				geodir_autoinstall_admin_header($post_type);
			break;
			case 'csv_installer':
				geodir_insert_csv_post_data();
			break;
            case 'title':
						
						if($i == 0){
							echo '<dl id="geodir_oiption_tabs" class="gd-tab-head"></dl>';
							echo '<div class="inner_content_tab_main">';
						}
						
						$i++;
						
				if (isset($value['id']) && $value['id'])
					$tab_id = $value['id'];
					
				if (isset($value['desc']) && $value['desc']) 
					$desc = '<span style=" text-transform:none;">:- '.$value['desc'].'</span>';
        
				if (isset($value['name']) && $value['name']){ 
					if($first_title === true){ $first_title = false; } else { echo '</div>'; } 
					echo '<dd id="'.trim($tab_id).'" class="geodir_option_tabs" ><a href="javascript:void(0);">'.$value['name'].'</a></dd>';
						 
					echo '<div id="sub_'.trim($tab_id).'" class="gd-content-heading" style=" margin-bottom:10px;" >'; 
				}
            	
            	do_action('geodir_settings_'.sanitize_title($value['id']));
            break;
						
						case 'no_tabs':
							
							echo '<div class="inner_content_tab_main">';
							echo '<div id="sub_'.trim($tab_id).'" class="gd-content-heading" style=" margin-bottom:10px;" >'; 
            	
            break;
						
            case 'sectionstart':
				if (isset($value['desc']) && $value['desc']) 
					$desc = '<span style=" text-transform:none;"> - '.$value['desc'].'</span>';
				if (isset($value['name']) && $value['name'])
				echo '<h3>'.$value['name'].$desc.'</h3>'; 
				if (isset($value['id']) && $value['id']) do_action('geodir_settings_'.sanitize_title($value['id']).'_start');
				echo '<table class="form-table">'. "\n\n";
            	
            break;
			case 'sectionend':
            	if (isset($value['id']) && $value['id']) do_action('geodir_settings_'.sanitize_title($value['id']).'_end');
            	echo '</table>';
            	if (isset($value['id']) && $value['id']) do_action('geodir_settings_'.sanitize_title($value['id']).'_after');
            break;
            case 'text':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp"><input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="<?php echo esc_attr( $value['type'] ); ?>" style=" <?php echo esc_attr( $value['css'] ); ?>" value="<?php if ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) { echo esc_attr( stripslashes( get_option($value['id'] ) ) ); } else { echo esc_attr( $value['std'] ); } ?>" /> <span class="description"><?php echo $value['desc']; ?></span></td>
                </tr><?php
            break;
			
			case 'password':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp"><input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="<?php echo esc_attr( $value['type'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" value="<?php if ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) { echo esc_attr( stripslashes( get_option($value['id'] ) ) ); } else { echo esc_attr( $value['std'] ); } ?>" /> <span class="description"><?php echo $value['desc']; ?></span></td>
                </tr><?php
            break;
			
			case 'html_content':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp"><span class="description"><?php echo $value['desc']; ?></span></td>
                </tr><?php
            break;
			
            case 'color' :
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp"><input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="text" style="<?php echo esc_attr( $value['css'] ); ?>" value="<?php if ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) { echo esc_attr( stripslashes( get_option($value['id'] ) ) ); } else { echo esc_attr( $value['std'] ); } ?>" class="colorpick" /> <span class="description"><?php echo $value['desc']; ?></span> <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div></td>
                </tr><?php
            break;
            case 'image_width' :
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp">
                    	
                    	<?php _e('Width', GEODIRECTORY_TEXTDOMAIN); ?> <input name="<?php echo esc_attr( $value['id'] ); ?>_width" id="<?php echo esc_attr( $value['id'] ); ?>_width" type="text" size="3" value="<?php if ( $size = get_option( $value['id'].'_width') ) echo stripslashes($size); else echo $value['std']; ?>" /> 
                    	
                    	<?php _e('Height', GEODIRECTORY_TEXTDOMAIN); ?> <input name="<?php echo esc_attr( $value['id'] ); ?>_height" id="<?php echo esc_attr( $value['id'] ); ?>_height" type="text" size="3" value="<?php if ( $size = get_option( $value['id'].'_height') ) echo stripslashes($size); else echo $value['std']; ?>" /> 
                    	
                    	<label><?php _e('Hard Crop', GEODIRECTORY_TEXTDOMAIN); ?> <input name="<?php echo esc_attr( $value['id'] ); ?>_crop" id="<?php echo esc_attr( $value['id'] ); ?>_crop" type="checkbox" <?php if (get_option( $value['id'].'_crop')!='') checked(get_option( $value['id'].'_crop'), 1); else checked(1); ?> /></label> 
                    	
                    	<span class="description"><?php echo $value['desc'] ?></span></td>
                </tr><?php
            break;
            case 'select':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp"><select name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php if (isset($value['class'])) echo $value['class']; ?>" option-ajaxchosen="false">
                        <?php
                        foreach ($value['options'] as $key => $val) {
							$geodir_select_value = '';
							if(get_option($value['id'])!='')
							{
								if (get_option($value['id'])!='' && get_option($value['id']) == $key)
										$geodir_select_value = ' selected="selected" ';
							}	
							else
							{
								if($value['std']== $key)	
									$geodir_select_value = ' selected="selected" ';
							}
							
						
                        ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo $geodir_select_value; ?> ><?php echo ucfirst($val) ?></option>
                        <?php
                        }
                        ?>
                       </select> <span class="description"><?php echo $value['desc'] ?></span>
                    </td>
                </tr><?php
            break;
			
			case 'multiselect':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp"><select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php if (isset($value['class'])) echo $value['class']; ?>" data-placeholder="<?php if(isset($value['placeholder_text'])) echo $value['placeholder_text'] ;?>" option-ajaxchosen="false">
                        <?php
                        foreach ($value['options'] as $key => $val) {
							if ( strpos( $key, 'optgroup_start-' ) === 0 ) {
								?><optgroup label="<?php echo ucfirst( $val ); ?>"><?php
							} else if ( strpos( $key, 'optgroup_end-' ) === 0 ) {
								?></optgroup><?php
							} else {
                        ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php if(is_array(get_option($value['id']))){ if ( in_array($key, get_option($value['id']))) { ?> selected="selected" <?php }} ?>><?php echo ucfirst($val) ?></option>
                        <?php
							}
                        }
                        ?>
                       </select> <span class="description"><?php echo $value['desc'] ?></span>
                    </td>
                </tr><?php
            break;
			case 'file':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name']; ?></th>
                    <td class="forminp">
                    <input type="file" name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php if (isset($value['class'])) echo $value['class']; ?>" />
                    <?php if(get_option($value['id'])){ ?>
						<input type="hidden" name="<?php echo esc_attr( $value['id'] ); ?>_remove"  id="<?php echo esc_attr( $value['id'] ); ?>_remove" value="0">
						<span class="description"> <?php $uploads = wp_upload_dir(); ?> <a href="<?php echo get_option($value['id']); ?>" target="_blank"><?php echo get_option($value['id']); ?></a> <i title="<?php _e('remove file (set to empty)', GEODIRECTORY_TEXTDOMAIN);?>" onclick="jQuery('#<?php echo esc_attr( $value['id'] ); ?>_remove').val('1'); jQuery( this ).parent().text('<?php _e('save to remove file', GEODIRECTORY_TEXTDOMAIN);?>');" class="fa fa-times gd-remove-file" ></i></span>

                    <?php } ?>
                    </td>
                </tr><?php
            break;	
			case 'map_default_settings' :
			?>	

				<tr valign="top">
					<th class="titledesc" width="40%"><?php _e('Default map language', GEODIRECTORY_TEXTDOMAIN);?></th>
						<td width="60%">
							<select name="geodir_default_map_language" style="width:60%" >
						<?php 
	$arr_map_langages = array(
	'ar' =>  __('ARABIC', GEODIRECTORY_TEXTDOMAIN) ,
	'eu' =>  __('BASQUE', GEODIRECTORY_TEXTDOMAIN) ,
	'bg' =>  __('BULGARIAN' , GEODIRECTORY_TEXTDOMAIN),
	'bn' =>  __('BENGALI' , GEODIRECTORY_TEXTDOMAIN),
	'ca' =>  __('CATALAN' , GEODIRECTORY_TEXTDOMAIN),
	'cs' =>  __('CZECH', GEODIRECTORY_TEXTDOMAIN) ,
	'da' =>  __('DANISH' , GEODIRECTORY_TEXTDOMAIN),
	'de' =>  __('GERMAN', GEODIRECTORY_TEXTDOMAIN) ,
	'el' =>  __('GREEK' , GEODIRECTORY_TEXTDOMAIN),
	'en' =>  __('ENGLISH' , GEODIRECTORY_TEXTDOMAIN),
	'en-AU' =>  __('ENGLISH (AUSTRALIAN)', GEODIRECTORY_TEXTDOMAIN) ,
	'en-GB' =>  __('ENGLISH (GREAT BRITAIN)' , GEODIRECTORY_TEXTDOMAIN),
	'es' =>  __('SPANISH' , GEODIRECTORY_TEXTDOMAIN),
	'eu' =>  __('BASQUE' , GEODIRECTORY_TEXTDOMAIN),
	'fa' =>  __('FARSI' , GEODIRECTORY_TEXTDOMAIN),
	'fi' =>  __('FINNISH' , GEODIRECTORY_TEXTDOMAIN),
	'fil' =>  __('FILIPINO' , GEODIRECTORY_TEXTDOMAIN),
	'fr' =>  __('FRENCH', GEODIRECTORY_TEXTDOMAIN) ,
	'gl' =>  __('GALICIAN' , GEODIRECTORY_TEXTDOMAIN),
	'gu' =>  __('GUJARATI', GEODIRECTORY_TEXTDOMAIN) ,
	'hi' =>  __('HINDI' , GEODIRECTORY_TEXTDOMAIN),
	'hr' =>  __('CROATIAN' , GEODIRECTORY_TEXTDOMAIN),
	'hu' =>  __('HUNGARIAN', GEODIRECTORY_TEXTDOMAIN),
	'id' =>  __('INDONESIAN' , GEODIRECTORY_TEXTDOMAIN),
	'it' =>  __('ITALIAN', GEODIRECTORY_TEXTDOMAIN) ,
	'iw' =>  __('HEBREW', GEODIRECTORY_TEXTDOMAIN) ,
	'ja' =>  __('JAPANESE' , GEODIRECTORY_TEXTDOMAIN),
	'kn' =>  __('KANNADA' , GEODIRECTORY_TEXTDOMAIN),
	'ko' =>  __('KOREAN', GEODIRECTORY_TEXTDOMAIN) ,
	'lt' =>  __('LITHUANIAN' , GEODIRECTORY_TEXTDOMAIN),
	'lv' =>  __('LATVIAN', GEODIRECTORY_TEXTDOMAIN) ,
	'ml' =>  __('MALAYALAM' , GEODIRECTORY_TEXTDOMAIN),
	'mr' =>  __('MARATHI', GEODIRECTORY_TEXTDOMAIN) ,
	'nl' =>  __('DUTCH' , GEODIRECTORY_TEXTDOMAIN),
	'no' =>  __('NORWEGIAN' , GEODIRECTORY_TEXTDOMAIN),
	'pl' => __( 'POLISH' , GEODIRECTORY_TEXTDOMAIN),
	'pt' =>  __('PORTUGUESE' , GEODIRECTORY_TEXTDOMAIN),
	'pt-BR' =>  __('PORTUGUESE (BRAZIL)' , GEODIRECTORY_TEXTDOMAIN),
	'pt-PT' =>  __('PORTUGUESE (PORTUGAL)', GEODIRECTORY_TEXTDOMAIN) ,
	'ro' =>  __('ROMANIAN' , GEODIRECTORY_TEXTDOMAIN),
	'ru' =>  __('RUSSIAN', GEODIRECTORY_TEXTDOMAIN) ,
	'ru' =>  __('RUSSIAN', GEODIRECTORY_TEXTDOMAIN) ,
	'sk' =>  __('SLOVAK' , GEODIRECTORY_TEXTDOMAIN),
	'sl' =>  __('SLOVENIAN' , GEODIRECTORY_TEXTDOMAIN),
	'sr' => __( 'SERBIAN', GEODIRECTORY_TEXTDOMAIN) ,
	'sv' =>  __('	SWEDISH', GEODIRECTORY_TEXTDOMAIN) ,
	'tl' =>  __('TAGALOG' , GEODIRECTORY_TEXTDOMAIN),
	'ta' =>  __('TAMIL', GEODIRECTORY_TEXTDOMAIN) ,
	'te' =>  __('TELUGU' , GEODIRECTORY_TEXTDOMAIN),
	'th' =>  __('THAI', GEODIRECTORY_TEXTDOMAIN) ,
	'tr' =>  __('TURKISH' , GEODIRECTORY_TEXTDOMAIN),
	'uk' =>  __('UKRAINIAN' , GEODIRECTORY_TEXTDOMAIN) ,
	'vi' => __( 'VIETNAMESE' , GEODIRECTORY_TEXTDOMAIN),
	'zh-CN' =>  __('CHINESE (SIMPLIFIED)', GEODIRECTORY_TEXTDOMAIN) ,
			'zh-TW' => __('CHINESE (TRADITIONAL)', GEODIRECTORY_TEXTDOMAIN),
	);
	$geodir_default_map_language = get_option('geodir_default_map_language');
	if(empty($geodir_default_map_language))
		$geodir_default_map_language ='en';
	foreach($arr_map_langages as $language_key =>  $language_txt )
	{
		if(!empty($geodir_default_map_language) && $language_key==$geodir_default_map_language)
			$geodir_default_language_selected = "selected='selected'" ;
		else
			$geodir_default_language_selected ='';
			
?>
	<option value="<?php echo $language_key?>" <?php echo $geodir_default_language_selected  ; ?>><?php echo $language_txt; ?></option>

<?php	} 
?>
						</select>
						</td>
				</tr>
				
				<tr valign="top">
					<th class="titledesc" width="40%" ><?php _e('Default post type search on map' , GEODIRECTORY_TEXTDOMAIN);?></th>
						<td width="60%">
						<select name="geodir_default_map_search_pt" style="width:60%" >
						<?php 
	$post_types = geodir_get_posttypes('array');
	$geodir_default_map_search_pt = get_option('geodir_default_map_search_pt');
	if(empty($geodir_default_map_search_pt))
		$geodir_default_map_search_pt='gd_place';
	if(is_array($post_types))
	{
		foreach($post_types as $key => $post_types_obj)
		{
			if(!empty($geodir_default_map_search_pt) && $key==$geodir_default_map_search_pt)
				$geodir_search_pt_selected = "selected='selected'" ;
			else
				$geodir_search_pt_selected ='';
				
?>
								<option value="<?php echo $key?>" <?php echo $geodir_search_pt_selected  ; ?>><?php echo $post_types_obj['labels']['singular_name']; ?></option>
						
						<?php	} 
	
	}
	
?>
						</select>
						</td>
				</tr>  
                    
			<?php 
            break ;
						
			case 'map':
            	?>	
				<tr valign="top">
				  <td class="forminp">
				<?php 
					global $post_cat, $cat_display;
					$post_types = geodir_get_posttypes( 'object' );
					$cat_display = 'checkbox';
					$gd_post_types = get_option( 'geodir_exclude_post_type_on_map' );
					$gd_cats = get_option( 'geodir_exclude_cat_on_map' );
					$gd_cats_upgrade = (int)get_option( 'geodir_exclude_cat_on_map_upgrade' );				
					$count = 1;
				?>
				<table width="70%" class="widefat">
					<thead>
						<tr>
							<th><b><?php echo DESIGN_POST_TYPE_SNO; ?></b></th>
							<th><b><?php echo DESIGN_POST_TYPE; ?></b></th>
							<th><b><?php echo DESIGN_POST_TYPE_CAT; ?></b></th>
						</tr>
						<?php 
					$gd_categs = $gd_cats;
					foreach( $post_types as $key => $post_types_obj ) :
						$checked = is_array( $gd_post_types ) && in_array( $key, $gd_post_types ) ? 'checked="checked"' : '';
						$gd_taxonomy = geodir_get_taxonomies( $key );
						if( $gd_cats_upgrade ) {
							$gd_cat_taxonomy = isset( $gd_taxonomy[0] ) ? $gd_taxonomy[0] : '';
							$gd_cats = isset( $gd_categs[$gd_cat_taxonomy] ) ? $gd_categs[$gd_cat_taxonomy] : array();
							$gd_cats = !empty( $gd_cats ) && is_array( $gd_cats ) ? array_unique( $gd_cats ) : array();
						}
						$post_cat = implode( ',', $gd_cats );
						$gd_taxonomy_list = geodir_custom_taxonomy_walker( $gd_taxonomy );
					?>
						<tr>
						  <td valign="top" width="5%"><?php echo $count; ?></td>
						  <td valign="top" width="25%" id="td_post_types"><input type="checkbox" name="home_map_post_types[]" id="<?php echo esc_attr( $value['id'] ); ?>" value="<?php echo $key; ?>" class="map_post_type" <?php echo $checked;?> />
							<?php echo $post_types_obj->labels->singular_name; ?></td>
						  <td width="40%">
							<div class="home_map_category" style="overflow:auto;width:200px;height:100px;" id="<?php echo $key; ?>"><?php echo $gd_taxonomy_list; ?></div>
						  </td>
						</tr>
					<?php $count++; endforeach; ?>
					  </thead>
					</table>
					<p><?php _e('Note: Tick respective post type or categories which you want to hide from home page map widget.', GEODIRECTORY_TEXTDOMAIN)?></p>
				   </td>
				</tr>
				<?php
            break;
						
            case 'checkbox' :
            
            	if (!isset($value['checkboxgroup']) || (isset($value['checkboxgroup']) && $value['checkboxgroup']=='start')) :
            		?>
            		<tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
					<td class="forminp">
					<?php
            	endif;
            	
            	?>
	            <fieldset><legend class="screen-reader-text"><span><?php echo $value['name'] ?></span></legend>
					<label for="<?php echo $value['id'] ?>">
					<input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="checkbox" value="1" <?php checked(get_option($value['id']), true); ?> />
					<?php echo $value['desc'] ?></label><br>
				</fieldset>
				<?php
				
				if (!isset($value['checkboxgroup']) || (isset($value['checkboxgroup']) && $value['checkboxgroup']=='end')) :
					?>
						</td>
					</tr>
					<?php
				endif;
				
            break;
			
			case 'radio' :
            
            	if (!isset($value['radiogroup']) || (isset($value['radiogroup']) && $value['radiogroup']=='start')) :
            		?>
            		<tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
					<td class="forminp">
					<?php
            	endif;
            	
            	?>
	            <fieldset><legend class="screen-reader-text"><span><?php echo $value['name'] ?></span></legend>
					<label for="<?php echo $value['id'];?>">
					<input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'].$value['value'] ); ?>" type="radio" value="<?php echo $value['value'] ?>" <?php if( get_option($value['id'])==$value['value']){echo 'checked="checked"';} ?> />
					<?php echo $value['desc']; ?></label><br>
				</fieldset>
				<?php
				
				if (!isset($value['radiogroup']) || (isset($value['radiogroup']) && $value['radiogroup']=='end')) :
					?>
						</td>
					</tr>
					<?php
				endif;
				
            break;
			
            case 'textarea':
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp">
											<textarea <?php if ( isset($value['args']) ) echo $value['args'] . ' '; ?>name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>"><?php if (get_option($value['id'])) echo esc_textarea(stripslashes(get_option($value['id']))); else echo esc_textarea( $value['std'] ); ?></textarea><span class="description"><?php echo $value['desc'] ?></span>
											
                    </td>
                </tr><?php
            break;
						
						case 'editor':
            	?><tr valign="top">
									<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp"><?php
											
											$content = '';
											if (get_option($value['id'])) 
												$content = stripslashes(get_option($value['id'])); 
											else 
												$content = $value['std'];
												
											$editor_settings = array('media_buttons'=>false, 'textarea_rows'=>10);
											
											wp_editor( $content, esc_attr( $value['id'] ), $editor_settings );
											
											?> <span class="description"><?php echo $value['desc'] ?></span>
											
                    </td>
                </tr><?php
            break;
						
            case 'single_select_page' :
            	$page_setting = (int) get_option($value['id']);
            	
            	$args = array( 'name'				=> $value['id'],
            				   'id'					=> $value['id'],
            				   'sort_column' 		=> 'menu_order',
            				   'sort_order'			=> 'ASC',
            				   'show_option_none' 	=> ' ',
            				   'class'				=> $value['class'],
            				   'echo' 				=> false,
            				   'selected'			=> $page_setting);
            	
            	if( isset($value['args']) ) $args = wp_parse_args($value['args'], $args);
            	
            	?><tr valign="top" class="single_select_page">
                    <th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp">
			        	<?php echo str_replace(' id=', " data-placeholder='".__('Select a page...', 'geodirectory')."' style='".$value['css']."' class='".$value['class']."' id=", wp_dropdown_pages($args)); ?> <span class="description"><?php echo $value['desc'] ?></span>        
			        </td>
               	</tr><?php	
            break;
            case 'single_select_country' :
            	$countries = $geodirectory->countries->countries;
            	$country_setting = (string) get_option($value['id']);
            	if (strstr($country_setting, ':')) :
            		$country = current(explode(':', $country_setting));
            		$state = end(explode(':', $country_setting));
            	else :
            		$country = $country_setting;
            		$state = '*';
            	endif;
            	?><tr valign="top">
                    <th scope="rpw" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp"><select name="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" data-placeholder="<?php _e('Choose a country&hellip;', GEODIRECTORY_TEXTDOMAIN); ?>" title="Country" class="chosen_select">	
			        	<?php echo $geodirectory->countries->country_dropdown_options($country, $state); ?>          
			        </select> <span class="description"><?php echo $value['desc'] ?></span>
               		</td>
               	</tr><?php	
            break;
            case 'multi_select_countries' :
            	$countries = $geodirectory->countries->countries;
            	asort($countries);
            	$selections = (array) get_option($value['id']);
            	?><tr valign="top">
					<th scope="row" class="titledesc"><?php echo $value['name'] ?></th>
                    <td class="forminp">
	                    <select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;',GEODIRECTORY_TEXTDOMAIN); ?>" title="Country" class="chosen_select">	
				        	<?php
				        		if ($countries) foreach ($countries as $key=>$val) :
	                    			echo '<option value="'.$key.'" '.selected( in_array($key, $selections), true, false ).'>'.$val.'</option>';   			
	                    		endforeach;   
	                    	?>     
				        </select>
               		</td>
               	</tr>
                
				<?php        
						            	
            break;
						
						case 'field_seperator' :
            
            	?><tr valign="top">
                    <td colspan="2" class="forminp geodir_line_seperator"></td>
    						</tr>
				<?php        
						            	
            break;
			
        endswitch;
		
    endforeach;
	
	if($first_title === false){ echo "</div>"; }
	
	?>
	
    <script type="text/javascript">
    
		
		jQuery(document).ready(function(){
			
			jQuery('.geodir_option_tabs').each(function(ele){
				jQuery('#geodir_oiption_tabs').append(jQuery(this));
			});
			
			
			
			jQuery('.geodir_option_tabs').removeClass('gd-tab-active');
			jQuery('.geodir_option_tabs:first').addClass('gd-tab-active');
			
			jQuery('.gd-content-heading').hide();
			jQuery('.gd-content-heading:first').show();
			jQuery('.geodir_option_tabs').bind('click',function(){
				var tab_id = jQuery(this).attr('id');
				
				if(tab_id=='dummy_data_settings'){
					jQuery('p .button-primary').hide();
				}else if(tab_id=='csv_upload_settings'){
					jQuery('p .button-primary').hide();
				}else{
					jQuery('.button-primary').show();	
				}
				
				if(jQuery('#sub_'+tab_id+' div').hasClass('geodir_auto_install'))
					jQuery('p .button-primary').hide();
				
				jQuery('.geodir_option_tabs').removeClass('gd-tab-active');
				jQuery(this).addClass('gd-tab-active');
				jQuery('.gd-content-heading').hide();
				jQuery('#sub_'+tab_id).show();
				jQuery('.active_tab').val(tab_id);
				jQuery("select.chosen_select").trigger("chosen:updated"); //refresh closen
			});
			
			
			
			<?php
			if(isset($_REQUEST['active_tab']) && $_REQUEST['active_tab'] != '')
			{
				
				?>
				jQuery('.geodir_option_tabs').removeClass('gd-tab-active');
				jQuery('#<?php echo $_REQUEST['active_tab'];?>').addClass('gd-tab-active');
				
				jQuery('.gd-content-heading').hide();
				jQuery('#sub_<?php echo $_REQUEST['active_tab'];?>').show();
				
				
				
				<?php
			}
			?>
			
		});
    
    </script>
	
	<?php   
}

function geodir_post_info_setting()
{
	global $post,$post_id;
	
	$post_type = get_post_type();
	
	$package_info = array() ;
	
	$package_info = geodir_post_package_info($package_info , $post, $post_type);
	wp_nonce_field( plugin_basename( __FILE__ ), 'geodir_post_info_noncename' );
	echo '<div id="geodir_wrapper">';
	do_action('geodir_before_default_field_in_meta_box');
	//geodir_get_custom_fields_html($package_info->pid,'default',$post_type); 
	// to display all fields in one information box
	geodir_get_custom_fields_html($package_info->pid,'all',$post_type); 
	do_action('geodir_after_default_field_in_meta_box');
	echo '</div>';
}

function geodir_post_addinfo_setting()
{
	global $post,$post_id;
	
	$post_type = get_post_type();
	
	$package_info = array() ;
	
	$package_info = geodir_post_package_info($package_info , $post, $post_type);
	
	wp_nonce_field( plugin_basename( __FILE__ ), 'geodir_post_addinfo_noncename' );
	echo '<div id="geodir_wrapper">';
	geodir_get_custom_fields_html($package_info->pid,'custom',$post_type);
	echo '</div>';

}

function geodir_post_attachments()
{
	global $post,$post_id;

	wp_nonce_field( plugin_basename( __FILE__ ), 'geodir_post_attachments_noncename' ); 
	
	if( geodir_get_featured_image($post_id,'thumbnail')) { 
	     echo '<h4>'. __('Featured Image' , GEODIRECTORY_TEXTDOMAIN) .'</h4>';
		 geodir_show_featured_image($post_id,'thumbnail');
    } 
	
	$image_limit = 0;
	
	?>
    
    
    <h5 class="form_title"> 
    	<?php if($image_limit!=0 && $image_limit==1 ){echo '<br /><small>('.__('You can upload' , GEODIRECTORY_TEXTDOMAIN).' '.$image_limit.' '.__('image with this package' , GEODIRECTORY_TEXTDOMAIN).')</small>';} ?>
        <?php if($image_limit!=0 && $image_limit>1 ){echo '<br /><small>('.__('You can upload' , GEODIRECTORY_TEXTDOMAIN).' '.$image_limit.' '.__('images with this package' , GEODIRECTORY_TEXTDOMAIN).')</small>';} ?>
        <?php if($image_limit==0){echo '<br /><small>('.__('You can upload unlimited images with this package' , GEODIRECTORY_TEXTDOMAIN).')</small>';} ?>
    </h5>


	<?php
    
    $curImages = geodir_get_images($post_id);
    $place_img_array = array();
    
    if(!empty($curImages)):
        foreach($curImages as $p_img):
            $place_img_array[] = $p_img->src;
        endforeach;
    endif;
    
    if(!empty($place_img_array))
        $curImages = implode(',', $place_img_array);
    
    
    
    // adjust values here
    $id = "post_images"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls
    
    $svalue = $curImages; // this will be initial value of the above form field. Image urls.
    
    $multiple = true; // allow multiple files upload
    
    $width = geodir_media_image_large_width(); // If you want to automatically resize all uploaded images then provide width here (in pixels)
    
    $height = geodir_media_image_large_height(); // If you want to automatically resize all uploaded images then provide height here (in pixels)
    
    ?>

<div class="gtd-form_row clearfix" id="<?php echo $id; ?>dropbox" style="border:1px solid #999999; padding:5px;" align="center">
	<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
    <div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
     <h4><?php _e('Drop files to upload' , GEODIRECTORY_TEXTDOMAIN);?></h4>
    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php _e('Select Files' ,GEODIRECTORY_TEXTDOMAIN); ?>" class="button" />
    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
    <?php if ($width && $height): ?>
    <span class="plupload-resize"></span>
    <span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
    <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
    <?php endif; ?>
    <div class="filelist"></div>
    </div>
    <div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?> clearfix" id="<?php echo $id; ?>plupload-thumbs" style="border-top:1px solid #ccc; padding-top:10px;">
    </div>
	<span id="upload-msg" ><?php _e('Please drag &amp; drop the images to rearrange the order' , GEODIRECTORY_TEXTDOMAIN);?></span>
	<span id="upload-error" style="display:none"></span>
</div>    

<?php    

} 

function geodir_action_post_updated( $post_ID, $post_after, $post_before ) {
	$post_type = get_post_type( $post_ID );
	
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'inline-save' ) {
		if( $post_type != '' && in_array( $post_type, geodir_get_posttypes() ) && !wp_is_post_revision( $post_ID ) && !empty( $post_after->post_title ) && $post_after->post_title != $post_before->post_title ) {
			geodir_save_post_meta( $post_ID, 'post_title', $post_after->post_title );
		}
	}
}

/*
 * Add bcc option
 */
function geodir_notification_add_bcc_option($settings) {
	if (!empty($settings)) {
		$new_settings = array();
		foreach ($settings as $setting) {
			if (isset($setting['id']) && $setting['id']=='site_bcc_options' && isset($setting['type']) && $setting['type']=='sectionend') {				
				$geodir_bcc_listing_published_yes = array(  
											'name' => __( 'Listing published', GEODIRECTORY_TEXTDOMAIN ),
											'desc' => __( 'Yes', GEODIRECTORY_TEXTDOMAIN),
											'id' => 'geodir_bcc_listing_published',
											'std' => 'yes',
											'type' => 'radio',
											'value' => '1',
											'radiogroup' => 'start'
										);
				
				$geodir_bcc_listing_published_no = array(  
											'name' => __( 'Listing published', GEODIRECTORY_TEXTDOMAIN ),
											'desc' => __( 'No', GEODIRECTORY_TEXTDOMAIN ),
											'id' => 'geodir_bcc_listing_published',
											'std' => 'yes',
											'type' => 'radio',
											'value' => '0',
											'radiogroup' => 'end'
										);
				
				$new_settings[] = $geodir_bcc_listing_published_yes;
				$new_settings[] = $geodir_bcc_listing_published_no;
			}
			$new_settings[] = $setting;
		}
		$settings = $new_settings;
	}
		
	return $settings;
}


add_action( 'wp_ajax_get_gd_theme_compat_callback', 'get_gd_theme_compat_callback' );

function get_gd_theme_compat_callback() {
	global $wpdb; 
	$themes = get_option('gd_theme_compats');
	
	if(isset($_POST['theme']) && isset($themes[$_POST['theme']]) && !empty($themes[$_POST['theme']])){
		if(isset($_POST['export'])){
			echo json_encode(array($_POST['theme'] => $themes[$_POST['theme']]));
		}else{
		    echo json_encode($themes[$_POST['theme']]);	
		}
		
	}

	die();
}

add_action( 'wp_ajax_get_gd_theme_compat_import_callback', 'get_gd_theme_compat_import_callback' );

function get_gd_theme_compat_import_callback() {
	global $wpdb; 
	$themes = get_option('gd_theme_compats');
	if(isset($_POST['theme']) && !empty($_POST['theme'])){
		$json = json_decode(stripslashes($_POST['theme']), true);
		if(!empty($json) && is_array($json)){
			$key =  key($json);
			$themes[$key]=$json[$key];
			update_option('gd_theme_compats',$themes);
			echo $key;
			die();
		}
	}
echo '0';
	die();
}


function gd_set_theme_compat(){
	global $wpdb;
$theme = wp_get_theme();
	
if($theme->parent()){
$theme_name = str_replace(" ","_",$theme->parent()->get( 'Name' ));	
}else{
$theme_name =  str_replace(" ","_",$theme->get( 'Name' ));
}
	
	$theme_compats = get_option('gd_theme_compats');
	$current_compat = get_option('gd_theme_compat');
$current_compat = str_replace("_custom","",$current_compat);

if($current_compat == $theme_name && strpos("_custom", get_option('gd_theme_compat')) !== false){return;}// if already running corect compat then bail

if(isset($theme_compats[$theme_name])){// if there is a compat avail then set it
	update_option('gd_theme_compat',$theme_name);
	update_option('theme_compatibility_setting',$theme_compats[$theme_name]);
	
		// if there are default options to set then set them
		if(isset($theme_compats[$theme_name]['geodir_theme_compat_default_options']) && !empty($theme_compats[$theme_name]['geodir_theme_compat_default_options'])){
			
			foreach($theme_compats[$theme_name]['geodir_theme_compat_default_options'] as $key=>$val){
				update_option($key,$val);
			}
		}
	
}else{
	update_option('gd_theme_compat','');
	update_option('theme_compatibility_setting','');
}

	
}



// function to check if Avada needs header.php replaced
add_action('wp_loaded','gd_check_avada_compat');
function gd_check_avada_compat(){
if(function_exists('avada_load_textdomain') && !get_option('avada_nag')){
add_action('admin_notices', 'gd_avada_compat_warning');	
}
}



function gd_avada_compat_warning(){
	
	/*
	$msg_type = error
	$msg_type = updated fade
	$msg_type = update-nag	
	*/
	
	$plugin = 'avada-nag';
	$timestamp ='avada-nag123';
	$message = __('Avada theme has no hooks for compatibility, because of this you must add two small changes to the header.php. <a href="http://docs.wpgeodirectory.com/avada-compatibility-header-php/" target="_blank">Instructions</a>',GEODIRECTORY_TEXTDOMAIN);
	echo '<div id="'.$timestamp.'"  class="error">';
	echo '<span class="gd-remove-noti" onclick="gdRemoveANotification(\''.$plugin.'\',\''.$timestamp.'\');" ><i class="fa fa-times"></i></span>';
	echo "<img class='gd-icon-noti' src='".plugin_dir_url('')."geodirectory/geodirectory-assets/images/favicon.ico' > ";
	echo "<p>$message</p>";
	echo "</div>";
	
	?>
    <script>
	function gdRemoveANotification($plugin,$timestamp){
		
		jQuery('#'+$timestamp).css("background-color", "red");
		jQuery('#'+$timestamp).fadeOut("slow");
		// This does the ajax request
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				'action':'geodir_avada_remove_notification',
				'plugin' : $plugin,
				'timestamp' : $timestamp
			},
			success:function(data) {
				// This outputs the result of the ajax request
				//alert(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 
		
	}
	</script>
    <style>
	.gd-icon-noti{
		float: left;
		margin-top: 10px;
		margin-right: 5px;
	}
	
	.update-nag .gd-icon-noti{
		margin-top: 2px;
	}
	.gd-remove-noti{
		float: right;margin-top: -20px;margin-right: -20px;color:#FF0000;cursor:pointer;
	}
	
	.updated .gd-remove-noti, .error .gd-remove-noti{
		float: right;margin-top: -10px;margin-right: -17px;color:#FF0000;cursor:pointer;
	}
	
	
	</style>
    <?php
    	
}


function geodir_avada_remove_notification() {
 	update_option('avada_nag',TRUE);
     
    // Always die in functions echoing ajax content
   die();
}

 
add_action( 'wp_ajax_geodir_avada_remove_notification', 'geodir_avada_remove_notification' );



