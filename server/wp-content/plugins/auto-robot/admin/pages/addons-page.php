<?php
/**
 * Auto_Robot_Addons_Page Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Addons_Page' ) ) :

    class Auto_Robot_Addons_Page extends Auto_Robot_Admin_Page {

        /**
         * Add page screen hooks
         *
         * @since 1.0.0
         * @param $hook
         */
        public function enqueue_scripts( $hook ) {

            // Load admin styles
            auto_robot_admin_enqueue_styles( AUTO_ROBOT_VERSION );

        }
    }

endif;