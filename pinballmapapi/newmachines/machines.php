<html>
<head>

    <?php
        include('head.php')
    ?>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto">

    <link rel="stylesheet"
          href="style.css">

<?php


// Query the various JSON blobs from PinballMap API
// Documentation: https://pinballmap.com/api/v1/docs

$region = 'Philadelphia';

$json = file_get_contents('https://pinballmap.com/api/v1/locations.json?region=' . $region);
$obj = json_decode($json);
$obj_decode = json_decode($json, TRUE);



?>

<title><?php
echo $region;
?> PinballMap.com Recently Added Machines</title>




</head>



<body bgcolor="#18181c">
<!-- Entire body is part of this div class-->
<div class="my_text">


<?php

echo "<hr>";

echo "<b>PinballMap.com Recently Added Machines: " . $region . " </b> | <a href=https://www.pinballmap.com/" . $region . ">https://www.pinballmap.com/" . $region . "</a><p>";


$countcheck = count($obj_decode['locations']);

$countcheckcounter = $countcheck - 1;

$array_machines = array();

$i = 0;

$date_today = date('m/d/Y h:i:s a', time());
$date_compare = date("Y-m-d", strtotime("-28 days"));
$date_limit = date("Y/m/d", strtotime("-90 days"));

while($i <= $countcheckcounter){

      $location_id = $obj_decode['locations'][$i]['id'];
      $location_url = 'https://www.pinballmap.com/map/?by_location_id=' . $location_id;
      $location_name = $obj_decode['locations'][$i]['name'];
      $location_street = $obj_decode['locations'][$i]['street'];
      $location_city = $obj_decode['locations'][$i]['city'];
      $location_state = $obj_decode['locations'][$i]['state'];
      $location_zip = $obj_decode['locations'][$i]['zip'];
      $location_created_at = $obj_decode['locations'][$i]['created_at'];
      $location_updated_at = $obj_decode['locations'][$i]['updated_at'];
      $location_description = $obj_decode['locations'][$i]['description'];
      $location_last_updated_by_username = $obj_decode['locations'][$i]['last_updated_by_username'];
      $location_num_machines = $obj_decode['locations'][$i]['num_machines'];


      $countmachinecheck = count($obj_decode['locations'][$i]['location_machine_xrefs']);

      $countmachinecheckcounter = $countmachinecheck - 1;

      $m = 0;

      while($m <= $countmachinecheckcounter){

          $machine_created_at = ($obj_decode['locations'][$i]['location_machine_xrefs'][$m]['created_at']);
          $machine_updated_at = ($obj_decode['locations'][$i]['location_machine_xrefs'][$m]['updated_at']);
          $machine_created_date = date('Y/m/d', strtotime($machine_created_at));
          $machine_updated_date = date('Y/m/d', strtotime($machine_updated_at));

          if ($machine_created_at === $machine_updated_at) {
              $machine_type = 'New';
              $machine_type_css = 'machinenew';
          } elseif ($machine_created_at > $date_compare) {
              $machine_type = 'New and Updated';
              $machine_type_css = 'machinenew';
          } else {
              $machine_type = 'Updated';
              $machine_type_css = 'machineupdated';
          }
          //$date_diff = $date_today->diff($machine_updated_at);
          //$date_diff = date("Y-m-d", $date_today) - date("Y-m-d", $machine_updated_at);

          $date_diff = strtotime("now") - strtotime($machine_updated_at);
          $date_diff = round($date_diff/86400);
          //if($date_diff = 1) {$date_diff_1 = '';} else {$date_diff_1 = 's';}
          $date_diff_text = ' - ' . number_format($date_diff) . ' Days' . $date_diff_1 . ' Ago';

          $machine_keep = 'no';

          if (strtotime($machine_updated_date) > strtotime($date_limit)) {
              $machine_keep = 'yes';
          } else {
              $machine_keep = 'no';
          }

          $machine_last_updated_by_username = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['last_updated_by_username'];
          if(empty($machine_last_updated_by_username)) {$machine_last_updated_by_username_check = 'admin';} else {$machine_last_updated_by_username_check = $machine_last_updated_by_username;}
          $machine_condition = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['condition'];
          $machine_id = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine_id'];
          $machine_name = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine']['name'];
          $machine_manufacturer = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine']['manufacturer'];
          $machine_year = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine']['year'];
          $machine_ipdb_id = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine']['ipdb_id'];
          if(empty($machine_ipdb_id)) {$machine_link_ipdb = '';} else {$machine_link_ipdb = 'https://www.ipdb.org/machine.cgi?id=' . $machine_ipdb_id;}
          $machine_opdb_id = $obj_decode['locations'][$i]['location_machine_xrefs'][$m]['machine']['opdb_id'];
          if(empty($machine_opdb_id)) {$machine_link_pintips = '';} else {$machine_link_pintips = 'https://www.ipdb.org/machine.cgi?id=' . $machine_opdb_id;}


          array_push($array_machines,
            array('location_id' => $location_id,
                  'location_url' => $location_url,
                  'location_name' => $location_name,
                  'location_street' => $location_street,
                  'location_city' => $location_city,
                  'location_state' => $location_state,
                  'location_zip' => $location_zip,
                  'location_created_at' => $location_created_at,
                  'location_updated_at' => $location_updated_at,
                  'location_description' => $location_description,
                  'location_last_updated_by_username' => $location_last_updated_by_username,
                  'location_last_updated_by_username_check' => $location_last_updated_by_username_check,
                  'location_num_machines' => $location_num_machines,
                  'machine_created_at' => $machine_created_at,
                  'machine_updated_at' => $machine_updated_at,
                  'machine_created_date' => $machine_created_date,
                  'machine_updated_date' => $machine_updated_date,
                  'machine_last_updated_by_username' => $machine_last_updated_by_username,
                  'machine_last_updated_by_username_check' => $machine_last_updated_by_username_check,
                  'machine_condition' => $machine_condition,
                  'machine_id' => $machine_id,
                  'machine_name' => $machine_name,
                  'machine_manufacturer' => $machine_manufacturer,
                  'machine_year' => $machine_year,
                  'machine_ipdb_id' => $machine_ipdb_id,
                  'machine_link_ipdb' => $machine_link_ipdb,
                  'machine_opdb_id' => $machine_opdb_id,
                  'machine_link_pintips' => $machine_link_pintips,
                  'machine_type' => $machine_type,
                  'machine_type_css' => $machine_type_css,
                  'machine_keep' => $machine_keep,
                  'date_diff_text' => $date_diff_text,
                  'date_limit' => $date_limit,

                )
            );

        $m++;

        }

$i++;

}




usort($array_machines, function ($item1, $item2) {
    return $item2['machine_updated_at'] <=> $item1['machine_updated_at'];
});

/// Box

$counttable = count($array_machines);

$countchecktable = $counttable - 1;

$t = 0;

while($t <= $countchecktable){

$machine_keep_check = $array_machines[$t]['machine_keep'];

if($machine_keep_check === 'yes') {

    echo '<table>';
    echo '<tr>';
    echo '<td class="' . $array_machines[$t]['machine_type_css'] . '">' . $array_machines[$t]['machine_type'] . $array_machines[$t]['date_diff_text'] . '</td>';
    echo '<td></td>';
    echo '</tr>';

    echo '<tr class="info">';
    echo '<td class="machinename">' . $array_machines[$t]['machine_name'] . '</td>';
    echo '<td class="machinemanu">' . $array_machines[$t]['machine_manufacturer'] . ' - ' . $array_machines[$t]['machine_year'] . '</td>';
    echo '</tr>';

    echo '<tr class="info">';
    echo '<td><a href=' . $array_machines[$t]['location_url'] . " target='new'>" . $array_machines[$t]['location_name'] . '</a> - ' . $array_machines[$t]['location_city'] . ', ' . $array_machines[$t]['location_state'] . ' ' . $array_machines[$t]['location_zip'] . '</td>';
    echo '<td>Added: ' . $array_machines[$t]['machine_created_date'] . '<br>Updated: ' . $array_machines[$t]['machine_updated_date'] . '</td>';
    echo '</tr>';

    echo '<tr class="info">';
    echo '<td>';
        echo '<a href=' . $array_machines[$t]['location_url'] . " target='new'>PinballMap</a>";
        if(empty($array_machines[$t]['machine_link_ipdb'])) {} else {echo ' | <a href=' . $array_machines[$t]['machine_link_ipdb'] . " target='new'>IPDB</a>";}
        if(empty($array_machines[$t]['machine_link_pintips'])) {} else {echo ' | <a href=' . $array_machines[$t]['machine_link_pintip'] . " target='new'>PinTips</a>";}

    echo '</td>';
    echo '<td>Last Updated By: ' . $array_machines[$t]['machine_last_updated_by_username_check'] . '</td>';
    echo '</tr>';

    if (empty($array_machines[$t]['machine_condition'])) {
    } else {
        echo '<tr class="info">';
        echo '<td colspan=2 class="machinenote">Latest Note: ' . $array_machines[$t]['machine_condition'] . '</td>';
        echo '</tr>';
    }

//    echo '<tr class="info">';
//    echo '<td colspan=2 class="machinenote">Latest Note: ' . $array_machines[$t]['machine_condition'] . '</td>';
//    echo '</tr>';


    echo '</table>';


} else {
}

$t++;

}

/// Grid

// $counttable = count($array_machines);
//
// $countchecktable = $counttable - 1;
//
// $t = 0;
//
// echo '<hr>' . $counttable . '<hr>';
//
// echo '<table>';
// echo '<tr>';
// echo '<td>Location Name</td>';
// echo '<td>Machine Name</td>';
// echo '<td>Created</td>';
// echo '<td>Updated</td>';
// echo '</tr>';
//
//
// while($t <= $countchecktable){
//
//     echo '<tr>';
//     echo '<td>'. $array_machines[$t]['location_name'] . '</td>';
//     echo '<td>'. $array_machines[$t]['machine_name'] . '</td>';
//     echo '<td>'. $array_machines[$t]['machine_created_at'] . '</td>';
//     echo '<td>'. $array_machines[$t]['machine_updated_at'] . '</td>';
//     echo '</tr>';
//
// $t++;
//
// }
//
// echo '</table>';
//
//
// echo "<pre>";
// print_r($array_machines);
// echo "</pre> <p>";


//echo "<pre>";
//print_r($obj_decode);
//echo "</pre>";

?>
<p>
<hr>
PinballMap Recently Updated Machines v1.0
<hr>
Data: <a href='https://www.pinballmap.com/'>pinballmap.com/</a>
<br>
Scoreboard: <a href='http://www.pinballspinner.com'>pinballspinner.com</a>
</div>
