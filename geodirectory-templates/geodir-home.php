<?php 
// get header
get_header(); 

###### WRAPPER OPEN ######
// this adds the opening html tags to the primary div, this required the closing tag below :: ($type='',$id='',$class='')
do_action( 'geodir_wrapper_open', 'home-page', 'geodir-wrapper','');

    ###### TOP CONTENT ######
	// action called before the main content and the page specific content
	do_action('geodir_top_content', 'home-page');
	// template specific, this can add the sidebar top section and breadcrums
	do_action('geodir_home_before_main_content');
	// action called before the main content
	do_action('geodir_before_main_content', 'home-page');

		###### SIDEBAR ######
		do_action('geodir_home_sidebar_left');
			
			###### MAIN CONTENT WRAPPERS OPEN ######
			// this adds the opening html tags to the content div, this required the closing tag below :: ($type='',$id='',$class='')
			do_action( 'geodir_wrapper_content_open', 'home-page', 'geodir-wrapper-content','');
			
					###### MAIN CONTENT ######
					// this call the main page content
        			do_action('geodir_home_content');
        
	   		###### MAIN CONTENT WRAPPERS CLOSE ######
			// this adds the closing html tags to the wrapper_content div :: ($type='')
			do_action( 'geodir_wrapper_content_close', 'home-page');
      
        ###### SIDEBAR ######
		do_action('geodir_home_sidebar_right');
        
    ###### BOTTOM SECTION WIDGET AREA ######
	// adds the details bottom section widget area, you can add more classes via ''
	do_action('geodir_sidebar_home_bottom_section');
	
# WRAPPER CLOSE ######	
// this adds the closing html tags to the wrapper div :: ($type='')
do_action( 'geodir_wrapper_close', 'home-page');

//get footer
get_footer();    