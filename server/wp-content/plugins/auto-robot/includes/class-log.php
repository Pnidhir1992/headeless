<?php
/**
 * Auto Robot Log Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Auto_Robot_Log' ) ) :

	/**
	 * Auto Robot Log
	 */
	class Auto_Robot_Log {

        /**
         * Campaign ID
         *
         * @int
         */
        public $campaign_id;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 */
		public function __construct($campaign_id) {
            $this->campaign_id = $campaign_id;
            $this->start();
        }

        /**
         * Campaign Job Start
         *
         * @since 1.0.0
         * @return void
         */
        public function start() {

            $level = 'info';

            $this->add( 'Started Campaign Job Process', $level );

            $this->add( '# System Details: ', $level  );
            $this->add( "Debug Mode \t\t: " . $this->get_debug_mode(), $level );
            $this->add( "Operating System \t: " . $this->get_os(), $level );
            $this->add( "Software \t\t: " . $this->get_software(), $level );
            $this->add( "MySQL version \t\t: " . $this->get_mysql_version(), $level );
            $this->add( "XML Reader \t\t: " . $this->get_xmlreader_status(), $level );
            $this->add( "PHP Version \t\t: " . $this->get_php_version(), $level );
            $this->add( "PHP Max Input Vars \t: " . $this->get_php_max_input_vars(), $level );
            $this->add( "PHP Max Post Size \t: " . $this->get_php_max_post_size(), $level );
            $this->add( "PHP Extension GD \t: " . $this->get_php_extension_gd(), $level );
            $this->add( "PHP Max Execution Time \t: " . $this->get_max_execution_time(), $level );
            $this->add( "Max Upload Size \t: " . size_format( wp_max_upload_size() ), $level );
            $this->add( "Memory Limit \t\t: " . $this->get_memory_limit(), $level );
            $this->add( "Timezone \t\t: " . $this->get_timezone(), $level );
            $this->add( 'Importing Started! - ' . $this->current_time(), $level );

        }

        /**
         * Write content to a log table.
         *
         * @since 1.1.0
         * @param string $content content to be saved to the file.
         */
        public function add( $content, $level = 'info' ) {

            global $wpdb;
            $wpdb->insert(
				"{$wpdb->prefix}auto_robot_logs",
				array(
					'camp_id'    =>  $this->campaign_id,
                    'message'    =>  $content,
                    'level'      =>  $level,
					'created' =>  microtime( true ),
				)
			);
        }

        /**
         * Debug Mode
         *
         * @since 1.1.0
         * @return string Enabled for Debug mode ON and Disabled for Debug mode Off.
         */
        public function get_debug_mode() {
            if ( WP_DEBUG ) {
                return __( 'Enabled', 'auto-robot' );
            }

            return __( 'Disabled', 'auto-robot' );
        }

        /**
         * Operating System
         *
         * @since 1.1.0
         * @return string Current Operating System.
         */
        public function get_os() {
            return PHP_OS;
        }

        /**
         * Server Software
         *
         * @since 1.1.0
         * @return string Current Server Software.
         */
        public function get_software() {
            return $_SERVER['SERVER_SOFTWARE'];
        }

        /**
         * MySql Version
         *
         * @since 1.1.0
         * @return string Current MySql Version.
         */
        public function get_mysql_version() {
            global $wpdb;
            return $wpdb->db_version();
        }

        /**
         * XML Reader
         *
         * @since 1.2.8
         * @return string Current XML Reader status.
         */
        public function get_xmlreader_status() {

            if ( class_exists( 'XMLReader' ) ) {
                return esc_html__( 'Yes', 'auto-robot' );
            }

            return esc_html__( 'No', 'auto-robot' );
        }

        /**
         * PHP Version
         *
         * @since 1.1.0
         * @return string Current PHP Version.
         */
        public function get_php_version() {
            if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
                return _x( 'We recommend to use php 5.4 or higher', 'PHP Version', 'auto-robot' );
            }
            return PHP_VERSION;
        }

        /**
         * PHP Max Input Vars
         *
         * @since 1.1.0
         * @return string Current PHP Max Input Vars
         */
        public function get_php_max_input_vars() {
            return ini_get( 'max_input_vars' ); // phpcs:disable PHPCompatibility.IniDirectives.NewIniDirectives.max_input_varsFound
        }

        /**
         * PHP Max Post Size
         *
         * @since 1.1.0
         * @return string Current PHP Max Post Size
         */
        public function get_php_max_post_size() {
            return ini_get( 'post_max_size' );
        }

        /**
         * PHP Max Execution Time
         *
         * @since 1.1.0
         * @return string Current Max Execution Time
         */
        public function get_max_execution_time() {
            return ini_get( 'max_execution_time' );
        }

        /**
         * PHP GD Extension
         *
         * @since 1.1.0
         * @return string Current PHP GD Extension
         */
        public function get_php_extension_gd() {
            if ( extension_loaded( 'gd' ) ) {
                return esc_html__( 'Yes', 'auto-robot' );
            }

            return esc_html__( 'No', 'auto-robot' );
        }

        /**
         * Memory Limit
         *
         * @since 1.1.0
         * @return string Memory limit.
         */
        public function get_memory_limit() {

            $required_memory                = '64M';
            $memory_limit_in_bytes_current  = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
            $memory_limit_in_bytes_required = wp_convert_hr_to_bytes( $required_memory );

            if ( $memory_limit_in_bytes_current < $memory_limit_in_bytes_required ) {
                return sprintf(
                /* translators: %1$s Memory Limit, %2$s Recommended memory limit. */
                    _x( 'Current memory limit %1$s. We recommend setting memory to at least %2$s.', 'Recommended Memory Limit', 'auto-robot' ),
                    WP_MEMORY_LIMIT,
                    $required_memory
                );
            }

            return WP_MEMORY_LIMIT;
        }

        /**
         * Timezone
         *
         * @since 1.1.0
         * @see https://codex.wordpress.org/Option_Reference/
         *
         * @return string Current timezone.
         */
        public function get_timezone() {
            $timezone = get_option( 'timezone_string' );

            if ( ! $timezone ) {
                return get_option( 'gmt_offset' );
            }

            return $timezone;
        }

        /**
         * Current Time for log.
         *
         * @since 1.1.0
         * @return string Current time with time zone.
         */
        public function current_time() {
            return gmdate( 'H:i:s' ) . ' ' . date_default_timezone_get();
        }
    }

endif;
