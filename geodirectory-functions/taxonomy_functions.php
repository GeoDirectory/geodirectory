<?php    
/**
 * Geodirectory Custom Post Types/Taxonomies
 * 
 * Inits custom post types and taxonomies
 *
 * @package		GeoDirectory
 * @category	Core
 * @author		WPGeoDirectory
 */

include_once('custom_taxonomy_hooks_actions.php');

/**
 * Add Custom Menu Items
 **/
 
function geodir_add_nav_menu_items(  ) {
	$items = '';
	// apply filter to add more navigations // -Filter-Location-Manager
	
	if(get_option('geodir_show_listing_nav')){
	
	$menu_class = '';
	if(geodir_is_page('listing'))
			$menu_class = 'current-menu-item';
	
	
	//SHOW LISTING OF POST TYPE IN MAIN NAVIGATION		
	$post_types = geodir_get_posttypes('object');
	$show_post_type_main_nav = get_option('geodir_add_posttype_in_main_nav');
	if(!empty($post_types)){
		foreach($post_types as $post_type => $args){
			if(!empty($show_post_type_main_nav)){
				if ( in_array($post_type, $show_post_type_main_nav)) {
				if(get_post_type_archive_link( $post_type )){
					$menu_class = '';
					if(geodir_get_current_posttype() == $post_type && geodir_is_page('listing'))
					$menu_class = 'current-menu-item';
					$li_class = apply_filters('geodir_menu_li_class','menu-item '.$menu_class);
					$a_class = apply_filters('geodir_menu_a_class','');
					$items .=	'<li class="'.$li_class.'">
									<a href="'. get_post_type_archive_link( $post_type ) .'" class="'.$a_class.'">
										'.__(ucfirst($args->labels->name)).'
									</a>
								</li>';
				}
			}
			}			
		}	
	}
	//END LISTING OF POST TYPE IN MAIN NAVIGATION
	
	$view_posttype_listing = get_option('geodir_add_posttype_in_listing_nav') ;
	$is_listing_sub_meny_exists = (!empty($view_posttype_listing)) ? true : false ;	
	if($is_listing_sub_meny_exists)
	{	$li_class = apply_filters('geodir_menu_li_class','menu-item '.$menu_class);
		$sub_li_class = apply_filters('geodir_sub_menu_li_class','menu-item '.$menu_class);
		$sub_ul_class = apply_filters('geodir_sub_menu_ul_class' ,'sub-menu' );
		$a_class = apply_filters('geodir_menu_a_class','');
		$sub_a_class = apply_filters('geodir_sub_menu_a_class','');
		$items .= '<li class="'.$li_class.'">
					<a href="#" class="'.$a_class.'">'.__('Listing',GEODIRECTORY_TEXTDOMAIN).'</a>
					<ul class="'.$sub_ul_class .'">';
						$post_types = geodir_get_posttypes('object');
						
						$show_listing_post_types = get_option('geodir_add_posttype_in_listing_nav');
						
						if(!empty($post_types)){
							global $geodir_add_location_url;
							$geodir_add_location_url = true;
							foreach($post_types as $post_type => $args){
								if(!empty($show_listing_post_types)){
									if ( in_array($post_type, $show_listing_post_types)) {
										if(get_post_type_archive_link( $post_type )){
										
												$menu_class = '';
												if(geodir_get_current_posttype() == $post_type && geodir_is_page('listing'))
												$menu_class = 'current-menu-item';
											
												$items .=	'<li class="'.$sub_li_class.'">
														<a href="'. get_post_type_archive_link( $post_type ) .'" class="'.$sub_a_class.'">
															'.__(ucfirst($args->labels->name)).'
														</a>
													</li>';
										}
									}
								}			
							}	
							$geodir_add_location_url = NULL;
						}
						
					$items .= '	</ul>
				</li>';
		}
	}
	
	if(get_option('geodir_show_addlisting_nav')){
		
		$menu_class = '';
		if(geodir_is_page('add-listing'))
				$menu_class = 'current-menu-item';
	
		//SHOW ADD LISTING POST TYPE IN MAIN NAVIGATION 
		$post_types = geodir_get_posttypes('object');
		$show_add_listing_post_types_main_nav = get_option('geodir_add_listing_link_main_nav');
		$geodir_allow_posttype_frontend = get_option('geodir_allow_posttype_frontend');
		
		if(!empty($post_types)){
			foreach($post_types as $post_type => $args){
				if(!empty($geodir_allow_posttype_frontend)){
					if ( in_array($post_type, $geodir_allow_posttype_frontend)) {	
						if(!empty($show_add_listing_post_types_main_nav)){
							if ( in_array($post_type, $show_add_listing_post_types_main_nav)) {	
								if(geodir_get_addlisting_link( $post_type )){
								
										$menu_class = '';
										if(geodir_get_current_posttype() == $post_type && geodir_is_page('add-listing'))
										$menu_class = 'current-menu-item';
										$li_class = apply_filters('geodir_menu_li_class','menu-item '.$menu_class);
										$a_class = apply_filters('geodir_menu_a_class','');
										$items .=	'<li class="'.$li_class .'">
											<a href="'. geodir_get_addlisting_link( $post_type ) .'" class="'.$a_class.'">
												'.__('Add',GEODIRECTORY_TEXTDOMAIN).' '.__( $args->labels->singular_name, GEODIRECTORY_TEXTDOMAIN ).'
											</a>
										</li>';
								}
							}
						}
					}
				}			
			}
		}
		//END SHOW ADD LISTING POST TYPE IN MAIN NAVIGATION 
	}
	
	$view_add_posttype_listing = get_option('geodir_add_listing_link_add_listing_nav') ;
	$is_add_listing_sub_meny_exists = (!empty($view_add_posttype_listing)) ? true : false ;	
	if($is_add_listing_sub_meny_exists)
	{
	
		if(get_option('geodir_show_addlisting_nav')){		
		$li_class = apply_filters('geodir_menu_li_class','menu-item '.$menu_class);
		$sub_li_class = apply_filters('geodir_sub_menu_li_class','menu-item '.$menu_class);
		$sub_ul_class = apply_filters('geodir_sub_menu_ul_class' ,'sub-menu' );
		$a_class = apply_filters('geodir_menu_a_class','');
		$sub_a_class = apply_filters('geodir_sub_menu_a_class','');
		$items .= '<li  class="'.$li_class.'">
					<a href="#" class="'.$a_class.'">'.__('Add Listing',GEODIRECTORY_TEXTDOMAIN).'</a>
					<ul class="'.$sub_ul_class .'">';
					
						$post_types = geodir_get_posttypes('object');
						
						$show_add_listing_post_types = get_option('geodir_add_listing_link_add_listing_nav');
						
						if(!empty($post_types)){
							foreach($post_types as $post_type => $args){
								if(!empty($geodir_allow_posttype_frontend)){
									if ( in_array($post_type, $geodir_allow_posttype_frontend)) {
										if(!empty($show_add_listing_post_types)){
											if ( in_array($post_type, $show_add_listing_post_types)) {	
												if(geodir_get_addlisting_link( $post_type )){
												
													$menu_class = '';
													if(geodir_get_current_posttype() == $post_type && geodir_is_page('add-listing'))
													$menu_class = 'current-menu-item';
												$li_class = apply_filters('geodir_menu_li_class','menu-item '.$menu_class);
													$items .=	'<li class="'.$li_class.'">
														<a href="'. geodir_get_addlisting_link( $post_type ) .'" class="'.$sub_a_class.'">
															'.__('Add',GEODIRECTORY_TEXTDOMAIN).' ' . __( $args->labels->singular_name, GEODIRECTORY_TEXTDOMAIN ) . '
														</a>
													</li>';
												}
											}
										}
									}	
								}			
							}
						}
						
					$items .= '	</ul>
				</li>';
		}
	}
	// apply filter to add more navigations // -Filter-Location-Manager
	return $items;
}


function geodir_pagemenu_items($menu, $args){
	$locations = get_nav_menu_locations();
	$geodir_theme_location = get_option('geodir_theme_location_nav');
	$geodir_theme_location_nav = array();
	if ( empty( $locations) &&  empty($geodir_theme_location))
	{
		$menu = str_replace("</ul></div>",geodir_add_nav_menu_items()."</ul></div>",$menu);
		$geodir_theme_location_nav[] = $args['theme_location'] ;
		update_option('geodir_theme_location_nav' , $geodir_theme_location_nav);
	}
	//else if(empty($geodir_theme_location)) // It means 'Show geodirectory navigation in selected menu locations' is not set yet.
//		$menu = str_replace("</ul></div>",geodir_add_nav_menu_items()."</ul></div>",$menu);
	else  if (  is_array($geodir_theme_location) && in_array($args['theme_location'],$geodir_theme_location) )
		$menu = str_replace("</ul></div>",geodir_add_nav_menu_items()."</ul></div>",$menu);
	
	return $menu;
	
}


function geodir_menu_items($items, $args){

		$location = $args->theme_location;
		
		$geodir_theme_location = get_option('geodir_theme_location_nav');
		
		if ( has_nav_menu( $location )=='1' && is_array($geodir_theme_location) && in_array($location,$geodir_theme_location) ) {
			
			$items = $items.geodir_add_nav_menu_items();	
			return $items;
			
		}
		else
		{
			return $items;
		}
}

/**
 * Get array of all categories
 **/
function geodir_get_category_all_array()
{
	global $wpdb;
	$return_array = array();
	
	$taxonomies = geodir_get_taxonomies(); 
	$taxonomies = implode("','",$taxonomies);
	$taxonomies = "'".$taxonomies."'";
	
	$pn_categories =	$wpdb->get_results(
											$wpdb->prepare(
												"SELECT $wpdb->terms.name as name, $wpdb->term_taxonomy.count as count, $wpdb->terms.term_id as cat_ID FROM $wpdb->term_taxonomy,  $wpdb->terms WHERE $wpdb->term_taxonomy.term_id = %d AND $wpdb->term_taxonomy.taxonomy in ( $taxonomies ) ORDER BY name",
												array($wpdb->terms.term_id)
											)
										);
	
	foreach($pn_categories as $pn_categories_obj)
	{
		$return_array[] = array("id" => $pn_categories_obj->cat_ID,
							   "title"=> $pn_categories_obj->name,);
	}
	return $return_array;
}


/**
 * Get Current Post Type
 */		 
function geodir_get_current_posttype(){
	
	global $wp_query,$geodir_post_type;
	
	$geodir_post_type = get_query_var('post_type');	
		
	if(geodir_is_page('add-listing') || geodir_is_page('preview')){	
		if( isset($_REQUEST['pid']) && $_REQUEST['pid'] != '' )
			$geodir_post_type = get_post_type($_REQUEST['pid']);
		elseif( isset($_REQUEST['listing_type']) )	
			$geodir_post_type = $_REQUEST['listing_type'];
	
	}
	
	if( (geodir_is_page('search') || geodir_is_page('author')) && isset($_REQUEST['stype']) )
		$geodir_post_type = $_REQUEST['stype'];
	
	if(is_tax())
		$geodir_post_type = geodir_get_taxonomy_posttype();
	
	
	$all_postypes = geodir_get_posttypes();
	$all_postypes = stripslashes_deep($all_postypes);
	
	if(is_array($all_postypes) && !in_array($geodir_post_type, $all_postypes))
		$geodir_post_type = '';
	
	
	return $geodir_post_type;
}

function geodir_get_posttypes($output = 'names'){
	$post_types = array();
	$post_types = get_option('geodir_post_types');
	$post_types = stripslashes_deep($post_types);
	if(!empty($post_types)){	
		switch($output):
			case 'object':
			case 'Object':
				$post_types = json_decode(json_encode($post_types), FALSE);//(object)$post_types;			
			break;
			case 'array':
			case 'Array':
				$post_types = (array)$post_types;	
			break;
			default:
				$post_types = array_keys($post_types);			
			break;
		endswitch;
	}
		
	if(!empty($post_types))
		return $post_types;
	else
		return array();		
}

/**
 * Get Custom Post Type info
 **/
function geodir_get_posttype_info($post_type = ''){
	$post_types = array();
	$post_types = get_option('geodir_post_types');
	$post_types = stripslashes_deep($post_types);
	if(!empty($post_types) && $post_type != ''){
		return $post_types[$post_type];
	}else
		return false;		
}

/**
 * Get all custom taxonomies
 **/
if(!function_exists('geodir_get_taxonomies')){
	function geodir_get_taxonomies($post_type = '', $tages_taxonomies = false){	
		
		$taxonomies = array();
		$gd_taxonomies = array();
		
		if( $taxonomies = get_option('geodir_taxonomies')){
			
			
			$gd_taxonomies = array_keys($taxonomies);
		
			
			if( $post_type != '' )
				$gd_taxonomies =  array();
			
			$i = 0;
			foreach ( $taxonomies as $taxonomy => $args ){
				
				if($post_type != '' && $args['object_type'] == $post_type)
					$gd_taxonomies[] = $taxonomy;
				
				if( $tages_taxonomies === false && strpos($taxonomy,'_tag') !== false ){
					if(array_search($taxonomy,$gd_taxonomies) !== false)
					unset($gd_taxonomies[array_search($taxonomy,$gd_taxonomies)]);
				}	
				
			}
			
			$gd_taxonomies = array_values($gd_taxonomies);
		}	
	
		$taxonomies = apply_filters('geodir_taxonomy',$gd_taxonomies);
		
		if(!empty($taxonomies)) 
		{
			return $taxonomies;
		}
		else{
		return false;
		}
	}
}


/**
 * Get categories drpdown
 **/
if(!function_exists(' geodir_get_categories_dl')){
	function  geodir_get_categories_dl($post_type = '', $selected = '', $tages_taxonomies = false, $echo = true){
		
		$html = '';
		$taxonomies = geodir_get_taxonomies( $post_type, $tages_taxonomies );
		
		$categories = get_terms($taxonomies);
		
		$html .= '<option value="0">'.__('All',GEODIRECTORY_TEXTDOMAIN).'</option>';
		
		foreach($categories as $category_obj){
			$select_opt = '';
			if($selected == $category_obj->term_id){ $select_opt = 'selected="selected"'; } 
			$html .= '<option '.$select_opt.' value="'.$category_obj->term_id.'">'
					 .ucfirst($category_obj->name).'</option>';
		}
		
		if($echo)
			echo $html;
		else
			return $html;
	}
}	


/**
 * Get post type listing slug
 **/
function geodir_get_listing_slug($object_type = ''){
	
	$listing_slug = '';
	
	$post_types = get_option('geodir_post_types');
	$taxonomies = get_option('geodir_taxonomies');
	
	
	if($object_type != '' ){	
		if( !empty($post_types) && array_key_exists($object_type, $post_types) ){
			
			$object_info = $post_types[$object_type];
			$listing_slug = $object_info['listing_slug'];
		}elseif( !empty($taxonomies) && array_key_exists($object_type, $taxonomies) ){
			$object_info = $taxonomies[$object_type];	 
			$listing_slug = $object_info['listing_slug'];
		}
		
	}
	
	if(!empty($listing_slug))
		return $listing_slug;
	else
		return false;
} 
 
 
/**
 * Get current taxonomies posttypes
 **/
function geodir_get_taxonomy_posttype($taxonomy = ''){ 
	global $wp_query;
	
	$post_type = array();
	$taxonomies = array();
	
	if(!empty($taxonomy)){
		$taxonomies[] = $taxonomy;
	}elseif($wp_query->tax_query->queries){
		$taxonomies = wp_list_pluck( $wp_query->tax_query->queries, 'taxonomy' );
		
	}
	
	if(!empty($taxonomies)){
		foreach ( geodir_get_posttypes() as $pt ) {
			$object_taxonomies = $pt === 'attachment' ? get_taxonomies_for_attachments() : get_object_taxonomies( $pt );
			if ( array_intersect( $taxonomies, $object_taxonomies ) )
				$post_type[] = $pt;
		}
	}	
	
	if(!empty($post_type))
		return $post_type[0];
	else
		return false;	
}

/**
 * Custom taxonomy walker function 
 **/
if (!function_exists('geodir_custom_taxonomy_walker')) {
function geodir_custom_taxonomy_walker($cat_taxonomy, $cat_parent = 0,$hide_empty = false,$pading = 0)
{
	global $cat_display,$post_cat, $exclude_cats;
	
	$search_terms = trim($post_cat,",");
	
	$search_terms = explode(",",$search_terms);
	
	$cat_terms = get_terms($cat_taxonomy, array('parent' => $cat_parent, 'hide_empty' => $hide_empty, 'exclude'=>$exclude_cats));
	
	$display = '';
	$onchange = '';
	$term_check = '';
	$main_list_class = '';
	$out = '';
	//If there are terms, start displaying
	if(count($cat_terms) > 0)
	{
		//Displaying as a list
		$p = $pading * 20;
		$pading++;
		
		
		if( ( !geodir_is_page('listing') ) || ( is_search() && $_REQUEST['search_taxonomy']=='' ) )
		{
			if($cat_parent == 0)
			{	
				$list_class = 'main_list gd-parent-cats-list gd-cats-display-' . $cat_display;
				$main_list_class = 'class="main_list_selecter"';
			}	
			else
			{	
				//$display = 'display:none';
				$list_class = 'sub_list gd-sub-cats-list';
			}
		}					
		
		if($cat_display == 'checkbox' || $cat_display == 'radio') {
			$p = 0;
			$out = '<div class="'.$list_class.' gd-cat-row-' . $cat_parent . '" style="margin-left:'.$p.'px;'.$display.';">';
		}
		 
		foreach ($cat_terms as $cat_term)
		{
			
			$checked = '';
			
			if(in_array($cat_term->term_id,$search_terms))
			{	
				if($cat_display == 'select' || $cat_display == 'multiselect')	
					$checked = 'selected="selected"';
				else
					$checked = 'checked="checked"';	
			}	
			
			if($cat_display == 'radio')
				$out .= '<span style="display:block" ><input type="radio" field_type="radio" name="post_category['.$cat_term->taxonomy.'][]" '.$main_list_class.' alt="'.$cat_term->taxonomy.'" title="'.ucfirst($cat_term->name).'" value="'.$cat_term->term_id.'" '.$checked.$onchange.' id="gd-cat-' . $cat_term->term_id . '" >'.$term_check.ucfirst($cat_term->name).'</span>';
			elseif($cat_display == 'select' || $cat_display == 'multiselect') 
            	$out .= '<option '.$main_list_class.' style="margin-left:'.$p.'px;" alt="'.$cat_term->taxonomy.'" title="'.ucfirst($cat_term->name).'" value="'.$cat_term->term_id.'" '.$checked.$onchange.' >'.$term_check.ucfirst($cat_term->name).'</option>';
				
			else {
				$out .= '<span style="display:block"><input style="display:inline-block" type="checkbox" field_type="checkbox" name="post_category['.$cat_term->taxonomy.'][]" '.$main_list_class.' alt="'.$cat_term->taxonomy.'" title="'.ucfirst($cat_term->name).'" value="'.$cat_term->term_id.'" '.$checked.$onchange.' id="gd-cat-' . $cat_term->term_id . '" >'.$term_check.ucfirst($cat_term->name).'</span>'; 
			}
				
				// Call recurson to print sub cats
				$out .=  geodir_custom_taxonomy_walker($cat_taxonomy, $cat_term->term_id,$hide_empty,$pading);
			
		}
		
		if($cat_display == 'checkbox' || $cat_display == 'radio')	
			$out .= '</div>'; 
		
		return $out;
	}
	return;
}
}

/* secound test */
if (!function_exists('geodir_custom_taxonomy_walker2')) {
function geodir_custom_taxonomy_walker2($cat_taxonomy, $cat_limit = '')
{	
	$post_category = '';
	$post_category_str = '';
	global $exclude_cats;
	
	$cat_exclude = '';
	if(is_array($exclude_cats) && !empty($exclude_cats))
		$cat_exclude = serialize($exclude_cats);
	
	if(isset($_REQUEST['backandedit'])){
		$post = (object)unserialize($_SESSION['listing']);
		
		if(!is_array($post->post_category[$cat_taxonomy]))
			$post_category = $post->post_category[$cat_taxonomy];
			
		$post_categories = $post->post_category_str;
		if(!empty($post_categories) && array_key_exists($cat_taxonomy,$post_categories))
			$post_category_str = $post_categories[$cat_taxonomy];
	
	}elseif((geodir_is_page('add-listing') && isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') || (is_admin())) { 
		global $post;
	
		$post_category =   geodir_get_post_meta($post->ID,$cat_taxonomy,true);
		if(empty($post_category) && isset( $post->$cat_taxonomy ) ){
			$post_category =   $post->$cat_taxonomy;
		}
		
		$post_categories = get_post_meta($post->ID,'post_categories',true);
		
		if(empty($post_category) && !empty($post_categories) && !empty($post_categories[$cat_taxonomy])){
			foreach(explode(",", $post_categories[$cat_taxonomy] ) as $cat_part){
					if(is_numeric($cat_part)){$cat_part_arr[] =$cat_part; }				
			}
			if(is_array($cat_part_arr)){
			$post_category =   implode(',',$cat_part_arr);	
			}
		}
		
		if(!empty($post_category)){
			$cat1 = array_filter(explode(',',$post_category));
			$post_category = ','.implode(',',$cat1).',';
			
		}
	
		if($post_category != '' && is_array($exclude_cats) && !empty($exclude_cats)){

			$post_category_upd = explode(',', $post_category);
			$post_category_change = '';
			foreach($post_category_upd as $cat){
			
				if(!in_array($cat, $exclude_cats) && $cat != ''){
					$post_category_change .= ','.$cat;
				}	
			}	
			$post_category = $post_category_change;
		}
		
		
		
		if(!empty($post_categories) && array_key_exists($cat_taxonomy,$post_categories)){
			$post_category_str = $post_categories[$cat_taxonomy];	
		}
	}
	
    echo '<input type="hidden" id="cat_limit" value="'.$cat_limit.'" name="cat_limit['.$cat_taxonomy.']"  />'; 
	
	echo '<input type="hidden" id="post_category" value="'.$post_category.'" name="post_category['.$cat_taxonomy.']"  />'; 
	
	echo '<input type="hidden" id="post_category_str" value="'.$post_category_str.'" name="post_category_str['.$cat_taxonomy.']"  />'; 
	
	
	?>	
    	<div class="cat_sublist" > 
			<?php 
				
				$post_id = isset($post->ID) ? $post->ID : '';
				
				if((geodir_is_page('add-listing') || is_admin()) && !empty($post_categories[$cat_taxonomy]) ) { 
				
					geodir_editpost_categories_html($cat_taxonomy, $post_id, $post_categories);
				}	
			?>
        </div>
    	<script type="text/javascript">
		
		function show_subcatlist(main_cat){
				if(main_cat != ''){
					var url = '<?php echo geodir_get_ajax_url();?>';
					var cat_taxonomy = '<?php echo $cat_taxonomy;?>';
					var cat_exclude = '<?php echo base64_encode($cat_exclude);?>'; 
					var cat_limit = jQuery('#'+cat_taxonomy).find('#cat_limit').val();
					jQuery.post(url,{geodir_ajax:'category_ajax',cat_tax:cat_taxonomy,main_catid:main_cat,exclude:cat_exclude},function(data){
						if(data != ''){
							jQuery('#'+cat_taxonomy).find('.cat_sublist').append(data);
							
							setTimeout(function(){
								jQuery('#'+cat_taxonomy).find('.cat_sublist').find('.chosen_select').chosen();
							},200);
							
							
						}	
						maincat_obj = jQuery('#'+cat_taxonomy).find('.main_cat_list');					
						
						if(cat_limit != '' && jQuery('#'+cat_taxonomy).find('.cat_sublist .chosen_select').length >= cat_limit ){
							maincat_obj.find('.chosen_select').chosen('destroy');
							maincat_obj.hide();		
						}else{
							maincat_obj.show();
							maincat_obj.find('.chosen_select').chosen('destroy');
							maincat_obj.find('.chosen_select').prop('selectedIndex', 0);
							maincat_obj.find('.chosen_select').chosen();
						}					
						
						update_listing_cat();
																	
					});
				}
				update_listing_cat();
			}
			
			function update_listing_cat(){
				var cat_taxonomy = '<?php echo $cat_taxonomy;?>';
				var cat_ids = '';	
				var main_cat = '';
				var sub_cat = '';
				var post_cat_str = '';
				var cat_limit = jQuery('#'+cat_taxonomy).find('#cat_limit').val();
				
				jQuery('#'+cat_taxonomy).find('.cat_sublist > div').each(function(){
					main_cat = jQuery(this).find('.listing_main_cat').val();
					
					if(jQuery(this).find('.chosen_select').length > 0)
						sub_cat = jQuery(this).find('.chosen_select').val()
					
					if(post_cat_str != '')
						post_cat_str = post_cat_str + '#';
						
					post_cat_str = post_cat_str + main_cat;
					
					if(jQuery(this).find('.listing_main_cat').is(':checked')){
					  cat_ids = cat_ids + ',' + main_cat;
					  post_cat_str = post_cat_str + ',y';
					  
					  if(jQuery(this).find('.post_default_category input').is(':checked'))
					  	post_cat_str = post_cat_str + ',d';
					  
					}else{
						post_cat_str = post_cat_str + ',n';
					}  
					  
					if(sub_cat != ''){ 
						cat_ids = cat_ids + ',' + sub_cat;
						post_cat_str = post_cat_str + ':' + sub_cat;		
					}else{
						post_cat_str = post_cat_str + ':';		
					}
					
				});
				
				maincat_obj = jQuery('#'+cat_taxonomy).find('.main_cat_list');					
				
				
				if(cat_limit != '' && jQuery('#'+cat_taxonomy).find('.cat_sublist > div.post_catlist_item').length >= cat_limit && cat_limit != 0 ){
					maincat_obj.find('.chosen_select').chosen('destroy');
					maincat_obj.hide();		
				}else{
					maincat_obj.show();
					maincat_obj.find('.chosen_select').chosen('destroy');
					maincat_obj.find('.chosen_select').prop('selectedIndex', 0);
					maincat_obj.find('.chosen_select').chosen();	
				}
						
				maincat_obj.find('.chosen_select').trigger("chosen:updated");
				jQuery('#'+cat_taxonomy).find('#post_category').val(cat_ids);
				jQuery('#'+cat_taxonomy).find('#post_category_str').val(post_cat_str);
				
				
			}
			jQuery(function(){
				update_listing_cat();
			})
			
			
		</script>	
        <?php 
			if( !empty($post_categories) && array_key_exists($cat_taxonomy,$post_categories) ){
				$post_cat_str = $post_categories[$cat_taxonomy];
				$post_cat_array = explode("#",$post_cat_str);
				if(count($post_cat_array) >= $cat_limit && $cat_limit != 0)
					$style = "display:none;";
			}	
		?>
       	<div class="main_cat_list" style=" <?php if(isset($style)){ echo $style;}?> "> 
	   		<?php geodir_get_catlist($cat_taxonomy,0);  // print main categories list ?>
		</div>
        <?php 
	  
}
}

/* Category Slection Interface in add/edit listing form */
function geodir_addpost_categories_html( $request_taxonomy, $parrent, $selected = false, $main_selected = true, $default = false, $exclude='' ){ 
				global $exclude_cats;
				
				if($exclude != ''){
					$exclude_cats = unserialize(base64_decode($exclude));
				}
				
				if((is_array($exclude_cats) && !empty($exclude_cats) && !in_array($parrent, $exclude_cats)) || 
				(!is_array($exclude_cats) || empty($exclude_cats))
				){
?>
	
    <?php $main_cat = get_term( $parrent, $request_taxonomy); ?>
    
    <div class="post_catlist_item" style="border:1px solid #CCCCCC; margin:5px auto; padding:5px;">
    	<img alt="move icon" src="<?php echo geodir_plugin_url().'/geodirectory-assets/images/move.png';?>" onclick="jQuery(this).closest('div').remove();update_listing_cat();" align="right" />
		<?php /* ?>
		<img src="<?php echo geodir_plugin_url().'/geodirectory-assets/images/move.png';?>" onclick="jQuery(this).closest('div').remove();show_subcatlist();" align="right" /> 
		<?php */ ?>
       
        <input type="checkbox" value="<?php echo $main_cat->term_id;?>" class="listing_main_cat"  onchange="if(jQuery(this).is(':checked')){jQuery(this).closest('div').find('.post_default_category').prop('checked',false).show();}else{jQuery(this).closest('div').find('.post_default_category').prop('checked',false).hide();};update_listing_cat()" checked="checked" disabled="disabled" />
       <span> 
        <?php printf( __('Add listing in %s category',GEODIRECTORY_TEXTDOMAIN), ucwords($main_cat->name) );?> 
        </span> 
        <br/>
        
		<div class="post_default_category" >
        <input type="radio" name="post_default_category"  value="<?php echo $main_cat->term_id;?>" onchange="update_listing_cat()" <?php if($default) echo ' checked="checked" ';?>   />
        <span> 
        <?php printf( __('Set %s as default category',GEODIRECTORY_TEXTDOMAIN), ucwords($main_cat->name) );?> 
        </span>
        </div>
        
        <br/>
        <?php 
		$cat_terms = get_terms($request_taxonomy, array('parent' => $main_cat->term_id, 'hide_empty' => false, 'exclude'=>$exclude_cats)); 
		if(!empty($cat_terms)) { ?>
            <span> <?php printf( __('Add listing in category',GEODIRECTORY_TEXTDOMAIN) );?></span>
            <?php geodir_get_catlist($request_taxonomy, $parrent, $selected) ?>
        <?php } ?>
    </div>
        
<?php }  }


function geodir_editpost_categories_html($request_taxonomy, $request_postid, $post_categories){ 
	
	if( !empty($post_categories) && array_key_exists($request_taxonomy,$post_categories) ){
		$post_cat_str = $post_categories[$request_taxonomy];
		$post_cat_array = explode("#",$post_cat_str);
		if(is_array($post_cat_array)){
			foreach($post_cat_array as $post_cat_html){
				
				$post_cat_info = explode(":",$post_cat_html);
				$post_maincat_str = $post_cat_info[0];
				
				if(!empty($post_maincat_str)){	
					$post_maincat_info = explode(",",$post_maincat_str);
					$post_maincat_id = $post_maincat_info[0];
					($post_maincat_info[1] == 'y') ? $post_maincat_selected = true : $post_maincat_selected = false ;
					(end($post_maincat_info) == 'd') ? $post_maincat_default = true : $post_maincat_default = false ;
				}
				$post_sub_catid = '';
				if(isset($post_cat_info[1]) &&  !empty($post_cat_info[1])){	
					$post_sub_catid = (int)$post_cat_info[1];
				}
				 
				geodir_addpost_categories_html($request_taxonomy, $post_maincat_id, $post_sub_catid, $post_maincat_selected, $post_maincat_default  );
				 
			}
		}else{
			
			$post_cat_info = explode(":",$post_cat_str);
			$post_maincat_str = $post_cat_info[0];
			
			$post_sub_catid = '';
			
			if(!empty($post_maincat_str)){	
				$post_maincat_info = explode(",",$post_maincat_str);
				$post_maincat_id = $post_maincat_info[0];
				($post_maincat_info[1] == 'y') ? $post_maincat_selected = true : $post_maincat_selected = false ;
				(end($post_maincat_info) == 'd') ? $post_maincat_default = true : $post_maincat_default = false ;
			}
			
			if(isset($post_cat_info[1]) &&  !empty($post_cat_info[1])){	
				$post_sub_catid = (int)$post_cat_info[1];
			}
			
			geodir_addpost_categories_html($request_taxonomy, $post_maincat_id, $post_sub_catid, $post_maincat_selected, $post_maincat_default  );
			
		}	
	}
}

function geodir_get_catlist($cat_taxonomy, $parrent = 0, $selected = false)
{
	
	global $exclude_cats;
	
	$cat_terms = get_terms($cat_taxonomy, array('parent' => $parrent, 'hide_empty' => false, 'exclude'=>$exclude_cats)); 
	
	if(!empty($cat_terms)){
	
		$onchange = '';
		//if($parrent == '0')
			$onchange = ' onchange="show_subcatlist(this.value)"  ';
		//else
			//$onchange = ' onchange="update_listing_cat()"  ';
		
		$option_selected = '';	
		if(!$selected)	
			$option_slected = ' selected="selected" ';
			
		echo '<select field_type="select" id="'.$cat_taxonomy.'" class="chosen_select" '.$onchange.' option-ajaxChosen="false" >';   
		
		echo '<option value="" '.$option_selected.' >'.__('Select Category',GEODIRECTORY_TEXTDOMAIN).'</option>';   	
		
		foreach ($cat_terms as $cat_term)
		{
			$option_selected = '';
			if($selected == $cat_term->term_id)	
				$option_selected = ' selected="selected" '; 
			
			echo '<option  '.$option_selected.' alt="'.$cat_term->taxonomy.'" title="'.ucfirst($cat_term->name).'" value="'.$cat_term->term_id.'" >'.ucfirst($cat_term->name).'</option>'; 
		} 
		echo '</select>';
	}	
}

 
/**
 * Replaces "Post" in the update messages for custom post types on the "Edit" post screen.
 * For example "Post updated. View Post." becomes "Place updated. View Place".
 **/
function geodir_custom_update_messages( $messages ) {
	global $post, $post_ID;

	$post_types = get_post_types( array( 'show_ui' => true, '_builtin' => false ), 'objects' );

	foreach( $post_types as $post_type => $post_object ) {

		$messages[$post_type] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( '%s updated. <a href="%s">View %s</a>',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
			2 => __( 'Custom field updated.',GEODIRECTORY_TEXTDOMAIN ),
			3 => __( 'Custom field deleted.',GEODIRECTORY_TEXTDOMAIN),
			4 => sprintf( __( '%s updated.',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name ),
			5 => isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( '%s published. <a href="%s">View %s</a>',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
			7 => sprintf( __( '%s saved.',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name ),
			8 => sprintf( __( '%s submitted. <a target="_blank" href="%s">Preview %s</a>',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
			9 => sprintf( __( '%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview %s</a>',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, date_i18n( __( 'M j, Y @ G:i' ,GEODIRECTORY_TEXTDOMAIN), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), $post_object->labels->singular_name ),
			10 => sprintf( __( '%s draft updated. <a target="_blank" href="%s">Preview %s</a>',GEODIRECTORY_TEXTDOMAIN ), $post_object->labels->singular_name, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $post_object->labels->singular_name ),
			);
	}

	return $messages;
}

 
 
/**
 * Register default custom Post Types and Taxonomies
 **/
  
function geodir_register_defaults() {
	
	global $wpdb;
	
	$menu_icon  = geodir_plugin_url() . '/geodirectory-assets/images/favicon.ico';
	
	if(!$listing_slug = get_option('geodir_listing_prefix'))
		$listing_slug = 'places';
	
	/**
	 * Taxonomies
	 **/
	//if ( ! taxonomy_exists('gd_place_tags') )
	{

			$gd_placetags = array();
			$gd_placetags['object_type']= 'gd_place';
			$gd_placetags['listing_slug']= $listing_slug.'/tags';
			$gd_placetags['args'] = array (
				'public' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => $listing_slug.'/tags', 'with_front' =>false, 'hierarchical' =>true),
				'query_var' => true,
				
				'labels' => array (
					'name'          => __( 'Place Tags', GEODIRECTORY_TEXTDOMAIN ),
					'singular_name' => __( 'Place Tag', GEODIRECTORY_TEXTDOMAIN ),
					'search_items'  => __( 'Search Place Tags', GEODIRECTORY_TEXTDOMAIN ),
					'popular_items' => __( 'Popular Place Tags', GEODIRECTORY_TEXTDOMAIN ),
					'all_items'     => __( 'All Place Tags', GEODIRECTORY_TEXTDOMAIN ),
					'edit_item'     => __( 'Edit Place Tag', GEODIRECTORY_TEXTDOMAIN ),
					'update_item'   => __( 'Update Place Tag', GEODIRECTORY_TEXTDOMAIN ),
					'add_new_item'  => __( 'Add New Place Tag', GEODIRECTORY_TEXTDOMAIN ),
					'new_item_name' => __( 'New Place Tag Name', GEODIRECTORY_TEXTDOMAIN ),
					'add_or_remove_items' => __( 'Add or remove Place tags', GEODIRECTORY_TEXTDOMAIN ),
					'choose_from_most_used' => __( 'Choose from the most used Place tags', GEODIRECTORY_TEXTDOMAIN ),
					'separate_items_with_commas' => __( 'Separate Place tags with commas', GEODIRECTORY_TEXTDOMAIN ),
					),
			);

			
			$geodir_taxonomies = get_option('geodir_taxonomies');
			$geodir_taxonomies['gd_place_tags'] = $gd_placetags;
			update_option( 'geodir_taxonomies', $geodir_taxonomies );
	

			// Update post types and delete tmp options
			flush_rewrite_rules();

		}

	//if ( ! taxonomy_exists('gd_placecategory') )
	{

			$gd_placecategory = array();
			$gd_placecategory['object_type']= 'gd_place';
			$gd_placecategory['listing_slug']= $listing_slug;
			$gd_placecategory['args'] = array (
				'public' => true,
				'hierarchical'  => true,
				'rewrite' => array ('slug' =>$listing_slug, 'with_front' =>false, 'hierarchical' =>true),
				'query_var' => true,
				'labels' => array (
					'name'          => __( 'Place Categories', GEODIRECTORY_TEXTDOMAIN ),
					'singular_name' => __( 'Place Category', GEODIRECTORY_TEXTDOMAIN ),
					'search_items'  => __( 'Search Place Categories', GEODIRECTORY_TEXTDOMAIN ),
					'popular_items' => __( 'Popular Place Categories', GEODIRECTORY_TEXTDOMAIN ),
					'all_items'     => __( 'All Place Categories', GEODIRECTORY_TEXTDOMAIN ),
					'edit_item'     => __( 'Edit Place Category', GEODIRECTORY_TEXTDOMAIN ),
					'update_item'   => __( 'Update Place Category', GEODIRECTORY_TEXTDOMAIN ),
					'add_new_item'  => __( 'Add New Place Category', GEODIRECTORY_TEXTDOMAIN ),
					'new_item_name' => __( 'New Place Category', GEODIRECTORY_TEXTDOMAIN ),
					'add_or_remove_items' => __( 'Add or remove Place categories', GEODIRECTORY_TEXTDOMAIN ),
				),
			);

		
			$geodir_taxonomies = get_option('geodir_taxonomies');
			$geodir_taxonomies['gd_placecategory'] = $gd_placecategory;
			update_option( 'geodir_taxonomies', $geodir_taxonomies );
			

			flush_rewrite_rules();
		}
    
    /**
	 * Post Types
	 **/
	
	//if ( ! post_type_exists('gd_place') ) 
	{
			
			$labels = array (
			'name'          => __('Places', GEODIRECTORY_TEXTDOMAIN),
			'singular_name' => __('Place', GEODIRECTORY_TEXTDOMAIN),
			'add_new'       => __('Add New', GEODIRECTORY_TEXTDOMAIN),
			'add_new_item'  => __('Add New Place', GEODIRECTORY_TEXTDOMAIN),
			'edit_item'     => __('Edit Place', GEODIRECTORY_TEXTDOMAIN),
			'new_item'      => __('New Place', GEODIRECTORY_TEXTDOMAIN),
			'view_item'     => __('View Place', GEODIRECTORY_TEXTDOMAIN),
			'search_items'  => __('Search Places', GEODIRECTORY_TEXTDOMAIN),
			'not_found'     => __('No Place Found', GEODIRECTORY_TEXTDOMAIN),
			'not_found_in_trash' => __('No Place Found In Trash', GEODIRECTORY_TEXTDOMAIN) );
			
			$place_default = array (
			'labels' => $labels,	
			'can_export' => true,
			'capability_type' => 'post',
			'description' => 'Place post type.',
			'has_archive' => $listing_slug,
			'hierarchical' => false,
			'map_meta_cap' => true,
			'menu_icon' => $menu_icon,
			'public' => true,
			'query_var' => true,
			'rewrite' => array ('slug' => $listing_slug.'/%gd_taxonomy%', 'with_front' => false, 'hierarchical' => true),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', /*'revisions', 'post-formats'*/ ),
			'taxonomies' => array('gd_placecategory','gd_place_tags') );

			//Update custom post types
			$geodir_post_types = get_option( 'geodir_post_types' );
			$geodir_post_types['gd_place'] = $place_default;
			update_option( 'geodir_post_types', $geodir_post_types );
			
			// Update post types and delete tmp options
			flush_rewrite_rules();
		}
		
		
		geodir_register_taxonomies();
		geodir_register_post_types();
		
		//die;
		
} 


$comment_post_cache = array();
$gd_permalink_cache = array();
function geodir_listing_permalink_structure($post_link, $post_obj, $leavename, $sample)
{
	//echo $post_link."<br />".$sample ;


	global $wpdb, $wp_query ,$plugin_prefix,$post,$comment_post_cache,$gd_permalink_cache;
	if(isset($post_obj->ID) && isset($post->ID) && $post_obj->ID==$post->ID){}
	elseif(isset($post_obj->post_status) && $post_obj->post_status=='auto-draft'){return $post_link;}
	else{$orig_post = $post;$post = $post_obj;}

		if(in_array( $post->post_type, geodir_get_posttypes() ) ){

		if(isset($comment_post_cache[$post->ID])){$post = $comment_post_cache[$post->ID];}
		if(isset($gd_permalink_cache[$post->ID]) && $gd_permalink_cache[$post->ID] && !$sample){$post_id = $post->ID; if(isset($orig_post)){$post = $orig_post;} return $gd_permalink_cache[$post_id];}

		if(!isset($post->post_locations)){
			$post_type= $post->post_type;
			$ID = $post->ID;
				$post2 = $wpdb->get_row(
						$wpdb->prepare(
							"SELECT * from ".$plugin_prefix.$post->post_type."_detail WHERE post_id = %d ",
							array($post->ID)
						)
					);
				
				$post = (object) array_merge((array) $post, (array) $post2);
				
				$comment_post_cache[$post->ID] = $post;
				}
		
		if ( false !== strpos( $post_link, '%gd_taxonomy%' ) ) 
		{
			
			if(get_option('geodir_add_location_url'))
			{
				$location_request = '';
				
				
				if(!empty($post->post_locations))
				{
					$geodir_arr_locations = explode(',' , $post->post_locations);
					if(count($geodir_arr_locations)==3)
					{
						$post->city_slug = str_replace('[','',$geodir_arr_locations[0]);
						$post->city_slug =  str_replace(']' ,'', $post->city_slug);
						$post->region_slug = str_replace('[','' ,$geodir_arr_locations[1]);
						$post->region_slug =  str_replace(']' , '' , $post->region_slug);
						$post->country_slug = str_replace('[','' ,$geodir_arr_locations[2]);
						$post->country_slug =  str_replace(']' , '' ,  $post->country_slug);
						
						$post_location = (object)array(	'country_slug'=>$post->country_slug,
																	'region_slug'=>$post->region_slug,
																	'city_slug'=>$post->city_slug
															);
						
					}
					else
						$post_location = geodir_get_location();
					
					
					
				
				}
				else
				{
				
					$post_location_sql = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT post_locations from ".$plugin_prefix.$post->post_type."_detail WHERE post_id = %d ",
							array($post->ID)
						)
					);
					
					if(!empty($post_location_sql) && is_array($post_location_sql) &&  !empty($post_location_sql[0]->post_locations))
					{
						
						$geodir_arr_locations = explode(',' , $post_location_sql[0]->post_locations);
						if(count($geodir_arr_locations)==3)
						{
							$post->city_slug = str_replace('[', '' ,$geodir_arr_locations[0]);
							$post->city_slug =  str_replace(']' , '' ,$post->city_slug);
							$post->region_slug = str_replace('[','',$geodir_arr_locations[1]);
							$post->region_slug =  str_replace(']' ,'', $post->region_slug);
							$post->country_slug = str_replace('[','',$geodir_arr_locations[2]);
							$post->country_slug =  str_replace(']' ,'', $post->country_slug);
							
							$post_location = (object)array(	'country_slug'=>$post->country_slug,
																		'region_slug'=>$post->region_slug,
																		'city_slug'=>$post->city_slug
																);
							
						}
					}
					else
						$post_location = geodir_get_location();
				}
				
				
					
				
				if(!empty($post_location)){
					if(get_option('geodir_show_location_url') == 'all'){
						$location_request .= $post_location->country_slug.'/';
						$location_request .= $post_location->region_slug.'/';
						$location_request .= $post_location->city_slug.'/';		
					}else{
						$location_request .= $post_location->city_slug.'/';		
					}
				}		
			}
			
			if(get_option('geodir_add_categories_url'))
			{
				
				$term_request = '';
				$taxonomies = geodir_get_taxonomies($post->post_type);
				
				$taxonomies = end($taxonomies);
				
				if(!empty($post->default_category))
				{
					$post_terms = $post->default_category;
				}
				else
				{	$post_terms='';
				
					if(isset($post->$taxonomies)){
					$post_terms = explode(",", trim($post->$taxonomies,","));
					$post_terms = $post_terms[0];
					}
					
					if(!$post_terms)
						$post_terms = geodir_get_post_meta($post->ID, 'default_category',true);
					
					if(!$post_terms)
					{
						$post_terms = geodir_get_post_meta($post->ID, $taxonomies,true);
						
						if($post_terms)
						{
							$post_terms = explode(",", trim($post_terms,","));
							$post_terms = $post_terms[0];
						}
					}
				}	
				
				$term = get_term_by( 'id', $post_terms, $taxonomies );
				
				if(!empty($term))
					$term_request = $term->slug;
					//$term_request = $term->slug.'/';
			}
			
			$request_term = '';
			$listingurl_separator = '';
			//$detailurl_separator = get_option('geodir_detailurl_separator');
			$detailurl_separator ='';
			if(isset($location_request) && $location_request != '' && isset($term_request) && $term_request != '')
			{	
				$request_term = $location_request;
				//$listingurl_separator = get_option('geodir_listingurl_separator');
				//$request_term .= $listingurl_separator.'/'.$term_request;
				$request_term .= $term_request;
				
			}else{
				if(isset($location_request) && $location_request != '') $request_term = $location_request;
				
				if(isset($term_request) && $term_request != '')	$request_term .= $term_request;	
			}
			$request_term =trim($request_term,'/');
			if(!empty($request_term))					
				$post_link = str_replace( '%gd_taxonomy%', $request_term.$detailurl_separator, $post_link );
			else
				$post_link = str_replace( '/%gd_taxonomy%', $request_term.$detailurl_separator, $post_link );
			//echo $post_link ;
		}
		// temp cache the permalink
		if(!$sample && (!isset($_REQUEST['geodir_ajax']) || (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax']!='add_listing' ))){$gd_permalink_cache[$post->ID]=$post_link;}
	}	
	if(isset($orig_post)){$post = $orig_post;}
	//echo $post_link ;
    return $post_link;

}


function geodir_term_link($termlink, $term, $taxonomy) 
{
  	$geodir_taxonomies = geodir_get_taxonomies('',true);

	if(isset($taxonomy) && !empty($geodir_taxonomies) && in_array($taxonomy,$geodir_taxonomies)){
		global $geodir_add_location_url;
		$include_location = false;
		$request_term = array();
		
		$listing_slug = geodir_get_listing_slug($taxonomy);
		//echo $listing_slug ;
		
		if($geodir_add_location_url != NULL && $geodir_add_location_url != '')
		{	
			if($geodir_add_location_url && get_option('geodir_add_location_url'))
			{	$include_location = true; }
			
		}elseif(get_option('geodir_add_location_url') && isset($_SESSION['gd_multi_location']) && $_SESSION['gd_multi_location']==1)
			$include_location = true;
		
		if($include_location ){
		
			$request_term = geodir_get_current_location_terms('query_vars') ;
			
			if(!empty($request_term)){
				
				$url_separator = '';//get_option('geodir_listingurl_separator');	
				
				if ( get_option('permalink_structure') != '' ){
					
					$old_listing_slug = '/'.$listing_slug.'/';
					
					$request_term = implode("/",$request_term);
					//$new_listing_slug = '/'.$listing_slug.'/'.rtrim($request_term,'/').'/'.$url_separator.'/';
					$new_listing_slug = '/'.$listing_slug.'/'. $request_term.'/';
					
					$termlink = substr_replace($termlink, $new_listing_slug, strpos($termlink, $old_listing_slug), strlen($old_listing_slug));
						
				}else{
					$termlink = geodir_getlink($termlink,$request_term);
				}
			
			}
		}	
	}
	
    return $termlink;
}


function geodir_posttype_link($link, $post_type){
	global $geodir_add_location_url;
	$location_terms = array();
	if(in_array($post_type, geodir_get_posttypes()) ){

		if(get_option('geodir_add_location_url') && isset($_SESSION['gd_multi_location']) && $_SESSION['gd_multi_location']==1){
			$location_terms = geodir_get_current_location_terms('query_vars');
			if(!empty($location_terms)){
		
				if ( get_option('permalink_structure') != '' ){
					
					$location_terms = implode("/",$location_terms);
					$location_terms = rtrim($location_terms,'/');
					return $link . urldecode($location_terms) . '/';  		
				}else{
					return geodir_getlink($link,$location_terms);
				}
			
			}
			
		}		
		
	}
	
	return $link;

}

function get_post_type_singular_label($post_type, $echo=false)
{
	$obj_post_type = get_post_type_object($post_type);
	if(!is_object($obj_post_type)){return;}
	if($echo)
		echo $obj_post_type->labels->singular_name;
	else
		return $obj_post_type->labels->singular_name;

}

function get_post_type_plural_label($post_type, $echo=false)
{
	$all_postypes = geodir_get_posttypes();
	
	if(!in_array($post_type, $all_postypes))
		return false;
		
	$obj_post_type = get_post_type_object($post_type);
	if($echo)
		echo $obj_post_type->labels->name;
	else
		return $obj_post_type->labels->name;

}

function geodir_term_exists($term, $taxonomy = '', $parent = 0) {
	global $wpdb;

	$select = "SELECT term_id FROM $wpdb->terms as t WHERE ";
	$tax_select = "SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE ";

	if ( is_int($term) ) {
		if ( 0 == $term )
			return 0;
		$where = 't.term_id = %d';
		if ( !empty($taxonomy) )
			return $wpdb->get_row( $wpdb->prepare( $tax_select . $where . " AND tt.taxonomy = %s", $term, $taxonomy ), ARRAY_A );
		else
			return $wpdb->get_var( $wpdb->prepare( $select . $where, $term ) );
	}

	$term = trim( wp_unslash( $term ) );

	if ( '' === $slug = sanitize_title($term) )
		return 0;

	$where = 't.slug = %s';

	$where_fields = array($slug);
	if ( !empty($taxonomy) ) {
		$parent = (int) $parent;
		if ( $parent > 0 ) {
			$where_fields[] = $parent;
			$else_where_fields[] = $parent;
			$where .= ' AND tt.parent = %d';
			
		}

		$where_fields[] = $taxonomy;
		

		if ( $result = $wpdb->get_row( $wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $where AND tt.taxonomy = %s", $where_fields), ARRAY_A) )
			return $result;

		return false;
	}

	if ( $result = $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $where", $where_fields) ) )
		return $result;

	return false;
}

function geodir_get_term_icon_rebuild(){

    update_option('gd_term_icons','');

}

function geodir_get_term_icon($term_id = false,$rebuild=false)
{
    global $wpdb;
	if(!$rebuild){$terms_icons = get_option('gd_term_icons');}
	else{$terms_icons = '';}

	if(empty($terms_icons)){
		$default_icon_url = get_option('geodir_default_marker_icon');
		$taxonomy = geodir_get_taxonomies();
        $post_types = geodir_get_posttypes();
        $tax_arr = array();
        foreach($post_types as $post_type){
            $tax_arr[]= "'".$post_type."category'";
        }
        $tax_c = implode(',',$tax_arr);
        $terms = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy IN ($tax_c)");
		//$terms = get_terms( $taxonomy );

        //print_r($terms );exit;

		foreach( $terms as $term ) {
			$post_type = str_replace("category", "", $term->taxonomy);
			$a_terms[$post_type][] = $term;

		}

		foreach ($a_terms as $pt=>$t2) {

			foreach ($t2 as $term) {

				//print_r($term);
				$term_icon = get_tax_meta($term->term_id, 'ct_cat_icon', false, $pt);
				if ($term_icon) {
					$term_icon_url = $term_icon["src"];
				} else {
					$term_icon_url = $default_icon_url;
				}
				$terms_icons[$term->term_id] = $term_icon_url;
			}
		}

		update_option('gd_term_icons',$terms_icons);
	}

	if ($term_id && isset($terms_icons[$term_id])) {
		return $terms_icons[$term_id];
	}elseif($term_id && !isset($terms_icons[$term_id])) {
		return get_option('geodir_default_marker_icon');
	}


	return $terms_icons;
}