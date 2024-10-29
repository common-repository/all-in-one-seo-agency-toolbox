<?php

global $sat_settings, $sat_error, $sat_error_msg, $sat_accountinfo, $sat_report, $sat_keyworddensity_data,$grapi;

$sat_keyworddensity_data = '';

$sat_error = FALSE;
//Conect to the API and test the apikey
$grapi = new SATGeoRankerAPI($sat_settings['email'], $sat_settings['apikey']);

if ((isset($_POST['sat_keyworddensity_url']) && empty($_POST['sat_keyworddensity_url'])) || isset($_POST['sat_keyworddensity_url']) && is_numeric($_POST['sat_keyworddensity_url'])) {
    $sat_error = TRUE;
    $sat_error_msg = __("You must specify a valid URL or domain in reports of the type 'Keyword Density'.<br/>A URL must have at least 5 caracters and a dot (.).", 'sat');
}

if (isset($_POST['sat_keyworddensity_url']) && !empty($_POST['sat_keyworddensity_url']) && !is_numeric($_POST['sat_keyworddensity_url'])) {

    $sat_keyworddensity_url = $_POST['sat_keyworddensity_url'];
    $sat_keyworddensity_minwordlength = isset($_POST['sat_keyworddensity_minwordlength']) && !is_numeric($_POST['sat_keyworddensity_minwordlength']) ? $_POST['sat_keyworddensity_minwordlength'] : 4;
    $sat_keyworddensity_ignorebody = isset($_POST['sat_keyworddensity_ignorebody']) && $_POST['sat_keyworddensity_ignorebody'] == 1 ? TRUE : FALSE;
    $sat_keyworddensity_maxwordlength = isset($_POST['sat_keyworddensity_maxwordlength']) && !empty($_POST['sat_keyworddensity_maxwordlength']) ? $_POST['sat_keyworddensity_maxwordlength'] : 20;
    $sat_keyworddensity_weightforkeywordsonbody = isset($_POST['sat_keyworddensity_weightforkeywordsonbody']) && !is_numeric($_POST['sat_keyworddensity_weightforkeywordsonbody']) ? $_POST['sat_keyworddensity_weightforkeywordsonbody'] : 1;
    $sat_keyworddensity_weightforh1h2h3 = isset($_POST['sat_keyworddensity_weightforh1h2h3']) && !is_numeric($_POST['sat_keyworddensity_weightforh1h2h3']) ? $_POST['sat_keyworddensity_weightforh1h2h3'] : 10;
    $sat_keyworddensity_weightfortitle = isset($_POST['sat_keyworddensity_weightfortitle']) && !is_numeric($_POST['sat_keyworddensity_weightfortitle']) ? $_POST['sat_keyworddensity_weightfortitle'] : 5;
    $sat_keyworddensity_weightforlinkstext = isset($_POST['sat_keyworddensity_weightforlinkstext']) && !is_numeric($_POST['sat_keyworddensity_weightforlinkstext']) ? $_POST['sat_keyworddensity_weightforlinkstext'] : 1;
    $sat_keyworddensity_weightforlinkstitle = isset($_POST['sat_keyworddensity_weightforlinkstitle']) && !is_numeric($_POST['sat_keyworddensity_weightforlinkstitle']) ? $_POST['sat_keyworddensity_weightforlinkstitle'] : 1;
    $sat_keyworddensity_weightformetatitle = isset($_POST['sat_keyworddensity_weightformetatitle']) && !is_numeric($_POST['sat_keyworddensity_weightformetatitle']) ? $_POST['sat_keyworddensity_weightformetatitle'] : 1;
    $sat_keyworddensity_weightformetakeywords = isset($_POST['sat_keyworddensity_weightformetakeywords']) && !is_numeric($_POST['sat_keyworddensity_weightformetakeywords']) ? $_POST['sat_keyworddensity_weightformetakeywords'] : 5;
    $sat_keyworddensity_weightformetadescription = isset($_POST['sat_keyworddensity_weightformetadescription']) && !is_numeric($_POST['sat_keyworddensity_weightformetadescription']) ? $_POST['sat_keyworddensity_weightformetadescription'] : 10;
    $sat_keyworddensity_weightforimagealt = isset($_POST['sat_keyworddensity_weightforimagealt']) && !is_numeric($_POST['sat_keyworddensity_weightforimagealt']) ? $_POST['sat_keyworddensity_weightforimagealt'] : 1;
    $sat_keyworddensity_doublekeywordweightmultiplier = isset($_POST['sat_keyworddensity_doublekeywordweightmultiplier']) && !is_numeric($_POST['sat_keyworddensity_doublekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_doublekeywordweightmultiplier'] : 10;
    $sat_keyworddensity_triplekeywordweightmultiplier = isset($_POST['sat_keyworddensity_triplekeywordweightmultiplier']) && !is_numeric($_POST['sat_keyworddensity_triplekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_triplekeywordweightmultiplier'] : 15;
    $sat_keyworddensity_quadrupleormorekeywordweightmultiplier = isset($_POST['sat_keyworddensity_quadrupleormorekeywordweightmultiplier']) && !is_numeric($_POST['sat_keyworddensity_quadrupleormorekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_quadrupleormorekeywordweightmultiplier'] : 20;
    $sat_keyworddensity_stopwordmode = isset($_POST['sat_keyworddensity_stopwordmode']) && !empty($_POST['sat_keyworddensity_stopwordmode']) ? $_POST['sat_keyworddensity_stopwordmode'] : 'normal';  
    $sat_keyworddensity_mindensitytobeused = isset($_POST['sat_keyworddensity_mindensitytobeused']) && !is_numeric($_POST['sat_keyworddensity_mindensitytobeused']) ? $_POST['sat_keyworddensity_mindensitytobeused'] : 2;

     if ($sat_error === FALSE) {
        $callback = null;
        $sat_keywords = null;
        $sat_reportobj = $grapi->keyworddensity($sat_keyworddensity_url,$sat_keyworddensity_ignorebody, $sat_keyworddensity_minwordlength, $sat_keyworddensity_maxwordlength, $sat_keyworddensity_weightforkeywordsonbody, $sat_keyworddensity_weightforh1h2h3, $sat_keyworddensity_weightfortitle, $sat_keyworddensity_weightforlinkstext, $sat_keyworddensity_weightforlinkstitle, $sat_keyworddensity_weightformetatitle,$sat_keyworddensity_weightformetakeywords, $sat_keyworddensity_weightformetadescription, $sat_keyworddensity_weightforimagealt, $sat_keyworddensity_doublekeywordweightmultiplier, $sat_keyworddensity_triplekeywordweightmultiplier,$sat_keyworddensity_quadrupleormorekeywordweightmultiplier, $sat_keyworddensity_stopwordmode, $sat_keyworddensity_mindensitytobeused);
        if (empty($sat_reportobj)) {
            $sat_error = TRUE;
            $sat_error_msg = __('Could not create a report.<br/>An unknown error occurred', 'sat');
        } else {
            if (isset($sat_reportobj->msg)) {
                $sat_error = TRUE;
                $sat_error_msg = $sat_report->msg . '<br/>' . $sat_reportobj->solution;
            } else {
                $sat_keyworddensity_data = $sat_reportobj;                
            }
        }
    }
    
}
