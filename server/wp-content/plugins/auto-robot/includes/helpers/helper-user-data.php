<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Save user data
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_save_user_data(){
	global $wp_version, $wpdb;
    $theme_details = array();
	if ( $wp_version >= 3.4 ) {
		$active_theme                   = wp_get_theme();
		$theme_details['theme_name']    = strip_tags( $active_theme->name );
		$theme_details['theme_version'] = strip_tags( $active_theme->version );
		$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
    }
    
	$plugin_stat_data                     = array();
	$plugin_stat_data['plugin_slug']      = 'auto-robot-lite';
	$plugin_stat_data['type']             = 'standard_edition';
	$plugin_stat_data['version_number']   = AUTO_ROBOT_VERSION;
	$plugin_stat_data['event']            = 'activate';
	$plugin_stat_data['domain_url']       = site_url();
	$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
	$plugin_stat_data['email']            = get_option( 'admin_email' );
	$plugin_stat_data['wp_version']       = $wp_version;
	$plugin_stat_data['php_version']      = sanitize_text_field( phpversion() );
	$plugin_stat_data['mysql_version']    = $wpdb->db_version();
	$plugin_stat_data['max_input_vars']   = ini_get( 'max_input_vars' );
	$plugin_stat_data['operating_system'] = PHP_OS . '  (' . PHP_INT_SIZE * 8 . ') BIT';
	$plugin_stat_data['php_memory_limit'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
	$plugin_stat_data['extensions']       = get_loaded_extensions();
	$plugin_stat_data['plugins']          = auto_robot_get_plugin_info();
	$plugin_stat_data['themes']           = $theme_details;
	$url                                  = WPHOBBY_STATS_URL . '/index.php?rest_route=/wphobby/v1/user/post/';
	$response                             = wp_safe_remote_post(
		$url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => json_encode($plugin_stat_data),
			)
		);	
	
	if ( ! is_wp_error( $response ) ) {
		return $response['body'];
	}
	die( 'success' );	
						
}

/**
 * Get plugin info
 *
 * @since 1.0.0
 */
function auto_robot_get_plugin_info() {
	$active_plugins = (array) get_option( 'active_plugins', array() );
	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}
	$plugins = array();
	if ( count( $active_plugins ) > 0 ) {
		$get_plugins = array();
		foreach ( $active_plugins as $plugin ) {
			$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );// @codingStandardsIgnoreLine

				$get_plugins['plugin_name']    = strip_tags( $plugin_data['Name'] );
				$get_plugins['plugin_author']  = strip_tags( $plugin_data['Author'] );
				$get_plugins['plugin_version'] = strip_tags( $plugin_data['Version'] );
				array_push( $plugins, $get_plugins );
		}
		return $plugins;
	}
}