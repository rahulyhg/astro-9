<?php

function return_astronomical_objects_list($dbc, $sort_type, $sort_type_info){
	$return_data;
	
	if($_GET['mark'] && isset($_SESSION['user_id'])){
		$query = 'INSERT INTO users_objects_marked (user_id, object_id) VALUES (' . $_SESSION['user_id'] . ', '  . mysqli_real_escape_string($dbc, trim($_GET['mark'])) . ')';
		
		$result = mysqli_query($dbc, $query);
		
		if($result){
			echo '<p class="notification">The object has been succesfully <strong>marked</strong></p>' . "\n";
		}
	}
	
	if($_GET['unmark'] && isset($_SESSION['user_id'])){
		$query = 'DELETE FROM users_objects_marked WHERE user_id=' . $_SESSION['user_id'] . ' && marked_id='  . mysqli_real_escape_string($dbc, trim($_GET['unmark']));
		
		$result = mysqli_query($dbc, $query);
		
		if($result){
			echo '<p class="notification">The object has been succesfully <strong>unmarked</strong></p>' . "\n";
		}
	}
	
	if(!empty($_GET['sorting_column'])){
		$sorting_column = mysqli_real_escape_string($dbc, trim($_GET['sorting_column']));
	}
	else {
		$sorting_column = 'object_id';
	}
	if(!empty($_GET['sorting_order'])){
		$sorting_order = mysqli_real_escape_string($dbc, trim($_GET['sorting_order']));
	}
	else {
		$sorting_order = 'ASC';
	}

	$return_data .= '<table>' . "\n";
	$return_data .= '<tr>' . "\n";
	$return_data .= '<th>' . "\n";
	$return_data .= '<a href="?p=' . trim($_GET['p']);
	if(!empty($_GET['user_id'])){
		$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
	}
	else if(!empty($_GET['constellation_id'])){
		$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
	}
	$return_data .= '&amp;sorting_column=object_messier_number&amp;';
	if($sorting_column == 'object_messier_number' && $sorting_order == 'ASC'){
		$return_data .= 'sorting_order=DESC';
	}
	else {
		$return_data .= 'sorting_order=ASC';
	}
	$return_data .= '">Messier</a>' . "\n";
	$return_data .= '</th>' . "\n";
	$return_data .= '<th>' . "\n";
	$return_data .= '<a href="?p=' . trim($_GET['p']);
	if(!empty($_GET['user_id'])){
		$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
	}
	else if(!empty($_GET['constellation_id'])){
		$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
	}
	$return_data .= '&amp;sorting_column=object_ngc_number&amp;';
	if($sorting_column == 'object_ngc_number' && $sorting_order == 'ASC'){
		$return_data .= 'sorting_order=DESC';
	}
	else {
		$return_data .= 'sorting_order=ASC';
	}
	$return_data .= '">NGC</a>' . "\n";
	$return_data .= '</th>' . "\n";
	$return_data .= '<th>' . "\n";
	$return_data .= '<a href="?p=' . trim($_GET['p']);
	if(!empty($_GET['user_id'])){
		$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
	}
	else if(!empty($_GET['constellation_id'])){
		$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
	}
	$return_data .= '&amp;sorting_column=object_name&amp;';
	if($sorting_column == 'object_name' && $sorting_order == 'ASC'){
		$return_data .= 'sorting_order=DESC';
	}
	else {
		$return_data .= 'sorting_order=ASC';
	}
	$return_data .= '">Name</a>' . "\n";
	$return_data .= '</th>' . "\n";
	$return_data .= '<th>' . "\n";
	$return_data .= '<a href="?p=' . trim($_GET['p']);
	if(!empty($_GET['user_id'])){
		$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
	}
	else if(!empty($_GET['constellation_id'])){
		$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
	}
	$return_data .= '&amp;sorting_column=object_type_id&amp;';
	if($sorting_column == 'object_type_id' && $sorting_order == 'ASC'){
		$return_data .= 'sorting_order=DESC';
	}
	else {
		$return_data .= 'sorting_order=ASC';
	}
	$return_data .= '">Type</a>' . "\n";
	$return_data .= '</th>' . "\n";
	$return_data .= '<th>' . "\n";
	$return_data .= '<a href="?p=' . trim($_GET['p']);
	if(!empty($_GET['user_id'])){
		$return_data .= '&amp;user_id=' . trim($_GET['user_id']);
	}
	else if(!empty($_GET['constellation_id'])){
		$return_data .= '&amp;constellation_id=' . trim($_GET['constellation_id']);
	}
	$return_data .= '&amp;sorting_column=constellation_name&amp;';
	if($sorting_column == 'constellation_name' && $sorting_order == 'ASC'){
		$return_data .= 'sorting_order=DESC';
	}
	else {
		$return_data .= 'sorting_order=ASC';
	}
	$return_data .= '">Constellation</a>' . "\n";
	$return_data .= '</th>' . "\n";
	//$return_data .= '<th>Links</th>' . "\n";
	$return_data .= '<th>Observations</th>' . "\n";
	if(isset($_SESSION['user_id'])){
		$return_data .= '<th>Marked</th>' . "\n";
	}
	$return_data .= '</tr>' . "\n";


	//if(login_check()){
	//	$return_data .= '<th>Starred user_id' . $_SESSION['user_id'] . '</th>';
	//}
	
	require_once('php_scripts/function_observations_user_array.php');
	
	$observations_array = observations_user_array($dbc);
		
	switch($sorting_column){
		case 'constellation_name':
			$sorting_table = 'constellations';
			break;
		default:
			$sorting_table = 'astronomical_objects';
			break;
	}
	
	$query = 'SELECT astronomical_objects.object_id, astronomical_objects.object_messier_number, astronomical_objects.object_ngc_number, astronomical_objects.object_name, astronomical_objects.object_thumb_url, astronomical_objects.object_url_wikipedia, astronomical_objects_types.object_type_name, astronomical_objects_types.object_type_thumb_url, astronomical_objects_types.object_type_url_wikipedia, ';
	//astronomical_objects_lists.list_name, 
	$query .= 'constellations.constellation_id,  constellations.constellation_name, constellations.constellation_thumb_url, constellations.constellation_url_wikipedia
				FROM astronomical_objects
				INNER JOIN astronomical_objects_types
				USING (object_type_id) ';
				//LEFT JOIN astronomical_objects_lists_objects
				//USING (object_id)
				//LEFT JOIN astronomical_objects_lists
				//USING (list_id)
	$query .=	'INNER JOIN constellations
				USING (constellation_id)';
				
	switch($sort_type){
		case 'marked':
			$query .= ' INNER JOIN users_objects_marked
						USING (object_id)
						WHERE users_objects_marked.user_id =' . $sort_type_info;
			break;
		case 'constellation':
			$query .= ' WHERE constellations.constellation_id=' . $sort_type_info;
			break;
		case 'picture':
			$query .= ' INNER JOIN pictures_astronomical_objects
						USING (object_id)
						WHERE pictures_astronomical_objects.picture_id=' . $sort_type_info;
	}
	
	$query .= ' ORDER BY ' . $sorting_table . '.' . $sorting_column . ' ' . $sorting_order;
	
	$table_id = mysqli_query($dbc, $query);
	
	if(isset($_SESSION['user_id'])){
		$query_m = 'SELECT marked_id, object_id FROM users_objects_marked WHERE user_id=' . $_SESSION['user_id'];
		
		$table_id_m = mysqli_query($dbc, $query_m);
		
		$marked_objects = array();
		while($row_m = mysqli_fetch_array($table_id_m)){
			array_push($marked_objects, array($row_m['object_id'], $row_m['marked_id']));
		}
		//echo '<p class="notification">' . $query_m . ' - ' . mysqli_num_rows($table_id_m) . ' : ' . count($marked_objects) . '</p>';
	}
	
	while($row = mysqli_fetch_array($table_id)){
		$return_data .= '<tr>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_messier_number($row['object_messier_number']);
		$return_data .= '</td>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_ngc_number($row['object_ngc_number']);
		$return_data .= '</td>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_name_data($row['object_id'], $row['object_name'], $row['object_url_wikipedia'], $row['object_thumb_url']);
		$return_data .= '</td>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_object_type_data($row['object_type_name'], $row['object_type_url_wikipedia'], $row['object_type_thumb_url'], '');
		$return_data .= '</td>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_constellation_data($row['constellation_id'], $row['constellation_name'], $row['constellation_thumb_url'], $row['constellation_url_wikipedia']);
		$return_data .= '</td>' . "\n";
		//$return_data .= '<td>';
		//if(!empty($row['object_url_wikipedia'])){
		//	$return_data .= '<a href="' . $row['object_url_wikipedia'] . '"><img src="images/link_wiki.png" alt="wikipedia_link" /></a>';
		//}
		//$return_data .= '</td>' . "\n";
		$return_data .= '<td>';
		$return_data .= return_user_object_observation_data($observations_array, $row['object_id']);
		/*if(isset($observations_array) && !empty($observations_array[$row['object_id']])){
			for($i = 0; $i<count($observations_array[$row['object_id']]);$i++){
				echo '<div class="avatar" style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . SITE_AVATAR_DIR . $observations_array[$row['object_id']][$i][2] . '\');"><div class="observations">' . $observations_array[$row['object_id']][$i][1] . '</div></div>';	
				//echo 'user: ' . $observations_array[$row['object_id']][$i][0] . ' views: ' . $observations_array[$row['object_id']][$i][1];
			}
		}*/
		$return_data .= '</td>' . "\n";
		if(isset($_SESSION['user_id'])){
			$query_o = 'SELECT object_id FROM observations WHERE MONTH(observation_datetime) = MONTH(NOW()) AND object_id = ' . $row['object_id'];
			
			$table_id_o = mysqli_query($dbc, $query_o);
			
			$return_data .= '<td';
			if(mysqli_num_rows($table_id_o) > 0){
				$return_data .= ' class="now_visible"';
			}
			$return_data .= '>';
			$return_data .= return_user_marked_data($row['object_id'], $marked_objects);
			$return_data .= '</td>' . "\n";
		}
		$return_data .= '</tr>' . "\n";
	}
	
	$return_data .= '</table>';
	
	
	if(mysqli_num_rows($table_id) == 0 && $sort_type == 'marked'){
		return '<p>No objects marked</p>';
	}
	if(mysqli_num_rows($table_id) == 0){
		return false;
	}
	else {
		return $return_data;
}	}
?>