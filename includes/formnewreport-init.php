<?PHP

global $sat_settings;
global $sat_countrylist;
global $sat_error;
global $sat_error_msg;
global $sat_accountinfo;
global $urlViewReporteRanker;
global $sat_report;

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

function sat_htmlcbforcountrybycontinent($countrydata, $continentname) {
    $out = "";
    if (empty($countrydata)) {
        return $out;
    }
    $countries = array();
    foreach ($countrydata as $singlecountry) {
        if (strcasecmp($singlecountry->continent, trim($continentname)) == 0 && $singlecountry->is_active) {
            $countries[$singlecountry->code] = $singlecountry;
        }
    }

    foreach ($countries as $value) {
        $importantcountry = '';
        $classnamearr = 'countryflag' . $value->code;
        if (isset($_POST['countries']) && is_array($_POST['countries']) && in_array($value->code, $_POST['countries'])) {
            $checkedclass = 'checked="checked"';
        } else {
            $checkedclass = '';
        }
        $out.= '<label class="sat_labelcountry" for="sat_globalreport-country-' . $value->code . '"><input id="sat_globalreport-country-' . $value->code . '" class="sat_checkboxcountry" type="checkbox" value="' . $value->code . '" name="sat_countries[]" ' . $checkedclass . ' data-code="' . $value->code . '" data-name="' . stripcslashes($value->name) . '"><div class="sat_flagcountry" style="background-image: url(\'' . plugins_url(SAT_FOLDERNAME . '/images/flags/16/' . trim(strtoupper($value->code)) . '.png') . '\')"></div><div class="sat_namecountry">' . $value->name . ' </div></label>';
    }
    return $out;
}

if (isset($_POST) && isset($_POST['sat_type']) && !empty($_POST['sat_type'])) {

    $sat_type = (strcasecmp($_POST['sat_type'], 'firstpage') === 0) ? '1stpage' : $_POST['sat_type'];
    $sat_newreport_url = isset($_POST['sat_newreport_url'])?$_POST['sat_newreport_url']: NULL;
    $sat_newreport_keywords = str_replace(';', "\n", str_replace(',', "\n", isset($_POST['sat_newreport_keywords'])?$_POST['sat_newreport_keywords']: NULL));
    $sat_is_global = isset($_POST['sat_is_global'])?$_POST['sat_is_global']: NULL;
    $sat_countries = isset($_POST['sat_countries'])? $_POST['sat_countries']: NULL;
    $sat_customcitieslist = isset($_POST['sat_customcitieslist'])?$_POST['sat_customcitieslist']: NULL;
    $sat_sendreportviaemailcb = isset($_POST['sat_sendreportviaemailcb'])?$_POST['sat_sendreportviaemailcb']: NULL;
    $sat_maxcities = isset($_POST['sat_maxcities'])?$_POST['sat_maxcities']: NULL;
    $sat_is_usealternativetld = isset($_POST['sat_is_usealternativetld'])?$_POST['sat_is_usealternativetld']: NULL;
    $sat_is_fillcities =isset($_POST['sat_is_fillcities'])?$_POST['sat_is_fillcities']: NULL;
    $sat_is_formobile = isset($_POST['sat_is_formobile'])?$_POST['sat_is_formobile']: NULL;
    $sat_is_gmsearchmode = isset($_POST['sat_is_gmsearchmode'])?$_POST['sat_is_gmsearchmode']: NULL;
    $sat_is_carouselfallbackmode = isset($_POST['sat_is_carouselfallbackmode'])?$_POST['sat_is_carouselfallbackmode']: NULL;
    $sat_is_localonly = isset($_POST['sat_is_localonly'])?$_POST['sat_is_localonly']: NULL;
    $sat_countryselect = isset($_POST['sat_countryselect'])?$_POST['sat_countryselect']: NULL;
    $sat_language =isset($_POST['sat_language'])?$_POST['sat_language']: NULL;
    $sat_newreport_brand = isset($_POST['sat_brand'])?$_POST['sat_brand']: NULL;
    $sat_ignoretypes = (isset($_POST['sat_ignoretypes']) && !empty($_POST['sat_ignoretypes'])) ? explode(';', $_POST['sat_ignoretypes']) : array();
    $sat_phone = '';
    $sat_fields = '';
    $sat_onlyonekeyword = isset($_POST['sat_onlyonekeyword'])?$_POST['sat_onlyonekeyword']: NULL;

    if (isset($_POST['ignoregm']) && !empty($_POST['ignoregm'])) {
        $ignoregm = TRUE;
    } else {
        $ignoregm = FALSE;
    }

    //URL
    if (isset($sat_newreport_url)) {
        $sat_newreport_url = str_replace('https://', '', str_replace('http://', '', strtolower(trim($sat_newreport_url))));
        if (strlen($sat_newreport_url) < 5 || strpos($sat_newreport_url, ".") === FALSE) {
            $sat_newreport_url = null;
        }
    } else {
        $sat_newreport_url = null;
    }
    if (strcmp($sat_type, "ranktracker") == 0 && empty($sat_newreport_url)) {
        $sat_error = TRUE;
        $sat_error_msg = __("You must specify a valid URL or domain in reports of the type 'Rank Tracker'.<br/>A URL must have at least 5 characters and a dot (.).", 'sat');
    }
    if (strcmp($sat_type, "citations") == 0 && $sat_maxcities < 5 && empty($sat_is_global)) {
        $sat_error = TRUE;
        $sat_error_msg = __("For citations report you must choose at least five cities.", 'sat');
    }

    //KEYWORDS


    $sat_keywordsarr = explode("\n", $sat_newreport_keywords);
    $sat_keywords = array();
    if (empty($sat_keywordsarr)) {
        $sat_error = TRUE;
        $sat_error_msg = __('You must specify at least one keyword.<br/>The keyword must contain at least 3 characters.', 'sat');
    }
    
    if (!empty($sat_keywordsarr)) {
        if ($sat_onlyonekeyword == 1) {
            if (count($sat_keywordsarr) == 1) {
                foreach ($sat_keywordsarr as $value) {
                    $value = trim(mb_strtolower($value));
                    if (!empty($value) && mb_strlen($value) > 2 && !in_array($value, $sat_keywords)) {
                        $sat_keywords[] = $value;
                    }
                }
            } else {
                $sat_error = TRUE;
                $sat_error_msg = __('You must specify only one keyword.<br/> The keyword must contain at least 3 characters.', 'sat');
            }
        } else {
            foreach ($sat_keywordsarr as $value) {
                $value = trim(mb_strtolower($value));
                if (!empty($value) && mb_strlen($value) > 2 && !in_array($value, $sat_keywords)) {
                    $sat_keywords[] = $value;
                }
            }
        }
    }

    

    unset($sat_keywordsarr);
    unset($sat_newreport_keywords);
    
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
//        if (count($sat_countries) < 2 || !is_array($sat_countries)) {
//            $sat_error = TRUE;
//            $sat_error_msg = __('On global reports you must choose at least two countries.', 'sat');
//        } else {
            $sat_countries = array_unique($sat_countries);
        //}
    } else {
        $sat_countries = array($sat_countryselect);
    }
    
    //BRAND
    if (!isset($sat_newreport_brand) || empty($sat_newreport_brand)) {
        $sat_newreport_brand = null;
    }

    if (!empty($sat_is_global) && strcasecmp($sat_type, "citations") == 0 && count($sat_countries) < 5) {
        $sat_error = TRUE;
        $sat_error_msg = "On global <b>Citations</b> reports you must choose at least five countries.";
    }
    $ignoreorganic = FALSE;

    if ($ignoregm == TRUE && $sat_is_localonly == TRUE) {
        $ignoretypes = 'gm,or,im,vi,yt,fb,gp,tw,wi,lk,nw,sh,sc,am,yh,go,mt,eb';
    } else {
        if ($sat_is_localonly == TRUE) {
            $ignoretypes = 'or,im,vi,yt,fb,gp,tw,wi,lk,nw,sh,sc,am,yh,go,mt,eb';
        } else {
            if ($ignoregm == TRUE && $ignoreorganic == TRUE) {
                $ignoretypes = '';
            } else {
                if ($ignoregm == TRUE && $ignoreorganic == FALSE) {
                    $ignoretypes = 'gm';
                } else {
                    if ($ignoreorganic == TRUE) {
                        $ignoretypes = 'or';
                    } else {
                        $ignoretypes = '';
                    }
                }
            }
        }
    }
    
    if ($sat_error === FALSE) {
        $callback = sat_addqueryonurl(sat_getfrontendurl(), 'action=callback');
        $sat_report = $grapi->reportnew($sat_type, $sat_keywords, $sat_countries, $sat_is_global, $sat_maxcities, $sat_cities, $sat_newreport_url, $sat_language, $ignoretypes, $sat_is_usealternativetld, $sat_is_fillcities, $sat_is_formobile, $callback, $sat_newreport_brand, $sat_is_gmsearchmode, $sat_is_localonly, $sat_is_carouselfallbackmode, $sat_phone, $sat_fields);
       
        if (empty($sat_report)) {
            $sat_error = TRUE;
            $sat_error_msg = __('Could not create a report.<br/>An unknown error occurred', 'sat');
        } else {
            if (isset($sat_report->msg)) {
                $sat_error = TRUE;
                $sat_error_msg = $sat_report->msg . '<br/>';
            } else {
                $urlViewReporteRanker = sat_addqueryonurl(sat_getfrontendurl(), 'action=report&subaction=' . $sat_report->id);
            }
        }
    }
}
