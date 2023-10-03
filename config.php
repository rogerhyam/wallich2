<?php

// db connection kept out of github
require_once('../../wallich_secrets.php');

// create and initialise the database connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);  

// connect to the database
if ($mysqli->connect_error) {
  echo $mysqli->connect_error;
}

if (!$mysqli->set_charset("utf8mb4")) {
  echo printf("Error loading character set utf8: %s\n", $mysqli->error);
}