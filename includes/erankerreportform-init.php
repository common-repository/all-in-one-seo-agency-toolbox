<?PHP

global $sat_settings;
global $sat_countrylist;
global $sat_error;
global $sat_error_msg;
global $sat_accountinfo;
global $sat_action;
global $sat_subaction;
global $sat_newreporteranker_url;
global $urlViewReporteRanker;
global $sat_reportobj;


// Check if Wordpress is Loaded
if (!function_exists('add_action')) {
    exit('Sorry, you can not execute this file without wordpress.');
}
$sat_error = FALSE;
$sat_error_msg = '';
$urlViewReporteRanker = '';

//Conect to the API and test the apikey
$grapi = new SATGeoRankerAPI($sat_settings['email'], $sat_settings['apikey']);
$sat_countrylist = $grapi->countrylist();
if (empty($sat_countrylist) || isset($sat_countrylist->debug)) {
    $sat_error = TRUE;
    $sat_error_msg = 'Unable to get the updated list of countries.';
}
$sat_accountinfo = $grapi->accountinfo();
if (empty($sat_accountinfo)) {
    $sat_error = TRUE;
    $sat_error_msg = 'Unable to get the updated list of countries.';
}

if (isset($_POST['sat_newreporteranker_url'])) {


    $sat_newreporteranker_url = isset($_POST['sat_newreporteranker_url']) ? $_POST['sat_newreporteranker_url'] : "";
    $sat_newreporteranker_companyname = isset($_POST['sat_newreporteranker_company']) ? $_POST['sat_newreporteranker_company'] : "";
    $sat_newreporteranker_phone = (isset($_POST['sat_newreporteranker_phone']) && !empty($_POST['sat_newreporteranker_phone'])) ? $_POST['sat_newreporteranker_phone'] : "";
    $sat_newreporteranker_region = isset($_POST['sat_newreporteranker_region']) ? $_POST['sat_newreporteranker_region'] : "";
    $sat_newreporteranker_country = isset($_POST['sat_newreporteranker_country']) ? $_POST['sat_newreporteranker_country'] : "";

    $sat_type = 'sitereport';
    $sat_newreport_keywords = null;
    $sat_is_global = false;
    $sat_countries = $_POST['sat_countries'];
    $sat_customcitieslist = $_POST['sat_customcitieslist'];
    $sat_sendreportviaemailcb = null;
    $sat_maxcities = $_POST['sat_maxcities'];
    $sat_is_usealternativetld = false;
    $sat_is_fillcities = false;
    $sat_is_formobile = false;
    $sat_is_gmsearchmode = false;
    $sat_is_carouselfallbackmode = false;
    $sat_is_localonly = false;
    $sat_countryselect = $_POST['sat_countryselect'];
    $sat_language = null;
    $sat_newreport_brand = $sat_newreporteranker_companyname;
    $sat_ignoretypes = null;
    $sat_fields = null;

    //URL
    if (isset($sat_newreporteranker_url)) {
        if (strlen($sat_newreporteranker_url) < 5 || strpos($sat_newreporteranker_url, ".") === FALSE) {
            $sat_error = TRUE;
            $sat_error_msg = __("Is not a valid url. Must possess the url http: // or https: //", 'sat');
            $sat_newreporteranker_url = null;
        } else {
            if (strpos($sat_newreporteranker_url, "http://") === FALSE || strpos($sat_newreporteranker_url, "https://") === FALSE) {
                $sat_newreporteranker_url = 'http://' . $sat_newreporteranker_url;
            }
        }
    } else {
        $sat_newreporteranker_url = null;
    }
    if (empty($sat_newreporteranker_url)) {
        $sat_error = TRUE;
        $sat_error_msg = __("You must specify a valid URL or domain. <br/>A URL must have at least 5 caracters and a dot (.).", 'sat');
    }

    $sat_newreport_url = $sat_newreporteranker_url;

    //CITIES
    $sat_cities = array();
    if (empty($sat_is_global)) {
        $citiesarr = explode(";", urldecode(trim($sat_customcitieslist)));
        $countcities = 0;
        foreach ($citiesarr as $value) {
            $value = trim(mb_strtolower($value));
            if (!empty($value) && mb_strlen($value) > 1 && !in_array($value, $sat_cities)) {
                $sat_cities[] = $value;
                $countcities++;
            }
            if ($countcities >= $sat_maxcities) {
                break;
            }
        }
        unset($citiesarr);
        unset($countcities);
        $sat_cities = array_unique($sat_cities);
    } else {
        $sat_cities = array();
    }
    unset($sat_customcitieslist);

    //COUNTRIES
    if (!empty($sat_is_global)) {
        if (count($sat_countries) < 2 || !is_array($sat_countries)) {
            $sat_error = TRUE;
            $sat_error_msg = __('On global reports you must choose at least two countries.', 'sat');
        } else {
            $sat_countries = array_unique($sat_countries);
        }
    } else {
        $sat_countries = array($sat_countryselect);
    }

    //BRAND
    if (!isset($sat_newreport_brand) || empty($sat_newreport_brand)) {
        $sat_newreport_brand = null;
    }
    if ($sat_error === FALSE) {
        $callback = null;
        $sat_keywords = null;
        $sat_reportobj = $grapi->reportnew($sat_type, $sat_keywords, $sat_countries, $sat_is_global, $sat_maxcities, $sat_cities, $sat_newreport_url, $sat_language, $sat_ignoretypes, $sat_is_usealternativetld, $sat_is_fillcities, $sat_is_formobile, $callback, $sat_newreport_brand, $sat_is_gmsearchmode, $sat_is_localonly, $sat_is_carouselfallbackmode, $sat_newreporteranker_phone, $sat_fields);
        if (empty($sat_reportobj)) {
            $sat_error = TRUE;
            $sat_error_msg = __('Could not create a report.<br/>An unknown error occurred', 'sat');
        } else {
            if (isset($sat_reportobj->msg)) {
                $sat_error = TRUE;
                $sat_error_msg = $sat_report->msg . '<br/>' . $sat_reportobj->solution;
            } else {
                $urlViewReporteRanker = sat_addqueryonurl(sat_getfrontendurl(), 'action=report&subaction=' . $sat_reportobj->id);                
            }
        }
    }
}

