<?php
/**
 * Auto_Robot_Logs_Page Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Logs_Page' ) ) :

    class Auto_Robot_Logs_Page extends Auto_Robot_Admin_Page {

        /**
         * Add page screen hooks
         *
         * @since 1.0.0
         * @param $hook
         */
        public function enqueue_scripts( $hook ) {

            parent::enqueue_scripts( $hook );


        }
    }

endif;