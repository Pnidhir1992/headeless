<?php
if ( ! defined( 'ABSPATH' ) ) {
exit;
} // Exit if accessed directly

/**
 * Sanitize field
 *
 * @since 1.0.0
 *
 * @param $field
 *
 * @return array|string
 */
function auto_robot_sanitize_field( $field ) {
    // If array map all fields
    if ( is_array( $field ) ) {
        return array_map( 'auto_robot_sanitize_field', $field );
    }

    return wp_strip_all_tags( $field );
}