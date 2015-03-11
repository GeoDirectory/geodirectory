<?php
// =============================== Recent Comments Widget ======================================
class geodir_recent_reviews_widget extends WP_Widget {
	function geodir_recent_reviews_widget() {
	//Constructor
		$widget_ops = array('classname' => 'geodir_recent_reviews', 'description' =>__('GD > Recent Reviews',GEODIRECTORY_TEXTDOMAIN) );		
		$this->WP_Widget('geodir_recent_reviews',  __('GD > Recent Reviews',GEODIRECTORY_TEXTDOMAIN), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		// prints the widget
		extract($args, EXTR_SKIP );
		
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', __( $instance['title'], GEODIRECTORY_TEXTDOMAIN ) );
		$count = empty( $instance['count'] ) ? '5' : apply_filters( 'widget_count', $instance['count'] );

        $g_size = apply_filters('geodir_recent_reviews_g_size', 30);
		$comments_li = geodir_get_recent_reviews( $g_size, $count, 100, false );
		
		if( $comments_li ) {
			echo $before_widget;
			?>
			<div class="widget geodir_recent_reviews_section">
			<?php if( $title ) { echo $before_title . $title . $after_title; } ?>
			<ul class="geodir_recent_reviews"><?php echo $comments_li; ?></ul>
			</div>
			<?php 
			echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
 		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'count' => '' ) );		
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']);
 ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Widget Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('count'); ?>">Number of Reviews  <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></label></p>
<?php
	}}
	
register_widget('geodir_recent_reviews_widget');


