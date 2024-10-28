<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Get registered addons grouped by connected status
 *
 * @since 1.0.0
 * @return array
 */
function auto_robot_get_addons() {

    //Integrations Addons
    $addons_list = Auto_Robot_Addon_Loader::get_instance()->get_addons();

    $connected_addons     = array();
    $not_connected_addons = array();

    foreach ( $addons_list as $key => $addon ) {
        if ( $addon['is_connected'] ) {
            $connected_addons[] = $addon;
        } else {
            $not_connected_addons[] = $addon;
        }
    }



    return array(
        'connected'     => $connected_addons,
        'not_connected' => $not_connected_addons,
    );
}

/**
 * Load popup template
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_load_popup($template){

    $file    = AUTO_ROBOT_DIR . "/admin/views/integrations/popups/$template.php";
    $content = '';

    if ( is_file( $file ) ) {
        ob_start();

        include $file;

        $content = ob_get_clean();
    }

    return $content;
}

/**
 * Save addon api data
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_save_addon_data($data){
    Auto_Robot_Addon_Loader::get_instance()->save_addon_data($data);
}

/**
 * Get addon api data
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_get_addon_data($slug){
    $data = Auto_Robot_Addon_Loader::get_instance()->get_addon_data($slug);
    return $data;
}
