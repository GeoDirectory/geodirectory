<?php
/**
 * Personal data exporters.
 *
 * @since 1.2.26
 * @package GeoDirectory
 */

defined( 'ABSPATH' ) || exit;

/**
 * GeoDir_Privacy_Exporters Class.
 */
class GeoDir_Privacy_Exporters {
	/**
	 * Finds and exports data which could be used to identify a person from GeoDirectory data associated with an email address.
	 *
	 * Posts are exported in blocks of 10 to avoid timeouts.
	 *
	 * @since 1.2.26
	 * @param string $email_address The user email address.
	 * @param int    $page  Page.
	 * @return array An array of personal data in name value pairs
	 */
	public static function post_data_exporter( $email_address, $page ) {
		$done           = false;
		$page           = (int) $page;
		$user           = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.
		$data_to_export = array();

		$post_query    = array(
			'limit'    => 10,
			'page'     => $page,
			'author'   => array( $email_address ),
		);

		if ( $user instanceof WP_User ) {
			$order_query['author'][] = (int) $user->ID;
		}

		$posts = array();

		if ( 0 < count( $posts ) ) {
			foreach ( $posts as $post ) {
				$data_to_export[] = array(
					'group_id'    => 'geodirectory_posts',
					'group_label' => __( 'Posts', 'geodirectory' ),
					'item_id'     => 'post-' . $post->ID,
					'data'        => self::get_post_personal_data( $post ),
				);
			}
			$done = 10 > count( $posts );
		} else {
			$done = true;
		}

		return array(
			'data' => $data_to_export,
			'done' => $done,
		);
	}

	/**
	 * Get personal data (key/value pairs) for an post object.
	 *
	 * @since 1.6.26
	 * @param WP_Post $post Post object.
	 * @return array
	 */
	protected static function get_post_personal_data( $post ) {
		$personal_data = array(
			array(
				'name'  => __( 'Post ID', 'geodirectory' ),
				'value' => $post->ID,
			),
			array(
				'name'  => __( 'Post Title', 'geodirectory' ),
				'value' => get_the_title( $post->ID ),
			),
			array(
				'name'  => __( 'Post Date', 'geodirectory' ),
				'value' => date_i18n( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), strtotime( $post->post_date ) ),
			),
			// TODO more fields
		);

		/**
		 * Allow extensions to register their own personal data for this post for the export.
		 *
		 * @since 1.6.26
		 * @param array    $personal_data Array of name value pairs to expose in the export.
		 * @param WP_Post $post The post object.
		 */
		$personal_data = apply_filters( 'geodir_privacy_export_post_personal_data', $personal_data, $post );

		return $personal_data;
	}
}
