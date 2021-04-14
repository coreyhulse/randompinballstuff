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
?> MatchPlay Pinball Teams</title>


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

while($i <= $countcheckcounter){

  $value = $obj_results[0][games][$i][players][0][name];

  $name_check = array_search($value, array_column($array_test,'namecheck'));

  if($name_check !== false)
  {

  } else
  {

      // array_push($array_test,
      //   array('name' => $obj_results[0][games][$i][players][0][name],
      //         'sort' => $obj_results[0][games][$i][players][0][player_id],
      //         'team' => 1
      //        ),
      //   array('name' => $obj_results[0][games][$i][players][1][name],
      //         'sort' => $obj_results[0][games][$i][players][1][player_id],
      //         'team' => 2
      //        ),
      //   array('name' => $obj_results[0][games][$i][players][2][name],
      //         'sort' => $obj_results[0][games][$i][players][2][player_id],
      //         'team' => 3
      //         ),
      //   array('name' => $obj_results[0][games][$i][players][3][name],
      //         'sort' => $obj_results[0][games][$i][players][3][player_id],
      //         'team' => 4
      //        )


      $player0 = fmod($igroup,4);
      $player1 = fmod($igroup+1,4);
      $player2 = fmod($igroup+2,4);
      $player3 = fmod($igroup+3,4);

      $namecheck = $obj_results[0][games][$i][players][0][name];
      $name0 = $obj_results[0][games][$i][players][$player0][name];
      if(empty($name0)){$name0 = 'Absent Pinballer';} else {}
      $sort0 = $obj_results[0][games][$i][players][$player0][player_id];
      $mod0 = fmod($sort0,10) + 0.1;
      $name1 = $obj_results[0][games][$i][players][$player1][name];
      if(empty($name1)){$name1 = 'Absent Pinballer';} else {}
      $sort1 = $obj_results[0][games][$i][players][$player1][player_id];
      $mod1 = fmod($sort1,10) + 0.2;
      $name2 = $obj_results[0][games][$i][players][$player2][name];
      if(empty($name2)){$name2 = 'Absent Pinballer';} else {}
      $sort2 = $obj_results[0][games][$i][players][$player2][player_id];
      $mod2 = fmod($sort2,10) + 0.3;
      $name3 = $obj_results[0][games][$i][players][$player3][name];
      if(empty($name3)){$name3 = 'Absent Pinballer';} else {}
      $sort3 = $obj_results[0][games][$i][players][$player3][player_id];
      $mod3 = fmod($sort3,10) + 0.4;


       array_push($array_test,
         array('namecheck' => $namecheck,
               'name1' => $name0,
               'sort1' => $sort0,
               'team1' => 1,
               'name2' => $name1,
               'sort2' => $sort1,
               'team2' => 2,
               'name3' => $name2,
               'sort3' => $sort2,
               'team3' => 3,
               'name4' => $name3,
               'sort4' => $sort3,
               'team4' => 4
              )

      );

      $igroup++;
  }

    $i++;
}

$countcheck = count($array_test);

$countcheckcounter = $countcheck - 1;

$i = 0;

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
