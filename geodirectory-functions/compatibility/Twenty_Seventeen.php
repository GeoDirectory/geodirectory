<?php


add_filter('post_thumbnail_html','geodir_2017_remove_header',10,5);
function geodir_2017_remove_header($html, $post_ID, $post_thumbnail_id, $size, $attr){
	if($size=='twentyseventeen-featured-image'){

		if(geodir_is_page('detail') || geodir_is_page('add-listing')){
			$html = '';// nothing up top
		}

	}

	return $html;
}

add_action('after_setup_theme', 'gd_2017_action_calls', 11);
/**
 * Action calls for enfold theme compatibility.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_2017_action_calls() {
	// make top section wide
	remove_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
	remove_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
	remove_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
	remove_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
	remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
	remove_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);


	add_action('geodir_wrapper_open', 'gd_2017_compat_add_top_section_back', 5);

}

/**
 * Adds top section based on current page type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_2017_compat_add_top_section_back()
{

	if (is_page_geodir_home() || geodir_is_page('location') || geodir_is_page('home')) {
		add_action('geodir_wrapper_open', 'geodir_action_geodir_sidebar_home_top', 8);
	}
	elseif (geodir_is_page('listing')) {
		add_action('geodir_wrapper_open', 'geodir_action_geodir_sidebar_listings_top', 8);
	}
	elseif (geodir_is_page('detail')) {
		add_action('geodir_wrapper_open', 'geodir_action_geodir_sidebar_detail_top', 8);
	}
	elseif (geodir_is_page('search')) {
		add_action('geodir_wrapper_open', 'geodir_action_geodir_sidebar_search_top', 8);
	}
	elseif (geodir_is_page('author')) {
		add_action('geodir_wrapper_open', 'geodir_action_geodir_sidebar_author_top', 8);
	}


}

function geodir_twentyseventeen_body_classes($classes){

	if(geodir_is_page('add-listing')
	   || geodir_is_page('preview')
	   || geodir_is_page('home')
	   || geodir_is_page('location')
	   || geodir_is_page('listing')
	   || geodir_is_page('search')
	   || geodir_is_page('author')
	){
		$classes[] = 'has-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'geodir_twentyseventeen_body_classes' );

