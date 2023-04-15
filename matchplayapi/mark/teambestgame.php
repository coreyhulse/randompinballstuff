<html>
<head>

    <?php
        include('head.php')
    ?>


<!-- autorefresh=1 can be added to the parameters of the page to enable Auto Refresh -->

<?php
if (isset($_GET['autorefresh'])) {

echo "<!-- Refresh the page once every 60 seconds. -->";
echo '<meta http-equiv="refresh" content="60">';
} else {
}

?>




<?php

// Parameterize the MatchPlay link if provided in the URL

if (isset($_GET['matchplaylink'])) {
$matchplaylink = $_GET['matchplaylink'];
} else {
$notice = "<div class='errorNotice'><p>No MatchPlay link provided.</p><p>The URL should include a valid MatchPlay.events tournament code. For example:</p>
<ul>
<li>players.php?matchplaylink=summerclassic2019</li>
<li>players.php?matchplaylink=0bbr3</li>
</ul>
<p>You can also change the font size by adding the fontsize variable:
<i>players.php?matchplaylink=summerclassic2019&fontsize=36</i><p></div>";
}

// Query the various JSON blobs from MatchPlay
// Documentation: https://matchplay.events/api-docs/#/Matchplay

$json = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink);
$obj = json_decode($json);
$obj_decode = json_decode($json, TRUE);

$json_results = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink . '/results');
$obj_results = json_decode($json_results, TRUE);

$json_standings = file_get_contents('https://matchplay.events/data/tournaments/' . $matchplaylink . '/standings');
$obj_standings = json_decode($json_standings, TRUE);


// Default font size in the CSS to 24px, or override it.

if (isset($_GET['fontsize'])) {
$fontsize = $_GET['fontsize'];
} else {
$fontsize = 24;
}

$tournament = $obj->name;
$url_label = $obj->url_label;
$games_per_round = $obj->games_per_round;
$round_status = $obj_results[0][status];


?>

<title><?php
echo $tournament;
?> MatchPlay Team Best Game</title>

<!-- Stylesheet -->
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400&family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;700&display=swap');
		body {
			margin: 0px 25px 0px 25px;
			/* margin-top: 0px;
			margin-bottom: 0px;
			margin-left: 25px;
			margin-right: 25px; */
			background: #18181c;
		}
        .my_text {
			font-family: 'Roboto', sans-serif;
			font-weight: 300;
			margin-left: 20px;
			margin-right: 20px;
        }
        .tabularData {
            font-family: 'Roboto Mono', monospace;
            font-size: 24px;
            font-weight: 400;
            text-align: right;
			white-space: nowrap;
        }
        table.standingsTable {
            background-color: #2D2E36;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            margin-bottom: 40px;
			border-bottom: 2px solid #FFFFFF;
        }
        table.totalsTable {
            background-color: #2D2E36;
            text-align: left;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table.standingsTable td, table.totalsTable td {
			border-top: 1px solid #18181C;
			border-right: 0px;
			border-bottom: 1px solid #18181C; 
			border-left: 0px;
            padding: 8px;
            font-size: 15px;
            color: rgba(255, 255, 255, 1);
        }
        tr.sectionBreak {
            border-top: 2px solid #FFFFFF !important;
        }
        a:link, a:visited {
            color: #4578ed;
        }
        .team1 {
            background: #70943e; /*Green General Illumination*/
			white-space: nowrap;
        }
        .team2 {
            background: #4b6186; /*Blue Bumpers*/
			white-space: nowrap;
        }
        .team3 {
            background: #e37a49; /*Coral Kickbacks*/
			white-space: nowrap;
        }
        .team4 {
            background: #713d65; /*Violet Vari-Targets*/
			white-space: nowrap;
        }
        .team5 {
            background: #3f7a74; /*Teal Tilt Bobs*/
			white-space: nowrap;
        }
        .team6 {
            background: #d32d4a; /*Fuschia Flipper*/
			white-space: nowrap;
        }
        .team7 {
            background: #cc0000; /*Red Replays*/
			white-space: nowrap;
        }
        .team8 {
            background: #444444; /*Silver Spinners*/
			white-space: nowrap;
        }
        .team1_dark {
            background: rgba(112,148,62,0.60);
        }
        .team2_dark {
            background: rgba(75,97,134,0.60);
        }
        .team3_dark {
            background: rgba(227,122,74,0.60);
        }
        .team5_dark {
            background: rgba(63,121,116,0.60);
        }
        .team4_dark {
            background: rgba(113,61,101,0.60);
        }
        .team6_dark {
            background: rgba(211,45,74,0.60);
        }
        .team7_dark {
            background: rgba(204,0,0,0.60);
        }
        .team8_dark {
            background: rgba(204,204,204,0.60);
        }
        .game_90485 { /* Alien */
            background-image: url("images/alien_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-repeat: no-repeat;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_86179 { /* Avengers Infinity Quest */
            background-image: url("images/avengers_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_32851 { /* Batman 66 */
            background-image: url("images/batman66_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_2513 { /* Fathom */
            background-image: url("images/fathom_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
    		border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
	    }
        .game_2916 { /* Funhouse */
            background-image: url("images/funhouse_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_86867 { /* Funhouse 2.0 */
            background-image: url("images/funhouse2_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_71810 { /* Jurassic Park Premium */
            background-image: url("images/jurassicpark_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_96401 { /* Jurassic Park Premium */
            background-image: url("images/jurassicpark_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_31874 { /* Metallica */
            background-image: url("images/metallica_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_71811 { /* Quicksilver */
            background-image: url("images/quicksilver_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_2915 { /* The Simpsons Pinball Party */
            background-image: url("images/simpsons_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_2918 { /* The Simpsons Pinball Party */
            background-image: url("images/theatre_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_49946 { /* Wonka (LE) */
            background-image: url("images/wonka_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .game_77943 { /* Godzilla (Pro) */
            background-image: url("images/godzilla_translite.jpg");
            color: rgba(255, 255, 255, 0.0) !important;
			border-right: 1px solid #18181C !important;
			background-size: auto 150%; 
			/* background-position: center; */
        }
        .rank {
            background: #2d2e36;
            text-align: center;
			border-left: 1px solid #000000 !important;
			
        }
		.footer {
			background: #302E2F;
			padding: 12px;
			font-family: 'Roboto', sans-serif;
    		font-size: 12px;
    		color: #FFFFFF;
    		font-weight: 300;
		}
		.pageHeader {
			background-color: darkgoldenrod;
			padding: 10px;
			margin-bottom: 20px;
			font-family: 'Roboto Slab', serif;
			font-size: 16px;
			color: gold;
			text-transform: uppercase;
			font-weight: 700;
			letter-spacing: 3px;
}
		.pageHeader a {
			color: #3F3212;
			font-size: 14px;
			text-transform: none;
			letter-spacing: normal;
			font-weight: 400;
		}
		.pageheader .notice{
			color: #3F3212;
			float: right;
			letter-spacing: normal;
		}
		h1 {
			font-family: 'Roboto Slab', serif;
			font-size: 16px;
			color: gold;
			text-transform: uppercase;
			font-weight: 700;
			letter-spacing: 3px;
			margin: 5px;
		}
		th {
			text-transform: uppercase;
			font-size: 12px !important;
			font-weight: 700;
			color: white;
			background: #18181c;
			padding: 4px;
		}
		.totalsTable .gameName {
			font-family: 'Roboto Slab', serif;
			font-size: 14px;
			color: gold;
			font-weight: 700;
			background-color: #18181c !important;
		}
		.errorNotice {
			background-color: red;
			color: white;
			font-family: 'Roboto Slab', serif;
			font-size: 14px;
			color: white;
			font-weight: 700;
			padding: 20px;
			margin-bottom: 20px;
		}
	</style>
</head>



<body>
<!-- Entire body is part of this div class-->



<?php

// In case it's a bad MatchPlay link
echo $notice;

$countcheck = count($obj_results[0][games]);
	
// ********************
// HEADER
// ********************	

echo "<div class='PageHeader'>MatchPlay Pinball Teams: " . $tournament . "  <a href=https://matchplay.live/" . $url_label . ">https://matchplay.live/" . $url_label . "</a>";
if($round_status == "completed")
	{
	  echo "<span class='notice'>ROUND COMPLETE</span></div>";
	}
	else {
	  echo "<span class='notice'>Mark complete in MatchPlay.</span></div>";
	}

echo "<div class='my_text'>";
	
$countcheckcounter = $countcheck - 1;

$names = array();
$array_test = array();
$array_total = array();
$array_games = array();
$array_total_2 = array();
$array_player = array();
$array_player_sum = array();

$i = 0;

$iminus = -1;

$igroup = 0;

$groupcheck = '';

while($i <= $countcheckcounter){

  $value = $obj_results[0][games][$i][players][0][name];

  $name_check = array_search($value, array_column($array_test,'namecheck'));

      $namecheck = $obj_results[0][games][$i][players][0][name];
      $arenaid = $obj_results[0][games][$i][arena_id];
      $gamestatus = $obj_results[0][games][$i][status];
      if($gamestatus === 'complete'){
        $groupcompletegamecount = 1;
      } else {$groupcompletegamecount = 0;}
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
      //if(empty($name3)){$name3 = 'Absent Pinballer';} else {}
      $sort3 = $obj_results[0][games][$i][players][3][player_id];
      $results3_check = array_search($sort3, array_column($obj_results[0][games][$i][results],'player_id'));
      $score3 = $obj_results[0][games][$i][results][$results3_check][score];
      //if($name3 === 'Absent Pinballer'){
      //  $score3 = floor(($score0 + $score1 + $score2)/3);
      //} else {}
      if(empty($name3)){
        $groupmembers = 3;
        $score3 = 0;
      } else {
        $groupmembers = 4;
      }
      $teamgamescore = floor($score0 + $score1 + $score2 + $score3) / $groupmembers;

      $groupname = $name0 . ' / ' . $name1 . ' / ' . $name2  . ' / ' . $name3;
      if($groupcheck === $groupname){
      }
      else {
        $igroup++;
      }
      $groupcheck = $groupname;

      if($igroup === 1){$teamname = 'Green General Illumination';}
      if($igroup === 2){$teamname = 'Blue Bumpers';}
      if($igroup === 3){$teamname = 'Coral Kickbacks';}
      if($igroup === 4){$teamname = 'Violet Vari-Targets';}
      if($igroup === 5){$teamname = 'Teal Tilt Bobs';}
      if($igroup === 6){$teamname = 'Fuschia Flippers';}
      if($igroup === 7){$teamname = 'Red Replays';}
      if($igroup === 8){$teamname = 'Silver Spinners';}
      if($igroup > 8){$teamname = 'Pinball Team ' . $igroup;}


       array_push($array_games,
         array('arenaname' => $arenaname,
               'name' => $name0,
               'score' => $score0,
               'groupnumber' => $igroup,
               'teamname' => $teamname,
              )
       );
       array_push($array_games,
         array('arenaname' => $arenaname,
               'name' => $name1,
               'score' => $score1,
               'groupnumber' => $igroup,
               'teamname' => $teamname,
              )
       );
       array_push($array_games,
         array('arenaname' => $arenaname,
               'name' => $name2,
               'score' => $score2,
               'groupnumber' => $igroup,
               'teamname' => $teamname,
              )
       );
       array_push($array_games,
         array('arenaname' => $arenaname,
               'name' => $name3,
               'score' => $score3,
               'groupnumber' => $igroup,
               'teamname' => $teamname,
              )
       );



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
              'groupcompletegamecount' => $groupcompletegamecount,
              'groupname' => $groupname,
              'groupnumber' => $igroup,
              'teamname' => $teamname,
             )

     );

    $i++;
}

usort($array_games, function($a, $b)
{
    $name = strcmp($a['arenaname'], $b['arenaname']);
    if($name === 0)
    {
        return $b['score'] - $a['score'];
    }
    return $name;
});

usort($array_test, function($a, $b)
{
    $name = strcmp($a['arenaname'], $b['arenaname']);
    if($name === 0)
    {
        return $b['teamgamescore'] - $a['teamgamescore'];
    }
    return $name;
});

$countcheck = count($array_test);

$countcheckcounter = $countcheck - 1;

$i = 0;



// ********************
// Standings Table
// ********************

echo "<h1>Standings</h1>";
echo "<table class='standingsTable'>";
echo "<tr>";
echo "<th>Game</th>";
echo "<th>Team</th>";
echo "<th>Rank</th>";
echo "<th>Points</th>";
echo "<th>Player 1</th>";
echo "<th>Score</th>";
echo "<th>Player 2</th>";
echo "<th>Score</th>";
echo "<th>Player 3</th>";
echo "<th>Score</th>";
echo "<th>Player 4</th>";
echo "<th>Score</th>";
echo "<th>Avg Score</th>";
echo "</tr>";

$points1total = 0;
$points2total = 0;
$points3total = 0;
$points4total = 0;
$teampointstotal = 0;

while($i <= $countcheckcounter){

$iplus = $i+1;

$points1 = 0;
$points2 = 0;
$points3 = 0;
$points4 = 0;
$teampoints = 0;

$points1total = $points1total + $points1;
$points2total = $points2total + $points2;
$points3total = $points3total + $points3;
$points4total = $points4total + $points4;


$gamecheck = $array_test[$i][arenaname];

if($gamecheck !== $gamecheckprior)
{
  $sectionbreak = "sectionBreak";


  $gamerank = 1;
  $gamepoints = 10;


}
else {
  $sectionbreak = "";
}

  if($gamerank == 1) {$gamepoints = 10;}
  if($gamerank == 2) {$gamepoints = 7;}
  if($gamerank == 3) {$gamepoints = 5;}
  if($gamerank == 4) {$gamepoints = 4;}
  if($gamerank == 5) {$gamepoints = 3;}
  if($gamerank == 6) {$gamepoints = 2;}
  if($gamerank == 7) {$gamepoints = 1;}
  if($gamerank > 7) {$gamepoints = 0;}

  if(number_format($array_test[$i][teamgamescore]) == 0)
  {
    $gameranktable = '?';
    $gamepointstable = 0;
    $gamegroupcount = 0;
  }
    else
  {
    $gameranktable = $gamerank;
    $gamepointstable = $gamepoints;
    $gamegroupcount = 1;
  }



//echo "<tr class='team" . $array_test[$i][groupnumber] . " " . $sectionbreak . "'>";
echo "<tr class='team" . " " . $sectionbreak . "'>";

if($gamecheck !== $gamecheckprior)
{
echo "<td class='game_" . $array_test[$i][arenaid] . "' rowspan=" . $igroup . ">Game: " . $array_test[$i][arenaname] . "</td>";
}
echo "<td class='team" . $array_test[$i][groupnumber] . "'>" . $array_test[$i][teamname] . "</td>";
echo "<td class='rank'>" . $gameranktable . "</td>";
echo "<td class='rank team" . $array_test[$i][groupnumber] . "_dark'>" . $gamepointstable . "</td>";
echo "<td class='team" . $array_test[$i][groupnumber] . "'>" . $array_test[$i][name1] . "</td>";
echo "<td class='tabularData team" . $array_test[$i][groupnumber] . "_dark'>" . number_format($array_test[$i][score1]) . "</td>";
echo "<td class='team" . $array_test[$i][groupnumber] . "'>" . $array_test[$i][name2] . "</td>";
echo "<td class='tabularData team" . $array_test[$i][groupnumber] . "_dark'>" . number_format($array_test[$i][score2]) . "</td>";
echo "<td class='team" . $array_test[$i][groupnumber] . "'>" . $array_test[$i][name3] . "</td>";
echo "<td class='tabularData team" . $array_test[$i][groupnumber] . "_dark'>" . number_format($array_test[$i][score3]) . "</td>";
echo "<td class='team" . $array_test[$i][groupnumber] . "'>" . $array_test[$i][name4] . "</td>";
echo "<td class='tabularData team" . $array_test[$i][groupnumber] . "_dark'>" . number_format($array_test[$i][score4]) . "</td>";
echo "<td class='tabularData team" . $array_test[$i][groupnumber] . "'><b>" . number_format($array_test[$i][teamgamescore]) . "</b></td>";

echo "</tr>".PHP_EOL;

array_push($array_total,
  array($array_test[$i][groupname] => $gamepointstable,
    )
  );

array_push($array_total_2,
  array('team' => $array_test[$i][groupname] . '|' . $array_test[$i][groupnumber] . '|' . $array_test[$i][teamname],
        'gamepointstable' => $gamepointstable,
        'gamegroupcount' => $gamegroupcount,
        'groupcompletegamecount' => $groupcompletegamecount,

    )
  );

    $gamecheckprior = $gamecheck;

    $i++;
    $gamerank++;


}


echo "</table>";


$sums = array();

foreach ($array_total as $key => $values) {
    foreach ($values as $label => $count) {
        // Create a node in the array to store the value
        if (!array_key_exists($label, $sums)) {
            $sums[$label] = 0;
        }
        // Add the value to the corresponding node
        $sums[$label] += $count;
    }
}


// Sort the array in descending order of values
arsort($sums);



$sums_2 = array();

$i = 0;

foreach ($array_total_2 as $item) {
    $key = $item['team'];

    if (!array_key_exists($key, $sums_2)) {
        $sums_2[$key] = array(
            'team' => $item['team'],
            'gamepointstable' => $item['gamepointstable'],
            'gamegroupcount' => $item['gamegroupcount'],
            'groupcompletegamecount' => $item['groupcompletegamecount'],
        );
    } else {
        $sums_2[$key]['gamepointstable'] = $sums_2[$key]['gamepointstable'] + $item['gamepointstable'];
        $sums_2[$key]['gamegroupcount'] = $sums_2[$key]['gamegroupcount'] + $item['gamegroupcount'];
        $sums_2[$key]['groupcompletegamecount'] = $sums_2[$key]['groupcompletegamecount'] + $item['groupcompletegamecount'];
    }

}

usort($sums_2, function ($a, $b) {
    return $b['gamepointstable'] - $a['gamepointstable'];
});


// ********************
// Team Totals
// ********************

$countcheck = count($sums_2);

$countcheckcounter = $countcheck - 1;

$i = 0;

echo "<h1>Team Totals</h1>";
echo "<table class='totalsTable'>";
/* if($round_status == "completed")
{
  echo "<tr>";
  echo "<td colspan=4>ROUND COMPLETE</td>";
  echo "</tr>";
}
else {
  echo "<tr>";
  echo "<td colspan=4>ROUND NOT COMPLETE. Mark the round complete in MatchPlay to finalize standings.</td>";
  echo "</tr>";
} */

echo "<tr>";
echo "<th>Team</th>";
echo "<th>Players</th>";
echo "<th>Points</th>";
echo "<th>Played</th>";
echo "</tr>";

foreach ($sums_2 as $item) {

    $teamexplode = explode('|',$item[team]);

    echo "<tr class='team" . $teamexplode[1] . "'>";
    echo "<td>". $teamexplode[2] ."</td>";
    echo "<td>". $teamexplode[0] ."</td>";
    echo "<td class='rank'>". $item[gamepointstable] ."</td>";
    echo "<td class='rank'>". $item[gamegroupcount] ."</td>";
    echo "</tr>";
  }

echo "</table>";


// ********************
// Individual Standings
// ********************
	
$countcheckgames = count($array_games);

$countcheckgamescounter = $countcheckgames - 1;

$i = 0;

$player_points = 0;

echo "<h1>Individual Standings</h1>";
echo "<table>";
echo "<tr>";
echo "<td>";
echo "<table>";

$indgamename = 0;
$indplayer = 0;
$indrank = 0;
$indpoints = 0;

while($i <= $countcheckgamescounter){

$iplus = $i+1;

$points1 = 0;
$points2 = 0;
$points3 = 0;
$points4 = 0;
$teampoints = 0;


$gamecheck = $array_games[$i][arenaname];

if($gamecheck !== $gamecheckprior)
{

  echo "</table>";
  echo "</td>";

  echo "<td>";
  echo "<table class='totalsTable'>";

  echo "<tr>";
  //echo "<td class='game_" . $array_test[$i][arenaid] . "' colspan=4>" . $array_games[$i][arenaname] . "</td>";
  echo "<th class='gameName' colspan=4>" . $array_games[$i][arenaname] . "</th>";
  echo "</tr>";
  echo "<tr>";
  echo "<th>Rank</th>";
  echo "<th>Points</th>";
  echo "<th>Player</th>";
  echo "<th>Score</th>";
  echo "</tr>";

  if($tablecolor === '#dddddd')
  {$tablecolor = '#ffffff';}
    else
  {$tablecolor = '#dddddd';}


  $gamerank = 1;


}
else {}

  if($gamerank < 25) {$gamepoints = 26 - $gamerank;}
  if($gamerank >= 25) {$gamepoints = 0;}

  if(number_format($array_games[$i][score]) == 0)
  {
    $gameranktable = '?';
    $gamepointstable = 0;
    $gamegroupcount = 0;
  }
    else
  {
    $gameranktable = $gamerank;
    $gamepointstable = $gamepoints;
    $gamegroupcount = 1;
  }

echo "<tr>";
echo "<td class='rank'>" . $gameranktable . "</td>";
echo "<td class='rank'>" . $gamepointstable . "</td>";
echo "<td class='team" . $array_games[$i][groupnumber] . "'>" . $array_games[$i][name] . "</td>";
echo "<td class='tabularData team" . $array_games[$i][groupnumber] . "_dark'>" . number_format($array_games[$i][score]) . "</td>";
echo "</tr>";

$player_name = $array_games[$i][name] . "|" . $array_games[$i][groupnumber];

$player_points = $array_player[$player_name];

$array_player[$player_name] = $gamepointstable + $player_points;

    $gamecheckprior = $gamecheck;
    $i++;
    $gamerank++;

}

  echo "</table>";
  echo "</tr>";
  echo "</td>";
  echo "</table>";

  // Sort the array in descending order of values
  arsort($array_player);



  // Individual Totals

  $countcheckplayer = count($array_player);
  $countcheckplayercounter = $countcheckplayer - 1;
  $i = 0;
  $player_points = 0;

  echo "<h1>Individual Totals</h1>";
  echo "<table class='totalsTable'>";
  echo "<tr>";
  echo "<th>Rank</th>";
  echo "<th>Player</th>";
  echo "<th>Points</th>";
  echo "</tr>";

  $totalplayer = 0;
  $totalrank = 0;
  $totalpoints = 0;
  $gamerank = 1;

  foreach ($array_player as $key => $val) {

  $playerexplode = explode('|',$key);

  echo "<tr>";
  echo "<td class='rank'>" . $gamerank . "</td>";
  echo "<td class='team" . $playerexplode[1] . "'>" . $playerexplode[0] . "</td>";
  echo "<td class='rank'>" . $val . "</td>";
  echo "</tr>";

  $gamerank++;

  }

	echo "</table>";
	echo "</tr>";
	echo "</td>";
	echo "</table>";

?>
</div>
<!-- FOOTER AREA -->
	
  <div class="footer">Team Match Play v8.0.1 | Data: <a href='http://matchplay.events'>matchplay.events</a> | Scoreboard: <a href='http://www.pinballspinner.com'>pinballspinner.com</a> | CSS: <a href='http://www.markrmiles.com/'>markrmiles.com</a></div>	

</body>
