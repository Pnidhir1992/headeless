<?php
/**
 * Auto_Robot_Admin_Data Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Admin_Data' ) ) :

    class Auto_Robot_Admin_Data {

        public $core = null;

        /**
         * Current Nonce
         *
         * @since 1.0.0
         * @var string
         */
        private $_nonce = '';

        /**
         * Auto_Robot_Admin_Data constructor.
         *
         * @since 1.0.0
         */
        public function __construct() {
            $this->core = Auto_Robot::get_instance();

            $this->generate_nonce();

        }

        /**
         * Combine Data and pass to JS
         *
         * @since 1.0.0
         * @return array
         */
        public function get_options_data() {
            $data           = $this->admin_js_defaults();
            $data           = apply_filters( 'auto_robot_data', $data );

            return $data;
        }

        /**
         * Generate nonce
         *
         * @since 1.0.0
         */
        public function generate_nonce() {
            $this->_nonce = wp_create_nonce( 'auto-robot' );
        }

        /**
         * Get current generated nonce
         *
         * @since 1.0.0
         * @return string
         */
        public function get_nonce() {
            return $this->_nonce;
        }

        /**
         * Return published pages
         *
         * @since 1.8
         *
         * @return mixed
         */
        public function get_pages() {
            $args = array(
                'sort_order' => 'DESC',
                'sort_column' => 'ID',
                'hierarchical' => 1,
                'exclude' => '',
                'include' => '',
                'meta_key' => '',
                'meta_value' => '',
                'authors' => '',
                'child_of' => 0,
                'parent' => -1,
                'exclude_tree' => '',
                'number' => '',
                'offset' => 0,
                'post_type' => 'page',
                'post_status' => 'publish'
            );

            $pages = get_pages($args);

            return $pages;
        }

        /**
         * Default Admin properties
         *
         * @since 1.0.0
         * @return array
         */
        public function admin_js_defaults() {

            return array(
                'ajaxurl'                        => auto_robot_ajax_url(),
                '_ajax_nonce'                    => $this->get_nonce(),
                'wizard_url'                     => admin_url( 'admin.php?page=auto-robot-campaign-wizard' ),
                'integrations_url'               => admin_url( 'admin.php?page=auto-robot-integrations' ),
                'dashboard_url'                  => admin_url( 'admin.php?page=auto-robot' ),
                'campaigns_url'                  => admin_url( 'admin.php?page=auto-robot-campaign' ),
                'pricing_url'                  => admin_url( 'admin.php?page=auto-robot-pricing' ),
            );
        }

    }

endif;
