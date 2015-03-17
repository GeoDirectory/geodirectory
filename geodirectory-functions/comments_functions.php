<?php 
// ADD THE COMMENTS META FIELDS TO THE COMMENTS ADMIN PAGE

add_filter( 'comment_row_actions', 'geodir_comment_meta_row_action', 11, 1 );
function geodir_comment_meta_row_action( $a ) {
	global $comment;
	
	$rating = geodir_get_commentoverall($comment->comment_ID);
	if($rating != 0){
		//echo '<div class="gd_rating_show" data-average="'.$rating.'" data-id="'.$comment->comment_ID.'"></div>';
		echo geodir_get_rating_stars($rating,$comment->comment_ID);
	}
	return $a;
}





add_action( 'add_meta_boxes_comment', 'geodir_comment_add_meta_box' );
function geodir_comment_add_meta_box($comment)
{ 
    add_meta_box( 'gd-comment-rating', __( 'Comment Rating',GEODIRECTORY_TEXTDOMAIN ), 'geodir_comment_rating_meta', 'comment', 'normal', 'high' );
}
 
function geodir_comment_rating_meta( $comment )
{	
	if($rating = geodir_get_commentoverall($comment->comment_ID)){
	
		echo '<div class="gd_rating" data-average="'.$rating.'" data-id="5"></div>
    		<input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="'.$rating.'"  />';
		
	
	}else{
		echo '<div class="gd_rating" data-average="0" data-id="5"></div>
    		<input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="0"  />';
	}		
}



add_action( 'comment_form_logged_in_after', 'geodir_comment_rating_fields' );
add_action( 'comment_form_before_fields', 'geodir_comment_rating_fields' );
function geodir_comment_rating_fields()
{	global $post;
	
	$post_types = geodir_get_posttypes();
	
if(in_array($post->post_type,$post_types)){
 	?><div class="gd_rating" data-average="0" data-id="5"></div>
    <input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="0"  /><?php 
}
}


add_filter('comment_reply_link', 'geodir_comment_replaylink');
function geodir_comment_replaylink($link){
	
	$link = '<div class="gd_comment_replaylink">'.$link.'</div>';
	
	return $link;
}

add_filter('cancel_comment_reply_link', 'geodir_cancle_replaylink');
function geodir_cancle_replaylink($link){
	
	$link = '<span id="gd_cancle_replaylink">'.$link.'</span>';
	
	return $link;
}

add_action('comment_post','geodir_save_rating');
function geodir_save_rating($comment = 0){
	global $wpdb, $user_ID, $post,$plugin_prefix;
	
	$comment_info = get_comment($comment);
	
	$post_id = $comment_info->comment_post_ID; 
	$status = $comment_info->comment_approved;
	$rating_ip = getenv("REMOTE_ADDR");	
	
	$post_details = $wpdb->get_row("SELECT * FROM ".$plugin_prefix.$post->post_type."_detail WHERE post_id =".$post->ID);

	if($post->post_status=='publish'){$post_status='1';}else{$post_status='0';}
	if(isset($_REQUEST['geodir_overallrating'])){
		
		$overall_rating = $_REQUEST['geodir_overallrating'];
		if ( isset( $comment_info->comment_parent ) && (int)$comment_info->comment_parent == 0 ) {
			$overall_rating = $overall_rating > 0 ? $overall_rating : '0';
						
			$sqlqry = $wpdb->prepare("INSERT INTO ".GEODIR_REVIEW_TABLE." SET
					post_id		= %d,
					post_type = %s,
					post_title	= %s,
					user_id		= %d,
					comment_id	= %d,
					rating_ip	= %s,
					overall_rating = %f,
					status		= %s,
					post_status		= %s, 
					post_date		= %s, 
					post_city		= %s, 
					post_region		= %s, 
					post_country	= %s 
					",
					array($post_id,$post->post_type,$post->post_title,$user_ID,$comment,$rating_ip,$overall_rating,$status,$post_status,date("Y-m-d H:i:s"),$post_details->post_city,$post_details->post_region,$post_details->post_country)
					);		
			
			$wpdb->query($sqlqry);
			
			do_action('geodir_after_save_comment', $_REQUEST, 'Comment Your Post');
			
			if($status){
				geodir_update_postrating($post_id);
			}
		}
	}

}

 

add_action('wp_set_comment_status','geodir_update_rating_status_change',10,2);
function geodir_update_rating_status_change($comment_id,$status){
	
	global $wpdb, $plugin_prefix, $user_ID;
	
	$comment_info = get_comment($comment_id);
	
	$post_id = isset($comment_info->comment_post_ID) ? $comment_info->comment_post_ID : '';
	
	if(!empty($comment_info))
		$status = $comment_info->comment_approved;
	
	if($status=='approve' || $status==1){$status=1;}else{$status=0;}
	
	$comment_info_ID = isset($comment_info->comment_ID) ? $comment_info->comment_ID : '';
	$old_rating = geodir_get_commentoverall($comment_info_ID);
	
	$post_type = get_post_type($post_id);
	
	$detail_table =  $plugin_prefix . $post_type . '_detail';
	
	if($comment_id){
		
		$overall_rating = $old_rating;
		
		if(isset($old_rating)){
			
			$sqlqry = $wpdb->prepare("UPDATE ".GEODIR_REVIEW_TABLE." SET
						overall_rating = %f,
						status		= %s 
						WHERE comment_id = %d ", array($overall_rating,$status,$comment_id));
			
			$wpdb->query($sqlqry);
			
			//update rating
			geodir_update_postrating($post_id,$post_type);
			
		}
		
	}
	
}



add_action('edit_comment','geodir_update_rating');
function geodir_update_rating($comment_id = 0){
	
	global $wpdb, $plugin_prefix, $user_ID;
	
	$comment_info = get_comment($comment_id);
	
	$post_id = $comment_info->comment_post_ID;
	$status = $comment_info->comment_approved;
	$old_rating = geodir_get_commentoverall($comment_info->comment_ID);
	
	$post_type = get_post_type($post_id);
	
	$detail_table =  $plugin_prefix . $post_type . '_detail';
	
	if(isset($_REQUEST['geodir_overallrating'])){
		
		$overall_rating = $_REQUEST['geodir_overallrating'];
		
		if ( isset( $comment_info->comment_parent ) && (int)$comment_info->comment_parent == 0 ) {
			$overall_rating = $overall_rating > 0 ? $overall_rating : '0';
			
			if(isset($old_rating)){
							
				$sqlqry = $wpdb->prepare("UPDATE ".GEODIR_REVIEW_TABLE." SET
						overall_rating = %f,
						status		= %s 
						WHERE comment_id = %d ", array($overall_rating,$status,$comment_id));		
			
				$wpdb->query($sqlqry);
				
				//update rating
				geodir_update_postrating($post_id,$post_type);
				 
			}
		}
	}
	

}

add_action( 'delete_comment', 'geodir_comment_delete_comment' );
function geodir_comment_delete_comment( $comment_id )
{ 
	global $wpdb;
	
	$review_info = geodir_get_review($comment_id);
	if($review_info){
		geodir_update_postrating($review_info->post_id);
	}	
	
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM ".GEODIR_REVIEW_TABLE." WHERE comment_id=%d",
			array($comment_id)
		)
	);
	
}

add_filter('comment_text', 'geodir_wrap_comment_text',10,2);
function geodir_wrap_comment_text($content,$comment=''){
		$rating = 0;
		if(!empty($comment))
			$rating = geodir_get_commentoverall($comment->comment_ID);
		if($rating != 0 && !is_admin()){
return '<div>'.__('Overall Rating',GEODIRECTORY_TEXTDOMAIN).': <div class="rating">'.$rating.'</div>'.geodir_get_rating_stars($rating,$comment->comment_ID).'</div><div class="description">'.$content.'</div>';
		}else
			return 	$content;
	
}


// $delete epreciated since ver 1.3.6
function geodir_update_postrating($post_id = 0, $post_type='' , $delete = false ){
	global $wpdb, $plugin_prefix, $comment;
	if(!$post_type){$post_type = get_post_type($post_id);}
	$detail_table =  $plugin_prefix . $post_type . '_detail';
	$post_newrating = geodir_get_post_rating($post_id,1);
	$post_newrating_count = geodir_get_review_count_total($post_id);
			
			
			//$post_newrating = ( (float)$post_oldrating - (float)$old_rating ) + (float)$overall_rating ;
		
			if ($wpdb->get_var("SHOW TABLES LIKE '".$detail_table."'") == $detail_table){
								
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE ".$detail_table." SET 
						overall_rating = %f,
						rating_count = %f
						where post_id = %d",
						array($post_newrating, $post_newrating_count ,$post_id)
					)
				);
				
				update_post_meta( $post_id, 'overall_rating', $post_newrating );
				update_post_meta( $post_id, 'rating_count', $post_newrating_count );
			}
	do_action('geodir_update_postrating');
	
}

function geodir_get_postoverall($post_id = 0){
	global $wpdb, $plugin_prefix;
	
	$post_type = get_post_type($post_id);
	$detail_table =  $plugin_prefix . $post_type . '_detail';
	
	if ( $wpdb->get_var("SHOW TABLES LIKE '".$detail_table."'") == $detail_table ){
		
		$post_ratings =	$wpdb->get_var(
											$wpdb->prepare(
												"SELECT overall_rating FROM ".$detail_table." WHERE post_id = %d",
												array($post_id) 
											)
										);
		
		
	}else{
		$post_ratings = get_post_meta( $post_id, 'overall_rating');
	} 
	
	if($post_ratings)
		return $post_ratings;
	else
		return false;	
}


function geodir_get_review($comment_id = 0){
	global $wpdb;
	
	$reatings =	$wpdb->get_row(
								$wpdb->prepare(
									"SELECT * FROM ".GEODIR_REVIEW_TABLE." WHERE comment_id = %d",
									array($comment_id)
								)
							);
	
	if(!empty($reatings))
		return $reatings; 
	else
		return false; 	
}

function geodir_get_review_total($post_id = 0){
	global $wpdb;
	
	$results =	$wpdb->get_var(
								$wpdb->prepare(
									"SELECT SUM(overall_rating) FROM ".GEODIR_REVIEW_TABLE." WHERE post_id = %d AND status=1 AND overall_rating>0",
									array($post_id)
								)
							);
	
	if(!empty($results))
		return $results; 
	else
		return false; 	
}

function geodir_get_review_count_by_user_id($user_id = 0){
	global $wpdb;
	$results =	$wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(overall_rating) FROM ".GEODIR_REVIEW_TABLE." WHERE user_id = %d AND status=1 AND overall_rating>0",
			array($user_id)
		)
	);

	if(!empty($results))
		return $results;
	else
		return false;
}

function geodir_get_post_rating($post_id = 0, $force_query = 0){
	global $wpdb,$post;
	
	if(isset($post->ID) && $post->ID == $post_id && !$force_query){
		if(isset($post->rating_count) && $post->rating_count>0 && isset($post->overall_rating) && $post->overall_rating>0){
		return $post->overall_rating;
		}else{
		return 0;
		}
	}
	
	$results =	$wpdb->get_var(
								$wpdb->prepare(
									"SELECT COALESCE(avg(overall_rating),0) FROM ".GEODIR_REVIEW_TABLE." WHERE post_id = %d AND status=1 AND overall_rating>0",
									array($post_id)
								)
							);
	
	if(!empty($results))
		return $results; 
	else
		return false; 	
}

function geodir_get_review_count_total($post_id = 0){
	global $wpdb;
	
	$results =	$wpdb->get_var(
								$wpdb->prepare(
									"SELECT COUNT(overall_rating) FROM ".GEODIR_REVIEW_TABLE." WHERE post_id = %d AND status=1 AND overall_rating>0",
									array($post_id)
								)
							);
	
	if(!empty($results))
		return $results; 
	else
		return false; 	
}

function geodir_get_comments_number($post_id = 0){
	global $wpdb;
	
	$results =	$wpdb->get_var(
								$wpdb->prepare(
									"SELECT COUNT(overall_rating) FROM ".GEODIR_REVIEW_TABLE." WHERE post_id = %d AND status=1 AND overall_rating>0",
									array($post_id)
									)
							);
	
	
	if(!empty($results))
		return $results; 
	else
		return false; 	
}

function geodir_get_commentoverall($comment_id = 0){
	global $wpdb;
	
	$reatings =	$wpdb->get_var(
								$wpdb->prepare(
									"SELECT overall_rating FROM ".GEODIR_REVIEW_TABLE." WHERE comment_id = %d",
									array($comment_id)
								)
							);
	
	if($reatings)
		return $reatings; 
	else
		return false; 	
}

// depreciated since ver 1.3.6
function geodir_get_commentoverall_number($post_id = 0){
	return geodir_get_post_rating($post_id);	
}


function geodir_comment_template( $comment_template ) {
     global $post;
		 
		 $post_types = geodir_get_posttypes();
		 
     if ( !( is_singular() && ( have_comments() || (isset($post->comment_status) && 'open' == $post->comment_status) ) ) ) {
        return;
     }
     if(in_array($post->post_type, $post_types)){ // assuming there is a post type called business
       return dirname(__FILE__) . '/reviews.php';
     }
}

add_filter( "comments_template", "geodir_comment_template" );


if ( ! function_exists( 'geodir_comment' ) ) {
function geodir_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class('geodir-comment'); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:',GEODIRECTORY_TEXTDOMAIN ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)',GEODIRECTORY_TEXTDOMAIN ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class('geodir-comment'); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment hreview">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="reviewer">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author',GEODIRECTORY_TEXTDOMAIN ) . '</span>' : ''
					);
					echo "<span class='item'><small><span class='fn'>$post->post_title</span></small></span>";
					printf( '<a href="%1$s"><time datetime="%2$s" class="dtreviewed">%3$s<span class="value-title" title="%2$s"></span></time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s',GEODIRECTORY_TEXTDOMAIN ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.',GEODIRECTORY_TEXTDOMAIN ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit',GEODIRECTORY_TEXTDOMAIN ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply',GEODIRECTORY_TEXTDOMAIN ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
}

 
 
#############################	 FUNCTION TO NOT LIST REPLIES AS REVIEWS
add_filter('get_comments_number', 'geodir_fix_comment_count', 10, 2);
if ( ! function_exists( 'geodir_fix_comment_count' ) ) {
function geodir_fix_comment_count( $count, $post_id) {
	if ( !is_admin() || strpos($_SERVER['REQUEST_URI'],'admin-ajax.php') ) {
		global $post;
		$post_types = geodir_get_posttypes();
		
		if (in_array(get_post_type( $post_id ), $post_types)) {
			$review_count = geodir_get_review_count_total($post_id);
			return $review_count;
			
			if ($post && isset($post->rating_count)) { 
				return $post->rating_count;
			} else {
				return geodir_get_comments_number($post_id);
			}
		} else {
			return $count;
		}
	} else {
		return $count;
	}
}
}

##############################	 END FUNCTION TO NOT LIST REPLIES AS REVIEWS


/**
 * HTML for rating stars
 */		 
function geodir_get_rating_stars($rating, $post_id, $small=false){
	$a_rating = $rating/5*100;
	
	if($small){
		$r_html = '<div class="rating"><div class="gd_rating_map" data-average="'.$rating.'" data-id="'.$post_id.'"><div class="geodir_RatingColor" ></div><div class="geodir_RatingAverage_small" style="width: '.$a_rating.'%;"></div><div class="geodir_Star_small"></div></div></div>';
		
	}else{
	
	//$rating_img = '<img src="'.geodir_plugin_url().'/geodirectory-assets/images/stars.png" />';
	$rating_img = '<img alt="rating icon" src="'.get_option('geodir_default_rating_star_icon').'" />';

	/* fix rating star for safari */
	$star_width = 23 * 5;
	global $is_safari, $is_iphone, $ios, $is_chrome;
	$attach_style = ( $is_safari || $is_iphone || $ios || $is_chrome ) && $star_width > 0 ? 'width:' . $star_width . 'px;max-width:none' : '';
	
	$r_html = '<div class="geodir-rating" style="' . $attach_style . '"><div class="gd_rating_show" data-average="'.$rating.'" data-id="'.$post_id.'"><div class="geodir_RatingAverage" style="width: '.$a_rating.'%;"></div><div class="geodir_Star">'.$rating_img.$rating_img.$rating_img.$rating_img.$rating_img.'</div></div></div>';
	}
	return $r_html;   
}

function geodir_is_reviews_show($pageview = ''){
	
	$active_tabs = get_option('geodir_detail_page_tabs_excluded');
	
	$is_display = true;
	if(!empty($active_tabs) && in_array('reviews', $active_tabs))
		$is_display = false;
		
	return apply_filters('geodir_is_reviews_show', $is_display, $pageview);
}