<?php

function return_device_list($dbc, $max_devices, $user_id){
	$return_data;
	
	$query = 'SELECT devices.device_id, devices.device_name, devices.device_thumb_url, devices.device_large_thumb_url, devices_types.device_type_name, devices_types.device_type_thumb_url, devices_types.device_type_url_wikipedia
				FROM devices
				INNER JOIN devices_types
				USING (device_type_id)';
				//INNER JOIN users
				//USING (user_id)';
	//if(is_int(intval($user_id)) && $user_id > 0){
	//	$query .= ' WHERE user_id=' . $user_id;
	//}
	$query .= ' ORDER BY devices_types.device_type_id ASC';
	
	if(is_int($max_pictures)){
		$query .= ' LIMIT 0,' . $max_devices;
	}
	//echo $query;
	$table_id = mysqli_query($dbc, $query);
	
	if(mysqli_num_rows($table_id) == 0){
		$return_data .= '<p>No devices yet</p>';
	}
	
	$counter = 0;
	while($row = mysqli_fetch_array($table_id)){
		$counter ++;
		$return_data .= '<div class="picture" style="background-image: url(\'images/' . SITE_LARGE_THUMB_DIR . $row['device_large_thumb_url']  .'\')';
		if(is_int($counter / 3)){
			$return_data .= '; margin-right: 0px';
		}
		$return_data .= ';">' . "\n";
		$return_data .= '<h3><a href="?p=device&amp;device_id=' . $row['device_id'] . '" >' . $row['device_name'] . '</a></h3>' . "\n";
		$return_data .= '<div class="information">' . "\n";
		$return_data .= return_device_type_data($row['device_type_name'], $row['device_type_thumb_url'], $row['device_type_url_wikipedia'], false);
		if($row['device_optical_id'] > 0){
			$query = 'SELECT device_name, device_thumb_url FROM devices WHERE device_id=' . $row['device_optical_id'];
			
			$table_id_d = mysqli_query($dbc, $query);
			
			$row_d = mysqli_fetch_array($table_id_d);
			
			//$return_data .= return_device_data($row_d['device_name'], $row_d['device_thumb_url'], false);
		}
		
		if($row['device_camera_id'] > 0){
			$query = 'SELECT device_name, device_thumb_url FROM devices WHERE device_id=' . $row['device_camera_id'];
			
			$table_id_d = mysqli_query($dbc, $query);
			
			$row_d = mysqli_fetch_array($table_id_d);
			
			//$return_data .= return_device_data($row_d['device_name'], $row_d['device_thumb_url'], false);
		}
		$return_data .= '</div>' . "\n";
		$return_data .= '<a class="picture_thumb_link" href="?p=device&amp;device_id=' . $row['device_id'] . '"></a>' . "\n";
		$return_data .= '</div>' . "\n";
	}
	
	return $return_data;
}
?>