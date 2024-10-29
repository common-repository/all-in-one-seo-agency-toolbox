<?PHP
global $sat_accountinfo, $sat_subaction, $sat_report, $onlyonekeyword, $sat_settings, $wpdb;
require_once 'formnewreport-init.php';

//$wpdb->show_errors = true;

$user_ip = $_SERVER['REMOTE_ADDR'];

$user = wp_get_current_user();
if (!empty($user->ID)) {
    $total_reports = $wpdb->get_var($wpdb->prepare("SELECT count(*) as total FROM " . $wpdb->prefix . "sat_reports WHERE `date`>DATE_SUB(NOW(), interval 1 month) AND user_id=%d", $user->ID));
} else {
    $total_reports = $wpdb->get_var($wpdb->prepare("SELECT count(*) as total FROM " . $wpdb->prefix . "sat_reports WHERE `date`>DATE_SUB(NOW(), interval 1 month) AND ip=%s", $user_ip));
}

if (empty($user->ID) && (int) $total_reports >= (int) $sat_settings['is_limetfreereport_notlogged'] && (int) $sat_settings['is_limetfreereport_notlogged'] > 0) {
    include 'blockedpage.php';
    return;
}

if (!empty($user->ID) && (int) $total_reports >= (int) $sat_settings['is_limetfreereport_islogged'] && (int) $sat_settings['is_limetfreereport_islogged'] > 0) {
    include 'blockedusedashboard.php';
    return;
}

if (empty($urlViewReporteRanker)) {
    ?>

    <script>var sat_flagfolder = "<?PHP echo plugins_url(SAT_FOLDERNAME . '/images/flags/16/') ?>";</script>
    <form class="sat_newreport" method="post" action="">
        <table class="form-table sat_table sat_noborder sat_nomargin">
            <?php if (strcasecmp($sat_subaction, 'ranktracker') == 0) { ?>
                <tr class="row_even sat_bglgray">
                    <td class="sat_nobg" colspan="2">
                        <label title="<?php _e('Insert the url that will be searched. You can add a domain, subdomain or full URL. Ex: mycompany.com.', 'sat'); ?>" for="sat_newreport_url"><?php _e('URL', 'sat'); ?>:</label><br/>
                        <input title="<?php _e('Insert the url that will be searched. You can add a domain, subdomain or full URL. Ex: mycompany.com.', 'sat'); ?>" type="text" placeholder="<?php _e('www.yourcompany.com', 'sat'); ?>" id="sat_newreport_url" name="sat_newreport_url" size="65" value="<?php echo (isset($_POST['sat_newreport_url']) ? htmlspecialchars($_POST['sat_newreport_url']) : ''); ?>" />
                    </td>
                </tr>
            <?php } ?>
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg" colspan="2">
                    <label title="<?php _e('Add the keywords that will be used on the report.', 'sat'); ?>" for="sat_newreport_keyword"><?php _e('Keyword', 'sat'); ?>:</label><br/>
                    <input style="width: 100%" title="<?php _e('Add the keywords that will be used on the report.', 'sat'); ?>"  id="sat_newreport_keywords" class="sat_textareakeywords" name="sat_newreport_keywords" value="<?php echo (isset($_POST['sat_newreport_keywords']) ? htmlspecialchars($_POST['sat_newreport_keywords']) : ''); ?>"  placeholder="<?php _e('Add the keywords that will be used on the report.', 'sat'); ?>">
                </td>
            </tr>                       
            <tr class="row_even sat_bglgray">
                <td class="sat_nobg"  colspan="2">
                    <label for="sat_newreport_url" title="<?php _e('Determines how the report will be created.', 'sat'); ?>   <?php _e('Local report: You compare the rankings between cities in a country.', 'sat'); ?>   <?php _e('Global Report: You compare the rankings between different countries.', 'sat'); ?>"><?php _e('Coverage', 'sat'); ?>:</label><br/>
                    <label for="sat_is_global_0" title="<?php _e('Local report: You compare the rankings between cities in a country.', 'sat'); ?>" ><input onchange="jQuery('.sat_tabs.sat_tabscountries').toggle(!this.checked);
                                jQuery('.sat_countriesselectbox').toggle(this.checked);
                                jQuery('.sat_maxcities_tr').toggle(this.checked);
                                jQuery('.sat_customcitytr').toggle(this.checked);" type="radio" name="sat_is_global" id="sat_is_global_0" value="0" <?php echo (!isset($_POST['sat_is_global']) || (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 0) ) ? 'checked="checked"' : '' ?>> <?php _e('Local (by cities)', 'sat'); ?></label> &nbsp;&nbsp;&nbsp;
                    <label for="sat_is_global_1" title="<?php _e('Global Report: You compare the rankings between different countries.', 'sat'); ?>" ><input onchange="jQuery('.sat_tabs.sat_tabscountries').toggle(this.checked);
                                jQuery('.sat_countriesselectbox').toggle(!this.checked);
                                jQuery('.sat_maxcities_tr').toggle(!this.checked);
                                jQuery('.sat_customcitytr').toggle(!this.checked);"  type="radio" name="sat_is_global"  id="sat_is_global_1" value="1" <?php echo (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 1) ? 'checked="checked"' : '' ?>> <?php _e('Global (by countries)', 'sat'); ?></label>
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
                    <div class="sat_tabs sat_tabscountries" <?php echo (!isset($_POST['sat_is_global']) || (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 0) ) ? 'style="display:none"' : '' ?>> 	
                        <ul title="<?php _e('Select the country where you want to do the searches. In global reports, you must select at least two countries.', 'sat'); ?>">
                            <li><a href="#satcountriestab1"><?php _e('Americas', 'sat'); ?></a></li>
                            <li><a href="#satcountriestab2"><?php _e('Europe', 'sat'); ?></a></li>
                            <li><a href="#satcountriestab3"><?php _e('Asia', 'sat'); ?></a></li>
                            <li><a href="#satcountriestab4"><?php _e('Africa', 'sat'); ?></a></li>
                            <li><a href="#satcountriestab5"><?php _e('Oceania', 'sat'); ?></a></li>
                        </ul>
                        <div id="satcountriestab1">
                            <h4><?php _e('North America', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'North America'); ?>
                            <div class="satclearboth"></div>
                            <h4><?php _e('Central America', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'Central America'); ?>
                            <div class="satclearboth"></div>
                            <h4><?php _e('South America', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'South America'); ?>
                            <div class="satclearboth"></div>
                        </div>
                        <div id="satcountriestab2">
                            <h4><?php _e('Europe', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'Europe'); ?>
                            <div class="satclearboth"></div>
                        </div>
                        <div id="satcountriestab3">
                            <h4><?php _e('Asia', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'Asia'); ?>
                            <div class="satclearboth"></div>
                        </div>
                        <div id="satcountriestab4">
                            <h4><?php _e('Africa', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'Africa'); ?>
                            <div class="satclearboth"></div>
                        </div>
                        <div id="satcountriestab5">
                            <h4><?php _e('Oceania', 'sat'); ?></h4>
                            <?php echo sat_htmlcbforcountrybycontinent($sat_countrylist, 'Oceania'); ?>
                            <div class="satclearboth"></div>
                        </div>
                    </div>                
                </td>
            </tr>
            <tr class="row_even sat_bglgray sat_maxcities_tr"  <?php echo (!isset($_POST['sat_is_global']) || (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 0) ) ? '' : 'style="display:none"' ?>>
                <td class="sat_nobg" colspan="2">
                    <label><?php _e('Select top cities', 'sat') ?>:</label><br/>

                    <select name="sat_maxcities" class="sat_maxcities"
                            onchange="jQuery('.sat_customcitieslist').val(jQuery('.sat_countriesselectbox').children('option:selected').data('cities'));
                                        sat_updatecitylist(jQuery('.sat_maxcities'), jQuery('.sat_customcitieslist'), jQuery('.sat_citieslist'), jQuery('.sat_countriesselectbox').children('option:selected').val());">
                            <?PHP
                                if (isset($_POST['sat_maxcities']) && $_POST['sat_maxcities'] <= 50 && !empty($_POST['sat_maxcities'])) {
                                    $valuetobechecked = (int) strip_tags($_POST['sat_maxcities']);
                                } else {
                                    $valuetobechecked = 5;
                                }
                                for ($i = 1; $i <= $sat_accountinfo->plan->maxlocations; $i++) {
                                    if (strcasecmp($pagename, 'citations') === 0 && $i == 1 && $sat_accountinfo->plan->maxlocations > 1) {
                                        continue;
                                    }
                                    if ($i > 20) {
                                        echo "<option value='" . $sat_accountinfo->plan->maxlocations . "' " . (($sat_accountinfo->plan->maxlocations == $valuetobechecked) ? 'selected' : '' ) . ">" . __('All cities avaliable', 'georanker') . "</option>";
                                        break;
                                    }
                                    echo "<option value=\"$i\"  " . (($i == $valuetobechecked) ? 'selected' : '' ) . ">$i " . (($i > 1) ? __('cities', 'georanker') : __('city', 'georanker') ) . "</option>";
                                }
                                ?> 
                    </select> 
                </td>
            </tr> 
            <tr class="row_even sat_bglgray sat_customcitytr" <?php echo (!isset($_POST['sat_is_global']) || (isset($_POST['sat_is_global']) && $_POST['sat_is_global'] == 0) ) ? '' : 'style="display:none"' ?>>
                <td class="sat_nobg">
                    <label title="<?php _e('Add a custom city, region or address  (must be in the selected country).', 'sat'); ?>" for="sat_newreport_city"><?php _e('Cities', 'sat'); ?>:</label><br/>
                    <div class="sat_error sat_hidden sat_citynotfoundmsg" >
                        <?PHP _e('<strong>City name not Found!</strong><br/>Please check the name of the city or enter a full address in the selected country.', 'sat') ?>
                    </div>
                    <input placeholder="<?php _e('Custom city (or zipcode if in the US)', 'sat'); ?>"  class="sat_actualcity" type="text" name="sat_actualcity" title="<?php _e('Add a custom city, region or address  (must be in the selected country).', 'sat'); ?>">
                    <div class="sat_actualcityaddbt button-primary" onclick="sat_addcustomcity(jQuery('.sat_citynotfoundmsg'), jQuery('.sat_actualcity'), jQuery('.sat_customcitieslist'), jQuery('.sat_countriesselectbox'), jQuery('.sat_maxcities'), jQuery('.sat_citieslist'));">
                        <?php _e("Add City", 'sat') ?>
                    </div>
                    <input onchange="sat_updatecitylist(jQuery('.sat_maxcities').children('option:selected').val(), jQuery('.sat_customcitieslist'), jQuery('.sat_citieslist'), jQuery('.sat_countriesselectbox').children('option:selected').val());" type="hidden" value="<?php echo (isset($_POST['sat_customcitieslist']) ? strip_tags($_POST['sat_customcitieslist']) : '') ?>" name="sat_customcitieslist" class="sat_customcitieslist"/>
                    <ul class="sat_citieslist"></ul>
                </td>
            </tr>

            <!--            <tr class="row_even sat_bglgray">
                        <td class="sat_nobg"  colspan="2">
                            <label for="sat_sendreportviaemailcb" title="<?php _e('Enter a list of comma separated email addresses. An email with a link to the report created for each recipient will be sent. Example: user1@company.com, user2@company.com', 'sat'); ?>" ><input onchange="jQuery('.sat_sendreportviaemailemail').toggle(this.checked);" type="checkbox" name="sat_sendreportviaemailcb" id="sat_sendreportviaemailcb" value="0" <?php echo ((isset($_POST['sat_sendreportviaemailcb']) && $_POST['sat_sendreportviaemailcb'] == 1) ) ? 'checked="checked"' : '' ?>> <?php _e('Send this report by email.', 'sat'); ?></label> <br>
                            <input <?php echo ((isset($_POST['sat_sendreportviaemailcb']) && $_POST['sat_sendreportviaemailcb'] == 1) ) ? '' : ' style="display:none" ' ?> title="<?php _e('Enter a list of comma separated email addresses. An email with a link to the report created for each recipient will be sent. Example: user1@company.com, user2@company.com', 'sat'); ?>" type="text" placeholder="<?php _e('user1@company.com, user2@company.com', 'sat'); ?>" class="sat_sendreportviaemailemail" name="sat_sendreportviaemailemail" size="65" value="<?php echo (isset($_POST['sat_sendreportviaemailemail']) ? htmlspecialchars($_POST['sat_sendreportviaemailemail']) : ''); ?>" />
                        </td>
                    </tr> -->
        </table>
        <div class="sat_padded">
            <input type="hidden" name="sat_type" value="<?= $sat_subaction ?>" />
            <input type="hidden" name="sat_is_usealternativetld" value="0" />
            <input type="hidden" name="sat_is_fillcities" value="1" />
            <input type="hidden" name="sat_is_formobile" value="0" />
            <input type="hidden" name="sat_ignoretypes" value="" />
            <input type="hidden" name="sat_is_gmsearchmode" value="1" />
            <input type="hidden" name="sat_is_localonly" value="0" />
            <input type="hidden" name="sat_is_carouselfallbackmode" value="1" />
            <input type="hidden" name="sat_brand" value="" />
            <input type="hidden" name="sat_onlyonekeyword" value="<?= !empty($onlyonekeyword) ? $onlyonekeyword : 1 ?>" />
    <!--            <input type="hidden" class="sat_maxcities"name="sat_maxcities" value="<?= min(max($sat_accountinfo->plan->maxlocations, 1), 20) ?>" />-->
            <input type="hidden" name="sat_language" value="" />
            <input type="submit" name="submit" class="button-primary" value="<?php _e('Create Report', 'sat') ?>" />
        </div>
    </form> 
 <?php
 
//     <script>
//         jQuery(document).ready(function () {
//             jQuery.get("https://freegeoip.net/json/", function (response) {
//                 if (jQuery('select.sat_countriesselectbox option[value=' + response.country_code + ']').length > 0) {
//                     jQuery('select.sat_countriesselectbox').val(response.country_code);
//                     jQuery('select.sat_countriesselectbox').change();
//                     jQuery('#sat_globalreport-country-' + response.country_code).prop('checked', true);
//                 }
//             }, "jsonp");

//         });
//     </script>
   
} else {
    $redirect = sat_redirectviewreportpage($urlViewReporteRanker);
    echo $redirect;
}
?>
