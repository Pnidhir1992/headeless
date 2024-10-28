<?php
/**
 * Auto_Robot_Wizard_Page Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Wizard_Page' ) ) :

	class Auto_Robot_Wizard_Page extends Auto_Robot_Admin_Page {
		/**
         * Add page screen hooks
         *
         * @since 1.0.0
         *
         * @param $hook
         */
        public function enqueue_scripts( $hook ) {
            // Load admin styles
			auto_robot_admin_enqueue_styles( AUTO_ROBOT_VERSION );
			
            $auto_robot_data = new Auto_Robot_Admin_Data();

        	// Load admin scripts
        	auto_robot_admin_enqueue_scripts_wizard(
            	AUTO_ROBOT_VERSION,
            	$auto_robot_data->get_options_data()
        	);
		}
		
		/**
         * Render page container
         *
         * @since 1.0.0
         */
        public function render() {

            $accessibility_enabled = get_option( 'auto_robot_enable_accessibility', false ); ?>

            <main class="robot-wrap <?php echo $accessibility_enabled ? 'robot-color-accessible' : ''; ?> <?php echo esc_attr( 'auto-robot-' . $this->page_slug ); ?>">

                <?php
                $this->render_page_content();
                ?>

            </main>

            <?php
        }
	}

endif;
