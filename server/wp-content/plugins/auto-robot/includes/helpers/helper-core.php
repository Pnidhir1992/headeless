<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Return needed cap for admin pages
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_get_admin_cap() {
	$cap = 'manage_options';

	if ( is_multisite() && is_network_admin() ) {
		$cap = 'manage_network';
	}

	return apply_filters( 'auto_robot_admin_cap', $cap );
}

/**
 * Enqueue admin fonts
 *
 * @since 1.0.0
 * @since 1.5.1 implement $version
 *
 * @param $version
 */
function auto_robot_admin_enqueue_fonts( $version ) {
	wp_enqueue_style(
		'auto_robot-roboto',
		'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i',
		array(),
		'1.0.0'
	); // cache as long as you can
	wp_enqueue_style(
		'auto_robot-opensans',
		'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i',
		array(),
		'1.0.0'
	); // cache as long as you can
	wp_enqueue_style(
		'auto_robot-source',
		'https://fonts.googleapis.com/css?family=Source+Code+Pro',
		array(),
		'1.0.0'
	); // cache as long as you can

	// if plugin internal font need to enqueued, please use $version as its subject to cache
}

/**
 * Enqueue admin styles
 *
 * @since 1.0.0
 * @since 1.1 Remove auto_robot-admin css after migrate to shared-ui
 *
 * @param $version
 */
function auto_robot_admin_enqueue_styles( $version ) {
	wp_enqueue_style( 'magnific-popup', AUTO_ROBOT_URL . 'assets/css/magnific-popup.css', array(), $version, false );
    wp_enqueue_style( 'auto-robot-select2-style', AUTO_ROBOT_URL . 'assets/css/select2.min.css', array(), $version, false );
    wp_enqueue_style( 'auto-robot-main-style', AUTO_ROBOT_URL . 'assets/css/main.css', array(), $version, false );
}

/**
 * Enqueue front styles
 * @param $version
 */
function auto_robot_front_enqueue_styles( $version ) {
    wp_enqueue_style( 'auto-robot-front-style', AUTO_ROBOT_URL . 'assets/css/front.css', array(), $version, false );
}

/**
 * Load admin scripts
 *
 * @since 1.0.0
 */
function auto_robot_admin_jquery_ui_init() {
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-mouse' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-resize' );
	wp_enqueue_style( 'wp-color-picker' );
}

/**
 * Enqueue admin scripts
 *
 * @since 1.0.0
 *
 * @param       $version
 * @param array $data
 * @param array $l10n
 */
function auto_robot_admin_enqueue_scripts( $version, $data = array(), $l10n = array() ) {

    if ( function_exists( 'wp_enqueue_editor' ) ) {
        wp_enqueue_editor();
    }
    if ( function_exists( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

	wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js', array(), $version, false );

	wp_enqueue_script( 'jquery-magnific-popup', AUTO_ROBOT_URL . '/assets/js/library/jquery.magnific-popup.min.js', array( 'jquery' ), $version, false );

	wp_enqueue_script( 'auto-robot-chart', AUTO_ROBOT_URL  . '/assets/js/library/Chart.bundle.min.js', array( 'jquery' ), '2.9.8', false );

    wp_enqueue_script( 'auto-robot-select2', AUTO_ROBOT_URL . '/assets/js/library/select2.min.js', array( 'jquery' ), $version, false );


    wp_register_script(
        'auto-robot-admin',
        AUTO_ROBOT_URL . '/assets/js/main.js',
        array(
            'jquery'
        ),
        $version,
        true
    );
	wp_register_script(
		'auto-robot-action',
		AUTO_ROBOT_URL . '/assets/js/action.js',
		array(
			'jquery'
		),
		$version,
		true
	);
    wp_register_script(
		'auto-robot-list',
		AUTO_ROBOT_URL . '/assets/js/list.js',
		array(
			'jquery'
		),
		$version,
		true
	);
	wp_register_script(
		'auto-robot-addon',
		AUTO_ROBOT_URL . '/assets/js/addon.js',
		array(
			'jquery'
		),
		$version,
		true
	);

    wp_enqueue_script( 'auto-robot-admin' );
    wp_enqueue_script( 'auto-robot-action' );
    wp_enqueue_script( 'auto-robot-list' );
	wp_enqueue_script( 'auto-robot-addon' );

    wp_localize_script( 'auto-robot-action', 'Auto_Robot_Data', $data );
}

/**
 * Enqueue admin wizard scripts
 *
 * @since 1.0.0
 *
 * @param       $version
 * @param array $data
 * @param array $l10n
 */
function auto_robot_admin_enqueue_scripts_wizard( $version, $data = array(), $l10n = array() ) {
    wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js', array(), $version, false );

    wp_register_script(
		'auto-robot-wizard',
		AUTO_ROBOT_URL . '/assets/js/wizard.js',
		array(
			'jquery'
		),
		$version,
		true
	);

    wp_enqueue_script( 'auto-robot-wizard' );

    wp_localize_script( 'auto-robot-wizard', 'Auto_Robot_Data', $data );
}

/**
 * Enqueue admin welcome scripts
 *
 * @since 1.0.0
 *
 * @param       $version
 * @param array $data
 * @param array $l10n
 */
function auto_robot_admin_enqueue_scripts_welcome( $version, $data = array(), $l10n = array() ) {
    wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js', array(), $version, false );

    wp_register_script(
		'auto-robot-welcome',
		AUTO_ROBOT_URL . '/assets/js/welcome.js',
        array( 'jquery', 'wp-util' ),
		$version,
		true
	);

    wp_register_script(
		'auto-robot-snap',
		AUTO_ROBOT_URL . '/assets/js/library/snap.svg-min.js',
		array(),
		$version,
		true
	);

    wp_enqueue_script( 'auto-robot-snap' );
    wp_enqueue_script( 'auto-robot-welcome' );
    wp_localize_script( 'auto-robot-welcome', 'Auto_Robot_Data', $data );
}

/**
 * Enqueue admin settings scripts
 *
 * @since 1.0.0
 *
 * @param       $version
 * @param array $data
 * @param array $l10n
 */
function auto_robot_admin_enqueue_scripts_settings( $version, $data = array(), $l10n = array() ) {
    wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js', array(), $version, false );

    wp_register_script(
		'auto-robot-settings',
		AUTO_ROBOT_URL . '/assets/js/settings.js',
        array( 'jquery', 'wp-util' ),
		$version,
		true
	);
    wp_enqueue_script( 'auto-robot-settings' );
    wp_localize_script( 'auto-robot-settings', 'Auto_Robot_Data', $data );
}

/**
 * Enqueue admin import scripts
 *
 * @since 1.0.0
 *
 * @param       $version
 * @param array $data
 * @param array $l10n
 */
function auto_robot_admin_enqueue_scripts_import( $version, $data = array(), $l10n = array() ) {
    wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js', array(), $version, false );

    wp_register_script(
		'auto-robot-import',
		AUTO_ROBOT_URL . '/assets/js/import.js',
        array( 'jquery', 'wp-util' ),
		$version,
		true
	);

    wp_enqueue_script( 'auto-robot-import' );
    wp_localize_script( 'auto-robot-import', 'Auto_Robot_Data', $data );
}

/**
 * Return AJAX url
 *
 * @since 1.0.0
 * @return mixed
 */
function auto_robot_ajax_url() {
    return admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' );
}

/**
 * Return post status
 *
 * @since 1.0.0
 * @return array
 */
function auto_robot_get_post_status() {
    return apply_filters(
        'auto_robot_post_status',
        array(
            'publish'     => esc_html__( 'Publish',Auto_Robot::DOMAIN ),
            'draft'     => esc_html__( 'Draft',Auto_Robot::DOMAIN ),
        )
    );
}

/**
 * Return Components
 *
 * @since 1.0.0
 * @return array
 */
function auto_robot_get_components($source) {

    $components_dir = "campaigns/wizard/components/";

    $components = array();

    switch ( $source ) {
        case 'rss':
            $components = ['campaign-name', 'feed-links'];
            break;
        case 'search':
            $components = ['campaign-name', 'language-location', 'feed-keywords'];
            break;
        default:
            $components = ['premium-version'];
            break;
    }

    foreach($components as $key => $value){
        $components[$key] = $components_dir.$value;
    }

    return $components;
}

/**
 * Process RSS Campaign Job
 *
 * @since 1.0.0
 * @return array
 */
function auto_robot_process_rss_job($id, $source, $feed_link, $settings){
    // Initial job class and run this job
    $job = new Auto_Robot_RSS_Job( $id, $source, $feed_link, $settings);
    $result = $job->run();

    // Return this job running result
    return $result;
}

/**
 * Process RSS Search Campaign Job
 *
 * @since 1.0.0
 * @return array
 */
function auto_robot_process_search_job($id, $source, $keyword, $settings){
    // Initial job class and run this job
    $job = new Auto_Robot_Search_Job( $id, $source, $keyword, $settings);
    $result = $job->run();

    // Return this job running result
    return $result;
}

/**
 * Process RSS Campaign Job
 *
 * @since 1.0.0
 * @return int
 */
function auto_robot_calculate_next_time($update_frequency, $update_frequency_unit){
    $time_length = 0;

    switch ( $update_frequency_unit ) {
        case 'Minutes':
            $time_length = $update_frequency*60;
            break;
        case 'Hours':
            $time_length = $update_frequency*60*60;
            break;
        case 'Days':
            $time_length = $update_frequency*60*60*24;
            break;
        default:
            break;
    }

    return $time_length;
}

/**
 * Get random keyword
 * @param string
 * @since 1.0.0
 * @return array
 */
function auto_robot_get_random($keywords) {
    $keywords_array = explode(', ', $keywords);
    $rand = array_rand($keywords_array);

    return $keywords_array[$rand];
}

/*
* Checks for a new version of Auto Robot and creates messages if needed
*/
function auto_robot_check_for_newer() {
	if ( ! $updates = get_site_transient( 'update_plugins' ) ) {
		return false;
	}

	$ret = false;
	$key = 'auto-robot/auto-robot-lite.php';

	if ( isset( $updates->response[ $key ] ) ) {
		$old = AUTO_ROBOT_VERSION;
		$new = $updates->response[ $key ]->new_version;
		if ( 1 === version_compare( $new, $old ) ) { // current version is lower than latest
			$msg = sprintf( __( 'A new version of %s is available. Please install it.', Auto_Robot::DOMAIN ), 'Auto Robot' );
			$ret = array( 'msg' => $msg, 'ver' => $new );
		}
	}

	return $ret;
}