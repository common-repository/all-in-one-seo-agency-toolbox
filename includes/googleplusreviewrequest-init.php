<?php

global $sat_settings, $sat_error, $sat_error_msg, $sat_accountinfo, $sat_report, $sat_googleplusreviewrequest_data;

$sat_googleplusreviewrequest_data = '';

$sat_error = FALSE;
//Conect to the API and test the apikey
$grapi = new SATGeoRankerAPI($sat_settings['email'], $sat_settings['apikey']);

if ((isset($_POST['sat_googleplusreviewrequest_googlelocalurl']) && empty($_POST['sat_googleplusreviewrequest_googlelocalurl'])) || isset($_POST['sat_googleplusreviewrequest_googlelocalurl']) && is_numeric($_POST['sat_googleplusreviewrequest_googlelocalurl'])) {
    $sat_error = TRUE;
    $sat_error_msg = __("You must specify a valid URL or domain in reports of the type 'Keyword Density'.<br/>A URL must have at least 5 caracters and a dot (.).", 'sat');
}

if ((isset($_POST['sat_googleplusreviewrequest_googlelocalurl']) && !empty($_POST['sat_googleplusreviewrequest_googlelocalurl']) && !is_numeric($_POST['sat_googleplusreviewrequest_googlelocalurl'])) && isset($_POST['sat_googleplusreviewrequestt_business_name']) && !empty($_POST['sat_googleplusreviewrequestt_business_name'])) {
    $sat_googleplusreviewrequest_googlelocalurl = $_POST['sat_googleplusreviewrequest_googlelocalurl'];
    $sat_googleplusreviewrequestt_business_name = isset($_POST['sat_googleplusreviewrequestt_business_name']) && !is_numeric($_POST['sat_googleplusreviewrequestt_business_name']) ? $_POST['sat_googleplusreviewrequestt_business_name'] : 4;

    if ($sat_error === FALSE) {
        $callback = null;
        $sat_keywords = null;
        $sat_reportobj = $grapi->googleplusreviewrequest($sat_googleplusreviewrequest_googlelocalurl, $sat_googleplusreviewrequestt_business_name);
        if ($sat_reportobj === FALSE) {
            $sat_error = TRUE;
            $sat_error_msg = __('Could not create the PDF.<br/>An unknown error occurred.', 'sat');
        } else {
            if (isset($sat_reportobj->msg)) {
                $sat_error = TRUE;
                $sat_error_msg = $sat_report->msg . '<br/>' . $sat_reportobj->solution;
            } else {
                header('Content-Type: application/pdf');
                header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                header('Pragma: public');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Content-Length: ' . strlen($sat_reportobj));
                header('Content-Type: application/force-download');
                header('Content-Type: application/octet-stream', false);
                header('Content-Type: application/download', false);
                header('Content-Type: image/jpeg', false);
                header('Content-Disposition: attachment; filename="reviewrequest.pdf";');
                header('Content-Transfer-Encoding: binary');
                echo $sat_reportobj;
                exit;
            }
        }
    }
}
