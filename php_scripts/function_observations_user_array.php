<?php
function observations_user_array($dbc){
 $query_observations = 'SELECT observations.object_id, observations.level_id, observations.user_id, users.user_avatar_url
							FROM observations
							INNER JOIN users
							USING (user_id)
							ORDER BY object_id, user_id ASC';
	
	$table_id_observations = mysqli_query($dbc, $query_observations);

	$current_object_id = 0;
	$prev_object_id = 0;
	$current_user_id = 0;
	$prev_user_id = 0;
	$prev_level_id = 0;
	$current_max_level_id = 0;
	$current_observation_count = 0;
	$observations_user_array = array();
	$observations_array = array();
	
	while($row = mysqli_fetch_array($table_id_observations)){
		$current_object_id = $row['object_id'];
		$current_user_id = $row['user_id'];
		$current_level_id = $row['level_id'];
		
		if($prev_object_id == 0){
			$prev_object_id = $current_object_id;
		}
		if($prev_user_id == 0){
			$prev_user_id = $current_user_id;
		}
		if($prev_level_id == 0){
			$prev_level_id = $current_level_id;
			}
	
		if($current_user_id != $prev_user_id || ($current_object_id != $prev_object_id && $current_user_id == $prev_user_id)){
			//echo $pev_user_id . ' - ' . $current_observation_count . ' - ' . $prev_avatar_url . ' - ' . $current_max_level_id . '(' . $row['level_id'] . ')<br />';
			array_push($observations_user_array, array($prev_user_id, $current_observation_count, $prev_avatar_url, $current_max_level_id));
			$current_observation_count = 1;
			$current_max_level_id = 0;
		}
		else {
			$current_observation_count += 1;
			$prev_level_id = $current_level_id;
		}
		
		if($current_object_id != $prev_object_id){
			$observations_array[$prev_object_id] = $observations_user_array;
			$observations_user_array = '';
			$observations_user_array = array();
		}
		
		if($current_level_id >= $prev_level_id){
			$current_max_level_id = $current_level_id;
		}
		
		$prev_object_id = $current_object_id;
		$prev_user_id = $current_user_id;
		$prev_avatar_url = $row['user_avatar_url'];
	}
	
	//echo $pev_user_id . ' - ' . $current_observation_count;
	array_push($observations_user_array, array($prev_user_id, $current_observation_count, $prev_avatar_url, $current_max_level_id));
	
	//echo ' laatste ' . $prev_object_id . ' - ' . count($observations_user_array);
	$observations_array[$prev_object_id] = $observations_user_array;
	
	return $observations_array;
}
?>