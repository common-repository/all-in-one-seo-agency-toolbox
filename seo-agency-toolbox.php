<?php

/*
  Plugin Name: All-in-One SEO Agency ToolBox
  Plugin URI: http://www.georanker.com/wordpress-plugin/
  Description: Provide GeoRanker and eRanker SEO tools like <strong>Rank Tracker, Top Advertisers, Google First Page, Local Carrousel Data</strong> and others. <br/> To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://www.georanker.com/register" target="_blank">Sign up for an GeoRanker API key</a>, and 3) Go to your <a href="admin.php?page=sat_page_settings">All-in-One SEO Agency ToolBox</a> page, and save your API key.
  Version: 3.0.6
  Author: georanker
  Author URI: http://www.GeoRanker.com/
  Network: false
  Licence: GNU General Public License v3

  This file is part of "All-in-One SEO Agency ToolBox" plugin for WordPress.

  "All-in-One SEO Agency ToolBox" Plugin is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**/////////////////////////////////////////////////////////////////////////////
// Plugin Contants definition
//////////////////////////////////////////////////////////////////////////////*/
define('SAT_VER', '3.0.6');
define('SAT_FOLDERNAME', 'all-in-one-seo-agency-toolbox');
define('SAT_PAGETITLE', 'all-in-one-seo-agency-toolbox'); //Do not change or the CSS will broke!
define('SAT_ACT_REPORT', 'report');

/**/////////////////////////////////////////////////////////////////////////////
// Check to make sure you meet the requirements
//////////////////////////////////////////////////////////////////////////////*/
global $wp_version;
if (version_compare($wp_version, "3.1", "<")) {
    exit('Sorry, but "All-in-One SEO Agency ToolBox" no longer support pre-3.1 WordPress installs.');
}
if (!function_exists('curl_version')) {
    exit('Sorry, but "All-in-One SEO Agency ToolBox" needs CURL PHP extension to work.');
}

/**/////////////////////////////////////////////////////////////////////////////
// Check if Wordpress is Loaded
//////////////////////////////////////////////////////////////////////////////*/
if (!function_exists('add_action')) {
    exit('Sorry, you can not execute this file without wordpress.');
}

/**/////////////////////////////////////////////////////////////////////////////
// Load Languages
//////////////////////////////////////////////////////////////////////////////*/
load_plugin_textdomain('sat', false, basename(dirname(__FILE__)) . '/languages', 'languages');

/**/////////////////////////////////////////////////////////////////////////////
// Load Pages titles
//////////////////////////////////////////////////////////////////////////////*/
global $sat_titles;
$sat_titles = array();
$sat_titles[SAT_ACT_REPORT] = "";

/**/////////////////////////////////////////////////////////////////////////////
// Cachetimes 
//////////////////////////////////////////////////////////////////////////////*/
global $sat_reportcachetime, $sat_nocache;
$sat_nocache = true;
$sat_reportcachetime = 3600 * 24 * 7;


/**/////////////////////////////////////////////////////////////////////////////
// Includes
//////////////////////////////////////////////////////////////////////////////*/
require_once ('includes/SATGeoRankerAPI.class.php');
require_once ('includes/actions.php');
require_once ('includes/widget-shortcodes_view_report.php');
require_once ('includes/widget-shortcodes_create_report.php');


/**/////////////////////////////////////////////////////////////////////////////
// Load settings from database
//////////////////////////////////////////////////////////////////////////////*/
global $sat_settings;
global $sat_pageid;
$sat_pageid = 0;

function sat_readsettings() {
    global $sat_settings, $sat_pageid;
    //Load the serialized array of settings for this plugin
    $sat_settings = get_option('sat_settings');
    $sat_pageid = get_option('sat_pageid');
    if (empty($sat_settings)) {
        $sat_settings = array('apikey' => '', 'email' => '', 'apikey_invalid' => 1);
    }
}

function sat_savesettings($settings, $log = true) {
    $out = update_option('sat_settings', $settings);

    if ($out && $log) {

        global $grapi, $sat_settings;

        if (!isset($grapi) || $grapi == NULL || empty($grapi)) {
            $grapi = new SATGeoRankerAPI("", "");
        }

        $grapi->pluginlog(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown', isset($settings['email']) && isset($settings['apikey']) && !empty($settings['email']) && !empty($settings['apikey']) ? 'LINK' : ' UNLINK', !empty($settings['email']) ? $settings['email'] : $grapi->email );
    }

    return $out;
}

sat_readsettings();

/**/////////////////////////////////////////////////////////////////////////////
// Add URLs on the plugin description
//////////////////////////////////////////////////////////////////////////////*/
add_filter('plugin_row_meta', 'sat_pluginpagelinks_content', 10, 2);
add_action('plugin_action_links_' . basename(dirname(__FILE__)) . '/' . basename(__FILE__), 'sat_pluginpagelinks_left', 10, 4);

function sat_pluginpagelinks_content($links, $file) {
    if ($file == plugin_basename(basename(dirname(__FILE__)) . '/' . basename(__FILE__))) {
        $links[] = '<a href="http://www.georanker.com/register" target="_blank">' . __('Get an API Key', 'sat') . '</a>';
        $links[] = '<a href="http://www.georanker.com/contactus" target="_blank">' . __('Contact Support', 'sat') . '</a>';
    }
    return $links;
}

function sat_pluginpagelinks_left($links) {
    $settings_link = '<a href="admin.php?page=sat_page_settings">' . __('Settings', 'sat') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/**/////////////////////////////////////////////////////////////////////////////
// Add pages menu on admin side
//////////////////////////////////////////////////////////////////////////////*/

function sat_admin_add_page() {
    global $sat_settings;
    add_menu_page(__('SEO Agency ToolBox', 'sat'), __('SEO Agency ToolBox', 'sat'), 'manage_options', 'sat_page_settings', 'sat_page_settings', plugins_url(basename(dirname(__FILE__)) . '/images/georanker-plugin-icon-20x20.png'), 417);
    add_submenu_page('sat_page_settings', __('GeoRanker Tool Settings', 'sat'), __('GeoRanker Settings', 'sat'), 'manage_options', 'sat_page_settings', 'sat_page_settings');

    $riscado_begin = '';
    $riscado_end = '';
    if (!isset($_POST['sat_settings']) && !sat_is_apikeyvalid()) {
        $riscado_begin = '<span style="text-decoration: line-through;" title="' . __('Please, setup the plugin first', 'sat') . '">';
        $riscado_end = '</span>';
    }
    add_submenu_page('sat_page_settings', 'wp-menu-separator', '', 'manage_options', 'sat_page_settings', 'sat_page_settings');
    add_submenu_page('sat_page_settings', __('Website Report', 'sat'), $riscado_begin . __('Website Report', 'sat') . $riscado_end, 'manage_options', 'sat_page_erankerreport', 'sat_page_erankerreport');
    add_submenu_page('sat_page_settings', __('Local Rank Checker', 'sat'), $riscado_begin . __('Local Rank Checker', 'sat') . $riscado_end, 'manage_options', 'sat_page_localrankchecker', 'sat_page_localrankchecker');
    add_submenu_page('sat_page_settings', __('First Page', 'sat'), $riscado_begin . __('First Page', 'sat') . $riscado_end, 'manage_options', 'sat_page_googlefirstpage', 'sat_page_googlefirstpage');
    add_submenu_page('sat_page_settings', __('Advertisers Report', 'sat'), $riscado_begin . __('Advertisers Report', 'sat') . $riscado_end, 'manage_options', 'sat_page_advertisersreport', 'sat_page_advertisersreport');
//    add_submenu_page('sat_page_settings', __('GMaps Rank Checker', 'sat'), $riscado_begin . __('GMaps Rank Checker', 'sat') . $riscado_end, 'manage_options', 'sat_page_gmapsrankchecker', 'sat_page_gmapsrankchecker');
//    add_submenu_page('sat_page_settings', __('GMaps First Page', 'sat'), $riscado_begin . __('GMaps First Page', 'sat') . $riscado_end, 'manage_options', 'sat_page_gmapsfirstpage', 'sat_page_gmapsfirstpage');
//    add_submenu_page('sat_page_settings', __('Top Authors Report', 'sat'), $riscado_begin . __('Top Authors Report', 'sat') . $riscado_end, 'manage_options', 'sat_page_topauthorsreport', 'sat_page_topauthorsreport');
    add_submenu_page('sat_page_settings', __('Citations Source Tool', 'sat'), $riscado_begin . __('Citations Source Tool', 'sat') . $riscado_end, 'manage_options', 'sat_page_citationssourcetool', 'sat_page_citationssourcetool');
//    add_submenu_page('sat_page_settings', __('Local SEO Analyzer', 'sat'), $riscado_begin . __('Local SEO Analyzer', 'sat') . $riscado_end, 'manage_options', 'sat_page_localseoanalyzer', 'sat_page_localseoanalyzer');
//    add_submenu_page('sat_page_settings', __('KML Generator', 'sat'), $riscado_begin . __('KML Generator', 'sat') . $riscado_end, 'manage_options', 'sat_page_kmlgenerator', 'sat_page_kmlgenerator');
    add_submenu_page('sat_page_settings', __('Keyword Density', 'sat'), $riscado_begin . __('Keyword Density', 'sat'), 'manage_options' . $riscado_end, 'sat_page_keyworddensity', 'sat_page_keyworddensity');
    add_submenu_page('sat_page_settings', __('Google Plus Review Request', 'sat'), $riscado_begin . __('G+ Review Request', 'sat') . $riscado_end, 'manage_options', 'sat_page_googleplusreviewrequest', 'sat_page_googleplusreviewrequest');
//    add_submenu_page('sat_page_settings', __('Structured Data Test', 'sat'), $riscado_begin . __('Structured Data Test', 'sat') . $riscado_end, 'manage_options', 'sat_page_structureddatatest', 'sat_page_structureddatatest');
//    add_submenu_page('sat_page_settings', __('NAP checker', 'sat'), $riscado_begin . __('NAP checker', 'sat') . $riscado_end, 'manage_options', 'sat_page_napchecker', 'sat_page_napchecker');
}

add_action('admin_menu', 'sat_admin_add_page');

/**/////////////////////////////////////////////////////////////////////////////
// Show an admin warning if the user does not setup the GeoRanker API Key
//////////////////////////////////////////////////////////////////////////////*/
if (is_admin() && !sat_is_apikeyvalid() && !isset($_POST['submit']) && !(isset($_GET['page']) && strcasecmp(trim($_GET['page']), 'sat_page_settings') == 0)) {

    function sat_warning_apikey() {
        echo " <div id='georanker-warning' class='updated fade'><p><strong>" . __('GeoRanker SEO Tools is almost ready to use.', 'sat') . "</strong> " . sprintf(__('You must <a href="%1$s">enter your GeoRanker API key</a> for it to work.', 'sat'), "admin.php?page=sat_page_settings") . "</p></div> ";
    }

    add_action('admin_notices', 'sat_warning_apikey');
}

/**/////////////////////////////////////////////////////////////////////////////
// Plugin activation hook
//////////////////////////////////////////////////////////////////////////////*/
register_activation_hook(basename(dirname(__FILE__)) . '/' . basename(__FILE__), 'sat_activate');

function sat_activate() {

    global $sat_pageid, $wpdb, $sat_db_version;
    delete_option('sat_pageid');
    $the_page = get_page_by_title(SAT_PAGETITLE);
    if (!$the_page) {
        // Create post object
        $_p = array();
        $_p['post_title'] = SAT_PAGETITLE;
        $_p['post_content'] = "This text may be overridden by the plugin. You shouldn't edit it.";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['post_name'] = SAT_PAGETITLE;
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        // Insert the post into the database
        $sat_pageid = wp_insert_post($_p);
    } else {
        // the plugin may have been previously active and the page may just be trashed...
        $sat_pageid = $the_page->ID;
        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $sat_pageid = wp_update_post($the_page);
    }

    delete_option('sat_pageid');
    add_option('sat_pageid', $sat_pageid, '', 'yes');

    $table_name = $wpdb->prefix . 'sat_sitereport';
    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted 
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';
    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }
    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		request text NULL,
		data longtext NULL
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    add_option('sat_db_version', $sat_db_version);

    global $grapi;
    if (!isset($grapi) || $grapi == NULL || empty($grapi)) {
        $grapi = new SATGeoRankerAPI("", "");
    }
    $grapi->pluginlog(
            isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown', 'ACTIVATE');

    // add a page for custom pending messages
    $custom_message = wp_insert_post(array(
        'post_name' => 'seo-agency-pending',
        'post_title' => 'SEO AGENCY PENDING',
        'post_content' => 'Your report is being generated.',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_date' => date("Y-m-d H:i:s"))
    );
}

/**/////////////////////////////////////////////////////////////////////////////
// Plugin deactivation/unistall hook
//////////////////////////////////////////////////////////////////////////////*/
register_deactivation_hook(basename(dirname(__FILE__)) . '/' . basename(__FILE__), 'sat_uninstall');
register_uninstall_hook(basename(dirname(__FILE__)) . '/' . basename(__FILE__), 'sat_uninstall');

function sat_uninstall() {

    $id = get_option('sat_pageid');
    if ($id == true) {
        wp_delete_post($id, true);
    }
    delete_option('sat_pageid');

    global $grapi;
    if (!isset($grapi) || $grapi == NULL || empty($grapi)) {
        $grapi = new SATGeoRankerAPI("", "");
    }
    $grapi->pluginlog(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown', 'DEACTIVATE');
}

add_filter('parse_query', 'sat_query_parser');

function sat_query_parser($q) {
    global $sat_pageid;
    if (!empty($q->query_vars['page_id']) AND ( intval($q->query_vars['page_id']) == $sat_pageid )) {
        $q->set(SAT_PAGETITLE . '_page_is_called', true);
    } elseif (isset($q->query_vars['pagename']) AND ( ($q->query_vars['pagename'] == SAT_PAGETITLE) OR ( strpos($q->query_vars['pagename'], SAT_PAGETITLE . '/') === 0))) {
        $q->set(SAT_PAGETITLE . '_page_is_called', true);
    } else {
        $q->set(SAT_PAGETITLE . '_page_is_called', false);
    }
}

add_filter('the_posts', 'sat_page_filter');

function sat_page_filter($posts) {
    global $wp_query, $sat_titles, $sat_action, $sat_subaction;
    if ($wp_query->get(SAT_FOLDERNAME . '_page_is_called')) {

        $sat_action = (isset($_GET['action']) && !empty($_GET['action'])) ? trim(strtolower(strip_tags(addslashes($_GET['action'])))) : '';
        $sat_subaction = (isset($_GET['subaction'])) ? trim(strtolower($_GET['subaction'])) : null;

        $posts[0]->post_title = htmlspecialchars(isset($sat_titles[$sat_action]) ? $sat_titles[$sat_action] : ucwords($sat_action));

        ob_start();
        switch ($sat_action) {
            case SAT_ACT_REPORT:
                call_user_func("sat_act_report");
                break;
            default:
                call_user_func("sat_act_home");
                break;
        }
        $newcontent = ob_get_contents();
        ob_end_clean();

        $posts[0]->post_content = $newcontent;
    }
    return $posts;
}

/**/////////////////////////////////////////////////////////////////////////////
// Call JS and CSS on all pages
//////////////////////////////////////////////////////////////////////////////*/

function sat_loadscripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style('jquery-ui-smoothness', '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', false, SAT_VER);

    //wp_enqueue_script('googleplacesapi', '//maps.googleapis.com/maps/api/js?libraries=places&amp;key=DEPRECATED', array('jquery'), SAT_VER, TRUE);
    wp_enqueue_script('satjs', plugins_url(basename(dirname(__FILE__)) . '/js/base.js'), array('jquery', 'jquery-ui-core'), SAT_VER, TRUE);
    //wp_enqueue_script('circlesjs', plugins_url(basename(dirname(__FILE__)) . '/js/vendor/circles.min.js'), array(), SAT_VER);
    //wp_enqueue_script('d3js', plugins_url(basename(dirname(__FILE__)) . '/js/vendor/d3.min.js'), array(), SAT_VER);
    wp_enqueue_script('gmapjs', plugins_url(basename(dirname(__FILE__)) . '/js/vendor/gmaps.js'), array(), SAT_VER, TRUE);
    //wp_enqueue_script('readmorejs', plugins_url(basename(dirname(__FILE__)) . '/js/vendor/readmore.min.js'), array(), SAT_VER);
    //wp_enqueue_script('erjs', plugins_url(basename(dirname(__FILE__)) . '/js/eranker.js'), array('jquery'), SAT_VER);
    //wp_enqueue_script('errpjs', plugins_url(basename(dirname(__FILE__)) . '/js/erreport.js'), array(), SAT_VER);
    wp_enqueue_style('satcss', plugins_url(basename(dirname(__FILE__)) . '/css/base.css'), array(), SAT_VER);
    //wp_enqueue_style('ercss', plugins_url(basename(dirname(__FILE__)) . '/css/eranker.css'), array('satcss'), SAT_VER);
    wp_enqueue_style('satthemelitecss', plugins_url(basename(dirname(__FILE__)) . '/css/theme.lite.css'), array('satcss'), SAT_VER);
    //wp_enqueue_style('fontawsome', plugins_url(basename(dirname(__FILE__)) . '/css/vendor/font-awesome.min.css'));
}

function sat_loadscripts_foradmin() {
    wp_enqueue_style('satcss_foradmin', plugins_url(basename(dirname(__FILE__)) . '/css/adminpages.css'), array('satcss'), SAT_VER);
}

add_action('wp_enqueue_scripts', 'sat_loadscripts');
add_action('admin_enqueue_scripts', 'sat_loadscripts');
add_action('admin_enqueue_scripts', 'sat_loadscripts_foradmin');

/**/////////////////////////////////////////////////////////////////////////////
// Define all functions to load the pages
//////////////////////////////////////////////////////////////////////////////*/

add_action('plugins_loaded', 'sat_load_init');

function sat_load_init() {
    require_once 'includes/googleplusreviewrequest-init.php';
}

function sat_page_settings() {
    require 'includes/settings.php';
}

function sat_page_localrankchecker() {
    require 'includes/localrankchecker.php';
}

function sat_page_erankerreport() {
    require 'includes/newreporteranker.php';
}

function sat_page_googlefirstpage() {
    require 'includes/googlefirstpage.php';
}

function sat_page_gmapsrankchecker() {
    require 'includes/gmapsrankchecker.php';
}

function sat_page_gmapsfirstpage() {
    require 'includes/gmapsfirstpage.php';
}

function sat_page_advertisersreport() {
    require 'includes/advertisersreport.php';
}

function sat_page_topauthorsreport() {
    require 'includes/topauthorsreport.php';
}

function sat_page_citationssourcetool() {
    require 'includes/citationssourcetool.php';
}

function sat_page_localseoanalyzer() {
    require 'includes/localseoanalyzer.php';
}

function sat_page_kmlgenerator() {
    require 'includes/kmlgenerator.php';
}

function sat_page_keyworddensity() {
    require 'includes/keyworddensity.php';
}

function sat_page_googleplusreviewrequest() {
    require 'includes/googleplusreviewrequest.php';
}

function sat_page_structureddatatest() {
    require 'includes/structureddatatest.php';
}

function sat_page_napchecker() {
    require 'includes/napchecker.php';
}

/**/////////////////////////////////////////////////////////////////////////////
// Generic functions 
//////////////////////////////////////////////////////////////////////////////*/

function sat_redirectviewreportpage($url) {
    return '<script type="text/javascript"> window.location="' . $url . '"; </script>';
}

function sat_is_apikeyvalid($forcecheck = false) {
    global $sat_settings;
    if ($forcecheck) {
        //TODO: implement a way to force check
    } else {
        return !empty($sat_settings['email']) && !empty($sat_settings['apikey']) && !$sat_settings['apikey_invalid'];
    }
}

function set_echoerror($errorcode = 503, $details = '') {
    switch ($errorcode) {
        case 404:
            header("HTTP/1.0 404 Not Found");
            echo "<h1>" . __('Error 404 - Not Found', 'sat') . "</h1>";
            echo "<h4>" . __('The report you tried to load was not found.', 'sat') . "</h4>";
            echo "<p>" . __('It seem the page you were looking for has moved or is no longer there. Or maybe you just mistyped something. It happens.', 'sat') . "</p>";

            break;
        case 503:
        default:
            header("HTTP/1.0 503 Service Unavailable");
            echo "<h1>" . __('Error 503 - Service Unavailable', 'sat') . "</h1>";
            echo "<h4>" . __('Unable to load the report.', 'sat') . "</h4>";
            echo "<p>" . __('Unable to connect to the server API. Please try again in a few minutes. If the error persists contact the administrator.', 'sat') . "</p>";
            break;
    }
    if (!empty($details)) {
        echo "<p>" . __('Details:', 'sat') . " " . $details . "</p>";
    }
}

function sat_getimagefortypesfromcode($code) {
    if (empty($code)) {
        return "";
    }
    switch ($code) {
        case 'OR':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-organic.png') . '" alt="Organic" title="Organic" style="vertical-align: text-top;display: inline;"/>';
        case 'IM':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-image.png') . '" alt="Image" title="Image" style="vertical-align: text-top;display: inline;"/>';
        case 'VI':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-video.png') . '" alt="Video" title="Video" style="vertical-align: text-top;display: inline;"/>';
        case 'YT':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-youtube.png') . '" alt="Youtube" title="Youtube" style="vertical-align: text-top;display: inline;"/>';
        case 'FB':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-facebook.png') . '" alt="Facebook" title="Facebook" style="vertical-align: text-top;display: inline;"/>';
        case 'GM':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-gmaps.png') . '" alt="Google Maps" title="Google Maps" style="vertical-align: text-top;display: inline;"/>';
        case 'GP':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-gplus.png') . '" alt="Google Plus" title="Google Plus" style="vertical-align: text-top;display: inline;"/>';
        case 'TW':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-twitter.png') . '" alt="Twitter" title="Twitter" style="vertical-align: text-top;display: inline;"/>';
        case 'WI':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-wikipedia.png') . '" alt="Wikipedia" title="Wikipedia" style="vertical-align: text-top;display: inline;"/>';
        case 'LK':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-linkedin.png') . '" alt="LinkedIn" title="LinkedIn" style="vertical-align: text-top;display: inline;"/>';
        case 'NW':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-news.png') . '" alt="Google News" title="Google News" style="vertical-align: text-top;display: inline;"/>';
        case 'SH':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-shop.png') . '" alt="Google Shop" title="Google Shop" style="vertical-align: text-top;display: inline;"/>';
        case 'SC':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-search.png') . '" alt="Search" title="Search" style="vertical-align: text-top;display: inline;"/>';
        case 'AM':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-amazon.png') . '" alt="Amazon" title="Amazon" style="vertical-align: text-top;display: inline;"/>';
        case 'YH':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-yahoo.png') . '" alt="Yahoo!" title="Yahoo!" style="vertical-align: text-top;display: inline;"/>';
        case 'GO':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-google.png') . '" alt="Google Link" title="Google Link" style="vertical-align: text-top;display: inline;"/>';
        case 'MT':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-metacafe.png') . '" alt="Metacafe" title="Metacafe" style="vertical-align: text-top;display: inline;"/>';
        case 'EB':
            return '<img src="' . plugins_url(basename(dirname(__FILE__)) . '/images/tag-ebay.png') . '" alt="eBay" title="eBay" style="vertical-align: text-top;display: inline;"/>';
        default:
            return '';
    }
}

/**
 * Add an query string on the end of an url
 * @param String $url The original URL
 * @param String $query The query string to be added
 * @return String the final URL with the added query string
 */
function sat_addqueryonurl($url, $query) {
    $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
    return $url . $separator . $query;
}

/**
 * Get the plugin fruntend page URL. The the page does not exist, we use a default one.
 * @return String the URL for the plugin frontend page
 */
function sat_getfrontendurl() {
    $the_page = get_page_by_title(SAT_PAGETITLE);
    return !$the_page ? WP_HOME . '/all-in-one-seo-agency-toolbox/' : get_permalink($the_page->ID);
}

//Create table

global $sat_db_version;
// This version has to be integer number
$sat_db_version = 1; 

function sat_install() {
    global $wpdb;
    global $sat_db_version;

    $table_name = $wpdb->prefix . 'sat_reports';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
            `token` varchar(50) COLLATE utf8_bin NOT NULL,
            `date` datetime NOT NULL,
            `ip` varchar(50) NOT NULL,
            `user_id` bigint(20) unsigned DEFAULT NULL,
             KEY `idx_".$sat_db_version."_satplugin_date` (`date`),
             KEY `idx_".$sat_db_version."_satplugin_ip` (`ip`),
             KEY `idx_".$sat_db_version."_satplugin_userid` (`user_id`)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    add_option('sat_db_version', $sat_db_version);
}

register_activation_hook(__FILE__, 'sat_install');

function sat_update_db_check() {
    global $sat_db_version;
    if (get_site_option('sat_db_version') != $sat_db_version) {
        sat_install();
    }
}

add_action('plugins_loaded', 'sat_update_db_check');
