<?php
/**
 * Auto_Robot_Search_Job Class
 *
 * @since  1.0.0
 * @package Auto Robot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Auto_Robot_Search_Job' ) ) :

    class Auto_Robot_Search_Job extends Auto_Robot_Job{

        /**
         * Feed Link
         *
         * @var string
         */
        public $feed_link = '';

        public $ch = '';

        /**
         * Auto_Robot_Search_Job constructor.
         *
         * @since 1.0.0
         */
        public function __construct($id, $type, $keyword, $settings) {
            $this->id = $id;
            $this->keyword = $keyword;
            $this->settings = $settings;
            $this->logger = new Auto_Robot_Log($id);
            $this->log = array();
        }

        /**
         * Run this job
         *
         * @return array
         */
        public function run(){
            $response = array();

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
            $robot_init_language = str_replace('_', ':' ,$this->settings['robot_init_language']);
            $pieces = explode(":", $robot_init_language);
            $country_code = $pieces[0];
            $language_code = $pieces[1];

            // Search google news feed by keyword
            $url = 'https://news.google.com/rss/search?';
            $req_params = [
                'q' => $this->keyword,
                'hl' => $language_code,
                'gl' => $country_code,
                'ceid' => $robot_init_language,
            ];

            $url .= http_build_query($req_params);

            //var_dump($url);

            // Get RSS Feed(s)
            include_once( ABSPATH . WPINC . '/feed.php' );

            // Get a SimplePie feed object from the specified feed source.
            $rss = fetch_feed( $url );

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

                // Reverse feed items order
                $rss_items = array_reverse($rss_items);

                foreach($rss_items as $item){
                    if(!is_null($item) && !$this->is_title_duplicate($item->get_title())){
                        $single_item = $item;
                    }
                }

                //var_dump($single_item);

                $return = array();
                if(!is_null($single_item)){
                    // Post original post date
                    $feed_post_date = $single_item->get_date("");

                    if ($this->string_trim($feed_post_date) != '') {
                        $this->log[] = array(
                            'message'   => 'Feed Original Post Published: ' . $feed_post_date,
                            'level'     => 'log'
                        );
                    }

                    // translate title
                    $return['title'] = $single_item->get_title();
                    $return['link'] = $single_item->get_permalink();
                    $return['content'] = $single_item->get_content();

                    // Get google original real link
                    try {
                        $newUrl = $this->decode_google_news_link($return['link']);
                    } catch (Exception $e) {
                        throw new Exception('Error in google news link extractor: ' . $e->getMessage());
                    }

                    //var_dump($newUrl);
                    if(is_string($newUrl)){
                        $sourceHref = $newUrl;
                    }else{
                        $sourceHref = $return['link'];
                    }

                    $this->log[] = array(
                        'message'   => 'Loading original feed post content now ...',
                        'level'     => 'log'
                    );

                    // Fetch origin content
                    $article_content  = $this->fetch_stream( $sourceHref );

                    //var_dump($article_content);

                    if (is_string($article_content)) {
                        $this->log[] = array(
                            'message'   => strlen($article_content) . ' chars returned in ' . timer_stop() . ' seconds',
                            'level'     => 'log'
                        );
                        $return['featured_image'] = $this->get_og_image($article_content);
                        if(!empty($return['featured_image'])){
                            $this->log[] = array(
                                'message'   => 'Feature Image URL: <a target="_blank"  href="'.$return['featured_image'].'">'.$return['featured_image'].'</a>',
                                'level'     => 'log'
                            );
                        }
                    }

                    if(!empty($article_content)){
                        // Parse DOM HTML
                        // Create DOM from URL or file
                        $html = str_get_html($article_content);
                        if($html){
                            $main_content = '';
                            // get each paragraph
                            foreach($html->find('p') as $element) {
                                $innertext = $element->innertext;
                                $main_content .= $innertext . '<br>';
                            }
                            $return['content'] = $main_content;
                        }
                    }

                    //var_dump($return['content']);

                    // Spin rewriter processor
                    if(isset($this->settings['robot_spin_rewriter']) && $this->settings['robot_spin_rewriter'] == 'on'){
                        $return['content'] = $this->maybe_spin_rewriter($return['content']);
                    }

                    $this->log[] = array(
                        'message'   => 'Fetch Title: '.$return['title'],
                        'level'     => 'log'
                    );
                    $this->log[] = array(
                        'message'   => 'Fetch URL: <a href="'.$return['link'].'">'.$return['link'].'</a>',
                        'level'     => 'log'
                    );

                }else{
                    $this->log[] = array(
                        'message'   => 'There is no new post from this source, Auto Robot will generate new post again when this source have update',
                        'level'     => 'log'
                    );
                }

                if(!empty($return['featured_image']) && $this->settings['robot_feature_image'] !== 'on'){
                    $return['content'] = '<img class="robot-feature-image" src="' . $return['featured_image'] . '" /><br>' . $return['content'];
                }

                return $return;

            }else{

                return $rss;

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
     * Function takes the google news link and returns the original link
     * @param string $url
     * @return string $url
     * @example https://news.google.com/rss/articles/CBMipgFBVV95cUxNM3VkRXF1TDFrOWVtWHRTb1lFTUFPQTljY2dtb1ZlVmRyTjBDRmNYcVgtSzRNTjJpZy1oMU5aZXlPYnA3aHhObGM0TE5YZXJ5Zzh4Z1ZDTFlpdTlYVWRNUk5uVko2d1RHXzlwMWtlM0NobWJfOFI5WjJyWG14SHhEMFV2VHU3UzhuTk1UTmVyRDU5czFVTDRDcjhBS0pfTlRDUlR5azRR?oc=5
     * @example old format CBMiV2h0dHBzOi8vd3d3LnBva2VybmV3cy5jb20vc3RyYXRlZ3kvaG9sZC1lbS13aXRoLWhvbGxvd2F5LXZvbC03Ny1qb3NlcGgtY2hlb25nLTMxODU4Lmh0bdIBAA
     */

    public function decode_google_news_link($url)
    {

        //echo '<br>Decoding google news link: ' . $url;

        //url is on form https://news.google.com/rss/articles/CBMipgFBVV95cUxNM3VkRXF1TDFrOWVtWHRTb1lFTUFPQTljY2dtb1ZlVmRyTjBDRmNYcVgtSzRNTjJpZy1oMU5aZXlPYnA3aHhObGM0TE5YZXJ5Zzh4Z1ZDTFlpdTlYVWRNUk5uVko2d1RHXzlwMWtlM0NobWJfOFI5WjJyWG14SHhEMFV2VHU3UzhuTk1UTmVyRDU5czFVTDRDcjhBS0pfTlRDUlR5azRR?oc=5
        //get the last part after the last / and before the ?
        //remove ?.* from the end
        $link = explode('?', $url)[0];

        //get the last part after the last /articles/
        $base64_part = preg_match('/\/articles\/(.*?)$/', $link, $matches);

        if (isset($matches[1]) && $this->auto_robot_trim($matches[1]) != '') {
            $base64_part = $matches[1];
        } else {
            throw new Exception('Could not extract the base64 part');
        }

        //test for old format
        //$base64_part = 'CBMiV2h0dHBzOi8vd3d3LnBva2VybmV3cy5jb20vc3RyYXRlZ3kvaG9sZC1lbS13aXRoLWhvbGxvd2F5LXZvbC03Ny1qb3NlcGgtY2hlb25nLTMxODU4Lmh0bdIBAA';

        //decode the base64 part
        //example CBMipgFBVV95cUxNM3VkRXF1TDFrOWVtWHRTb1lFTUFPQTljY2dtb1ZlVmRyTjBDRmNYcVgtSzRNTjJpZy1oMU5aZXlPYnA3aHhObGM0TE5YZXJ5Zzh4Z1ZDTFlpdTlYVWRNUk5uVko2d1RHXzlwMWtlM0NobWJfOFI5WjJyWG14SHhEMFV2VHU3UzhuTk1UTmVyRDU5czFVTDRDcjhBS0pfTlRDUlR5azRR
        $decoded = base64_decode($base64_part);

        //if contains http then return it
        if (stristr($decoded, 'http://') || stristr($decoded, 'https://')) {

            //remove any string before http using regex /^.*http/
            $decoded = preg_replace('/^.*http/', 'http', $decoded);

            // remove \xd2\x01\x00
            $decoded = preg_replace('{\\xd2\\x01\\x00}', '', $decoded);

            //trim
            $decoded = $this->auto_robot_trim($decoded);

            //var_dump($decoded);

            return $decoded;
        }

        //now it does not contain http, check if new format containing AU_y in the decoded string
        if (stristr($decoded, 'AU_y')) {
            //new format

            try {
                $decoded = $this->decode_google_news_link_remotely($base64_part);

                if ($this->auto_robot_trim($decoded) != '') {
                    return $decoded;
                }

            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

        }

        throw new Exception('Could not extract the original link reached end of the process with no result');

    }

    /**
     * Google changed on 1 SEP and this function no more works as expected
     * Decodes the Google News link remotely.
     *
     * @param int $id The ID of the Google News link.
     * @return void
     */
    public function decode_google_news_link_remotely($id)
    {

        //echo '<br>Decoding google news link remotely: ' . $id;

        //get decoding parameters
        try
        {
            $decoding_params = $this->get_decoding_params($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $articles = array($decoding_params);

        //now we have the decoding parameters
        $articles_reqs = [];

        foreach ($articles as $art) {
            $articles_reqs[] = [
                "Fbv4je",
                '["garturlreq",[["X","X",["X","X"],null,null,1,1,"US:en",null,1,null,null,null,null,null,0,1],"X","X",1,[1,1,1],1,1,null,0,0,null,0],"' . $art["gn_art_id"] . '",' . $art["timestamp"] . ',"' . $art["signature"] . '"]',
            ];
        }

        $payload = 'f.req=' . urlencode(json_encode([$articles_reqs]));

        $this->ch = curl_init();

        // Initialize cURL session for POST request
        curl_setopt($this->ch, CURLOPT_URL, "https://news.google.com/_/DotsSplashUi/data/batchexecute");
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded;charset=UTF-8"]);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);



        $response = curl_exec($this->ch);

        //response code
        $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        //echo code
        $this->log[] = array(
            'message'   => 'HTTP Code: ' . $httpcode,
            'level'     => 'log'
        );

        //if 302 and redirect url contains sorry then throw exception
        if ($httpcode == 302) {
            $redirect_url = curl_getinfo($this->ch, CURLINFO_REDIRECT_URL);

            if (stristr($redirect_url, 'sorry')) {
                throw new Exception('Could not extract the original link remotely, Google showed a captcha and unusual traffic detected');
            }
        }

        //code 429, ask the user to use proxies on the plugin settings page and enable using them on the campaign
        if ($httpcode == 429) {
            throw new Exception('Could not extract the original link remotely, Google returned 429, please use private proxies on the plugin settings page and enable using them on the campaign');
        }

        //example input CBMipAFBVV95cUxQSHlIaUM0cEVIRV9UdUtoY3d5a2Z4QXJpSXdBWmFydnR1SC1SU0N2T1JVU01PeXRid1ZCRW1vMnEyWHU5aTB6QzZTalAzdTE4UG5ndTBhd256Tzc1U3E2RHVtdlY1aDAyR21HOXlEOW5EeDNOYnl0bHV5MjJoaXhmQ1oyRkYyd3lsY2hKYl95dTFoN2NfdlVRZHJ5ZG9rXzNOYjY2WA
        //response example https://pastebin.com/e9p644C4


        //if does not contain garturlres, trow exception
        if (!stristr($response, 'garturlres')) {
            throw new Exception('Could not extract the original link remotely, response does not contain expected data garturlres');
        }

        //strip slashes
        $response = stripslashes($response);

        //var_dump($response);

        $this->log[] = array(
            'message'   => 'Finding response now...',
            'level'     => 'log'
        );

        $myArray = explode(',', $response);
        $newUrl = $myArray[3];
        $newUrl = trim($newUrl, '"');

        $this->log[] = array(
            'message'   => 'Real original article url: <a href="' . $newUrl . '">' . $newUrl . '</a>',
            'level'     => 'log'
        );

        //var_dump($newUrl);

        return $newUrl;


        //send api call to the api
        // try {
        //     $newUrl = $this->api_call('googleNewsLinkExtractor', array('googleNewsLinkExec' => $response));

        //     if ($this->auto_robot_trim($newUrl) != '') {

        //         return $newUrl;

        //     }

        // } catch (Exception $e) {

        //     throw new Exception($e->getMessage());

        // }

        // throw new Exception('Could not extract the original link remotely, reached end of the process with no result');

    }

    /**
     * Retrieves the decoding parameters for a given article ID.
     *
     * @param int $gn_art_id The ID of the article.
     * @return mixed The decoding parameters for the article.
     */
    public function get_decoding_params($gn_art_id)
    {
        $url = "https://news.google.com/articles/" . $gn_art_id;

        $url ="https://news.google.com/rss/articles/".$gn_art_id;

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        //useragent
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');

        // follow redirects
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($this->ch);


        if (curl_errno($this->ch)) {
            throw new Exception('Failed to retrieve article page: ' . curl_error($this->ch));
        }

        //code
        $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        //if not 200 throw exception
        if ($httpcode != 200) {
            throw new Exception('Failed to retrieve article page: HTTP code ' . $httpcode);
        }

        //if response is empty throw exception
        if ($this->auto_robot_trim($response) == '') {
            throw new Exception('Failed to retrieve article page: empty response');
        }



        // Load HTML
        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $div = $xpath->query("//c-wiz/div")->item(0);

        if (!$div) {
            throw new Exception('Failed to extract decoding parameters: div not found');
        }

        return [
            "signature" => $div->getAttribute("data-n-a-sg"),
            "timestamp" => $div->getAttribute("data-n-a-ts"),
            "gn_art_id" => $gn_art_id,
        ];
    }

    //function wordpress automatic trim to trim a string, accepts a string or a null and if null, it returns an empty string and if a string, it trim it using trim function
    //for PHP 8.2 compatibility
    public function auto_robot_trim($str){
	    if (is_null($str)) {
		    return '';
	    } else {
		    return trim($str);
	    }
    }


}

endif;
