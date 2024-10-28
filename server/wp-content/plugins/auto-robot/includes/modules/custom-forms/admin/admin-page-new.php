<?php
/**
 * Auto_Robot_CForm_New_Page Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_CForm_New_Page' ) ) :

class Auto_Robot_CForm_New_Page extends Auto_Robot_Admin_Page {
    
    /**
     * Get wizard title
     *
     * @since 1.0
     * @return mixed
     */
    public function getWizardTitle() {
        if ( isset( $_REQUEST['id'] ) ) { // WPCS: CSRF OK
            return __( "Edit Campaign", Auto_Robot::DOMAIN );
        } else {
            return __( "New Campaign", Auto_Robot::DOMAIN );
        }
    }

    /**
     * Add page screen hooks
     *
     * @since 1.0.0
     * @param $hook
     */
    public function enqueue_scripts( $hook ) {

        // Load admin styles
        auto_robot_admin_enqueue_styles( AUTO_ROBOT_VERSION );

        $auto_robot_data = new Auto_Robot_Admin_Data();


        // Load admin scripts
        auto_robot_admin_enqueue_scripts(
            AUTO_ROBOT_VERSION,
            $auto_robot_data->get_options_data()
        );
        
    }

    /**
     * Render page header
     *
     * @since 1.0
     */
    protected function render_header() { ?>
        <?php
        if ( $this->template_exists( $this->folder . '/header' ) ) {
            $this->template( $this->folder . '/header' );
        } else {
            ?>
            <h1 class="robot-header-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <?php } ?>
        <?php
    }


    /**
     * Return single model
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return array
     */
    public function get_single_model( $id ) {
        $data = Auto_Robot_Custom_Form_Model::model()->get_single_model( $id );

        return $data;
    }
}

endif;
