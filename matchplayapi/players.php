<html>
<head>

    <?php
        include('head.php')
    ?>

<!-- Refresh the page once every 60 seconds. -->

<meta http-equiv="refresh" content="60">

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
                font-family: 'Roboto Mono', "Courier New", Courier, monospace;
                font-size: <?php echo $fontsize;?>px;
                font-weight: 400;
                font-size:      <?php echo $fontsize;?>px;
            }
        </style>

</head>



<body>
<div class="my_text">


<?php

echo $notice;



if(is_array($obj->players)){
    $countcheck = count($obj->players);
}
else {
    $countcheck = 1;
}

$countcheckcounter = $countcheck - 1;

$countactive = 0;

$names = array();
$i = 0;
while($i <= $countcheckcounter){

    $strike = '<b>';
    $strikeend = '</b>';

    $name = $obj->players[$i]->name;
    $status = $obj->players[$i]->tournament->status;
    if ($status === 'inactive') {
        $strike = '<strike>';
        $strikeend = '</strike>';
        } else {
        $countactive++;
        }
    $names[] = $strike . $name . $strikeend;
    $i++;
}

echo "<b>Active Players: " . $countactive . "</b> | <a href=https://matchplay.live/" . $url_label . ">https://matchplay.live/" . $url_label . "</a><p><hr>";

sort($names);

if(is_array($lengths)){
    echo max($lengths) . '<p>';
}
else {
    echo '<p>';
}

$clength = count($names);
for($x = 0; $x < $clength; $x++) {
    echo "<b>" . str_replace(" ","&nbsp",$names[$x]) . "</b>";
    echo "&nbsp&nbsp; ";
}


?>
<p>


</div>
</body>
