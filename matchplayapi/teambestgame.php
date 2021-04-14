<html>
<head>

    <?php
        include('head.php')
    ?>

<?php

if (isset($_GET['matchplaylink'])) {
$matchplaylink = $_GET['matchplaylink'];
} else {
$notice = "No MatchPlay link provided.<p>The URL should include a valid MatchPlay.events tournament code.  For example:<br>
<i>players.php?matchplaylink=summerclassic2019</i><br>
<i>players.php?matchplaylink=0bbr3</i><p>
You can also change the font size by adding the fontsize variable:
<i>players.php?matchplaylink=summerclassic2019&fontsize=36</i><p>"

;
}

$json = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink);
$obj = json_decode($json);
$obj_decode = json_decode($json, TRUE);

$json_results = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink . '/results');
$obj_results = json_decode($json_results, TRUE);

$json_standings = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink . '/standings');
$obj_standings = json_decode($json_standings, TRUE);


if (isset($_GET['fontsize'])) {
$fontsize = $_GET['fontsize'];
} else {
$fontsize = 24;
}

$tournament = $obj->name;
$url_label = $obj->url_label;


?>

<title><?php
echo $tournament;
?> MatchPlay Team Best Game</title>


        <style>
            .my_text
            {
                font-family:    "Courier New", Courier, monospace;
                font-size:      <?php echo $fontsize;?>px;
            }
        </style>

</head>



<body>
<div class="my_text">


<?php

echo $notice;


$countcheck = count($obj_results[0][games]);

//print_r($obj_results);

echo "<hr>";

echo "<b>MatchPlay Pinball Teams: </b> | <a href=https://matchplay.live/" . $url_label . ">https://matchplay.live/" . $url_label . "</a><p>";

$countcheckcounter = $countcheck - 1;

$names = array();

$array_test = array();

$i = 0;

$igroup = 0;

echo '<hr>';

while($i <= $countcheckcounter){

  $value = $obj_results[0][games][$i][players][0][name];

  $name_check = array_search($value, array_column($array_test,'namecheck'));

      $namecheck = $obj_results[0][games][$i][players][0][name];
      $arenaid = $obj_results[0][games][$i][arena_id];
      $arenaname_check = array_search($arenaid, array_column($obj_decode[arenas],'arena_id'));
      $arenaname = $obj->arenas[$arenaname_check]->name;
      $name0 = $obj_results[0][games][$i][players][0][name];
      $sort0 = $obj_results[0][games][$i][players][0][player_id];
      $results0_check = array_search($sort0, array_column($obj_results[0][games][$i][results],'player_id'));
      $score0 = $obj_results[0][games][$i][results][$results0_check][score];
      $name1 = $obj_results[0][games][$i][players][1][name];
      $sort1 = $obj_results[0][games][$i][players][1][player_id];
      $results1_check = array_search($sort1, array_column($obj_results[0][games][$i][results],'player_id'));
      $score1 = $obj_results[0][games][$i][results][$results1_check][score];
      $name2 = $obj_results[0][games][$i][players][2][name];
      $sort2 = $obj_results[0][games][$i][players][2][player_id];
      $results2_check = array_search($sort2, array_column($obj_results[0][games][$i][results],'player_id'));
      $score2 = $obj_results[0][games][$i][results][$results2_check][score];
      $name3 = $obj_results[0][games][$i][players][3][name];
      if(empty($name3)){$name3 = 'Absent Pinballer';} else {}
      $sort3 = $obj_results[0][games][$i][players][3][player_id];
      $results3_check = array_search($sort3, array_column($obj_results[0][games][$i][results],'player_id'));
      $score3 = $obj_results[0][games][$i][results][$results3_check][score];
      if($name3 === 'Absent Pinballer'){
        $score3 = floor(($score0 + $score1 + $score2)/3);
      } else {}
      $teamgamescore = $score0 + $score1 + $score2 + $score3;





       array_push($array_test,
         array('namecheck' => $namecheck,
               'arenaid' => $arenaid,
               'arenaname_check' => $arenaname_check,
               'arenaname' => $arenaname,
               'name1' => $name0,
               'sort1' => $sort0,
               'results1_check' => $results0_check,
               'score1' => $score0,
               'name2' => $name1,
               'sort2' => $sort1,
               'results2_check' => $results1_check,
               'score2' => $score1,
               'name3' => $name2,
               'sort3' => $sort2,
               'results3_check' => $results2_check,
               'score3' => $score2,
               'name4' => $name3,
               'sort4' => $sort3,
               'results4_check' => $results3_check,
               'score4' => $score3,
               'teamgamescore' => $teamgamescore,
              )

      );

      $igroup++;

    $i++;
}

usort($array_test, function($a, $b)
{
    $name = strcmp($a['arenaname'], $b['arenaname']);
    if($name === 0)
    {
        return $b['teamgamescore'] - $a['teamgamescore'];
    }
    return $name;
});

//usort($array_test, build_sorter('arenaname'));


//$array_sorted = array_orderby($array_test, 'arenaname', SORT_ASC, 'teamgamescore', SORT_DESC);

//array_multisort($array_test[arenaname], SORT_ASC, SORT_STRING,
//                $array_test[teamgamescore], SORT_MUMERIC, SORT_DESC,
//                $array_test);


$countcheck = count($array_test);

$countcheckcounter = $countcheck - 1;

$i = 0;

print("<pre>".print_r($array_test,true)."</pre>");

// old table code

echo "<hr>Standings Table:<br>";

echo "<table border=1>";

echo "<tr>";

echo "<td><b>Group #</b></td>";
echo "<td colspan=2><b><font color='red'>Red Replays</font></b></td>";
echo "<td colspan=2><b><font color='teal'>Teal Tilt Bobs</font></b></td>";
echo "<td colspan=2><b><font color='orange'>Orange Orbits</font></b></td>";
echo "<td colspan=2><b><font color='blue'>Blue Bumpers</font></b></td>";

echo "</tr>";

$points1total = 0;
$points2total = 0;
$points3total = 0;
$points4total = 0;

while($i <= $countcheckcounter){

$iplus = $i+1;

$points1 = 0;
$points2 = 0;
$points3 = 0;
$points4 = 0;

$pointcheck1 = array_search($array_test[$i][name1], array_column($obj_standings,'name'));
$pointcheck2 = array_search($array_test[$i][name2], array_column($obj_standings,'name'));
$pointcheck3 = array_search($array_test[$i][name3], array_column($obj_standings,'name'));
$pointcheck4 = array_search($array_test[$i][name4], array_column($obj_standings,'name'));

if($pointcheck1 === false){$points1 = 0;} else {$points1 = $obj_standings[$pointcheck1][points];}
if($pointcheck2 === false){$points2 = 0;} else {$points2 = $obj_standings[$pointcheck2][points];}
if($pointcheck3 === false){$points3 = 0;} else {$points3 = $obj_standings[$pointcheck3][points];}
if($pointcheck4 === false){$points4 = 0;} else {$points4 = $obj_standings[$pointcheck4][points];}

if($array_test[$i][name1] === 'Absent Pinballer'){$points1 = 16;} else {}
if($array_test[$i][name2] === 'Absent Pinballer'){$points2 = 16;} else {}
if($array_test[$i][name3] === 'Absent Pinballer'){$points3 = 16;} else {}
if($array_test[$i][name4] === 'Absent Pinballer'){$points4 = 16;} else {}

$points1total = $points1total + $points1;
$points2total = $points2total + $points2;
$points3total = $points3total + $points3;
$points4total = $points4total + $points4;

echo "<tr>";

echo "<td>Group #" . $iplus . "</td>";
echo "<td>" . $array_test[$i][name1] . "</td>";
echo "<td>" . $points1 . "</td>";
echo "<td>" . $array_test[$i][name2] . "</td>";
echo "<td>" . $points2 . "</td>";
echo "<td>" . $array_test[$i][name3] . "</td>";
echo "<td>" . $points3 . "</td>";
echo "<td>" . $array_test[$i][name4] . "</td>";
echo "<td>" . $points4 . "</td>";

echo "</tr>";

    $i++;

}

echo "<tr>";

echo "<td><b>Totals</b></td>";
echo "<td><b><font color='red'>Red Replays</font></b></td>";
echo "<td><b>" . $points1total . "</b></td>";
echo "<td><b><font color='teal'>Teal Tilt Bobs</font></b></td>";
echo "<td><b>" . $points2total . "</b></td>";
echo "<td><b><font color='orange'>Orange Orbits</font></b></td>";
echo "<td><b>" . $points3total . "</b></td>";
echo "<td><b><font color='blue'>Blue Bumpers</font></b></td>";
echo "<td><b>" . $points4total . "</b></td>";

echo "</tr>";

echo "</table>";


?>
<p>


</div>
</body>
