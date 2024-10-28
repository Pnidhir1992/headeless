<?php
/**
 * Plugin Name: Auto Robot Lite - RSS Feed Autoblogging
 * Plugin URI: http://wpautorobot.com/
 * Description: Generate WordPress posts automatically from RSS Feed, Social Media, Videos, Images, Sound and etc
 * Version: 3.8.48
 * Author: wphobby
 * Author URI: https://wpautorobot.com/pricing
 *
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Set constants
 */
if ( ! defined( 'AUTO_ROBOT_DIR' ) ) {
    define( 'AUTO_ROBOT_DIR', plugin_dir_path(__FILE__) );
}

if ( ! defined( 'AUTO_ROBOT_URL' ) ) {
    define( 'AUTO_ROBOT_URL', plugin_dir_url(__FILE__) );
}

// Plugin Root File.
if ( ! defined( 'AUTO_ROBOT_FILE' ) ) {
	define( 'AUTO_ROBOT_FILE', __FILE__ );
}

if ( ! defined( 'AUTO_ROBOT_VERSION' ) ) {
    define( 'AUTO_ROBOT_VERSION', '3.7.70' );
}

if ( ! defined( 'WPHOBBY_STATS_URL' ) ) {
    define( 'WPHOBBY_STATS_URL', 'https://wpautorobot.com/pricing' );
}

if ( ! defined( 'WPHOBBY_MAIN_URL' ) ) {
    define( 'WPHOBBY_MAIN_URL', 'https://wpautorobot.com/pricing' );
}

/**
 * Class Auto_Robot
 *
 * Main class. Initialize plugin
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Auto_Robot' ) ) {
    /**
     * Auto_Robot
     */
    class Auto_Robot {

        const DOMAIN = 'auto-robot';

        /**
         * Instance of Auto_Robot
         *
         * @since  1.0.0
         * @var (Object) Auto_Robot
         */
        private static $_instance = null;

        /**
         * Get instance of Auto_Robot
         *
         * @since  1.0.0
         *
         * @return object Class object
         */
        public static function get_instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }

        /**
         * Constructor
         *
         * @since  1.0.0
         */
        private function __construct() {
            $this->includes();
            $this->init();
        }

        /**
         * Load plugin files
         *
         * @since 1.0
         */
        private function includes() {
            // Core files.
            require_once AUTO_ROBOT_DIR . '/includes/class-core.php';
            require_once AUTO_ROBOT_DIR . '/includes/class-addon-loader.php';
        }


        /**
         * Init the plugin
         *
         * @since 1.0.0
         */
        private function init() {
            // Initialize plugin core
            $this->auto_robot = Auto_Robot_Core::get_instance();

            // Create tables
            $this->create_tables();

            // Initial Schedule Class for WP Cron Jobs
            Auto_Robot_Schedule::get_instance();

            add_action( 'admin_init', array( $this, 'welcome' ) );

            /**
             * Triggered when plugin is loaded
             */
            do_action( 'auto_robot_loaded' );

            // $skip_premium = get_option( 'auto-robot-skip-premium', false );
            add_action('current_screen', array( $this, 'current_screen_action') );

            add_filter('script_loader_tag', array( $this, 'add_type_attribute') , 10, 3);

            // Add the settings link to a plugin on plugins page.
		    add_filter( 'plugin_action_links_' . plugin_basename( AUTO_ROBOT_FILE ), array( $this, 'add_plugin_action_link'), 10, 3 );
        }

        /**
	    * Add plugin action links on Plugins page (lite version only).
	    *
	    * @since 1.0.0
	    * @since 1.5.0 Added a link to Email Log.
	    * @since 2.0.0 Adjusted links. Process only the Lite plugin.
	    *
	    * @param array $links Existing plugin action links.
	    *
	    * @return array
	    */
	    public function add_plugin_action_link( $links ) {

		    $custom['pro'] = sprintf(
			    '<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer"
				    style="color: #00a32a; font-weight: 700;"
				    onmouseover="this.style.color=\'#008a20\';"
				    onmouseout="this.style.color=\'#00a32a\';"
				    >%3$s</a>',
			    esc_url( 'https://wpautorobot.com/pricing/' ),
			    esc_attr__( 'Upgrade to Auto Robot Pro', 'auto-robot' ),
			    esc_html__( 'Get Auto Robot Pro', 'auto-robot' )
		    );

		    $custom['settings'] = sprintf(
			    '<a href="%s" aria-label="%s">%s</a>',
			    esc_url( admin_url( 'admin.php?page=auto-robot-campaign' ) ),
			    esc_attr__( 'Go to Auto Robot Campaigns page', 'auto-robot' ),
			    esc_html__( 'Campaigns', 'auto-robot' )
		    );

		    $custom['docs'] = sprintf(
			    '<a href="%1$s" target="_blank" aria-label="%2$s" rel="noopener noreferrer">%3$s</a>',
			    esc_url( 'https://wpautorobot.com/document/' ),
			    esc_attr__( 'Go to Auto Robot Documentation page', 'auto-robot' ),
			    esc_html__( 'Docs', 'auto-robot' )
		    );

		    return array_merge( $custom, (array) $links );
	    }

        public function add_type_attribute($tag, $handle, $src) {
            // if not your script, do nothing and return original $tag
            if ( 'ionicons' !== $handle ) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.esm.js"></script>';
            return $tag;
        }

        /** Redirect to welcome page when activation */
		public function welcome() {
            $page_url = 'admin.php?page=auto-robot-campaign';
            if ( ! get_transient( '_auto_robot_activation_redirect' ) ) {
                return;
            }
            delete_transient( '_auto_robot_activation_redirect' );
            wp_safe_redirect( admin_url( $page_url ) );
            exit;
		}

        /**
         * @since 1.0.0
         */
        public static function create_tables() {
            global $wpdb;
            $wpdb->hide_errors();

            $table_schema = [
                "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}auto_robot_logs` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `camp_id` int(11) DEFAULT NULL,
                `level` ENUM('log','info','warn','error','success') NOT NULL DEFAULT 'log',
                `message` text DEFAULT NULL,
                `created` DECIMAL(16, 6) NOT NULL,
                PRIMARY KEY (`id`)
            )  CHARACTER SET utf8 COLLATE utf8_general_ci;",
            ];
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            foreach ( $table_schema as $table ) {
                dbDelta( $table );
            }
        }

        /**
        * Current screen action
        *
        * @since 1.0.1
        * @return void
        */
        public function current_screen_action() {
            $screen = get_current_screen();
            $where = array(
                'toplevel_page_auto-robot',
                'auto-robot_page_auto-robot-campaign',
                'auto-robot_page_auto-robot-integrations',
                'auto-robot_page_auto-robot-wizard',
                'auto-robot_page_auto-robot-campaign-wizard',
                'auto-robot_page_auto-robot-logs',
                'auto-robot_page_auto-robot-settings',
                'auto-robot_page_auto-robot-upgrade',
                'auto-robot_page_auto-robot-welcome',
                'auto-robot_page_auto-robot-addons'
            );

            $enable_notice = true;
            if ( in_array($screen->base, $where) ) {
                $enable_notice = false;
            };

            if($enable_notice){
                add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_notice_scripts' ));
            }else{
                wp_enqueue_style( 'auto-robot-hide-style', AUTO_ROBOT_URL . 'assets/css/hide.css', array(), AUTO_ROBOT_VERSION, false );
            }
        }


        /**
        * Display an admin notice for premium version link
        *
        * @since 1.0.1
        * @return void
        * @use admin_notices hooks
        */
        public function display_admin_notice() {
        ?>
            <div class='robot-notice-container notice success'>
                <div class='robot-notice-inner-wrapper'>
                    <div class="robot-notice-message-container">
                        <h4 class="robot-notice-header"><?php esc_html_e( 'Try the auto robot premium version!', Auto_Robot::DOMAIN ); ?></h4>
                        <span class="robot-notice-message"><?php esc_html_e( 'Generate WordPress posts automatically from RSS Feed, Twitter, Facebook, Instagram, Youtube, Vimeo, Flickr, schedule post, save images to media library, log system, more advanced options, dedicated support and more.', Auto_Robot::DOMAIN ); ?></span>
                    </div>
                    <div class="robot-notice-actions">
                        <a href="<?php echo esc_url('https://wpautorobot.com/pricing') ?>" class="robot-notice-button button button-primary"><?php esc_html_e( 'Activate Premium', Auto_Robot::DOMAIN ); ?></a>
                        <a href="#" class="robot-notice-button robot-notice-skip button"><?php esc_html_e( 'No, thanks, not now', Auto_Robot::DOMAIN ); ?></a>
                    </div>
                </div>
            </div>
        <?php
        }

        public function enqueue_notice_scripts() {
            wp_enqueue_style( 'auto-robot-notice-style', AUTO_ROBOT_URL . 'assets/css/notice.css', array(), AUTO_ROBOT_VERSION, false );
            wp_enqueue_script( 'ionicons', 'https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js', array(), AUTO_ROBOT_VERSION, false );
            wp_register_script(
                'auto-robot-notice',
                AUTO_ROBOT_URL . '/assets/js/notice.js',
                array(
                    'jquery'
                ),
                AUTO_ROBOT_VERSION,
                true
            );

            wp_enqueue_script( 'auto-robot-notice' );

            $auto_robot_data = new Auto_Robot_Admin_Data();
            $data = $auto_robot_data->get_options_data();
            wp_localize_script( 'auto-robot-notice', 'Auto_Robot_Data', $data );
        }
    }
}

if ( ! function_exists( 'auto_robot' ) ) {
    function auto_robot() {
        return Auto_Robot::get_instance();
    }

    /**
     * Init the plugin and load the plugin instance
     *
     * @since 1.0.0
     */
    add_action( 'plugins_loaded', 'auto_robot' );
}

/**
* Plugin install hook
*
* @since 1.8.0
* @return void
*/
if ( ! function_exists( 'auto_robot_install' ) ) {
    function auto_robot_install(){
        // Hook for plugin install.
		do_action( 'auto_robot_install' );

		/*
		* Set current version.
		*/
		update_option( 'auto_robot_version_lite', AUTO_ROBOT_VERSION );

        // Save the date when the initial activation was performed.
		$type      = 'lite';
		$activated = get_option( 'auto_robot_activated', array() );
		if ( empty( $activated[ $type ] ) ) {
			$activated[ $type ] = time();
			update_option( 'auto_robot_activated', $activated );
		}

        set_transient( '_auto_robot_activation_redirect', 1 );
    }
}

// When activated, trigger install method.
register_activation_hook( AUTO_ROBOT_FILE, 'auto_robot_install' );

if ( ! function_exists( 'auto_robot_freemius' ) ) {
        // Create a helper function for easy SDK access.
        function auto_robot_freemius() {
            global $auto_robot_freemius;

            if ( ! isset( $auto_robot_freemius ) ) {
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';

                $auto_robot_freemius = fs_dynamic_init( array(
                    'id'                  => '8133',
                    'slug'                => 'auto-robot',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_67e917c6e577023ba16c224faf5cc',
                    'is_premium'          => false,
                    'is_premium_only'     => false,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'           => 'auto-robot',
                        'first-path'     => 'admin.php?page=auto-robot',
                        'support'        => false,
                    ),
                ) );
            }

            return $auto_robot_freemius;
        }

        // Init Freemius.
        auto_robot_freemius();
        // Signal that SDK was initiated.
        do_action( 'auto_robot_freemius_loaded' );
}