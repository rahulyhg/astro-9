<?php
header('Content-Type: text/xml');
header("Cache-Control: no-cache, must-revalidate");
//A date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require_once('../config.php');
require_once('tabular_data_functions.php');


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$observation_id = mysqli_real_escape_string($dbc, trim($_GET['observation_id']));

$start_time = mysqli_real_escape_string($dbc, trim($_GET['start_time']));
$end_time = mysqli_real_escape_string($dbc, trim($_GET['end_time']));


$query = 'SELECT observations.observation_id, observations.object_id, observations.user_id, observations.latitude, observations.longitude, observations.accuracy, observations.observation_datetime, observations.notes,
			users.user_first_name, users.user_surname, users.user_avatar_maps_url,
			astronomical_objects.object_name, astronomical_objects.object_thumb_url, 
			astronomical_objects_types.object_type_name, astronomical_objects_types.object_type_thumb_url, astronomical_objects_types.object_type_thumb_maps_url,
			devices.device_name, devices.device_thumb_url, devices.device_thumb_maps_url,
			devices_types.device_type_name, devices_types.device_type_thumb_maps_url
			FROM observations
			INNER JOIN users
			USING (user_id)
			INNER JOIN astronomical_objects
			USING (object_id)
			INNER JOIN astronomical_objects_types
			USING (object_type_id)
			INNER JOIN devices
			USING (device_id)
			INNER JOIN devices_types
			USING (device_type_id)';
if(!empty($observation_id)){
	$query .= ' WHERE observation_id=' . $observation_id;
}
else if(!empty($start_time) && !empty($end_time)){
	$query .= ' WHERE observation_datetime >= "' . $start_time . '" AND observation_datetime <= "' . $end_time . '"';
}

$query .= ' ORDER BY observation_datetime ASC';

$table_id = mysqli_query($dbc, $query);

$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" . "\n";
$xml .= "<locations>" . "\n";
while($row = mysqli_fetch_array($table_id)){
	$xml .= '<location latitude="' . $row['latitude'] . '" longitude="' . $row['longitude'] . '" accuracy="' . $row['accuracy'] . '" datetime="' . $row['observation_datetime'] . '">' . "\n";
	$astronomical_object_name = return_astronomical_object_name($dbc, $row['object_id'], $row['object_name']);

	$xml .= '<object object_id="' . $row['object_id'] . '" object_name="' . $astronomical_object_name . '"';
	if(!empty($row['object_thumb_url'])){
		$xml .= ' object_thumb_url="' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $row['object_thumb_url'] . '"';
	}
	$xml .= ' object_type_name="' . ucfirst($row['object_type_name']) . '" object_type_thumb_url="' . $row['object_type_thumb_url'] . '" object_type_thumb_maps_url="' . $row['object_type_thumb_maps_url'] . '"></object>' . "\n";
	$xml .= '<user first_name="' . $row['user_first_name'] . '" last_name="' . $row['user_surname'] . '" avatar_url="' . $row['user_avatar_maps_url'] . '" magnification="' . return_magnification($dbc, $row['observation_id'], 'observation', strtolower($row['device_type_name'])) . '"></user>' . "\n";
	$xml .= '<device device_name="' . $row['device_name'] . '" thumb_url="' . $row['device_thumb_url'] . '"  thumb_maps_url="' . $row['device_thumb_maps_url'] . '" thumb_type_maps_url="' . $row['device_type_thumb_maps_url'] . '"></device>' . "\n";
	$xml .= '<notes>';
	if(!empty($row['notes'])){
		$text = preg_replace("/\r\n/", "\n", $row['notes']);
		
		$text_array = explode("\n\n", $text);
		
		foreach($text_array as $paragraph){
			$xml .= '<p>';
			$paragraph = str_replace("\n", '<br />', $paragraph);
			$xml .= trim($paragraph);
			$xml .= '</p>' . "\n";
		}
	}
	$xml .= '</notes>' . "\n";
	$xml .= '</location>' . "\n";
}

$xml .= "</locations>";

echo $xml;
?>