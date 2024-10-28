<?php
/**
 * Auto_Robot Class
 *
 * Plugin Core Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Core' ) ) :

   class Auto_Robot_Core {

       /**
       * @var Auto_Robot_Admin
       */
       public $admin;

       /**
       * Store modules objects
       *
       * @var array
       */
       public $modules = array();

       /**
       * Store forms objects
       *
       * @var array
       */
       public $forms = array();

       /**
       * Store fields objects
       *
       * @var array
       */
       public $fields = array();

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
       * @return Auto_Robot_Core
       */
       public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

       /**
       * Auto_Robot_Core constructor.
       *
       * @since 1.0
       */
       public function __construct() {
           // Include all necessary files
           $this->includes();


           if ( is_admin() ) {
              // Initialize admin core
              $this->admin = new Auto_Robot_Admin();
           }

            // Get enabled modules
            $modules       = new Auto_Robot_Modules();
            $this->modules = $modules->get_modules();

            $check_auto_robot_wizard = get_option( 'auto-robot-wizard-set-up' );

            // Add integrations page
            if ( is_admin() ) {
                    // if($check_auto_robot_wizard !== 'opt-in'){
                    //     $this->admin->add_wizard_page();
                    // }
                    $this->admin->add_welcome_page();
                    $this->admin->add_settings_page();
                    $this->admin->add_integrations_page();
                    $this->admin->add_import_page();
                    //$this->admin->add_addons_page();
                    $this->admin->add_logs_page();
                    //$this->admin->add_help_page();
                    $this->admin->add_upgrade_page();
            }

            // Enqueue scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

       }

       /**
       * Add enqueue scripts hooks
       * @param $hook
       */
       public function enqueue_scripts( $hook ) {
          // Enqueue front styles
          auto_robot_front_enqueue_styles( AUTO_ROBOT_VERSION );
       }

       /**
       * Includes
       *
       * @since 1.0.0
       */
       private function includes() {
           // Library
           if ( ! class_exists( 'simple_html_dom_node' ) ){
            require_once AUTO_ROBOT_DIR . 'includes/library/simple_html_dom.php';
           }

           // Helpers
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-core.php';
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-forms.php';
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-fields.php';
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-addons.php';
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-user-data.php';
           require_once AUTO_ROBOT_DIR . 'includes/helpers/helper-report.php';

           // Readability
           require_once AUTO_ROBOT_DIR . 'includes/readability/auto_robot_Readability.php';

           // Model
           require_once AUTO_ROBOT_DIR . 'includes/model/class-base-form-model.php';
           require_once AUTO_ROBOT_DIR . 'includes/model/class-custom-form-model.php';


           // Jobs
           require_once AUTO_ROBOT_DIR . 'includes/jobs/abstract-class-job.php';
           require_once AUTO_ROBOT_DIR . 'includes/jobs/class-rss-job.php';
           require_once AUTO_ROBOT_DIR . 'includes/jobs/class-search-job.php';

           // Classes
           require_once AUTO_ROBOT_DIR . 'includes/class-loader.php';
           require_once AUTO_ROBOT_DIR . 'includes/class-modules.php';
           require_once AUTO_ROBOT_DIR . 'includes/class-log.php';
           require_once AUTO_ROBOT_DIR . 'includes/class-schedule.php';


           if ( is_admin() ) {
               require_once AUTO_ROBOT_DIR . 'admin/abstracts/class-admin-page.php';
               require_once AUTO_ROBOT_DIR . 'admin/abstracts/class-admin-module.php';
               require_once AUTO_ROBOT_DIR . 'admin/classes/class-admin.php';
           }

       }

   }

endif;
