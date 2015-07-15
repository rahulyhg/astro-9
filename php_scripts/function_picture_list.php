<?php

function return_picture_list($dbc, $max_pictures, $id, $select_type){
	$return_data;
	
	$query = 'SELECT users.user_id, users.user_first_name, users.user_avatar_url, pictures.device_optical_id, pictures.device_camera_id, pictures.picture_id, pictures.picture_name, pictures.picture_thumb_url, pictures.picture_datetime, pictures.picture_entry_datetime
				FROM pictures
				INNER JOIN users
				USING (user_id)';
	if($select_type == 'user' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE user_id=' . $id;
	}
	else if($select_type == 'astronomical_object' && is_int(intval($id)) && $id > 0){
		$query .= ' INNER JOIN pictures_astronomical_objects
					USING (picture_id)
					WHERE object_id=' . $id;
	}
	else if($select_type == 'camera' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE device_camera_id=' . $id;
	}
	else if(($select_type == 'camera lens' || $select_type == 'telescope') && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE device_optical_id=' . $id;
	}
	$query .= ' ORDER BY pictures.picture_entry_datetime DESC';
	
	if(is_int($max_pictures)){
		$query .= ' LIMIT 0,' . $max_pictures;
	}
	
	$table_id = mysqli_query($dbc, $query);
	
	if(mysqli_num_rows($table_id) == 0){
		$return_data .= '<p>No pictures yet</p>';
	}
	
	$counter = 0;
	while($row = mysqli_fetch_array($table_id)){
		$counter ++;
		$return_data .= '<div class="picture" style="background-image: url(\'' . SITE_PICTURES_DIR . 'thumbs/' . $row['picture_thumb_url']  .'\')';
		$return_data .= ';">' . "\n";
		$return_data .= '<h3><a href="?p=picture&amp;picture_id=' . $row['picture_id'] . '" >' . $row['picture_name'] . '</a></h3>' . "\n";
		$return_data .= '<div class="information">' . "\n";
		$return_data .= return_user_observation_data($row['user_id'], $row['user_first_name'], $row['user_avatar_url'], 'label');
		if($row['device_optical_id'] > 0){
			$query = 'SELECT device_name, device_thumb_url FROM devices WHERE device_id=' . $row['device_optical_id'];
			
			$table_id_d = mysqli_query($dbc, $query);
			
			$row_d = mysqli_fetch_array($table_id_d);
			
			$return_data .= return_device_data($row['device_optical_id'], $row_d['device_name'], $row_d['device_thumb_url'], false);
		}
		
		if($row['device_camera_id'] > 0){
			$query = 'SELECT device_name, device_thumb_url FROM devices WHERE device_id=' . $row['device_camera_id'];
			
			$table_id_d = mysqli_query($dbc, $query);
			
			$row_d = mysqli_fetch_array($table_id_d);
			
			$return_data .= return_device_data($row['device_camera_id'], $row_d['device_name'], $row_d['device_thumb_url'], false);
		}
		$return_data .= '</div>' . "\n";
		$return_data .= '<p class="date">' . date('j', strtotime($row['picture_entry_datetime'])) . ' ' . strtolower(date('F', strtotime($row['picture_entry_datetime']))) . ' \'' . date('y', strtotime($row['picture_entry_datetime'])) . '</p>' . "\n";
		$return_data .= '<a class="picture_thumb_link" href="?p=picture&amp;picture_id=' . $row['picture_id'] . '"></a>' . "\n";
		$return_data .= '</div>' . "\n";
	}
	
	return $return_data;
}
?>