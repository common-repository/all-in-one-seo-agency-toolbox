<?php
global $sat_keyworddensity_data;

if (!empty($sat_keyworddensity_data)) {
    ?>
    <div class="sat_report sat_keyworddensity">
        <div class="sat_display_block sat_clear_both sat_url sat_padding_10" >
            <b>URL: </b><?php echo $sat_keyworddensity_data->url; ?>
        </div>
        <div class="sat_display_block sat_big_block sat_clear_both">
            <b>Processing time: </b><?php echo sprintf("%01.2f", $sat_keyworddensity_data->time_total) ?> seconds<br />
            <small><i>Download Time: <?php echo sprintf("%01.2f", $sat_keyworddensity_data->time_download) . "s" ?>
                    | Parsing: <?php echo sprintf("%01.2f", $sat_keyworddensity_data->time_parse) . "s" ?></i></small>
        </div>
        <div class="sat_display_block sat_big_block sat_clear_both ">
            <b>Page Title:</b> <?php echo $sat_keyworddensity_data->title; ?>
        </div>
        <div class="sat_display_block sat_big_block sat_clear_both sat_background_gray">
            <b>Your website is optimized for: </b>
            <?php
            echo implode(", ", $sat_keyworddensity_data->bestkeywords);
            ?>
        </div>
        <div class="sat_padding_10">
            <div class="sat_display_block sat_float_left sat_width_48">
                <b>Density:</b><br />
                <ul>
                    <?php
                    foreach ($sat_keyworddensity_data->words->density as $w) {
                        echo '<li>';
                        echo sprintf('%02s', $w->count), ' <i>', $w->word, '</i><br />';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="sat_display_block sat_float_right sat_width_48">
                <b>Word Weights:</b><br />
                <ul>
                    <?php
                    foreach ($sat_keyworddensity_data->words->weights as $w) {

                        $thedensity = 1;
                        
                        $thestyle = '';
                        foreach ($sat_keyworddensity_data->words->density as $wd) {
                            if ($w->word === $wd->word) {
                                $thedensity = $wd->count;
                                if ($wd->count < (isset($_POST['sat_keyworddensity_mindensitytobeused']) ? (int) $_POST['sat_keyworddensity_mindensitytobeused'] : 2)) {
                                    $thestyle = 'color: #aa0000;';
                                }
                            }
                        }

                        echo "<li title='Density: $thedensity'>";
                        echo sprintf('%02s', $w->weight), ' <i  style=\'' . $thestyle . '\'>', $w->word, '</i><br />';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="sat_display_block sat_big_block sat_clear_both">
            <b>Words on:</b><br />
            <ul>
                <li><b>Title:</b> <br> 
                    <?PHP
                    foreach ($sat_keyworddensity_data->words->title as $v) {
                        echo $v->word . " (" . $v->count . "); ";
                    }
                    ?>
                </li>
                <li><b>H1 & H2 & H3:</b> <br> 
                    <?PHP
                    foreach ($sat_keyworddensity_data->words->h1 as $v) {
                        echo $v->word . " (" . $v->count . "); ";
                    }
                    ?>
                </li>
                <li><b>Links Text:</b> <br> 
                    <?PHP
                    foreach ($sat_keyworddensity_data->words->a as $v) {
                        echo $v->word . " (" . $v->count . "); ";
                    }
                    ?>
                </li>
                <li><b>Links Title:</b> <br> 
                    <?PHP
                    foreach ($sat_keyworddensity_data->words->a_title as $v) {
                        echo $v->word . " (" . $v->count . "); ";
                    }
                    ?>
                </li>
                <li><b>Image (Alt attribute):</b> <br> 
                    <?PHP
                    foreach ($sat_keyworddensity_data->words->img_alt as $v) {
                        echo $v->word . " (" . $v->count . "); ";
                    }
                    ?>
                </li>
            </ul>
            <small><i>Note that are extracted keywords from body and metakeywords but we don't show here.</i></small>
        </div>       
        <div class="sat_display_block sat_big_block sat_clear_both">
            <b>Meta tags:</b> <br/>
            <ul>
                <?php
                foreach ($sat_keyworddensity_data->meta as $key => $meta) {
                    echo '<li><i>name: ' . $meta->name . '</i><br />';
                    echo 'content: ' . $meta->content . '<br />';
                    if ($meta->{'http-equiv'} != '') {
                        echo 'http-equiv: ' . $meta->{'http-equiv'}. '<br />';
                    }
                    echo '</li>';
                }
                ?>
            </ul>
        </div>

        <div class="sat_display_block sat_big_block sat_clear_both">
            <b>Links:</b> <br/>
            <ul>
                <?php
                foreach ($sat_keyworddensity_data->links as $link) {
                    echo '<li><i>', $link->href, '</i><br />';
                    if (!empty($link->text)) {
                        echo 'Text: ' . $link->text . '<br />';
                    }
                    if (!empty($link->title)) {
                        echo 'Title: ' . $link->title . '<br />';
                    }
                    echo '</li>';
                }
                ?>
            </ul>
        </div>      
        <?php if (!empty($result->images)) { ?>
            <div class="sat_display_block  sat_clear_both">
                <b>Images:</b> <br/>
                <ul>
                    <?PHP
                    foreach ($result->images as $img) {
                        echo '<li><i>URL: ' . $img->src . '</i><br />';
                        if (!empty($link->alt)) {
                            echo 'Alt: ' . $img->alt . '<br />';
                        }
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div> 
        <?php } ?>
    </div>
    <?PHP
} else {
    echo "<center>Impossible to read the results. Please, try again later.</center>";
}

//echo '<pre>' . print_r($sat_keyworddensity_data, true) . '</pre>';

