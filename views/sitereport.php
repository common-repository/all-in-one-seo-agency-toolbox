<?php
require_once(dirname(__FILE__) . '/../includes/erreport.lib.php');
global $sat_reporttypeobj, $sat_reportobj;
?>
<?= render_erreport($sat_reporttypeobj[0], $sat_reportobj, true); ?>
<script type="text/javascript">

    var is_processed = false;
    if (typeof is_processed !== "undefined" && !is_processed) {
        try {
            processErReport();
        } catch (e) {
        }
        jQuery(document).ready(function () {
            processErReport();
        });
    }
</script>