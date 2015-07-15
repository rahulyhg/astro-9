<?php
//Information:
//Total observations
//Total unique observations
//Total observations bij object type
//Total objects
//Total objects by type

$user_id = trim($_GET['user_id']);

require_once('php_scripts/function_astronomical_objects_list.php');
require_once('php_scripts/tabular_data_functions.php');
require_once('php_scripts/login_functions.php');

//HTML Data
$html_data = array();
$html_data['objects_thumbs_by_type'];
//Number Data
$observation_data = array();
$observation_data['unique_observations'] = 0;
$observation_data['unique_observations_by_type'];

//FILL HTML Data
//##########################\\
// - ASTRONOMICAL_OBJECTS - \\
//##########################\\
$query = 'SELECT object';

//################################\\
// - ASTRONOMICAL_OBJECTS_TYPES - \\
//################################\\

$query_object_types = 'SELECT object_type_id, object_type_name, object_type_thumb_url, object_type_url_wikipedia FROM astronomical_objects_types';

$table_id_object_types = mysqli_query($dbc, $query_object_types);

while($row_ot = mysqli_fetch_array($table_id_object_types)){
	$query_no_objects = 'SELECT observations.object_id
							FROM observations
							INNER JOIN astronomical_objects
							USING (object_id)
							WHERE astronomical_objects.object_type_id=' . $row_ot['object_type_id'] . ' AND observations.user_id=' . $user_id;
	$table_id_no_objects = mysqli_query($dbc, $query_no_objects);
	$html_data['objects_thumbs_by_type'] .= '<div class="item">';
	$html_data['objects_thumbs_by_type'] .= return_object_type_data($row_ot['object_type_name'], $row_ot['object_type_url_wikipedia'], $row_ot['object_type_thumb_url'], mysqli_num_rows($table_id_no_objects));
	$html_data['objects_thumbs_by_type'] .= '</div>' . "\n";
	$observation_data['unique_observations_by_type'][$row_ot['object_type_id']]['seen'] = 0;
	$query_total_objects_by_type = 'SELECT object_type_id FROM astronomical_objects WHERE object_type_id=' .  $row_ot['object_type_id'];
	$table_id_total_objects_by_type = mysqli_query($dbc, $query_total_objects_by_type);
	$observation_data['unique_observations_by_type'][$row_ot['object_type_id']]['total'] = mysqli_num_rows($table_id_total_objects_by_type);
	//echo '<td>' . mysqli_num_rows($table_id_no_objects) . '</td>'; 
}

$query = 'SELECT user_first_name, user_surname, user_avatar_large_url FROM users WHERE users.user_id=' . $user_id;			

$table_id = mysqli_query($dbc, $query);

if(mysqli_num_rows($table_id) == 0){
	echo '<p>User doesn\'t exist</p>';
	return;
}

$row = mysqli_fetch_array($table_id);

?>
<h1><div class="avatar_user"><img src="<?php echo SITE_IMAGES_DIR_FRONT . SITE_AVATAR_DIR . $row['user_avatar_large_url'] . '" alt="user_avatar" />'; ?>
<div class="badges">
<?php 
$query_b = 'SELECT list_badge_url FROM astronomical_objects_lists';

$table_id_b = mysqli_query($dbc, $query_b);

while($row_b = mysqli_fetch_array($table_id_b)){
	echo '<div style="background-image: url(\'' . SITE_IMAGES_DIR_FRONT . $row_b['list_badge_url'] . '\')"></div>' . "\n";
}
?> 
</div></div>
<?php echo $row['user_first_name'];
if(isset($_SESSION['user_id'])){
echo ' ' . $row['user_surname'];
}
?></h1>
<?php
$query = 'SELECT DISTINCT users.user_first_name, users.user_surname, users.user_avatar_url, astronomical_objects.object_id, astronomical_objects.object_type_id, astronomical_objects.object_messier_number, astronomical_objects.object_thumb_url, astronomical_objects_types.object_type_thumb_url
			FROM users
			INNER JOIN observations
			USING (user_id)
			INNER JOIN astronomical_objects
			USING (object_id)
			INNER JOIN astronomical_objects_types
			USING (object_type_id)
			WHERE users.user_id=' . $user_id . ' ORDER BY astronomical_objects.object_id ASC';			

$table_id = mysqli_query($dbc, $query);
$observation_data['unique_observations_lists']['messier']['seen'] = 0;
$total_unique_observations = mysqli_num_rows($table_id);
while($row = mysqli_fetch_array($table_id)){
	$observation_data['unique_observations'] += 1;
	$observation_data['unique_observations_by_type'][$row['object_type_id']]['seen'] +=1;
	if($row['object_messier_number'] > 0){
		$observation_data['unique_observations_lists']['messier']['seen'] +=1;
	}
}

$query_fd = 'SELECT observations.device_id, devices.device_name, COUNT(device_id) FROM observations
				INNER JOIN devices
				USING (device_id)
				WHERE user_id=' . $user_id . '
				GROUP BY device_id
				ORDER BY COUNT(device_id) DESC';

$table_id_fd = mysqli_query($dbc, $query_fd);

$row = mysqli_fetch_array($table_id_fd);

?>
<script src="js/utils.js"></script>
<script src="js/obs_graph.js"></script>
<canvas id="observations_graph" width="500" height="250">
	<p>This browser doesn't know Canvas.</p>
</canvas>

<table class="list">
	<tr>
		<td>Total observations</td>
		<td>
		<?php 
		$query = 'SELECT object_id FROM observations WHERE user_id =' . $user_id;
		$table_id = mysqli_query($dbc, $query);
		echo mysqli_num_rows($table_id);
		?>
		</td>
	</tr>
	<tr>
		<td>Unique observations</td>
		<td><?php echo $total_unique_observations; ?></td>
	</tr>
	<tr>
		<td>Favourite observing device</td>
		<td><?php echo return_device_data($row['device_id'], $row['device_name'], null, null); ?></td>
	</tr>
	<tr>
		<td>Favourite month</td>
		<?php
		$query_fm = 'SELECT count(extract(month from observation_datetime)), extract(month from observation_datetime), observation_datetime FROM observations
				WHERE user_id=' . $user_id . '
				GROUP BY extract(month from observation_datetime)
				ORDER BY count(extract(month from observation_datetime)) DESC';
		
		$table_id_fm = mysqli_query($dbc, $query_fm);
		
		$row = mysqli_fetch_array($table_id_fm);
		?>
		<td><?php if(!empty($row['observation_datetime'])){ echo date('F', strtotime($row['observation_datetime']));} ?></td>
	</tr>
	<tr>
		<td>Favourite locality</td>
		<?php
		$query_fl = 'SELECT count(loc_locality), loc_locality FROM observations
				WHERE user_id=' . $user_id . '
				GROUP BY loc_locality
				ORDER BY count(loc_locality) DESC';
		
		$table_id_fl = mysqli_query($dbc, $query_fl);
		
		$row = mysqli_fetch_array($table_id_fl);
		?>
		<td><?php if(!empty($row['loc_locality'])){ echo $row['loc_locality'];}else{ echo 'unknown location';} ?></td>
	</tr>
</table>
<div class="type_observations">
	<?php
	echo $html_data['objects_thumbs_by_type'];
	?>
</div>
<!--<h2>Observation Library</h2>
<div id="library">
<?php
/*while($row = mysqli_fetch_array($table_id)){
	if(!empty($row['object_thumb_url'])){
		echo '<img src="' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $row['object_thumb_url'] .'" />';
	}
	else {
		echo '<img src="' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $row['object_type_thumb_url'] .'" />';
	}
}*/
?>
</div>-->
<h2>Observation Statistics</h2>
<?php
$query_total_objects = 'SELECT object_id FROM astronomical_objects';

$table_id_total_objects = mysqli_query($dbc, $query_total_objects);

$total_objects = mysqli_num_rows($table_id_total_objects);
?>
<table class="list statistics">
	<tr>
		<td>Total</td>
		<td><div class="stat_bar"><div style="width: <?php echo round($observation_data['unique_observations']/$total_objects*100, 2)?>%;"><span><?php echo $observation_data['unique_observations'] . '/' . $total_objects; ?></span></div></div></td>
	</tr>
	<?php
	$query = 'SELECT object_type_id, object_type_name FROM astronomical_objects_types';
	
	$table_id = mysqli_query($dbc, $query);
	
	while($row = mysqli_fetch_array($table_id)){
		if(substr($row['object_type_name'], -1) == 'y'){
			$object_type_name_plural = substr($row['object_type_name'], 0, strlen($row['object_type_name'])-1) . 'ies';
		}
		else {
			$object_type_name_plural = $row['object_type_name'] . 's';
		}
		echo '<tr>' . "\n" . '<td>Total ' . $object_type_name_plural . '</td>' . "\n";
		echo '<td><div class="stat_bar"><div style="width:' . round($observation_data['unique_observations_by_type'][$row['object_type_id']]['seen']/$observation_data['unique_observations_by_type'][$row['object_type_id']]['total']*100, 2) . '%;"><span>' . $observation_data['unique_observations_by_type'][$row['object_type_id']]['seen'] . '/' . $observation_data['unique_observations_by_type'][$row['object_type_id']]['total'] . '</span></div></div></td>' . "\n";
		echo '</tr>' . "\n";
	}
	/*echo '<td>' . count($observation_data['unique_observations_by_type']) . '</td><td>';
	for($i=0;$i<count($observation_data['unique_observations_by_type']);$i++){
		echo $observation_data['unique_observations_by_type'][$i];
	}*/
	?>
</table>
<h2>Observations Statistics by Lists</h2>
<table class="list statistics">
	<?php
	//$query = 'SELECT object_id FROM astronomical_objects WHERE object_messier_number > 0';
	$query = 'SELECT list_id, list_name FROM astronomical_objects_lists ORDER BY list_order_display ASC';
	
	$table_id = mysqli_query($dbc, $query);
	
	while($row = mysqli_fetch_array($table_id)){
		echo '<tr>' . "\n" . '<td>' . $row['list_name'] . ' list</td>';
		
		$query = 'SELECT DISTINCT list_object_number FROM astronomical_objects_lists_objects WHERE list_id=' . $row['list_id'];
		
		$table_id_inner = mysqli_query($dbc, $query);
		
		$total_obj_in_list = mysqli_num_rows($table_id_inner);
		
		$query = 'SELECT DISTINCT astronomical_objects_lists_objects.list_object_number
					FROM astronomical_objects_lists_objects
					INNER JOIN observations
					USING (object_id)
					WHERE astronomical_objects_lists_objects.list_id=' . $row['list_id'] . ' AND observations.user_id=' . $user_id;
		
		$table_id_inner = mysqli_query($dbc, $query);
		
		$total_seen_in_list = mysqli_num_rows($table_id_inner);
		
		echo '<td><div class="stat_bar"><div style="width:' . round($total_seen_in_list/$total_obj_in_list*100, 2) . '%;"><span>' . $total_seen_in_list . '/' . $total_obj_in_list . '</span></div></div></td>' . "\n";
		echo '</tr>' . "\n";
	}
	
	//echo '<tr>' . "\n" . '<td>Messier List</td>' . "\n";
	//echo '<td><div class="stat_bar"><div style="width:' . round($observation_data['unique_observations_lists']['messier']['seen']/mysqli_num_rows($table_id)*100, 2) . '%;"><span>' . $observation_data['unique_observations_lists']['messier']['seen'] . '/' . mysqli_num_rows($table_id) . '</span></div></div></td>' . "\n";
	//echo '</tr>' . "\n";
	/*echo '<td>' . count($observation_data['unique_observations_by_type']) . '</td><td>';
	for($i=0;$i<count($observation_data['unique_observations_by_type']);$i++){
		echo $observation_data['unique_observations_by_type'][$i];
	}*/
	?>
</table>
<h2>Recent Observations</h2>
<?php
require_once('php_scripts/function_observation_list.php');

echo return_observation_list($dbc, 5, $user_id, 'user');
?>
<h2>Recent Pictures</h2>
<div class="pictures">
<?php
require_once('php_scripts/function_picture_list.php');
echo return_picture_list($dbc, 3, $user_id, 'user');
?>
</div>
<h2>Recent Comments</h2>
<?php
require_once('php_scripts/function_comment_list.php');
echo return_comment_list($dbc, 3, 'picture', 'recent', $user_id, 'user', false);
?>
<h2>Marked Objects</h2>
<?php
echo return_astronomical_objects_list($dbc, 'marked', $user_id);
?>