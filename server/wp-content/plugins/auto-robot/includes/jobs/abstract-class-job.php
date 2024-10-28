<?php
/**
 * Auto_Robot_Job Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Job' ) ) :

    abstract class Auto_Robot_Job {

        /**
         * Job ID
         *
         * @int
         */
        public $id;

        /**
         * Type
         *
         * @var string
         */
        public $type = '';

        /**
         * API data
         *
         * @var array
         */
        public $api_data = array();

        /**
         * Settings
         *
         * @var array
         */
        public $settings = array();

        /**
         * Keywords
         *
         * @var array
         */
        public $keywords = array();

        /**
         * Log Message
         *
         * @var array
         */
        public $log = array();

        /**
         * Logger class
         *
         */
        public $logger;

        /**
         * Run this job
         *
         * @return array
         */
        public function run(){

        }

        /**
         * Prepare Post Data
         * @param string $title
         * @param string $content
         * @return array
         * @since  1.0.0
         */
        public function prepare_post($title, $content, $settings=array()) {

            // Only set words limit to rss source
            if(isset($this->settings['robot_selected_source']) && $this->settings['robot_selected_source'] === 'rss'){
                if(isset($this->settings['robot_words_limit']) && !empty($this->settings['robot_words_limit'])){
                    $content = substr($content, 0, $this->settings['robot_words_limit']);
                }
            }

            if(isset($this->settings['robot_before_post_content']) && !empty($this->settings['robot_before_post_content'])){
                $content = $this->settings['robot_before_post_content'].'<br>'.$content;
            }

            if(isset($this->settings['robot_after_post_content']) && !empty($this->settings['robot_after_post_content'])){
                $content = $content.'<br>'.$this->settings['robot_after_post_content'];
            }

            // cache image on local server
            if($settings['robot_save_image'] == 'on'){

                preg_match_all ( '/<img [^>]*src=["|\']([^"|\']+)/i', stripslashes ( $content ), $matches );

			    $srcs = $matches [1];
			    $srcs = array_unique ( $srcs );
			    $current_host = parse_url ( home_url (), PHP_URL_HOST );

			    $first_image_cache = true; // copy of the first image if used for the featured image

			    foreach ( $srcs as $image_url ) {
                    // check inline images
					if (stristr ( $image_url, 'data:image' )) {
						continue;
					}

					// instantiate so we replace . note we modify image_url
					$image_url_original = $image_url;

					// decode html entitiies
					$image_url = html_entity_decode ( $image_url );

					// file name to store
                    $filename = basename ( $image_url );

                    if (stristr ( $image_url, '%' ) || stristr ( $filename, '%' )) {
						$filename = urldecode ( $filename );
					}

					if (stristr ( $image_url, ' ' )) {
						$image_url = str_replace ( ' ', '%20', $image_url );
					}

					$imghost = parse_url ( $image_url, PHP_URL_HOST );

					if (stristr ( $imghost, 'http://' )) {
						$imgrefer = $imghost;
					} else {
						$imgrefer = 'http://' . $imghost;
                    }

                    if ($imghost != $current_host) {
                        $file_link = auto_robot_upload_image($image_url, $filename);
                        // replace original src with new file link
						$content = str_replace ( $image_url_original, $file_link, $content );
					}
                } //end foreach images
            }

            $post_data = array();
            $post_data['post_title']   = $title;
            $post_data['post_content'] = $content;
            $post_data['post_type']    = $settings['robot_post_type'];
            $post_data['post_status']  = $settings['robot_post_status'];
            $post_author = get_user_by('login',$settings['robot_post_author']);
            $post_data['post_author']  = $post_author->ID;

            $post_data['post_category'] = $this->settings['robot-post-category'];

            // get tags name
            $tags = array();
            $tag_ids = $this->settings['robot-post-tag'];

            foreach($tag_ids as $tag_id){
                $tag = get_tag($tag_id);
                $tags[] = $tag->name;
            }

            $post_data['tags_input'] = array_values( $tags );;

            return $post_data;

        }

        /**
         * Create Post
         * @param string $title
         * @param string $content
         * @param array $settings
         * @return array
         * @since  1.0.0
         */
        public function create_post($title, $content, $settings = array(), $image_url=''){

            // check errors
            $new_post = array('id' => false, 'error' => false, 'permalink' => '');

            // check if post title duplicate
            if($this->is_title_duplicate($title)){
                $this->log[] = array(
                    'message' => 'Title Duplicate ' . $title,
                    'level'     => 'log'
                );
                $this->log[] = array(
                    'message' => 'There is no new post from this source, Auto Robot will generate new post again when this source have update',
                    'level'     => 'log'
                );
                $new_post['error'] = 'post title duplicate.';
                return $new_post;
            }

            $post_data = $this->prepare_post($title, $content, $settings);

            // before saving post
            remove_filter('content_save_pre', 'wp_filter_post_kses');
            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');

            // save code here
            $new_post['id'] = wp_insert_post($post_data);

            // after saving post
            add_filter('content_save_pre', 'wp_filter_post_kses');
            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');

            $post_id = isset($new_post['id']) ? $new_post['id'] : null;

            if (!$post_id) {
                $new_post['error'] = 'post-fail';
            }else{
                $new_post['permalink'] = get_permalink( $post_id );
                // Set post feature image
                if($settings['robot_feature_image'] == 'on'){
                    $this->set_post_feature_image($post_id, $image_url);
                }
            }

            if(false === $new_post['error']){
                $this->log[] = array(
                    'message' => 'Post New Post Success: <a href="'.$new_post['permalink'].'">'.$title.'</a>',
                    'level'     => 'success'
                );
            }else{
                $this->log[] = array(
                    'message' => 'Generate New Post Error: '.$new_post['error'],
                    'level'     => 'error'
                );
            }


            return $new_post;

        }

        /**
         * Set post feature image
         * @param int $parent_post_id
         * @return bool
         * @since  1.0.0
         */
        public function set_post_feature_image($post_id, $image_url){
            // Add Featured Image to Post
            $image_name       = 'wp-header-logo.png';
            $upload_dir       = wp_upload_dir(); // Set upload folder
            $image_data       = wp_remote_retrieve_body(wp_remote_get($image_url)); // Get image data
            $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
            $filename         = basename( $unique_file_name ); // Create image file name

            // Check folder permission and define file location
            if( wp_mkdir_p( $upload_dir['path'] ) ) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }

            // Create the image file on the server
            file_put_contents( $file, $image_data );

            // Check image file type
            $wp_filetype = wp_check_filetype( $filename, null );

            // Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => sanitize_file_name( $filename ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Create the attachment
            $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

            // Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

            // Assign metadata to attachment
            wp_update_attachment_metadata( $attach_id, $attach_data );

            // And finally assign featured image to post
            set_post_thumbnail( $post_id, $attach_id );
        }

        /**
         * check if post title already exists
         * @param string $title
         * @return bool
         * @since  1.0.0
         */
        public function is_title_duplicate($title){
            if( get_page_by_title( $title, 'OBJECT', 'post' )  ){
                return true;
            }else{
                return false;
            }
        }

        /**
         * Get API Data.
         *
         * @since 1.0.0
         */
        public function get_api_data($type) {
            $this->api_data = Auto_Robot_Addon_Loader::get_instance()->get_addon_data($type);
        }

        /**
        * Fetch stream bt HTTP GET Method
        *
        * @param  string $url
        * @return string
        */
        public function fetch_stream( $url, $headers = array()) {
          // build http request args
          $args = array(
              'headers' => $headers,
              'timeout'     => '20'
          );

          $request = wp_remote_get( $url, $args );

          // return an empty array when error happen on remote get
          $empty_response = array();
          // retrieve the body from the raw response
          $json_posts = wp_remote_retrieve_body( $request );

          // log error messages
          if ( is_wp_error( $request ) ) {
              $this->log[] = array(
                  'message' => 'Fetching failed with WP_Error: '. $request->errors['http_request_failed'][0],
                  'level'     => 'warn'
              );
              return $empty_response;
          }

          if ( $request['response']['code'] != 200 ) {
              $this->log[] = array(
                 'message'   => 'Fetching failed with code: ' . $request['response']['code'],
                 'level'     => 'error'
              );
              $response_body = json_decode($request['body']);
              $this->log[] = array(
                'message'   => 'Fetching failed with error message: ' . $response_body->error->message,
                'level'     => 'error'
              );
            //   $this->log[] = array(
            //     'message'   => '<a href="'.admin_url( 'admin.php?page=auto-robot-integrations' ).'">Note: Please Remember to Setup Youtube API before run this campaign<a>',
            //     'level'     => 'error'
            //   );
              return $empty_response;
          }

          return $json_posts;
        }

        /**
         * Fetch stream bt HTTP POST Method
         *
         * @param  string $url
         * @return string
         */
        public function fetch_post( $url, $headers = array(), $body = array()) {
            // build http request args
            $args = array(
                'headers' => $headers,
                'body'    => $body,
                'method'  => 'POST',
                'timeout' => 45,
            );

            $request = wp_remote_post( $url, $args );

            // retrieve the body from the raw response
            $json_posts = wp_remote_retrieve_body( $request );

            // log error messages
            if ( is_wp_error( $request ) ) {
                $this->log[] = array(
                    'message'   => 'Fetching failed with WP_Error: '. $request->errors['http_request_failed'][0],
                    'level'     => 'warn'
                );
                return $request;
            }

            if ( $request['response']['code'] != 200 ) {
                $this->log[] = array(
                    'message'   => 'Fetching failed with code: ' . $request['response']['code'],
                    'level'     => 'error'
                );
                return false;
            }

            return $json_posts;

        }

        /**
         * Trim a string, accepts a string or a null and if null, it returns an empty string and if a string, it trim it using trim function
         * for PHP 8.2 compatibility
         * @param  string $str
         * @return string
         */
        public function string_trim($str){
	        if (is_null($str)) {
		        return '';
	        } else {
		        return trim($str);
	        }
        }

    }

endif;
