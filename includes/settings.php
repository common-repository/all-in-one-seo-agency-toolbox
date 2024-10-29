<?PHP require_once 'settings-init.php'; ?>
<div class="wrap">   
    <div class="sat_page sat_page_settings">
        <a href="http://www.georanker.com" target="blank" class="sat_floatrigth sat_marigin5bottom sat_mariginleft" title="<?php _e('Visit GeoRanker.com Website!', 'sat'); ?>" >
            <img src="<?PHP echo plugins_url(SAT_FOLDERNAME . '/images/georanker-logo-big.png') ?>" width="175" height="50" alt="GeoRanker" />
        </a>
        <h2><?php _e('All-in-One SEO Agency ToolBox Settings', 'sat'); ?></h2>

        <?PHP if (empty($accountinfo) || isset($accountinfo->debug)) { ?>

            <p>
                <?php _e('Create deep SEO reports directly from your admin page. Let your users see the data and monitor the results.', 'sat'); ?>
            </p>
            <div class="sat_box" id="sat_box_info">
                <div class="sat_box_title">
                    <?php _e('Get Started with GeoRanker!', 'sat'); ?>
                </div>   
                <a class="button-secondary sat_floatingrigthbuttontop" href="https://www.georanker.com/register/" target="_blank"><strong><?php _e('Setup your free account', 'sat'); ?></strong></a>
                <p>
                    <?php printf(__('To be able to use this plugin you first of all need to create a free account at %s.', 'sat'), '<a href="https://www.georanker.com/register" target="_blank">https://www.GeoRanker.com/</a>'); ?>
                    <?php _e('After having created your account, please enter the API Key and your login email in the form below.', 'sat'); ?>
                    <?php _e("Don't worry the setup takes only a couple of seconds!", 'sat'); ?>
                </p>
            </div>
            <?php
        } else {
            ?>
            <p></p>
            <div class="sat_box" id="sat_box_info">
                <div class="sat_box_title">
                    <?php _e('Your API Account is setup correctly', 'sat'); ?>
                </div>
                <a class="button-secondary sat_floatingrigthbuttontop" href="https://www.georanker.com/dashboard/" target="_blank"><strong><?php _e('Visit your GeoRanker account', 'sat'); ?></strong> </a>
                <p>
                    <?php _e('Login to your account to manage your reports, monitors and plan.', 'sat'); ?> 
                    <?php _e('Detailed information of all reports you create using your api key will be available under your GeoRanker account dashboard.', 'sat'); ?>
                </p>
            </div>
            <?php
        }
        ?>

        <?php
        if (isset($_POST) && isset($_POST['sat_settings']) && !empty($_POST['sat_settings']) && is_admin() && current_user_can('manage_options')) {
            if (!empty($accountinfo) && !isset($accountinfo->debug)) {
                echo '<div class="sat_box" id="sat_box_updated">';
                echo __('Your modifications have been saved successfully!', 'sat');
                echo '</div>';
            } else {
                echo '<div class="sat_box" id="sat_box_updatederror">';
                echo __('Unable to connect to GeoRanker API. Make sure that you entred a valid API Key and your email registred on georanker!', 'sat');
                if (isset($accountinfo->msg) && !empty($accountinfo->msg)) {
                    echo '<br/>' . __('Details', 'sat') . ': <span style="font-weight:normal">' . strip_tags($accountinfo->msg) . '</span>';
                }
                echo '</div>';
            }
        }
        ?>
        <?PHP if (empty($accountinfo) || isset($accountinfo->debug)) { ?>
            <div class="widget sat_widget">
                <div class="widget-top sat_nomovecursor">
                    <div class="widget-title">
                        <a class="sat_floatrigth sat_mariginleft" href="https://www.georanker.com/settings" target="_blank"><?php _e('Click here to create and view your API Credentials', 'sat'); ?></a>
                        <h4><?php _e('API Connection Settings', 'sat'); ?> <span class="in-widget-title"></span></h4>
                    </div>
                </div>
                <div class="widget-inside sat_nopadding" style="display: block;">
                    <form method="post" action="admin.php?page=sat_page_settings">
                        <table class="form-table sat_table sat_noborder sat_nomargin">
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="sat_settings_api_email"><?php _e('Email', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <input type="text"  id="sat_settings_api_email" name="sat_settings[email]" size="65" value="<?php echo (isset($sat_settings['email']) ? htmlspecialchars($sat_settings['email']) : ''); ?>" />
                                </td>
                            </tr>
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="sat_settings_api_key"><?php _e('API Key', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <input type="text"  id="sat_settings_api_key" name="sat_settings[apikey]" size="65" value="<?php echo (isset($sat_settings['apikey']) ? htmlspecialchars($sat_settings['apikey']) : ''); ?>" />
                                </td>
                            </tr>    
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="sat_settings_redirect_ongeoranker"><?php _e('Redirect the report on GeoRanker', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <?php
                                    if (isset($_POST['is_redirect_ongeoranker']) && $_POST['is_redirect_ongeoranker'] == 1) {
                                        $checkedclass = 'checked="checked"';
                                    } else {
                                        $checkedclass = '';
                                    }
                                    ?>
                                    <input type="checkbox" <?php echo $checkedclass ?> id="sat_settings_redirect_ongeoranker" name="is_redirect_ongeoranker" value="1" />
                                </td>
                            </tr>
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="is_limetfreereport_notlogged"><?php _e('Number limit of create report user not logged', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <select  name="is_limetfreereport_notlogged" class="sat_settings_limetfreereport">
                                        <option value="1" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 1) ? "selected" : "" ?> >1 Report</option>
                                        <option value="2" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 2) ? "selected" : "" ?> >2 Report</option>
                                        <option value="4" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 4) ? "selected" : "" ?>>4 Report</option>
                                        <option value="8" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 8) ? "selected" : "" ?>>8 Report</option>
                                        <option value="16" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 16) ? "selected" : "" ?>>16 Report</option>
                                        <option  value="" <?php echo (empty($_POST['is_limetfreereport_notlogged'])) ? "selected" : "" ?>>Unlimited Report</option>                                       
                                    </select>
                                </td>
                            </tr>
							<tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="is_limetfreereport_islogged"><?php _e('Number limit of create report user is logged', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <select  name="is_limetfreereport_islogged" class="sat_settings_limetfreereport">
                                        <option value="1" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 1) ? "selected" : "" ?> >1 Report</option>
                                        <option value="2" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 2) ? "selected" : "" ?> >2 Report</option>
                                        <option value="4" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 4) ? "selected" : "" ?>>4 Report</option>
                                        <option value="8" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 8) ? "selected" : "" ?>>8 Report</option>
                                        <option value="16" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 16) ? "selected" : "" ?>>16 Report</option>
                                        <option  value="" <?php echo (empty($_POST['is_limetfreereport_islogged'])) ? "selected" : "" ?>>Unlimited Report</option>                                       
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="sat_padded">
                            <input type="hidden" name="sat_settings[apikey_invalid]" value="0" />
                            <input type="submit" name="submit" class="button-primary" value="<?php _e('Save &amp; Verify API Settings', 'sat') ?>" />
                        </div>
                    </form> 
                </div>
            </div>
        <?PHP } else { ?>
            <div class="widget sat_widget">
                <div class="widget-top sat_nomovecursor">
                    <div class="widget-title">                       
                        <h4 ><?php _e('API account status', 'sat'); ?> <span class="in-widget-title"></span>
                            <a style="font-size: 12px;" class="sat_floatrigth sat_mariginleft" href="https://www.georanker.com/settings" target="_blank"><?php _e('Account Settings at GeoRanker', 'sat'); ?> </a>
                        </h4>
                    </div>
                </div>
                <div class="widget-inside sat_nopadding" style="display: block;">
                    <form method="post" action="admin.php?page=sat_page_settings">
                        <table class="form-table sat_table sat_noborder sat_nomargin">
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <?php _e('User', 'sat'); ?>:
                                </td>
                                <td class="sat_nobg">
                                    <img class="sat_floatleft sat_mariginright sat_borderradius3" src="<?PHP echo "https://www.gravatar.com/avatar/" . md5(strtolower(trim($accountinfo->email))) . "?d=mm&s=40" ?>" alt="<?php echo stripslashes($accountinfo->display_name); ?>"/>
                                    <h3 style="height: 20px;margin: 0;padding: 0;color: '#48524B';"><?php echo $accountinfo->display_name; ?></h3>
                                    <?php echo $accountinfo->email; ?>
                                </td>
                            </tr>
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px" >
                                    <?php _e('API Key', 'sat'); ?>:
                                </td>
                                <td class="sat_nobg" style="font-family: monospace; ">
                                    <?php echo substr(strtoupper($sat_settings['apikey']), 0, 10); ?><img style="vertical-align: text-top;" src="<?PHP echo plugins_url(SAT_FOLDERNAME . '/images/apikeyblur.jpg') ?>" alt="XXXXXXXXXXXXXXXXXXXXXXX" />
                                </td>
                            </tr>   
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <?php _e('Credits', 'sat'); ?>:
                                </td>
                                <td class="sat_nobg">
                                    <?php
                                    //TODO add htmlspecial char in all output that com from post or api
                                    echo $accountinfo->credits;
                                    ?> of <?php echo $accountinfo->plan->creditspermonth; ?>
                                </td>
                            </tr>   
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <?php _e('Plan', 'sat'); ?>:
                                </td>
                                <td class="sat_nobg">
                                    <?PHP
                                    $medalimg = 'medal_gray_small.png';
                                    $planamecolor = '#48524B';
                                    if ($accountinfo->plan->price > 0) {
                                        $medalimg = 'medal_glod_small.png';
                                        $planamecolor = '#686004';
                                    }
                                    if ($accountinfo->plan->price > 30) {
                                        $medalimg = 'medal_blue_small.png';
                                        $planamecolor = '#0916AC';
                                    }
                                    if ($accountinfo->plan->price > 150) {
                                        $medalimg = 'medal_red_small.png';
                                        $planamecolor = '#AC0909';
                                    }
                                    ?>
                                    <a href="http://www.georanker.com/plans" target="_blank"><img src="<?PHP echo plugins_url(SAT_FOLDERNAME . '/images/' . $medalimg) ?>" alt="" class="sat_floatleft sat_mariginright"/></a>
                                    <a href="http://www.georanker.com/plans" target="_blank" style="text-decoration: none"><h3 style="height: 20px;margin: 0;padding: 0;color: <?php echo $planamecolor; ?>;"><?php echo $accountinfo->plan->name; ?> </h3></a>
                                    Renew: <?php echo $accountinfo->creditsexpiration; ?>
                                </td>
                            </tr>
                            <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="sat_settings_redirect_ongeoranker"><?php _e('Redirect the report on GeoRanker', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <?php
                                    if (isset($_POST['is_redirect_ongeoranker']) && $_POST['is_redirect_ongeoranker'] == 1) {
                                        $checkedclass = 'checked="checked"';
                                    } else {
                                        $checkedclass = '';
                                    }
                                    ?>
                                    <input type="checkbox" disabled="disabled" <?php echo $checkedclass ?> id="sat_settings_redirect_ongeoranker" name="is_redirect_ongeoranker" value="1" />
                                </td>
                            </tr>
                             <tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="is_limetfreereport_notlogged"><?php _e('Number limit of create report user not logged', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <select disabled="disabled" name="is_limetfreereport_notlogged" class="sat_settings_limetfreereport">
                                        <option value="1" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 1 || isset($sat_settings['is_limetfreereport_notlogged']) && $sat_settings['is_limetfreereport_notlogged'] == 1) ? "selected" : "" ?> >1 Report</option>
                                        <option value="2" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 2 || isset($sat_settings['is_limetfreereport_notlogged']) && $sat_settings['is_limetfreereport_notlogged'] == 2) ? "selected" : "" ?> >2 Report</option>
                                        <option value="4" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 4 || isset($sat_settings['is_limetfreereport_notlogged']) && $sat_settings['is_limetfreereport_notlogged'] == 4) ? "selected" : "" ?>>4 Report</option>
                                        <option value="8" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 8 || isset($sat_settings['is_limetfreereport_notlogged']) && $sat_settings['is_limetfreereport_notlogged'] == 8) ? "selected" : "" ?>>8 Report</option>
                                        <option value="16" <?php echo (isset($_POST['is_limetfreereport_notlogged']) && $_POST['is_limetfreereport_notlogged'] == 16 || isset($sat_settings['is_limetfreereport_notlogged']) && $sat_settings['is_limetfreereport_notlogged'] == 16) ? "selected" : "" ?>>16 Report</option>
                                        <option value="" <?php echo (empty($_POST['is_limetfreereport_notlogged'])) ? "selected" : "" ?>>Unlimited Report</option>                                       
                                    </select>
                                </td>
                            </tr>
							<tr class="row_even sat_bglgray">
                                <td class="row_multi sat_nobg" style="width:200px">
                                    <label for="is_limetfreereport_islogged"><?php _e('Number limit of create report user is logged', 'sat'); ?>:</label>
                                </td>
                                <td class="sat_nobg">
                                    <select disabled="disabled" name="is_limetfreereport_islogged" class="sat_settings_limetfreereport">
                                        <option value="1" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 1 || isset($sat_settings['is_limetfreereport_islogged']) && $sat_settings['is_limetfreereport_islogged'] == 1) ? "selected" : "" ?> >1 Report</option>
                                        <option value="2" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 2 || isset($sat_settings['is_limetfreereport_islogged']) && $sat_settings['is_limetfreereport_islogged'] == 2) ? "selected" : "" ?> >2 Report</option>
                                        <option value="4" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 4 || isset($sat_settings['is_limetfreereport_islogged']) && $sat_settings['is_limetfreereport_islogged'] == 4) ? "selected" : "" ?>>4 Report</option>
                                        <option value="8" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 8 || isset($sat_settings['is_limetfreereport_islogged']) && $sat_settings['is_limetfreereport_islogged'] == 8) ? "selected" : "" ?>>8 Report</option>
                                        <option value="16" <?php echo (isset($_POST['is_limetfreereport_islogged']) && $_POST['is_limetfreereport_islogged'] == 16 || isset($sat_settings['is_limetfreereport_islogged']) && $sat_settings['is_limetfreereport_islogged'] == 16) ? "selected" : "" ?>>16 Report</option>
                                        <option value="" <?php echo (empty($_POST['is_limetfreereport_islogged'])) ? "selected" : "" ?>>Unlimited Report</option>                                       
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="sat_padded">
                            <input type="hidden" name="sat_settings[email]" value="" />
                            <input type="hidden" name="sat_settings[apikey_invalid]" value="1" />
                            <input type="hidden" name="sat_settings[apikey]" value="" />
                            <input onClick="return confirm('<?php _e('Are you sure you want to clear your API settings?\nNOTE: The plugin will stop work until you add new credentials!', 'sat'); ?>');" type="submit" name="submit" class="button-primary" value="<?php _e('Unlink Account', 'sat') ?>" />
                        </div>
                    </form>
                </div>
            </div>
        <?PHP } ?>


        <div class="sat_box" id="sat_box_help">
            <div class="sat_box_title">
                <?php _e('Help, Updates &amp; Documentation', 'sat'); ?>
            </div>
            <ul>
                <li><?php printf(__('<a target="_blank" href="%s">Follow us on Twitter</a> to stay informed about updates', 'sat'), 'http://www.twitter.com/GeoRanker'); ?>;</li>
                <li><?php printf(__('<a target="_blank" href="%s">Read the online documentation</a> and our <a target="_blank" href="%s">Blog</a> for more information about this plugin', 'sat'), 'https://www.georanker.com/wordpress-plugin/', 'https://www.georanker.com/blog/'); ?>;</li>
                <li><?php printf(__('<a target="_blank" href="%s">Contact us</a> if you have feedback or need assistance', 'sat'), 'https://www.georanker.com/contactus/'); ?>;   </li>   
                <li><?php printf(__('Do you want <strong>develop your own plugins</strong> using GeoRanker API? <a target="_blank" href="%s">See our API documentation</a>', 'sat'), 'http://apidocs.georanker.com/'); ?>.</li>
            </ul>
        </div>
    </div>
</div>