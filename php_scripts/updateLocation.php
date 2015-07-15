<?php
require_once('../config.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$observation_id = mysqli_real_escape_string($dbc, trim($_GET['observation_id']));
$locality = mysqli_real_escape_string($dbc, trim($_GET['locality']));
$administrative_area_level_2 = mysqli_real_escape_string($dbc, trim($_GET['administrative_area_level_2']));

$query = 'UPDATE observations SET loc_administrative_area_level_2="' . $administrative_area_level_2 . '", loc_locality="' . $locality . '"  WHERE observation_id=' . $observation_id;
echo $query;
echo 'result: ' . mysqli_query($dbc, $query);

?>