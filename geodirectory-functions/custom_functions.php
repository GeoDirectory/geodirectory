<?php
/**
 * Custom functions
 *
 * @since   1.0.0
 * @package GeoDirectory
 */

/**
 * Front end listing view template selection.
 *
 * This function adds a drop down in front end listing page for selecting view template. Ex: list view, 2 column grid
 * view, etc.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @global object $gd_session GeoDirectory Session object.
 */
function geodir_list_view_select() {
	global $gd_session;
	?>
	<script type="text/javascript">
		function geodir_list_view_select(list) {
			//alert(listval);
			val = list.value;
			if (!val) {
				return;
			}

			var listSel = jQuery(list).parent().parent().next('.geodir_category_list_view');
			if (val != 1) {
				jQuery(listSel).children('li').addClass('geodir-gridview');
				jQuery(listSel).children('li').removeClass('geodir-listview');
			} else {
				jQuery(listSel).children('li').addClass('geodir-listview');
			}

			if (val == 1) {
				jQuery(listSel).children('li').removeClass('geodir-gridview gridview_onehalf gridview_onethird gridview_onefourth gridview_onefifth');
			}
			else if (val == 2) {
				jQuery(listSel).children('li').switchClass('gridview_onethird gridview_onefourth gridview_onefifth', 'gridview_onehalf', 600);
			}
			else if (val == 3) {
				jQuery(listSel).children('li').switchClass('gridview_onehalf gridview_onefourth gridview_onefifth', 'gridview_onethird', 600);
			}
			else if (val == 4) {
				jQuery(listSel).children('li').switchClass('gridview_onehalf gridview_onethird gridview_onefifth', 'gridview_onefourth', 600);
			}
			else if (val == 5) {
				jQuery(listSel).children('li').switchClass('gridview_onehalf gridview_onethird gridview_onefourth', 'gridview_onefifth', 600);
			}

			jQuery.post("<?php echo geodir_get_ajax_url();?>&gd_listing_view=" + val, function (data) {
				//alert(data );
			});
		}
	</script>
	<div class="geodir-list-view-select">
		<select name="gd_list_view" id="gd_list_view" onchange="geodir_list_view_select(this);">
			<?php $listing_view = (int) $gd_session->get( 'gd_listing_view' ); ?>
			<option value=""><?php _e( 'View:', 'geodirectory' ); ?></option>
			<option
				value="1" <?php selected( 1, $listing_view ); ?>><?php _e( 'View: List', 'geodirectory' ); ?></option>
			<option
				value="2" <?php selected( 2, $listing_view ); ?>><?php _e( 'View: Grid 2', 'geodirectory' ); ?></option>
			<option
				value="3" <?php selected( 3, $listing_view ); ?>><?php _e( 'View: Grid 3', 'geodirectory' ); ?></option>
			<option
				value="4" <?php selected( 4, $listing_view ); ?>><?php _e( 'View: Grid 4', 'geodirectory' ); ?></option>
			<option
				value="5" <?php selected( 5, $listing_view ); ?>><?php _e( 'View: Grid 5', 'geodirectory' ); ?></option>
		</select>
	</div>
	<?php
}

add_action( 'geodir_before_listing', 'geodir_list_view_select', 100 );

/**
 * Limit the listing excerpt.
 *
 * This function limits excerpt characters and display "read more" link.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string|int $charlength The character length.
 *
 * @global object $post          The current post object.
 * @return string The modified excerpt.
 */
function geodir_max_excerpt( $charlength ) {
	global $post;
	if ( $charlength == '0' ) {
		return;
	}
	$out = '';

	$temp_post = $post;
	$excerpt   = get_the_excerpt();

	$charlength ++;
	$excerpt_more = function_exists( 'geodirf_excerpt_more' ) ? geodirf_excerpt_more( '' ) : geodir_excerpt_more( '' );
	if ( geodir_utf8_strlen( $excerpt ) > $charlength ) {
		if ( geodir_utf8_strlen( $excerpt_more ) > 0 && geodir_utf8_strpos( $excerpt, $excerpt_more ) !== false ) {
			$excut = - ( geodir_utf8_strlen( $excerpt_more ) );
			$subex = geodir_utf8_substr( $excerpt, 0, $excut );
			if ( $charlength > 0 && geodir_utf8_strlen( $subex ) > $charlength ) {
				$subex = geodir_utf8_substr( $subex, 0, $charlength );
			}
			$out .= $subex;
		} else {
			$subex   = geodir_utf8_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = - ( geodir_utf8_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				$out .= geodir_utf8_substr( $subex, 0, $excut );
			} else {
				$out .= $subex;
			}
		}
		$out .= ' <a class="excerpt-read-more" href="' . get_permalink() . '" title="' . get_the_title() . '">';
		/**
		 * Filter excerpt read more text.
		 *
		 * @since 1.0.0
		 */
		$out .= apply_filters( 'geodir_max_excerpt_end', __( 'Read more [...]', 'geodirectory' ) );
		$out .= '</a>';

	} else {
		if ( geodir_utf8_strlen( $excerpt_more ) > 0 && geodir_utf8_strpos( $excerpt, $excerpt_more ) !== false ) {
			$excut = - ( geodir_utf8_strlen( $excerpt_more ) );
			$out .= geodir_utf8_substr( $excerpt, 0, $excut );
			$out .= ' <a class="excerpt-read-more" href="' . get_permalink() . '" title="' . get_the_title() . '">';
			/**
			 * Filter excerpt read more text.
			 *
			 * @since 1.0.0
			 */
			$out .= apply_filters( 'geodir_max_excerpt_end', __( 'Read more [...]', 'geodirectory' ) );
			$out .= '</a>';
		} else {
			$out .= $excerpt;
		}
	}
	$post = $temp_post;

	return $out;
}

/**
 * Returns package information as an objects.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param array $package_info Package info array.
 * @param object|string $post The post object.
 * @param string $post_type   The post type.
 *
 * @return object Returns filtered package info as an object.
 */
function geodir_post_package_info( $package_info, $post = '', $post_type = '' ) {
	$package_info['pid']              = 0;
	$package_info['days']             = 0;
	$package_info['amount']           = 0;
	$package_info['is_featured']      = 0;
	$package_info['image_limit']      = '';
	$package_info['google_analytics'] = 1;
	$package_info['sendtofriend']     = 1;

	/**
	 * Filter listing package info.
	 *
	 * @since 1.0.0
	 *
	 * @param array $package_info  {
	 *                             Attributes of the package_info.
	 *
	 * @type int $pid              Package ID. Default 0.
	 * @type int $days             Package validity in Days. Default 0.
	 * @type int $amount           Package amount. Default 0.
	 * @type int $is_featured      Is this featured package? Default 0.
	 * @type string $image_limit   Image limit for this package. Default "".
	 * @type int $google_analytics Add analytics to this package. Default 1.
	 * @type int $sendtofriend     Send to friend. Default 1.
	 *
	 * }
	 * @param object|string $post  The post object.
	 * @param string $post_type    The post type.
	 */
	return (object) apply_filters( 'geodir_post_package_info', $package_info, $post, $post_type );

}


/**
 * Send enquiry to listing author
 *
 * This function let the user to send Enquiry to listing author. If listing author email not available, then admin
 * email will be used. Email content will be used WP Admin -> Geodirectory -> Notifications -> Other Emails -> Email
 * enquiry
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @global object $wpdb    WordPress Database object.
 *
 * @param array $request   {
 *                         The submitted form fields as an array.
 *
 * @type string $sendact   Enquiry type. Default "send_inqury".
 * @type string $pid       Post ID.
 * @type string $inq_name  Sender name.
 * @type string $inq_email Sender mail.
 * @type string $inq_phone Sender phone.
 * @type string $inq_msg   Email message.
 *
 * }
 */
function geodir_send_inquiry( $request ) {
	global $wpdb;

	// strip slashes from text
	$request = ! empty( $request ) ? stripslashes_deep( $request ) : $request;

	$yourname      = $request['inq_name'];
	$youremail     = $request['inq_email'];
	$inq_phone     = $request['inq_phone'];
	$frnd_comments = $request['inq_msg'];
	$pid           = $request['pid'];

	$author_id  = '';
	$post_title = '';

	if ( $request['pid'] ) {

		$productinfosql = $wpdb->prepare(
			"select ID,post_author,post_title from $wpdb->posts where ID =%d",
			array( $request['pid'] )
		);
		$productinfo    = $wpdb->get_row( $productinfosql );

		$author_id  = $productinfo->post_author;
		$post_title = $productinfo->post_title;
	}

	$post_title = '<a href="' . get_permalink( $pid ) . '">' . $post_title . '</a>';

	$user_info = get_userdata( $author_id );
	$to_email  = geodir_get_post_meta( $pid, 'geodir_email', true );
	$to_name   = geodir_get_client_name( $author_id );

	if ( $to_email == '' ) {
		$to_email = get_option( 'admin_email' );
	}

	/**
	 * Called after the send enquiry var have been set but before the email has been sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request   {
	 *                         The submitted form fields as an array.
	 *
	 * @type string $sendact   Enquiry type. Default "send_inqury".
	 * @type string $pid       Post ID.
	 * @type string $inq_name  Sender name.
	 * @type string $inq_email Sender mail.
	 * @type string $inq_phone Sender phone.
	 * @type string $inq_msg   Email message.
	 *
	 * }
	 * @param string $type     The form type, default: `Enquiry`.
	 */
	do_action( 'geodir_after_send_enquiry', $request, 'Enquiry' );

	$client_message = $frnd_comments;
	$client_message .= '<br>' . __( 'From :', 'geodirectory' ) . ' ' . $yourname . '<br>' . __( 'Phone :', 'geodirectory' ) . ' ' . $inq_phone . '<br>' . __( 'Email :', 'geodirectory' ) . ' ' . $youremail . '<br><br>' . __( 'Sent from', 'geodirectory' ) . ' - <b><a href="' . trailingslashit( home_url() ) . '">' . get_option( 'blogname' ) . '</a></b>.';
	/**
	 * Filter client message text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $client_message Client message text.
	 */
	$client_message = apply_filters( 'geodir_inquiry_email_msg', $client_message );

	/**
	 * Called before the send enquiry email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request   {
	 *                         The submitted form fields as an array.
	 *
	 * @type string $sendact   Enquiry type. Default "send_inqury".
	 * @type string $pid       Post ID.
	 * @type string $inq_name  Sender name.
	 * @type string $inq_email Sender mail.
	 * @type string $inq_phone Sender phone.
	 * @type string $inq_msg   Email message.
	 *
	 * }
	 */
	do_action( 'geodir_before_send_enquiry_email', $request );
	if ( $to_email ) {
		// strip slashes message
		$client_message = stripslashes_deep( $client_message );

		geodir_sendEmail( $youremail, $yourname, $to_email, $to_name, '', $client_message, $extra = '', 'send_enquiry', $request['pid'] );//To client email
	}

	/**
	 * Called after the send enquiry email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request   {
	 *                         The submitted form fields as an array.
	 *
	 * @type string $sendact   Enquiry type. Default "send_inqury".
	 * @type string $pid       Post ID.
	 * @type string $inq_name  Sender name.
	 * @type string $inq_email Sender mail.
	 * @type string $inq_phone Sender phone.
	 * @type string $inq_msg   Email message.
	 *
	 * }
	 */
	do_action( 'geodir_after_send_enquiry_email', $request );
	$url = get_permalink( $pid );
	if ( strstr( $url, '?' ) ) {
		$url = $url . "&send_inquiry=success";
	} else {
		$url = $url . "?send_inquiry=success";
	}
	/**
	 * Filter redirect url after the send enquiry email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url Redirect url.
	 */
	$url = apply_filters( 'geodir_send_enquiry_after_submit_redirect', $url );
	wp_redirect( $url );
	gd_die();

}

/**
 * Send Email to a friend.
 *
 * This function let the user to send Email to a friend.
 * Email content will be used WP Admin -> Geodirectory -> Notifications -> Other Emails -> Send to friend
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param array $request       {
 *                             The submitted form fields as an array.
 *
 * @type string $sendact       Enquiry type. Default "email_frnd".
 * @type string $pid           Post ID.
 * @type string $to_name       Friend name.
 * @type string $to_email      Friend email.
 * @type string $yourname      Sender name.
 * @type string $youremail     Sender email.
 * @type string $frnd_subject  Email subject.
 * @type string $frnd_comments Email Message.
 *
 * }
 * @global object $wpdb        WordPress Database object.
 */
function geodir_send_friend( $request ) {
	global $wpdb;

	// strip slashes from text
	$request = ! empty( $request ) ? stripslashes_deep( $request ) : $request;

	$yourname      = $request['yourname'];
	$youremail     = $request['youremail'];
	$frnd_subject  = $request['frnd_subject'];
	$frnd_comments = $request['frnd_comments'];
	$pid           = $request['pid'];
	$to_email      = $request['to_email'];
	$to_name       = $request['to_name'];
	if ( $request['pid'] ) {
		$productinfosql = $wpdb->prepare(
			"select ID,post_title from $wpdb->posts where ID =%d",
			array( $request['pid'] )
		);
		$productinfo    = $wpdb->get_results( $productinfosql );
		foreach ( $productinfo as $productinfoObj ) {
			$post_title = $productinfoObj->post_title;
		}
	}

	/**
	 * Called before the send to friend email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request       {
	 *                             The submitted form fields as an array.
	 *
	 * @type string $sendact       Enquiry type. Default "email_frnd".
	 * @type string $pid           Post ID.
	 * @type string $to_name       Friend name.
	 * @type string $to_email      Friend email.
	 * @type string $yourname      Sender name.
	 * @type string $youremail     Sender email.
	 * @type string $frnd_subject  Email subject.
	 * @type string $frnd_comments Email Message.
	 *
	 * }
	 */
	do_action( 'geodir_before_send_to_friend_email', $request );
	geodir_sendEmail( $youremail, $yourname, $to_email, $to_name, $frnd_subject, $frnd_comments, $extra = '', 'send_friend', $request['pid'] );//To client email

	/**
	 * Called after the send to friend email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request       {
	 *                             The submitted form fields as an array.
	 *
	 * @type string $sendact       Enquiry type. Default "email_frnd".
	 * @type string $pid           Post ID.
	 * @type string $to_name       Friend name.
	 * @type string $to_email      Friend email.
	 * @type string $yourname      Sender name.
	 * @type string $youremail     Sender email.
	 * @type string $frnd_subject  Email subject.
	 * @type string $frnd_comments Email Message.
	 *
	 * }
	 */
	do_action( 'geodir_after_send_to_friend_email', $request );

	$url = get_permalink( $pid );
	if ( strstr( $url, '?' ) ) {
		$url = $url . "&sendtofrnd=success";
	} else {
		$url = $url . "?sendtofrnd=success";
	}
	/**
	 * Filter redirect url after the send to friend email is sent.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url Redirect url.
	 */
	$url = apply_filters( 'geodir_send_to_friend_after_submit_redirect', $url );
	wp_redirect( $url );
	gd_die();
}

/**
 * Adds open div before the tab content.
 *
 * This function adds open div before the tab content like post information, post images, reviews etc.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string $hash_key
 */
function geodir_before_tab_content( $hash_key ) {
	switch ( $hash_key ) {
		case 'post_info' :
			echo '<div class="geodir-company_info field-group">';
			break;
		case 'post_images' :
			/**
			 * Filter post gallery HTML id.
			 *
			 * @since 1.0.0
			 */
			echo ' <div id="' . apply_filters( 'geodir_post_gallery_id', 'geodir-post-gallery' ) . '" class="clearfix" >';
			break;
		case 'reviews' :
			echo '<div id="reviews-wrap" class="clearfix"> ';
			break;
		case 'post_video':
			echo ' <div id="post_video-wrap" class="clearfix">';
			break;
		case 'special_offers':
			echo '<div id="special_offers-wrap" class="clearfix">';
			break;
	}
}

/**
 * Adds closing div after the tab content.
 *
 * This function adds closing div after the tab content like post information, post images, reviews etc.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string $hash_key
 */
function geodir_after_tab_content( $hash_key ) {
	switch ( $hash_key ) {
		case 'post_info' :
			echo '</div>';
			break;
		case 'post_images' :
			echo '</div>';
			break;
		case 'reviews' :
			echo '</div>';
			break;
		case 'post_video':
			echo '</div>';
			break;
		case 'special_offers':
			echo '</div>';
			break;
	}
}


/**
 * Returns default sorting order of a post type.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string $post_type The post type.
 *
 * @global object $wpdb     WordPress Database object.
 * @return bool|null|string Returns default sort results, when the post type is valid. Otherwise returns false.
 */
function geodir_get_posts_default_sort( $post_type ) {

	global $wpdb;

	if ( $post_type != '' ) {

		$all_postypes = geodir_get_posttypes();

		if ( ! in_array( $post_type, $all_postypes ) ) {
			return false;
		}

		$sort_field_info = $wpdb->get_var( $wpdb->prepare( "select default_order from " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " where	post_type= %s and is_active=%d and is_default=%d", array(
			$post_type,
			1,
			1
		) ) );

		if ( ! empty( $sort_field_info ) ) {
			return $sort_field_info;
		}

	}

}


/**
 * Returns sort options of a post type.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string $post_type The post type.
 *
 * @global object $wpdb     WordPress Database object.
 * @return bool|mixed|void Returns sort results, when the post type is valid. Otherwise returns false.
 */
function geodir_get_sort_options( $post_type ) {
	global $wpdb;

	if ( $post_type != '' ) {
		$all_postypes = geodir_get_posttypes();

		if ( ! in_array( $post_type, $all_postypes ) ) {
			return false;
		}

		$sort_field_info = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " WHERE post_type=%s AND is_active=%d AND (sort_asc=1 || sort_desc=1 || field_type='random') AND field_type != 'address' ORDER BY sort_order ASC", array(
			$post_type,
			1
		) ) );

		/**
		 * Filter post sort options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $sort_field_info Unfiltered sort field array.
		 * @param string $post_type      Post type.
		 */
		return apply_filters( 'geodir_get_sort_options', $sort_field_info, $post_type );
	}

}


/**
 * Display list of sort options available in front end using dropdown.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 */
function geodir_display_sort_options() {
	global $wp_query;

	/**
	 * On search pages there should be no sort options, sorting is done by search criteria.
	 *
	 * @since 1.4.4
	 */
	if ( is_search() ) {
		return;
	}

	$sort_by = '';

	if ( isset( $_REQUEST['sort_by'] ) ) {
		$sort_by = $_REQUEST['sort_by'];
	}

	$gd_post_type = geodir_get_current_posttype();

	$sort_options = geodir_get_sort_options( $gd_post_type );


	$sort_field_options = '';

	if ( ! empty( $sort_options ) ) {
		foreach ( $sort_options as $sort ) {
			$sort = stripslashes_deep( $sort ); // strip slashes

			$label = __( $sort->site_title, 'geodirectory' );

			if ( $sort->field_type == 'random' ) {
				$key = $sort->field_type;
				( $sort_by == $key || ( $sort->is_default == '1' && ! isset( $_REQUEST['sort_by'] ) ) ) ? $selected = 'selected="selected"' : $selected = '';
				$sort_field_options .= '<option ' . $selected . ' value="' . esc_url( add_query_arg( 'sort_by', $key ) ) . '">' . __( $label, 'geodirectory' ) . '</option>';
			}

			if ( $sort->htmlvar_name == 'comment_count' ) {
				$sort->htmlvar_name = 'rating_count';
			}

			if ( $sort->sort_asc ) {
				$key   = $sort->htmlvar_name . '_asc';
				$label = $sort->site_title;
				if ( $sort->asc_title ) {
					$label = $sort->asc_title;
				}
				( $sort_by == $key || ( $sort->is_default == '1' && $sort->default_order == $key && ! isset( $_REQUEST['sort_by'] ) ) ) ? $selected = 'selected="selected"' : $selected = '';
				$sort_field_options .= '<option ' . $selected . ' value="' . esc_url( add_query_arg( 'sort_by', $key ) ) . '">' . __( $label, 'geodirectory' ) . '</option>';
			}

			if ( $sort->sort_desc ) {
				$key   = $sort->htmlvar_name . '_desc';
				$label = $sort->site_title;
				if ( $sort->desc_title ) {
					$label = $sort->desc_title;
				}
				( $sort_by == $key || ( $sort->is_default == '1' && $sort->default_order == $key && ! isset( $_REQUEST['sort_by'] ) ) ) ? $selected = 'selected="selected"' : $selected = '';
				$sort_field_options .= '<option ' . $selected . ' value="' . esc_url( add_query_arg( 'sort_by', $key ) ) . '">' . __( $label, 'geodirectory' ) . '</option>';
			}

		}
	}

	if ( $sort_field_options != '' ) {

		?>

		<div class="geodir-tax-sort">

			<select name="sort_by" id="sort_by" onchange="javascript:window.location=this.value;">

				<option
					value="<?php echo esc_url( add_query_arg( 'sort_by', '' ) ); ?>" <?php if ( $sort_by == '' ) {
					echo 'selected="selected"';
				} ?>><?php _e( 'Sort By', 'geodirectory' ); ?></option><?php

				echo $sort_field_options; ?>

			</select>

		</div>
		<?php

	}

}


/**
 * Removes the section title
 *
 * Removes the section title "Posts sort options", if the custom field type is multiselect or textarea or taxonomy.
 * Can be found here. WP Admin -> Geodirectory -> Place settings -> Custom fields
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param string $title      The section title.
 * @param string $field_type The field type.
 *
 * @return string Returns the section title.
 */
function geodir_advance_customfields_heading( $title, $field_type ) {

	if ( in_array( $field_type, array( 'multiselect', 'textarea', 'taxonomy' ) ) ) {
		$title = '';
	}

	return $title;
}


/**
 * Returns related listings of a listing.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param array $request            Related posts request array.
 *
 * @global object $wpdb             WordPress Database object.
 * @global object $post             The current post object.
 * @global string $gridview_columns The girdview style of the listings.
 * @global object $gd_session       GeoDirectory Session object.
 * @return string Returns related posts html.
 */
function geodir_related_posts_display( $request ) {
	if ( ! empty( $request ) ) {
		$before_title = ( isset( $request['before_title'] ) && ! empty( $request['before_title'] ) ) ? $request['before_title'] : '';
		$after_title  = ( isset( $request['after_title'] ) && ! empty( $request['after_title'] ) ) ? $request['after_title'] : '';

		$title               = ( isset( $request['title'] ) && ! empty( $request['title'] ) ) ? $request['title'] : __( 'Related Listings', 'geodirectory' );
		$post_number         = ( isset( $request['post_number'] ) && ! empty( $request['post_number'] ) ) ? $request['post_number'] : '5';
		$relate_to           = ( isset( $request['relate_to'] ) && ! empty( $request['relate_to'] ) ) ? $request['relate_to'] : 'category';
		$layout              = ( isset( $request['layout'] ) && ! empty( $request['layout'] ) ) ? $request['layout'] : 'gridview_onehalf';
		$add_location_filter = ( isset( $request['add_location_filter'] ) && ! empty( $request['add_location_filter'] ) ) ? $request['add_location_filter'] : '0';
		$listing_width       = ( isset( $request['listing_width'] ) && ! empty( $request['listing_width'] ) ) ? $request['listing_width'] : '';
		$list_sort           = ( isset( $request['list_sort'] ) && ! empty( $request['list_sort'] ) ) ? $request['list_sort'] : 'latest';
		$character_count     = ( isset( $request['character_count'] ) && ! empty( $request['character_count'] ) ) ? $request['character_count'] : '';

		global $wpdb, $post, $gd_session, $related_nearest, $related_parent_lat, $related_parent_lon;
		$related_parent_lat   = !empty($post->post_latitude) ? $post->post_latitude : '';
		$related_parent_lon   = !empty($post->post_longitude) ? $post->post_longitude : '';
		$arr_detail_page_tabs = geodir_detail_page_tabs_list();

		$related_listing_array = array();
		if ( get_option( 'geodir_add_related_listing_posttypes' ) ) {
			$related_listing_array = get_option( 'geodir_add_related_listing_posttypes' );
		}
		if ( isset($post->post_type) && in_array( $post->post_type, $related_listing_array ) ) {
			$arr_detail_page_tabs['related_listing']['is_display'] = true;
		}

		$is_display        = $arr_detail_page_tabs['related_listing']['is_display'];
		$origi_post        = $post;
		$post_type         = '';
		$post_id           = '';
		$category_taxonomy = '';
		$tax_field         = 'id';
		$category          = array();

		if ( isset( $_REQUEST['backandedit'] ) ) {
			$post      = (object) $gd_session->get( 'listing' );
			$post_type = $post->listing_type;
			if ( isset( $_REQUEST['pid'] ) && $_REQUEST['pid'] != '' ) {
				$post_id = $_REQUEST['pid'];
			}
		} elseif ( isset( $_REQUEST['pid'] ) && $_REQUEST['pid'] != '' ) {
			$post      = geodir_get_post_info( $_REQUEST['pid'] );
			$post_type = $post->post_type;
			$post_id   = $_REQUEST['pid'];
		} elseif ( isset( $post->post_type ) && $post->post_type != '' ) {
			$post_type = $post->post_type;
			$post_id   = $post->ID;
		}

		if ( $relate_to == 'category' ) {

			$category_taxonomy = $post_type . $relate_to;
			if ( isset( $post->{$category_taxonomy} ) && $post->{$category_taxonomy} != '' ) {
				$category = explode( ',', trim( $post->{$category_taxonomy}, ',' ) );
			}

		} elseif ( $relate_to == 'tags' ) {

			$category_taxonomy = $post_type . '_' . $relate_to;
			if ( $post->post_tags != '' ) {
				$category = explode( ',', trim( $post->post_tags, ',' ) );
			}
			$tax_field = 'name';
		}

		/* --- return false in invalid request --- */
		if ( empty( $category ) ) {
			return false;
		}

		$all_postypes = geodir_get_posttypes();

		if ( ! in_array( $post_type, $all_postypes ) ) {
			return false;
		}

		/* --- return false in invalid request --- */

		$location_url = '';
		if ( $add_location_filter != '0' ) {
			$location_url             = array();
			$geodir_show_location_url = get_option( 'geodir_show_location_url' );

			$gd_city = get_query_var( 'gd_city' );

			if ( $gd_city ) {
				$gd_country = get_query_var( 'gd_country' );
				$gd_region  = get_query_var( 'gd_region' );
			} else {
				$location = geodir_get_default_location();

				$gd_country = isset( $location->country_slug ) ? $location->country_slug : '';
				$gd_region  = isset( $location->region_slug ) ? $location->region_slug : '';
				$gd_city    = isset( $location->city_slug ) ? $location->city_slug : '';
			}

			if ( $geodir_show_location_url == 'all' ) {
				$location_url[] = $gd_country;
				$location_url[] = $gd_region;
			} else if ( $geodir_show_location_url == 'country_city' ) {
				$location_url[] = $gd_country;
			} else if ( $geodir_show_location_url == 'region_city' ) {
				$location_url[] = $gd_region;
			}

			$location_url[] = $gd_city;

			$location_url = implode( '/', $location_url );
		}


		if ( ! empty( $category ) ) {
			global $geodir_add_location_url;
			$geodir_add_location_url = '0';
			if ( $add_location_filter != '0' ) {
				$geodir_add_location_url = '1';
			}
			$viewall_url             = get_term_link( (int) $category[0], $post_type . $category_taxonomy );
			$geodir_add_location_url = null;
		}
		ob_start();
		?>


		<div class="geodir_locations geodir_location_listing">

			<?php
			if ( isset( $request['is_widget'] ) && $request['is_widget'] == '1' ) {
				/** geodir_before_title filter Documented in geodirectory_widgets.php */
				$before_title = isset( $before_title ) ? $before_title : apply_filters( 'geodir_before_title', '<h3 class="widget-title">' );
				/** geodir_after_title filter Documented in geodirectory_widgets.php */
				$after_title = isset( $after_title ) ? $after_title : apply_filters( 'geodir_after_title', '</h3>' );
				?>
				<div class="location_list_heading clearfix">
					<?php echo $before_title . $title . $after_title; ?>
				</div>
				<?php
			}
			$query_args = array(
				'posts_per_page'   => $post_number,
				'is_geodir_loop'   => true,
				'gd_location'      => ( $add_location_filter ) ? true : false,
				'post_type'        => $post_type,
				'order_by'         => $list_sort,
				'post__not_in'     => array( $post_id ),
				'excerpt_length'   => $character_count,
				'related_listings' => $is_display
			);

			$tax_query = array(
				'taxonomy' => $category_taxonomy,
				'field'    => $tax_field,
				'terms'    => $category
			);

			$query_args['tax_query'] = array( $tax_query );

			global $gridview_columns, $post;

			/**
			 * Filters related listing query args.
			 *
			 * @since 1.6.11
			 *
			 * @param array $query_args The query array.
			 * @param array $request Related posts request array.
			 */
			$query_args = apply_filters( 'geodir_related_posts_widget_query_args', $query_args, $request );

			query_posts( $query_args );

			if ( strstr( $layout, 'gridview' ) ) {
				$listing_view_exp = explode( '_', $layout );
				$gridview_columns = $layout;
				$layout           = $listing_view_exp[0];
			} else if ( $layout == 'list' ) {
				$gridview_columns = '';
			}
			$related_posts = true;

			$related_nearest = false;
			if ( $list_sort == 'nearest' ) {
				$related_nearest = true;
			}


			/**
			 * Filters related listing listview template.
			 *
			 * @since 1.0.0
			 */
			$template = apply_filters( "geodir_template_part-related-listing-listview", geodir_locate_template( 'listing-listview' ) );

			/**
			 * Includes related listing listview template.
			 *
			 * @since 1.0.0
			 */
			include( $template );

			wp_reset_query();
			$post            = $origi_post;
			$related_nearest = false;
			?>

		</div>
		<?php
		return $html = ob_get_clean();

	}

}


//add_action('wp_footer', 'geodir_category_count_script', 10);
/**
 * Adds the category post count javascript code
 *
 * @since       1.0.0
 * @package     GeoDirectory
 * @global string $geodir_post_category_str The geodirectory post category.
 * @depreciated No longer needed.
 */
function geodir_category_count_script() {
	global $geodir_post_category_str;

	if ( ! empty( $geodir_post_category_str ) ) {
		$geodir_post_category_str = serialize( $geodir_post_category_str );
	}

	$all_var['post_category_array'] = html_entity_decode( (string) $geodir_post_category_str, ENT_QUOTES, 'UTF-8' );
	$script                         = "var post_category_array = " . json_encode( $all_var ) . ';';
	echo '<script>';
	echo $script;
	echo '</script>';

}

/**
 * Returns the default language of the map.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @return string Returns the default language.
 */
function geodir_get_map_default_language() {
	$geodir_default_map_language = get_option( 'geodir_default_map_language' );
	if ( empty( $geodir_default_map_language ) ) {
		$geodir_default_map_language = 'en';
	}

	/**
	 * Filter default map language.
	 *
	 * @since 1.0.0
	 *
	 * @param string $geodir_default_map_language Default map language.
	 */
	return apply_filters( 'geodir_default_map_language', $geodir_default_map_language );
}

/**
 * Returns the Google maps api key.
 *
 * @since   1.6.4
 * @package GeoDirectory
 * @return string Returns the api key.
 */
function geodir_get_map_api_key() {
	$key = get_option( 'geodir_google_api_key' );

	/**
	 * Filter Google maps api key.
	 *
	 * @since 1.6.4
	 *
	 * @param string $key Google maps api key.
	 */
	return apply_filters( 'geodir_google_api_key', $key );
}


/**
 * Adds meta keywords and description for SEO.
 *
 * @since   1.0.0
 * @since   1.5.4 Modified to replace %location% from meta when Yoast SEO plugin active.
 * @since   1.6.18 Option added to disable overwrite by Yoast SEO titles & metas on GD pages.
 * @package GeoDirectory
 * @global object $wpdb             WordPress Database object.
 * @global object $post             The current post object.
 * @global object $wp_query         WordPress Query object.
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 */
function geodir_add_meta_keywords() {
	global $wp, $post, $wp_query, $wpdb, $geodir_addon_list;

	$is_geodir_page = geodir_is_geodir_page();
	if ( ! $is_geodir_page ) {
		return;
	}// if non GD page, bail

	$use_gd_meta = true;
	if ( ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'All_in_One_SEO_Pack' ) ) && !geodir_disable_yoast_seo_metas() ) {
		$use_gd_meta = false;

		if ( geodir_is_page( 'search' ) ) {
			$use_gd_meta = true;
		}
	}

	if ( ! $use_gd_meta ) {
		return;
	}// bail if Yoast Wordpress SEO or All_in_One_SEO_Pack active.

	$current_term = $wp_query->get_queried_object();

	$all_postypes = geodir_get_posttypes();

	$geodir_taxonomies = geodir_get_taxonomies( '', true );

	$meta_desc = '';
	$meta_key  = '';
	if ( isset( $current_term->ID ) && $current_term->ID == geodir_location_page_id() ) {
		/**
		 * Filter SEO meta location description.
		 *
		 * @since 1.0.0
		 */
		$meta_desc = apply_filters( 'geodir_seo_meta_location_description', '' );
		$meta_desc .= '';
	}
	if ( have_posts() && is_single() OR is_page() ) {
		while ( have_posts() ) {
			the_post();

			if ( has_excerpt() ) {
				$out_excerpt = strip_tags( strip_shortcodes( get_the_excerpt() ) );
				if ( empty( $out_excerpt ) ) {
					$out_excerpt = strip_tags( do_shortcode( get_the_excerpt() ) );
				}
				$out_excerpt = str_replace( array( "\r\n", "\r", "\n" ), "", $out_excerpt );
			} else {
				$out_excerpt = str_replace( array( "\r\n", "\r", "\n" ), "", $post->post_content );
				$out_excerpt = strip_tags( strip_shortcodes( $out_excerpt ) );
				if ( empty( $out_excerpt ) ) {
					$out_excerpt = strip_tags( do_shortcode( $out_excerpt ) ); // parse short code from content
				}
				$out_excerpt = trim( wp_trim_words( $out_excerpt, 35, '' ), '.!?,;:-' );
			}

			$meta_desc .= $out_excerpt;
		}
	} elseif ( ( is_category() || is_tag() ) && isset( $current_term->taxonomy ) && in_array( $current_term->taxonomy, $geodir_taxonomies ) ) {
		if ( is_category() ) {
			$meta_desc .= __( "Posts related to Category:", 'geodirectory' ) . " " . geodir_utf8_ucfirst( single_cat_title( "", false ) );
		} elseif ( is_tag() ) {
			$meta_desc .= __( "Posts related to Tag:", 'geodirectory' ) . " " . geodir_utf8_ucfirst( single_tag_title( "", false ) );
		}
	} elseif ( isset( $current_term->taxonomy ) && in_array( $current_term->taxonomy, $geodir_taxonomies ) ) {
		$meta_desc .= isset( $current_term->description ) ? $current_term->description : '';
	}


	$geodir_post_type       = geodir_get_current_posttype();
	$geodir_post_type_info  = get_post_type_object( $geodir_post_type );
	$geodir_is_page_listing = geodir_is_page( 'listing' ) ? true : false;

	$category_taxonomy = geodir_get_taxonomies( $geodir_post_type );
	$tag_taxonomy      = geodir_get_taxonomies( $geodir_post_type, true );

	$geodir_is_category = isset( $category_taxonomy[0] ) && get_query_var( $category_taxonomy[0] ) ? get_query_var( $category_taxonomy[0] ) : false;
	$geodir_is_tag      = isset( $tag_taxonomy[0] ) && get_query_var( $tag_taxonomy[0] ) ? true : false;

	$geodir_is_search        = geodir_is_page( 'search' ) ? true : false;
	$geodir_is_location      = geodir_is_page( 'location' ) ? true : false;
	$geodir_location_manager = isset( $geodir_addon_list['geodir_location_manager'] ) && $geodir_addon_list['geodir_location_manager'] = 'yes' ? true : false;
	$godir_location_terms    = geodir_get_current_location_terms( 'query_vars' );
	$gd_city                 = $geodir_location_manager && isset( $godir_location_terms['gd_city'] ) ? $godir_location_terms['gd_city'] : null;
	$gd_region               = $geodir_location_manager && isset( $godir_location_terms['gd_region'] ) ? $godir_location_terms['gd_region'] : null;
	$gd_country              = $geodir_location_manager && isset( $godir_location_terms['gd_country'] ) ? $godir_location_terms['gd_country'] : null;
	/**
	 * Filter the Everywhere text in location description.
	 *
	 * @since 1.6.22
	 * 
	 * @param string $replace_location Everywhere text.
	 */
	$replace_location        = apply_filters( 'geodir_location_description_everywhere_text', __( 'Everywhere', 'geodirectory' ) );
	$location_id             = null;
	if ( $geodir_location_manager ) {
		$sql           = $wpdb->prepare( "SELECT location_id FROM " . POST_LOCATION_TABLE . " WHERE city_slug=%s ORDER BY location_id ASC LIMIT 1", array( $gd_city ) );
		$location_id   = (int) $wpdb->get_var( $sql );
		$location_type = geodir_what_is_current_location();
		if ( $location_type == 'city' ) {
			$replace_location = geodir_get_current_location( array( 'what' => 'city', 'echo' => false ) );
		} elseif ( $location_type == 'region' ) {
			$replace_location = geodir_get_current_location( array( 'what' => 'region', 'echo' => false ) );
		} elseif ( $location_type == 'country' ) {
			$replace_location = geodir_get_current_location( array( 'what' => 'country', 'echo' => false ) );
			$replace_location = __( $replace_location, 'geodirectory' );
		}
		$country          = get_query_var( 'gd_country' );
		$region           = get_query_var( 'gd_region' );
		$city             = get_query_var( 'gd_city' );
		$current_location = '';
		if ( $country != '' ) {
			$current_location = get_actual_location_name( 'country', $country, true );
		}
		if ( $region != '' ) {
			$current_location = get_actual_location_name( 'region', $region );
		}
		if ( $city != '' ) {
			$current_location = get_actual_location_name( 'city', $city );
		}
		$replace_location = $current_location != '' ? $current_location : $replace_location;
	}

	$geodir_meta_keys = '';
	$geodir_meta_desc = '';
	if ( $is_geodir_page && ! empty( $geodir_post_type_info ) ) {
		if ( $geodir_is_page_listing || $geodir_is_search || geodir_is_page( 'add-listing' ) ) {
			$geodir_meta_keys = isset( $geodir_post_type_info->seo['meta_keyword'] ) && $geodir_post_type_info->seo['meta_keyword'] != '' ? $geodir_post_type_info->seo['meta_keyword'] : $geodir_meta_keys;

			$geodir_meta_desc = isset( $geodir_post_type_info->description ) ? $geodir_post_type_info->description : $geodir_meta_desc;
			$geodir_meta_desc = isset( $geodir_post_type_info->seo['meta_description'] ) && $geodir_post_type_info->seo['meta_description'] != '' ? $geodir_post_type_info->seo['meta_description'] : $geodir_meta_desc;

			if ( $geodir_is_category ) {
				$category = $geodir_is_category ? get_term_by( 'slug', $geodir_is_category, $category_taxonomy[0] ) : null;
				if ( isset( $category->term_id ) && ! empty( $category->term_id ) ) {
					$category_id   = $category->term_id;
					$category_desc = trim( $category->description ) != '' ? trim( $category->description ) : geodir_get_tax_meta( $category_id, 'ct_cat_top_desc', false, $geodir_post_type );
					if ( $location_id ) {
						$option_name    = 'geodir_cat_loc_' . $geodir_post_type . '_' . $category_id;
						$cat_loc_option = get_option( $option_name );

						$gd_cat_loc_default = ! empty( $cat_loc_option ) && isset( $cat_loc_option['gd_cat_loc_default'] ) && $cat_loc_option['gd_cat_loc_default'] > 0 ? true : false;
						if ( ! $gd_cat_loc_default ) {
							$option_name   = 'geodir_cat_loc_' . $geodir_post_type . '_' . $category_id . '_' . $location_id;
							$option        = get_option( $option_name );
							$category_desc = isset( $option['gd_cat_loc_desc'] ) && trim( $option['gd_cat_loc_desc'] ) != '' ? trim( $option['gd_cat_loc_desc'] ) : $category_desc;
						}
					}
					$geodir_meta_desc = __( "Posts related to Category:", 'geodirectory' ) . " " . geodir_utf8_ucfirst( single_cat_title( "", false ) ) . '. ' . $category_desc;
				}
			} else if ( $geodir_is_tag ) {
				$geodir_meta_desc = __( "Posts related to Tag:", 'geodirectory' ) . " " . geodir_utf8_ucfirst( single_tag_title( "", false ) ) . '. ' . $geodir_meta_desc;
			}
		}
	}


	$gd_page = '';
	if ( geodir_is_page( 'home' ) ) {
		$gd_page   = 'home';
		$meta_desc = ( get_option( 'geodir_meta_desc_homepage' ) ) ? get_option( 'geodir_meta_desc_homepage' ) : $meta_desc;
	} elseif ( geodir_is_page( 'detail' ) ) {
		$gd_page   = 'detail';
		$meta_desc = ( get_option( 'geodir_meta_desc_detail' ) ) ? get_option( 'geodir_meta_desc_detail' ) : $meta_desc;
	} elseif ( geodir_is_page( 'pt' ) ) {
		$gd_page   = 'pt';
		$meta_desc = ( get_option( 'geodir_meta_desc_pt' ) ) ? get_option( 'geodir_meta_desc_pt' ) : $meta_desc;
	} elseif ( geodir_is_page( 'listing' ) ) {
		$gd_page   = 'listing';
		$meta_desc = ( get_option( 'geodir_meta_desc_listing' ) ) ? get_option( 'geodir_meta_desc_listing' ) : $meta_desc;
	} elseif ( geodir_is_page( 'location' ) ) {
		$gd_page   = 'location';
		$meta_desc = ( get_option( 'geodir_meta_desc_location' ) ) ? get_option( 'geodir_meta_desc_location' ) : $meta_desc;
		$meta_desc = apply_filters( 'geodir_seo_meta_location_description', $meta_desc );

	} elseif ( geodir_is_page( 'search' ) ) {
		$gd_page   = 'search';
		$meta_desc = ( get_option( 'geodir_meta_desc_search' ) ) ? get_option( 'geodir_meta_desc_search' ) : $meta_desc;
	} elseif ( geodir_is_page( 'add-listing' ) ) {
		$gd_page   = 'add-listing';
		$meta_desc = ( get_option( 'geodir_meta_desc_add-listing' ) ) ? get_option( 'geodir_meta_desc_add-listing' ) : $meta_desc;
	} elseif ( geodir_is_page( 'author' ) ) {
		$gd_page   = 'author';
		$meta_desc = ( get_option( 'geodir_meta_desc_author' ) ) ? get_option( 'geodir_meta_desc_author' ) : $meta_desc;
	} elseif ( geodir_is_page( 'login' ) ) {
		$gd_page   = 'login';
		$meta_desc = ( get_option( 'geodir_meta_desc_login' ) ) ? get_option( 'geodir_meta_desc_login' ) : $meta_desc;
	} elseif ( geodir_is_page( 'listing-success' ) ) {
		$gd_page   = 'listing-success';
		$meta_desc = ( get_option( 'geodir_meta_desc_listing-success' ) ) ? get_option( 'geodir_meta_desc_listing-success' ) : $meta_desc;
	}


	if ( $meta_desc ) {
		$meta_desc = stripslashes_deep( $meta_desc );
		/**
		 * Filter page description to replace variables.
		 *
		 * @since 1.5.4
		 *
		 * @param string $title   The page description including variables.
		 * @param string $gd_page The GeoDirectory page type if any.
		 */
		$meta_desc = apply_filters( 'geodir_seo_meta_description_pre', __( $meta_desc, 'geodirectory' ), $gd_page, '' );

		/**
		 * Filter SEO meta description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $meta_desc Meta description content.
		 */
		echo apply_filters( 'geodir_seo_meta_description', '<meta name="description" content="' . $meta_desc . '" />', $meta_desc );
	}

	// meta keywords
	if ( isset( $post->post_type ) && in_array( $post->post_type, $all_postypes ) ) {
		$place_tags = wp_get_post_terms( $post->ID, $post->post_type . '_tags', array( "fields" => "names" ) );
		$place_cats = wp_get_post_terms( $post->ID, $post->post_type . 'category', array( "fields" => "names" ) );

		$meta_key .= implode( ", ", array_merge( (array) $place_cats, (array) $place_tags ) );
	} else {
		$posttags = get_the_tags();
		if ( $posttags ) {
			foreach ( $posttags as $tag ) {
				$meta_key .= $tag->name . ' ';
			}
		} else {
			$tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) );
			$xt   = 1;

			foreach ( $tags as $tag ) {
				if ( $xt <= 20 ) {
					$meta_key .= $tag->name . ", ";
				}

				$xt ++;
			}
		}
	}

	$meta_key         = $meta_key != '' ? rtrim( trim( $meta_key ), "," ) : $meta_key;
	$geodir_meta_keys = $geodir_meta_keys != '' ? ( $meta_key != '' ? $meta_key . ', ' . $geodir_meta_keys : $geodir_meta_keys ) : $meta_key;
	if ( $geodir_meta_keys != '' ) {
		$geodir_meta_keys = strip_tags( $geodir_meta_keys );
		$geodir_meta_keys = esc_html( $geodir_meta_keys );
		$geodir_meta_keys = geodir_strtolower( $geodir_meta_keys );
		$geodir_meta_keys = wp_html_excerpt( $geodir_meta_keys, 1000, '' );
		$geodir_meta_keys = str_replace( '%location%', $replace_location, $geodir_meta_keys );

		$meta_key = rtrim( trim( $geodir_meta_keys ), "," );
	}

	if ( $meta_key ) {
		$meta_key = stripslashes_deep( $meta_key );
		/**
		 * Filter SEO meta keywords.
		 *
		 * @since 1.0.0
		 *
		 * @param string $meta_desc Meta keywords.
		 */
		echo apply_filters( 'geodir_seo_meta_keywords', '<meta name="keywords" content="' . $meta_key . '" />', $meta_key );
	}

}

/**
 * Returns list of options available for "Detail page tab settings"
 *
 * Options will be populated here. WP Admin -> Geodirectory -> Design -> Detail -> Detail page tab settings -> Exclude
 * selected tabs from detail page
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @return array returns list of options available as an array.
 */
function geodir_detail_page_tabs_key_value_array() {
	$geodir_detail_page_tabs_key_value_array = array();

	$geodir_detail_page_tabs_array = geodir_detail_page_tabs_array();

	foreach ( $geodir_detail_page_tabs_array as $key => $tabs_obj ) {
		$geodir_detail_page_tabs_key_value_array[ $key ] = $tabs_obj['heading_text'];
	}

	return $geodir_detail_page_tabs_key_value_array;
}

/**
 * Build and return the list of available tabs as an array.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @return mixed|array Tabs array.
 */
function geodir_detail_page_tabs_array() {

	$arr_tabs = array();
	/**
	 * Filter detail page tab display.
	 *
	 * @since 1.0.0
	 */
	$arr_tabs['post_profile'] = array(
		'heading_text'  => __( 'Profile', 'geodirectory' ),
		'is_active_tab' => true,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'post_profile' ),
		'tab_content'   => ''
	);
	$arr_tabs['post_info']    = array(
		'heading_text'  => __( 'More Info', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'post_info' ),
		'tab_content'   => ''
	);

	$arr_tabs['post_images'] = array(
		'heading_text'  => __( 'Photos', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'post_images' ),
		'tab_content'   => ''
	);

	$arr_tabs['post_video'] = array(
		'heading_text'  => __( 'Video', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'post_video' ),
		'tab_content'   => ''
	);

	$arr_tabs['special_offers'] = array(
		'heading_text'  => __( 'Special Offers', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'special_offers' ),
		'tab_content'   => ''
	);

	$arr_tabs['post_map'] = array(
		'heading_text'  => __( 'Map', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'post_map' ),
		'tab_content'   => ''
	);

	$arr_tabs['reviews'] = array(
		'heading_text'  => __( 'Reviews', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'reviews' ),
		'tab_content'   => 'review display'
	);

	$arr_tabs['related_listing'] = array(
		'heading_text'  => __( 'Related Listing', 'geodirectory' ),
		'is_active_tab' => false,
		'is_display'    => apply_filters( 'geodir_detail_page_tab_is_display', true, 'related_listing' ),
		'tab_content'   => ''
	);

	/**
	 * Filter the tabs array.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'geodir_detail_page_tab_list_extend', $arr_tabs );


}


/**
 * Returns the list of tabs available as an array.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @return mixed|array Tabs array.
 */
function geodir_detail_page_tabs_list() {
	$tabs_excluded = get_option( 'geodir_detail_page_tabs_excluded' );
	$tabs_array    = geodir_detail_page_tabs_array();

	if ( ! empty( $tabs_excluded ) ) {
		foreach ( $tabs_excluded as $tab ) {
			if ( array_key_exists( $tab, $tabs_array ) ) {
				unset( $tabs_array[ $tab ] );
			}
		}
	}

	return $tabs_array;
}


/**
 * The main function responsible for displaying tabs in frontend detail page.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @global object $post                      The current post object.
 * @global array $post_images                List of images attached to the post.
 * @global string $video                     The video embed content.
 * @global string $special_offers            Special offers content.
 * @global string $related_listing           Related listing html.
 * @global string $geodir_post_detail_fields Detail field html.
 */
function geodir_show_detail_page_tabs() {
	global $post, $post_images, $video, $special_offers, $related_listing, $geodir_post_detail_fields, $preview;

	$post_id            = ! empty( $post ) && isset( $post->ID ) ? (int) $post->ID : 0;
	$request_post_id    = ! empty( $_REQUEST['p'] ) ? (int) $_REQUEST['p'] : 0;
	$is_backend_preview = ( is_single() && ! empty( $_REQUEST['post_type'] ) && ! empty( $_REQUEST['preview'] ) && ! empty( $_REQUEST['p'] ) ) && is_super_admin() ? true : false; // skip if preview from backend

	if ( $is_backend_preview && ! $post_id > 0 && $request_post_id > 0 ) {
		$post = geodir_get_post_info( $request_post_id );
		setup_postdata( $post );
	}

	$geodir_post_detail_fields = geodir_show_listing_info( 'moreinfo' );

	$package_info = geodir_post_package_info(array(), $post, (!empty($post->post_type) ? $post->post_type : ''));
	$image_limit = '';
	if (defined('GEODIRPAYMENT_VERSION') && !empty($package_info) && isset($package_info->image_limit) && $package_info->image_limit !== '') {
		$image_limit = (int)$package_info->image_limit;
	}

	if ( geodir_is_page( 'detail' ) ) {
		$video                 = geodir_get_video( $post->ID );
		$special_offers        = geodir_get_special_offers( $post->ID );
		$related_listing_array = array();
		if ( get_option( 'geodir_add_related_listing_posttypes' ) ) {
			$related_listing_array = get_option( 'geodir_add_related_listing_posttypes' );
		}

		$excluded_tabs = get_option( 'geodir_detail_page_tabs_excluded' );
		if ( ! $excluded_tabs ) {
			$excluded_tabs = array();
		}

		$related_listing = '';
		if ( in_array( $post->post_type, $related_listing_array ) && ! in_array( 'related_listing', $excluded_tabs ) ) {
			$request = array(
				'post_number'         => get_option( 'geodir_related_post_count' ),
				'relate_to'           => get_option( 'geodir_related_post_relate_to' ),
				'layout'              => get_option( 'geodir_related_post_listing_view' ),
				'add_location_filter' => get_option( 'geodir_related_post_location_filter' ),
				'list_sort'           => get_option( 'geodir_related_post_sortby' ),
				'character_count'     => get_option( 'geodir_related_post_excerpt' )
			);

			if ( $post->post_type == 'gd_event' && defined( 'GDEVENTS_VERSION' ) ) {
				$related_listing = geodir_get_detail_page_related_events( $request );
			} else {
				$related_listing = geodir_related_posts_display( $request );
			}

		}

		$post_images = geodir_get_images( $post->ID, 'thumbnail' );
		$thumb_image = '';
		if ( ! empty( $post_images ) ) {
			$count = 1;
			foreach ( $post_images as $image ) {
				if ($image_limit !== '' && $count > $image_limit) {
					 break;
				}
				$caption = ( ! empty( $image->caption ) ) ? $image->caption : '';
				$thumb_image .= '<a href="' . $image->src . '" title="' . $caption . '">';
				$thumb_image .= geodir_show_image( $image, 'thumbnail', true, false );
				$thumb_image .= '</a>';
				$count++;
			}
		}

		$map_args                    = array();
		$map_args['map_canvas_name'] = 'detail_page_map_canvas';
		$map_args['width']           = '600';
		$map_args['height']          = '300';
		if ( $post->post_mapzoom ) {
			$map_args['zoom'] = '' . $post->post_mapzoom . '';
		}
		$map_args['autozoom']                 = false;
		$map_args['scrollwheel']              = ( get_option( 'geodir_add_listing_mouse_scroll' ) ) ? 0 : 1;
		$map_args['child_collapse']           = '0';
		$map_args['enable_cat_filters']       = false;
		$map_args['enable_text_search']       = false;
		$map_args['enable_post_type_filters'] = false;
		$map_args['enable_location_filters']  = false;
		$map_args['enable_jason_on_load']     = true;
		$map_args['enable_map_direction']     = true;
		$map_args['map_class_name']           = 'geodir-map-detail-page';
		$map_args['maptype']                  = ( ! empty( $post->post_mapview ) ) ? $post->post_mapview : 'ROADMAP';
	} else if ( geodir_is_page( 'preview' ) ) {
		$video          = isset( $post->geodir_video ) ? $post->geodir_video : '';
		$special_offers = isset( $post->geodir_special_offers ) ? $post->geodir_special_offers : '';

		if ( isset( $post->post_images ) ) {
			$post->post_images = trim( $post->post_images, "," );
		}

		if ( isset( $post->post_images ) && ! empty( $post->post_images ) ) {
			$post_images = explode( ",", $post->post_images );
		}

		$thumb_image = '';
		if ( ! empty( $post_images ) ) {
			$count = 1;
			foreach ( $post_images as $image ) {
				if ( $image != '' ) {
					if ($image_limit !== '' && $count > $image_limit) {
						 break;
					}
					$thumb_image .= '<a href="' . $image . '">';
					$thumb_image .= geodir_show_image( array( 'src' => $image ), 'thumbnail', true, false );
					$thumb_image .= '</a>';
					$count++;
				}
			}
		}

		global $map_jason;
		$marker_json      = $post->marker_json != '' ? json_decode( $post->marker_json, true ) : array();
		$marker_icon      = ( ! empty( $marker_json ) && ! empty( $marker_json['i'] ) ) ? $marker_json['i'] : '';
		$icon_size        = geodir_get_marker_size( $marker_icon );
		$marker_json['w'] = $icon_size['w'];
		$marker_json['h'] = $icon_size['h'];
		$map_jason[]      = json_encode( $marker_json );

		$address_latitude  = isset( $post->post_latitude ) ? $post->post_latitude : '';
		$address_longitude = isset( $post->post_longitude ) ? $post->post_longitude : '';
		$mapview           = isset( $post->post_mapview ) ? $post->post_mapview : '';
		$mapzoom           = isset( $post->post_mapzoom ) ? $post->post_mapzoom : '';
		if ( ! $mapzoom ) {
			$mapzoom = 12;
		}

		$map_args                             = array();
		$map_args['map_canvas_name']          = 'preview_map_canvas';
		$map_args['width']                    = '950';
		$map_args['height']                   = '300';
		$map_args['child_collapse']           = '0';
		$map_args['maptype']                  = $mapview;
		$map_args['autozoom']                 = false;
		$map_args['zoom']                     = "$mapzoom";
		$map_args['latitude']                 = $address_latitude;
		$map_args['longitude']                = $address_longitude;
		$map_args['enable_cat_filters']       = false;
		$map_args['enable_text_search']       = false;
		$map_args['enable_post_type_filters'] = false;
		$map_args['enable_location_filters']  = false;
		$map_args['enable_jason_on_load']     = true;
		$map_args['enable_map_direction']     = true;
		$map_args['map_class_name']           = 'geodir-map-preview-page';
	}

	$arr_detail_page_tabs = geodir_detail_page_tabs_list();// get this sooner so we can get the active tab for the user

	$active_tab       = '';
	$active_tab_name  = '';
	$default_tab      = '';
	$default_tab_name = '';
	foreach ( $arr_detail_page_tabs as $tab_index => $tabs ) {
		if ( isset( $tabs['is_active_tab'] ) && $tabs['is_active_tab'] && ! empty( $tabs['is_display'] ) && isset( $tabs['heading_text'] ) && $tabs['heading_text'] ) {
			$active_tab      = $tab_index;
			$active_tab_name = __( $tabs['heading_text'], 'geodirectory' );
		}

		if ( $default_tab === '' && ! empty( $tabs['is_display'] ) && ! empty( $tabs['heading_text'] ) ) {
			$default_tab      = $tab_index;
			$default_tab_name = __( $tabs['heading_text'], 'geodirectory' );
		}
	}

	if ( $active_tab === '' && $default_tab !== '' ) { // Make first tab as a active tab if not any tab is active.
		if ( isset( $arr_detail_page_tabs[ $active_tab ] ) && isset( $arr_detail_page_tabs[ $active_tab ]['is_active_tab'] ) ) {
			$arr_detail_page_tabs[ $active_tab ]['is_active_tab'] = false;
		}

		$arr_detail_page_tabs[ $default_tab ]['is_active_tab'] = true;
		$active_tab                                            = $default_tab;
		$active_tab_name                                       = $default_tab_name;
	}
	$tab_list = ( get_option( 'geodir_disable_tabs', false ) ) ? true : false;
	?>
	<div class="geodir-tabs" id="gd-tabs" style="position:relative;">
		<?php if ( ! $tab_list ){ ?>
		<div id="geodir-tab-mobile-menu">
			<i class="fa fa-bars"></i>
			<span class="geodir-mobile-active-tab"><?php echo $active_tab_name; ?></span>
			<i class="fa fa-sort-desc"></i>
		</div>
		<dl class="geodir-tab-head">
			<?php
			}
			/**
			 * Called before the details page tab list headings, inside the `dl` tag.
			 *
			 * @since 1.0.0
			 * @see   'geodir_after_tab_list'
			 */
			do_action( 'geodir_before_tab_list' ); ?>
			<?php

			foreach ( $arr_detail_page_tabs as $tab_index => $detail_page_tab ) {
				if ( $detail_page_tab['is_display'] ) {

					if ( ! $tab_list ) {
						?>
						<dt></dt> <!-- added to comply with validation -->
						<dd <?php if ( $detail_page_tab['is_active_tab'] ){ ?>class="geodir-tab-active"<?php } ?> ><a
								data-tab="#<?php echo $tab_index; ?>"
								data-status="enable"><?php _e( $detail_page_tab['heading_text'], 'geodirectory' ); ?></a>
						</dd>
						<?php
					}
					ob_start() // start tab content buffering
					?>
					<li id="<?php echo $tab_index; ?>Tab">
						<?php if ( $tab_list ) {
							$tab_title = '<span class="gd-tab-list-title" ><a href="#' . $tab_index . '">' . __( $detail_page_tab['heading_text'], 'geodirectory' ) . '</a></span><hr />';
							/**
							 * Filter the tab list title html.
							 *
							 * @since 1.6.1
							 *
							 * @param string $tab_title      The html for the tab title.
							 * @param string $tab_index      The tab index type.
							 * @param array $detail_page_tab The array of values including title text.
							 */
							echo apply_filters( 'geodir_tab_list_title', $tab_title, $tab_index, $detail_page_tab );
						} ?>
						<div id="<?php echo $tab_index; ?>" class="hash-offset"></div>
						<?php
						/**
						 * Called before the details tab content is output per tab.
						 *
						 * @since 1.0.0
						 *
						 * @param string $tab_index The tab name ID.
						 */
						do_action( 'geodir_before_tab_content', $tab_index );

						/**
						 * Called before the details tab content is output per tab.
						 *
						 * Uses dynamic hook name: geodir_before_$tab_index_tab_content
						 *
						 * @since 1.0.0
						 * @todo  do we need this if we have the hook above? 'geodir_before_tab_content'
						 */
						do_action( 'geodir_before_' . $tab_index . '_tab_content' );
						/// write a code to generate content of each tab
						switch ( $tab_index ) {
							case 'post_profile':
								/**
								 * Called before the listing description content on the details page tab.
								 *
								 * @since 1.0.0
								 */
								do_action( 'geodir_before_description_on_listing_detail' );
								if ( geodir_is_page( 'detail' ) ) {
									the_content();
								} else {
									/** This action is documented in geodirectory_template_actions.php */
									echo apply_filters( 'the_content', stripslashes( $post->post_desc ) );
								}

								/**
								 * Called after the listing description content on the details page tab.
								 *
								 * @since 1.0.0
								 */
								do_action( 'geodir_after_description_on_listing_detail' );
								break;
							case 'post_info':
								echo $geodir_post_detail_fields;
								break;
							case 'post_images':
								echo $thumb_image;
								break;
							case 'post_video':
								// some browsers hide $_POST data if used for embeds so we replace with a placeholder
								if ( $preview ) {
									if ( $video ) {
										echo "<span class='gd-video-embed-preview' ><p class='gd-video-preview-text'><i class=\"fa fa-video-camera\" aria-hidden=\"true\"></i><br />" . __( 'Video Preview Placeholder', 'geodirectory' ) . "</p></span>";
									}
								} else {

									// stop payment manager filtering content length
									$filter_priority = has_filter( 'the_content', 'geodir_payments_the_content' );
									if ( false !== $filter_priority ) {
										remove_filter( 'the_content', 'geodir_payments_the_content', $filter_priority );
									}

									/** This action is documented in geodirectory_template_actions.php */
									echo apply_filters( 'the_content', stripslashes( $video ) );// we apply the_content filter so oembed works also;

									if ( false !== $filter_priority ) {
										add_filter( 'the_content', 'geodir_payments_the_content', $filter_priority );
									}
								}
								break;
							case 'special_offers':
								echo apply_filters( 'gd_special_offers_content', wpautop( stripslashes( $special_offers ) ) );

								break;
							case 'post_map':
								geodir_draw_map( $map_args );
								break;
							case 'reviews':
								comments_template();
								break;
							case 'related_listing':
								echo $related_listing;
								break;
							default: {
								if ( ( isset( $post->{$tab_index} ) || ( ! isset( $post->{$tab_index} ) && ( strpos( $tab_index, 'gd_tab_' ) !== false || $tab_index == 'link_business' ) ) ) && ! empty( $detail_page_tab['tab_content'] ) ) {
									echo $detail_page_tab['tab_content'];
								}
							}
								break;
						}

						/**
						 * Called after the details tab content is output per tab.
						 *
						 * @since 1.0.0
						 */
						do_action( 'geodir_after_tab_content', $tab_index );

						/**
						 * Called after the details tab content is output per tab.
						 *
						 * Uses dynamic hook name: geodir_after_$tab_index_tab_content
						 *
						 * @since 1.0.0
						 * @todo  do we need this if we have the hook above? 'geodir_after_tab_content'
						 */
						do_action( 'geodir_after_' . $tab_index . '_tab_content' );
						?> </li>
					<?php
					/**
					 * Filter the current tab content.
					 *
					 * @since 1.0.0
					 */
					$arr_detail_page_tabs[ $tab_index ]['tab_content'] = apply_filters( "geodir_modify_" . $detail_page_tab['tab_content'] . "_tab_content", ob_get_clean() );
				} // end of if for is_display
			}// end of foreach

			/**
			 * Called after the details page tab list headings, inside the `dl` tag.
			 *
			 * @since 1.0.0
			 * @see   'geodir_before_tab_list'
			 */
			do_action( 'geodir_after_tab_list' );
			?>
			<?php if ( ! $tab_list ){ ?></dl><?php } ?>
		<ul class="geodir-tabs-content entry-content <?php if ( $tab_list ) { ?>geodir-tabs-list<?php } ?>"
		    style="position:relative;">
			<?php
			foreach ( $arr_detail_page_tabs as $detail_page_tab ) {
				if ( $detail_page_tab['is_display'] && ! empty( $detail_page_tab['tab_content'] ) ) {
					echo $detail_page_tab['tab_content'];
				}// end of if
			}// end of foreach

			/**
			 * Called after all the tab content is output in `li` tags, called before the closing `ul` tag.
			 *
			 * @since 1.0.0
			 */
			do_action( 'geodir_add_tab_content' ); ?>
		</ul>
		<!--gd-tabs-content ul end-->
	</div>
	<?php if ( ! $tab_list ) { ?>
		<script>
			if (window.location.hash && window.location.hash.indexOf('&') === -1 && jQuery(window.location.hash + 'Tab').length) {
				hashVal = window.location.hash;
			} else {
				hashVal = jQuery('dl.geodir-tab-head dd.geodir-tab-active').find('a').attr('data-tab');
			}
			jQuery('dl.geodir-tab-head dd').each(function () {
				//Get all tabs
				var tabs = jQuery(this).children('dd');
				var tab = '';
				tab = jQuery(this).find('a').attr('data-tab');
				if (hashVal != tab) {
					jQuery(tab + 'Tab').hide();
				}

			});
		</script>
		<?php
	}
}

/**
 * Fixes image orientation.
 *
 * @since   1.0.0
 * @package GeoDirectory
 *
 * @param array $file The image file.
 *
 * @return mixed Image file.
 */
function geodir_exif( $file ) {
	if ( empty( $file ) || ! is_array( $file ) ) {
		return $file;
	}

	$file_path = ! empty( $file['tmp_name'] ) ? sanitize_text_field( $file['tmp_name'] ) : '';
	if ( ! ( $file_path && file_exists( $file_path ) ) ) {
		return $file;
	}
	$file['file'] = $file_path;

	if ( ! file_is_valid_image( $file_path ) ) {
		return $file; // Bail if file is not an image.
	}

	if ( ! function_exists( 'wp_get_image_editor' ) ) {
		return $file;
	}

	$mime_type = $file['type'];
	$exif      = array();
	if ( $mime_type == 'image/jpeg' && function_exists( 'exif_read_data' ) ) {
		try {
			$exif = exif_read_data( $file_path );
		} catch ( Exception $e ) {
			$exif = array();
		}
	}

	$rotate      = false;
	$flip        = false;
	$modify      = false;
	$orientation = 0;
	if ( ! empty( $exif ) && isset( $exif['Orientation'] ) ) {
		switch ( (int) $exif['Orientation'] ) {
			case 1:
				// do nothing
				break;
			case 2:
				$flip   = array( false, true );
				$modify = true;
				break;
			case 3:
				$orientation = - 180;
				$rotate      = true;
				$modify      = true;
				break;
			case 4:
				$flip   = array( true, false );
				$modify = true;
				break;
			case 5:
				$orientation = - 90;
				$rotate      = true;
				$flip        = array( false, true );
				$modify      = true;
				break;
			case 6:
				$orientation = - 90;
				$rotate      = true;
				$modify      = true;
				break;
			case 7:
				$orientation = - 270;
				$rotate      = true;
				$flip        = array( false, true );
				$modify      = true;
				break;
			case 8:
			case 9:
				$orientation = - 270;
				$rotate      = true;
				$modify      = true;
				break;
			default:
				$orientation = 0;
				$rotate      = true;
				$modify      = true;
				break;
		}
	}

	$quality = null;
	/**
	 * Filter the image quality.
	 *
	 * @since 1.5.7
	 *
	 * @param int|null $quality Image Compression quality between 1-100% scale. Default null.
	 * @param string $quality   Image mime type.
	 */
	$quality = apply_filters( 'geodir_image_upload_set_quality', $quality, $mime_type );
	if ( $quality !== null ) {
		$modify = true;
	}

	if ( ! $modify ) {
		return $file; // no change
	}

	$image = wp_get_image_editor( $file_path );
	if ( ! is_wp_error( $image ) ) {
		if ( $rotate ) {
			$image->rotate( $orientation );
		}

		if ( ! empty( $flip ) ) {
			$image->flip( $flip[0], $flip[1] );
		}

		if ( $quality !== null ) {
			$image->set_quality( (int) $quality );
		}

		$result = $image->save( $file_path );
		if ( ! is_wp_error( $result ) ) {
			$file['file']     = $result['path'];
			$file['tmp_name'] = $result['path'];
		}
	}

	// The image orientation is fixed, pass it back for further processing
	return $file;
}

/**
 * Returns the recent reviews.
 *
 * @since   1.0.0
 * @since   1.6.21 Recent reviews doesn't working well with WPML.
 * @package GeoDirectory
 *
 * @global object $wpdb        WordPress Database object.
 * @global object $gd_session  GeoDirectory Session object.
 *
 * @param int $g_size          Optional. Avatar size in pixels. Default 60.
 * @param int $no_comments     Optional. Number of reviews you want to display. Default: 10.
 * @param int $comment_lenth   Optional. Maximum number of characters you want to display. After that read more link
 *                             will appear.
 * @param bool $show_pass_post Optional. Not yet implemented.
 *
 * @return string Returns the recent reviews html.
 */
function geodir_get_recent_reviews( $g_size = 60, $no_comments = 5, $comment_lenth = 60, $show_pass_post = false ) {
	global $wpdb, $tablecomments, $tableposts, $rating_table_name, $gd_session, $table_prefix;
	$tablecomments = $wpdb->comments;
	$tableposts    = $wpdb->posts;

	$comments_echo  = '';
	$city_filter    = '';
	$region_filter  = '';
	$country_filter = '';

	if ( $gd_session->get( 'gd_multi_location' ) ) {
		if ( $gd_ses_country = $gd_session->get( 'gd_country' ) ) {
			$country_filter = $wpdb->prepare( " AND r.post_country=%s ", str_replace( "-", " ", $gd_ses_country ) );
		}

		if ( $gd_ses_region = $gd_session->get( 'gd_region' ) ) {
			$region_filter = $wpdb->prepare( " AND r.post_region=%s ", str_replace( "-", " ", $gd_ses_region ) );
		}

		if ( $gd_ses_city = $gd_session->get( 'gd_city' ) ) {
			$city_filter = $wpdb->prepare( " AND r.post_city=%s ", str_replace( "-", " ", $gd_ses_city ) );
		}
	}
	
	$join = '';
	$where = '';
	
	if (geodir_is_wpml()) {
		$lang_code = ICL_LANGUAGE_CODE;
		
		if ($lang_code) {
			$join .= " JOIN " . $table_prefix . "icl_translations AS icltr2 ON icltr2.element_id = c.comment_post_ID AND p.ID = icltr2.element_id AND CONCAT('post_', p.post_type) = icltr2.element_type LEFT JOIN " . $table_prefix . "icl_translations AS icltr_comment ON icltr_comment.element_id = c.comment_ID AND icltr_comment.element_type = 'comment'";
			$where .= " AND icltr2.language_code = '" . $lang_code . "' AND (icltr_comment.language_code IS NULL OR icltr_comment.language_code = icltr2.language_code)";
		}
	}
	
	$request = "SELECT r.id AS ID, r.post_type, r.comment_id AS comment_ID, r.post_date AS comment_date, r.overall_rating, r.user_id, r.post_id FROM " . GEODIR_REVIEW_TABLE . " AS r JOIN " . $wpdb->comments . " AS c ON c.comment_ID = r.comment_id JOIN " . $wpdb->posts . " AS p ON p.ID = c.comment_post_ID " . $join . " WHERE c.comment_parent = 0 AND c.comment_approved = 1 AND r.status = 1 AND r.overall_rating >= 1 AND p.post_status = 'publish' " . $where . " ORDER BY r.post_date DESC, r.id DESC LIMIT ". $no_comments;
	
	$comments = $wpdb->get_results( $request );
	
	foreach ( $comments as $comment ) {
		// Set the extra comment info needed.
		$comment_extra = $wpdb->get_row( "SELECT * FROM $wpdb->comments WHERE comment_ID =$comment->comment_ID" );
		$comment->comment_content      = $comment_extra->comment_content;
		$comment->comment_author       = $comment_extra->comment_author;
		$comment->comment_author_email = $comment_extra->comment_author_email;

		$comment_id      = '';
		$comment_id      = $comment->comment_ID;
		$comment_content = strip_tags( $comment->comment_content );

		$comment_content = preg_replace( '#(\\[img\\]).+(\\[\\/img\\])#', '', $comment_content );

		$permalink            = get_permalink( $comment->ID ) . "#comment-" . $comment->comment_ID;
		$comment_author_email = $comment->comment_author_email;
		$comment_post_ID      = $comment->post_id;

		$post_title        = get_the_title( $comment_post_ID );
		$permalink         = get_permalink( $comment_post_ID );
		$comment_permalink = $permalink . "#comment-" . $comment->comment_ID;
		$read_more         = '<a class="comment_excerpt" href="' . $comment_permalink . '">' . __( 'Read more', 'geodirectory' ) . '</a>';

		$comment_content_length = strlen( $comment_content );
		if ( $comment_content_length > $comment_lenth ) {
			$comment_excerpt = geodir_utf8_substr( $comment_content, 0, $comment_lenth ) . '... ' . $read_more;
		} else {
			$comment_excerpt = $comment_content;
		}

		if ( $comment->user_id ) {
			$user_profile_url = get_author_posts_url( $comment->user_id );
		} else {
			$user_profile_url = '';
		}

		if ( $comment_id ) {
			$comments_echo .= '<li class="clearfix">';
			$comments_echo .= "<span class=\"li" . $comment_id . " geodir_reviewer_image\">";
			if ( function_exists( 'get_avatar' ) ) {
				if ( ! isset( $comment->comment_type ) ) {
					if ( $user_profile_url ) {
						$comments_echo .= '<a href="' . $user_profile_url . '">';
					}
					$comments_echo .= get_avatar( $comment->comment_author_email, $g_size, geodir_plugin_url() . '/geodirectory-assets/images/gravatar2.png' );
					if ( $user_profile_url ) {
						$comments_echo .= '</a>';
					}
				} elseif ( ( isset( $comment->comment_type ) && $comment->comment_type == 'trackback' ) || ( isset( $comment->comment_type ) && $comment->comment_type == 'pingback' ) ) {
					if ( $user_profile_url ) {
						$comments_echo .= '<a href="' . $user_profile_url . '">';
					}
					$comments_echo .= get_avatar( $comment->comment_author_url, $g_size, geodir_plugin_url() . '/geodirectory-assets/images/gravatar2.png' );
				}
			} elseif ( function_exists( 'gravatar' ) ) {
				if ( $user_profile_url ) {
					$comments_echo .= '<a href="' . $user_profile_url . '">';
				}
				$comments_echo .= "<img src=\"";
				if ( '' == $comment->comment_type ) {
					$comments_echo .= gravatar( $comment->comment_author_email, $g_size, geodir_plugin_url() . '/geodirectory-assets/images/gravatar2.png' );
					if ( $user_profile_url ) {
						$comments_echo .= '</a>';
					}
				} elseif ( ( 'trackback' == $comment->comment_type ) || ( 'pingback' == $comment->comment_type ) ) {
					if ( $user_profile_url ) {
						$comments_echo .= '<a href="' . $user_profile_url . '">';
					}
					$comments_echo .= gravatar( $comment->comment_author_url, $g_size, geodir_plugin_url() . '/geodirectory-assets/images/gravatar2.png' );
					if ( $user_profile_url ) {
						$comments_echo .= '</a>';
					}
				}
				$comments_echo .= "\" alt=\"\" class=\"avatar\" />";
			}

			$comments_echo .= "</span>\n";

			$comments_echo .= '<span class="geodir_reviewer_content">';
			if ( $comment->user_id ) {
				$comments_echo .= '<a href="' . get_author_posts_url( $comment->user_id ) . '">';
			}
			$comments_echo .= '<span class="geodir_reviewer_author">' . $comment->comment_author . '</span> ';
			if ( $comment->user_id ) {
				$comments_echo .= '</a>';
			}
			$comments_echo .= '<span class="geodir_reviewer_reviewed">' . __( 'reviewed', 'geodirectory' ) . '</span> ';
			$comments_echo .= '<a href="' . $permalink . '" class="geodir_reviewer_title">' . $post_title . '</a>';
			$comments_echo .= geodir_get_rating_stars( $comment->overall_rating, $comment_post_ID );
			$comments_echo .= '<p class="geodir_reviewer_text">' . $comment_excerpt . '';
			//echo preg_replace('#(\\[img\\]).+(\\[\\/img\\])#', '', $comment_excerpt);
			$comments_echo .= '</p>';

			$comments_echo .= "</span>\n";
			$comments_echo .= '</li>';
		}
	}

	return $comments_echo;
}

/**
 * Returns All post categories from all GD post types.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @return array Returns post categories as an array.
 */
function geodir_home_map_cats_key_value_array() {
	$post_types = geodir_get_posttypes( 'object' );

	$return = array();
	if ( ! empty( $post_types ) ) {
		foreach ( $post_types as $key => $post_type ) {
			$cpt_name       = __( $post_type->labels->singular_name, 'geodirectory' );
			$post_type_name = sprintf( __( '%s Categories', 'geodirectory' ), $cpt_name );
			$taxonomies     = geodir_get_taxonomies( $key );
			$cat_taxonomy   = ! empty( $taxonomies[0] ) ? $taxonomies[0] : null;
			$cat_terms      = $cat_taxonomy ? get_terms( $cat_taxonomy ) : null;

			if ( ! empty( $cat_terms ) ) {
				$return[ 'optgroup_start-' . $key ] = $post_type_name;

				foreach ( $cat_terms as $cat_term ) {
					$return[ $key . '_' . $cat_term->term_id ] = $cat_term->name;
				}

				$return[ 'optgroup_end-' . $key ] = $post_type_name;
			}
		}
	}

	return $return;
}

/**
 * "Twitter tweet" button code.
 *
 * To display "Twitter tweet" button, you can call this function.
 * @since   1.0.0
 * @package GeoDirectory
 */
function geodir_twitter_tweet_button() {
	if ( isset( $_GET['gde'] ) ) {
		$link = '?url=' . urlencode( geodir_curPageURL() );
	} else {
		$link = '';
	}
	?>
	<a href="http://twitter.com/share<?php echo $link; ?>"
	   class="twitter-share-button"><?php _e( 'Tweet', 'geodirectory' ); ?></a>
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<?php
}

/**
 * "Facebook like" button code.
 *
 * To display "Facebook like" button, you can call this function.
 *
 * @since   1.0.0
 * @package GeoDirectory
 * @global object $post The current post object.
 */
function geodir_fb_like_button() {
	global $post;
	?>
	<iframe <?php if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
		echo 'allowtransparency="true"';
	} ?> class="facebook"
	     src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light"
	     style="border:none; overflow:hidden; width:100px; height:20px"></iframe>
	<?php
}

/**
 * "Google Plus" share button code.
 *
 * To display "Google Plus" share button, you can call this function.
 *
 * @since   1.0.0
 * @package GeoDirectory
 */
function geodir_google_plus_button() {
	?>
	<div id="plusone-div" class="g-plusone" data-size="medium"></div>
	<script type="text/javascript">
		(function () {
			var po = document.createElement('script');
			po.type = 'text/javascript';
			po.async = true;
			po.src = 'https://apis.google.com/js/platform.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(po, s);
		})();
	</script>
	<?php
}


function geodir_listing_bounce_map_pin_on_hover() {
	if ( get_option( 'geodir_listing_hover_bounce_map_pin', true ) ) {
		?>
		<script>
			jQuery(function ($) {
				if (typeof(animate_marker) == 'function') {
					var groupTab = $("ul.geodir_category_list_view").children("li");
					groupTab.hover(function () {
						animate_marker('listing_map_canvas', String($(this).data("post-id")));
					}, function () {
						stop_marker_animation('listing_map_canvas', String($(this).data("post-id")));
					});
				} else {
					window.animate_marker = function () {
					};
					window.stop_marker_animation = function () {
					};
				}
			});
		</script>
		<?php
	}
}

add_action( 'geodir_after_listing_listview', 'geodir_listing_bounce_map_pin_on_hover', 10 );

add_action( 'geodir_after_favorite_html', 'geodir_output_favourite_html_listings', 1, 1 );
function geodir_output_favourite_html_listings( $post_id ) {
	geodir_favourite_html( '', $post_id );
}

add_action( 'geodir_listing_after_pinpoint', 'geodir_output_pinpoint_html_listings', 1, 2 );
function geodir_output_pinpoint_html_listings( $post_id, $post = array() ) {
	global $wp_query;

	$show_pin_point = $wp_query->is_main_query();

	if ( ! empty( $post ) && ! empty( $show_pin_point ) && is_active_widget( false, "", "geodir_map_v3_listing_map" ) ) {
		$term_icon_url = geodir_get_tax_meta( $post->default_category, 'ct_cat_icon', false, $post->post_type );
		$marker_icon   = isset( $term_icon_url['src'] ) ? $term_icon_url['src'] : get_option( 'geodir_default_marker_icon' );
		?>
		<span class="geodir-pinpoint"
		      style="background:url('<?php echo $marker_icon; ?>') no-repeat scroll left top transparent;background-size:auto 100%; -webkit-background-size:auto 100%;-moz-background-size:auto 100%;height:9px;width:14px;"><?php echo apply_filters( 'geodir_listing_listview_pinpoint_inner_content', '', 'listing' ); ?></span>
		<a class="geodir-pinpoint-link" href="javascript:void(0)"
		   onclick="if(typeof openMarker=='function'){openMarker('listing_map_canvas' ,'<?php echo $post->ID; ?>')}"
		   onmouseover="if(typeof animate_marker=='function'){animate_marker('listing_map_canvas' ,'<?php echo $post->ID; ?>')}"
		   onmouseout="if(typeof stop_marker_animation=='function'){stop_marker_animation('listing_map_canvas' ,'<?php echo $post->ID; ?>')}"><?php _e( 'Pinpoint', 'geodirectory' ); ?></a>
		<?php
	}
}

function geodir_search_form_submit_button() {

	$new_style = get_option( 'geodir_show_search_old_search_from' ) ? false : true;

	if ( $new_style ) {
		$default_search_button_label = '<i class="fa fa-search" aria-hidden="true"></i>';
	}else{
		$default_search_button_label = 'Search';
	}
	if ( get_option( 'geodir_search_button_label' ) && get_option( 'geodir_search_button_label' ) != 'Search' ) {
		$default_search_button_label = __( get_option( 'geodir_search_button_label' ), 'geodirectory' );
	}

	/**
	 * Filter the default search button text value for the search form.
	 *
	 * This text can be changed via an option in settings, this is a last resort.
	 *
	 * @since 1.5.5
	 *
	 * @param string $default_search_button_label The current search button text.
	 */
	$default_search_button_label = apply_filters( 'geodir_search_default_search_button_text', $default_search_button_label );

	$fa_class = '';
	if ( strpos( $default_search_button_label, '&#' ) !== false ) {
		$fa_class = 'fa';
	}


	if ( $new_style ) {
	?>
		<button class="geodir_submit_search <?php echo $fa_class; ?>"><?php _e( $default_search_button_label ,'geodirectory'); ?></button>
<?php }else{?>
		<input type="button" value="<?php esc_attr_e( $default_search_button_label ); ?>"
	       class="geodir_submit_search <?php echo $fa_class; ?>"/>
	<?php }
}

add_action( 'geodir_before_search_button', 'geodir_search_form_submit_button', 5000 );

function geodir_search_form_post_type_input() {
	global $geodir_search_post_type;
	$post_types     = apply_filters( 'geodir_search_form_post_types', geodir_get_posttypes( 'object' ) );
	$curr_post_type = $geodir_search_post_type;

	if ( ! empty( $post_types ) && count( (array) $post_types ) > 1 ) {

		foreach ( $post_types as $post_type => $info ){
			global $wpdb;
			$has_posts = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_status='publish' LIMIT 1", $post_type ) );
			if ( ! $has_posts ) {
				unset($post_types->{$post_type});
			}
		}

		if ( ! empty( $post_types ) && count( (array) $post_types ) > 1 ) {

			$new_style = get_option( 'geodir_show_search_old_search_from' ) ? false : true;
			if ( $new_style ) {
				echo "<div class='gd-search-input-wrapper gd-search-field-cpt'>";
			}
			?>
			<select name="stype" class="search_by_post">
				<?php foreach ( $post_types as $post_type => $info ):
					global $wpdb;
					?>

					<option data-label="<?php echo get_post_type_archive_link( $post_type ); ?>"
					        value="<?php echo $post_type; ?>" <?php if ( isset( $_REQUEST['stype'] ) ) {
						if ( $post_type == $_REQUEST['stype'] ) {
							echo 'selected="selected"';
						}
					} elseif ( $curr_post_type == $post_type ) {
						echo 'selected="selected"';
					} ?>><?php _e( geodir_utf8_ucfirst( $info->labels->name ), 'geodirectory' ); ?></option>

				<?php endforeach; ?>
			</select>
			<?php
			if ( $new_style ) {
				echo "</div>";
			}
		}else{
			if(! empty( $post_types )){
				$pt_arr = (array)$post_types;
				echo '<input type="hidden" name="stype" value="' . key( $pt_arr  ) . '"  />';
			}else{
				echo '<input type="hidden" name="stype" value="gd_place"  />';
			}

		}

	}elseif ( ! empty( $post_types ) ) {
		echo '<input type="hidden" name="stype" value="gd_place"  />';
	}
}

function geodir_search_form_search_input() {

	$default_search_for_text = SEARCH_FOR_TEXT;
	if ( get_option( 'geodir_search_field_default_text' ) ) {
		$default_search_for_text = __( get_option( 'geodir_search_field_default_text' ), 'geodirectory' );
	}

	$new_style = get_option('geodir_show_search_old_search_from') ? false : true;
	if($new_style){
		echo "<div class='gd-search-input-wrapper gd-search-field-search'>";
	}
	?>
	<input class="search_text" name="s"
	       value="<?php if ( isset( $_REQUEST['s'] ) && trim( $_REQUEST['s'] ) != '' ) {
		       echo esc_attr( stripslashes_deep( $_REQUEST['s'] ) );
	       } else {
		       echo $default_search_for_text;
	       } ?>" type="text"
	       onblur="if (this.value.trim() == '') {this.value = '<?php echo esc_sql( $default_search_for_text ); ?>';}"
	       onfocus="if (this.value == '<?php echo esc_sql( $default_search_for_text ); ?>') {this.value = '';}"
	       onkeydown="javascript: if(event.keyCode == 13) geodir_click_search(this);">
	<?php
	if($new_style){
		echo "</div>";
	}
}

function geodir_search_form_near_input() {

	$default_near_text = NEAR_TEXT;
	if ( get_option( 'geodir_near_field_default_text' ) ) {
		$default_near_text = __( get_option( 'geodir_near_field_default_text' ), 'geodirectory' );
	}

	if ( isset( $_REQUEST['snear'] ) && $_REQUEST['snear'] != '' ) {
		$near = esc_attr( stripslashes_deep( $_REQUEST['snear'] ) );
	} else {
		$near = $default_near_text;
	}
	

	global $geodir_search_post_type;
	$curr_post_type = $geodir_search_post_type;
	/**
	 * Used to hide the near field and other things.
	 *
	 * @since 1.6.9
	 * @param string $curr_post_type The current post type.
	 */
	$near_input_extra = apply_filters('geodir_near_input_extra','',$curr_post_type);


	/**
	 * Filter the "Near" text value for the search form.
	 *
	 * This is the input "value" attribute and can change depending on what the user searches and is not always the default value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $near              The current near value.
	 * @param string $default_near_text The default near value.
	 */
	$near = apply_filters( 'geodir_search_near_text', $near, $default_near_text );
	/**
	 * Filter the default "Near" text value for the search form.
	 *
	 * This is the default value if nothing has been searched.
	 *
	 * @since 1.0.0
	 *
	 * @param string $near              The current near value.
	 * @param string $default_near_text The default near value.
	 */
	$default_near_text = apply_filters( 'geodir_search_default_near_text', $default_near_text, $near );
	/**
	 * Filter the class for the near search input.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class The class for the HTML near input, default is blank.
	 */
	$near_class = apply_filters( 'geodir_search_near_class', '' );

	$new_style = get_option('geodir_show_search_old_search_from') ? false : true;
	if($new_style){
		echo "<div class='gd-search-input-wrapper gd-search-field-near' $near_input_extra>";
		
		do_action('geodir_before_near_input');
	}

	?>
	<input name="snear" class="snear <?php echo $near_class; ?>" type="text" value="<?php echo $near; ?>"
	       onblur="if (this.value.trim() == '') {this.value = ('<?php echo esc_sql( $near ); ?>' != '' ? '<?php echo esc_sql( $near ); ?>' : '<?php echo $default_near_text; ?>');}"
	       onfocus="if (this.value == '<?php echo $default_near_text; ?>' || this.value =='<?php echo esc_sql( $near ); ?>') {this.value = '';}"
	       onkeydown="javascript: if(event.keyCode == 13) geodir_click_search(this);" <?php echo $near_input_extra;?>/>
	<?php
	if($new_style){
		do_action('geodir_after_near_input');

		echo "</div>";
	}
}

add_action( 'geodir_search_form_inputs', 'geodir_search_form_post_type_input', 10 );
add_action( 'geodir_search_form_inputs', 'geodir_search_form_search_input', 20 );
add_action( 'geodir_search_form_inputs', 'geodir_search_form_near_input', 30 );

function geodir_get_search_post_type($pt=''){
	global $geodir_search_post_type;

	if($pt!=''){return $geodir_search_post_type = $pt;}
	if(!empty($geodir_search_post_type)){ return $geodir_search_post_type;}

	$geodir_search_post_type = geodir_get_current_posttype();

	if(!$geodir_search_post_type) {
		$geodir_search_post_type = geodir_get_default_posttype();
	}


	return $geodir_search_post_type;
}

function geodir_search_form(){

	geodir_get_search_post_type();

	geodir_get_template_part('listing', 'filter-form');

	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_geodir_search_form', 'geodir_search_form' );
add_action( 'wp_ajax_nopriv_geodir_search_form', 'geodir_search_form' );

/**
 * Check wpml active or not.
 *
 * @since 1.5.0
 *
 * @return True if WPML is active else False.
 */
function geodir_is_wpml() {
    if (class_exists('SitePress') && function_exists('icl_object_id')) {
        return true;
    }

    return false;
}

/**
 * Get WPML language code for current term.
 *
 * @since 1.5.0
 *
 * @global object $sitepress Sitepress WPML object.
 *
 * @param int $element_id Post ID or Term id.
 * @param string $element_type Element type. Ex: post_gd_place or tax_gd_placecategory.
 * @return Language code.
 */
function geodir_get_language_for_element($element_id, $element_type) {
    global $sitepress;

    return $sitepress->get_language_for_element($element_id, $element_type);
}

/**
 * Duplicate post details for WPML translation post.
 *
 * @since 1.5.0
 * @since 1.6.16 Sync reviews if sync comments allowed.
 *
 * @param int $master_post_id Original Post ID.
 * @param string $lang Language code for translating post.
 * @param array $postarr Array of post data.
 * @param int $tr_post_id Translation Post ID.
 * @param bool $after_save If true it will force duplicate. 
 *                         Added to fix duplicate translation for front end.
 */
function geodir_icl_make_duplicate($master_post_id, $lang, $postarr, $tr_post_id, $after_save = false) {
    global $sitepress;
    
    $post_type = get_post_type($master_post_id);
    $icl_ajx_action = !empty($_REQUEST['icl_ajx_action']) && $_REQUEST['icl_ajx_action'] == 'make_duplicates' ? true : false;
    if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'wpml_duplicate_dashboard' && !empty($_REQUEST['duplicate_post_ids'])) {
        $icl_ajx_action = true;
    }
    
    if (in_array($post_type, geodir_get_posttypes())) {
        if ($icl_ajx_action || $after_save) {
            // Duplicate post details
            geodir_icl_duplicate_post_details($master_post_id, $tr_post_id, $lang);
            
            // Duplicate taxonomies
            geodir_icl_duplicate_taxonomies($master_post_id, $tr_post_id, $lang);
            
            // Duplicate post images
            geodir_icl_duplicate_post_images($master_post_id, $tr_post_id, $lang);
        }
        
        // Sync post reviews
        if ($sitepress->get_setting('sync_comments_on_duplicates')) {
            geodir_wpml_duplicate_post_reviews($master_post_id, $tr_post_id, $lang);
        }
    }
}
add_filter( 'icl_make_duplicate', 'geodir_icl_make_duplicate', 11, 4 );

/**
 * Duplicate post listing manually after listing saved.
 *
 * @since 1.6.16 Sync reviews if sync comments allowed.
 *
 * @param int $post_id The Post ID.
 * @param string $lang Language code for translating post.
 * @param array $request_info The post details in an array.
 */
function geodir_wpml_duplicate_listing($post_id, $request_info) {
    global $sitepress;
    
    $icl_ajx_action = !empty($_REQUEST['icl_ajx_action']) && $_REQUEST['icl_ajx_action'] == 'make_duplicates' ? true : false;
    if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'wpml_duplicate_dashboard' && !empty($_REQUEST['duplicate_post_ids'])) {
        $icl_ajx_action = true;
    }
    
    if (!$icl_ajx_action && in_array(get_post_type($post_id), geodir_get_posttypes()) && $post_duplicates = $sitepress->get_duplicates($post_id)) {
        foreach ($post_duplicates as $lang => $dup_post_id) {
            geodir_icl_make_duplicate($post_id, $lang, $request_info, $dup_post_id, true);
        }
    }
}

/**
 * Duplicate post reviews for WPML translation post.
 *
 * @since 1.6.16
 *
 * @global object $wpdb WordPress Database object.
 *
 * @param int $master_post_id Original Post ID.
 * @param int $tr_post_id Translation Post ID.
 * @param string $lang Language code for translating post.
 * @return bool True for success, False for fail.
 */
function geodir_wpml_duplicate_post_reviews($master_post_id, $tr_post_id, $lang) {
    global $wpdb;

    $reviews = $wpdb->get_results($wpdb->prepare("SELECT comment_id FROM " . GEODIR_REVIEW_TABLE . " WHERE post_id=%d ORDER BY id ASC", $master_post_id), ARRAY_A);

    if (!empty($reviews)) {
        foreach ($reviews as $review) {
            geodir_wpml_duplicate_post_review($review['comment_id'], $master_post_id, $tr_post_id, $lang);
        }
    }

    return false;
}

/**
 * Duplicate post general details for WPML translation post.
 *
 * @since 1.5.0
 *
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 *
 * @param int $master_post_id Original Post ID.
 * @param int $tr_post_id Translation Post ID.
 * @param string $lang Language code for translating post.
 * @return bool True for success, False for fail.
 */
function geodir_icl_duplicate_post_details($master_post_id, $tr_post_id, $lang) {
    global $wpdb, $plugin_prefix;

    $post_type = get_post_type($master_post_id);
    $post_table = $plugin_prefix . $post_type . '_detail';

    $query = $wpdb->prepare("SELECT * FROM " . $post_table . " WHERE post_id = %d", array($master_post_id));
    $data = (array)$wpdb->get_row($query);

    if ( !empty( $data ) ) {
        $data['post_id'] = $tr_post_id;
        unset($data['default_category'], $data['marker_json'], $data['featured_image'], $data[$post_type . 'category']);
        $wpdb->update($post_table, $data, array('post_id' => $tr_post_id));
        return true;
    }

    return false;
}

/**
 * Duplicate post taxonomies for WPML translation post.
 *
 * @since 1.5.0
 *
 * @global object $sitepress Sitepress WPML object.
 * @global object $wpdb WordPress Database object.
 *
 * @param int $master_post_id Original Post ID.
 * @param int $tr_post_id Translation Post ID.
 * @param string $lang Language code for translating post.
 * @return bool True for success, False for fail.
 */
function geodir_icl_duplicate_taxonomies($master_post_id, $tr_post_id, $lang) {
    global $sitepress, $wpdb;
    $post_type = get_post_type($master_post_id);

    remove_filter('get_term', array($sitepress,'get_term_adjust_id')); // AVOID filtering to current language

    $taxonomies = get_object_taxonomies($post_type);
    foreach ($taxonomies as $taxonomy) {
        $terms = get_the_terms($master_post_id, $taxonomy);
        $terms_array = array();
        
        if ($terms) {
            foreach ($terms as $term) {
                $tr_id = apply_filters( 'translate_object_id',$term->term_id, $taxonomy, false, $lang);
                
                if (!is_null($tr_id)){
                    // not using get_term - unfiltered get_term
                    $translated_term = $wpdb->get_row($wpdb->prepare("
                        SELECT * FROM {$wpdb->terms} t JOIN {$wpdb->term_taxonomy} x ON x.term_id = t.term_id WHERE t.term_id = %d AND x.taxonomy = %s", $tr_id, $taxonomy));

                    $terms_array[] = $translated_term->term_id;
                }
            }

            if (!is_taxonomy_hierarchical($taxonomy)){
                $terms_array = array_unique( array_map( 'intval', $terms_array ) );
            }

            wp_set_post_terms($tr_post_id, $terms_array, $taxonomy);
            
            if ($taxonomy == $post_type . 'category') {
                geodir_set_postcat_structure($tr_post_id, $post_type . 'category');
            }
        }
    }
}

/**
 * Duplicate post images for WPML translation post.
 *
 * @since 1.5.0
 *
 * @global object $wpdb WordPress Database object.
 *
 * @param int $master_post_id Original Post ID.
 * @param int $tr_post_id Translation Post ID.
 * @param string $lang Language code for translating post.
 * @return bool True for success, False for fail.
 */
function geodir_icl_duplicate_post_images($master_post_id, $tr_post_id, $lang) {
    global $wpdb;

    $query = $wpdb->prepare("DELETE FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE mime_type like %s AND post_id = %d", array('%image%', $tr_post_id));
    $wpdb->query($query);

    $query = $wpdb->prepare("SELECT * FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE mime_type like %s AND post_id = %d ORDER BY menu_order ASC", array('%image%', $master_post_id));
    $post_images = $wpdb->get_results($query);

    if ( !empty( $post_images ) ) {
        foreach ( $post_images as $post_image) {
            $image_data = (array)$post_image;
            unset($image_data['ID']);
            $image_data['post_id'] = $tr_post_id;
            
            $wpdb->insert(GEODIR_ATTACHMENT_TABLE, $image_data);
            
            geodir_set_wp_featured_image($tr_post_id);
        }
        
        return true;
    }

    return false;
}


/**
 * Duplicate post review for WPML translation post.
 *
 * @since 1.6.16
 *
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 *
 * @param int $master_comment_id Original Comment ID.
 * @param int $master_post_id Original Post ID.
 * @param int $tr_post_id Translation Post ID.
 * @param string $lang Language code for translating post.
 * @return bool True for success, False for fail.
 */
function geodir_wpml_duplicate_post_review($master_comment_id, $master_post_id, $tr_post_id, $lang) {
    global $wpdb, $plugin_prefix, $sitepress;

    $review = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_id=%d ORDER BY id ASC", $master_comment_id), ARRAY_A);

    if (empty($review)) {
        return false;
    }
    if ($review['post_id'] != $master_post_id) {
        $wpdb->query($wpdb->prepare("UPDATE " . GEODIR_REVIEW_TABLE . " SET post_id=%d WHERE comment_id=%d", $master_post_id, $master_comment_id));
        geodir_update_postrating($master_post_id, $post_type);
    }

    $tr_comment_id = geodir_wpml_duplicate_comment_exists($tr_post_id, $master_comment_id);

    if (empty($tr_comment_id)) {
        return false;
    }

    $post_type = get_post_type($master_post_id);
    $post_table = $plugin_prefix . $post_type . '_detail';

    $translated_post = $wpdb->get_row($wpdb->prepare("SELECT post_title, post_latitude, post_longitude, post_city, post_region, post_country FROM " . $post_table . " WHERE post_id = %d", $tr_post_id), ARRAY_A);
    if (empty($translated_post)) {
        return false;
    }

    $review['comment_id'] = $tr_comment_id;
    $review['post_id'] = $tr_post_id;
    $review['post_title'] = $translated_post['post_title'];
    $review['post_city'] = $translated_post['post_city'];
    $review['post_region'] = $translated_post['post_region'];
    $review['post_country'] = $translated_post['post_country'];
    $review['post_latitude'] = $translated_post['post_latitude'];
    $review['post_longitude'] = $translated_post['post_longitude'];

    if (isset($review['id'])) {
        unset($review['id']);
    }

    $tr_review_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_id=%d AND post_id=%d ORDER BY id ASC", $tr_comment_id, $tr_post_id));

    if ($tr_review_id) { // update review
        $wpdb->update(GEODIR_REVIEW_TABLE, $review, array('id' => $tr_review_id));
    } else { // insert review
        $wpdb->insert(GEODIR_REVIEW_TABLE, $review);
        $tr_review_id = $wpdb->insert_id;
    }

    if ($tr_post_id) {
        geodir_update_postrating($tr_post_id, $post_type);
        
        if (defined('GEODIRREVIEWRATING_VERSION') && get_option('geodir_reviewrating_enable_review') && $sitepress->get_setting('sync_comments_on_duplicates')) {
            $wpdb->query($wpdb->prepare("DELETE FROM " . GEODIR_COMMENTS_REVIEWS_TABLE . " WHERE comment_id = %d", array($tr_comment_id)));
            $likes = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . GEODIR_COMMENTS_REVIEWS_TABLE . " WHERE comment_id=%d ORDER BY like_date ASC", $master_comment_id, $tr_post_id), ARRAY_A);

            if (!empty($likes)) {
                foreach ($likes as $like) {
                    unset($like['like_id']);
                    $like['comment_id'] = $tr_comment_id;
                    
                    $wpdb->insert(GEODIR_COMMENTS_REVIEWS_TABLE, $like);
                }
            }
        }
    }

    return $tr_review_id;
}

/**
 * Synchronize review for WPML translation post.
 *
 * @since 1.6.16
 *
 * @global object $wpdb WordPress Database object.
 * @global object $sitepress Sitepress WPML object.
 * @global array $gd_wpml_posttypes Geodirectory post types array.
 *
 * @param int $comment_id The Comment ID.
 */
function gepdir_wpml_sync_comment($comment_id) {
    global $wpdb, $sitepress, $gd_wpml_posttypes;

    if (empty($gd_post_types)) {
        $gd_wpml_posttypes = geodir_get_posttypes();
    }

    $comment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->comments} WHERE comment_ID=%d", $comment_id), ARRAY_A);
    if (empty($comment)) {
        return;
    }

    $post_id = $comment['comment_post_ID'];
    $post_type = $post_id ? get_post_type($post_id) : NULL;

    if (!($post_type && in_array($post_type, $gd_wpml_posttypes))) {
        return;
    }

    $post_duplicates = $sitepress->get_duplicates($post_id);
    if (empty($post_duplicates)) {
        return;
    }

    foreach ($post_duplicates as $lang => $dup_post_id) {
        if (empty($comment['comment_parent'])) {
            geodir_wpml_duplicate_post_review($comment_id, $post_id, $dup_post_id, $lang);
        }
    }
    
    return true;
}

/**
 * Get the WPML duplicate comment ID of the comment.
 *
 * @since 1.6.16
 *
 * @global object $dup_post_id WordPress Database object.
 *
 * @param int $dup_post_id The duplicate post ID.
 * @param int $original_cid The original Comment ID.
 * @return int The duplicate comment ID.
 */
function geodir_wpml_duplicate_comment_exists($dup_post_id, $original_cid) {
    global $wpdb;

    $duplicate = $wpdb->get_var(
        $wpdb->prepare(
            "   SELECT comm.comment_ID
                FROM {$wpdb->comments} comm
                JOIN {$wpdb->commentmeta} cm
                    ON comm.comment_ID = cm.comment_id
                WHERE comm.comment_post_ID = %d
                    AND cm.meta_key = '_icl_duplicate_of'
                    AND cm.meta_value = %d
                LIMIT 1",
            $dup_post_id,
            $original_cid
        )
    );

    return $duplicate;
}

/**
 * Get the CPT that disabled review stars.
 *
 * @since 1.6.16
 *
 * @param string $post_type WP post type or WP texonomy. Ex: gd_place.
 * @param bool $taxonomy Whether $post_type is taxonomy or not.
 * @return bool True if review star disabled, otherwise false.
 */ 
function geodir_rating_disabled_post_types() {
	$post_types = get_option( 'geodir_disable_rating_cpt' );
	
	/**
	 * Filter the post types array which have rating disabled.
	 *
	 * @since 1.6.16
	 *
	 * @param array $post_types Array of post types which have rating starts disabled.
	 */
	return apply_filters( 'geodir_rating_disabled_post_types', $post_types );
}

/**
 * Check review star disabled for certain CPT.
 *
 * @since 1.6.16
 *
 * @param string|int $post_type WP post type or Post ID or WP texonomy. Ex: gd_place.
 * @param bool $taxonomy Whether $post_type is taxonomy or not.
 * @return bool True if review star disabled, otherwise false.
 */ 
function geodir_cpt_has_rating_disabled( $post_type = '', $taxonomy = false ) {
	$post_types = geodir_rating_disabled_post_types();
	
	if ( empty( $post_types ) ) {
		return false;
	}
	
	if ( is_int( $post_type ) ) {
		$post_type = get_post_type( $post_type );
	}
	
	if ( $taxonomy && !empty( $post_types ) ) {
		$posttypes = array();
		
		foreach ( $post_types as $posttype ) {
			$posttypes[] = $posttype . 'category';
			$posttypes[] = $posttype . '_tags';
		}
		
		$post_types = $posttypes;
	}

	$return = false;
	if ( $post_type != '' && !empty( $post_types ) && in_array( $post_type, $post_types ) ) {
		$return = true;
	}

	return $return;
}

/**
 * Checks that Yoast SEO is disabled on GD pages.
 *
 * @since 1.6.18
 *
 * @return bool True if Yoast SEO disabled on GD pages.
 */
function geodir_disable_yoast_seo_metas() {
    return (bool)get_option( 'geodir_disable_yoast_meta' );
}

/**
 * Checks the user allowed to duplicate listing or not for WPML.
 *
 * @since 1.6.18
 *
 * @param int $post_id The post ID.
 * @return bool True if allowed.
 */
function geodir_wpml_allowed_to_duplicate( $post_id ) {
    $allowed = false;
    
    if ( !geodir_is_wpml() || empty( $post_id ) ) {
        return $allowed;
    }
    
    $user_id = (int)get_current_user_id();
    
    if ( empty( $user_id ) ) {
        return $allowed;
    }
    
    $post_type = get_post_type( $post_id );
    if ( !geodir_wpml_is_post_type_translated( $post_type ) || get_post_meta( $post_id, '_icl_lang_duplicate_of', true ) ) {
        return $allowed;
    }
    
    if ( geodir_listing_belong_to_current_user( $post_id ) ) {
        $allowed = true;
    }
    
    $disable_cpts = get_option( 'geodir_wpml_disable_duplicate' );
    if ( $allowed && !empty( $disable_cpts ) && in_array( $post_type, $disable_cpts ) ) {
        $allowed = false;
    }
    
    /**
     * Filter the user allowed to duplicate listing or not for WPML.
     *
     * @param bool $allowed True if allowed.
     * @param int $post_id The post ID.
     */
    return apply_filters( 'geodir_wpml_allowed_to_duplicate', $allowed, $post_id );
}

/**
 * Display WPML languages option in sidebar to allow authors to duplicate their listing.
 *
 * @since 1.6.18
 *
 * @global WP_Post|null $post The current post.
 * @global bool $preview True if the current page is add listing preview page. False if not.
 * @global object $sitepress Sitepress WPML object.
 *
 * @param string $content_html The output html of the geodir_edit_post_link() function.
 * @return string Filtered html of the geodir_edit_post_link() function.
 */
function geodir_wpml_frontend_duplicate_listing( $content_html ) {
    global $post, $preview, $sitepress;
    
    if ( !empty( $post->ID ) && !$preview && geodir_is_page( 'detail' ) && geodir_wpml_allowed_to_duplicate( $post->ID ) ) {
        $post_id = $post->ID;
        $element_type = 'post_' . get_post_type( $post_id );
        $original_post_id = $sitepress->get_original_element_id( $post_id, $element_type );
        
        if ( $original_post_id == $post_id ) {
            $wpml_languages = $sitepress->get_active_languages();
            $post_language = $sitepress->get_language_for_element( $post_id, $element_type );
            
            if ( !empty( $wpml_languages ) && isset( $wpml_languages[ $post_language ] ) ) {
                unset( $wpml_languages[ $post_language ] );
            }
            
            if ( !empty( $wpml_languages ) ) {
                $trid  = $sitepress->get_element_trid( $post_id, $element_type );
                $element_translations = $sitepress->get_element_translations( $trid, $element_type );
                $duplicates = $sitepress->get_duplicates( $post_id );
                
                $wpml_content = '<div class="geodir-company_info gd-detail-duplicate"><h3 class="widget-title">' . __( 'Translate Listing', 'geodirectory' ) . '</h3>';
                $wpml_content .= '<table class="gd-duplicate-table" style="width:100%;margin:0"><tbody>';
                $wpml_content .= '<tr style="border-bottom:solid 1px #efefef"><th style="padding:0 2px 2px 2px">' . __( 'Language', 'geodirectory' ) . '</th><th style="width:25px;"></th><th style="width:5em;text-align:center">' . __( 'Translate', 'geodirectory' ) . '</th></tr>';
                
                $needs_translation = false;
                
                foreach ( $wpml_languages as $lang_code => $lang ) {
                    $duplicates_text = '';
                    $translated = false;
                    
                    if ( !empty( $element_translations ) && isset( $element_translations[$lang_code] ) ) {
                        $translated = true;
                        
                        if ( !empty( $duplicates ) && isset( $duplicates[$lang_code] ) ) {
                            $duplicates_text = ' ' . __( '(duplicate)', 'geodirectory' );
                        }
                    } else {
                        $needs_translation = true;
                    }
                    
                    $wpml_content .= '<tr><td style="padding:4px">' . $lang['english_name'] . $duplicates_text . '</td><td>&nbsp;</td><td style="text-align:center;">';
                    
                    if ( $translated ) {
                        $wpml_content .= '<i class="fa fa-check" style="color:orange"></i>';
                    } else {
                        $wpml_content .= '<input name="gd_icl_dup[]" value="' . $lang_code . '" title="' . esc_attr__( 'Create duplicate', 'geodirectory' ) . '" type="checkbox">';
                    }
                    
                    $wpml_content .= '</td></tr>';
                }
                
                if ( $needs_translation ) {
                    $nonce = wp_create_nonce( 'geodir_duplicate_nonce' );
                    $wpml_content .= '<tr><td>&nbsp;</td><td style="vertical-align:middle;padding-top:13px"><i style="display:none" class="fa fa-spin fa-refresh"></i></td><td style="padding:15px 3px 3px 3px;text-align:right"><button data-nonce="' . esc_attr( $nonce ) . '" data-post-id="' . $post_id . '" id="gd_make_duplicates" class="button-secondary">' . __( 'Duplicate', 'geodirectory' ) . '</button></td></tr>';
                }
                
                $wpml_content .= '</tbody></table>';
                $wpml_content .= '</div>';
                
                $content_html .= $wpml_content;
            }
        }
    }
    
    return $content_html;
}

/**
 * Add setting for WPML front-end duplicate translation in design page setting section.
 *
 * @since 1.6.18
 *
 * @param array $settings GD design settings array.
 * @return array Filtered GD design settings array..
 */
function geodir_wpml_duplicate_settings( $settings = array() ) {
    $new_settings = array();
    
    foreach ( $settings as $key => $setting ) {
        
        if ( isset( $setting['type'] ) && $setting['type'] == 'sectionend' && $setting['id'] == 'detail_page_settings' ) {
            $new_settings[] = array(
                'name' => __('Disable WPML duplicate translation', 'geodirectory'),
                'desc' => __('Select post types to disable front end WPML duplicate translation. For selected post types the WPML duplicate option will be disabled from listing detail page sidebar.', 'geodirectory'),
                'tip' => '',
                'id' => 'geodir_wpml_disable_duplicate',
                'css' => 'min-width:300px;',
                'std' => '',
                'type' => 'multiselect',
                'placeholder_text' => __('Select post types', 'geodirectory'),
                'class' => 'chosen_select',
                'options' => array_unique(geodir_post_type_setting_fun())
            );
        }
        $new_settings[] = $setting;
    }
    
    return $new_settings;
}

/**
 * Checks if a given taxonomy is currently translated.
 *
 * @since 1.6.22
 *
 * @param string $taxonomy name/slug of a taxonomy.
 * @return bool true if the taxonomy is currently set to being translatable in WPML.
 */
function geodir_wpml_is_taxonomy_translated( $taxonomy ) {
    if ( empty( $taxonomy ) || !geodir_is_wpml() || !function_exists( 'is_taxonomy_translated' ) ) {
        return false;
    }
    
    if ( is_taxonomy_translated( $taxonomy ) ) {
        return true;
    }
    
    return false;
}

/**
 * Checks if a given post_type is currently translated.
 *
 * @since 1.6.22
 *
 * @param string $post_type name/slug of a post_type.
 * @return bool true if the post_type is currently set to being translatable in WPML.
 */
function geodir_wpml_is_post_type_translated( $post_type ) {
    if ( empty( $post_type ) || !geodir_is_wpml() || !function_exists( 'is_post_type_translated' ) ) {
        return false;
    }
    
    if ( is_post_type_translated( $post_type ) ) {
        return true;
    }
    
    return false;
}

/**
 * Get the element in the WPML current language.
 *
 * @since 1.6.22
 *
 * @param int         $element_id                 Use term_id for taxonomies, post_id for posts
 * @param string      $element_type               Use post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag, etc.
 *                                                You can also pass 'any', to let WPML guess the type, but this will only work for posts.
 * @param bool        $return_original_if_missing Optional, default is FALSE. If set to true it will always return a value (the original value, if translation is missing).
 * @param string|NULL $language_code              Optional, default is NULL. If missing, it will use the current language.
 *                                                If set to a language code, it will return a translation for that language code or
 *                                                the original if the translation is missing and $return_original_if_missing is set to TRUE.
 *
 * @return int|NULL
 */
function geodir_wpml_object_id( $element_id, $element_type = 'post', $return_original_if_missing = false, $ulanguage_code = null ) {
    if ( geodir_is_wpml() ) {
        if ( function_exists( 'wpml_object_id_filter' ) ) {
            return apply_filters( 'wpml_object_id', $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        } else {
            return icl_object_id( $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        }
    }
    
    return $element_id;
}