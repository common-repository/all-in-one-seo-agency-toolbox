<?PHP

global $sat_reporttypeobj, $sat_reportobj;

$jsonobj = $sat_reporttypeobj;

if (!empty($jsonobj)) {
    echo "<div style='overflow-x: auto;'>";
    echo "<table style=\"width: 100%;\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th class=\"sat_thstylecss\">Keyword</th>";
    $countcountry = 0;
    foreach ($sat_reportobj->countries as $country) {
        $countcountry++;
    }
    if ($countcountry >= 2) {
        foreach ($sat_reportobj->countries as $country) {
            echo "<th class=\"sat_thstylecss\">" . $country . "</th>";
        }
    } else {
        foreach ($sat_reportobj->regions as $value) {
            echo "<th class=\"sat_thstylecss\">" . $value . "</th>";
        }
    }
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";


    foreach ($sat_reportobj->keywords as $keyword) {
        echo "<tr>";
        echo "<td  class=\"sat_thstylecss\">";
        echo "$keyword";
        echo "</td>";
        if ($countcountry >= 2) {
            foreach ($sat_reportobj->countries as $country) {
                foreach ($jsonobj as $sat_blockiten) {
                    if (strcasecmp($keyword, $sat_blockiten->keyword) == 0 && strcasecmp($country, $sat_blockiten->country_code) == 0) {
                        echo "<td  class=\"sat_thstylecss\">";
                        if (!empty($sat_blockiten->position)) {
                            echo $sat_blockiten->position;
                        } else {
                            echo '<i class="fa fa-minus"></i>';
                        }
                        echo "</td>";
                    }
                }
            }
        } else {
            foreach ($sat_reportobj->regions as $region) {
                foreach ($jsonobj as $sat_blockiten) {
                    if (strcasecmp($keyword, $sat_blockiten->keyword) == 0 && strcasecmp($region, $sat_blockiten->region) == 0) {
                        echo "<td  class=\"sat_thstylecss\">";
                        if (!empty($sat_blockiten->position)) {
                            echo $sat_blockiten->position;
                        } else {
                            echo '<i class="fa fa-minus"></i>';
                        }
                        echo "</td>";
                    }
                }
            }
        }
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}