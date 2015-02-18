<?php 
function geodir_register_sidebar() {
	global $geodir_sidebars ;
	
	if ( function_exists( 'register_sidebar' ) ) {
		/*===========================*/
		/* Home page sidebars start*/
		/*===========================*/
		
		$before_widget = apply_filters( 'geodir_before_widget', '<section id="%1$s" class="widget geodir-widget %2$s">' );
		$after_widget = apply_filters( 'geodir_after_widget', '</section>' );
		$before_title = apply_filters( 'geodir_before_title', '<h3 class="widget-title">' );
		$after_title = apply_filters( 'geodir_after_title', '</h3>' );
		
		if( get_option( 'geodir_show_home_top_section' ) ) {
		register_sidebars(1,array('id'=> 'geodir_home_top','name' => __('GD Home Top Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_home_top' ;
		}
		
		if( get_option('geodir_show_home_contant_section') ) {
		register_sidebars(1,array('id'=> 'geodir_home_content','name' => __('GD Home Content Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_home_content' ;
		}
		
		if( get_option('geodir_show_home_right_section') ) {
		register_sidebars(1,array('id'=> 'geodir_home_right','name' => __('GD Home Right Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_home_right' ;
		}
		
		if( get_option('geodir_show_home_left_section') ) {
		register_sidebars(1,array('id'=> 'geodir_home_left','name' => __('GD Home Left Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_home_left' ;
		}
		
		if(get_option('geodir_show_home_bottom_section')) {
		register_sidebars(1,array('id'=> 'geodir_home_bottom','name' => __('GD Home Bottom Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_home_bottom' ;
		}
		
		/*===========================*/
		/* Home page sidebars end*/
		/*===========================*/
		
		/*===========================*/
		/* Listing page sidebars start*/
		/*===========================*/
		
		if(get_option('geodir_show_listing_top_section')) {
		register_sidebars(1,array('id'=> 'geodir_listing_top','name' => __('GD Listing Top Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_listing_top' ;
		}
		
		if( get_option('geodir_show_listing_left_section') ) {
		register_sidebars(1,array('id'=> 'geodir_listing_left_sidebar','name' => __('GD Listing Left Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_listing_left_sidebar' ;
		}
		
		if( get_option('geodir_show_listing_right_section') ) {
		register_sidebars(1,array('id'=> 'geodir_listing_right_sidebar','name' => __('GD Listing Right Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_listing_right_sidebar' ;
		}
		
		if(get_option('geodir_show_listing_bottom_section')) {
		register_sidebars(1,array('id'=> 'geodir_listing_bottom','name' => __('GD Listing Bottom Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_listing_bottom' ;
		}
		
		/*===========================*/
		/* Listing page sidebars start*/
		/*===========================*/
		
		/*===========================*/
		/* Search page sidebars start*/
		/*===========================*/
		
		if(get_option('geodir_show_search_top_section')) {
		register_sidebars(1,array('id'=> 'geodir_search_top','name' => __('GD Search Top Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_search_top' ;
		}
		
		if( get_option('geodir_show_search_left_section') ) {
		register_sidebars(1,array('id'=> 'geodir_search_left_sidebar','name' => __('GD Search Left Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_search_left_sidebar' ;
		}
		
		if( get_option('geodir_show_search_right_section') ) {
		register_sidebars(1,array('id'=> 'geodir_search_right_sidebar','name' => __('GD Search Right Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_search_right_sidebar' ;
		}
		
		if(get_option('geodir_show_search_bottom_section')) {
		register_sidebars(1,array('id'=> 'geodir_search_bottom','name' => __('GD Search Bottom Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_search_bottom' ;
		}
		
		/*===========================*/
		/* Search page sidebars end*/
		/*===========================*/
		
		/*==================================*/
		/* Detail/Single page sidebars start*/
		/*==================================*/
		if(get_option('geodir_show_detail_top_section')) {
		register_sidebars(1,array('id'=> 'geodir_detail_top','name' => __('GD Detail Top Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_detail_top' ;
		}
		
		register_sidebars(1,array('id'=> 'geodir_detail_sidebar','name' => __('GD Detail Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_detail_sidebar' ;
		
		if(get_option('geodir_show_detail_bottom_section')){
		register_sidebars(1,array('id'=> 'geodir_detail_bottom','name' => __('GD Detail Bottom Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_detail_bottom' ;
		}
		
		/*==================================*/
		/* Detail/Single page sidebars end*/
		/*==================================*/
		
		/*==================================*/
		/* Author page sidebars start       */
		/*==================================*/
		
		if(get_option('geodir_show_author_top_section')) { 
		register_sidebars(1,array('id'=> 'geodir_author_top','name' => __('GD Author Top Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_author_top' ;
		}
		
		if( get_option('geodir_show_author_left_section') ) {
		register_sidebars(1,array('id'=> 'geodir_author_left_sidebar','name' => __('GD Author Left Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_author_left_sidebar' ;
		}
		
		if( get_option('geodir_show_author_right_section') ) {
		register_sidebars(1,array('id'=> 'geodir_author_right_sidebar','name' => __('GD Author Right Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_author_right_sidebar' ;
		}
		
		if(get_option('geodir_show_author_bottom_section')) { 
		register_sidebars(1,array('id'=> 'geodir_author_bottom','name' => __('GD Author Bottom Section',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_author_bottom' ;
		}
		
		/*==================================*/
		/* Author page sidebars end         */
		/*==================================*/
		
		/*==================================*/
		/* Add listing page sidebars start       */
		/*==================================*/
		
		register_sidebars(1,array('id'=> 'geodir_add_listing_sidebar','name' => __('GD Add Listing Right Sidebar',GEODIRECTORY_TEXTDOMAIN),'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
		
		$geodir_sidebars[] ='geodir_add_listing_sidebar' ;
		
		/*==================================*/
		/* Add listing page sidebars end         */
		/*==================================*/
		
	}
} 


if( !function_exists( 'register_geodir_widgets' ) ) {
function register_geodir_widgets() {
	// =============================== Login Widget ======================================
	class geodir_loginwidget extends WP_Widget {
		function geodir_loginwidget() {
			//Constructor
			$widget_ops = array( 'classname' => 'geodir_loginbox', 'description' => __( 'Geodirectory Loginbox Widget', GEODIRECTORY_TEXTDOMAIN ) );		
			$this->WP_Widget( 'geodir_loginbox', __( 'GD > Loginbox',GEODIRECTORY_TEXTDOMAIN ), $widget_ops );
		}
		
		function widget( $args, $instance ) {
			// prints the widget
			extract( $args, EXTR_SKIP );
			$title = empty( $instance['title'] ) ? __( 'My Dashboard', GEODIRECTORY_TEXTDOMAIN ) : apply_filters( 'widget_title', __( $instance['title'],GEODIRECTORY_TEXTDOMAIN ) );
			
			echo $before_widget;
			echo $before_title.$title.$after_title;
			
			if( is_user_logged_in() ) {
			  	global $current_user;
			 	
				$login_url = geodir_getlink( home_url(), array( 'geodir_signup' => 'true' ), false );
			 	$add_listurl = get_permalink( get_option( 'geodir_add_listing_page' ) );
				$add_listurl = geodir_getlink( $add_listurl, array( 'listing_type' => 'gd_place' ) );
				$author_link = get_author_posts_url( $current_user->data->ID );
				$author_link = geodir_getlink( $author_link, array( 'geodir_dashbord' => 'true' ), false );
                
				echo '<ul class="geodir-loginbox-list">';
				ob_start();
				?>
				<li><a class="signin" href="<?php echo wp_logout_url( home_url() );?>"><?php _e( 'Logout', GEODIRECTORY_TEXTDOMAIN );?></a></li>
				<?php 
				$post_types = geodir_get_posttypes( 'object' );
				$show_add_listing_post_types_main_nav = get_option( 'geodir_add_listing_link_user_dashboard' );
				$geodir_allow_posttype_frontend = get_option( 'geodir_allow_posttype_frontend' );
				
				if( !empty( $show_add_listing_post_types_main_nav ) ) {
					$addlisting_links = '';
					foreach($post_types as $key => $postobj){
						
						if(in_array($key, $show_add_listing_post_types_main_nav)){
							
							if($add_link = geodir_get_addlisting_link( $key )){
								
								$name = $postobj->labels->name;
								
								$selected = '';
								if(geodir_get_current_posttype() == $key && geodir_is_page('add-listing')) 
									$selected = 'selected="selected"';
								
								// hook for add listing link
								$add_link = apply_filters( 'geodir_dashboard_link_add_listing', $add_link, $key, $current_user->ID );
								
								$addlisting_links .= '<option '.$selected.' value="'.$add_link.'">'.__( ucfirst( $name  ), GEODIRECTORY_TEXTDOMAIN ).'</option>';
								
							}
						}
						
					}	
					
					if($addlisting_links != ''){ ?>
					
						<li><select id="geodir_add_listing" class="chosen_select" onchange="window.location.href=this.value" option-autoredirect="1" name="geodir_add_listing" option-ajaxchosen="false" data-placeholder="<?php echo esc_attr( __( 'Add Listing', GEODIRECTORY_TEXTDOMAIN ) );?>">
						<option value=""></option>
						<?php echo $addlisting_links;?>
						</select></li> <?php 
						
					}
				
				}
				// My Favourites in Dashboard
				$show_favorite_link_user_dashboard = get_option( 'geodir_favorite_link_user_dashboard' );
				$user_favourite = geodir_user_favourite_listing_count();
								 
				if ( !empty( $show_favorite_link_user_dashboard ) && !empty( $user_favourite ) ) {
					$favourite_links = '';
					
					foreach ( $post_types as $key => $postobj ) {
						if( in_array( $key, $show_favorite_link_user_dashboard ) && array_key_exists( $key, $user_favourite ) ) {
							$name = $postobj->labels->name;
							$post_type_link = geodir_getlink( $author_link, array( 'stype' => $key, 'list' => 'favourite' ), false );
							
							$selected = '';
							
							if( isset( $_REQUEST['list'] ) && $_REQUEST['list'] == 'favourite' && isset( $_REQUEST['stype'] ) && $_REQUEST['stype'] == $key && isset( $_REQUEST['geodir_dashbord'] ) ) {
								$selected = 'selected="selected"';
							}
							// hook for favorite listing link
							$post_type_link = apply_filters( 'geodir_dashboard_link_favorite_listing', $post_type_link, $key, $current_user->ID );
								
							$favourite_links .= '<option ' . $selected . ' value="' . $post_type_link . '">' . __( ucfirst( $name  ), GEODIRECTORY_TEXTDOMAIN ) . '</option>';
						}
					}
					
					if( $favourite_links != '' ) {
					?>
						<li>
							<select id="geodir_my_favourites" class="chosen_select" onchange="window.location.href=this.value" option-autoredirect="1" name="geodir_my_favourites" option-ajaxchosen="false" data-placeholder="<?php echo esc_attr( __( 'My Favorites', GEODIRECTORY_TEXTDOMAIN ) );?>">
								<option value=""></option>
								<?php echo $favourite_links;?>
							</select>
						</li>
					<?php 
					}
				}
				
				
				$show_listing_link_user_dashboard = get_option('geodir_listing_link_user_dashboard');
				$user_listing = geodir_user_post_listing_count();
				
				if ( !empty( $show_listing_link_user_dashboard ) && !empty( $user_listing ) ) {
					$listing_links = '';
					
					foreach ( $post_types as $key => $postobj ) {
						if( in_array( $key, $show_listing_link_user_dashboard ) && array_key_exists( $key, $user_listing ) ) {
							$name = $postobj->labels->name;
							$listing_link = geodir_getlink( $author_link, array( 'stype' => $key ), false );
							
							$selected = '';
							if ( !isset( $_REQUEST['list'] ) && isset( $_REQUEST['geodir_dashbord'] ) && isset( $_REQUEST['stype'] ) && $_REQUEST['stype'] == $key ) {
								$selected = 'selected="selected"';
							}
							
							// hook for my listing link
							$listing_link = apply_filters( 'geodir_dashboard_link_my_listing', $listing_link, $key, $current_user->ID );
							
							$listing_links .= '<option ' . $selected . ' value="' . $listing_link.'">' . __( ucfirst( $name  ), GEODIRECTORY_TEXTDOMAIN ) . '</option>';
						}
					}
					
					if( $listing_links != '' ) {
					?>
						<li>
							<select id="geodir_my_listings" class="chosen_select" onchange="window.location.href=this.value" option-autoredirect="1" name="geodir_my_listings"  option-ajaxchosen="false" data-placeholder="<?php echo esc_attr( __( 'My Listings', GEODIRECTORY_TEXTDOMAIN ) );?>">
								<option value=""></option>
								<?php echo $listing_links;?>
							</select>
						</li>
					<?php
					}
				}
				
				$dashboard_link = ob_get_clean();
				
				echo apply_filters( 'geodir_dashboard_links', $dashboard_link );
				echo '</ul>';
			} else { 
			?>
                
				<form name="loginform" class="loginform1" action="<?php echo apply_filters( 'geodir_signup_reg_submit_link', home_url() . '/index.php?geodir_signup=true' ); ?>" method="post" >
					<div class="geodir_form_row"><input placeholder="<?php _e('Email', GEODIRECTORY_TEXTDOMAIN);?>" name="log" type="text" class="textfield user_login1" /> <span class="user_loginInfo"></span> </div>
					<div class="geodir_form_row"><input placeholder="<?php _e('Password', GEODIRECTORY_TEXTDOMAIN);?>" name="pwd" type="password" class="textfield user_pass1 input-text" /><span class="user_passInfo"></span>  </div>
					
					<input type="hidden" name="redirect_to" value="<?php echo geodir_curPageURL(); ?>" />
					<input type="hidden" name="testcookie" value="1" />
					<div class="geodir_form_row clearfix"><input type="submit" name="submit" value="<?php echo SIGN_IN_BUTTON;?>" class="b_signin"/><p class="geodir-new-forgot-link">   
                    <a href="<?php echo apply_filters( 'geodir_signup_reg_form_link', home_url() . '/?geodir_signup=true&page1=sign_up' ); ?>" class="goedir-newuser-link"><?php echo NEW_USER_TEXT;?></a>  
                    
                    <a href="<?php echo apply_filters( 'geodir_signup_forgot_form_link', home_url() . '/?geodir_signup=true&page1=sign_in' ); ?>"class="goedir-forgot-link"><?php echo FORGOT_PW_TEXT;?></a> </p> </div>
				 </form>           
				<?php }
				
				echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;		
			$instance['title'] = strip_tags($new_instance['title']);
			
			return $instance;
		}
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'desc1' => '' ) );		
			$title = strip_tags($instance['title']);
		
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title',GEODIRECTORY_TEXTDOMAIN);?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
            
		   
	<?php 
		}
	}
	register_widget('geodir_loginwidget');
	
	
	
		// =============================== GeoDirectory Social Like Widget ===================
	class geodir_social_like_widget extends WP_Widget { 
		
		
		function geodir_social_like_widget()
		{
			$widget_ops = array('classname' => 'geodir_social_like_widget', 'description' => __('GD > Twitter,Facebook and Google+ buttons',GEODIRECTORY_TEXTDOMAIN) );		
			$this->WP_Widget('social_like_widget', __('GD > Social Like',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
		}
		
		function widget($args, $instance)
		{
			// prints the widget
			extract($args, EXTR_SKIP);
			
			$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
			
			global $current_user,$post;
			echo $before_widget;
			?>
						
			<?php //if ( get_option('gd_tweet_button') ) { ?>
			
				<a href="http://twitter.com/share" class="twitter-share-button"><?php _e('Tweet',GEODIRECTORY_TEXTDOMAIN);?></a>
				
				<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script> 
			
			<?php //} ?>
			
			<?php // if ( get_option('gd_facebook_button') ) { ?>
			
				<iframe <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){echo 'allowtransparency="true"'; }?> class="facebook" src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(geodir_curPageURL()); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0"  style="border:none; overflow:hidden; width:100px; height:20px"></iframe> 
			
			
			<?php //} ?>
			
			<?php //if ( get_option('gd_google_button') ) { ?>
			
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
				{ parsetags: 'explicit' }
				</script>
				
				<div id="plusone-div"></div>
				<script type="text/javascript">gapi.plusone.render('plusone-div', {"size": "medium", "count": "true" });</script>                    
			<?php //} 
			echo $after_widget;
			
		}
		
		function update($new_instance, $old_instance) 
		{
			//save the widget
			$instance = $old_instance;		
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}
		
		function form($instance) 
		{
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );		
			$title = strip_tags($instance['title']);
		?>
		<p>No settings for this widget</p>
		
		
		<?php
		}
 }
	register_widget('geodir_social_like_widget');



	// ===============================GeoDirectory Feedburner Subscribe widget ============
	class geodirsubscribeWidget extends WP_Widget {
		
		
		function geodirsubscribeWidget()
		{
			//Constructor
			$widget_ops = array('classname' => 'geodir-subscribe', 'description' => __('GD > Google Feedburner Subscribe',GEODIRECTORY_TEXTDOMAIN) );		
			$this->WP_Widget('widget_subscribeWidget', __('GD > Subscribe',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
		}
		
		function widget($args, $instance) 
		{
			// prints the widget
			extract($args, EXTR_SKIP);
			
			$id = empty($instance['id']) ? '' : apply_filters('widget_id', $instance['id']);
			
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', __($instance['title'],GEODIRECTORY_TEXTDOMAIN));
			
			$text = empty($instance['text']) ? '' : apply_filters('widget_text', $instance['text']);
			
			echo $before_widget;
			?>
						
			<?php echo $before_title.$title; ?>  <a href="<?php if($id){echo 'http://feeds2.feedburner.com/'.$id;}else{bloginfo('rss_url');} ?>" ><i class="fa fa-rss-square"></i> </a><?php echo $after_title;?>
			
			<?php if ( $text <> "" ) { ?>	 
			
				 <p><?php echo $text; ?> </p>
			
			<?php } ?>
			
			<form class="geodir-subscribe-form"  action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow"  onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true"> 
			   
				<input type="text" class="field" onfocus="if (this.value == '<?php _e('Your Email Address',GEODIRECTORY_TEXTDOMAIN)?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Your Email Address',GEODIRECTORY_TEXTDOMAIN)?>';}" name="email" value="<?php _e('Your Email Address',GEODIRECTORY_TEXTDOMAIN)?>" />
				
				<input type="hidden" value="<?php echo $id; ?>" name="uri"/><input type="hidden" name="loc" value="en_US"/>
				
				<input class="btn_submit" type="submit" name="submit" value="Submit" /> 
				
			</form>
			
			<?php
			echo $after_widget;
			
		}
		
		function update($new_instance, $old_instance)
		{
		
			//save the widget
			$instance = $old_instance;		
			$instance['id'] = strip_tags($new_instance['id']);
			$instance['title'] = ($new_instance['title']);
			$instance['text'] = ($new_instance['text']);
	
			
			return $instance;
		}
		
		function form($instance)
		{
			//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '', 'advt1' => '','text' => '','twitter' => '','facebook' => '','digg' => '','myspace' => '' ) );		
			
			$id = strip_tags($instance['id']);
			
			$title = strip_tags($instance['title']);
			
			$text = strip_tags($instance['text']);
			
	
		
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title',GEODIRECTORY_TEXTDOMAIN);?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Feedburner ID (ex :- geotheme)',GEODIRECTORY_TEXTDOMAIN);?>: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Short Description',GEODIRECTORY_TEXTDOMAIN);?> <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($text); ?></textarea></label></p>
		<?php
		}
 }
	register_widget('geodirsubscribeWidget');

	// =============================== GeoDirectory Advt Widgets  =========================
	class geodiradvtwidget extends WP_Widget {
		
		function geodiradvtwidget()
		{
		//Constructor
			$widget_ops = array('classname' => 'GeoDirectory Advertise', 'description' => __('GD > common advertise widget in sidebar, bottom section',GEODIRECTORY_TEXTDOMAIN) );
			$this->WP_Widget('advtwidget', __('GD > Advertise',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
		}
		
		
		function widget($args, $instance)
		{
		
			// prints the widget
			
			extract($args, EXTR_SKIP);
			
			$desc1 = empty($instance['desc1']) ? '&nbsp;' : apply_filters('widget_desc1', $instance['desc1']);
			echo $before_widget;
		?>						
				<?php if ( $desc1 <> "" ) { ?>	
					<?php echo $desc1; ?> 
				<?php } 
				echo $after_widget;
		}
		
		function update($new_instance, $old_instance)
		{	
			//save the widget
			$instance = $old_instance;
			$instance['desc1'] = ($new_instance['desc1']);
			return $instance;
		}
		
		function form($instance)
		{
			//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'desc1' => '' ) );	
			
			$desc1 = ($instance['desc1']);
			?>
			<p><label for="<?php echo $this->get_field_id('desc1'); ?>"><?php _e('Your Advt code (ex.google adsense, etc.)',GEODIRECTORY_TEXTDOMAIN);?> <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('desc1'); ?>" name="<?php echo $this->get_field_name('desc1'); ?>"><?php echo esc_attr($desc1); ?></textarea></label></p>
		
		<?php
		}
 }
	register_widget('geodiradvtwidget');

	// =============================== GeoDirectory Flickr widget ========================
	class GeodirFlickrWidget extends WP_Widget {
		
		
		function GeodirFlickrWidget() {
			//Constructor
			$widget_ops = array('classname' => 'Geo Dir Flickr Photos ', 'description' => __('GD > Flickr Photos',GEODIRECTORY_TEXTDOMAIN) );
			$this->WP_Widget('widget_flickrwidget', __('GD > Flickr Photos',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
		}
		
		function widget($args, $instance)
		{
		
			// prints the widget
			extract($args, EXTR_SKIP);
			
			echo $before_widget;
			
			$id = empty($instance['id']) ? '&nbsp;' : apply_filters('widget_id', $instance['id']);
			
			$number = empty($instance['number']) ? '&nbsp;' : apply_filters('widget_number', $instance['number']);
			echo $before_title.__('Photo Gallery',GEODIRECTORY_TEXTDOMAIN).$after_title;
		?> 
		
			<div class="geodir-flickr clearfix">
			
				<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>
			
			</div>
		
		
		<?php echo $after_widget;
		}
		
		function update($new_instance, $old_instance) 
		{
			//save the widget
			$instance = $old_instance;
			$instance['id'] = strip_tags($new_instance['id']);
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
		
		function form($instance)
		{
		
			//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array('title' => '',  'id' => '', 'number' => '') );
			$id = strip_tags($instance['id']);
			$number = strip_tags($instance['number']);
		?>
		
			<p>
				<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID',GEODIRECTORY_TEXTDOMAIN);?> (<a href="http://www.idgettr.com">idGettr</a>):
					<input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
				</label>
			</p>
		
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos:',GEODIRECTORY_TEXTDOMAIN);?>
					<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
				</label>
			</p>
		<?php
		}
}
	register_widget('GeodirFlickrWidget');

	// ===============================GeoDirectory Twitter widget ========================
	
	// =============================== GeoDirectory Advt Widgets  =========================
	class geodir_twitter extends WP_Widget {
		
		function geodir_twitter()
		{
			//Constructor
			$widget_ops = array('classname' => 'Twitter', 'description' => __('GD > Twitter Feed',GEODIRECTORY_TEXTDOMAIN) );
			$this->WP_Widget('widget_Twidget', __('GD > Twitter',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
		}
		
		
		function widget($args, $instance)
		{
		
			// prints the widget
			
			extract($args, EXTR_SKIP);
			
			$desc1 = empty($instance['gd_tw_desc1']) ? '&nbsp;' : apply_filters('gd_tw_widget_desc1', $instance['gd_tw_desc1']);
			echo $before_widget;
		 if ( $desc1 <> "" ) { echo $desc1; } 
			echo $after_widget;
		}
		
		function update($new_instance, $old_instance)
		{	
			//save the widget
			$instance = $old_instance;
			$instance['gd_tw_desc1'] = ($new_instance['gd_tw_desc1']);
			return $instance;
		}
		
		function form($instance)
		{
			//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'gd_tw_desc1' => '' ) );	
			
			$desc1 = ($instance['gd_tw_desc1']);
			?>
			<p><label for="<?php echo $this->get_field_id('gd_tw_desc1'); ?>"><?php _e('Your twitter code (ex.google adsense, etc.)',GEODIRECTORY_TEXTDOMAIN);?> <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('gd_tw_desc1'); ?>" name="<?php echo $this->get_field_name('gd_tw_desc1'); ?>"><?php echo esc_attr($desc1); ?></textarea></label></p>
		
		<?php
		}
 }

	register_widget('geodir_twitter');


	
 

class geodir_advance_search_widget extends WP_Widget {

	function geodir_advance_search_widget()
	{
		//Constructor
		$widget_ops = array('classname' => 'geodir_advance_search_widget', 'description' => __('GD > Search',GEODIRECTORY_TEXTDOMAIN) );
		$this->WP_Widget('geodir_advance_search', __('GD > Search',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
	}
	
	
	function widget($args, $instance) 
	{
		
		// prints the widget
		extract($args, EXTR_SKIP);
		
		echo $before_widget;
		
		$title = empty($instance['title']) ? __('Search',GEODIRECTORY_TEXTDOMAIN) : apply_filters('widget_title', __($instance['title'],GEODIRECTORY_TEXTDOMAIN));
		
		geodir_get_template_part('listing','filter-form'); 
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		//save the widget
		//Nothing to save
		return isset($instance) ? $instance : '';
	}
	
	function form($instance) 
	{
		//widgetform in backend
		echo __("This is a search widget to show advance search for gedodirectory listings.",GEODIRECTORY_TEXTDOMAIN);
	} 
}
register_widget('geodir_advance_search_widget');	
	
	
	include_once ('geodirectory-widgets/geodirectory_popular_widget.php');
	include_once ('geodirectory-widgets/geodirectory_listing_slider_widget.php');
	include_once ('geodirectory-widgets/home_map_widget.php');
	include_once ( 'geodirectory-widgets/listing_map_widget.php');
	include_once ('geodirectory-widgets/geodirectory_reviews_widget.php');
	include_once ('geodirectory-widgets/geodirectory_related_listing_widget.php');
    include_once ('geodirectory-widgets/geodirectory_bestof_widget.php');
}

}


