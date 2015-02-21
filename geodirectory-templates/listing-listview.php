<?php do_action('geodir_before_listing_listview'); global $gridview_columns;
$grid_view_class = apply_filters('geodir_grid_view_widget_columns' ,$gridview_columns);
if(isset($_SESSION['gd_listing_view']) && $_SESSION['gd_listing_view']!='' && !isset($before_widget) && !isset($related_posts)){
	if($_SESSION['gd_listing_view']=='1'){$grid_view_class = '';}
	if($_SESSION['gd_listing_view']=='2'){$grid_view_class = 'gridview_onehalf';}
	if($_SESSION['gd_listing_view']=='3'){$grid_view_class = 'gridview_onethird ';}
	if($_SESSION['gd_listing_view']=='4'){$grid_view_class = 'gridview_onefourth';}
	if($_SESSION['gd_listing_view']=='5'){$grid_view_class = 'gridview_onefifth';}
}
?>

<ul class="geodir_category_list_view clearfix">
	
	<?php if (have_posts()) :
    		
				do_action('geodir_before_listing_post_listview');
					
         while (have_posts()) : the_post(); 
		 	global $post, $wpdb, $listing_width, $preview;
			$post_view_class = apply_filters( 'geodir_post_view_extra_class', '' );
			$post_view_article_class = apply_filters( 'geodir_post_view_article_extra_class', '' );
		 ?> 
            
					<li id="post-<?php echo $post->ID;?>" class="clearfix <?php if($grid_view_class){ echo 'geodir-gridview '.$grid_view_class;}else{echo ' geodir-listview ';}?> <?php if($post_view_class){echo $post_view_class;}?>" <?php if($listing_width) echo "style='width:{$listing_width}%;'"; // Width for widget listing ?> >
					<article class="geodir-category-listing <?php if($post_view_article_class){echo $post_view_article_class;}?>">		
			<div class="geodir-post-img"> 
			<?php if($fimage = geodir_show_featured_image($post->ID, 'list-thumb', true, false, $post->featured_image)){ ?>
							
									<a  href="<?php the_permalink(); ?>">
											<?php  echo $fimage;?>
									</a>
								<?php 
									do_action('geodir_before_badge_on_image', $post) ;
									if($post->is_featured){
										echo geodir_show_badges_on_image('featured' , $post,get_permalink());
									}
									
									$geodir_days_new = (int)get_option('geodir_listing_new_days');
									
									if(round(abs(strtotime($post->post_date)-strtotime(date('Y-m-d')))/86400)<$geodir_days_new){
                                    	echo geodir_show_badges_on_image('new' , $post,get_permalink());
									}
                                    do_action('geodir_after_badge_on_image', $post) ;
									?>
									
							
			<?php }  ?>
							
							</div>
						 
						 <div class="geodir-content"> 
						 				
									<?php do_action('geodir_before_listing_post_title', 'listview', $post ); ?>
										                          
									<header class="geodir-entry-header"><h3 class="geodir-entry-title">
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
													
													<?php the_title(); ?>
											
											</a>	
								</h3></header><!-- .entry-header -->
									
									<?php do_action('geodir_after_listing_post_title', 'listview', $post ); ?>
								
									<?php /// Print Distance
				if(isset($_REQUEST['sgeo_lat']) && $_REQUEST['sgeo_lat']!=''){
				
					$startPoint = array( 'latitude'	=> $_REQUEST['sgeo_lat'], 'longitude' => $_REQUEST['sgeo_lon']);	
					
											$endLat = $post->post_latitude; 
											$endLon = $post->post_longitude;
											$endPoint = array( 'latitude'	=> $endLat, 'longitude'	=> $endLon);
											$uom = get_option('geodir_search_dist_1');
											$distance = geodir_calculateDistanceFromLatLong ($startPoint,$endPoint,$uom);?>
										 <h3>
					<?php
					
					 if (round((int)$distance,2) == 0){
												$uom = get_option('geodir_search_dist_2');
						 						
												$distance = geodir_calculateDistanceFromLatLong ($startPoint,$endPoint,$uom);
												if($uom=='feet'){
						 							$uom =__( 'feet', GEODIRECTORY_TEXTDOMAIN );
						 						}
												else{
													$uom =__( 'meters', GEODIRECTORY_TEXTDOMAIN );
												}
												echo round($distance).' '.$uom.'<br />';
											}else{
												if($uom=='miles'){
						 							$uom =__( 'miles', GEODIRECTORY_TEXTDOMAIN );
						 						}
												else{
													$uom =__( 'km', GEODIRECTORY_TEXTDOMAIN );
												}
												echo round($distance,2).' '.$uom.'<br />';
										}
					?>
											</h3>
									<?php } ?>
									
							
								 <?php do_action('geodir_before_listing_post_excerpt', $post ); ?>
								 <?php echo geodir_show_listing_info('listing');?>       
				<div class="geodir-entry-content"><?php if( isset( $character_count ) && ( $character_count || $character_count=='0' ) ) {
				
				echo geodir_max_excerpt($character_count);}else{ the_excerpt(); }?></div>
									
									<?php do_action('geodir_after_listing_post_excerpt', $post ); ?>
							</div><!-- gd-content ends here-->
							<footer class="geodir-entry-meta"><div class="geodir-addinfo clearfix">
								 
								 <?php 
					
					$review_show = geodir_is_reviews_show('listview');
					
					if ($review_show) {
					
						if (!$preview) {
							$post_avgratings = geodir_get_post_rating($post->ID);
							
							do_action('geodir_before_review_rating_stars_on_listview' , $post_avgratings , $post->ID) ;
							echo geodir_get_rating_stars($post_avgratings,$post->ID);
							do_action('geodir_after_review_rating_stars_on_listview' , $post_avgratings , $post->ID);
						}
						?>
						<a href="<?php comments_link(); ?>" class="geodir-pcomments"><i class="fa fa-comments"></i>
						<?php geodir_comments_number( $post->rating_count ); ?></a>
					<?php 
					}
					geodir_favourite_html($post->post_author,$post->ID);
					
					global $wp_query ;
					
					$show_pin_point = $wp_query->is_main_query();
				 	if( !empty( $show_pin_point) && is_active_widget( false, "","geodir_map_v3_listing_map" ) ){ 
				 
						/*if($json_info = json_decode($post->marker_json))
							$marker_icon = $json_info->icon;*/
						
						$term_icon_url = get_tax_meta($post->default_category,'ct_cat_icon', false, $post->post_type);
						$marker_icon = isset($term_icon_url['src']) ? $term_icon_url['src'] : get_option('geodir_default_marker_icon');
				 ?>
								 <span class="geodir-pinpoint" style=" background:url('<?php if(isset($marker_icon)){ echo $marker_icon;}?>') no-repeat scroll left top transparent; background-size:auto 100%; -webkit-background-size:auto 100%; -moz-background-size:auto 100%; height:9px; width:14px; "></span><a class="geodir-pinpoint-link" href="javascript:void(0)" onclick="openMarker('listing_map_canvas' ,'<?php echo $post->ID; ?>')" onmouseover="animate_marker('listing_map_canvas' ,'<?php echo $post->ID; ?>')" onmouseout="stop_marker_animation('listing_map_canvas' ,'<?php echo $post->ID; ?>')" ><?php _e('Pinpoint',GEODIRECTORY_TEXTDOMAIN);?></a>
								 <?php } ?>
								 
								 <?php if( $post->post_author == get_current_user_id() ){ ?>
										<?php 
													$addplacelink = get_permalink( get_option('geodir_add_listing_page') );
													$editlink = geodir_getlink($addplacelink,array('pid'=>$post->ID),false);
													$upgradelink = geodir_getlink($editlink,array('upgrade'=>'1'),false);
													
													$ajaxlink = geodir_get_ajax_url();
													$deletelink = geodir_getlink($ajaxlink,array('geodir_ajax'=>'add_listing','ajax_action'=>'delete','pid'=>$post->ID),false);
													
											?>
											
											<span class="geodir-authorlink clearfix">
											
											<?php if(isset($_REQUEST['geodir_dashbord']) && $_REQUEST['geodir_dashbord']){
														
														do_action('geodir_before_edit_post_link_on_listing');
											?>
											
											<a href="<?php echo $editlink;?>" class="geodir-edit" title="<?php _e('Edit Listing',GEODIRECTORY_TEXTDOMAIN);?>"><?php _e('edit',GEODIRECTORY_TEXTDOMAIN);?></a> 
											<a href="<?php echo $deletelink;?>" class="geodir-delete" title="<?php _e('Delete Listing',GEODIRECTORY_TEXTDOMAIN);?>"><?php _e('delete',GEODIRECTORY_TEXTDOMAIN);?></a> 
											<?php 
													do_action('geodir_after_edit_post_link_on_listing');
											} ?>
											</span>
									 
								 <?php }?>
								 
							</div><!-- geodir-addinfo ends here-->
                            </footer><!-- .entry-meta -->
                         </article>
					</li>
    		
				<?php 
				endwhile; 
		
				do_action('geodir_after_listing_post_listview');
		
	else:
		
		if(isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite')
			echo '<li class="no-listing">'.__('No favorite listings found which match your selection.',GEODIRECTORY_TEXTDOMAIN).'</li>'; 
		else
			echo '<li class="no-listing">'.__('No listings found which match your selection.',GEODIRECTORY_TEXTDOMAIN).'</li>'; 
			 
	endif;

	?>
</ul>  <!-- geodir_category_list_view ends here-->

<div class="clear"></div>
<?php do_action('geodir_after_listing_listview');   
