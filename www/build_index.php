<?php

require_once('../config.php');

$mysqli->query("DROP TABLE IF EXISTS `search`;");

$mysqli->query("CREATE TABLE `search` (
		   `id` int NOT NULL AUTO_INCREMENT,
		   `nid` int DEFAULT NULL,
		   `kind` varchar(10) DEFAULT NULL,
		   `title` varchar(255) DEFAULT NULL,
		   `body` text,
		   PRIMARY KEY (`id`),
		   FULLTEXT KEY `search` (`body`)
		 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

  
$response = $mysqli->query("SELECT * FROM entries ORDER BY entry_number ASC;");
$entries = $response->fetch_all(MYSQLI_ASSOC);
$response->close();

foreach ($entries as $entry) {
    
    echo "Processing {$entry['entry_number']}\n";

    $txt = $entry['title'];
    $txt .= ' ';
    $txt .= $entry['verbatim'];
    $txt .= ' ';
    $txt .= $entry['notes'];
    $txt .= ' ';
    $txt .= $entry['taxon_name'];
    $txt .= ' ';
    $txt .= $entry['author_name'];

    $txt = strip_tags($txt);
    $txt = $mysqli->real_escape_string($txt);

    $safe_title = $mysqli->real_escape_string($entry['title']);
    $sql = "INSERT INTO search (nid, kind, title, body) VALUES ({$entry['entry_number']}, 'entry' ,'$safe_title', '$txt' );";

    $mysqli->query($sql);

    // now do the sub_entries
    $response = $mysqli->query("SELECT * FROM sub_entries WHERE entry_number = {$entry['entry_number']} ");
    $rows = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();

    $current_nid = -1;
    $sub_rows = null;
    foreach ($rows as $row) {
        if($row['drupal_nid'] != $current_nid){
            // we are starting a new sub_entry
            if($sub_rows) save_sub($sub_rows); // save the last one if it has values
            $sub_rows = array();
            $current_nid = $row['drupal_nid'];
        }
        $sub_rows[] = $row;
    }
    save_sub($sub_rows); // save the last one

}

function save_sub($sub_rows){

    global $mysqli;
    
    echo "\t{$sub_rows[0]['entry_number_qualifier']}\n";

    // first we do the common values
    $txt = $sub_rows[0]['title'];
    $txt .= ' ';
    $txt .= $sub_rows[0]['verbatim'];
    $txt .= ' ';
    $txt .= $sub_rows[0]['notes'];
    $txt .= ' ';
    $txt .= $sub_rows[0]['taxon_name'];
    $txt .= ' ';
    $txt .= $sub_rows[0]['author_name'];
    $txt .= ' ';
    $txt .= $sub_rows[0]['year'];

    // fixme - add locations and collectors
    $location_tids = array();
    $collector_tids = array();
    foreach ($sub_rows as $sub) {
        $location_tids[] = $sub['location_tid'];
        $collector_tids[] = $sub['collector_tid'];
    }

    $location_tids = array_filter($location_tids);
    $collector_tids = array_filter($collector_tids);

    if($location_tids){
        $response = $mysqli->query(
            "SELECT * FROM gazetteer WHERE tid in ("
            . implode(',' , $location_tids) 
            . ");"
        );
        $locations = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();
        foreach($locations as $location){
            $txt .= ' ';
            $txt .= $location['name'];
            $txt .= ' ';
            $txt .= $location['description'];
        }
    }
    
    if($collector_tids){
        $response = $mysqli->query("SELECT * FROM collectors WHERE tid in (". implode(',' , $collector_tids) .");");
        $collectors = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();
        foreach($collectors as $collector){
            $txt .= ' ';
            $txt .= $collector['name'];
            $txt .= ' ';
            $txt .= $collector['description'];
        }
    }

    $txt = strip_tags($txt);
    $txt = $mysqli->real_escape_string($txt);

    $safe_title = $mysqli->real_escape_string($sub_rows[0]['title']);
    $sql = "INSERT INTO search (nid, kind, title, body) VALUES ({$sub_rows[0]['drupal_nid']}, 'sub_entry' ,'$safe_title', '$txt' );";

    $mysqli->query($sql);


}