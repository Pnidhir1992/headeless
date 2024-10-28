<?php
/**
 * Admin Addons List Class
 *
 * Plugin Admin Addons List Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Auto_Robot_Admin_Addons' ) ) :

	/**
	 * Admin Addons List
	 */
	class Auto_Robot_Admin_Addons {

        public static function get_list(){
            // list of addons
                $addons = apply_filters('robot_addons_details_list',array(
                    'youtube' => array(
                        'name'=>'Youtube Addon',
                        'desc'=>'Auto generate post from Youtube to Wordpress by keywords, playlist and channel.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/youtube.png'
                    ),
                    'vimeo' => array(
                        'name'=>'Vimeo Addon',
                        'desc'=>'Auto generate post from Vimeo to Wordpress by keywords, user profile.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/vimeo.png'
                    ),
                    'twitter' => array(
                        'name'=>'Twitter Addon',
                        'desc'=>'Auto generate post from Twitter to Wordpress by keywords, user profile.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/twitter.png'
                    ),
                    'flickr' => array(
                        'name'=>'Flickr Addon',
                        'desc'=>'Auto generate post from Flickr to Wordpress by keywords, user profile.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/flickr.png'
                    ),
                    'translate' => array(
                        'name'=>'Google Translate',
                        'desc'=>'Translate source content using Google Translate API before publish post.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/google-translate.png'
                    ),
                    'rewriter' => array(
                        'name'=>'Spin Rewriter',
                        'desc'=>'Enable spin rewriter api services.',
                        'thumbnail' => AUTO_ROBOT_URL.'/assets/images/spin-rewriter.png'
                    ),

                ));

                return $addons;
            }


	}



endif;