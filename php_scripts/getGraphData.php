<?php
header('Content-Type: text/xml');
header("Cache-Control: no-cache, must-revalidate");
//A date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require_once('../config.php');
require_once('tabular_data_functions.php');


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(!empty($_GET['constellation_id'])){
	$constellation_id = mysqli_real_escape_string($dbc, trim($_GET['constellation_id']));
}
else if(!empty($_GET['user_id'])){
	$user_id = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
}
else if(!empty($_GET['object_id'])){
	$object_id = mysqli_real_escape_string($dbc, trim($_GET['object_id']));
}


$query = 'SELECT COUNT(EXTRACT(MONTH FROM observations.observation_datetime)), EXTRACT(MONTH FROM observations.observation_datetime)
			FROM observations';
			
if(!empty($constellation_id)){
	$query .= ' INNER JOIN astronomical_objects
	USING (object_id)
	INNER JOIN constellations
	USING (constellation_id)
	WHERE constellation_id=' . $constellation_id;
}
else if(!empty($user_id)){
$query .= ' INNER JOIN users
	USING (user_id)
	WHERE user_id=' . $user_id;
}
else if(!empty($object_id)){
$query .= ' WHERE object_id=' . $object_id;
}
$query .= ' GROUP BY EXTRACT(MONTH FROM observation_datetime)';

$table_id = mysqli_query($dbc, $query);

$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" . "\n";
$xml .= "<months>" . "\n";
while($row = mysqli_fetch_array($table_id)){
	$xml .= '<month month="' . $row['EXTRACT(MONTH FROM observations.observation_datetime)'] . '" observation_count="' . $row['COUNT(EXTRACT(MONTH FROM observations.observation_datetime))'] . '"></month>' . "\n";
}

$xml .= "</months>";

echo $xml;
?>