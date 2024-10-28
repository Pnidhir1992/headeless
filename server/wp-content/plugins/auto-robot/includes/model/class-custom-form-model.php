<?php
/**
 * Auto_Robot_Base_Form_Model Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Custom_Form_Model' ) ) :

    class Auto_Robot_Custom_Form_Model extends Auto_Robot_Base_Form_Model {

        protected $post_type = 'auto_robot_forms';

        /**
         * @param int|string $class_name
         *
         * @since 1.0
         * @return Auto_Robot_Custom_Form_Model
         */
        public static function model( $class_name = __CLASS__ ) { // phpcs:ignore
            return parent::model( $class_name );
        }

        /**
         * Run Campaign
         *
         * @since 1.0.0
         *
         */
        public function run_campaign() {

            $settings = $this->get_settings();

			$id = $this->id;
			$source = $settings['robot_selected_source'];

            //Get RSS Feed Link
            $feed_link = '';
            if(isset($settings['robot_feed_link'])){
               $feed_link = $settings['robot_feed_link'];
            }

            // Get RSS Search Random Keyword
            $rss_keyword = '';
            if(isset($settings['rss_selected_keywords'])){
                $rss_keywords = $settings['rss_selected_keywords'];
                $rss_keyword = auto_robot_get_random($rss_keywords);
            }

            // Update Campaign Last and Next Run Time
            if ( !is_null( $id ) || $id > 0 ) {
                $form_model = Auto_Robot_Custom_Form_Model::model()->load( $id );
                $settings = $form_model->settings;

                $update_frequency = isset($settings['update_frequency']) ? $settings['update_frequency'] : 5;
                $update_frequency_unit = isset($settings['update_frequency_unit']) ? $settings['update_frequency_unit'] : 'Minutes';

                // Update next run time
                $time_length = auto_robot_calculate_next_time($update_frequency, $update_frequency_unit);
                $settings['next_run_time'] = time() + $time_length;

                // Update last run time
                $settings['last_run_time'] = time();

                // Save Settings to model
                $form_model->settings = $settings;
                // Save data
                $id = $form_model->save();

                $logger = new Auto_Robot_Log($id);
                $logger->add( "Update Campaign Next Run Time \t\t: " . $settings['next_run_time']. "for campaign id ". $id, 'info'  );
            }


            $result = array();
            switch ( $source ) {
                case 'rss':
                    $result = auto_robot_process_rss_job($id, $source, $feed_link, $settings);
                    break;
                case 'search':
                    $result = auto_robot_process_search_job($id, $source, $rss_keyword, $settings);
                    break;
                default:
                    break;
            }


            return $result;
        }
    }

endif;
