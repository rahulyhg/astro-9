<?php
$observation_id = mysqli_real_escape_string($dbc, trim($_GET['observation_id']));

$query = 'SELECT observations.object_id, observations.level_id, observations.eyepiece_device_id, observations.filter_device_id, observations.loc_locality, observations.loc_administrative_area_level_2,
			observations.observation_datetime, observations.notes, observations_levels.level_description, astronomical_objects.object_magnitude, astronomical_objects.object_url_wikipedia,
			astronomical_objects_types.object_type_name, astronomical_objects_types.object_type_url_wikipedia,
			astronomical_objects_types.object_type_thumb_url, users.user_first_name, users.user_surname,
			devices.device_id, devices.device_name, devices.device_thumb_url, devices_types.device_type_name
			FROM observations
			INNER JOIN users
			USING (user_id)
			INNER JOIN astronomical_objects
			USING (object_id)
			INNER JOIN astronomical_objects_types
			USING (object_type_id)
			INNER JOIN observations_levels
			USING (level_id)
			INNER JOIN devices
			USING (device_id)
			INNER JOIN devices_types
			USING (device_type_id)
			WHERE observations.observation_id=' . $observation_id;

$table_id = mysqli_query($dbc, $query);

$row = mysqli_fetch_array($table_id);

$query_on = 'SELECT astronomical_objects.object_name, astronomical_objects_lists_objects.list_object_number, astronomical_objects_lists.list_name
			FROM astronomical_objects
			INNER JOIN astronomical_objects_lists_objects
			USING (object_id)
			INNER JOIN (astronomical_objects_lists)
			USING (list_id)
			WHERE astronomical_objects.object_id=' . $row['object_id'] . '
			ORDER BY astronomical_objects_lists.list_order_naming ASC';

$table_id_on = mysqli_query($dbc, $query_on);

if(mysqli_num_rows($table_id_on) == 0){
	$query_on = 'SELECT astronomical_objects.object_name FROM astronomical_objects WHERE astronomical_objects.object_id=' . $row['object_id'];

	$table_id_on = mysqli_query($dbc, $query_on);
}

$row_on = mysqli_fetch_array($table_id_on);

if(!empty($row_on['object_name'])){
	$object_name = $row_on['object_name'];
}
else {
	while($row_on['list_object_number'] == 0 && $row_on){
		$row_on = mysqli_fetch_array($table_id_on);
	}
	$object_name = $row_on['list_name'] . ' ' . $row_on['list_object_number'];
}

require_once('php_scripts/tabular_data_functions.php');
?>
<h1><?php echo return_date($row['observation_datetime'], null, null); echo $object_name . ' '; ?>
<?php
if(!empty($row['object_url_wikipedia'])){
	echo '<a href="' . $row['object_url_wikipedia'] . '"><img src="images/link_wiki.png" alt="wikipedia_link" /></a> ';
}
?>
<span>observation by <?php echo $row['user_first_name']; if(isset($_SESSION['user_id'])){ echo ' ' . $row['user_surname'];}?></span></h1>

<?php
if(isset($_SESSION['user_id'])){
?>
<!-- GOOGLE MAPS javascript API -->
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/location_observation_maps.js"></script>
<div id="location_observation">
</div>

<!-- GOOGLE MAPS END -->
<?php
}
?>

<table class="list">
	<tr>
		<td><?php echo ucfirst($row['device_type_name']);?></td>
		<td><?php echo return_device_data($row['device_id'], $row['device_name'], $row['device_thumb_url'], false)?></td>
	</tr>
	<?php
	if($row['eyepiece_device_id'] > 0){
		$query_e = 'SELECT device_id, device_name, device_thumb_url FROM devices WHERE device_id=' . $row['eyepiece_device_id'];
		
		$table_id_e = mysqli_query($dbc, $query_e);
		
		$row_e = mysqli_fetch_array($table_id_e);
	?>
	<tr>
		<td>Eyepiece</td>
		<td><?php echo return_device_data($row_e['device_id'], $row_e['device_name'], $row_e['device_thumb_url'], false)?></td>
	</tr>
	<?php }
	if($row['filter_device_id'] > 0){
		$query_f = 'SELECT device_id, device_name, device_thumb_url FROM devices WHERE device_id=' . $row['filter_device_id'];
		
		$table_id_f = mysqli_query($dbc, $query_f);
		
		$row_f = mysqli_fetch_array($table_id_f);
	?>
	<tr>
		<td>Filter</td>
		<td><?php echo return_device_data($row_f['device_id'], $row_f['device_name'], $row_f['device_thumb_url'], false)?></td>
	</tr>
	<?php }?>
	<tr>
		<td>Date & time</td>
		<td><?php echo return_date($row['observation_datetime'], null, 'line')?></td>
	</tr>
	<tr>
		<td>Magnification</td>
		<td><?php echo return_magnification($dbc, $observation_id, 'observation', strtolower($row['device_type_name']));?></td>
	</tr>
	<tr>
		<td>Level</td>
		<td><?php echo return_level_data($row['level_id'], $row['level_description'], null);?></td>
	</tr>
	<tr>
		<td>Type</td>
		<td><?php echo return_object_type_data($row['object_type_name'], $row['object_type_url_wikipedia'], $row['object_type_thumb_url'], '')?></td>
	</tr>
	<?php
	if($row['object_magnitude'] > 0){
	?>
	<tr>
		<td>Magnitude</td>
		<td><?php echo $row['object_magnitude'];?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
if(!empty($row['loc_administrative_area_level_2']) || !empty($row['loc_locality'])){
?>
<h3>Location</h3>
<table class="list">
	<?php if(!empty($row['loc_locality'])){ ?>
	<tr>
		<td>Locality:</td>
		<td><?php echo $row['loc_locality'];?></td>
	</tr>
	<?php
	}
	 if(!empty($row['loc_administrative_area_level_2'])){ ?>
	<tr>
		<td>Municipality, Department:</td>
		<td><?php echo $row['loc_administrative_area_level_2'];?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
}
?>

<h3>Notes</h3>
<?php
if(!empty($row['notes'])){
	echo format_text($row['notes'], '', 'description');
}
else {
	echo '<p class="description"><i>No notes</i></p>' . "\n";
}
?>