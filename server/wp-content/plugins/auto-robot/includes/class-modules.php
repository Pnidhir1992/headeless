<?php
/**
 * Auto_Robot_Modules Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Modules' ) ) :

class Auto_Robot_Modules {

	/**
	 * Store modules objects
	 *
	 * @var array
	 */
	public $modules = array();

	/**
	 * Auto_Robot_Modules constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->includes();
		$this->load_modules();
	}

	/**
	 * Includes
	 *
	 * @since 1.0
	 */
	private function includes() {

		require_once AUTO_ROBOT_DIR . '/includes/abstracts/abstract-class-module.php';
	}

	/**
	 * Load modules
	 *
	 * @since 1.0
	 */
	private function load_modules() {
		/**
		 * Filters modules list
		 */
		$modules = apply_filters( 'auto_robot_modules', array(
			'custom_forms' => array(
				'class'	  => 'Custom_Form',
				'slug'  => 'custom-forms',
				'label'	  => __( 'Custom Forms', Auto_Robot::DOMAIN )
			),
		) );

		array_walk( $modules, array( $this, 'load_module' ) );
	}

	/**
	 * Load module
	 *
	 * @since 1.0
	 * @param $data
	 * @param $id
	 */
	public function load_module( $data, $id ) {
		$module_class = 'Auto_Robot_' . $data[ 'class' ];
		$module_slug = $data[ 'slug' ];
		$module_label = $data[ 'label' ];
		
		// Include module
		$path = AUTO_ROBOT_DIR . '/includes/modules/' . $module_slug . '/loader.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}

		if ( class_exists( $module_class ) ) {
			$module_object = new $module_class( $id, $module_label );

			$this->modules[ $id ] = $module_object;
		}
	}

	/**
	 * Retrieve modules objects
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_modules() {
		return $this->modules;
	}
}

endif;
