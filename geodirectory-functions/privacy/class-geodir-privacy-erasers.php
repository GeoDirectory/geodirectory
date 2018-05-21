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
	 * Finds and erases data which could be used to identify a person from GeoDirectory data assocated with an email address.
	 *
	 * Posts are erased in blocks of 10 to avoid timeouts.
	 *
	 * @since 1.6.26
	 * @param string $email_address The user email address.
	 * @param int    $page  Page.
	 * @return array An array of personal data in name value pairs
	 */
	public static function post_data_eraser( $email_address, $page ) {
		$page            = (int) $page;
		$user            = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

		$erasure_enabled = get_option( 'geodir_erasure_request_removes_post_data', false );

		$response        = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		$post_query = array(
			'limit'    => 10,
			'page'     => $page,
			'user'     => array( $email_address ),
		);

		if ( $user instanceof WP_User ) {
			$post_query['user'][] = (int) $user->ID;
		}

		$posts = array();

		if ( 0 < count( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( apply_filters( 'geodir_privacy_erase_post_personal_data', $erasure_enabled, $post ) ) {
					self::remove_post_personal_data( $post );

					/* Translators: %s Post number. */
					$response['messages'][]    = sprintf( __( 'Removed personal data from post %s.', 'geodirectory' ), $post->ID );
					$response['items_removed'] = true;
				} else {
					/* Translators: %s Post number. */
					$response['messages'][]     = sprintf( __( 'Personal data within post %s has been retained.', 'geodirectory' ), $post->ID );
					$response['items_retained'] = true;
				}
			}
			$response['done'] = 10 > count( $posts );
		} else {
			$response['done'] = true;
		}

		return $response;
	}

	/**
	 * Remove personal data specific to GeoDirectory from an post object.
	 *
	 * Note; this will hinder post processing for obvious reasons!
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function remove_post_personal_data( $post ) {
		$anonymized_data = array();

		/**
		 * Allow extensions to remove their own personal data for this post first, so post data is still available.
		 *
		 * @since 1.6.26
		 * @param WP_Post $post A post object.
		 */
		do_action( 'geodir_privacy_before_remove_post_personal_data', $post );

		/**
		 * Expose props and data types we'll be anonymizing.
		 *
		 * @since 1.6.26
		 * @param array    $props Keys are the prop names, values are the data type we'll be passing to wp_privacy_anonymize_data().
		 * @param WP_Post $post A post object.
		 */
		$props_to_remove = apply_filters( 'geodir_privacy_remove_post_personal_data_props', array(
			'phone'       => 'phone',
			'email'       => 'email'
			// TODO more fields
		), $post );

		if ( ! empty( $props_to_remove ) && is_array( $props_to_remove ) ) {
			foreach ( $props_to_remove as $prop => $data_type ) {
				// Get the current value in edit context.
				$value = isset( $post->{$prop} ) ? $post->{$prop} : '';

				// If the value is empty, it does not need to be anonymized.
				if ( empty( $value ) || empty( $data_type ) ) {
					continue;
				}

				$anon_value = wp_privacy_anonymize_data( $data_type, $value );

				/**
				 * Expose a way to control the anonymized value of a prop via 3rd party code.
				 *
				 * @since 1.6.26
				 * @param bool     $anonymized_data Value of this prop after anonymization.
				 * @param string   $prop Name of the prop being removed.
				 * @param string   $value Current value of the data.
				 * @param string   $data_type Type of data.
				 * @param WP_Post $post The post object.
				 */
				$anonymized_data[ $prop ] = apply_filters( 'geodir_privacy_remove_post_personal_data_prop_value', $anon_value, $prop, $value, $data_type, $post );
			}
		}

		// Set all new props and persist the new data to the database.
		//$post->set_props( $anonymized_data );	// TODO
		//$post->update_meta_data( '_anonymized', 'yes' );	// TODO
		//$post->save();	// TODO

		/**
		 * Allow extensions to remove their own personal data for this post.
		 *
		 * @since 1.6.26
		 * @param WP_Post $post The post object.
		 */
		do_action( 'geodir_privacy_remove_post_personal_data', $post );
	}
}
