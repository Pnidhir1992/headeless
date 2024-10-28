<?php
/**
 * Auto_Robot_RSS_Job Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

use Google\Cloud\Translate\V2\TranslateClient;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_RSS_Job' ) ) :

    class Auto_Robot_RSS_Job extends Auto_Robot_Job{

        /**
         * Feed Link
         *
         * @var string
         */
        public $feed_link = '';

        /**
         * Auto_Robot_RSS_Job constructor.
         *
         * @since 1.0.0
         */
        public function __construct($id, $type, $feed_link, $settings) {
            $this->id = $id;
            $this->feed_link = $feed_link;
            $this->settings = $settings;
            $this->logger = new Auto_Robot_Log($id);
        }

        /**
         * Run this job
         *
         * @return array
         */
        public function run(){
            $title = '';
            $content = '';

            // Fetch Data
            $data = $this->fetch_data();
            if(is_object($data)){
                $this->log[] = array(
                    'message'   => $data->errors['simplepie-error'][0],
                    'level'     => 'error'
                );
            }else if(count($data) > 0){
                $title = $data['title'];
                // Build post content
                $content = $data['content'].'<br>';
                if($this->settings['robot_source_link'] === 'on' || is_null($this->settings['robot_source_link'])){
                    $content .= '<a href="'.$data['link'].'">source</a>';
                }

                // Generate New Post
                $this->create_post($title, $content, $this->settings, $data['featured_image']);
            }

            // Add this job running log to system log file
            foreach($this->log as $key => $value){
                $this->logger->add($value['message'], $value['level']);
            }

            return $this->log;
        }

        /**
        * Fetch Data
        *
        * @return array
        */
        public function fetch_data() {
            // Get RSS Feed(s)
            include_once( ABSPATH . WPINC . '/feed.php' );

            $this->log[] = array(
                'message'   => 'Start process feed: '.$this->feed_link,
                'level'     => 'log'
            );

            // Get a SimplePie feed object from the specified feed source.
            $rss = fetch_feed( $this->feed_link );

            $maxitems = 0;

            // Checks that the object is created correctly
            if ( ! is_wp_error( $rss ) ) {

                // Figure out how many total items there are, but limit it to 5.
                $maxitems = $rss->get_item_quantity();

                $this->log[] = array(
                    'message'   => 'Feed contains '.$maxitems.' total items',
                    'level'     => 'log'
                );

                // Build an array of all the items, starting with element 0 (first element).
                $rss_items = $rss->get_items(0, $maxitems);

                // If reverse order option not selected
                if (!isset($this->settings['robot_feed_reverse']) && !$this->settings['robot_feed_reverse'] == 'on') {
                    $rss_items = array_reverse($rss_items);
                }else{
                    $this->log[] = array(
                        'message'   => 'Reversing the feed items order',
                        'level'     => 'log'
                    );
                }

                // print_r($rss_items);

                foreach($rss_items as $item){
                    if(!is_null($item) && !$this->is_title_duplicate($item->get_title())){
                        $single_item = $item;
                    }
                }

                $return = array();

                if(!is_null($single_item)){
                    // translate title
                    $return['title'] = $this->maybe_spin_rewriter($single_item->get_title());
                    $return['title'] = $this->translate_innertext($single_item->get_title());
                    $return['link'] = $single_item->get_permalink();
                    $return['content'] = $single_item->get_content();
                    $return['description'] = $single_item->get_description();

                    // Fetch origin content
                    $original_content  = $this->fetch_stream( $return['link'] );

                    $return['featured_image'] = $this->get_og_image($original_content);
                    //$return['content'] = $original_content;

                    // if(!empty($return['content']) && $this->settings['robot_content_options'] === 'full_content'){
                    //     // Parse DOM HTML
                    //     // Create DOM from URL or file
                    //     $html = new simple_html_dom($return['content']);
                    //     $html->load($return['content']);
                    //     if($html){
                    //         $main_content = '';
                    //         // get each paragraph
                    //         foreach($html->find('p') as $element) {
                    //             if(isset($this->settings['robot_spin_rewriter']) && $this->settings['robot_spin_rewriter'] == 'on'){
                    //                 if(strpos($element->outertext, '<img') || strpos($element->outertext, '<a href')){
                    //                     continue;
                    //                 }
                    //                 $outertext = $this->maybe_spin_rewriter($element->outertext);
                    //             }else{
                    //                 // translate each paragraph
                    //                 $outertext = $this->translate_innertext($element->outertext);
                    //             }
                    //             $main_content .= $outertext . '<br>';
                    //         }
                    //         $return['content'] = $main_content;
                    //     }


                    // }

                    $this->log[] = array(
                        'message'   => 'Fetch Title: '.$return['title'],
                        'level'     => 'log'
                    );
                    $this->log[] = array(
                        'message'   => 'Fetch URL: <a target="_blank" href="'.$return['link'].'">'.$return['link'].'</a>',
                        'level'     => 'log'
                    );

                    // post date
                    $feed_post_date = $single_item->get_date("");

                    if ($this->string_trim($feed_post_date) != '') {
                        $this->log[] = array(
                            'message'   => 'Feed Original Post Published: ' . $feed_post_date,
                            'level'     => 'log'
                        );
                    }

                    $this->log[] = array(
                        'message'   => 'Loading original feed post content now ...',
                        'level'     => 'log'
                    );

                    // get scripts
				    $postponedScripts = array ();
				    preg_match_all ( '{<script.*?</script>}s', $original_content, $scriptMatchs );
				    $scriptMatchs = $scriptMatchs [0];

				    foreach ( $scriptMatchs as $singleScript ) {
					    if (stristr ( $singleScript, 'connect.facebook' )) {
						    $postponedScripts [] = $singleScript;
					    }

					    $original_content = str_replace ( $singleScript, '', $original_content );
				    }

                    $auto_robot_Readability = new auto_robot_Readability ( $original_content, $return['link'] );

					$auto_robot_Readability->debug = false;
					$result = $auto_robot_Readability->init ();

					if ($result) {

						// Redability title
						$title = $auto_robot_Readability->getTitle ()->textContent;

						// Redability Content
						$content = $auto_robot_Readability->getContent ()->innerHTML;

						// twitter embed fix
						if (stristr ( $content, 'twitter.com' ) && ! stristr ( $content, 'platform.twitter' )) {
							$content .= '<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
						}

						// Remove auto_robot_Readability attributes
						$content = preg_replace ( '{ auto_robot_Readability\=".*?"}s', '', $content );

						// Fix iframe if exists
						preg_match_all ( '{<iframe[^<]*/>}s', $content, $ifrMatches );
						$iframesFound = $ifrMatches [0];

						foreach ( $iframesFound as $iframeFound ) {

							$correctIframe = str_replace ( '/>', '></iframe>', $iframeFound );
							$content = str_replace ( $iframeFound, $correctIframe, $content );
						}

						// add postponed scripts
						if (count ( $postponedScripts ) > 0) {
							$content .= implode ( '', $postponedScripts );
						}

						// Cleaning redability for better memory
						unset ( $auto_robot_Readability );
						unset ( $result );

						// Check existence of title words in the content
						$title_arr = explode ( ' ', $title );

						$valid = '';
						$nocompare = array (
								'is',
								'Is',
								'the',
								'The',
								'this',
								'This',
								'and',
								'And',
								'or',
								'Or',
								'in',
								'In',
								'if',
								'IF',
								'a',
								'A',
								'|',
								'-'
						);
						foreach ( $title_arr as $title_word ) {

							if (strlen ( $title_word ) > 3) {

								if (! in_array ( $title_word, $nocompare ) && preg_match ( '/\b' . preg_quote ( trim ( $title_word ), '/' ) . '\b/ui', $content )) {
                                    $this->log[] = array(
                                        'message'   => 'Title word ' . $title_word . ' exists on the content, approving.',
                                        'level'     => 'log'
                                    );

									// echo $content;
									$valid = 'yeah';
									break;
								} else {
									// echo '<br>Word '.$title_word .' does not exists';
								}
							}
						}

						if (trim ( $valid ) != '') {

							$return['content'] = $content;
							$return['matched_content'] = $content;
							$return['og_img'] = '';

							// let's find og:image may be the content we got has no image
							preg_match ( '{<meta[^<]*?property=["|\']og:image["|\'][^<]*?>}s', $html, $plain_og_matches );

							if (isset ( $plain_og_matches [0] ) && @stristr ( $plain_og_matches [0], 'og:image' )) {
								preg_match ( '{content=["|\'](.*?)["|\']}s', $plain_og_matches [0], $matches );
								$og_img = $matches [1];

								if (trim ( $og_img ) != '') {
									$return['og_img'] = $og_img;
								}
							} // If og:image

                            $return['content'] = $this->lazy_loading_auto_fix( $return['content']);
                            $feature_image_src = $this->get_image_src($return['content']);
                            if(!empty($feature_image_src)){
                                $return['featured_image'] = $this->get_image_src($return['content']);
                            }
						} else {
                            $this->log[] = array(
                                'message'   => 'Can not make sure if the returned content is the full content, using excerpt instead.',
                                'level'     => 'log'
                            );
						}
					} else {
                        $this->log[] = array(
                            'message'   => 'Looks like we couldn\'t find the full content. :( returning summary',
                            'level'     => 'log'
                        );
					}

                    // Fetch origin content
                    // $article_content  = $this->fetch_stream( $return['link']);
                    // $feature_image_src = $this->get_image_src($article_content);

                    // Translate content
                    $return['content'] = $this->translate_innertext($return['content']);

                    if($this->settings['robot_content_options'] === 'excerpt'){
                        $return['content'] = $this->translate_innertext($single_item->get_description());
                    }

                    $feed_feature_image = $single_item->get_enclosure()->link;

                    if(!empty($feed_feature_image) && $this->settings['robot_feature_image'] !== 'on'){
                        $this->log[] = array(
                            'message'   => 'Enclosure Image URL: <a target="_blank"  href="'.$feed_feature_image.'">'.$feed_feature_image.'</a>',
                            'level'     => 'log'
                        );
                        $return['content'] = '<img src="' . $feed_feature_image . '" /><br>' . $return['content'];
                    }

                    if(!empty($feed_feature_image)){
                        $return['featured_image'] = $feed_feature_image;
                    }

                }else{
                    $this->log[] = array(
                        'message'   => 'There is no new post from this source currently, Auto Robot will generate new post automatically again when this source has any update',
                        'level'     => 'log'
                    );
                }

                return $return;

            }else{
                return $rss;
            }

        }

        /**
	    * Auto fix lazy loading
	    *
	    * @param
	    * $cont
	    */
	    function lazy_loading_auto_fix($cont) {
		    preg_match_all ( '{<img .*?>}s', $cont, $imgsMatchs );

		    // if no images
		    $imgs_count = count ( $imgsMatchs [0] );

		    if ($imgs_count < 1)
			    return $cont;

		    $found_lazy_tag = '';

		    if (stristr ( $cont, ' data-src=' )) {
			    $found_lazy_tag = 'data-src';
		    } elseif (stristr ( $cont, ' data-lazy-src=' )) {
			    $found_lazy_tag = 'data-lazy-src';
		    } else {

			    // suspecting lazy
			    $lazy_suspected = false;

			    $images_plain = implode ( ' ', $imgsMatchs [0] );

			    if (stristr ( $images_plain, 'lazy' )) {

				    if ($this->debug == true)
                        $this->log[] = array(
                            'message'   => 'Lazy word exists, now suspected',
                            'level'     => 'log'
                        );

				    $lazy_suspected = true;
			    } else {

				    if ($this->debug == true)
                        $this->log[] = array(
                            'message'   => 'Word Lazy does not exist, lets guess...',
                            'level'     => 'log'
                        );

				    // src values
				    preg_match_all ( '{ src[\s]?=[\s]?["|\'](.*?)["|\']}', $images_plain, $srcs_matches );

				    $found_srcs_count = count ( $srcs_matches [0] );
				    $unique_srcs_count = count ( array_unique ( $srcs_matches [1] ) );

				    if ($this->debug == true)
					    echo "<br>Post contains $found_srcs_count src attributes, and $unique_srcs_count unique";

				    if ($found_srcs_count != 0) {
					    $diff_percentage = (($found_srcs_count - $unique_srcs_count)) * 100 / $found_srcs_count;
				    } else {
					    $diff_percentage = 0;
				    }

				    if ($this->debug == true)
					    echo '<-- Percentage is ' . $diff_percentage;

				    if ($diff_percentage > 39) {
					    $lazy_suspected = true;

					    if ($this->debug == true)
						    echo '<-- Lazy suspected';
				    } else {
					    if ($this->debug == true)
						    echo '<-- Lazy was not suspected';
				    }
			    }

			    // finding suspected lazy attribute
			    if ($lazy_suspected) {

				    $images_plain_no_src = preg_replace ( '{ src[\s]?=[\s]?["|\'].*?["|\']}', ' ', $images_plain );

				    $replace_known_attributes = array (
						    ' alt',
						    ' srcset',
						    ' data-srcset',
						    ' class',
						    ' id',
						    ' title'
				    );

				    $images_plain_no_src = str_replace ( $replace_known_attributes, ' ', $images_plain_no_src );

				    // remove attributes containing small data 1-9
				    $images_plain_no_src = preg_replace ( '{ [\w|-]*?[\s]?=[\s]?["|\'].{1,9}?["|\']}s', ' ', $images_plain_no_src );

				    // attributes with slashes
				    preg_match_all ( '{( [\w|-]*?)[\s]?=[\s]?["|\'][^",]*?/[^",]*?["|\']}', $images_plain_no_src, $possible_src_matches );

				    $unique_attr = (array_unique ( $possible_src_matches [1] ));

				    if (isset ( $unique_attr [0] )) {
					    $found_lazy_tag = $unique_attr [0];
				    }
			    }
		    }

		    // found tag?

		    // of course not src
		    if (trim ( $found_lazy_tag ) == 'src')
			    return $cont;

		    if (trim ( $found_lazy_tag ) != '') {
                $this->log[] = array(
                    'message'   => 'Lazy loading was automatically detected where lazy tag is: <strong>' . $found_lazy_tag . '</strong>...Fixing...',
                    'level'     => 'log'
                );

			    $cg_feed_lazy = trim ( $found_lazy_tag );
		    } else {
			    return $cont;
		    }

		    if (! stristr ( $cont, $cg_feed_lazy ))
			    return $cont;

		    foreach ( $imgsMatchs [0] as $imgMatch ) {

			    if (stristr ( $imgMatch, $cg_feed_lazy )) {

				    $newImg = $imgMatch;
				    $newImg = str_replace ( ' src=', ' bad-src=', $newImg );
				    $newImg = preg_replace ( '{ bad-src=[\'|"].*?[\'|"] }', ' ', $newImg );
				    $newImg = str_replace ( ' ' . $cg_feed_lazy, ' src', $newImg );

				    $cont = str_replace ( $imgMatch, $newImg, $cont );
			    }
		    }

		    return $cont;
	    }


        /**
         * Translate inner text
         *
         * @return string
         */
        private function translate_innertext($innertext){

            $this->api_data = Auto_Robot_Addon_Loader::get_instance()->get_addon_data('google-translate');

            $googleTranslateKey = !empty($this->api_data['api_key']) ? $this->api_data['api_key'] :'AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw';

            // translate content for rss campaigns
            if(isset($this->settings['translation']) && $this->settings['translation'] == 'translation-on'){
                switch ( $this->settings['robot_translation_api'] ) {
                    case 0:
                        $translate = new TranslateClient([
                            'key' => $googleTranslateKey
                        ]);
                        // Translate text.
                        $result = $translate->translate($innertext, ['target' => $this->settings['robot_translation_to_language']]);
                        return $result['text'];
                        break;
                    case 1:
                        break;
                    default:
                        break;
                }
            }else{
                return $innertext;
            }
        }

        /**
         * Spin Rewriter
         *
         * @return string
         */
        private function maybe_spin_rewriter($innertext){

            $spin_rewriter_api_data = Auto_Robot_Addon_Loader::get_instance()->get_addon_data('spin-rewriter');

            // spin rewriter service
            if(isset($this->settings['robot_spin_rewriter']) && $this->settings['robot_spin_rewriter'] == 'on'){

                // Spin Rewriter API settings - authentication:
	            $email_address = $spin_rewriter_api_data['email'];			// your Spin Rewriter email address goes here
	            $api_key = $spin_rewriter_api_data['api_key'];	// your unique Spin Rewriter API key goes here

                if(!empty($email_address) && !empty($api_key)){
                    // Authenticate yourself.
	                $spinrewriter_api = new SpinRewriterAPI($email_address, $api_key);

                    // (optional) Set whether the One-Click Rewrite process automatically protects Capitalized Words outside the article's title.
	                $spinrewriter_api->setAutoProtectedTerms(false);

	                // (optional) Set the confidence level of the One-Click Rewrite process.
	                $spinrewriter_api->setConfidenceLevel("medium");

	                // (optional) Set whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not.
	                $spinrewriter_api->setNestedSpintax(true);

	                // (optional) Set whether Spin Rewriter rewrites complete sentences on its own.
	                $spinrewriter_api->setAutoSentences(false);

	                // (optional) Set whether Spin Rewriter rewrites entire paragraphs on its own.
	                $spinrewriter_api->setAutoParagraphs(false);

	                // (optional) Set whether Spin Rewriter writes additional paragraphs on its own.
	                $spinrewriter_api->setAutoNewParagraphs(false);

	                // (optional) Set whether Spin Rewriter changes the entire structure of phrases and sentences.
	                $spinrewriter_api->setAutoSentenceTrees(false);

	                // (optional) Sets whether Spin Rewriter should only use synonyms (where available) when generating spun text.
	                $spinrewriter_api->setUseOnlySynonyms(false);

	                // (optional) Sets whether Spin Rewriter should intelligently randomize the order of paragraphs and lists when generating spun text.
	                $spinrewriter_api->setReorderParagraphs(false);

	                // (optional) Sets whether Spin Rewriter should automatically enrich generated articles with headings, bullet points, etc.
	                $spinrewriter_api->setAddHTMLMarkup(false);

	                // (optional) Sets whether Spin Rewriter should automatically convert line-breaks to HTML tags.
	                $spinrewriter_api->setUseHTMLLinebreaks(false);

                    // remove img tags before process
                    // $innertext = preg_replace("/<img[^>]+\>/i", "(image)", $innertext);

	                // Make the actual API request and save the response as a native PHP array.
	                $api_response = $spinrewriter_api->getUniqueVariation($innertext);


                    if($api_response['status'] == 'ERROR'){
                        $this->log[] = array(
                            'message'   => $api_response['response'],
                            'level'     => 'error'
                        );
                        return $innertext;
                    }else if($api_response['status'] == 'OK'){
                        return $api_response['response'];
                    }
                }
            }else{
                return $innertext;
            }
        }

        /**
         * Get og:image source
         *
         * @return string
         */
        private function get_og_image($original_content){

            $og_img = '';

            // let's find og:image may be the content we got has no image
            preg_match ( '{<meta[^<]*?(?:property|name)=["|\']og:image["|\'][^<]*?>}s', $original_content, $plain_og_matches );

            if (isset ( $plain_og_matches [0] ) && stristr ( $plain_og_matches [0], 'og:image' )) {
                preg_match ( '{content=["|\'](.*?)["|\']}s', $plain_og_matches [0], $matches );
                $og_img = $matches [1];
            }

            return $og_img;
        }

        /**
         * Get imgae source
         *
         * @return string
         */
        private function get_image_src($original_content){
            $dom = new DOMDocument();
            $dom->loadHTML($original_content);
            if(is_object($dom->getElementsByTagName('img')->item(0))){
                return $dom->getElementsByTagName('img')->item(0)->getAttribute('src');
            }else{
                return '';
            }

        }
}

endif;
