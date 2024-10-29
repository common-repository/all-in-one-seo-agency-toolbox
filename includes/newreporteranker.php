<?php global $sat_error, $sat_error_msg,$sat_accountinfo; ?>
<div class="wrap">   
    <div class="sat_page sat_page_localrankchecker">        
        <div class="widget sat_widget">
            <div class="widget-top sat_nomovecursor">
                <div class="widget-title">                    
                    <h4><?php _e('eRanker Report', 'sat'); ?> <span class="in-widget-title"></span>
                        <a style="font-size: 12px; font-weight: normal;" class="sat_floatrigth sat_mariginleft" href="http://www.georanker.com/reports" target="_blank"><?php _e('Latest Reports', 'sat'); ?> </a>
                    </h4>
                </div>
            </div>
            <div class="widget-inside sat_nopadding" style="display: block;">
              <?PHP 
                global $sat_subaction;
                $sat_subaction = 'ranktracker';
                include 'erankerreportform.php';                
                if ($sat_error) {
                    echo '<div id="sat_error-modal" title="'. __('An error occurred','sat').'">';
                    echo '<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>';
                    echo $sat_error_msg;
                    echo '</p>';
                    echo '</div>';
                }
                ?> 
            </div>
        </div>
    </div>
</div>