<?php
global $sat_error, $sat_error_msg, $sat_accountinfo, $sat_googleplusreviewrequest_data;
require_once 'googleplusreviewrequest-init.php';
?>
<script>
    jQuery(document).ready(function () {
        jQuery(".sat_page, .sat_widget").tooltip();
    });
</script>

<div class="wrap">   
    <div class="sat_page sat_page_localrankchecker">        
        <div class="widget sat_widget">
            <div class="widget-top sat_nomovecursor">
                <div class="widget-title">                    
                    <h4><?php _e('Google+ Review Request PDF Generator', 'sat'); ?> <span class="in-widget-title"></span>
                        <a style="font-size: 12px; font-weight: normal;" class="sat_floatrigth sat_mariginleft" href="http://www.georanker.com/reports" target="_blank"><?php _e('Latest Reports', 'sat'); ?> </a>
                    </h4>
                </div>
            </div>
            <div class="widget-inside sat_nopadding" style="display: block;">
                <form id="form-newtool-keyworddensity" class="sat_newreport" method="post" action="">
                    <table class="form-table sat_table sat_noborder sat_nomargin">
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg" colspan="2">
                                <table width="100%">
                                    <tr>
                                        <td class="fix-td-newtool fix-width-td-newtool">
                                            <label title="<?php _e('Insert your company name)', 'sat'); ?>" for="sat_googleplusreviewrequestt_business_name"><?php _e('Business Name', 'sat'); ?>:</label><br/>
                                            <input placeholder="Your company name" title="<?php _e('Insert your company name', 'sat'); ?>" type="text"  id="sat_googleplusreviewrequestt_business_name"  class="sat_googleplusreviewrequestt_business_name" name="sat_googleplusreviewrequestt_business_name" size="65" value="<?php echo (isset($_POST['sat_googleplusreviewrequestt_business_name']) ? $_POST['sat_googleplusreviewrequestt_business_name'] : ''); ?>"/> 
                                        </td>
                                        <td class="fix-td-newtool">
                                            <label title="<?php _e('Insert a valid full URL. (including http://)', 'sat'); ?>" for="sat_googleplusreviewrequest_googlelocalurl"><?php _e('Google Local URL', 'sat'); ?>:</label><br/>
                                            <input placeholder="https://plus.google.com/123456789012345678901/about" title="<?php _e('Insert a valid full URL. (including http://)', 'sat'); ?>" type="text" placeholder="<?php _e('http://www.yourcompany.com', 'sat'); ?>" id="sat_googleplusreviewrequest_googlelocalurl"  class="sat_googleplusreviewrequest_googlelocalurl" name="sat_googleplusreviewrequest_googlelocalurl" size="65" value="<?php echo (isset($_POST['sat_googleplusreviewrequest_googlelocalurl']) ? $_POST['sat_googleplusreviewrequest_googlelocalurl'] : ''); ?>"/> 
                                        </td>
                                        <td class="fix-td-newtool fix-width-td-newtool">
                                            <br/>
                                            <label class="submit_default_tools"><input type="submit" name="submit" class="button-primary buttom_submit_tools" value="<?php _e('Download PDF', 'sat') ?>"/> </label>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>                        
                    </table>              
                </form>                 
            </div>
        </div>
    </div>
</div>

