<?php
global $sat_error, $sat_error_msg, $sat_accountinfo, $sat_keyworddensity_data;
require_once 'keyworddensity-init.php';


// <script>
//     $(function () {
//             $("#sat_keyworddensity_minwordlength").spinner();
//         });
//     $(function () {
//         $("#sat_keyworddensity_maxwordlength").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightforkeywordsonbody").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightforh1h2h3").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightfortitle").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightforlinkstext").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightforlinkstitle").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightformetatitle").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightformetakeywords").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightformetadescription").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_doublekeywordweightmultiplier").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_triplekeywordweightmultiplier").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_quadrupleormorekeywordweightmultiplier").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_weightforimagealt").spinner();
//     });
//     $(function () {
//         $("#sat_keyworddensity_mindensitytobeused").spinner();
//     });
//     jQuery(document).ready(function () {
//         jQuery(".sat_page, .sat_widget").tooltip();
//      });    
//     </script>

?>


<div class="wrap">   
    <div class="sat_page sat_page_localrankchecker">        
        <div class="widget sat_widget">
            <div class="widget-top sat_nomovecursor">
                <div class="widget-title">                    
                    <h4><?php _e('Keyword Density', 'sat'); ?> <span class="in-widget-title"></span>
                        <a style="font-size: 12px; font-weight: normal;" class="sat_floatrigth sat_mariginleft" href="http://www.georanker.com/reports" target="_blank"><?php _e('Latest Reports', 'sat'); ?>  </a>
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
                                            <label title="<?php _e('Insert a valid full URL. (including http://)', 'sat'); ?>" for="sat_keyworddensity_url" class="sat_keyworddensity_url_label"><?php _e('URL', 'sat'); ?>:</label>
                                        </td>
                                        <td class="fix-td-newtool">
                                            <input title="<?php _e('Insert a valid full URL. (including http://)', 'sat'); ?>" type="text" placeholder="<?php _e('http://www.yourcompany.com', 'sat'); ?>" id="sat_keyworddensity_url"  class="sat_keyworddensity_url" name="sat_keyworddensity_url" size="65" value="<?php echo (isset($_POST['sat_keyworddensity_url']) ? $_POST['sat_keyworddensity_url'] : ''); ?>"/> 
                                        </td>
                                        <td class="fix-td-newtool fix-width-td-newtool">
                                            <label class="submit_default_tools"><input type="submit" name="submit" class="buttom_submit_tools button-primary" value="<?php _e('Analysis', 'sat') ?>"/> </label>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>
                        <tr class="row_even sat_bglgray sat_div_advanced" onclick='jQuery("#sat_advancedmode_table").slideToggle("slow");'>
                            <td class="sat_nobg" colspan="2">
                                <span class="sat_arrow-down"></span>Advanced
                            </td>
                        </tr>
                    </table>                        
                    <table class="form-table sat_table sat_noborder sat_nomargin sat_display_none" id='sat_advancedmode_table'>  
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg" colspan="2">
                                <div>
                                    <h4 class="width-h4-tools">Word Length</h4>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Minimum number of characters a word can have. This is not valid for the center keyword on the triple word keywords.', 'sat'); ?>" for="sat_keyworddensity_minwordlength"><?php _e('Min', 'sat'); ?>:</label><br/>
                                        <input  title="<?php _e('Minimum number of characters a word can have. This is not valid for the center keyword on the triple word keywords.', 'sat'); ?>" type="text" placeholder="<?php _e('Minimum number of characters a word can have. This is not valid for the center keyword on the triple word keywords.', 'sat'); ?>" id="sat_keyworddensity_minwordlength" class="sat_keyworddensity_minwordlength" name="sat_keyworddensity_minwordlength"  value="<?php echo (isset($_POST['sat_keyworddensity_minwordlength']) ? $_POST['sat_keyworddensity_minwordlength'] : '4'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Maximum number of characters a word can have.', 'sat'); ?>" for="sat_keyworddensity_maxwordlength"><?php _e('Max', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Maximum number of characters a word can have.', 'sat'); ?>" type="text" placeholder="<?php _e('Maximum number of characters a word can have.', 'sat'); ?>" id="sat_keyworddensity_maxwordlength" class="sat_keyworddensity_maxwordlength" name="sat_keyworddensity_maxwordlength"  value="<?php echo (isset($_POST['sat_keyworddensity_maxwordlength']) ? $_POST['sat_keyworddensity_maxwordlength'] : '20'); ?>" />
                                    </label>
                                </div>                                
                            </td>
                        </tr>
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg" colspan="2">
                                <div>
                                    <h4 class="width-h4-tools">Weight</h4>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightforkeywordsonbody"><?php _e('Keywords on body', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightforkeywordsonbody" class="sat_keyworddensity_weightforkeywordsonbody" name="sat_keyworddensity_weightforkeywordsonbody"  value="<?php echo (isset($_POST['sat_keyworddensity_weightforkeywordsonbody']) ? $_POST['sat_keyworddensity_weightforkeywordsonbody'] : '1'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightforh1h2h3"><?php _e('Headings', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightforh1h2h3" class="sat_keyworddensity_weightforh1h2h3" name="sat_keyworddensity_weightforh1h2h3"  value="<?php echo (isset($_POST['sat_keyworddensity_weightforh1h2h3']) ? $_POST['sat_keyworddensity_weightforh1h2h3'] : '10'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightfortitle"><?php _e('Title', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightfortitle" class="sat_keyworddensity_weightfortitle" name="sat_keyworddensity_weightfortitle"  value="<?php echo (isset($_POST['sat_keyworddensity_weightfortitle']) ? $_POST['sat_keyworddensity_weightfortitle'] : '5'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightforlinkstext"><?php _e('Links text', 'sat'); ?>:</label><br/>
                                        <input  title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightforlinkstext" class="sat_keyworddensity_weightforlinkstext" name="sat_keyworddensity_weightforlinkstext"  value="<?php echo (isset($_POST['sat_keyworddensity_weightforlinkstext']) ? $_POST['sat_keyworddensity_weightforlinkstext'] : '1'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightforlinkstitle"><?php _e('Links title', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightforlinkstitle" class="sat_keyworddensity_weightforlinkstitle" name="sat_keyworddensity_weightforlinkstitle"  value="<?php echo (isset($_POST['sat_keyworddensity_weightforlinkstitle']) ? $_POST['sat_keyworddensity_weightforlinkstitle'] : '1'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightformetatitle"><?php _e('Meta title', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightformetatitle" class="sat_keyworddensity_weightforlinkstitle" name="sat_keyworddensity_weightformetatitle"  value="<?php echo (isset($_POST['sat_keyworddensity_weightformetatitle']) ? $_POST['sat_keyworddensity_weightformetatitle'] : '1'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightformetakeywords"><?php _e('Meta keywords', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightformetakeywords" class="sat_keyworddensity_weightformetakeywords" name="sat_keyworddensity_weightformetakeywords"  value="<?php echo (isset($_POST['sat_keyworddensity_weightformetakeywords']) ? $_POST['sat_keyworddensity_weightformetakeywords'] : '5'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightformetadescription"><?php _e('Meta description', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightformetadescription" class="sat_keyworddensity_weightformetadescription" name="sat_keyworddensity_weightformetadescription"  value="<?php echo (isset($_POST['sat_keyworddensity_weightformetadescription']) ? $_POST['sat_keyworddensity_weightformetadescription'] : '10'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" for="sat_keyworddensity_weightforimagealt"><?php _e('Image alt', 'sat'); ?>:</label><br/>
                                        <input title="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" type="text" placeholder="<?php _e('Insert a number. insert 0 to not use this keywords on the weight calculations.', 'sat'); ?>" id="sat_keyworddensity_weightforimagealt" class="sat_keyworddensity_weightforimagealt" name="sat_keyworddensity_weightforimagealt"  value="<?php echo (isset($_POST['sat_keyworddensity_weightforimagealt']) ? $_POST['sat_keyworddensity_weightforimagealt'] : '1'); ?>" />
                                    </label>
                                </div>     
                            </td>
                        </tr>                        
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg tdwidthspinner" colspan="2">
                                <div>
                                    <h4 class="width-h4-tools">Weight Multiplier</h4>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="0 for ignore 2 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." for="sat_keyworddensity_doublekeywordweightmultiplier"><?php _e('Double keyword', 'sat'); ?>:</label><br/>
                                        <input title="0 for ignore 2 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." type="text" placeholder="0 for ignore 2 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." id="sat_keyworddensity_doublekeywordweightmultiplier" class="sat_keyworddensity_doublekeywordweightmultiplier" name="sat_keyworddensity_doublekeywordweightmultiplier"  value="<?php echo (isset($_POST['sat_keyworddensity_doublekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_doublekeywordweightmultiplier'] : '10'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="0 for ignore 3 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." for="sat_keyworddensity_triplekeywordweightmultiplier"><?php _e('Triple keyword', 'sat'); ?>:</label><br/>
                                        <input title="0 for ignore 3 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." type="text" placeholder="0 for ignore 3 words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." id="sat_keyworddensity_triplekeywordweightmultiplier" class="sat_keyworddensity_triplekeywordweightmultiplier" name="sat_keyworddensity_triplekeywordweightmultiplier"  value="<?php echo (isset($_POST['sat_keyworddensity_triplekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_triplekeywordweightmultiplier'] : '15'); ?>" />
                                    </label>
                                    <label class="block-adjust tdwidthspinner">
                                        <label title="0 for ignore 4+ words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." for="sat_keyworddensity_quadrupleormorekeywordweightmultiplier"><?php _e('Quadruple (or more) keyword', 'sat'); ?>:</label><br/>
                                        <input title="0 for ignore 4+ words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." type="text" placeholder="0 for ignore 4+ words keywords; 1 to not touch the weight; &gt; 1 to make it have a bonus weight." id="sat_keyworddensity_quadrupleormorekeywordweightmultiplier" class="sat_keyworddensity_quadrupleormorekeywordweightmultiplier" name="sat_keyworddensity_quadrupleormorekeywordweightmultiplier"  value="<?php echo (isset($_POST['sat_keyworddensity_quadrupleormorekeywordweightmultiplier']) ? $_POST['sat_keyworddensity_quadrupleormorekeywordweightmultiplier'] : '20'); ?>" />
                                    </label>
                                </div>
                            </td>
                        </tr>                        
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg" colspan="2">
                                <label title="<?php _e('Possible values: "none", "normal" and "extended". Extended is the best one but increases the processing time.', 'sat'); ?>"  for="sat_keyworddensity_stopwordmode"><?php _e('Stop Word Mode', 'sat'); ?>:</label><br/>
                                <select title="<?php _e('Possible values: "none", "normal" and "extended". Extended is the best one but increases the processing time.', 'sat'); ?>"  id="sat_keyworddensity_stopwordmode" class="sat_keyworddensity_stopwordmode" name="sat_keyworddensity_stopwordmode"  >  
                                    <option value="none">None</option>
                                    <option selected="selected" value="normal">Normal</option>
                                    <option value="extended">Extended</option>                                    
                                </select>                                 
                            </td>
                        </tr>                            
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg" colspan="2">
                                <label title="<?php _e('Possible values: "none", "normal" and "extended". Extended is the best one but increases the processing time.', 'sat'); ?>"  for="sat_keyworddensity_ignorebody"><?php _e('Ignore Body', 'sat'); ?>:</label><br/>
                                <select title="<?php _e('Possible values: "none", "normal" and "extended". Extended is the best one but increases the processing time.', 'sat'); ?>"  id="sat_keyworddensity_ignorebody" class="sat_keyworddensity_ignorebody" name="sat_keyworddensity_ignorebody"  >  
                                    <option value="1">Yes</option>
                                    <option selected="selected" value="0">No</option>                                                                            
                                </select>                                 
                            </td>
                        </tr>                            
                        <tr class="row_even sat_bglgray">
                            <td class="sat_nobg tdwidthspinner" colspan="2">
                                <label title="<?php _e('This affect the "Keywords that will be probably used". If the density of the keywords is below the number, the keyword will not be used.', 'sat'); ?>" for="sat_keyworddensity_mindensitytobeused"><?php _e('Min Density to be used', 'sat'); ?>:</label><br/>
                                <input title="<?php _e('This affect the "Keywords that will be probably used". If the density of the keywords is below the number, the keyword will not be used.', 'sat'); ?>" type="text" placeholder="<?php _e('This affect the "Keywords that will be probably used". If the density of the keywords is below the number, the keyword will not be used.', 'sat'); ?>" id="sat_keyworddensity_mindensitytobeused" class="sat_keyworddensity_mindensitytobeused" name="sat_keyworddensity_mindensitytobeused"  value="<?php echo (isset($_POST['sat_keyworddensity_mindensitytobeused']) ? $_POST['sat_keyworddensity_mindensitytobeused'] : '2'); ?>" />
                            </td>
                        </tr>
                    </table>                        
                </form> 

                <?PHP
                global $sat_subaction;
                $sat_subaction = 'keyworddensity';
                if ($sat_error) {
                    echo '<div id="sat_error-modal" title="' . __('An error occurred', 'sat') . '">';
                    echo $sat_error_msg;
                    echo '</div>';
                }
                ?> 
                <?php if (!empty($sat_keyworddensity_data)) { ?>
                    <div class="">
                        <?php require(dirname(dirname(__FILE__)) . "/views/keyworddensity.php"); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
