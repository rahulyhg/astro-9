<?php
function return_ngc_number($ngc_number){
	$return_data;
	if(empty($ngc_number) || $ngc_number == 0){
		$return_data = '-';	
	}
	else {
		$return_data = $ngc_number;
	}
	return $return_data;
}

function return_messier_number($messier_number){
	$return_data;
	if(empty($messier_number) || $messier_number == 0){
		$return_data = '-';	
	}
	else {
		$return_data = $messier_number;
	}
	return $return_data;
}

function return_object_type_data($type_name, $type_wiki_url, $type_thumb_url, $type_count){
	$return_data;
	if(!empty($type_thumb_url)){
		$return_data = '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $type_thumb_url . '\')">';
		if(is_int($type_count)){
			$return_data .= '<div class="observations">' . $type_count . '</div>';
		}
		$return_data .= '<a href="' . $type_wiki_url . '" >' . $type_name . '</a></div>' . "\n";
	}
	else {
		$return_data = '<a href="' . $type_wiki_url . '" >';
		if(is_int($type_count)){
		$return_data .= '<span class="observations">' . $type_count . '</span>'; 
		}
		$return_data .= $type_name . '</a>' . "\n";
	}
	return $return_data;
}

function return_name_data($object_id, $type_name, $type_wiki_url, $type_thumb_url){
	$return_data;
	if(!empty($type_thumb_url) && !empty($type_name)){
		$return_data = '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $type_thumb_url . '\')"><a href="?p=astronomical_object&amp;object_id=' . $object_id . '" >' . $type_name . '</a></div>' . "\n";
	}
	else if(!empty($type_name)){
		$return_data = '<a href="?p=astronomical_object&amp;object_id=' . $object_id . '" >' . $type_name . '</a>' . "\n";
	}
	else {
		$return_data = '<a href="?p=astronomical_object&amp;object_id=' . $object_id . '" >no name</a>' . "\n";
	}
	/*else if(!empty($type_wiki_url)){
		$return_data = '<a href="' . $type_wiki_url . '" >' . $type_name . '</a>' . "\n";
	}
	else {
		$return_data = $type_name;
	}*/
	return $return_data;
}

function return_user_object_observation_data($observations_array, $object_id){
	if(isset($observations_array) && !empty($observations_array[$object_id])){
		$return_data;
		for($i = 0; $i<count($observations_array[$object_id]);$i++){
			$return_data .= '<div class="avatar" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_AVATAR_DIR . $observations_array[$object_id][$i][2] . '\');"><a href="?p=user&amp;user_id=' . $observations_array[$object_id][$i][0] . '"><div class="observations">' . $observations_array[$object_id][$i][1] . '</div></a></div>';
			//echo 'user: ' . $observations_array[$row['object_id']][$i][0] . ' views: ' . $observations_array[$row['object_id']][$i][1];
		}
		return $return_data;
	}
}

function return_user_observation_data($user_id, $user_first_name, $user_avatar_url, $style){
	$return_data;
	switch($style){
		case 'label':
			$return_data = '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_AVATAR_DIR . $user_avatar_url . '\');"><a class="label" href="?p=user&amp;user_id=' . $user_id . '">' . $user_first_name . '</a></div>';
			break;
		default:
			$return_data = '<div class="avatar" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_AVATAR_DIR . $user_avatar_url . '\');"><a href="?p=user&amp;user_id=' . $user_id . '"></a></div><a href="?p=user&amp;user_id=' . $user_id . '">' . $user_first_name . '</a>';
			break;
	}
	return $return_data;
}

function return_date($date_data, $observation_id, $display){
	if(!empty($observation_id)){
		$return_data = '<a class="date" href="?p=observation&amp;observation_id=' . $observation_id . '"><div class="month">' . date('F', strtotime($date_data)) . '</div><div class="day">' . date('j', strtotime($date_data)) . '</div></a>';
	}
	else if($display == 'line'){
		$return_data = date('j', strtotime($date_data)) . ' ' . date('F', strtotime($date_data)) . ' ' . date('Y', strtotime($date_data)) . ' (' . date('H', strtotime($date_data)) . ':' . date('i', strtotime($date_data)) . ')';
	}
	else {
		$return_data = '<div class="date"><div class="month">' . date('F', strtotime($date_data)) . '</div><div class="day">' . date('j', strtotime($date_data)) . '</div></div>';
	}
	
	return $return_data;
}


function return_device_data($device_id, $device_name, $device_thumb_url, $overlay){
	$return_data;
	if(!empty($device_thumb_url)){
		$return_data .= '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $device_thumb_url . '\')">';
		if($overlay == true){
			$return_data .= '<a class="overlay" href="?p=device&amp;device_id=' . $device_id . '" ></a></div>' . "\n";
		}
		else {
			$return_data .= '<a href="?p=device&amp;device_id=' . $device_id . '" >' . $device_name . '</a></div>' . "\n";
		}
	}
	else {
		$return_data = '<a href="?p=device&amp;device_id=' . $device_id . '" >' . $device_name . '</a>' . "\n";
	}
	return $return_data;
}

function return_device_type_data($device_type_name, $device_type_thumb_url, $device_type_url_wikipedia, $overlay){
	$return_data;
	if(!empty($device_type_thumb_url)){
		$return_data .= '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $device_type_thumb_url . '\')">';
		if($overlay == true){
			$return_data .= '<a class="overlay" href="' . $device_type_url_wikipedia . '" ></a></div>' . "\n";
		}
		else {
			$return_data .= '<a href="' . $device_type_url_wikipedia . '" >' . $device_type_name . '</a></div>' . "\n";
		}
	}
	else {
		$return_data = '<a href="' . $device_type_url_wikipedia . '" >' . $device_type_name . '</a>' . "\n";
	}
	return $return_data;
}

function return_level_data($level_id, $level_description, $notes){
	$return_data = '<div class="level_' . $level_id . '" >' . $level_id . '<div class="description"><p>' . $level_description;
	if(!empty($notes)){
		$return_data .= '</p>' . "\n" . '<h4>Notes</h4>' . "\n";
		
		$text = preg_replace("/\r\n/", "\n", $notes);
	
		$text_array = explode("\n\n", $text);
		
		foreach($text_array as $paragraph){
			$return_data .= '<p>' . "\n";
			$paragraph = str_replace("\n", '<br />', $paragraph);
			$return_data .= trim($paragraph);
			$return_data .= '</p>' . "\n";
		}
	}
	else {
		$return_data .= '</p>' . "\n";
	}
	$return_data .= '</div></div>';
	return $return_data;
}

function return_constellation_data($constellation_id, $constellation_name, $constellation_thumb_url, $constellation_url_wikipedia){
	if(!empty($constellation_thumb_url) && !empty($constellation_url_wikipedia)){
		$return_data = '<div class="thumb" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $constellation_thumb_url . '\')"><a href="?p=constellation&amp;constellation_id=' . $constellation_id . '">' . $constellation_name . '</a></div>' . "\n";
	}
	else if(!empty($constellation_url_wikipedia)){
		$return_data = '<a href="?p=constellation&amp;constellation_id=' . $constellation_id . '">' . $constellation_name . '</a> <a href="' . $constellation_url_wikipedia . '"><img src="images/link_wiki.png" alt="wikipedia_link" /><a>';
	}
	else if(!empty($constellation_name)){
		$return_data = $constellation_name;
	}
	else {
		$return_data = '-';
	}
	return $return_data;
}

function return_location_icon($latitude, $longitude){
	$return_data;
	if(!empty($latitude) && ceil($latitude) != 0 && !empty($longitude) && ceil($longitude) != 0){
		$return_data = '<div class="location">' . "\n";
		$return_data .= '<img class="map" src="http://maps.google.com/maps/api/staticmap?center=' . $latitude . ',' . $longitude . '&zoom=14&size=200x200&maptype=roadmap&markers=color:red%7Csize:tiny%7Clabel:S%7C' . $latitude .',' . $longitude . '&sensor=false" />' . "\n";
		$return_data .= '<a href="http://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '">' . "\n";
		$return_data .= '<img src="images/link_maps.png" />' . "\n" . '</a>' . "\n" . '</div>' . "\n";
		//$return_data = '<a href="http://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '"><img src="http://maps.google.com/maps/api/staticmap?center=' . $latitude . ',' . $longitude . '&zoom=14&size=40x40&maptype=roadmap&markers=color:red%7Csize:tiny%7Clabel:S%7C' . $latitude .',' . $longitude . '&sensor=false" /></a>' . "\n";
	}
	return $return_data;
}

function return_user_marked_data($object_id, $marked_objects){
	$return_data;
	foreach($marked_objects as $object_id_marked_id){
		if($object_id == $object_id_marked_id[0]){
			$return_data = '<a class="marked" href="?p=' . $_GET['p'];
			if(!empty($_GET['user_id'])){
				$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
			}
			else if(!empty($_GET['constellation_id'])){
				$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
			}
			else if(!empty($_GET['object_id'])){
				$return_data .= '&amp;object_id=' . trim($_GET['object_id']);
			}
			$return_data .= '&unmark=' . $object_id_marked_id[1] . '"></a>' . "\n";
		}
	}
	if(empty($return_data)){
		$return_data = '<a class="mark" href="?p=' . $_GET['p'];
		if(!empty($_GET['user_id'])){
			$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
		}
		else if(!empty($_GET['constellation_id'])){
			$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
		}
		else if(!empty($_GET['object_id'])){
			$return_data .= '&amp;object_id=' . trim($_GET['object_id']);
		}
		$return_data .= '&mark=' . $object_id . '"></a>' . "\n";
	}
	return $return_data;
}

function format_text($text, $substring_length, $class){
	$text = preg_replace("/\r\n/", "\n", $text);
			
	if(strlen($text) > $substring_length && is_int($substring_length)){
		$text = substr($text, 0, $substring_length);
		$text = trim($text) . '..';
	}
	
	$text_array = explode("\n\n", $text);
	
	foreach($text_array as $paragraph){
		$return_data .= '<p';
		if(!empty($class)){
			$return_data .= ' class="' . $class .'"';
		}
		$return_data .= '>' . "\n";
		$paragraph = str_replace("\n", '<br />', $paragraph);
		$return_data .= trim($paragraph);
		$return_data .= '</p>' . "\n";
	}
	return $return_data;
}

function return_magnification($dbc, $id, $type, $device_type){
	switch($device_type){
		case 'all':
		case 'telescope';
			$query = 'SELECT devices_telescope.telescope_focal_length, devices_eyepiece.eyepiece_focal_length
						FROM observations
						INNER JOIN devices_telescope
						USING (device_id)
						INNER JOIN devices_eyepiece
						ON (observations.eyepiece_device_id = devices_eyepiece.device_id)';
			if($type == 'average' && is_int(intval($id)) && $id > 0){		
				$query .= 'WHERE object_id=' . $id;			
			}
			else if($type == 'observation' && is_int(intval($id)) && $id > 0){		
				$query .= 'WHERE observation_id=' . $id;			
			}
			//echo $query;
			$table_id = mysqli_query($dbc, $query);
	
			$magnification_array = Array();
			while($row = mysqli_fetch_array($table_id)){
				array_push($magnification_array, $row['telescope_focal_length'] / $row['eyepiece_focal_length']);
			}
			
			if(mysqli_num_rows($table_id) > 0){
				$return_data = round(array_sum($magnification_array) / count($magnification_array), 1) . 'x';
			}
			else {
				$return_data = '-';
			}
		break;
		case 'binocular';
			$query = 'SELECT devices_binocular.binocular_magnification
						FROM observations
						INNER JOIN devices_binocular
						USING (device_id)';
			if($type == 'average' && is_int(intval($id)) && $id > 0){		
				$query .= 'WHERE object_id=' . $id;			
			}
			else if($type == 'observation' && is_int(intval($id)) && $id > 0){		
				$query .= 'WHERE observation_id=' . $id;			
			}
			$table_id = mysqli_query($dbc, $query);
	
			$magnification_array = Array();
			while($row = mysqli_fetch_array($table_id)){
				array_push($magnification_array, $row['binocular_magnification']);
			}
			
			if(mysqli_num_rows($table_id) > 0){
				$return_data = round(array_sum($magnification_array) / count($magnification_array), 1) . 'x';
			}
			else {
				$return_data = '-';
			}
		break;
	}
	//$return_data = $query;
	
	
	return $return_data;
}

function return_astronomical_object_name($dbc, $object_id, $object_name){

	if(!empty($object_name)){
		$return_data = $object_name;
	}
	else {
		$query = 'SELECT astronomical_objects_lists_objects.list_object_number, astronomical_objects_lists.list_id, astronomical_objects_lists.list_name
				FROM astronomical_objects
				INNER JOIN astronomical_objects_lists_objects
				USING (object_id)
				INNER JOIN (astronomical_objects_lists)
				USING (list_id)
				WHERE astronomical_objects.object_id=' . $object_id . '
				ORDER BY astronomical_objects_lists.list_order_naming ASC';
	
		$table_id = mysqli_query($dbc, $query);
		
		$row = mysqli_fetch_array($table_id);
		
		//while($row['list_object_number'] == 0){
		//	$row = mysqli_fetch_array($table_id);
		//}
		$return_data = $row['list_name'] . ' ' . $row['list_object_number'];
	}
	return $return_data;
}
?>