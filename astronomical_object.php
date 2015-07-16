<?php
if($_GET['mark'] && isset($_SESSION['user_id'])){
	$query = 'INSERT INTO users_objects_marked (user_id, object_id) VALUES (' . $_SESSION['user_id'] . ', '  . mysqli_real_escape_string($dbc, trim($_GET['mark'])) . ')';
		
	$result = mysqli_query($dbc, $query);

	if($result){
		echo '<p class="notification">The object has been succesfully <strong>marked</strong></p>' . "\n";
	}
}

if($_GET['unmark'] && isset($_SESSION['user_id'])){
	$query = 'DELETE FROM users_objects_marked WHERE user_id=' . $_SESSION['user_id'] . ' && object_id='  . mysqli_real_escape_string($dbc, trim($_GET['unmark']));
	
	$result = mysqli_query($dbc, $query);

	if($result){
		echo '<p class="notification">The object has been succesfully <strong>unmarked</strong></p>' . "\n";
	}
}

$object_id = mysqli_real_escape_string($dbc, trim($_GET['object_id']));

$query = 'SELECT astronomical_objects.object_name, astronomical_objects.object_thumb_url, astronomical_objects.object_url_wikipedia, astronomical_objects.object_magnitude, astronomical_objects.object_distance, astronomical_objects_types.object_type_name, astronomical_objects_types.object_type_url_wikipedia, astronomical_objects_types.object_type_thumb_url, constellations.constellation_id, constellations.constellation_name, constellations.constellation_thumb_url, constellations.constellation_url_wikipedia
		FROM astronomical_objects
		INNER JOIN astronomical_objects_types
		USING (object_type_id)
		INNER JOIN constellations
		USING (constellation_id)
		WHERE astronomical_objects.object_id=' . $object_id;
	
$table_id = mysqli_query($dbc, $query);

$row = mysqli_fetch_array($table_id);

$astronomical_object_name = return_astronomical_object_name($dbc, $object_id, $row['object_name']);
?>

<h1><?php if(!empty($row['object_thumb_url'])){ echo '<img alt="thumb" src="' . SITE_IMAGES_DIR_FRONT . SITE_THUMB_DIR . $row['object_thumb_url'] . '"" /> ';}
echo $astronomical_object_name;
if(!empty($row['object_url_wikipedia'])){
	echo ' <a href="' . $row['object_url_wikipedia'] . '"><img src="images/link_wiki.png" alt="wikipedia_link" /></a>';
}
?></h1>
<script src="js/utils.js"></script>
<script src="js/obs_graph.js"></script>
<canvas id="observations_graph" width="500" height="250">
	<p>This browser doesn't know Canvas.</p>
</canvas>

<?php
	require_once('php_scripts/function_observations_user_array.php');
	
	$observations_array = observations_user_array($dbc);
?>

<table class="list">
	<tr>
		<td>Type</td>
		<td><?php echo return_object_type_data($row['object_type_name'], $row['object_type_url_wikipedia'], $row['object_type_thumb_url'], '')?></td>
	</tr>
	<?php if($row['constellation_id'] > 0){ ?>
	<tr>
		<td>Constellation</td>
		<td><?php echo return_constellation_data($row['constellation_id'], $row['constellation_name'], $row['constellation_thumb_url'], $row['constellation_url_wikipedia']);?></td>
	</tr>
	<?php }
	if($row['object_magnitude'] > 0){ ?>
	<tr>
		<td>Magnitude</td>
		<td><?php echo $row['object_magnitude'];?></td>
	</tr>
	<?php }
	if($row['object_distance'] > 0){ ?>
	<tr>
		<td>Distance</td>
		<td><?php echo number_format($row['object_distance'], 0, ',', '.') . ' ly';?></td>
	</tr>
	<?php } ?>
	<tr>
		<td>Observed by</td>
		<td><?php
			$user_object_observation_data = return_user_object_observation_data($observations_array, $object_id);
			if(!empty($user_object_observation_data)){
				echo $user_object_observation_data;
			}
			else {
				echo 'not observed yet';
			}
		?></td>
	</tr>
	<?php
	if(isset($_SESSION['user_id'])){
		$query_m = 'SELECT marked_id, object_id FROM users_objects_marked WHERE user_id=' . $_SESSION['user_id'];
		
		$table_id_m = mysqli_query($dbc, $query_m);
		
		$marked_objects = array();
		while($row_m = mysqli_fetch_array($table_id_m)){
			array_push($marked_objects, array($row_m['object_id'], $row_m['marked_id']));
		}
		
		$query_o = 'SELECT object_id FROM observations WHERE MONTH(observation_datetime) = MONTH(NOW()) AND object_id = ' . $object_id;
			
		$table_id_o = mysqli_query($dbc, $query_o);
		
		echo '<tr>' . "\n" . '<td>Marked</td>' . "\n" . '<td';
		if(mysqli_num_rows($table_id_o) > 0){
			echo ' class="now_visible"';
		}
		echo '>';
		echo return_user_marked_data($object_id, $marked_objects);
		echo '</td>' . "\n" . '</tr>' . "\n";
	}
	?>
	<tr>
		<td>Magnification</td>
		<td><?php echo return_magnification($dbc, $object_id, 'average', 'telescope') . ' <sup>(average, telescope)</sup>';?></td>
	</tr>
</table>

<h2>Observations</h2>

<?php
require_once('php_scripts/function_observation_list.php');
require_once('php_scripts/tabular_data_functions.php');

$observations_objects = return_observation_list($dbc, '', $object_id, 'astronomical_object');

if($observations_objects != false){
	echo $observations_objects;
}
else {
	echo '<p>No observations for this astronomical object</p>';
}
?>

<h2>Pictures</h2>
<?php
require_once('php_scripts/function_picture_list.php');
?>
<div class="grid-overview">
<?php
echo return_picture_list($dbc, '', $object_id, 'astronomical_object');
?>
</div>

<!--<h2>Comments</h2>-->
<?php
/*require_once('php_scripts/function_comment_list.php');

if(isset($_SESSION['user_id'])){
	echo return_comment_list($dbc, 10, 'picture', null, $picture_id, true);
}
else {
	echo return_comment_list($dbc, 10, 'picture', null, $picture_id, false);
}*/

?>