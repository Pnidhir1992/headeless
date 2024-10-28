<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


/**
 * Generates a performance report
 *
 * @param int $period Days to look back
 *
 * @return string
 */
function auto_robot_generate_report() {
	global $wpdb;

	$global_settings = get_option( 'auto_robot_global_settings', array() );
	$update_frequency = isset($global_settings[ 'update_frequency']) ? $global_settings[ 'update_frequency'] : 7;
    $update_frequency_unit = isset($global_settings[ 'update_frequency_unit']) ? $global_settings[ 'update_frequency_unit'] : 'Days';


	$ret = '';
	$rows = array();
	$stamp = time() - auto_robot_calculate_next_time($update_frequency, $update_frequency_unit);
	$base_url = admin_url( 'admin.php?page=auto-robot-campaign' );
	$css_td = 'padding: 0.5em 0.5em 0.5em 1em; text-align: left;';
	$css_border = 'border-bottom: solid 2px #f9f9f9;';
	$css_table = 'width: 95%; max-width: 1000px; margin:0 auto; margin-bottom: 10px; background-color: #f5f5f5; text-align: center; font-family: Arial, Helvetica, sans-serif;';

	// Get all campaigns
	// Run campaigns job here
	$models = Auto_Robot_Custom_Form_Model::model()->get_all_models();

	$campaigns = $models['models'];
	$activites = array();

	foreach($campaigns as $key=>$model){
		$id = $model->id;
		$title = $model->settings['robot_campaign_name'];
		$query = "
		SELECT * FROM " . $wpdb->prefix . "auto_robot_logs
		WHERE level = 'success' AND created > '" . $stamp . "' AND
		camp_id = " . $id;
		$wpdb->get_results($query);

		$count = $wpdb->num_rows;

		$activites[$key]['id'] = $id;
		$activites[$key]['title'] = $title;
		$activites[$key]['count'] = $count;
	}

	$posts_count = 0;
	foreach ( $activites as $key => $activity) {
		$posts_count += $activity['count'];
	}

	$site_name = ( is_multisite() ) ? get_site_option( 'site_name' ) : get_option( 'blogname' );

	$ret .= '<div style="' . $css_table . '"><div style="margin:0 auto; text-align: center;"><p style="font-size: 130%; padding-top: 0.5em;">' . $site_name . '</p><p style="padding-bottom: 1em;">Last '.$update_frequency.' '.$update_frequency_unit.' Report</p></div></div>';

	$all_campaigns = '<td style="' . $css_td . ' text-align: right;">' . count($campaigns) . '</td><td style="padding: 0.5em; text-align: left;">Total Campaigns</td>';
	$total_posts = '<td style="' . $css_td . ' text-align: right;">' . $posts_count . '</td><td style="padding: 0.5em; text-align: left;">Total Posts</td>';

	$ret .= '<div style="text-align: center; ' . $css_table . '"><table style="font-size: 130%; margin:0 auto;"><tr>' . $all_campaigns . '</tr></table></div>';
	$ret .= '<div style="text-align: center; ' . $css_table . '"><table style="font-size: 130%; margin:0 auto;"><tr>' . $total_posts . '</tr></table></div>';

	// Activities breakdown
	$rows = array();
	$rows[] = '<td style="' . $css_td . $css_border . '" colspan="2"><p style="line-height: 1.5em; font-weight: bold;">' . __( 'Campaigns details', AUTO_ROBOT::DOMAIN ) . '</p></td>';
	if ( $activites ) {
		foreach ( $activites as $key => $activity) {
			$rows[] = '<td style="' . $css_border . $css_td . '">' . $activity['title'] . '</td><td style="padding: 0.5em; text-align: center; width:10%;' . $css_border . '"><a href="' . $base_url . '">' . $activity['count'] . ' posts</a></td>';
		}
	}
	$ret .= '<table style="border-collapse: collapse; ' . $css_table . '"><tr>' . implode( '</tr><tr>', $rows ) . '</tr></table>';

	$ret = '<div style="width:100%; padding: 1em; text-align: center; background-color: #f9f9f9;">' . $ret . '</div>';

	return $ret;
}

/**
 *
 * Send notification letter
 *
 * @param string $type Notification type
 * @param string|array $msg Additional message
 *
 * @return bool
 */
function auto_robot_send_email( $type = '', $msg = '' ) {
	if ( ! $type ) {
		return false;
	}

	$html_mode = true;

	$subj = '[' . get_option( 'blogname' ) . '] ' . __( 'Auto Robot notify', Auto_Robot::DOMAIN ) . ': ';
	$body = '';

	if ( is_array( $msg ) ) {
		$msg = implode( "\n\n", $msg );
	}

	switch ( $type ) {
		case 'report':
			$html_mode = true;
			$subj = '[' . get_option( 'blogname' ) . '] WP Auto Robot: ' . __( 'Stats Report', Auto_Robot::DOMAIN );
			$body = auto_robot_generate_report();
			$link = admin_url( 'admin.php?page=auto-robot-settings' );
			$body .= '<br/>' . __( 'To change reporting settings visit', Auto_Robot::DOMAIN ) . ' <a href="' . $link . '">' . $link . '</a>';
			$body .= $msg;
			break;
		case 'new_version':
			$subj = __( 'A new version of Auto Robot is available to install', Auto_Robot::DOMAIN );
			$body = __( 'Hi!', Auto_Robot::DOMAIN ) . "\n\n";
			$body .= __( 'A new version of Auto Robot is available to install', Auto_Robot::DOMAIN ) . "\n\n";
			$body .= $msg . "\n\n";
			$body .= __( 'Website', Auto_Robot::DOMAIN ) . ': ' . get_option( 'blogname' );
			break;
	}

	$to_list = auto_robot_get_email( $type, true );
	$to      = implode( ', ', $to_list );

	$footer = '';

	$footer .= "\n\n\n" . __( 'This message was sent by', Auto_Robot::DOMAIN ) . ' Auto Robot ' . AUTO_ROBOT_VERSION . "\n";
	$footer .= 'https://wpautorobot.com/pricing';

	if ( $html_mode ) {
		add_filter( 'wp_mail_content_type', 'auto_robot_enable_html' );
		$footer = str_replace( "\n", '<br/>', $footer );
	}

	// Everything is prepared, let's send it out

	$result = null;
	if ( $to && $subj && $body ) {
		if ( function_exists( 'wp_mail' ) ) {
			$body = $body . $footer;
			if ( $html_mode ) {
				$body = '<html>' . $body . '</html>';
			}
			$result = wp_mail( $to, $subj, $body );
		}
	}

	remove_filter('wp_mail_content_type', 'auto_robot_enable_html');

	return $result;
}

/**
 * @param string $type Type of notification email
 * @param bool $array  Return as an array
 *
 * @return array|string Email address(es) for notifications
 */
function auto_robot_get_email( $type = '', $array = false ) {
	$email = '';

	if ( empty( $email ) ) {
		$email = get_site_option( 'admin_email' );
		if ( $array ) {
			$email = array( $email );
		}
	}

	return $email;
}

function auto_robot_enable_html() {
	return 'text/html';
}