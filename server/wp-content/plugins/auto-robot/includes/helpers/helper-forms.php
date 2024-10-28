<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Return total forms
 *
 * $param  $status
 * @since 1.0.0
 *
 * @return int
 */
function auto_robot_total_forms( $status = '' ) {
	$modules = array(
		auto_robot_cforms_total( $status )
	);

	return array_sum( $modules );
}

/**
 * Return total custom form records
 *
 * @param string $status
 * @since 1.0.0
 *
 * @return int
 */
function auto_robot_cforms_total( $status = '' ) {
	return Auto_Robot_Custom_Form_Model::model()->count_all( $status );
}

/**
 * Central per page for form view
 *
 * @since 1.0.0
 * @return int
 */
function auto_robot_form_view_per_page( $type = 'listings' ) {

	if ( 'entries' === $type ) {
		$per_page = get_option( 'auto_robot_pagination_entries', 10 );
	} else {
		$per_page = get_option( 'auto_robot_pagination_listings', 10 );
	}

	// force at least 1 data per page
	if ( $per_page < 1 ) {
		$per_page = 1;
	}
	return apply_filters( 'auto_robot_form_per_page', $per_page, $type );
}

/**
 * Central per page for form view
 *
 * @since 1.0.0
 * @return string
 */
function auto_robot_get_campaign_name($id){

	$model = AUTO_ROBOT_Custom_Form_Model::model()->load( $id );

	$settings = $model->settings;

    // Return Campaign Name
	if ( ! empty( $settings['robot_campaign_name'] ) ) {
		return $settings['robot_campaign_name'];
	}
}

/**
 * Return campaigns last run time
 *
 * @since 1.0.0
 *
 * @return string
 */
function auto_robot_campaigns_last_run( ) {
    $campaigns_last_run = array();

    // Run campaigns job here
    $models = Auto_Robot_Custom_Form_Model::model()->get_all_models();

    $campaigns = $models['models'];

    foreach($campaigns as $key=>$model){
        $settings = $model->settings;
        if(isset($settings['last_run_time'])){
            $campaigns_last_run[] = $settings['last_run_time'];
        }
    }

    if(!empty($campaigns_last_run)){
        $last_run = max($campaigns_last_run);
        $return = date("F j, Y, g:i a", $last_run);
    }else{
        $return = 'Never';
    }



    return $return;
}

/**
 * Display category and it's child
 * @param $cat
 * @param $child_categories
 * @param $campaign_category
 */

function auto_robot_display_category($category, &$child_categories, $campaign_category){

	echo  '<option class="post_category" ';
    auto_robot_category_selected($campaign_category,$category->cat_ID);
    echo  ' value="'.$category->cat_ID.'">'.$category->cat_name.'</option>';

	$catChilds = array();

	if(isset($child_categories[$category->cat_ID]))
		$catChilds = $child_categories[$category->cat_ID];

	if(count($catChilds) > 0){
		foreach ($catChilds as $childCat){
            auto_robot_display_category($childCat, $child_categories, $campaign_category);
		}
	}

}

function auto_robot_category_selected($src,$val){

    if (in_array($val, $src)) {
        echo ' selected="selected" ';
    }

}

/**
 * Display tag and it's child
 * @param $tag
 * @param $campaign_tag
 */

function auto_robot_display_tag($tag, $campaign_tag){
    echo  '<option class="post_tag" ';
    auto_robot_category_selected($campaign_tag, $tag->term_id);
    echo  ' value="'.$tag->term_id.'">'.$tag->name.'</option>';

}