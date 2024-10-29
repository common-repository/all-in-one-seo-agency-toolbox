<?php 
require_once( dirname(__FILE__) . '/../../../../wp-blog-header.php' );
require_once( dirname(__FILE__) . '/../includes/SATGeoRankerAPI.class.php' );

$sat_subaction	= $_GET['id'];
$grapi          = new SATGeoRankerAPI( get_option('sat_settings')['email'], get_option('sat_settings')['apikey'] );
$loginobj       = $grapi->login();
$sat_reportobj	= $grapi->reportget( $sat_subaction);

echo $sat_reportobj->is_pending;
?>

    