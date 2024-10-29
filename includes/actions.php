<?php

//function sat_act_sitereport() {
//    global $sat_subaction, $sat_settings, $sat_reportobj, $sat_reportcachetime, $sat_nocache;
//
//    if (empty($sat_subaction) || strcmp($sat_subaction, "view") != 0) {
//        set_echoerror(404, __('Variable subaction empty.', 'sat'));
//        return;
//    }
//
//    $sat_newreporteranker_id = isset($_GET['report']) ? $_GET['report'] : "";
//
//    $sat_reportobj = get_transient('sat_sitereport_id_' . md5($sat_newreporteranker_url));
//    if (empty($sat_reportobj) || $sat_nocache) {
//        $grapi = new SATGeoRankerAPI($sat_settings['email'], $sat_settings['apikey']);
//        $loginobj = $grapi->login();
//        if (empty($loginobj) || isset($loginobj->debug)) {
//            set_echoerror(503, (isset($loginobj->msg) ? $loginobj->msg : __('Error on login object.', 'sat')));
//            return;
//        } 
//
//        $sat_reportobj = $grapi->sitereport($sat_newreporteranker_id);
//        if (empty($sat_reportobj) || isset($sat_reportobj->debug)) {
//            set_echoerror(404, (isset($sat_reportobj->msg) ? $sat_reportobj->msg : __('Error on report object.', 'sat')));
//            return;
//        }
//        set_transient('sat_sitereport_id_' . md5($sat_newreporteranker_url), $sat_reportobj, $sat_reportcachetime);
//    }
//
//    //print_r($sat_reportobj);  
//    require(dirname(dirname(__FILE__)) . "/views/erankerreport.php");
//}
global $wpdb;

function sat_act_report() {
    global $sat_subaction, $sat_settings, $sat_reportobj, $sat_reporttypeobj, $sat_reportcachetime, $sat_nocache;
    if (empty($sat_subaction)) {
        set_echoerror(404, __('Variable subaction empty.', 'sat'));
        return;
    }
    $sat_reportobj = get_transient('sat_report_id_' . ( $sat_subaction));
    if (empty($sat_reportobj) || $sat_nocache) {
        $grapi = new SATGeoRankerAPI($sat_settings['email'], $sat_settings['apikey']);
        $loginobj = $grapi->login();
        if (empty($loginobj) || isset($loginobj->debug)) {
            set_echoerror(503, (isset($loginobj->msg) ? $loginobj->msg : __('Error on login object.', 'sat')));
            return;
        }

        $sat_reportobj = $grapi->reportget($sat_subaction);
        $sat_reportobj;
        if (empty($sat_reportobj) || isset($sat_reportobj->debug)) {
            set_echoerror(404, (isset($sat_reportobj->msg) ? $sat_reportobj->msg : __('Error on report object object.', 'sat')));
            return;
        }
        if (empty($sat_reportobj->is_pending)) {
            set_transient('sat_report_id_' . ( $sat_subaction), $sat_reportobj, $sat_reportcachetime);
        }
    }

    if (!empty($sat_reportobj->type)) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sat_reports';		
				$user_id = wp_get_current_user();
			$test = $wpdb->insert($table_name, array('token' => $sat_reportobj->token, 'date' => date('Y-m-d H:i'), 'ip' => $_SERVER["REMOTE_ADDR"], 'user_id'=> $user_id->ID), array('%s','%s','%s','%d'));

        if ($sat_settings['is_redirect_ongeoranker'] === false) {
            if (empty($sat_reportobj->is_pending)) {
                $sat_reporttypeobj = get_transient('sat_report_id_' . ($sat_subaction) . trim(strtolower($sat_reportobj->type)));
                if (empty($sat_reporttypeobj) || $sat_nocache) {
                    $sat_reporttypeobj = $grapi->reportget($sat_subaction, $sat_reportobj->type);
                    if (empty($sat_reporttypeobj) || isset($sat_reporttypeobj->debug)) {
                        set_echoerror(404, (isset($sat_reporttypeobj->msg) ? $sat_reporttypeobj->msg : __('Error on type report object.', 'sat')));
                        return;
                    }
                    set_transient('sat_report_id_' . ( $sat_subaction) . trim(strtolower($sat_reportobj->type)), $sat_reporttypeobj, $sat_reportcachetime);
                }
                require(dirname(dirname(__FILE__)) . "/views/" . trim(strtolower($sat_reportobj->type) . ".php"));
            } else {
                require(dirname(dirname(__FILE__)) . "/views/pending.php");
            }
        } else {
            $ipCurrentUser = $_SERVER["REMOTE_ADDR"];
            $countCreatedREport = 0;
				$table_name = $wpdb->prefix . 'sat_reports';
				$user_id = wp_get_current_user();
			$wpdb->insert($table_name, array('token' => $sat_reportobj->token, 'date' => $sat_reportobj->date_created, 'ip' => $_SERVER["REMOTE_ADDR"], 'user_id'=> $user_id->ID), array('%s','%s','%s','%d'));
            
            if(!empty($sat_settings['is_redirect_ongeoranker']) && isset($sat_settings['is_redirect_ongeoranker']) && $countCreatedREport <= $sat_settings['is_redirect_ongeoranker']){
               $ipCurrentUserSave = $_SERVER["REMOTE_ADDR"]; 
            }
            header("location:http://www.georanker.com/$sat_reportobj->type/$sat_reportobj->token");
            exit;
        }
    }
}

function sat_act_home() {
    echo "HOME";
}
