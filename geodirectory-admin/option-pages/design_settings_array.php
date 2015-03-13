<?php

global $geodir_settings;


//function for post type settings
function geodir_post_type_setting_fun()
{
	$post_type_arr = array();
	
	$post_types = geodir_get_posttypes('object');
	
	foreach($post_types as $key => $post_types_obj)
	{
		$post_type_arr[$key] = $post_types_obj->labels->singular_name;
	}
	return 	$post_type_arr;
}


function geodir_theme_location_setting_fun()
{
	$post_type_arr=array();
	$geodir_all_nav_locations=get_registered_nav_menus();
	$geodir_active_nav_locations = get_nav_menu_locations();
	if(!empty($geodir_active_nav_locations) && is_array($geodir_active_nav_locations ))
	{
		foreach($geodir_active_nav_locations as $key => $theme_location)
		{
			if(!empty($geodir_all_nav_locations) && is_array($geodir_all_nav_locations) && array_key_exists($key , $geodir_all_nav_locations))
			$post_type_arr[$key] = $geodir_all_nav_locations[$key];
		}
	}	

	return 	$post_type_arr;
}

$geodir_settings['design_settings'] = apply_filters('geodir_design_settings', array(
	
	/* Home Layout Settings start */
	array( 'name' => __( 'Home', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => 'Setting to set home page layout', 'id' => 'home_page_settings ' ),

		
		array( 	'name' => __( 'Home Top Section Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_home_top_section' ),
	
		array(  
			'name'  => __( 'Geodirectory home page', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 	=> __('Set geodirectory home page as your home', GEODIRECTORY_TEXTDOMAIN ),
			'id' 	=> 'geodir_set_as_home',
			'type' 	=> 'checkbox',
			'std' 	=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Home top section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the top section of home page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_home_top_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		
		
	
	array( 'type' => 'sectionend', 'id' => 'geodir_home_top_section'),
	
	
	
	array( 	'name' => __( 'Home Page Layout Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_home_layout' ),
	
		array(  
			'name' => __( 'Home right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the right section of home page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_home_right_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of home right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of right section of home page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_home_right_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Home content section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the content section of home page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_home_contant_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of home content section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of content section of home page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_home_contant_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '63' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Home left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the left section of home page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_home_left_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of home left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of left section of home page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_home_left_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Home bottom section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the bottom section of home page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_home_bottom_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		array(  
			'name' 		=> __( 'Resize image large size', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> sprintf( __( 'Use default wordpress media image large size ( %s ) for featured image upload. If unchecked then default geodirectory image large size ( 800x800 ) will be used.', GEODIRECTORY_TEXTDOMAIN ), get_option( 'large_size_w' ) . 'x' . get_option( 'large_size_h' ) ),
			'id' 		=> 'geodir_use_wp_media_large_size',
			'type' 		=> 'checkbox',
			'std' 		=> '0'
		),
		
	array( 'type' => 'sectionend', 'id' => 'geodir_home_layout'),
	
	
	/* Home Layout Settings end */
	
	
	/* Listing Layout Settings end */
	
	array( 'name' => __( 'Listings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_listing_settings ' ),

	
	array( 	'name' => __( 'Listing Page Layout Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_listing_layout' ),
		
		array(  
			'name' => __( 'Listing top section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the top section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_listing_top_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Listing right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the right section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_listing_right_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of listing right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of right section of listing page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_listing_right_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		
		array(  
			'name' => __( 'Listing content section view', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the listing view of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_listing_view',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'gridview_onehalf',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array( 
				'gridview_onehalf' => __( 'Grid View (Two Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onethird' => __( 'Grid View (Three Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefourth' => __( 'Grid View (Four Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefifth' => __( 'Grid View (Five Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'listview' => __( 'List view', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
		
		array(  
			'name' => __( 'Width of listing content section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of content section of listing page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_listing_contant_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '63' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Listing left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the left section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_listing_left_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of listing left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of left section of listing in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_listing_left_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Listing bottom section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the bottom section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_listing_bottom_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Upload listing no image', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_listing_no_img',
			'type' 		=> 'file',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Description word limit', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_desc_word_limit',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '50' // Default value to show home top section
		),
		
	array( 'type' => 'sectionend', 'id' => 'geodir_listing_layout'),
		
	
	array( 'name' => __( 'Listing General Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_listing_gen_settings ' ),

	array(  
		'name' => __( 'New listing default status', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Select new listing default status.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_new_post_default_status',
		'css' 		=> 'min-width:300px;',
		'std' 		=> 'publish',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'options' => array_unique( array( 
			'publish' => __( 'publish', GEODIRECTORY_TEXTDOMAIN ),
			'draft' => __( 'draft', GEODIRECTORY_TEXTDOMAIN ),
			))
	),
	
	array(  
		'name' => __( 'New listings settings', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Enter number of days a listing will appear new.(enter 0 to disable feature)', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_listing_new_days',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> '30' // Default value for the page title - changed in settings
	),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_listing_gen_settings'),
	
	
	array( 'name' => __( 'Add Listing Form Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_add_listing_gen_settings' ),
	
	array(  
			'name' => __( 'Enable "Accept Terms and Conditions"', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the "Accept Terms and Conditions" field on add listing.', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_accept_term_condition',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Terms and Conditions Content', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_term_condition_content',
			'type' 		=> 'textarea',
			'css' 		=> 'width:500px; height: 150px;',
			'std' 		=> __('Please accept <a href="" target="_blank">terms and conditions</a>', GEODIRECTORY_TEXTDOMAIN )
		),
		
	array(  
	'name' => __( 'Show description field as editor', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Select post types to show advanced editor on add listing page.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_tiny_editor_on_add_listing',
		'css' 		=> 'min-width:300px;',
		'std' 		=> array(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_add_listing_gen_settings'),
	/* Listing Layout Settings end */
	
	
	/* Search Layout Settings end */
	
	array( 'name' => __( 'Search', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_search_settings ' ),

	
	array( 	'name' => __( 'Search Page Layout Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_search_layout' ),
		
		array(  
			'name' => __( 'Search top section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the top section of search page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_search_top_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Search right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the right section of search page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_search_right_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of search right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of right section of search page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_search_right_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		
		array(  
			'name' => __( 'Search content section view', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the listing view of search page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_search_view',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'gridview_onehalf',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array( 
				'gridview_onehalf' => __( 'Grid View (Two Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onethird' => __( 'Grid View (Three Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefourth' => __( 'Grid View (Four Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefifth' => __( 'Grid View (Five Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'listview' => __( 'List view', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
		
		array(  
			'name' => __( 'Width of search content section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of content section of search page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_search_contant_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '63' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Search left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the left section of search page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_search_left_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of search left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of left section of search in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_search_left_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Search bottom section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the bottom section of search page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_search_bottom_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array( 'type' => 'sectionend', 'id' => 'geodir_search_layout'),
				
				
		array( 	'name' => __( 'Search form default text settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_search_form_default_text_settings' ),
		
		array(  
			'name' => __( 'Search field default value', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the search text box \'placeholder\' value on search form.', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_search_field_default_text',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'Search for' // show on the listing page.
			),
		
		array(  
			'name' => __( 'Near field default value', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the near text box \'placeholder\' value on search form.', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_near_field_default_text',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'Near' // show on the listing page.
		),
		
		array(  
			'name' => __( 'Search button label', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the search button label on search form.', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_search_button_label',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'Search' // show on the listing page.
		),
		
		array( 'type' => 'sectionend', 'id' => 'geodir_search_form_default_text_settings'),
			
	/* Listing Layout Settings end */
	
	
	
	/* Detail Layout Settings end */
	
	array( 'name' => __( 'Detail', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_detail_settings ' ),
	
	array( 'name' => __( 'Detail/Single Page Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'detail_page_settings ' ),
	
	array(  
			'name' => __( 'Detail top section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the top section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_detail_top_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
	array(  
			'name' => __( 'Detail bottom section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the bottom section of listing page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_detail_bottom_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
	array(  
		'name' => __( 'Detail sidebar section on left side', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Display detail sidebar section on left side of the detail page', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_detail_sidebar_left_section',
		'type' 		=> 'checkbox',
		'std' 		=> '0'
	),
	array(  
		'name'	=> __( 'Disable GD modal', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable GD modal that displays slideshow images in popup', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_gb_modal',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable Tweet, Fb Like, Google+ buttons section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable Tweet, Fb Like, Google+ buttons section that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_tfg_buttons_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable share this button section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable share this button section that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_sharethis_button_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable Google Analytics section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable Google Analytics section that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_google_analytics_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable User Links section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable User Links section (Edit post, Favorite etc..) that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_user_links_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable Rating Info section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable Rating Info section that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_rating_info_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	array(
		'name'	=> __( 'Disable Listing Info section', GEODIRECTORY_TEXTDOMAIN ),
		'desc'	=> __( 'Disable Listing Info section that displays on Detail page sidebar', GEODIRECTORY_TEXTDOMAIN ),
		'id'	=> 'geodir_disable_listing_info_section',
		'type'	=> 'checkbox',
		'std'	=> '0'
	),
	
	array( 'type' => 'sectionend', 'id' => 'detail_page_settings'),
	
	
	
	/* ---------- DETAIL PAGE TAB SETTING START*/
	
	array( 'name' => __( 'Detail Page Tab Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_detail_page_tab_settings ' ),
	
	array(
		'name' => __( 'Exclude selected tabs from detail page', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Select tabs to exclude from the list of all appearing tabs on detail page.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_detail_page_tabs_excluded',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select tabs', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_detail_page_tabs_key_value_array())
	),
	
	
	array( 'type' => 'sectionend', 'id' => 'geodir_detail_page_tab_settings'),
	/* ---------- DETAIL PAGE TAB SETTING END*/
	
	/* START DEFAULT STAR IMAGE*/
	array( 'name' => __( 'Default Rating Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_rating_settings ' ),
		
	array(  
			'name' => __( 'Upload default rating star icon', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_default_rating_star_icon',
			'type' 		=> 'file',
			'std' 		=> '0',
			'value' =>  geodir_plugin_url().'/geodirectory-assets/images/stars.png'// Default value to show home top section
		),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_detail_page_tab_settings'),
	
	/* END DEFAULT STAR IMAGE*/
	
	/* Detail related post settings start */
		
		array( 'name' => __( 'Related Post Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'detail_page_related_post_settings ' ),
	
	array(
		'name' => __( 'Show related post listing on', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Select the post types to display related listing on detail page.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_add_related_listing_posttypes',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),

	array(
			'name' => __( 'Relate to', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the relation between current post to related posts.', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_related_post_relate_to',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'category',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array(
				'category' => __( 'Categories', GEODIRECTORY_TEXTDOMAIN ),
				'tags' => __( 'Tags', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
		
		array(
			'name' => __( 'Layout', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the listing view of relate post on detail page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_related_post_listing_view',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'gridview_onehalf',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array( 
				'gridview_onehalf' => __( 'Grid View (Two Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onethird' => __( 'Grid View (Three Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefourth' => __( 'Grid View (Four Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefifth' => __( 'Grid View (Five Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'listview' => __( 'List view', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
	
	array(
			'name' => __( 'Sort by', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the related post listing sort by view', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_related_post_sortby',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'latest',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array( 
				'latest' => __( 'Latest', GEODIRECTORY_TEXTDOMAIN ),
				'featured' => __( 'Featured', GEODIRECTORY_TEXTDOMAIN ),
				'high_review' => __( 'Review', GEODIRECTORY_TEXTDOMAIN ),
				'high_rating' => __( 'Rating', GEODIRECTORY_TEXTDOMAIN ),
				'random' => __( 'Random', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
		
		array(  
			'name' => __( 'Number of posts:', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter number of posts to display on related posts listing', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_related_post_count',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '5' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Post excerpt', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Post content excerpt character count', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_related_post_excerpt',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '20' // Default value to show home top section
		),
	
	
	array( 'type' => 'sectionend', 'id' => 'detail_page_related_post_settings'),
	/* Detail Layout Settings end */
	
	/* Author Layout Settings Start */
	
	array( 'name' => __( 'Author', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_author_settings ' ),

	
	array( 	'name' => __( 'Author Page Layout Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_author_layout' ),
		
		array(  
			'name' => __( 'Author top section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the top section of author page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_author_top_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Author right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the right section of author page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_author_right_section',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of author right section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of right section of author page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_author_right_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Author content section view', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Set the listing view of author page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_author_view',
			'css' 		=> 'min-width:300px;',
			'std' 		=> 'gridview_onehalf',
			'type' 		=> 'select',
			'class'		=> 'chosen_select',
			'options' => array_unique( array( 
				'gridview_onehalf' => __( 'Grid View (Two Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onethird' => __( 'Grid View (Three Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefourth' => __( 'Grid View (Four Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'gridview_onefifth' => __( 'Grid View (Five Columns)', GEODIRECTORY_TEXTDOMAIN ),
				'listview' => __( 'List view', GEODIRECTORY_TEXTDOMAIN ),
				))
		),
		
		array(  
			'name' => __( 'Width of author content section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of content section of author page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_author_contant_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '63' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Author left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the left section of author page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_author_left_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Width of author left section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Enter the width of left section of home page in %', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_width_author_left_section',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '30' // Default value to show home top section
		),
		
		array(  
			'name' => __( 'Author bottom section', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Show the bottom section of author page', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_show_author_bottom_section',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
		
		
		array(  
			'name' => __( 'Description word limit', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_author_desc_word_limit',
			'type' 		=> 'text',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '50' // Default value to show home top section
		),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_author_layout'),	
	/* Author Layout Settings end */
	
	
	
	/* Post Type Navigation Settings Start */
	array( 'name' => __( 'Navigation', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_navigation_settings' ),
	
	
	/* Post Type Navigation Settings Start */
	
	array( 	'name' => __( 'Navigation Locations', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_navigation_locations' ),
	
	array(  
		'name' => __( 'Show geodirectory navigation in selected menu locations', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_theme_location_nav',
		'css' 		=> 'min-width:300px;',
		'std' 		=> array(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select menu locations', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_theme_location_setting_fun())
	),	
	array( 'type' => 'sectionend', 'id' => 'geodir_navigation_options'),	
	
	
	
	array( 	'name' => __( 'Navigation Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_navigation_options' ),
	
	
	
	array(  
		'name' => __( 'Show add listing navigation in menu', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> sprintf(__( 'Show add listing navigation in main menu? (untick to disable) If you disable this option, none of the add listing link will appear in main navigation.', GEODIRECTORY_TEXTDOMAIN )),
		'id' 		=> 'geodir_show_addlisting_nav',
		'std' 		=> '1',
		'type' 		=> 'checkbox'
	),
	
	array(  
		'name' => __( 'Show listings navigation in menu', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> sprintf(__( 'Show listing navigation in main menu? (untick to disable) If you disable this option, none of the listing link will appear in main navigation.', GEODIRECTORY_TEXTDOMAIN )),
		'id' 		=> 'geodir_show_listing_nav',
		'std' 		=> '1',
		'type' 		=> 'checkbox'
	),
		
	array( 'type' => 'sectionend', 'id' => 'geodir_navigation_options'),			
	
	
	
	array( 	'name' => __( 'Post Type Navigation Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_post_type_navigation_layout' ),
	array(  
		'name' => __( 'Show listing link in main navigation', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_add_posttype_in_main_nav',
		'css' 		=> 'min-width:300px;',
		'std' 		=> array(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Show listing link in listing navigation', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_add_posttype_in_listing_nav',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Allow post type to add from frontend', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_allow_posttype_frontend',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Show add listing link in main navigation', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_add_listing_link_main_nav',
		'css' 		=> 'min-width:300px;',
		'std' 		=> array(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Show add listing link in add listing navigation', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_add_listing_link_add_listing_nav',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_post_type_navigation_layout'),
	
	
	array( 'name' => __( 'User Dashboard Post Type Navigation Settings', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'desc' => '', 'id' => 'geodir_user_dashboard_post_type ' ),
	
	
	array(  
		'name' => __( 'Show add listing link in user dashboard', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'tip' 		=> '',
		'id' 		=> 'geodir_add_listing_link_user_dashboard',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Show favorite link in user dashboard', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Option will not appear if user does not have a favorite of that post type', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_favorite_link_user_dashboard',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array(  
		'name' => __( 'Show listing link in user dashboard', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Option will not appear if user does not have his/her own listing of that post type', GEODIRECTORY_TEXTDOMAIN ),
		'tip' 		=> '',
		'id' 		=> 'geodir_listing_link_user_dashboard',
		'css' 		=> 'min-width:300px;',
		'std' 		=> geodir_get_posttypes(),
		'type' 		=> 'multiselect',
		'placeholder_text' => __( 'Select post types', GEODIRECTORY_TEXTDOMAIN ),
		'class'		=> 'chosen_select',
		'options' => array_unique( geodir_post_type_setting_fun())
	),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_user_dashboard_post_type'),
	/* Post Type Navigation Settings End */
	
	/* Script Settings Start */
	array( 'name' => __( 'Scripts', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_script_settings ' ),
	
	/*
	array( 	'name' => __( 'Add/Remove Scripts', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_script_enqueue_settings' ),
	
	array(  
			'name' => __( 'Google Api script', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Include Google Api script', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_enqueue_google_api_script',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value for the page title - changed in settings
		),
	
	array(  
			'name' => __( 'Flexslider script', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'include flexslider script', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_enqueue_flexslider_script',
			'type' 		=> 'checkbox',
			'std' 		=> '1' // Default value for the page title - changed in settings
		),
	
		array( 'type' => 'sectionend', 'id' => 'geodir_script_enqueue_settings'),
		
	*/		
	array( 	'name' => __( 'Script Settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_script_settings' ),
	
		array(  
			'name' => __( 'Custom style css code', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_coustem_css',
			'type' 		=> 'textarea',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '' // Default value for the page title - changed in settings
		),
		
		array(  
			'name' => __( 'Header script code', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_header_scripts',
			'type' 		=> 'textarea',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '' // Default value for the page title - changed in settings
		),
		
		array(  
			'name' => __( 'Footer script code', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_footer_scripts',
			'type' 		=> 'textarea',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '' // Default value for the page title - changed in settings
		),	
	
	array( 'type' => 'sectionend', 'id' => 'geodir_script_settings'),
	/* Script Settings End */			
	
	/* Map Settings Start */
	array( 'name' => __( 'Map', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'geodir_map_settings ' ),
	
	/* Untick the category by default on home map */
	array(
		'name' => __( 'Home Map Settings', GEODIRECTORY_TEXTDOMAIN ),
		'type' => 'sectionstart',
		'desc' => '', 
		'id' => 'geodir_home_map_section'
	),
	array(
		'name' => __( 'Select category to untick by default on map', GEODIRECTORY_TEXTDOMAIN ),
		'desc' => __( 'Select category to untick by default on the home map.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' => '',
		'id' => 'geodir_home_map_untick',
		'css' => 'min-width:300px;',
		'std' => '',
		'type' => 'multiselect',
		'placeholder_text' => __( 'Select category', GEODIRECTORY_TEXTDOMAIN ),
		'class'	=> 'chosen_select',
		'options' => geodir_home_map_cats_key_value_array()
	),
	array(
		'type' => 'sectionend',
		'id' => 'geodir_home_map_section'
	),	
	
	array(
		'name' => __( 'Add Listing Map Settings', GEODIRECTORY_TEXTDOMAIN ),
		'type' => 'sectionstart',
		'desc' => '', 
		'id' => 'geodir_add_listing_map_section'
	),
	array(  
			'name' => __( 'Disable mouse scroll', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'Stops the mouse scroll zooming the map', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_add_listing_mouse_scroll',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
	array(
		'type' => 'sectionend',
		'id' => 'geodir_add_listing_map_section'
	),
	
	
	array( 	'name' => __( 'Default map settings', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_map_default_settings' ),
	
	array(  
			'name' => '',
			'desc' 		=> '',
			'id' 		=> 'map_default_settings',
			'type' 		=> 'map_default_settings',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '' // Default value for the page title - changed in settings
		),
		
	array(  
			'name' => __( 'Upload map default marker icon', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_default_marker_icon',
			'type' 		=> 'file',
			'std' 		=> '0',
			'value' =>  geodir_plugin_url().'/geodirectory-functions/map-functions/icons/pin.png'// Default value to show home top section
		),
		// add option that allows enable/disable map dragging to phone devices
		array(  
			'name' => __( 'Show button control on map to enable/disable dragging', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> __( 'If checked, it displays button control to enable/disable dragging on google maps for phone devices', GEODIRECTORY_TEXTDOMAIN ),
			'id' 		=> 'geodir_map_onoff_dragging',
			'type' 		=> 'checkbox',
			'std' 		=> '0' // Default value to show home top section
		),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_map_default_settings'),			
	
	array( 	'name' => __( 'Show / hide post type and category on map', GEODIRECTORY_TEXTDOMAIN ), 
				'type' => 'sectionstart',
				'desc' => '', 
				'id' => 'geodir_map_settings' ),
	
		array(  
			'name' => __( 'Select Map Category', GEODIRECTORY_TEXTDOMAIN ),
			'desc' 		=> '',
			'id' 		=> 'geodir_map_settings',
			'type' 		=> 'map',
			'css' 		=> 'min-width:300px;',
			'std' 		=> '' // Default value for the page title - changed in settings
		),
	
	array( 'type' => 'sectionend', 'id' => 'geodir_map_settings'),
	/* Map Settings End */	
	
)); // End Design settings



//$_SESSION['geodir_settings']['design_settings'] = $geodir_settings['design_settings'] ;