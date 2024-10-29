<?PHP
global $sat_accountinfo, $sat_subaction, $sat_reporttypeobj, $urlViewReporteRanker,$sat_reportobj;

require_once 'erankerreportform-init.php';
if (empty($urlViewReporteRanker)) {
    ?>
    <script>var sat_flagfolder = "<?PHP echo plugins_url(SAT_FOLDERNAME . '/images/flags/16/') ?>";</script>
    <form class="sat_newreport" method="post" action="">
        <table class="form-table sat_table sat_noborder sat_nomargin">
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg" colspan="2">
                    <label title="<?php _e('Insert the url that will be searched. You can add a domain, subdomain or full URL. Ex: mycompany.com.', 'sat'); ?>" for="sat_newreporteranker_url"><?php _e('URL', 'sat'); ?>:</label><br/>
                    <input title="<?php _e('Insert the url that will be searched. You can add a domain, subdomain or full URL. Ex: mycompany.com.', 'sat'); ?>" type="text" placeholder="<?php _e('www.yourcompany.com', 'sat'); ?>" id="sat_newreporteranker_url" name="sat_newreporteranker_url" size="65" value="<?php echo (isset($_POST['sat_newreporteranker_url']) ? htmlspecialchars($_POST['sat_newreporteranker_url']) : ''); ?>" />
                </td>
            </tr> 
        </table>
        <div class='sat_div_advanced' onclick="jQuery('#sat_advancedmode_table').toggle();">        
            <span class="sat_arrow-down"></span>Advanced
        </div>
        <table class="form-table sat_table sat_noborder sat_nomargin sat_display_none" id='sat_advancedmode_table'>  
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg" colspan="2">
                    <label title="<?php _e('Number Phone', 'sat'); ?>" for="sat_newreporteranker_phone"><?php _e('Phone', 'sat'); ?>:</label><br/>
                    <input title="<?php _e('Number Phone', 'sat'); ?>" type="text" placeholder="<?php _e('Phone', 'sat'); ?>" id="sat_newreporteranker_phone" name="sat_newreporteranker_phone" size="65" value="<?php echo (isset($_POST['sat_newreporteranker_phone']) ? htmlspecialchars($_POST['sat_newreporteranker_phone']) : ''); ?>" />
                </td>
            </tr>
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg" colspan="2">
                    <label title="<?php _e('Company Name', 'sat'); ?>" for="sat_newreporteranker_company"><?php _e('Company Name', 'sat'); ?>:</label><br/>
                    <input title="<?php _e('Company Name', 'sat'); ?>" type="text" placeholder="<?php _e('Company Name', 'sat'); ?>" id="sat_newreporteranker_company" name="sat_newreporteranker_company" size="65" value="<?php echo (isset($_POST['sat_newreporteranker_company']) ? htmlspecialchars($_POST['sat_newreporteranker_company']) : ''); ?>" />
                </td>
            </tr>
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg" colspan="2">
                    <label title="<?php _e('Select the country where you want to do the searches.', 'sat'); ?>"  for="sat_newreport_countries"><?php _e('Country', 'sat'); ?>:</label><br/>
                    <select  <?php echo!((!isset($_POST['sat_is_global']) || (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 0) )) ? 'style="display:none"' : '' ?> onchange="jQuery('.sat_customcitieslist').val(jQuery(this).children('option:selected').data('cities'));
                                sat_updatecitylist(jQuery('.sat_maxcities'), jQuery('.sat_customcitieslist'), jQuery('.sat_citieslist'), jQuery('.sat_countriesselectbox').children('option:selected').val());" title="<?php _e('Select the country where you want to do the searches. All cities should be in the selected country.', 'sat'); ?>"  id="sat_newreport_countries" class="sat_countriesselectbox" name="sat_countryselect"  >  
                        <option data-cities="" value=''><?php _e('-- Select a Country --', 'sat'); ?></option>
                        <?PHP
                        foreach ($sat_countrylist as $countrydata) {
                            if ($countrydata->is_active) {
                                echo '<option data-cities="' . (!empty($countrydata->topcities) ? htmlentities(implode(';', (array) $countrydata->topcities), ENT_QUOTES) : '') . '" data-name="' . (!empty($countrydata->name) ? htmlentities($countrydata->name, ENT_QUOTES) : '') . '" data-code="' . (!empty($countrydata->code) ? htmlentities($countrydata->code, ENT_QUOTES) : '') . '" style="background: url(\'' . plugins_url(SAT_FOLDERNAME . '/images/flags/16/' . trim(strtoupper($countrydata->code)) . '.png') . '\') no-repeat !important; padding-left: 20px;" value="' . htmlentities(strtoupper($countrydata->code)) . '">' . htmlentities($countrydata->name) . '</option>';
                            }
                        }
                        ?>
                    </select>                                 
                </td>
            </tr>
            <tr class="row_even sat_bglgray sat_customcitytr">
                <td class="sat_nobg">
                    <label title="<?php _e('Add a custom city, region or address  (must be in the selected country).', 'sat'); ?>" for="sat_newreport_city"><?php _e('Cities', 'sat'); ?>:</label><br/>
                    <div class="sat_error sat_hidden sat_citynotfoundmsg" >
                        <?PHP _e('<strong>City name not Found!</strong><br/>Please check the name of the city or enter a full address in the selected country.', 'sat') ?>
                    </div>
                    <input placeholder="<?php _e('Custom city (or zipcode if in the US)', 'sat'); ?>"  class="sat_actualcity" type="text" name="sat_actualcity" title="<?php _e('Add a custom city, region or address  (must be in the selected country).', 'sat'); ?>">
                    <div class="sat_actualcityaddbt button-primary" onclick="sat_addcustomcity(jQuery('.sat_citynotfoundmsg'), jQuery('.sat_actualcity'), jQuery('.sat_customcitieslist'), jQuery('.sat_countriesselectbox'), jQuery('.sat_maxcities'), jQuery('.sat_citieslist'));">
                        <?php _e("Add City", 'sat') ?>
                    </div>
                    <hr class="sat_graydashed" />
                    <input onchange="sat_updatecitylist(jQuery('.sat_maxcities'), jQuery('.sat_customcitieslist'), jQuery('.sat_citieslist'), jQuery('.sat_countriesselectbox').children('option:selected').val());" type="hidden" value="<?php echo (isset($_POST['sat_customcitieslist']) ? strip_tags($_POST['sat_customcitieslist']) : '') ?>" name="sat_customcitieslist" class="sat_customcitieslist"/>
                    <ul class="sat_citieslist"></ul>
                </td>
            </tr>        

            <tr class="row_even sat_bglgray">
                <td class="sat_nobg"  colspan="2">
                    <label for="sat_sendreportviaemailcb" title="<?php _e('Enter a list of comma separated email addresses. An email with a link to the report created for each recipient will be sent. Example: user1@company.com, user2@company.com', 'sat'); ?>" ><input onchange="jQuery('.sat_sendreportviaemailemail').toggle(this.checked);" type="checkbox" name="sat_sendreportviaemailcb" id="sat_sendreportviaemailcb" value="0" <?php echo ((isset($_POST['sat_sendreportviaemailcb']) && $_POST['sat_sendreportviaemailcb'] == 1) ) ? 'checked="checked"' : '' ?>> <?php _e('Send this report by email.', 'sat'); ?></label> <br>
                    <input <?php echo ((isset($_POST['sat_sendreportviaemailcb']) && $_POST['sat_sendreportviaemailcb'] == 1) ) ? '' : ' style="display:none" ' ?> title="<?php _e('Enter a list of comma separated email addresses. An email with a link to the report created for each recipient will be sent. Example: user1@company.com, user2@company.com', 'sat'); ?>" type="text" placeholder="<?php _e('user1@company.com, user2@company.com', 'sat'); ?>" class="sat_sendreportviaemailemail" name="sat_sendreportviaemailemail" size="65" value="<?php echo (isset($_POST['sat_sendreportviaemailemail']) ? htmlspecialchars($_POST['sat_sendreportviaemailemail']) : ''); ?>" />
                </td>
            </tr>
            <input type="hidden" class="sat_maxcities"name="sat_maxcities" value="1" />
        </table>
        <div class="sat_padded">
            <input type="submit" name="submit" class="button-primary" value="Create Report">
        </div>
    </form>

    <?php
} else {
    $redirect = sat_redirectviewreportpage($urlViewReporteRanker);   
    echo $redirect;
}
?>
