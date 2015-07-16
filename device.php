<?php
$device_id = mysqli_real_escape_string($dbc, trim($_GET['device_id']));

$query = 'SELECT devices.device_name, devices.device_thumb_url, devices_types.device_type_name
			FROM devices
			INNER JOIN devices_types
			USING (device_type_id)
			WHERE device_id =' . $device_id;

$table_id = mysqli_query($dbc, $query);

$row = mysqli_fetch_array($table_id);
?>
<h1><?php if(!empty($row['device_thumb_url'])){echo '<img src="' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $row['device_thumb_url'] . '" alt="thumb" /> ';} echo $row['device_name']; ?></h1>
<?php

switch(strtolower($row['device_type_name'])){
	case 'telescope':
		$device_type_name = $row['device_type_name'];
		$query = 'SELECT telescope_aperture, telescope_focal_length FROM devices_telescope WHERE device_id=' . $device_id;
		$table_id = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($table_id);
		$information_array['Aperture'] = $row['telescope_aperture'] . ' mm';
		$information_array['Focal length'] = $row['telescope_focal_length'] . ' mm';
		$information_array['Speed'] = 'f' . round($row['telescope_focal_length'] / $row['telescope_aperture'], 2);
		$device_purpose = 'observation&picture';
		break;
	case 'binocular':
		$query = 'SELECT binocular_magnification, binocular_aperture FROM devices_binocular WHERE device_id=' . $device_id;
		$table_id = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($table_id);
		$information_array['Magnification'] = $row['binocular_magnification'] . ' mm';
		$information_array['Aperture'] = $row['binocular_aperture'] . ' mm';
		$information_array['Exit pupil'] = round($row['binocular_aperture'] / $row['binocular_magnification'], 2) . ' mm';
		$device_purpose = 'observation';
		break;
	case 'eyepiece':
		$device_type_name = $row['device_type_name'];
		$query = 'SELECT eyepiece_focal_length, eyepiece_fov, eyepiece_barrel_size FROM devices_eyepiece WHERE device_id=' . $device_id;
		$table_id = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($table_id);
		$information_array['Focal length'] = $row['eyepiece_focal_length'] . ' mm';
		$information_array['Field of view'] = $row['eyepiece_fov'] . '&#176;';
		$information_array['Barrel size'] = $row['eyepiece_barrel_size'] . '&#148;';
		$device_purpose = 'observation';
		break;
	case 'filter':
		$device_type_name = $row['device_type_name'];
		$query = 'SELECT filter_barrel_size FROM devices_filter WHERE device_id=' . $device_id;
		$table_id = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($table_id);
		$information_array['Barrel size'] = $row['filter_barrel_size'] . '&#148;';
		$device_purpose = 'observation';
		break;
	case 'camera':
		$device_purpose = 'picture';
		$device_type_name = $row['device_type_name'];
		break;
	case 'camera lens':
		$device_purpose = 'picture';
		$device_type_name = $row['device_type_name'];
		break;
}

echo '<table class="list">';

if(isset($information_array)){
	foreach($information_array as $key => $value){
		echo '<tr>' . "\n" . '<td>' . $key . '</td>' . "\n" . '<td>' . $value . '</td>' . "\n" . '</tr>';
	}
}
?>
</table>

<?php
if($device_purpose == 'observation' || $device_purpose == 'observation&picture'){
	echo '<h2>Observations with this device</h2>' . "\n";
	require_once('php_scripts/function_observation_list.php');
	require_once('php_scripts/tabular_data_functions.php');
	if(strtolower($device_type_name) == 'eyepiece'){
		echo return_observation_list($dbc, null, $device_id, 'eyepiece_device');	
	}
	else if(strtolower($device_type_name) == 'filter'){
		echo return_observation_list($dbc, null, $device_id, 'filter_device');	
	}
	else {
		echo return_observation_list($dbc, null, $device_id, 'device');
	}
}
if($device_purpose == 'picture' || $device_purpose == 'observation&picture'){
	echo '<h2>Pictures with this device</h2>' . "\n";
	require_once('php_scripts/tabular_data_functions.php');
	require_once('php_scripts/function_picture_list.php');
	?>
	<div class="grid-overview">
		<?php
		echo return_picture_list($dbc, '', $device_id, strtolower($device_type_name));
		echo '<div class="clear"></div>' . "\n";
		?>
	</div>
	<?php
}
?>