<?php
/**
 * Personal data erasers.
 *
 * @since 1.6.26
 * @package GeoDirectory
 */

defined( 'ABSPATH' ) || exit;

/**
 * GeoDir_Privacy_Erasers Class.
 */
class GeoDir_Privacy_Erasers {

	/**
	 * Erases personal data associated with an email address from the reviews table.
	 *
	 * @since 1.6.26
	 *
	 * @param  string $email_address The review author email address.
	 * @param  int    $page          Review page.
	 * @return array
	 */
	public static function review_data_eraser( $email_address, $page ) {
		global $wpdb;

		$response = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		if ( empty( $email_address ) ) {
			return $response;
		}

		$page           = (int) $page;
		$items_removed  = false;
		$items_retained = false;

		$reviews = self::reviews_by_author( $email_address, $page );

		if ( empty( $reviews ) ) {
			return $response;
		}

		$messages    = array();

		foreach ( $reviews as $review ) {
			$anonymized_review                         		= array();
			$anonymized_review['user_id']               	= 0;
			$anonymized_review['rating_ip']    				= wp_privacy_anonymize_data( 'ip', $review->rating_ip );

			$review_id = (int) $review->id;

			/**
			 * Filters whether to anonymize the review.
			 *
			 * @since 1.6.26
			 *
			 * @param bool|string                    Whether to apply the review anonymization (bool).
			 *                                       Custom prevention message (string). Default true.
			 * @param object 	 $review             Review object.
			 * @param array      $anonymized_review  Anonymized review data.
			 */
			$anon_message = apply_filters( 'geodir_anonymize_post_review', true, $review, $anonymized_review );

			if ( true !== $anon_message ) {
				if ( $anon_message && is_string( $anon_message ) ) {
					$messages[] = esc_html( $anon_message );
				} else {
					/* translators: %d: Review ID */
					$messages[] = sprintf( __( 'Review %d contains personal data but could not be anonymized.', 'geodirectory' ), $review_id );
				}

				$items_retained = true;

				continue;
			}

			$args = array(
				'id' => $review_id,
			);

			$updated = $wpdb->update( GEODIR_REVIEW_TABLE, $anonymized_review, $args );

			if ( $updated ) {
				$items_removed = true;
			} else {
				$items_retained = true;
			}
		}

		$done = count( $reviews ) < $number;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}

	public static function reviews_by_author( $email_address, $page, $posts_per_page = 10 ) {
		global $wpdb;

		if ( empty( $email_address ) || empty( $page ) ) {
			return array();
		}

		$user = get_user_by( 'email', $email_address );
		if ( empty( $user ) ) {
			return array();
		}

		if ( absint( $page ) < 1 ) {
			$page = 1;
		}

		$limit = absint( ( $page - 1 ) * $posts_per_page ) . ", " . $posts_per_page;
			
		$query = $wpdb->prepare( "SELECT * FROM " . GEODIR_REVIEW_TABLE . " WHERE user_id = %d ORDER BY comment_id ASC LIMIT " . $limit, array( $user->ID ) );

		$reviews = $wpdb->get_results( $query );

		return apply_filters( 'geodir_privacy_review_data_eraser_reviews', $reviews, $email_address, $user, $page );
	}

	/**
	 * Erases personal data associated with an email address from favorites data.
	 *
	 * @since 1.6.26
	 *
	 * @param  string $email_address The author email address.
	 * @param  int    $page          Page number.
	 * @return array
	 */
	public static function favorites_data_eraser( $email_address, $page ) {
		$response = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		if ( empty( $email_address ) ) {
			return $response;
		}

		$user = get_user_by( 'email', $email_address );
		if ( empty( $user ) ) {
			return $response;
		}

		$items_removed  = false;
		$items_retained = false;
		$messages       = array();

		$site_id = '';
        if ( is_multisite() ) {
            $blog_id = get_current_blog_id();
            if ( $blog_id && $blog_id != '1' ) {
				$site_id  = '_' . $blog_id;
			}
        }

		if ( delete_user_meta( $user->ID, 'gd_user_favourite_post' . $site_id ) ) {
			$messages[]    = __( 'Removed "Favorite Listings" data from user.', 'geodirectory' );
			$items_removed = true;
		}

		$done = true;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}
}
