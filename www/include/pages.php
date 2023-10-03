<?php

    // which page are we looking at
    $page_id = @$_GET['id'];
    if(!$page_id) $page_id = 1031; // fixme

    // mysql to get the page
    $page_id_safe = $mysqli->real_escape_string($page_id);
    $response = $mysqli->query("SELECT p.*, f.filename
                FROM pages AS p 
                JOIN files as f on f.fid = p.image_fid 
                WHERE p.nid = $page_id_safe");
    $rows = $response->fetch_all(MYSQLI_ASSOC);
    if(count($rows) != 1){
        echo "Failed to fetch page with nid = $page_id";
        exit;
    }
    
    $image_url = 'files/'. $rows[0]['filename'];


    echo "<img src=\"$image_url\" />";


?>

Page here...