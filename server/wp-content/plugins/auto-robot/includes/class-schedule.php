<?php
/**
 * Auto_Robot_Schedule Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Schedule' ) ) :

   class Auto_Robot_Schedule {

       /**
        * Plugin instance
        *
        * @var null
        */
       private static $instance = null;

       /**
        * Return the plugin instance
        *
        * @since 1.0.0
        * @return Auto_Robot_Schedule
        */
       public static function get_instance() {
           if ( is_null( self::$instance ) ) {
               self::$instance = new self();
           }

           return self::$instance;
       }

       /**
       * Auto_Robot_Schedule constructor.
       *
       * @since 1.0.0
       */
       public function __construct() {
           // Add a custom interval
           add_filter( 'cron_schedules', array( $this, 'robot_add_cron_interval' ) );
           // Setup Cron Job
           $this->cron_setup();
       }

       /**
        * Add a custom interval
        *
        * @since 1.0.0
        */
       public function robot_add_cron_interval( $schedules ) {
           $schedules['once_robot_a_minute'] = array(
               'interval' => 60,
               'display'  => esc_html__( 'Once Robot Job a Minute' )
           );
           return $schedules;
       }

       /**
        * Setup Cron Job
        *
        * @since 1.0.0
        */
       public function cron_setup(){
         //wp_clear_scheduled_hook('robot_cron_hook_131');

         if ( ! wp_next_scheduled( 'robot_cron_hook' ) ) {
            wp_schedule_event( time(), 'once_robot_a_minute', 'robot_cron_hook' );
         }

         // Add Cron Job Hook Function
         add_action( 'robot_cron_hook', array( $this, 'robot_cron_exec' )  );

       }

       /**
        * Cron Job Execute
        *
        * @since 1.0.0
        */
       public function robot_cron_exec(){
           $current_time = time();

           // Run campaigns job here
           $models = Auto_Robot_Custom_Form_Model::model()->get_all_models();
           $campaigns = $models['models'];
           foreach($campaigns as $key=>$model){
               $settings = $model->settings;
               // Run this campaign when time on schedule time
               if($current_time >= $settings['next_run_time']){
                   $model->run_campaign();
               }
           }

           // Send email report
           $global_settings = get_option( 'auto_robot_global_settings', array() );

           if ( empty( $global_settings[ 'next_report_time' ] ) ) {
               $global_settings[ 'next_report_time'] = time() + 1*60*60*24;
               update_option( 'auto_robot_global_settings', $global_settings );
           }

		   if($current_time >= $global_settings['next_report_time']){
               $result = auto_robot_send_email('report');
               $update_frequency = isset($global_settings[ 'update_frequency']) ? $global_settings[ 'update_frequency'] : 7;
               $update_frequency_unit = isset($global_settings[ 'update_frequency_unit']) ? $global_settings[ 'update_frequency_unit'] : 'Days';
               // update next report time
               $global_settings[ 'next_report_time'] = time() + auto_robot_calculate_next_time($update_frequency, $update_frequency_unit);
			   update_option( 'auto_robot_global_settings', $global_settings );
           }

           // Check new version and sent notify email
           if ( $new = auto_robot_check_for_newer() ) {
                $history = get_site_option( '_auto_robot_notify_new' );
                if ( ! $history || ! is_array( $history ) ) {
                    $history = array();
                }
                if ( ! in_array( $new['ver'], $history ) ) {
                    auto_robot_send_email( 'new_version', 'Read more: https://wpautorobot.com/pricing' );
                    $history[] = $new['ver'];
                    update_site_option( '_auto_robot_notify_new', $history );
                }
            }
       }

       /**
        * Print Current Cron Jobs
        *
        * @since 1.0.0
        */
       public function robot_print_tasks() {
           echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
       }

   }

endif;
