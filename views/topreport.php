<?PHP

global $sat_reporttypeobj, $sat_reportobj;

$jsonobj = (object) $sat_reporttypeobj;

if (!empty($jsonobj)) {
    foreach ($jsonobj as $sat_blockiten) {
        echo '<div class="sat_page sat_topreport">';
        echo '<h2>' . htmlentities(ucwords($sat_blockiten->region), ENT_QUOTES, 'UTF-8') . '</h2>';
        echo '<div class="sat_keywordline">' . htmlentities(ucwords($sat_blockiten->keyword), ENT_QUOTES, 'UTF-8') . '</div>';
        if (!empty($sat_blockiten->data)) {
            echo '<div class="sat_blocktopranking">';
            echo '<ul class="sat_listurltopreport">';
            $tablecount = 0;
            foreach ((array) $sat_blockiten->data as $sat_blockitenposition) {
                $titlepositionrow = '';

                echo '<li title="' . $titlepositionrow . '">';
                echo '<div class="sat_positiondivlist">' . $sat_blockitenposition->position . '</div>';
                echo '<div class="sat_titlelinelist">';

                if (!empty($sat_blockitenposition->title)) {
                    echo htmlentities($sat_blockitenposition->title, ENT_QUOTES, 'UTF-8');
                } else {
                    if (strcasecmp($sat_reportobj->type, 'citations') !== 0) {
                        echo '[Title not available]';
                    }
                }

                echo '</div>';
                $citation_class = (strcasecmp($sat_reportobj->type, 'citations') === 0)? 'sat-align-citation' :'' ;
                echo '<div class="sat_urllinelist '.$citation_class .'">';
                $url = (strcasecmp($sat_reportobj->type, 'citations') === 0)? 'http://' : '';                
                if (!empty($sat_blockitenposition->url)) {
                    echo '<a rel="nofollow" target="_blank" href="'. $url.'' . htmlentities($sat_blockitenposition->url, ENT_QUOTES, 'UTF-8') . '">';
                }

                if (!empty($sat_blockitenposition->type)) {
                    echo sat_getimagefortypesfromcode($sat_blockitenposition->type) . "&nbsp;";
                }
                if (!empty($sat_blockitenposition->url)) {
                    echo '</a>';
                }
                echo '<a rel="nofollow" target="_blank"  href="'. $url.'' . htmlentities($sat_blockitenposition->url, ENT_QUOTES, 'UTF-8') . '">' . $sat_blockitenposition->url . '</a><br/>';
                echo '</div>';


                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        echo '</div>';
    }
}