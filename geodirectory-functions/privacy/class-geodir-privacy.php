<?php
/**
 * Privacy/GDPR related functionality which ties into WordPress functionality.
 *
 * @since 1.6.26
 * @package GeoDirectory
 */

defined( 'ABSPATH' ) || exit;

/**
 * GeoDir_Privacy Class.
 */
class GeoDir_Privacy extends GeoDir_Abstract_Privacy {

	/**
	 * Init - hook into events.
	 */
	public function __construct() {
		parent::__construct( __( 'GeoDirectory', 'geodirectory' ) );

		// Include supporting classes.
		include_once( 'class-geodir-privacy-erasers.php' );
		include_once( 'class-geodir-privacy-exporters.php' );

		$gd_post_types = geodir_get_posttypes( 'object' );

		if ( ! empty( $gd_post_types ) ) {
			foreach ( $gd_post_types as $post_type => $info ) {
				$name = $info->labels->name;

				if ( self::allow_export_post_type_data( $post_type ) ) {
					// This hook registers GeoDirectory data exporters.
					$this->add_exporter( 'geodirectory-post-' . $post_type, wp_sprintf( __( 'User %s', 'geodirectory' ), $name ), array( 'GeoDir_Privacy_Exporters', 'post_data_exporter' ) );
				}
			}
		}

		// Handles custom anonomization types not included in core.
		add_filter( 'wp_privacy_anonymize_data', array( $this, 'anonymize_custom_data_types' ), 10, 3 );

		if ( self::allow_export_reviews_data() ) {
			// Review data export
			add_filter( 'wp_privacy_personal_data_export_page', array( 'GeoDir_Privacy_Exporters', 'review_data_exporter' ), 10, 7 );
		}

		if ( self::allow_erase_reviews_data() ) {
			// Review data erase
			$this->add_eraser( 'geodirectory-post-reviews', __( 'User Listing Reviews', 'geodirectory' ), array( 'GeoDir_Privacy_Erasers', 'review_data_eraser' ) );
		}

		// Post favorites
		if ( self::allow_export_favorites_data() ) {
			$this->add_exporter( 'geodirectory-post-favorites', __( 'GeoDirectory Favorite Listings', 'geodirectory' ), array( 'GeoDir_Privacy_Exporters', 'favorites_data_exporter' ) );
		}
		if ( self::allow_erase_favorites_data() ) {
			$this->add_eraser( 'geodirectory-post-favorites', __( 'GeoDirectory Favorite Listings', 'geodirectory' ), array( 'GeoDir_Privacy_Erasers', 'favorites_data_eraser' ) );
		}
	}

	/**
	 * Add privacy policy content for the privacy policy page.
	 *
	 * @since 1.6.26
	 *
	 * @return string The default policy content.
	 */
	public function get_privacy_message() {

		$content = '<h2>' . __( 'Listings', 'geodirectory' ) . '</h2>' .
		           '<p>' . __( 'We collect information about you during the add listing process on our site. This information may include, but is not limited to, your name, email address, phone number, address, locations details including GPS co-ordinates and any other details that might be requested from you for the purpose of adding your business/personal listings.', 'geodirectory' ) . '</p>' .
		           '<p>' . __( 'Handling this data also allows us to:', 'geodirectory' ) . '</p>' .
		           '<ul>' .
		           '<li>' . __( '- Display this information in a public facing manner (such as a web page or API request) and allow website users to search and view submitted listing information.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Send you important account/order/service information.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Provide a way for users to contact your listing via the provided contact information.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Notify you of user interactions such as but not limited to review and contact notifications.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Respond to your queries or complaints.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Set up and administer your account, provide technical and/or customer support, and to verify your identity. We do this on the basis of our legitimate business interests.', 'geodirectory' ) . '</li>' .
		           '</ul>' .
		           '<h2>' . __( 'Reviews', 'geodirectory' ) . '</h2>' .
		           '<p>' . __( 'We collect information about you during the leave a review process on our site. This information may include, but is not limited to, your name, email address, IP address, website url, image(s), review ratings and review texts.', 'geodirectory' ) . '</p>' .
		           '<p>' . __( 'Handling this data also allows us to:', 'geodirectory' ) . '</p>' .
		           '<ul>' .
		           '<li>' . __( '- Display this information in a public facing manner (such as a web page or API request).', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Notify you of interactions such as approval or rejection of your review.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Notify you of user interactions such as reply notifications.', 'geodirectory' ) . '</li>' .
		           '</ul>' .
		           '<h2>' . __( 'Listing contact forms', 'geodirectory' ) . '</h2>' .
		           '<p>' . __( 'We may collect information about you when you submit a contact form to a listing. This information may include, but is not limited to, your name, email address, IP address and contact texts.', 'geodirectory' ) . '</p>' .
		           '<p>' . __( 'Handling this data also allows us to:', 'geodirectory' ) . '</p>' .
		           '<ul>' .
		           '<li>' . __( '- Send your contact message and details to the listings contact email.', 'geodirectory' ) . '</li>' .
		           '<li>' . __( '- Monitor the contact system for spam and abuse.', 'geodirectory' ) . '</li>' .
		           '</ul>';


		return apply_filters( 'geodir_privacy_policy_content', $content) ;
	}

	/**
	 * Handle some custom types of data and anonymize them.
	 *
	 * @param string $anonymous Anonymized string.
	 * @param string $type Type of data.
	 * @param string $data The data being anonymized.
	 * @return string Anonymized string.
	 */
	public function anonymize_custom_data_types( $anonymous, $type, $data ) {
		switch ( $type ) {
			case 'phone':
				$anonymous = preg_replace( '/\d/u', '0', $data );
				break;
			case 'numeric_id':
				$anonymous = 0;
				break;
			case 'gps':
				$anonymous = '0';
				break;
		}
		return $anonymous;
	}

	public static function personal_data_exporter_key() {
		if ( ! wp_doing_ajax() ) {
			return false;
		}

		if ( empty( $_POST['id'] ) ) {
			return false;
		}
		$request_id = (int) $_POST['id'];

		if ( $request_id < 1 ) {
			return false;
		}

		if ( ! current_user_can( 'export_others_personal_data' ) ) {
			return false;
		}

		// Get the request data.
		$request = wp_get_user_request_data( $request_id );

		if ( ! $request || 'export_personal_data' !== $request->action_name ) {
			return false;
		}

		$email_address = $request->email;
		if ( ! is_email( $email_address ) ) {
			return false;
		}

		if ( ! isset( $_POST['exporter'] ) ) {
			return false;
		}
		$exporter_index = (int) $_POST['exporter'];

		if ( ! isset( $_POST['page'] ) ) {
			return false;
		}
		$page = (int) $_POST['page'];

		$send_as_email = isset( $_POST['sendAsEmail'] ) ? ( 'true' === $_POST['sendAsEmail'] ) : false;

		/**
		 * Filters the array of exporter callbacks.
		 *
		 * @since 1.6.26
		 *
		 * @param array $args {
		 *     An array of callable exporters of personal data. Default empty array.
		 *
		 *     @type array {
		 *         Array of personal data exporters.
		 *
		 *         @type string $callback               Callable exporter function that accepts an
		 *                                              email address and a page and returns an array
		 *                                              of name => value pairs of personal data.
		 *         @type string $exporter_friendly_name Translated user facing friendly name for the
		 *                                              exporter.
		 *     }
		 * }
		 */
		$exporters = apply_filters( 'wp_privacy_personal_data_exporters', array() );

		if ( ! is_array( $exporters ) ) {
			return false;
		}

		// Do we have any registered exporters?
		if ( 0 < count( $exporters ) ) {
			if ( $exporter_index < 1 ) {
				return false;
			}

			if ( $exporter_index > count( $exporters ) ) {
				return false;
			}

			if ( $page < 1 ) {
				return false;
			}

			$exporter_keys = array_keys( $exporters );
			$exporter_key  = $exporter_keys[ $exporter_index - 1 ];
			$exporter      = $exporters[ $exporter_key ];
			
			if ( ! is_array( $exporter ) || empty( $exporter_key ) ) {
				return false;
			}
			if ( ! array_key_exists( 'exporter_friendly_name', $exporter ) ) {
				return false;
			}
			if ( ! array_key_exists( 'callback', $exporter ) ) {
				return false;
			}
		}

		/**
		 * Filters a page of personal data exporter.
		 *
		 * @since 1.6.26
		 *
		 * @param array  $exporter_key    The key (slug) of the exporter that provided this data.
		 * @param array  $exporter        The personal data for the given exporter.
		 * @param int    $exporter_index  The index of the exporter that provided this data.
		 * @param string $email_address   The email address associated with this personal data.
		 * @param int    $page            The page for this response.
		 * @param int    $request_id      The privacy request post ID associated with this request.
		 * @param bool   $send_as_email   Whether the final results of the export should be emailed to the user.
		 */
		$exporter_key = apply_filters( 'geodir_privacy_personal_data_exporter', $exporter_key, $exporter, $exporter_index, $email_address, $page, $request_id, $send_as_email );

		return $exporter_key;
	}

	public static function exporter_post_type() {
		$exporter_key = self::personal_data_exporter_key();

		if ( empty( $exporter_key ) ) {
			return false;
		}

		if ( strpos( $exporter_key, 'geodirectory-post-' ) !== 0 ) {
			return false;
		}

		$post_type = str_replace( 'geodirectory-post-', '', $exporter_key );

		if ( $post_type != '' && in_array( $post_type, geodir_get_posttypes() ) ) {
			return $post_type;
		}

		return false;
	}

	public static function allow_export_post_type_data( $post_type ) {
		$allow = true;

		return apply_filters( 'geodir_privacy_allow_export_post_type_data', $allow, $post_type );
	}

	public static function allow_export_reviews_data() {
		$allow = true;

		return apply_filters( 'geodir_privacy_allow_export_reviews_data', $allow );
	}

	public static function allow_erase_reviews_data() {
		$allow = true;

		return apply_filters( 'geodir_privacy_allow_erase_reviews_data', $allow );
	}

	public static function allow_export_favorites_data() {
		$allow = true;

		return apply_filters( 'geodir_privacy_allow_export_favorites_data', $allow );
	}

	public static function allow_erase_favorites_data() {
		$allow = true;

		return apply_filters( 'geodir_privacy_allow_erase_favorites_data', $allow );
	}

	public static function favorites_by_user( $email_address, $page ) {
		if ( empty( $email_address ) ) {
			return array();
		}

		$user = get_user_by( 'email', $email_address );
		if ( empty( $user ) ) {
			return array();
		}

		$favourites = geodir_get_user_favourites( $user->ID );

		return ( ! empty( $favourites ) && is_array( $favourites ) ? $favourites : array() );
	}
}

new GeoDir_Privacy();
