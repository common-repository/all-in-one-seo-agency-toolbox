<?php
global $wpdb;
/**
 * Class GeoRanker API.
 * Conect with GeoRanker Api (apidocs.georanker.com)
 *
 * @author Renan Gomes
 * @since 28-11-2013
 */
if (! class_exists("SATGeoRankerAPI")) {

    class SATGeoRankerAPI {

        public $email = null;

        private $apikey = null;

        private $session = null;

        private $apiurl = "http://api.georanker.com/v2";

        private $cachefolder = null;

        private $cache = true;

        private $cachetime = 60;

        /**
         * Set the conection mode for the GeoRanker API interaction
         *
         * @param string $conectionmode
         *            can be 'curl' or 'fsockopen'
         */
        public function __construct($email, $apikey, $cache = true, $cachetime = 60, $cachefolder = null) {
            $this->email = $email;
            $this->apikey = strtolower($apikey);
            if (! empty($cachefolder)) {
                $this->cachefolder = $cachefolder;
            } else {
                $this->cachefolder = sys_get_temp_dir() . "/";
            }
            $this->cachetime = $cachetime;
            $this->cache = $cache;
        }

        public function login() {
            if (empty($this->email) || empty($this->apiurl)) {
                return false;
            }

            $ret = $this->docurl($this->apiurl . '/api/login.json?' . http_build_query(array('email' => $this->email,'apikey' => $this->apikey)));
            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                if (! empty($responseobj) && ! isset($responseobj->debug)) {
                    $this->session = $responseobj->session;
                }
                return $responseobj;
            }
            return false;
        }

        public function accountinfo() {
            if (empty($this->session) && ! $this->login()) {
                return false;
            }
            $ret = $this->docurl($this->apiurl . '/account/info.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)));
            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }
            return false;
        }

        public function reportnew($type, $keywords, $countries, $is_global, $maxcities, $regions = array(), $url = NULL, $language = NULL, $ignoretypes = array(), $is_usealternativetld = FALSE, $is_fillcities = TRUE,
            $is_formobile = FALSE, $callback = '', $brand = NULL, $is_gmsearchmode = FALSE, $is_localonly = FALSE, $is_carouselfallbackmode = FALSE, $phone = NULL, $fields = NULL) {
            if (empty($this->session) && ! $this->login()) {
                return false;
            }

            $post_fields = array();
            $post_fields['type'] = strtolower(trim($type));
            $post_fields['keywords'] = (empty($keywords)) ? array() : $keywords;
            $post_fields['countries'] = (empty($countries)) ? array() : $countries;
            $post_fields['regions'] = (empty($regions)) ? array() : $regions;
            $post_fields['url'] = (empty($url)) ? NULL : trim($url);
            $post_fields['language'] = empty($language) ? NULL : trim($language);
            $post_fields['ignoretypes'] = (empty($ignoretypes)) ? array() : $ignoretypes;
            $post_fields['is_usealternativetld'] = (empty($is_usealternativetld)) ? FALSE : TRUE;
            $post_fields['is_global'] = (empty($is_global)) ? FALSE : TRUE;
            $post_fields['is_fillcities'] = (empty($is_fillcities)) ? FALSE : TRUE;
            $post_fields['is_formobile'] = (empty($is_formobile)) ? FALSE : TRUE;
            $post_fields['maxcities'] = $maxcities;
            $post_fields['callback'] = (empty($callback)) ? NULL : $callback;
            $post_fields['brand'] = (empty($brand)) ? NULL : $brand;
            $post_fields['is_gmsearchmode'] = (empty($is_gmsearchmode)) ? FALSE : TRUE;
            $post_fields['is_localonly'] = (empty($is_localonly)) ? FALSE : TRUE;
            $post_fields['is_carouselfallbackmode'] = (empty($is_carouselfallbackmode)) ? FALSE : TRUE;
            $post_fields['fields'] = (empty($fields)) ? NULL : $fields;
            $post_fields['phone'] = (empty($phone)) ? NULL : $phone;

            if ($post_fields['is_global'] == true && count($post_fields['countries']) == 1) {
                $post_fields['is_global'] = false;
                $post_fields['maxcities'] = 1;
                $post_fields['is_localonly'] = true;
            }

            $ret = $this->docurl($this->apiurl . '/report/new.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)), 'POST', 30, array(),
                json_encode((object) $post_fields));

            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }
            return false;
        }

        public function keyworddensity($sat_keyworddensity_url, $sat_keyworddensity_ignorebody, $sat_keyworddensity_minwordlength, $sat_keyworddensity_maxwordlength,
            $sat_keyworddensity_weightforkeywordsonbody, $sat_keyworddensity_weightforh1h2h3, $sat_keyworddensity_weightfortitle, $sat_keyworddensity_weightforlinkstext,
            $sat_keyworddensity_weightforlinkstitle, $sat_keyworddensity_weightformetatitle, $sat_keyworddensity_weightformetakeywords, $sat_keyworddensity_weightformetadescription,
            $sat_keyworddensity_weightforimagealt, $sat_keyworddensity_doublekeywordweightmultiplier, $sat_keyworddensity_triplekeywordweightmultiplier,
            $sat_keyworddensity_quadrupleormorekeywordweightmultiplier, $sat_keyworddensity_stopwordmode, $sat_keyworddensity_mindensitytobeused) {
            if (empty($this->session) && ! $this->login()) {
                return false;
            }

            $post_fields = array();
            $post_fields['url'] = $sat_keyworddensity_url;
            $post_fields['ignorebody'] = $sat_keyworddensity_ignorebody;
            $post_fields['min_word_length'] = $sat_keyworddensity_minwordlength;
            $post_fields['max_word_length'] = $sat_keyworddensity_maxwordlength;
            $post_fields['weight_default'] = $sat_keyworddensity_weightforkeywordsonbody;
            $post_fields['weight_headings'] = $sat_keyworddensity_weightforh1h2h3;
            $post_fields['weight_title'] = $sat_keyworddensity_weightfortitle;
            $post_fields['weight_link'] = $sat_keyworddensity_weightforlinkstext;
            $post_fields['weight_link_alt'] = $sat_keyworddensity_weightforlinkstitle;
            $post_fields['weight_meta_title'] = $sat_keyworddensity_weightformetatitle;
            $post_fields['weight_meta_keywords'] = $sat_keyworddensity_weightformetakeywords;
            $post_fields['weight_meta_description'] = $sat_keyworddensity_weightformetadescription;
            $post_fields['weight_img_alt'] = $sat_keyworddensity_weightforimagealt;
            $post_fields['multipler_double_words'] = $sat_keyworddensity_doublekeywordweightmultiplier;
            $post_fields['multipler_tipler_words'] = $sat_keyworddensity_triplekeywordweightmultiplier;
            $post_fields['multipler_quadruple_words'] = $sat_keyworddensity_quadrupleormorekeywordweightmultiplier;
            $post_fields['stop_words_mode'] = $sat_keyworddensity_stopwordmode;
            $post_fields['min_density'] = $sat_keyworddensity_mindensitytobeused;
            $ret = $this->docurl($this->apiurl . '/tools/keyworddensity/new.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)), 'POST', 30, array(),
                json_encode((object) $post_fields));

            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }
            return false;
        }

        public function googleplusreviewrequest($sat_keyworddensity_url, $sat_keyworddensity_ignorebody) {
            if (empty($this->session) && ! $this->login()) {
                return false;
            }

            $post_fields = array();
            $post_fields['url'] = $sat_keyworddensity_url;
            $post_fields['name'] = $sat_keyworddensity_ignorebody;

            // print_r(json_encode((object) $post_fields));
            $ret = $this->docurl($this->apiurl . '/tools/reviewrequest/new.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)), 'POST', 30, array(),
                json_encode((object) $post_fields));

            if ($ret['info']['http_code'] == 200 && strcasecmp($ret['info']['content_type'], 'application/pdf') === 0) {
                return $ret['content'];
            }
            return false;
        }

        // public function sitereport($sat_newreporteranker_id) {
        // if (empty($sat_newreporteranker_id)) {
        // return false;
        //
        // }
        //
        // if (empty($this->session) && !$this->login()) {
        // return false;
        // }
        //
        // $ret = $this->docurl($this->apiurl . '/report/sitereport/'.$sat_newreporteranker_id.'.json?' . http_build_query(array('email' => $this->email, 'session' => $this->session)));
        // if (!empty($ret['content'])) {
        // $responseobj = json_decode(trim($ret['content']));
        // return $responseobj;
        // }
        // return false;
        // }
        public function countrylist() {
            if (empty($this->session) && ! $this->login()) {
                return false;
            }
            $ret = $this->docurl($this->apiurl . '/country/list.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)));
            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }
            return false;
        }

        public function reportget($id, $subtype = '') {
            if ($id === null || $id === "" || $id === 0 || (empty($this->session) && ! $this->login())) {
                return false;
            }
            if ($subtype === '1stpage') {
                $subtype = 'firstpage';
            }
            if ($subtype === 'advertisers') {
                $subtype = 'advertisers';
            }
            if (! empty($subtype) && strcasecmp($subtype, 'sitereport') !== 0 && strcasecmp($subtype, 'firstpage') !== 0 && strcasecmp($subtype, 'advertisers') !== 0 &&
                strcasecmp($subtype, 'heatmap') !== 0 && strcasecmp($subtype, 'ranktracker') !== 0 && strcasecmp($subtype, 'citations') !== 0 && strcasecmp($subtype, 'authors') !== 0) {
                return false;
            }

            $ret = $this->docurl($this->apiurl . '/report/' . (! empty($subtype) ? $subtype . '/' : '') . $id . '.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)));

            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }

            return false;
        }

        public function pluginlog($domain, $url, $action, $email_log = null) {
            if (empty($domain) || empty($url) || empty($action)) {
                return false;
            }
            $post_fields = array();
            $post_fields['domain'] = (empty($domain)) ? '' : $domain;
            $post_fields['url'] = (empty($url)) ? '' : $url;
            if (! empty($action) && (strcasecmp($action, 'ACTIVATE') === 0 || strcasecmp($action, 'DEACTIVATE') === 0 || strcasecmp($action, 'LINK') === 0 || strcasecmp($action, 'UNLINK') === 0)) {
                $post_fields['action'] = $action;
            } else {
                return false;
            }

            if (! empty($this->email)) {
                $post_fields['email'] = $this->email;
            } else {
                $post_fields['email'] = $email_log;
            }

            $ret = $this->docurl($this->apiurl . '/plugin/log.json?', 'POST', 30, array(), json_encode((object) $post_fields));

            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                return $responseobj;
            }
            return false;
        }

        public function siteanalysis($data, $mode = 'generate') {
            $cachefile = $this->cachefolder . md5($data . $mode) . "-sat-apigeoranker.json";
            if ($this->cache && file_exists($cachefile) && (filemtime($cachefile) > (time() - 60 * $this->cachetime))) {
                $cacheobj = json_decode(file_get_contents($cachefile));
                if (! empty($cacheobj)) {
                    return $cacheobj;
                }
            }
            if (empty($this->session) && ! $this->login()) {
                return false;
            }
            if (strcmp($mode, 'batch') === 0) {
                $ret = $this->docurl($this->apiurl . '/site/' . $mode . '.json?' . http_build_query(array('email' => $this->email,'session' => $this->session)), 'POST', 30, array(), json_encode(
                    $data));
            } else {
                $ret = $this->docurl($this->apiurl . '/site/' . $mode . '.json?' . http_build_query(array('email' => $this->email,'session' => $this->session,'url' => $data)), 'GET', 30, array());
            }
            if (! empty($ret['content'])) {
                $responseobj = json_decode(trim($ret['content']));
                if ($this->cache && ! empty($responseobj) && ! isset($responseobj->debug) && ! isset($responseobj->msg)) {
                    file_put_contents($cachefile, trim($ret['content']));
                }
                return $responseobj;
            }
            return false;
        }

        /**
         * Make a json string be easier to read
         *
         * @param string $json
         *            a valid json string
         * @return string The new and improved json string
         */
        static function beautifyJSON($json) {
            $result = '';
            $pos = 0;
            $strLen = strlen($json);
            $indentStr = '  ';
            $newLine = "\n";
            $prevChar = '';
            $outOfQuotes = true;
            for ($i = 0; $i <= $strLen; $i ++) {
                // Grab the next character in the string.
                $char = substr($json, $i, 1);
                // Are we inside a quoted string?
                if ($char == '"' && $prevChar != '\\') {
                    $outOfQuotes = ! $outOfQuotes;
                    // If this character is the end of an element,
                    // output a new line and indent the next line.
                } else 
                    if (($char == '}' || $char == ']') && $outOfQuotes) {
                        $result .= $newLine;
                        $pos --;
                        for ($j = 0; $j < $pos; $j ++) {
                            $result .= $indentStr;
                        }
                    }
                // Add the character to the result string.
                $result .= $char;
                // If the last character was the beginning of an element,
                // output a new line and indent the next line.
                if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                    $result .= $newLine;
                    if ($char == '{' || $char == '[') {
                        $pos ++;
                    }

                    for ($j = 0; $j < $pos; $j ++) {
                        $result .= $indentStr;
                    }
                }
                $prevChar = $char;
            }
            return $result;
        }

        private function docurl($url, $method = 'GET', $timeout = 30, $options = array(), $post_fields = '') {
            $args = array('method' => $method,'timeout' => $timeout,'sslverify' => false);

            if (strcasecmp($method, 'POST') === 0) {
                $args = array_merge($args, array('headers' => array('Content-type' => 'application/json','Content-length' => strlen($post_fields)),'body' => $post_fields));
            }

            $args = array_merge($args, $options);
            $response = wp_remote_request($url, $args);
            $headers = wp_remote_retrieve_headers($response);
            $content = wp_remote_retrieve_body($response);
            $theinfo = array('http_code' => wp_remote_retrieve_response_code($response),'content_type' => wp_remote_retrieve_header($response, 'content-type'));
            $outarray = array('headers' => $headers,'content' => $content,'info' => $theinfo);
            return $outarray;
        }
    }
}
    