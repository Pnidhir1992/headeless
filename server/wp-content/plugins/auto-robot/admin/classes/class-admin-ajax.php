<?php
/**
 * Auto_Robot_Admin_AJAX Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Admin_AJAX' ) ) :

class Auto_Robot_Admin_AJAX {

    /**
     * Auto_Robot_Admin_AJAX constructor.
     *
     * @since 1.0
     */
    public function __construct() {

        // WP Ajax Actions.
        add_action( 'wp_ajax_auto_robot_save_campaign', array( $this, 'save_campaign' ) );
        add_action( 'wp_ajax_auto_robot_run_campaign', array( $this, 'run_campaign_action' ) );
        add_action( 'wp_ajax_auto_robot_select_integration', array( $this, 'select_integration' ) );
        add_action( 'wp_ajax_auto_robot_save_api_data', array( $this, 'save_api_data' ) );
        add_action( 'wp_ajax_auto_robot_save_user_data', array( $this, 'save_user_data' ) );
        add_action( 'wp_ajax_auto_robot_skip_premium', array( $this, 'skip_premium' ) );
        add_action( 'wp_ajax_auto_robot_generate_campaign', array( $this, 'generate_campaign' ) );
        add_action( 'wp_ajax_auto_robot_save_settings', array( $this, 'save_settings' ) );

    }

    /**
     * Save settings
     *
     * @since 1.0.0
     */
    public function save_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        if ( isset( $_POST['fields_data'] ) ) {
            update_option( 'auto_robot_global_settings', $_POST['fields_data'] );
            $global_settings[ 'robot_google_search_api_key'] = $_POST['fields_data'][ 'robot_google_search_api_key'];
            $global_settings[ 'robot_google_search_engine_id'] = $_POST['fields_data'][ 'robot_google_search_engine_id'];
            // update next report time
            $global_settings[ 'update_frequency'] = $_POST['fields_data'][ 'update_frequency'];
            $global_settings[ 'update_frequency_unit'] = $_POST['fields_data'][ 'update_frequency_unit'];
            $global_settings[ 'next_report_time'] = time() + auto_robot_calculate_next_time($_POST['fields_data'][ 'update_frequency'], $_POST['fields_data'][ 'update_frequency_unit']);
            update_option( 'auto_robot_global_settings', $global_settings );

            $message = __( 'Global Settings has been connected successfully.' );

            wp_send_json_success( $message );
        } else {
            wp_send_json_error( __( 'User submit data are empty!', Auto_Robot::DOMAIN ) );
        }

    }

    /**
     * Generate Campaign
     *
     * @since 1.0.0
     */
    public function generate_campaign() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        if ( isset( $_POST['fields_data'] ) ) {

            $fields  = $_POST['fields_data'];
            $form_model = new Auto_Robot_Custom_Form_Model();
            $status = Auto_Robot_Custom_Form_Model::STATUS_PUBLISH;

            // Default update frequency is 60 minutes
            $default_update_frequency = 5;
            $default_update_frequency_unit = 'Minutes';

            // Sanitize settings
            $settings = $fields;

            // Campaign Next Run Time
            $time_length = auto_robot_calculate_next_time($default_update_frequency, $default_update_frequency_unit);
            $settings['next_run_time'] = time() + $time_length;

            $settings['robot_selected_source'] = 'search';
            $settings['update_frequency'] = $default_update_frequency;
            $settings['update_frequency_unit'] = $default_update_frequency_unit;
            // Set campaign feature image default on
            $settings['robot_feature_image'] = 'on';

            // Set Settings to model
            $form_model->settings = $settings;

            // status
            $form_model->status = $status;

            // Save data
            $id = $form_model->save();

            if (!$id) {
                wp_send_json_error( $id );
            }else{
                wp_send_json_success( $id );
            }

        } else {

            wp_send_json_error( __( 'User submit data are empty!', Auto_Robot::DOMAIN ) );
        }


    }

    /**
     * Run Campaign
     *
     * @since 1.0.0
     */
    public function run_campaign_action() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        if ( isset( $_POST['fields_data'] ) ) {

            $fields  = $_POST['fields_data'];
            $id      = isset( $fields['campaign_id'] ) ? $fields['campaign_id'] : null;
            $id      = intval( $id );
            if ( !is_null( $id ) || $id > 0 ) {
                $model = Auto_Robot_Custom_Form_Model::model()->load( $id );
            }

            if($model){
              $result = $model->run_campaign();
              wp_send_json_success( $result );
            }else{
              wp_send_json_error( __( 'Campaign not defined!', Auto_Robot::DOMAIN ) );
            }

        } else {
            wp_send_json_error( __( 'User submit data are empty!', Auto_Robot::DOMAIN ) );
        }



    }

    /**
     * Save Campaign
     *
     * @since 1.0.0
     */
    public function save_campaign() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        if ( isset( $_POST['fields_data'] ) ) {

            $fields  = $_POST['fields_data'];
            $id      = isset( $fields['campaign_id'] ) ? $fields['campaign_id'] : null;
            $id      = intval( $id );
            $title   = sanitize_text_field( $fields['robot_campaign_name'] );
            $status  = isset( $fields['campaign_status'] ) ? sanitize_text_field( $fields['campaign_status'] ) : '';

            if ( is_null( $id ) || $id <= 0 ) {
                $form_model = new Auto_Robot_Custom_Form_Model();
                $action     = 'create';

                if ( empty( $status ) ) {
                    $status = Auto_Robot_Custom_Form_Model::STATUS_DRAFT;
                }
            } else {
                $form_model = Auto_Robot_Custom_Form_Model::model()->load( $id );
                $action     = 'update';

                if ( ! is_object( $form_model ) ) {
                    wp_send_json_error( __( "Form model doesn't exist", Auto_Robot::DOMAIN ) );
                }

                if ( empty( $status ) ) {
                    $status = $form_model->status;
                }

            }

            // Sanitize settings
            $settings = $fields;

            // Campaign Next Run Time
            $time_length = auto_robot_calculate_next_time($fields['update_frequency'], $fields['update_frequency_unit']);
            $settings['next_run_time'] = time() + $time_length;

            // Set Settings to model
            $form_model->settings = $settings;

            // status
            $form_model->status = $status;

            // Save data
            $id = $form_model->save();

            if (!$id) {
                wp_send_json_error( $id );
            }else{
                wp_send_json_success( $id );
            }

        } else {

            wp_send_json_error( __( 'User submit data are empty!', Auto_Robot::DOMAIN ) );
        }

    }

    /**
     * Select Integration
     *
     * @since 1.0.0
    */
    public function select_integration() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        if ( isset( $_POST['template'] ) ) {
            $template = auto_robot_load_popup($_POST['template']);
            wp_send_json_success( $template );
        }

    }

    /**
     * Save API Data
     *
     * @since 1.0.0
     */
    public function save_api_data() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }


        if ( isset( $_POST['fields_data'] ) ) {
            // Sanitize api data
            $api_data = auto_robot_sanitize_field( $_POST['fields_data'] );
            auto_robot_save_addon_data($api_data);
            $message = '<strong>' . $api_data['slug'] . '</strong> ' . __( 'has been connected successfully.' );

            wp_send_json_success( $message );
        }else {
            wp_send_json_error( __( 'User submit data are empty!', Auto_Robot::DOMAIN ) );
        }

    }

    /**
     * Save User Data
     *
     * @since 1.0.0
     */
    public function save_user_data() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        $type = sanitize_text_field( $_POST['type'] );
        update_option( 'auto-robot-wizard-set-up', $type );

        switch ( $type ) {
            case 'skip':
                $return = __( 'User skip wizard opt-in!', Auto_Robot::DOMAIN );
                break;
            case 'opt-in':
                $return = auto_robot_save_user_data();
                break;
            default:
                break;
        }

        wp_send_json_success( $return );

    }

    /**
     * Skip premium
     *
     * @since 1.0.0
     */
    public function skip_premium() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_ajax_nonce'], 'auto-robot') ) {
            wp_send_json_error( __( 'You are not allowed to perform this action', Auto_Robot::DOMAIN ) );
        }

        update_option( 'auto-robot-skip-premium', 'skip' );
        $message = __( 'skip premium.' );
        wp_send_json_success( $message );

    }


}

endif;
