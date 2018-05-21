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
	 * Background process to clean up orders.
	 *
	 * @var GeoDir_Privacy_Background_Process
	 */
	protected static $background_process;

	/**
	 * Init - hook into events.
	 */
	public function __construct() {
		parent::__construct( __( 'GeoDirectory', 'geodirectory' ) );

		if ( ! self::$background_process ) {
			self::$background_process = new GeoDir_Privacy_Background_Process();
		}

		// Include supporting classes.
		include_once( 'class-geodir-privacy-erasers.php' );
		include_once( 'class-geodir-privacy-exporters.php' );

		$gd_post_types = geodir_get_posttypes( 'object' );

		if ( ! empty( $gd_post_types ) ) {
			foreach ( $gd_post_types as $post_type => $info ) {
				$name = $info->labels->name;

				// This hook registers GeoDirectory data exporters.
				$this->add_exporter( 'geodirectory-post-' . $post_type, wp_sprintf( __( 'User %s', 'geodirectory' ), $name ), array( 'GeoDir_Privacy_Exporters', 'post_data_exporter' ) );

				// This hook registers GeoDirectory data erasers.
				$this->add_eraser( 'geodirectory-post-' . $post_type, wp_sprintf( __( 'User %s', 'geodirectory' ), $name ), array( 'GeoDir_Privacy_Erasers', 'post_data_eraser' ) );
			}
		}

		// Cleanup orders daily - this is a callback on a daily cron event.
		add_action( 'geodir_cleanup_personal_data', array( $this, 'queue_cleanup_personal_data' ) );

		// Handles custom anonomization types not included in core.
		add_filter( 'wp_privacy_anonymize_data', array( $this, 'anonymize_custom_data_types' ), 10, 3 );

		// When this is fired, data is removed in a given order. Called from bulk actions.
		add_action( 'geodir_remove_post_personal_data', array( 'GeoDir_Privacy_Erasers', 'remove_post_personal_data' ) );

		// Review data
		add_filter( 'wp_privacy_personal_data_export_page', array( 'GeoDir_Privacy_Exporters', 'review_data_exporter' ), 10, 7 );
	}

	/**
	 * Add privacy policy content for the privacy policy page.
	 *
	 * @since 1.6.26
	 *
	 * @return string The default policy content.
	 */
	public function get_privacy_message() {
		$content = '';

		// Start of the suggested privacy policy text.
		$content .=
			'<div class="geodir-privacy-text">';
		$content .=
			'<h2>' . __( 'Who we are' ) . '</h2>';
		$content .=
			'<p>' . __( 'GeoDirectory is the only WordPress directory plugin on the market that can scale to millions of listings and withstand the battering of traffic that comes along with that.' ) . '</p>';

			'<h2>' . __( 'What personal data we collect and why we collect it' ) . '</h2>';
		$content .=
			'<p>' . __( 'In this section you should note what personal data you collect from users and site visitors. This may include personal data, such as name, email address, personal account preferences; transactional data, such as purchase information; and technical data, such as information about cookies.' ) . '</p>' .
			'<p>' . __( 'You should also note any collection and retention of sensitive personal data, such as data concerning health.' ) . '</p>' .
			'<p>' . __( 'In addition to listing what personal data you collect, you need to note why you collect it. These explanations must note either the legal basis for your data collection and retention or the active consent the user has given.' ) . '</p>' .
			'<p>' . __( 'Personal data is not just created by a user&#8217;s interactions with your site. Personal data is also generated from technical processes such as contact forms, comments, cookies, analytics, and third party embeds.' ) . '</p>' .
			'<p>' . __( 'By default WordPress does not collect any personal data about visitors, and only collects the data shown on the User Profile screen from registered users. However some of your plugins may collect personal data. You should add the relevant information below.' ) . '</p>';

		$content .=
			'<h3>' . __( 'Posts' ) . '</h3>';
		$content .=
			'<p>' . __( 'In this subsection you should note what information is captured through posts. We have noted the data which WordPress collects by default.' ) . '</p>' . 
			'<p>' . __( 'When users add posts on the site we collect the data shown in the posts form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.' ) . '</p>' .
			'<p>' . __( 'An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your post, your profile picture is visible to the public in the context of your post.' ) . '</p>';

		$content .=
			'<h3>' . __( 'Reviews' ) . '</h3>';
		$content .=
			'<p>' . __( 'In this subsection you should note what information is captured through reviews. We have noted the data which WordPress collects by default.' ) . '</p>' . 
			'<p>' . __( 'When visitors leave reviews on the site we collect the data shown in the reviews form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.' ) . '</p>' .
			'<p>' . __( 'An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your review, your profile picture is visible to the public in the context of your review.' ) . '</p>';

		$content .=
			'<h3>' . __( 'Media' ) . '</h3>';
		$content .=
			'<p>' . __( 'In this subsection you should note what information may be disclosed by users who can upload media files. All uploaded files are usually publicly accessible.' ) . '</p>' . 
			'<p>' . __( 'If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.' ) . '</p>';

		$content .=
			'<h2>' . __( 'How long we retain your data' ) . '</h2>';
		$content .=
			'<p>' . __( 'In this section you should explain how long you retain personal data collected or processed by the web site. While it is your responsibility to come up with the schedule of how long you keep each dataset for and why you keep it, that information does need to be listed here. For example, you may want to say that you keep contact form entries for six months, analytics records for a year, and customer purchase records for ten years.' ) . '</p>' . 
			'<p>' . __( 'If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.' ) . '</p>' .
			'<p>' . __( 'For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.' ) . '</p>' .

			'<h2>' . __( 'What rights you have over your data' ) . '</h2>';
		$content .=
			'<p>' . __( 'In this section you should explain what rights your users have over their data and how they can invoke those rights.' ) . '</p>' . 
			'<p>' . __( 'If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.' ) . '</p>';

		$content .=
			'<h2>' . __( 'Where we send your data' ) . '</h2>';
		$content .=
			'<p>' . __( 'In this section you should list all transfers of your site data outside the European Union and describe the means by which that data is safeguarded to European data protection standards. This could include your web hosting, cloud storage, or other third party services.' ) . '</p>' .
			'<p>' . __( 'European data protection law requires data about European residents which is transferred outside the European Union to be safeguarded to the same standards as if the data was in Europe. So in addition to listing where data goes, you should describe how you ensure that these standards are met either by yourself or by your third party providers, whether that is through an agreement such as Privacy Shield, model clauses in your contracts, or binding corporate rules.' ) . '</p>' . 
			'<p>' . __( 'Visitor comments may be checked through an automated spam detection service.' ) . '</p>';

		$content .=
			'<h2>' . __( 'Additional information' ) . '</h2>';
		$content .=
			'<p>' . __( 'If you use your site for commercial purposes and you engage in more complex collection or processing of personal data, you should note the following information in your privacy policy in addition to the information we have already discussed.' ) . '</p>';

		$content .=
			'<h3>' . __( 'How we protect your data' ) . '</h3>';
		$content .=
			'<p>' . __( 'In this section you should explain what measures you have taken to protect your users&#8217; data. This could include technical measures such as encryption; security measures such as two factor authentication; and measures such as staff training in data protection. If you have carried out a Privacy Impact Assessment, you can mention it here too.' ) . '</p>';

		$content .=
			'<h3>' . __( 'What data breach procedures we have in place' ) . '</h3>';
		$content .=
			'<p>' . __( 'In this section you should explain what procedures you have in place to deal with data breaches, either potential or real, such as internal reporting systems, contact mechanisms, or bug bounties.' ) . '</p>';

		$content .=
			'<h3>' . __( 'What third parties we receive data from' ) . '</h3>';
		$content .=
			'<p>' . __( 'If your web site receives data about users from third parties, including advertisers, this information must be included within the section of your privacy policy dealing with third party data.' ) . '</p>';

		$content .=
			'<h3>' . __( 'What automated decision making and/or profiling we do with user data' ) . '</h3>';
		$content .=
			'<p>' . __( 'If your web site provides a service which includes automated decision making - for example, allowing customers to apply for credit, or aggregating their data into an advertising profile - you must note that this is taking place, and include information about how that information is used, what decisions are made with that aggregated data, and what rights users have over decisions made without human intervention.' ) . '</p>';

		$content .=
			'<h3>' . __( 'Industry regulatory disclosure requirements' ) . '</h3>';
		$content .=
			'<p>' . __( 'If you are a member of a regulated industry, or if you are subject to additional privacy laws, you may be required to disclose that information here.' ) . '</p>' .
			'</div>';

		return apply_filters( 'geodir_privacy_policy_content', $content );
	}

	/**
	 * Spawn events for order cleanup.
	 */
	public function queue_cleanup_personal_data() {
		self::$background_process->push_to_queue( array( 'task' => 'trash_pending_posts' ) );
		self::$background_process->push_to_queue( array( 'task' => 'anonymize_published_posts' ) );
		self::$background_process->save()->dispatch();
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
			case 'city':
			case 'region':
			case 'country':
				$anonymous = '';
				break;
			case 'phone':
				$anonymous = preg_replace( '/\d/u', '0', $data );
				break;
			case 'numeric_id':
				$anonymous = 0;
				break;
		}
		return $anonymous;
	}

	/**
	 * Find and trash old posts.
	 *
	 * @since 1.6.26
	 * @param  int $limit Limit posts to process per batch.
	 * @return int Number of posts processed.
	 */
	public static function trash_pending_posts( $limit = 20 ) {
		return 0;
	}

	/**
	 * Anonymize old published posts.
	 *
	 * @since 1.6.26
	 * @param  int $limit Limit posts to process per batch.
	 * @return int Number of posts processed.
	 */
	public static function anonymize_published_posts( $limit = 20 ) {
		return 0;
	}

	/**
	 * For a given query trash all matches.
	 *
	 * @since 3.4.0
	 * @param array $query Query array to pass to wc_get_orders().
	 * @return int Count of orders that were trashed.
	 */
	protected static function trash_posts_query( $query ) {
		$posts = array();
		$count  = 0;

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$count ++;
			}
		}

		return $count;
	}

	/**
	 * For a given query, anonymize all matches.
	 *
	 * @since 1.6.26
	 * @param array $query Query array.
	 * @return int Count of orders that were anonymized.
	 */
	protected static function anonymize_posts_query( $query ) {
		$posts = array();
		$count  = 0;

		if ( $posts ) {
			foreach ( $posts as $post ) {
				GeoDir_Privacy_Erasers::remove_post_personal_data( $post );
				$count ++;
			}
		}

		return $count;
	}

	public static function get_personal_data_exporters() {
		/**
		 * Filters the array of exporter callbacks.
		 *
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

		return $exporters;
	}

	public static function get_personal_data_exporter_key() {
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
}

new GeoDir_Privacy();
