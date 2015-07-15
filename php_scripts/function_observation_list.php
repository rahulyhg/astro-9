<?php
if(isset($_SESSION['user_id'])){
?>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
<?php
}
function return_observation_list($dbc, $max_observations, $id, $select_type){
	$return_data;
	$return_data .= '<table>
	<tr>' . "\n" . '
		<th>Date</th>' . "\n" . '
		<th>Observed By</th>' . "\n" . '
		<th>Level</th>' . "\n" . '
		<th>Messier</th>' . "\n" . '
		<th>NGC</th>' . "\n" . '
		<th>Name</th>' . "\n";
	if($select_type != 'astronomical_object'){
		$return_data .= '<th>Type</th>' . "\n";
	}
	$return_data .= '<th>Device used</th>' . "\n";
		if(isset($_SESSION['user_id'])){
			$return_data .= '<th>Loc</th>' . "\n";
		}
		$return_data .='<!--<th>Links</th>-->' . "\n" . '
	</tr>' . "\n";
	
	$query = 'SELECT observations.observation_id, observations.observation_datetime, observations.level_id, observations.latitude, observations.longitude, observations.notes, astronomical_objects.object_id, astronomical_objects.object_name, astronomical_objects.object_thumb_url, astronomical_objects.object_url_wikipedia, astronomical_objects_types.object_type_name, astronomical_objects_types.object_type_thumb_url, astronomical_objects_types.object_type_url_wikipedia, users.user_id, users.user_first_name, users.user_surname, users.user_avatar_url, observations_levels.level_description, devices.device_id, devices.device_name, devices.device_thumb_url
			FROM observations
			INNER JOIN users
			USING (user_id)
			INNER JOIN astronomical_objects
			USING (object_id)
			INNER JOIN observations_levels
			USING (level_id)
			INNER JOIN devices
			USING (device_id)
			INNER JOIN astronomical_objects_types
			USING (object_type_id)';
	if($select_type == 'user' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE user_id=' . $id;
	}
	else if($select_type == 'device' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE device_id=' . $id;
	}
	else if($select_type == 'eyepiece_device' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE eyepiece_device_id=' . $id;
	}
	else if($select_type == 'filter_device' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE filter_device_id=' . $id;
	}
	else if($select_type == 'astronomical_object' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE object_id=' . $id;
	}
	$query .= ' ORDER BY observations.observation_datetime DESC';
	if(is_int($max_observations)){
		$query .= ' LIMIT 0,' . $max_observations;
	}
		
	$table_id = mysqli_query($dbc, $query);

	if(mysqli_num_rows($table_id) == 0){
		$return_data .= '<tr><td></td><td>No observations yet</td><td></td><td></td><td></td><td></td>';
		if($select_type != 'astronomical_object' && !is_int(intval($id)) && $id <= 0){
			$return_data .= '<td></td>';
		}
		if(isset($_SESSION['user_id'])){
			$return_data .= '<td></td>';
		}
		$return_data .='<td></td><td></td></tr>';
	}

	while($row = mysqli_fetch_array($table_id)){
		$return_data .=  '<tr>' . "\n";
		$return_data .=  '<td>';
		$return_data .=  return_date($row['observation_datetime'], $row['observation_id'], null);
		$return_data .=  '</td>' . "\n";
		$return_data .=  '<td>';
		$return_data .=  return_user_observation_data($row['user_id'], $row['user_first_name'], $row['user_avatar_url'], 'default');
		$return_data .=  '</td>' . "\n";
		$return_data .=  '<td>';
		$return_data .=  return_level_data($row['level_id'], $row['level_description'], $row['notes']);
		$return_data .=  '</td>' . "\n";
		$return_data .=  '<td>';
		$query = 'SELECT list_object_number FROM astronomical_objects_lists_objects WHERE list_id=1 AND object_id=' . $row['object_id'];
		$table_id_messier = mysqli_query($dbc, $query);
		$row_messier = mysqli_fetch_array($table_id_messier);
		$return_data .=  return_messier_number($row_messier['list_object_number']);
		$return_data .=  '</td>' . "\n";
		$return_data .=  '<td>';
		$query = 'SELECT list_object_number FROM astronomical_objects_lists_objects WHERE list_id=3 AND object_id=' . $row['object_id'];
		$table_id_ngc= mysqli_query($dbc, $query);
		$row_ngc = mysqli_fetch_array($table_id_ngc);
		$return_data .=  return_ngc_number($row_ngc['list_object_number']);
		$return_data .=  '</td>' . "\n";
		$return_data .=  '<td>';
		$return_data .=  return_name_data($row['object_id'], $row['object_name'], $row['object_url_wikipedia'], $row['object_thumb_url']);
		$return_data .=  '</td>' . "\n";
		if($select_type != 'astronomical_object'){
			$return_data .=  '<td>';
			$return_data .=  return_object_type_data($row['object_type_name'], $row['object_type_url_wikipedia'], $row['object_type_thumb_url'], '');
			$return_data .=  '</td>' . "\n";
		}
		$return_data .=  '<td>';
		$return_data .=  return_device_data($row['device_id'], $row['device_name'], $row['device_thumb_url'], false);
		$return_data .=  '</td>' . "\n";
		if(isset($_SESSION['user_id'])){
		$return_data .=  '<td>';
		$return_data .=  return_location_icon($row['latitude'], $row['longitude']);
		$return_data .=  '</td>' . "\n";
		}
		//echo '<td><a href="' . $row['object_url_wikipedia'] . '"><img src="images/link_wiki.png" /></a></td>' . "\n";
		$return_data .=  '</tr>' . "\n";
	}	
	$return_data .= '</table>' . "\n";
	
	return $return_data;
}
?>