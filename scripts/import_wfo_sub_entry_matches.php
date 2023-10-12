<?php

// this is a run once to bring in the name matching 

require_once('../config.php');

$in = fopen('../data/wallich_sub_entries_matched.csv', 'r');

$header = fgetcsv($in);

while($line = fgetcsv($in)){

    print_r($line);

    $wfo = trim($line[0]);
    if(!preg_match('/^wfo-[0-9]{10}$/', $wfo)) continue;

    $drupal_nid = $line[3];

    $response = $mysqli->query("UPDATE sub_entries SET wfo_id = '$wfo' WHERE drupal_nid = $drupal_nid;");
    if($mysqli->error){
        echo $mysqli->error;
        exit;
    }
}
fclose($in);