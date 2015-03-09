<?php
function geodir_locate_template($template = '')
{
	global $post_type,$wp,$post;
	$fields = array();
	
	switch($template):
		case 'signup':
				return $template = locate_template( array( "geodirectory/geodir-signup.php" ));
		break;
		case 'add-listing':

				if(is_page() && isset($post->post_content) && has_shortcode( $post->post_content, 'add_listing' ) ) {$listing_page_id =$post->ID;}
				else{$listing_page_id = get_option( 'geodir_add_listing_page' );}

				if( $listing_page_id != '' && is_page( $listing_page_id ) && isset($_REQUEST['listing_type'])
					&& in_array( $_REQUEST['listing_type'], geodir_get_posttypes() ) )
						$post_type = $_REQUEST['listing_type'];
				if(empty($post_type) && !isset($_REQUEST['pid']))
				{
					$pagename = $wp->query_vars['pagename'] ;
					$post_types = geodir_get_posttypes();
					if(!empty($post_types))
						$post_type 	= $post_types[0] ;
					wp_redirect(home_url().'/'.$pagename .'/?listing_type='.$post_type);
					exit();		
				}
				return $template = locate_template( array( "geodirectory/add-{$post_type}.php","geodirectory/add-listing.php" ));
		break;
		case 'success':
				$success_page_id = get_option( 'geodir_success_page' );
				if( $success_page_id != '' && is_page( $success_page_id )  && isset($_REQUEST['listing_type'])
					&& in_array( $_REQUEST['listing_type'], geodir_get_posttypes() ) )
					$post_type = $_REQUEST['listing_type'];
				return $template = locate_template(array("geodirectory/{$post_type}-success.php","geodirectory/listing-success.php"));
		break;  
		case 'detail':
		case 'preview':
				if ( in_array( get_post_type(), geodir_get_posttypes() ) )
					$post_type = get_post_type();
				return $template = locate_template( array("geodirectory/single-{$post_type}.php","geodirectory/listing-detail.php"));
		break;
		case 'listing':
				$templates = array();
				if ( is_post_type_archive() && in_array( get_post_type(), geodir_get_posttypes() ) )
				{	$post_type = get_post_type(); $templates[] =  "geodirectory/archive-$post_type.php"; 	}
				
				
				if(is_tax() && geodir_get_taxonomy_posttype() )
				{
					$query_obj = get_queried_object();
					$curr_taxonomy = isset($query_obj->taxonomy) ? $query_obj->taxonomy : '';
					$curr_term = isset($query_obj->slug) ? $query_obj->slug : '';
					$templates[] =  "geodirectory/taxonomy-$curr_taxonomy-$curr_term.php";
					$templates[] =  "geodirectory/taxonomy-$curr_taxonomy.php";
				}
				
				$templates[] =  "geodirectory/geodir-listing.php";	
				
				return $template = locate_template( $templates );
		break;
		case 'information':
				return $template = locate_template( array( "geodirectory/geodir-information.php" ) );
		break;
		case 'author':
				return $template = locate_template( array( "geodirectory/geodir-author.php" ) );
		break;
		case 'search':
				return $template = locate_template( array( "geodirectory/geodir-search.php" ) );
		break;
		case 'location':
				return $template = locate_template( array( "geodirectory/geodir-location.php" ) );
		break;
		case 'geodir-home':
				return $template = locate_template( array( "geodirectory/geodir-home.php" ) );
		break;
		case 'listing-listview':
			$template = locate_template( array( "geodirectory/listing-listview.php" ) );
			if(!$template){ $template = geodir_plugin_path() . '/geodirectory-templates/listing-listview.php';}
			return $template;
		break;
		case 'widget-listing-listview':
			$template = locate_template( array( "geodirectory/widget-listing-listview.php" ) );
			if(!$template){ $template =  geodir_plugin_path() . '/geodirectory-templates/widget-listing-listview.php';}
			return $template;
		break;
	endswitch;
	
	return false;	
	
}

function geodir_template_loader( $template ) {

	global $wp_query;
	$geodir_custom_page_list=apply_filters('geodir_set_custom_pages' , array(
										'geodir_signup_page'=> 
										apply_filters( 'geodir_set_custom_signup_page',false) ,
									 	'geodir_add_listing_page'=> 
										apply_filters( 'geodir_set_custom_add_listing_page',false) ,
									 	'geodir_preview_page'=> 
										apply_filters( 'geodir_set_custom_preview_page',false) ,
									 	'geodir_listing_success_page'=> 
										apply_filters( 'geodir_set_custom_listing_success_page',false),
									 	'geodir_listing_detail_page'=> 
										apply_filters( 'geodir_set_custom_listing_detail_page',false),
									 	'geodir_listing_page'=> 
										apply_filters( 'geodir_set_custom_listing_page',false),
										'geodir_search_page'=> 
										apply_filters( 'geodir_set_custom_search_page',false),
										'geodir_author_page'=> 
										apply_filters( 'geodir_set_custom_author_page',false),
									 	'geodir_home_map_page' =>
										apply_filters( 'geodir_set_custom_home_map_page',false)
									) );
			 
	
	if(isset($_REQUEST['geodir_signup']) || $geodir_custom_page_list['geodir_signup_page'] ){
		
		$template = geodir_locate_template('signup');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-signup.php';
		
		return $template = apply_filters('geodir_template_signup',$template);
	}
	
	if( geodir_is_page('add-listing')  || $geodir_custom_page_list['geodir_add_listing_page'] )
	{
		if(!geodir_is_default_location_set())
		{
			global $information;
			$information = sprintf( __( 'Please %sclick here%s to set a default location, this will make the plugin work properly.', GEODIRECTORY_TEXTDOMAIN ) , '<a href=\'' .admin_url('admin.php?page=geodirectory&tab=default_location_settings').'\'>' , '</a>');
			
			$template = geodir_locate_template('information');
		
			if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-information.php';
		
			return $template = apply_filters('geodir_template_information',$template);
		}
		// check if pid exists in the record if yes then check if this post belongs to the user who is logged in.
		if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
		{
			 global $information;
			 $information =  __( 'This listing does not belong to your account, please check the listing id carefully.', GEODIRECTORY_TEXTDOMAIN );
			 $is_current_user_owner = geodir_listing_belong_to_current_user();
			 if(!$is_current_user_owner)
			 {
			 	$template = geodir_locate_template('information');
		
				if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-information.php';
		
				return $template = apply_filters('geodir_template_information',$template);
			 }
			 
			
		} 
		
		//geodir_is_login(true);
		global $current_user; 
		if(!$current_user->ID){
			wp_redirect( home_url().'?geodir_signup=true&redirect_add_listing='.urlencode(geodir_curPageURL()), 302 );exit;
		}
		
	 	$template = geodir_locate_template('add-listing');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/add-listing.php';
	
		return $template = apply_filters('geodir_template_add_listing',$template);
	}
	
	
	if ( geodir_is_page('preview') || $geodir_custom_page_list['geodir_preview_page'] ) {
		global $preview; $preview = true;
		
		$template = geodir_locate_template('preview');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/listing-detail.php';
		return $template = apply_filters('geodir_template_preview',$template);
		
	}
	
	
	if( geodir_is_page('listing-success') || $geodir_custom_page_list['geodir_listing_success_page'] ){
		
		$template = geodir_locate_template('success');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/listing-success.php';
		
		return $template = apply_filters('geodir_template_success',$template);
		
	}
	
	if ( geodir_is_page('detail') || $geodir_custom_page_list['geodir_listing_detail_page'] ) {
		
		$template = geodir_locate_template('detail');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/listing-detail.php';
		
		return $template = apply_filters('geodir_template_detail',$template);
		
	}
	
	if ( geodir_is_page('listing') || $geodir_custom_page_list['geodir_listing_page'] ) {
		
		$template = geodir_locate_template('listing');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-listing.php';
		
		return $template = apply_filters('geodir_template_listing',$template);
		
	}
	
	if ( geodir_is_page('search') || $geodir_custom_page_list['geodir_search_page'] ) {
		
		$template = geodir_locate_template('search');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-search.php';
		
		return $template = apply_filters('geodir_template_search',$template);
		
	}
	
	if( geodir_is_page('author') || $geodir_custom_page_list['geodir_author_page'] ){
		
		$template = geodir_locate_template('author');
		
		if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-author.php';
		
		return $template = apply_filters('geodir_template_author',$template);
		
	}
	
	if( get_option('geodir_set_as_home') || geodir_is_page('location') || $geodir_custom_page_list['geodir_home_map_page'] ){
		
		global $post,$wp_query;

		if( ( 'page' == get_option( 'show_on_front') && $post->ID == get_option( 'page_on_front' ) ) 
			|| ( is_home() && !$wp_query->is_posts_page  || $geodir_custom_page_list['geodir_home_map_page']) 
			){
			
			$template = geodir_locate_template('geodir-home');
			
			if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-home.php';
	
			return $template = apply_filters('geodir_template_homepage',$template);
	
		}elseif( geodir_is_page('location') ){
		
			$template = geodir_locate_template('location');
			
			if ( ! $template ) $template = geodir_plugin_path() . '/geodirectory-templates/geodir-location.php';
			
			return $template = apply_filters('geodir_template_location',$template);
			
		}else
			return $template;
	
	}
	
	return $template;
}

function geodir_get_template_part( $slug = '', $name = NULL )
{
	global $geodirectory, $post;
	do_action( "geodir_get_template_part_{$slug}", $slug, $name );
	$templates = array();
	$name = (string) $name;
	if ( '' !== $name ){
		$template_name = "{$slug}-{$name}.php";
		
	}else{
		$template_name = "{$slug}.php";
	}		
	
	if ( !locate_template(array("geodirectory/" .$template_name)) ) :
		
		$template = apply_filters( "geodir_template_part-{$slug}-{$name}", geodir_plugin_path() . '/geodirectory-templates/' . $template_name );
		include( $template );
	else:
		locate_template(array("geodirectory/" .$template_name), true, false);
	endif;
	
}

function geodir_core_post_view_extra_class( $class , $all_postypes='' ) {
	global $post;
	
	if(!$all_postypes){$all_postypes = geodir_get_posttypes();}
	
	$gdp_post_id = !empty( $post ) && isset( $post->ID ) ? $post->ID : NULL;
	$gdp_post_type = $gdp_post_id > 0 && isset( $post->post_type ) ? $post->post_type : NULL;
	$gdp_post_type = $gdp_post_type != '' && !empty( $all_postypes ) && in_array( $gdp_post_type, $all_postypes ) ? $gdp_post_type : NULL;
		
	if ( $gdp_post_id && $gdp_post_type ) {
		$append_class = 'gd-post-' . $gdp_post_type;
		$append_class .= isset( $post->is_featured ) && $post->is_featured > 0 ? ' gd-post-featured' : '';
		$class = $class != '' ? $class . ' ' . $append_class : $append_class;
	}
	
	return $class;
}
