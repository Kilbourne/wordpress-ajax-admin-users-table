<?php

namespace UTEC\Rest;

use UTEC\Common\Interfaces\Has_Hooks;
use UTEC\Admin\Request_Utils;
use UTEC\Data\Users;
use UTEC\Utils;
use UTEC\Admin\Table_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Our Rest API route logic to get and send users data to the React JS app.
 */
class Get_Users implements Has_Hooks {
	/**
	 * Necessary hooks
	 */
	public function hooks() {
		add_action( 'rest_api_init', [ $this, 'register_route' ] );
	}

	/**
	 * Register our /get-users route
	 */
	public function register_route() {
		$namespace = 'utec/v1';

		register_rest_route(
			$namespace,
			'get-users', 
			[
				'methods' => 'POST',
				'callback' => [ $this, 'process_request' ],
			]
		);
	}

	/**
	 * Process our /get-users route request.
	 *
	 * @param \WP_REST_Request $rest_request
	 * @return \WP_REST_Response Response data sent to React JS app.
	 */
	public function process_request( \WP_REST_Request $rest_request ) {
		$capability = apply_filters( 'utec_admin_table_capability', Table_Page::CAPABILITY );

		if ( ! current_user_can( $capability ) || ! wp_verify_nonce( $rest_request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
			return new \WP_REST_Response(
				[
					'error' => new \WP_Error( 'unauthorized_access', __( 'You can\'t do that.', 'utec' ), [ 'status' => 403 ] )
				]
			);
		}

		load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );

		$original_request = $rest_request->get_param( 'request' );
		$request          = Request_Utils::get_current_request( $original_request );
		$users_class      = utec()->get_service( 'users' );
		$users            = $users_class->get_users( $request );

		$response = [
			'request'    => $request,
			'users'      => $users['users'],
			'pagination' => [
				'current_page' => (int) $request['paged'],
				'total_pages'  => (int) $users['total_pages'],
				'total_users'  => (int) $users['total_users'],
			],
		];

		return new \WP_REST_Response( $response );
	}
}
