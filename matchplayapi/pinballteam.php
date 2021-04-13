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

if (isset($_GET['fontsize'])) {
$fontsize = $_GET['fontsize'];
} else {
$fontsize = 48;
}

$tournament = $obj->name;
$url_label = $obj->url_label;


?>

<title><?php
echo $tournament;
?> Match Play Participants</title>


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

echo "<b>Players Registered: " . $countcheck . "</b> | <a href=https://matchplay.live/" . $url_label . ">https://matchplay.live/" . $url_label . "</a><p><hr>";

echo "<hr>";

echo $obj_results[0][games][0][players][0][name] . "<br>";
echo $obj_results[0][games][0][players][0][player_id] . "<br>";
echo $obj_results[0][games][0][players][1][name] . "<br>";
echo $obj_results[0][games][0][players][1][player_id] . "<br>";
echo $obj_results[0][games][0][players][2][name] . "<br>";
echo $obj_results[0][games][0][players][2][player_id] . "<br>";
echo $obj_results[0][games][0][players][3][name] . "<br>";
echo $obj_results[0][games][0][players][3][player_id] . "<br>";










$countcheckcounter = $countcheck - 1;

$names = array();

$array_test = array();

$i = 0;

while($i <= $countcheckcounter){

  $value = $obj_results[0][games][$i][players][0][name];

  $name_check = array_search($value, array_column($array_test,'name'));

  echo '<br>';

  if($name_check !== false)
  {

  } else
  {

      array_push($array_test,
        array('name' => $obj_results[0][games][$i][players][0][name],
              'sort' => $obj_results[0][games][$i][players][0][player_id],
              'team' => 1
             ),
        array('name' => $obj_results[0][games][$i][players][1][name],
              'sort' => $obj_results[0][games][$i][players][1][player_id],
              'team' => 2
             ),
        array('name' => $obj_results[0][games][$i][players][2][name],
              'sort' => $obj_results[0][games][$i][players][2][player_id],
              'team' => 3
              ),
        array('name' => $obj_results[0][games][$i][players][3][name],
              'sort' => $obj_results[0][games][$i][players][3][player_id],
              'team' => 4
             )

      );
  }

    $i++;
}

print_r($array_test);

sort($names);

echo max($lengths) . '<p>';

$clength = count($names);
for($x = 0; $x < $clength; $x++) {
    echo "<b>" . str_replace(" ","&nbsp",$names[$x]) . "</b>";
    echo "&nbsp&nbsp; ";
}



?>
<p>


</div>
</body>
