<?php
/**
 * GeoDirectory Admin
 * 
 * Main admin file which loads all settings panels and sets up admin menus.
 *
 * @author 		WPGeoDirectory
 * @category 	Admin
 * @package 	GeoDirectory
 */
 
/* Admin init loader */

add_action('admin_init', 'geodir_admin_init');
if (!function_exists('geodir_admin_init')) {
	function geodir_admin_init() {
		
		if(is_admin()):
			global $current_tab ;
			geodir_redirect_to_admin_panel_on_installed() ;
			$current_tab = (isset($_GET['tab']) && $_GET['tab']!='') ? $_GET['tab'] : 'general_settings';
			if(!(isset($_REQUEST['action']))) // this will avoide Ajax requests
			geodir_handle_option_form_submit($current_tab); // located in admin function.php
			do_action('admin_panel_init') ;
			add_action('geodir_admin_option_form', 'geodir_get_admin_option_form', 1);
			

		endif;	
	}
}

function geodir_redirect_to_admin_panel_on_installed() 
{
	if (get_option('geodir_installation_redirect', false)) {
        delete_option('geodir_installation_redirect');
        wp_redirect(admin_url('admin.php?page=geodirectory&installed=yes')); 
    }
}
			
function geodir_get_admin_option_form($current_tab)
{
	geodir_admin_option_form($current_tab);// defined in admin template tags.php
}



/* Is used to show success or error message at the top of admin option panel */
add_action('geodir_update_options_compatibility_settings', 'geodir_update_options_compatibility_settings');
add_action('geodir_update_options_default_location_settings', 'geodir_location_form_submit');
add_action('geodir_before_admin_panel', 'geodir_before_admin_panel') ; // this function is in admin_functions.php

//add_action('geodir_before_admin_panel', 'geodir_autoinstall_admin_header');

/* Admin scripts loader */
function geodir_conditional_admin_script_load(){
global $pagenow;
	if((isset($_REQUEST['page']) && $_REQUEST['page'] =='geodirectory' ) || ($pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'edit.php' || $pagenow == 'edit-tags.php' || $pagenow == 'edit-comments.php' || $pagenow == 'comment.php') )
	{
		add_action( 'admin_enqueue_scripts', 'geodir_admin_scripts' );
		add_action( 'admin_enqueue_scripts', 'geodir_admin_styles' );
		
	}

add_action( 'admin_enqueue_scripts', 'geodir_admin_styles_req' );

}

add_action(  'init', 'geodir_conditional_admin_script_load');






/**
 * Admin Menus 
 */
add_action('admin_menu', 'geodir_admin_menu');

/**
 * Order admin menus
 */
add_action('menu_order', 'geodir_admin_menu_order');

add_action('admin_panel_init', 'geodir_location_form_submit'); // in location_function.php 

add_action('admin_panel_init', 'create_default_admin_main_nav', 1);
add_action('admin_panel_init','geodir_admin_list_columns',2);

/* --- insert dummy post action ---*/
add_action('geodir_insert_dummy_posts_gd_place','geodir_insert_dummy_posts',1);
add_action('geodir_delete_dummy_posts_gd_place','geodir_delete_dummy_posts',1);

function create_default_admin_main_nav()
{
	add_filter('geodir_settings_tabs_array' , 'geodir_default_admin_main_tabs' ,1 ) ;
	add_filter('geodir_settings_tabs_array','places_custom_fields_tab',2); 
	add_filter('geodir_settings_tabs_array','geodir_compatibility_setting_tab',90); 
	add_filter('geodir_settings_tabs_array','geodir_tools_setting_tab',95); 
	add_filter('geodir_settings_tabs_array','geodir_extend_geodirectory_setting_tab',100); 
	//add_filter('geodir_settings_tabs_array', 'geodir_hide_set_location_default',3);
	
} 


function geodir_admin_list_columns(){
	if($post_types = geodir_get_posttypes()){
		
		foreach($post_types as $post_type):
			add_filter( "manage_edit-{$post_type}_columns", 'geodir_edit_post_columns' , 100 ) ;		
			//Filter-Payment-Manager to show Package 
			add_action( "manage_{$post_type}_posts_custom_column", 'geodir_manage_post_columns', 10, 2 );
			
			add_filter( "manage_edit-{$post_type}_sortable_columns", 'geodir_post_sortable_columns' );
		endforeach;
	}
}

function geodir_default_admin_main_tabs($tabs)
{
	return $tabs = array(
					'general_settings' => array( 'label' => __( 'General', GEODIRECTORY_TEXTDOMAIN ) ),
					'design_settings' => array( 'label' =>__( 'Design', GEODIRECTORY_TEXTDOMAIN)),
					'permalink_settings' => array( 'label' =>__( 'Permalinks', GEODIRECTORY_TEXTDOMAIN)),
					'notifications_settings'   => array( 'label' =>__('Notifications', GEODIRECTORY_TEXTDOMAIN)),
					'default_location_settings' => 	array('label'=> __( 'Set Default Location', GEODIRECTORY_TEXTDOMAIN ) ),
					
                  );
}

add_action( 'do_meta_boxes', 'geodir_remove_image_box' );
function geodir_remove_image_box() {
	global $post;
	
	$geodir_posttypes = geodir_get_posttypes();
	
	if( isset($post) && in_array($post->post_type,$geodir_posttypes) ):
		
		remove_meta_box( 'postimagediv', $post->post_type, 'side' );
		remove_meta_box('revisionsdiv', $post->post_type, 'normal');
	
	endif;
	
}


add_action( 'add_meta_boxes', 'geodir_meta_box_add' );
function geodir_meta_box_add()
{	
	global $post;
	
	$geodir_post_types = geodir_get_posttypes('array');
	$geodir_posttypes = array_keys($geodir_post_types);

	if( isset($post->post_type) && in_array($post->post_type,$geodir_posttypes) ):
	
		$geodir_posttype = $post->post_type;
		$post_typename = ucwords($geodir_post_types[$geodir_posttype]['labels']['singular_name']);
		
		// Filter-Payment-Manager
		
		add_meta_box( 'geodir_post_images', $post_typename.' '.__('Attachments',GEODIRECTORY_TEXTDOMAIN), 'geodir_post_attachments', $geodir_posttype,'side' );
		
		add_meta_box( 'geodir_post_info', $post_typename.' '. __('Information',GEODIRECTORY_TEXTDOMAIN), 'geodir_post_info_setting', $geodir_posttype,'normal', 'high' );
		
		// no need of this box as all fields moved to main informain box
		//add_meta_box( 'geodir_post_addinfo', $post_typename. ' ' .__('Additional Information' , GEODIRECTORY_TEXTDOMAIN), 'geodir_post_addinfo_setting', $geodir_posttype,'normal', 'high' );
	
	endif;
	
}

add_action( 'save_post', 'geodir_post_information_save' );


//add_filter('geodir_design_settings' , 'geodir_show_hide_location_switcher_nav' ) ;



// Geodirectory hide categories post meta.
add_action( 'admin_menu', 'geodir_hide_post_taxonomy_meta_boxes');  
function geodir_hide_post_taxonomy_meta_boxes(){  
	
		$geodir_post_types = get_option( 'geodir_post_types' );
	
		if(!empty($geodir_post_types))
		{ 
			 foreach($geodir_post_types as $geodir_post_type => $geodir_posttype_info){
			 
			 	$gd_taxonomy = geodir_get_taxonomies($geodir_post_type);
			 	
				foreach($gd_taxonomy as $tax){
				
					remove_meta_box( $tax.'div', $geodir_post_type, 'normal' ); 
					
				}
						
			 } 
		}	
}

add_filter('geodir_add_listing_map_restrict', 'geodir_add_listing_map_restrict' );
function geodir_add_listing_map_restrict($map_restirct)
{
	if(is_admin())
	{
		if(isset($_REQUEST['tab']) && $_REQUEST['tab'] =='default_location_settings')
		{
			$map_restirct = false;
		}
	}
	return $map_restirct;
}


add_filter('geodir_notifications_settings', 'geodir_enable_editor_on_notifications', 1);

function geodir_enable_editor_on_notifications($notification){
	
	if(!empty($notification) && get_option('geodir_tiny_editor')=='1'){
		
		foreach($notification as $key => $value){
			if($value['type'] == 'textarea')
				$notification[$key]['type'] = 'editor';
		}
		
	}
	
	return $notification;
}


add_filter('geodir_design_settings', 'geodir_enable_editor_on_design_settings', 1);

function geodir_enable_editor_on_design_settings($design_setting){
	
	if(!empty($design_setting) && get_option('geodir_tiny_editor')=='1'){
		
		foreach($design_setting as $key => $value){
			if($value['type'] == 'textarea' && $value['id'] == 'geodir_term_condition_content')
				$design_setting[$key]['type'] = 'editor';
		}
		
	}
	
	return $design_setting;
}

/* ----------- START MANAGE CUSTOM FIELDS ---------------- */


add_action('geodir_manage_available_fields', 'geodir_manage_available_fields');

function geodir_manage_available_fields($sub_tab){
	
	switch($sub_tab)
	{
		case 'custom_fields':
			geodir_custom_available_fields();
		break;
		
		case 'sorting_options':
			geodir_sorting_options_available_fields();
		break;
		
	}
}


add_action('geodir_manage_selected_fields', 'geodir_manage_selected_fields');

function geodir_manage_selected_fields($sub_tab){
	
	switch($sub_tab)
	{
		case 'custom_fields':
			geodir_custom_selected_fields();
		break;
		
		case 'sorting_options':
			geodir_sorting_options_selected_fields();
		break;
		
	}
}


function geodir_sorting_options_available_fields(){
	global $wpdb;
	$listing_type	= ($_REQUEST['listing_type'] != '') ? $_REQUEST['listing_type'] : 'gd_place';
	?>
	<input type="hidden" name="listing_type" id="new_post_type" value="<?php echo $listing_type;?>"  />
	<input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo $_REQUEST['subtab']; ?>" />
	<ul>
		
		<?php
		
		$sort_options = geodir_get_custom_sort_options($listing_type);
		
		
		
		foreach($sort_options as $key => $val){
		
				
				$check_html_variable = 	$wpdb->get_var(
							$wpdb->prepare(
									"select htmlvar_name from ".GEODIR_CUSTOM_SORT_FIELDS_TABLE." where htmlvar_name = %s and post_type = %s and field_type=%s ",
									array($val['htmlvar_name'], $listing_type, $val['field_type'])		
							)
					);
					
					$display = '';
					if($check_html_variable)
						$display = ' style="display:none;"';
					
			
			?><li <?php echo $display;?>>
			<a id="gt-<?php echo $val['field_type'];?>-_-<?php echo $val['htmlvar_name'];?>" title="<?php echo $val['site_title'];?>" class="gt-draggable-form-items gt-<?php echo $val['field_type'];?> geodir-sort-<?php echo $val['htmlvar_name'];?>" href="javascript:void(0);"><b></b><?php _e($val['site_title'], GEODIRECTORY_TEXTDOMAIN);?></a>
			</li><?php
			
		}
		
		?>
		
	</ul>
	
	<?php

}


function geodir_sorting_options_selected_fields(){
	
	$listing_type	= ($_REQUEST['listing_type'] != '') ? $_REQUEST['listing_type'] : 'gd_place';
	?>
	
	<input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo $_REQUEST['subtab']; ?>" />
	
	<ul class="core"><?php global $wpdb;
				
				
				$fields =	$wpdb->get_results(
										$wpdb->prepare(
											"select * from ".GEODIR_CUSTOM_SORT_FIELDS_TABLE." where post_type = %s order by sort_order asc",
											array($listing_type)
										)
									);
				
				if(!empty($fields))
				{
					foreach($fields as $field)
					{
						//$result_str = $field->id;
						$result_str =$field;
						$field_type = $field->field_type;
						$field_ins_upd = 'display';
						
						$default = false;
						
					geodir_custom_sort_field_adminhtml($field_type, $result_str, $field_ins_upd, $default);
					}
				}
			?></ul>
	<?php
	
}


function geodir_custom_available_fields(){
	
	$listing_type	= ($_REQUEST['listing_type'] != '') ? $_REQUEST['listing_type'] : 'gd_place';
	?>
	<input type="hidden" name="listing_type" id="new_post_type" value="<?php echo $listing_type;?>"  />
	<input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo $_REQUEST['subtab']; ?>" />
	<ul class="full">
		<li><a id="gt-fieldset" class="gt-draggable-form-items gt-fieldset" href="javascript:void(0);"><?php _e('Fieldset' ,GEODIRECTORY_TEXTDOMAIN);?></a></li>
	</ul>
	<ul>
		<li><a id="gt-text" class="gt-draggable-form-items gt-text" href="javascript:void(0);"><b></b><?php _e('Text' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-datepicker" class="gt-draggable-form-items gt-datepicker" href="javascript:void(0);"><b></b><?php _e('Date', GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-textarea" class="gt-draggable-form-items gt-textarea" href="javascript:void(0);"><b></b><?php _e('Textarea' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-time" class="gt-draggable-form-items gt-time" href="javascript:void(0);"><b></b><?php _e('Time' , GEODIRECTORY_TEXTDOMAIN);?></a></li>	
		<li><a id="gt-checkbox" class="gt-draggable-form-items gt-checkbox" href="javascript:void(0);"><b></b><?php _e('Checkbox' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-phone" class="gt-draggable-form-items gt-phone"  href="javascript:void(0);"><b></b><?php _e('Phone' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-radio" class="gt-draggable-form-items gt-radio" href="javascript:void(0);"><b></b><?php _e('Radio', GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-email" class="gt-draggable-form-items gt-email" href="javascript:void(0);"><b></b><?php _e('Email' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-select" class="gt-draggable-form-items gt-select" href="javascript:void(0);"><b></b><?php _e('Select' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<!--<li><a id="gt-taxonomy" class="gt-draggable-form-items gt-select" href="javascript:void(0);"><b></b><?php _e('Taxonomy' , GEODIRECTORY_TEXTDOMAIN);?></a></li>-->	
		<li><a id="gt-multiselect" class="gt-draggable-form-items gt-multiselect" href="javascript:void(0);"><b></b><?php _e('Multi Select' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		 	<li><a id="gt-url" class="gt-draggable-form-items gt-url" href="javascript:void(0);"><b></b><?php _e('URL' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-html" class="gt-draggable-form-items gt-html" href="javascript:void(0);"><b></b><?php _e('HTML' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
		<li><a id="gt-file" class="gt-draggable-form-items gt-file" href="javascript:void(0);"><b></b><?php _e('File Upload' , GEODIRECTORY_TEXTDOMAIN);?></a></li>
						
	</ul>
	
	<?php
	
}


function geodir_custom_selected_fields(){
	
	$listing_type	= ($_REQUEST['listing_type'] != '') ? $_REQUEST['listing_type'] : 'gd_place';
	?>
	<input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo $_REQUEST['subtab']; ?>" />
	<ul class="core"><?php global $wpdb;
							
							
							$fields =	$wpdb->get_results(
													$wpdb->prepare(
														"select * from ".GEODIR_CUSTOM_FIELDS_TABLE." where post_type = %s order by sort_order asc",
														array($listing_type)
													)
												);
							
							if(!empty($fields))
							{
								foreach($fields as $field)
								{
									//$result_str = $field->id;
									$result_str =$field;
									$field_type = $field->field_type;
									$field_ins_upd = 'display';
									
									geodir_custom_field_adminhtml($field_type, $result_str, $field_ins_upd);
								}
							}
						?></ul>
	<?php
	
}

add_filter('geodir_custom_fields_panel_head' , 'geodir_custom_fields_panel_head' , 1, 3) ;
function geodir_custom_fields_panel_head($heading , $sub_tab , $listing_type)
{
	
	switch($sub_tab)
	{
		case 'custom_fields':
			$heading =	sprintf(__('Manage %s Custom Fields' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
		break;
		
		case 'sorting_options':
			$heading =	sprintf(__('Manage %s Listing Sorting Options Fields' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
			break;
	}
	return $heading;
}


add_filter('geodir_cf_panel_available_fields_head' , 'geodir_cf_panel_available_fields_head' , 1, 3) ;
function geodir_cf_panel_available_fields_head($heading , $sub_tab , $listing_type)
{
	
	switch($sub_tab)
	{
		case 'custom_fields':
			$heading =	sprintf( __('Add new %s form field' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
		break;
		
		case 'sorting_options':
			$heading =	sprintf(__('Available sorting options for %s listing and search results' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
			break;
	}
	return $heading;
}


add_filter('geodir_cf_panel_available_fields_note' , 'geodir_cf_panel_available_fields_note' , 1, 3) ;
function geodir_cf_panel_available_fields_note($note , $sub_tab , $listing_type)
{
	
	switch($sub_tab)
	{
		case 'custom_fields':
			$note =	sprintf( __('Click on any box below to add a field of that type on add %s listing form. You must be use a fieldset to group your fields.' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));;
		break;
		 
		case 'sorting_options':
			$note =	sprintf(__('Click on any box below to make it appear in sorting option dropdown on %s listing and search results.<br />To make a field available here, go to custom fields tab and expand any field from selected fields panel and tick the checkbox saying \'Include this field in sort option\'.' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
			break;
	}
	return $note;
}


add_filter('geodir_cf_panel_selected_fields_head' , 'geodir_cf_panel_selected_fields_head' , 1, 3) ;
function geodir_cf_panel_selected_fields_head($heading , $sub_tab , $listing_type)
{
	
	switch($sub_tab)
	{
		case 'custom_fields':
			$heading =	sprintf( __('List of fields those will appear on add new %s listing form' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
		break;
		
		case 'sorting_options':
			$heading =	sprintf(__('List of fields those will appear in %s listing and search resutls sorting option dropdown box.' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
			break;
	}
	return $heading;
}


add_filter('geodir_cf_panel_selected_fields_note' , 'geodir_cf_panel_selected_fields_note' , 1, 3) ;
function geodir_cf_panel_selected_fields_note($note , $sub_tab , $listing_type)
{
	
	switch($sub_tab)
	{
		case 'custom_fields':
			$note =	sprintf( __('Click to expand and view field related settings. You may drag and drop to arrange fields order on add %s listing form too.' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));;
		break;
		
		case 'sorting_options':
			$note =	sprintf(__('Click to expand and view field related settings. You may drag and drop to arrange fields order in sorting option dropdown box on %s listing and search results page.' , GEODIRECTORY_TEXTDOMAIN),  get_post_type_singular_label($listing_type));
			break;
	}
	return $note;
}



add_action('admin_init', 'geodir_remove_unnecessary_fields');

function geodir_remove_unnecessary_fields(){
	global $wpdb, $plugin_prefix;
	
	if(!get_option('geodir_remove_unnecessary_fields')){
	
		if($wpdb->get_var("SHOW COLUMNS FROM ".$plugin_prefix."gd_place_detail WHERE field = 'categories'"))
			$wpdb->query("ALTER TABLE `".$plugin_prefix."gd_place_detail` DROP `categories`");
		
		update_option('geodir_remove_unnecessary_fields', '1');
	
	}
	
}


/* ----------- END MANAGE CUSTOM FIELDS ---------------- */

/* Ajax Handler Start */
add_action('wp_ajax_geodir_admin_ajax', "geodir_admin_ajax_handler");

function geodir_admin_ajax_handler()
{
	if(isset($_REQUEST['geodir_admin_ajax_action']) && $_REQUEST['geodir_admin_ajax_action']!='')
	{
		$geodir_admin_ajax_action = $_REQUEST['geodir_admin_ajax_action'] ; 
		switch ($geodir_admin_ajax_action)
		{
			case 'diagnosis' :
				if(isset($_REQUEST['diagnose_this']) && $_REQUEST['diagnose_this']!='')
					$diagnose_this = $_REQUEST['diagnose_this'] ;
					call_user_func('geodir_diagnose_' . $diagnose_this) ;
					exit();
				break;
				
			case 'diagnosis-fix' :
				if(isset($_REQUEST['diagnose_this']) && $_REQUEST['diagnose_this']!='')
					$diagnose_this = $_REQUEST['diagnose_this'] ;
					call_user_func('geodir_diagnose_' . $diagnose_this) ;
					exit();
				break;
		}
	}
	exit();
}


function geodir_diagnose_multisite_table($filter_arr,$table,$tabel_name,$fix){
  global $wpdb;
  //$filter_arr['output_str'] .='###'.$table.'###';
  if($wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak2'")>0 && $wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak'")>0){
	$filter_arr['output_str'] .= "<li>".__('ERROR: You didnt follow instructions! Now you will need to contact support to manually fix things.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;$filter_arr['is_error_during_diagnose']=true;
 
  }
  elseif($wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."$table'")>0){
  $filter_arr['output_str'] .= "<li>".sprintf( __('ERROR: %s_ms_bak table found' , GEODIRECTORY_TEXTDOMAIN), $tabel_name )."</li>" ;$filter_arr['is_error_during_diagnose']=true;
  $filter_arr['output_str'] .= "<li>".__('IMPORTANT: This can be caused by out of date core or addons, please update core + addons before trying the fix OR YOU WILL HAVE A BAD TIME!' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;$filter_arr['is_error_during_diagnose']=true;
	
	if($fix){
		$ms_bak_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$table."_ms_bak");// get backup table count
		$new_table_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."$table");// get new table count
		
		if($ms_bak_count==$new_table_count){// if they are the same count rename to bak2
			//$filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: %s table count is the same as new table, contact support' , GEODIRECTORY_TEXTDOMAIN), $table )."</li>" ;
			
			$wpdb->query("RENAME TABLE ".$table."_ms_bak TO ".$table."_ms_bak2");// rename bak table to new table
			
			if($wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak2'") && $wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak'")==0){
			$filter_arr['output_str'] .= "<li>".__('-->FIXED: Renamed and backed up the tables' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
			}else{
			$filter_arr['output_str'] .= "<li>".__('-->PROBLEM: Failed to rename tables, please contact support.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
			}
			
		}elseif($ms_bak_count>$new_table_count){//if backup is greater then restore it
		
			$wpdb->query("RENAME TABLE ".$wpdb->prefix."$table TO ".$table."_ms_bak2");// rename new table to bak2
			$wpdb->query("RENAME TABLE ".$table."_ms_bak TO ".$wpdb->prefix."$table");// rename bak table to new table
			
			if($wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak2'") && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."$table'") && $wpdb->query("SHOW TABLES LIKE '$table'")==0){
			$filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: restored largest table %s' , GEODIRECTORY_TEXTDOMAIN), $table )."</li>" ;
			}else{
			$filter_arr['output_str'] .= "<li>".__('-->PROBLEM: Failed to rename tables, please contact support.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
			}
			
		}elseif($new_table_count>$ms_bak_count){// we cant do much so rename the table to stop errors
			
			$wpdb->query("RENAME TABLE ".$table."_ms_bak TO ".$table."_ms_bak2");// rename ms_bak table to ms_bak2
			
			if($wpdb->query("SHOW TABLES LIKE '".$table."_ms_bak'")==0){
			$filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: table %s_ms_bak renamed and backedup' , GEODIRECTORY_TEXTDOMAIN), $table )."</li>" ;
			}else{
			$filter_arr['output_str'] .= "<li>".__('-->PROBLEM: Failed to rename tables, please contact support.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
			}
			
		}
		
	}
  
  
  }
  elseif($wpdb->query("SHOW TABLES LIKE '$table'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."$table'")>0){
  $filter_arr['output_str'] .= "<li>".sprintf( __('ERROR: Two %s tables found' , GEODIRECTORY_TEXTDOMAIN), $tabel_name )."</li>" ;$filter_arr['is_error_during_diagnose']=true;
  
	  if($fix){
		  if($wpdb->get_var("SELECT COUNT(*) FROM $table")==0){// if first table is empty just delete it
			 if($wpdb->query("DROP TABLE IF EXISTS $table")){
			 $filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: Deleted table %s' , GEODIRECTORY_TEXTDOMAIN), $table )."</li>" ;
			 }else{
			 $filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: Delete table %s failed, please try manual delete from DB' , GEODIRECTORY_TEXTDOMAIN), $table )."</li>" ; 
			 }

		  }elseif($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."$table")==0){// if main table is empty but original is not, delete main and rename original
			  if($wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."$table")){
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: Deleted table %s' , GEODIRECTORY_TEXTDOMAIN), $wpdb->prefix.$table )."</li>" ;
			  }else{
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: Delete table %s failed, please try manual delete from DB' , GEODIRECTORY_TEXTDOMAIN), $wpdb->prefix.$table )."</li>" ;			  			  }
			  if($wpdb->query("RENAME TABLE $table TO ".$wpdb->prefix."$table") || $wpdb->query("SHOW TABLES LIKE '$table'")==0){
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: Table %s renamed to %s' , GEODIRECTORY_TEXTDOMAIN), $table,$wpdb->prefix.$table )."</li>" ;  
			  }else{
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: Failed to rename table %s to %s, please try manually from DB' , GEODIRECTORY_TEXTDOMAIN), $table,$wpdb->prefix.$table )."</li>" ;  			  
			  }
		  }else{// else rename the original table to _ms_bak
			 if($wpdb->query("RENAME TABLE $table TO ".$table."_ms_bak") || $wpdb->query("SHOW TABLES LIKE '$table'")==0){
			 $filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: Table contained info so we renamed %s to %s incase it is needed in future' , GEODIRECTORY_TEXTDOMAIN), $table,$table."_ms_bak" )."</li>" ;  			   
			 }else{
			 $filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: Table %s could not be renamed to %s, this table has info so may need to be reviewed manually in the DB' , GEODIRECTORY_TEXTDOMAIN), $table,$table."_ms_bak" )."</li>" ;	 
			 }
		  }		  
	  }
  
  }
  elseif($wpdb->query("SHOW TABLES LIKE '$table'")>0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."$table'")==0){
  $filter_arr['output_str'] .= "<li>".sprintf( __('ERROR: %s table not converted' , GEODIRECTORY_TEXTDOMAIN), $tabel_name )."</li>" ;$filter_arr['is_error_during_diagnose']=true;
  		
	  if($fix){
		  // if orignal table exists but new does not, rename
		  if($wpdb->query("RENAME TABLE $table TO ".$wpdb->prefix."$table") || $wpdb->query("SHOW TABLES LIKE '$table'")==0){
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->FIXED: Table %s renamed to %s' , GEODIRECTORY_TEXTDOMAIN), $table,$wpdb->prefix.$table )."</li>" ;
		  }else{
			  $filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: Failed to rename table %s to %s, please try manually from DB' , GEODIRECTORY_TEXTDOMAIN), $table,$wpdb->prefix.$table )."</li>" ;
		  }
		  
	  }
	  
  }
  elseif($wpdb->query("SHOW TABLES LIKE '$table'")==0 && $wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix."$table'")==0){
  $filter_arr['output_str'] .= "<li>".sprintf( __('ERROR: %s table does not exist' , GEODIRECTORY_TEXTDOMAIN), $tabel_name )."</li>" ;$filter_arr['is_error_during_diagnose']=true;
  
  	if($fix){
		  // if orignal table does not exist try deleting db_vers of all addons so the initial db_install scripts run;
		  delete_option('geodirlocation_db_version'); 
		  delete_option('geodirevents_db_version'); 
		  delete_option('geodir_reviewrating_db_version'); 
		  delete_option('gdevents_db_version'); 
		  delete_option('geodirectory_db_version'); 
		  delete_option('geodirclaim_db_version'); 
		  delete_option('geodir_custom_posts_db_version'); 
		  delete_option('geodir_reviewratings_db_version'); 
		  delete_option('geodiradvancesearch_db_version'); 
		  $filter_arr['output_str'] .= "<li>".__('-->TRY: Please refresh page to run table install functions' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	  }
	  
  }
  else{
  $filter_arr['output_str'] .= "<li>".sprintf( __('%s table converted correctly' , GEODIRECTORY_TEXTDOMAIN), $tabel_name )."</li>";	
  }
  return $filter_arr;
}


function geodir_diagnose_tags_sync()
{	global $wpdb,$plugin_prefix;
	$fix =  isset($_POST['fix']) ? true : false;
	
	//if($fix){echo 'true';}else{echo 'false';}
	$is_error_during_diagnose = false;
	$output_str = '';
	
	
	$all_postypes = geodir_get_posttypes();
	
	if(!empty($all_postypes))
		{
			foreach($all_postypes as $key)
			{
			// update each GD CTP
			$posts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."geodir_".$key."_detail d");
			
					if(!empty($posts)){
						
							foreach($posts as $p){
								$p->post_type = $key;
							$raw_tags = wp_get_object_terms($p->post_id,$p->post_type.'_tags' ,array('fields'=>'names'));
							if(empty($raw_tags)){$post_tags = '';}
							else{$post_tags = implode(",",$raw_tags);}
							$tablename = $plugin_prefix.$p->post_type.'_detail';
							$wpdb->query($wpdb->prepare("UPDATE ".$tablename." SET post_tags=%s WHERE post_id =%d",$post_tags,$p->post_id));
							
							}
			$output_str .= "<li>".$key.__(': Done' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
					  }
				
			}
			
		}

if($is_error_during_diagnose)
	{
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "<input type='button' value='".__('Fix' , GEODIRECTORY_TEXTDOMAIN)."' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
	
}

function geodir_diagnose_cats_sync()
{	global $wpdb,$plugin_prefix;
	$fix =  isset($_POST['fix']) ? true : false;
	
	//if($fix){echo 'true';}else{echo 'false';}
	$is_error_during_diagnose = false;
	$output_str = '';
	
	
	$all_postypes = geodir_get_posttypes();
	
	if(!empty($all_postypes))
		{
			foreach($all_postypes as $key)
			{
			// update each GD CTP
			$posts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."geodir_".$key."_detail d WHERE d.".$key."category='' ");
			
					if(!empty($posts)){
						
							foreach($posts as $p){
								$p->post_type = $key;
							$raw_cats = wp_get_object_terms($p->post_id,$p->post_type.'category' ,array('fields'=>'ids'));
							
							if(empty($raw_cats)){
								$post_categories = get_post_meta($p->post_id,'post_categories',true);
								
								if(!empty($post_categories) && !empty($post_categories[$p->post_type.'category'])){
								 $post_categories[$p->post_type.'category']  = str_replace("d:", "", $post_categories[$p->post_type.'category'] );
									foreach(explode(",", $post_categories[$p->post_type.'category'] ) as $cat_part){
											if(is_numeric($cat_part)){$raw_cats[] = (int)$cat_part;}				
									}
									
								}
								
								if(!empty($raw_cats)){
									$term_taxonomy_ids  = wp_set_object_terms( $p->post_id, $raw_cats, $p->post_type.'category');
									
								}
								
							}
							
							
							if(empty($raw_cats)){$post_cats = '';}
							else{$post_cats = ','.implode(",",$raw_cats).',';}
							$tablename = $plugin_prefix.$p->post_type.'_detail';
							$wpdb->query($wpdb->prepare("UPDATE ".$tablename." SET ".$p->post_type."category=%s WHERE post_id =%d",$post_cats,$p->post_id));
							}
			
					  }
			$output_str .= "<li>".$key.__(': Done' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
				
			}
			
		}

if($is_error_during_diagnose)
	{
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "<input type='button' value='".__('Fix' , GEODIRECTORY_TEXTDOMAIN)."' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
	
}

function geodir_diagnose_version_clear()
{	global $wpdb,$plugin_prefix;
	$fix =  isset($_POST['fix']) ? true : false;
	
	//if($fix){echo 'true';}else{echo 'false';}
	$is_error_during_diagnose = false;
	$output_str = '';
	
	
	$gd_arr = array('GeoDirectory' => 'geodirectory_db_version',
					'Payment Manager' => 'geodir_payments_db_version',
					'GeoDirectory Framework' => 'gdf_db_version',
					'Advanced Search' => 'geodiradvancesearch_db_version',
					'Review Rating Manager' => 'geodir_reviewratings_db_version',
					'Claim Manager' => 'geodirclaim_db_version',
					'CPT Manager' => 'geodir_custom_posts_db_version',
					'Location Manager' => 'geodirlocation_db_version',
					'Payment Manager' => 'geodir_payments_db_version',
					'Events Manager' => 'geodirevents_db_version',
					);
	
	$ver_arr = apply_filters('geodir_db_version_name',$gd_arr);
	
	if(!empty($ver_arr))
		{
			foreach($ver_arr as $key=>$val)
			{
				if(delete_option( $val )){
					$output_str .= "<li>".$key.__(' Version: Deleted' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
				}else{
					$output_str .= "<li>".$key.__(' Version: Not Found' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
				}

			}
			
			if($output_str){
				$output_str .= "<li><strong>".__(' Upgrade/install scripts will run on next page reload.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
			
		}

if($is_error_during_diagnose)
	{
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
	
}


function geodir_diagnose_ratings()
{	global $wpdb;
	$fix =  isset($_POST['fix']) ? true : false;
	
	//if($fix){echo 'true';}else{echo 'false';}
	$is_error_during_diagnose = false;
	$output_str = '';
	
	// check review locations
	if($wpdb->get_results("SELECT * FROM ".GEODIR_REVIEW_TABLE." WHERE post_city='' OR post_city IS NULL OR post_latitude='' OR post_latitude IS NULL")){		
		$output_str .= "<li>".__('Review locations missing or broken' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	$is_error_during_diagnose = true;
		
		if($fix){
			if(geodir_fix_review_location()){
				$output_str .= "<li><strong>".__('-->FIXED: Review locations fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Review locations fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
		
	}else{
		$output_str .= "<li>".__('Review locations ok' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
	}
	
	// check review content
	if($wpdb->get_results("SELECT * FROM ".GEODIR_REVIEW_TABLE." WHERE comment_content IS NULL")){		
		$output_str .= "<li>".__('Review content missing or broken' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	$is_error_during_diagnose = true;
		
		if($fix){
			if(geodir_fix_review_content()){
				$output_str .= "<li><strong>".__('-->FIXED: Review content fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Review content fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
		
	}else{
		$output_str .= "<li>".__('Review content ok' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;	
	}
	



if($is_error_during_diagnose)
	{
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "<input type='button' value='".__('Fix' , GEODIRECTORY_TEXTDOMAIN)."' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
	
}


function geodir_diagnose_multisite_conversion()
{	global $wpdb;
	$fix =  isset($_POST['fix']) ? true : false;
	//if($fix){echo 'true';}else{echo 'false';}
	$is_error_during_diagnose = false;
	$output_str = '';
	
	$filter_arr=array();
	$filter_arr['output_str']=$output_str;
	$filter_arr['is_error_during_diagnose']=$is_error_during_diagnose;
	$table_arr = array('geodir_countries'=>__('Countries' , GEODIRECTORY_TEXTDOMAIN),
					   'geodir_custom_fields'=>__('Custom fields' , GEODIRECTORY_TEXTDOMAIN),					  
					   'geodir_post_icon'=>__('Post icon' , GEODIRECTORY_TEXTDOMAIN),
					   'geodir_attachments'=>__('Attachments' , GEODIRECTORY_TEXTDOMAIN),
					   'geodir_post_review'=>__('Reviews' , GEODIRECTORY_TEXTDOMAIN),
					   'geodir_custom_sort_fields'=>__('Custom sort fields' , GEODIRECTORY_TEXTDOMAIN),
					   'geodir_gd_place_detail'=>__('Place detail' , GEODIRECTORY_TEXTDOMAIN)
					   );
	
	// allow other addons to hook in and add their checks
	$table_arr = apply_filters('geodir_diagnose_multisite_conversion',$table_arr);
	
	foreach($table_arr as $table=>$table_name){
		// Diagnose table
	$filter_arr = geodir_diagnose_multisite_table($filter_arr,$table,$table_name,$fix);	
	}


	
	$output_str = $filter_arr['output_str'];
	$is_error_during_diagnose = $filter_arr['is_error_during_diagnose'];
	
	
	
	if($is_error_during_diagnose)
	{
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "<input type='button' value='".__('Fix' , GEODIRECTORY_TEXTDOMAIN)."' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='multisite_conversion' />";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
}

function geodir_fix_virtual_page($slug,$page_title,$old_id,$option){
	global $wpdb,$current_user;

if(!empty($old_id)){wp_delete_post( $old_id, true );}//delete post if already there
else{
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;",
										array($slug)
									)
								);
	wp_delete_post( $page_found, true );
	
	}
									
$page_data = array( 
'post_status' => 'virtual', 
'post_type' => 'page', 
'post_author' => $current_user->ID, 
'post_name' => $slug, 
'post_title' => $page_title, 
'post_content' => '', 
'post_parent' => 0, 
'comment_status' => 'closed' 
); 
$page_id = wp_insert_post($page_data); 
update_option($option, $page_id); 
if($page_id){return true;}else{return false;}
}

function geodir_diagnose_default_pages()
{
	global $wpdb;
	$is_error_during_diagnose = false ;
	$output_str = '' ; 
	$fix =  isset($_POST['fix']) ? true : false;
	
	
	
	
	//////////////////////////////////
	/* Diagnose Listing Page Starts */
	//////////////////////////////////
	$option_value = get_option('geodir_listing_page'); 
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s AND post_status='virtual' LIMIT 1;",
										array('listings')
									)
								);
	
	if (!empty($option_value) && !empty($page_found ) && $option_value==$page_found) 
		$output_str .= "<li>".__('Listing page exists with proper setting.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	else
	{	
		$is_error_during_diagnose = true ;
		$output_str .= "<li><strong>".__('Listing page is missing.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
		if($fix){
			if(geodir_fix_virtual_page('listings',__('All Listings', GEODIRECTORY_TEXTDOMAIN),$page_found,'geodir_listing_page')){
				$output_str .= "<li><strong>".__('-->FIXED: Listing page fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Listing page fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
	}
	
	////////////////////////////////
	/* Diagnose Listing Page Ends */
	////////////////////////////////
	
	//////////////////////////////////
	/* Diagnose Add Listing Page Starts */
	//////////////////////////////////
	$option_value = get_option('geodir_add_listing_page'); 
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s AND post_status='virtual' LIMIT 1;",
										array('add-listing')
									)
								);
	
	if (!empty($option_value) && !empty($page_found) && $option_value==$page_found) 
		$output_str .= "<li>".__('Add Listing page exists with proper setting.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	else
	{
		$is_error_during_diagnose = true ;
		$output_str .= "<li><strong>".__('Add Listing page is missing.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
		if($fix){
			if(geodir_fix_virtual_page('add-listing',__('Add Listing', GEODIRECTORY_TEXTDOMAIN),$page_found,'geodir_add_listing_page')){
				$output_str .= "<li><strong>".__('-->FIXED: Add Listing page fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Add Listing page fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
	}
	
	////////////////////////////////
	/* Diagnose Add Listing Page Ends */
	////////////////////////////////
	
	
	//////////////////////////////////
	/* Diagnose Listing Preview Page Starts */
	//////////////////////////////////
	$option_value = get_option('geodir_preview_page'); 
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s AND post_status='virtual' LIMIT 1;",
										array('listing-preview')
									)
								);
	
	if (!empty($option_value) && !empty($page_found) && $option_value==$page_found) 
		$output_str .= "<li>".__('Listing Preview page exists with proper setting.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	else
	{
		$is_error_during_diagnose = true ;
		$output_str .= "<li><strong>".__('Listing Preview page is missing.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
		if($fix){
			if(geodir_fix_virtual_page('listing-preview',__('Listing Preview', GEODIRECTORY_TEXTDOMAIN),$page_found,'geodir_preview_page')){
				$output_str .= "<li><strong>".__('-->FIXED: Listing Preview page fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Listing Preview page fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
	}
	
	////////////////////////////////
	/* Diagnose Listing Preview Page Ends */
	////////////////////////////////
	
	//////////////////////////////////
	/* Diagnose Listing Success Page Starts */
	//////////////////////////////////
	$option_value = get_option('geodir_success_page'); 
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s AND post_status='virtual' LIMIT 1;",
										array('listing-success')
									)
								);
	
	if (!empty($option_value) && !empty($page_found) && $option_value==$page_found) 
		$output_str .= "<li>".__('Listing Success page exists with proper setting.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	else
	{
		$is_error_during_diagnose = true ;
		$output_str .= "<li><strong>".__('Listing Success page is missing.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
		if($fix){
			if(geodir_fix_virtual_page('listing-success',__('Listing Success', GEODIRECTORY_TEXTDOMAIN),$page_found,'geodir_success_page')){
				$output_str .= "<li><strong>".__('-->FIXED: Listing Success page fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Listing Success page fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
	}
	
	////////////////////////////////
	/* Diagnose Listing Sucess Page Ends */
	////////////////////////////////
	
	//////////////////////////////////
	/* Diagnose Location Page Starts */
	//////////////////////////////////
	$option_value = get_option('geodir_location_page'); 
	$page_found =	$wpdb->get_var(
									$wpdb->prepare(
										"SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s AND post_status='virtual' LIMIT 1;",
										array('location')
									)
								);
	
	if (!empty($option_value) && !empty($page_found) && $option_value==$page_found) 
		$output_str .= "<li>".__('Location page exists with proper setting.' , GEODIRECTORY_TEXTDOMAIN)."</li>" ;
	else
	{
		$is_error_during_diagnose = true ;
		$output_str .= "<li><strong>".__('Location page is missing.' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
		if($fix){
			if(geodir_fix_virtual_page('location',__('Location', GEODIRECTORY_TEXTDOMAIN),$page_found,'geodir_location_page')){
				$output_str .= "<li><strong>".__('-->FIXED: Location page fixed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}else{
				$output_str .= "<li><strong>".__('-->FAILED: Location page fix failed' , GEODIRECTORY_TEXTDOMAIN)."</strong></li>" ;
			}
		}
	}
	
	////////////////////////////////
	/* Diagnose Location Page Ends */
	////////////////////////////////
	
	if($is_error_during_diagnose)
	{	if($fix){flush_rewrite_rules();}
		$info_div_class =  "geodir_problem_info" ;
		$fix_button_txt = "<input type='button' value='".__('Fix' , GEODIRECTORY_TEXTDOMAIN)."' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='default_pages' />";
	}
	else
	{
		$info_div_class =  "geodir_noproblem_info" ;
		$fix_button_txt = '';
	}
	echo "<ul class='$info_div_class'>" ;
	echo $output_str ;
	echo  $fix_button_txt;
	echo "</ul>" ;
	
}
/* Ajax Handler Ends*/

/* sort by expire */
add_filter('posts_clauses_request', 'geodir_posts_clauses_request');
function geodir_posts_clauses_request($clauses) {
	global $wpdb, $wp_query, $plugin_prefix;
	
	if (is_admin() && !empty($wp_query->query_vars) && !empty($wp_query->query_vars['is_geodir_loop']) && !empty($wp_query->query_vars['orderby']) && $wp_query->query_vars['orderby']=='expire' && !empty($wp_query->query_vars['post_type']) && in_array( $wp_query->query_vars['post_type'], geodir_get_posttypes()) && !empty($wp_query->query_vars['orderby']) && isset($clauses['join']) && isset($clauses['orderby']) && isset($clauses['fields'])) {
		$table = $plugin_prefix . $wp_query->query_vars['post_type'] . '_detail';
		
		$join = $clauses['join'] . ' INNER JOIN ' . $table . ' AS gd_posts ON (gd_posts.post_id = ' . $wpdb->posts . '.ID)';
		$clauses['join'] = $join;
		
		$fields = $clauses['fields'] != '' ? $clauses['fields'] . ', ' : '';
		$fields .= 'IF(UNIX_TIMESTAMP(DATE_FORMAT(gd_posts.expire_date, "%Y-%m-%d")), UNIX_TIMESTAMP(DATE_FORMAT(gd_posts.expire_date, "%Y-%m-%d")), 253402300799) AS gd_expire';
		$clauses['fields'] = $fields;
		
		$order = !empty($wp_query->query_vars['order']) ? $wp_query->query_vars['order'] : 'ASC';
		$orderby = 'gd_expire ' . $order;
		$clauses['orderby'] = $orderby;
	}
	return $clauses;
}

/* display add listing page for wpml */
if(!is_admin()){
add_action( 'admin_init', 'geodir_wpml_check_element_id', 10, 2 );	
}


/* hook action for post updated */
add_action( 'post_updated', 'geodir_action_post_updated', 15, 3 );

/*
 * hook to add option in bcc options
 */
add_filter('geodir_notifications_settings', 'geodir_notification_add_bcc_option', 1);


/*
 * check if there is a compatibility pack when switching theme
 */
add_action('after_switch_theme', 'gd_theme_switch_compat_check');
function gd_theme_switch_compat_check() {
gd_set_theme_compat();
}

/**
 * read string as csv array
 */
function geodir_str_getcsv( $input, $delimiter = ",", $enclosure = '"', $escape = "\\" ) {	
	if ( function_exists( 'str_getcsv' ) ) {
		$fgetcsv = str_getcsv( $input, $delimiter, $enclosure, $escape );
	} else {
		global $current_user;
		$upload_dir = wp_upload_dir();
	
		$file = $upload_dir['path'] . '/temp_' . $current_user->data->ID . '/geodir_tmp.csv'; 
		$handle = fopen( $file, 'w' );
		
		fwrite( $handle, $input );
		fclose( $handle );
		
		$handle = fopen ( $file, 'rt' );
		if(PHP_VERSION>='5.3.0'){
		$fgetcsv = fgetcsv( $handle, 0, $delimiter, $enclosure, $escape );	
		}else{
		$fgetcsv = fgetcsv( $handle, 0, $delimiter, $enclosure );
		}
		fclose( $handle );
	}
	return $fgetcsv;
}

add_action( 'wp_ajax_gdImportCsv', 'geodir_ajax_import_csv' );
function geodir_ajax_import_csv() {
	error_reporting(0); // hide error to get clean json response
	
	global $wpdb, $plugin_prefix, $current_user;
	$uploads = wp_upload_dir(); 
	ini_set( 'auto_detect_line_endings', true );
	
	$task = isset( $_POST['task'] ) ? $_POST['task'] : '';
	$uploadedFile = isset( $_POST['gddata']['uploadedFile'] ) ? $_POST['gddata']['uploadedFile'] : NULL;
	$filename = $uploadedFile;
	
	$uploads = wp_upload_dir(); 
	$uploads_dir = $uploads['path'];
	$image_name_arr = explode( '/', $filename );
	$filename = end( $image_name_arr );
	$target_path = $uploads_dir . '/temp_' . $current_user->data->ID . '/' . $filename;
	$return = array();
	$return['file'] = $uploadedFile;
	$return['error'] = __( 'The uploaded file is not a valid csv file. Please try again.' , GEODIRECTORY_TEXTDOMAIN );

	if ( is_file( $target_path ) && file_exists( $target_path ) && $uploadedFile ) {
		$wp_filetype = wp_check_filetype_and_ext( $target_path, $filename );
		
		if ( !empty( $wp_filetype ) && isset( $wp_filetype['ext'] ) && strtolower( $wp_filetype['ext'] ) == 'csv' ) {
			$return['error'] = NULL;
			$response = wp_remote_get( $uploadedFile );

			$return['rows'] = 0;
			
			if ( !is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
				$file_contents = wp_remote_retrieve_body( $response );
				$file_contents = trim( $file_contents );
				$file = explode( PHP_EOL, $file_contents );
				
				$return['rows'] = ( !empty( $file ) && count( $file ) > 1 ) && strpos( $file_contents, PHP_EOL ) !== false ? count( $file ) - 1 : 0;
			}
	
			if ( !$return['rows'] > 0 ) {
				$return['error'] = __( 'No data found in csv file.' , GEODIRECTORY_TEXTDOMAIN );
			}
		}
	}
	if ( $task == 'prepare' || !empty( $return['error'] ) ) {
		echo json_encode($return); 
		exit;
	}

	$totRecords = isset( $_POST['gddata']['totRecords'] ) ? $_POST['gddata']['totRecords'] : NULL;
	$importlimit = isset( $_POST['gddata']['importlimit'] ) ? $_POST['gddata']['importlimit'] : 1;
	$count = $importlimit;
	$requested_limit = $importlimit;
	$tmpCnt = isset( $_POST['gddata']['tmpcount'] ) ? $_POST['gddata']['tmpcount'] : 0;
	
	if ( $count < $totRecords ) {
		$count = $tmpCnt + $count;
		if ( $count > $totRecords ) {
			$count = $totRecords;
		}
	} else {
		$count = $totRecords;
	}
	
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
	
	if ( !empty( $file ) ) {
		$columns = isset($file[0]) ? geodir_str_getcsv( $file[0] ) : NULL;
		$customKeyarray = $columns;
		
		if ( empty( $columns ) || ( !empty( $columns ) && $columns[0] == '' ) ) {
			$return['error'] = CSV_INVAILD_FILE;
			echo json_encode($return); 
			exit;
		}
				
		for( $i = 1; $i <= $importlimit; $i++ ) {
			$current_index = $tmpCnt + $i;
			if ( isset($file[$current_index])) {
				$total_records++;
				
				$buffer = geodir_str_getcsv( $file[$current_index] );
				$post_title = addslashes( $buffer[0] );
				$current_post_author = $buffer[1];
				$post_desc = addslashes( $buffer[2] );
				$post_cat = array();
				$catids_arr = array();
				$post_cat = trim( $buffer[3] ); // comma seperated category name
				
				if ( $post_cat ) {
					$post_cat_arr = explode( ',', $post_cat );
					
					for ( $c=0; $c < count( $post_cat_arr ); $c++ ) {
						$catid = wp_kses_normalize_entities( trim( $post_cat_arr[$c] ) );
						
						if ( !empty( $buffer[5] ) ) {
							if ( in_array( $buffer[5], geodir_get_posttypes() ) ) {
							
								$p_taxonomy = geodir_get_taxonomies( addslashes( $buffer[5] ) );
								
								if ( get_term_by( 'name', $catid, $p_taxonomy[0] ) ) {
									$cat = get_term_by( 'name', $catid, $p_taxonomy[0] );
									$catids_arr[] = $cat->slug;
								} else if( get_term_by( 'slug', $catid, $p_taxonomy[0] ) ) {
									$cat = get_term_by( 'slug', $catid, $p_taxonomy[0] );
									$catids_arr[] = $cat->slug;
								} else {
									$ret = wp_insert_term( $catid, $p_taxonomy[0] );
									if ( $ret && !is_wp_error( $ret ) ) {
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
					}
				}
				
				if ( !$catids_arr ){
					$catids_arr[] = 1;
				}

				$post_tags = trim( $buffer[4] ); // comma seperated tags
				
				$tag_arr = '';
				if ( $post_tags ) {
					$tag_arr = explode( ',', $post_tags );	
				}
				
				$table = $plugin_prefix . $buffer[5] . '_detail'; // check table in database
				
				$error = '';
				if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
					$invalid_post_type++;
					continue;
				}
				
				if ( $post_title != '' ) {
					$menu_order = 0;
					$image_folder_name = 'uplaod/';
					
					$image_names = array();
					
					for ( $c = 5; $c < count( $customKeyarray ); $c++ ) {
						$gd_post_info[$customKeyarray[$c]] = addslashes( $buffer[$c] );
						
						if ( $customKeyarray[$c] == 'IMAGE' ) {
							$buffer[$c] = trim( $buffer[$c] );
							
							if ( !empty( $buffer[$c] ) ) {
								$image_names[] = $buffer[$c];
							}
						}
						
						if ( $customKeyarray[$c] == 'alive_days' ) {
							if ( $buffer[$c] != '0' && $buffer[$c] != '' ) {
								$submitdata = date( 'Y-m-d' );
								
								$gd_post_info['expire_date'] = date( 'Y-m-d', strtotime( $submitdata . "+" . addslashes( $buffer[$c] ) . " days" ) );
							} else {
								$gd_post_info['expire_date'] = 'Never';
							}
						}
						
						if ( $customKeyarray[$c] == 'post_city' ) {
							$post_city = addslashes( $buffer[$c] );
						}
					
						if ( $customKeyarray[$c] == 'post_region' ) {
							$post_region = addslashes( $buffer[$c] );
						}
						
						if ( $customKeyarray[$c] == 'post_country' ) {
							$post_country = addslashes( $buffer[$c] );
						}
						
						if ( $customKeyarray[$c] == 'post_latitude' ) {
							$post_latitude = addslashes( $buffer[$c] );
						}
						
						if ( $customKeyarray[$c] == 'post_longitude' ) {
							$post_longitude = addslashes( $buffer[$c] );
						}
					}
				
					/* ================ before array create ============== */
					$location_result = geodir_get_default_location();
					if ( ( !isset( $gd_post_info['post_city'] ) || $gd_post_info['post_city'] == '') || ( !isset( $gd_post_info['post_region'] ) || $gd_post_info['post_region'] == '' ) || ( !isset( $gd_post_info['post_country'] ) || $gd_post_info['post_country'] == '' ) || ( !isset( $gd_post_info['post_address'] ) || $gd_post_info['post_address'] == '' ) || ( !isset( $gd_post_info['post_latitude'] ) || $gd_post_info['post_latitude'] == '' ) || ( !isset( $gd_post_info['post_longitude'] ) || $gd_post_info['post_longitude'] == '' ) ) {
						$blank_address++;
						continue;
					} else if ( $location_result->location_id == 0 ) {
						if ( ( strtolower( $gd_post_info['post_city'] ) != strtolower( $location_result->city ) ) || ( strtolower( $gd_post_info['post_region'] ) != strtolower( $location_result->region ) ) || ( strtolower( $gd_post_info['post_country'] ) != strtolower( $location_result->country ) ) ) {
							$address_invalid++;
							continue;
						}
					}
				
					$my_post['post_title'] = $post_title;
					$my_post['post_content'] = $post_desc;
					$my_post['post_type'] = addslashes( $buffer[5] );
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
					if ( $location_result->location_id > 0 ) {
						if ( isset( $post_city ) && isset( $post_region ) ) {
							$request_info['post_location'] = array( 
																'city' => $post_city, 
																'region' => $post_region, 
																'country' => $post_country,
																'geo_lat' => $post_latitude,
																'geo_lng' => $post_longitude
															);
									
							$post_location_info = $request_info['post_location'];
							if ( $location_id = geodir_add_new_location( $post_location_info ) )
								$post_location_id = $location_id;
						} else {
							$post_location_id = 0;
						}
					} else {
						$post_location_id = 0;
					}
				
					/* ------- get default package info ----- */
					$payment_info = array();	
					$package_info = array();
				
					$package_info = (array)geodir_post_package_info( $package_info , '', $buffer[5] );
					$package_id = '';
					if ( isset( $gd_post_info['package_id'] ) && $gd_post_info['package_id'] != '' ) {
						$package_id = $gd_post_info['package_id'];
					}
				
					if ( !empty( $package_info ) ) {
						$payment_info['package_id'] = $package_info['pid'];
						
						if ( isset( $package_info['alive_days'] ) && $package_info['alive_days'] != 0 ) {
							$payment_info['expire_date'] = date('Y-m-d', strtotime("+".$package_info['alive_days']." days"));
						} else {
							$payment_info['expire_date'] = 'Never';
						}
						
						$gd_post_info = array_merge($gd_post_info,$payment_info);
					}
				
					$gd_post_info['post_location_id'] = $post_location_id;
				
					$post_type = get_post_type( $last_postid );
				
					$table = $plugin_prefix . $post_type . '_detail';
				
					geodir_save_post_info( $last_postid, $gd_post_info );
				
					if ( !empty( $image_names ) ) {
						$upload_files++;
						$menu_order = 1;
						
						foreach ( $image_names as $image_name ) {
							$img_name_arr = explode( '.', $image_name );
							
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
				
							foreach ( $attachment as $key=>$val ) {
								if ( $val != '' )
									$attachment_set .= $key." = '".$val."', ";
							}
							$attachment_set = trim($attachment_set,", ");
							
							$wpdb->query("INSERT INTO ".GEODIR_ATTACHMENT_TABLE." SET ".$attachment_set);
				
							if ( $menu_order == 1 ) {
								$post_type = get_post_type( $last_postid );
								$wpdb->query( $wpdb->prepare( "UPDATE ".$table." SET featured_image = %s where post_id =%d", array( $sub_dir.'/'.$image_name,$last_postid ) ) );
							}
							$menu_order++;
						}
					}
					
					$gd_post_info['package_id'] = $package_id;
					
					do_action( 'geodir_after_save_listing', $last_postid,$gd_post_info );
					
					if ( !empty( $buffer[5] ) ) {
						if ( in_array( $buffer[5], geodir_get_posttypes() ) ) {
							$taxonomies = geodir_get_posttype_info( addslashes( $buffer[5] ) );
							wp_set_object_terms( $last_postid, $my_post['post_tags'], $taxonomy=$taxonomies['taxonomies'][1] );
							wp_set_object_terms( $last_postid, $my_post['post_category'], $taxonomy=$taxonomies['taxonomies'][0] );
							
							$post_default_category = isset( $my_post['post_default_category'] ) ? $my_post['post_default_category'] : '';
							$post_category_str = isset( $my_post['post_category_str'] ) ? $my_post['post_category_str'] : '';
							geodir_set_postcat_structure( $last_postid,$taxonomy, $post_default_category,$post_category_str );
						}
					}
				} else {
					$invalid_title++;
				}
			}
		}
	}
	$return['rowcount'] = $countpost;
	$return['invalidcount'] = $address_invalid;
	$return['blank_address'] = $blank_address;
	$return['upload_files'] = $upload_files;
	$return['invalid_post_type'] = $invalid_post_type;
	$return['invalid_title'] = $invalid_title;
	$return['total_records'] = $total_records;
		
	echo json_encode($return); 
	exit;
}