<?php
	if(isset($_SESSION['user_id'])){
		if(!empty($_POST['add_observation'])){
			$object_id = mysqli_real_escape_string($dbc, trim($_POST['object_id']));
			$device_id = mysqli_real_escape_string($dbc, trim($_POST['device_id']));
			$eyepiece_device_id = mysqli_real_escape_string($dbc, trim($_POST['eyepiece_device_id']));
			$filter_device_id = mysqli_real_escape_string($dbc, trim($_POST['filter_device_id']));
			$level_id = mysqli_real_escape_string($dbc, trim($_POST['level_id']));
			$latitude = mysqli_real_escape_string($dbc, trim($_POST['latitude']));
			$longitude = mysqli_real_escape_string($dbc, trim($_POST['longitude']));
			$accuracy = mysqli_real_escape_string($dbc, trim($_POST['accuracy']));
			$notes = mysqli_real_escape_string($dbc, stripslashes(trim($_POST['notes'])));
			$magnification = mysqli_real_escape_string($dbc, trim($_POST['magnification']));
			$day = mysqli_real_escape_string($dbc, trim($_POST['day']));
			$month = mysqli_real_escape_string($dbc, trim($_POST['month']));
			$year = mysqli_real_escape_string($dbc, trim($_POST['year']));
			$hour = mysqli_real_escape_string($dbc, trim($_POST['hour']));
			$minutes = mysqli_real_escape_string($dbc, trim($_POST['minutes']));
			
			$query_add = 'INSERT INTO observations (object_id, user_id, device_id, eyepiece_device_id, filter_device_id, level_id, latitude, longitude, accuracy, magnification, notes, observation_datetime, observation_entry_datetime) VALUE (' . $object_id . ', ' . $_SESSION['user_id'] . ', ' . $device_id . ', ' . $eyepiece_device_id . ', ' . $filter_device_id . ', ' . $level_id . ', "' . $latitude . '", "' . $longitude . '", "' . $accuracy . '", "' . $magnification . '", "' . $notes . '", "' . $year . '-' . $month . '-' . $day . ' ' . $hour . '-' . $minutes . '-0", NOW())';
						
			$result = mysqli_query($dbc, $query_add);
			
			if($result){
				echo '<p class="notification">The observations has been added succesfully</p>';	
			}
			else {
				echo '<p class="error">Error</p>';	
			}
		}
?>
<h2>Add observation</h2>

<script type="text/javascript">
if(navigator.geolocation) {
 
function handle_success(position){ 
 //alert('Latitude: ' + position.coords.latitude + '\n Longitude: ' + position.coords.longitude);
 document.getElementById('latitude').setAttribute('value', position.coords.latitude);
 document.getElementById('longitude').setAttribute('value', position.coords.longitude);
 document.getElementById('accuracy').setAttribute('value', position.coords.accuracy);
 var brElement = document.createElement('br');
 var divElement = document.createElement('div');
 divElement.setAttribute('class', 'location');
 var aElement = document.createElement('a');
 aElement.setAttribute('href', 'http://maps.google.com/maps?q=' + position.coords.latitude + ', ' + position.coords.longitude);
 var imgElement_1 = document.createElement('img');
 imgElement_1.setAttribute('src', 'images/link_maps.png');
 imgElement_1.setAttribute('alt', 'google maps');
 aElement.appendChild(imgElement_1);
 var imgElement_2 = document.createElement('img');
 imgElement_2.setAttribute('class', 'map');
 imgElement_2.setAttribute('src', 'http://maps.google.com/maps/api/staticmap?center=' + position.coords.latitude + ',' + position.coords.longitude + '&zoom=14&size=200x200&maptype=roadmap&markers=color:red%7Csize:tiny%7Clabel:S%7C' + position.coords.latitude + ',' + position.coords.longitude + '&sensor=false');
 divElement.appendChild(imgElement_2);
 divElement.appendChild(aElement);
 document.getElementById('latitude').parentNode.appendChild(brElement);
 document.getElementById('latitude').parentNode.appendChild(divElement);
}
 
function handle_errors(err) { 
 switch(err.code) 
    { 
   case err.PERMISSION_DENIED: alert("User refused to share geolocation data"); 
   break; 
 
   case err.POSITION_UNAVAILABLE: alert("Current position is unavailable"); 
   break; 
 
   case err.TIMEOUT: alert("Timed out"); 
   break; 
 
   default: alert("Unknown error"); 
   break; 
  }
 }
 navigator.geolocation.getCurrentPosition(handle_success,handle_errors);
}
</script>

<form action="?p=observations" method="post">
	<table class="list">
		<tr>
			<td>
				Object<br />
				<select name="object_id">
					<?php
					$query_objects = 'SELECT object_id, object_messier_number, object_ngc_number, object_name FROM astronomical_objects ORDER BY object_id ASC';
					
					$table_id_objects = mysqli_query($dbc, $query_objects);
					
					while($row_objects = mysqli_fetch_array($table_id_objects)){
						echo '<option value="' . $row_objects['object_id'] . '">Messier: ' . $row_objects['object_messier_number'] . ' NGC: ' . $row_objects['object_ngc_number'];
						if(!empty($row_objects['object_name'])){
							echo ' Name: ' . $row_objects['object_name'];
						}
						echo '</option>' . "\n";
					}
					?>
				</select>
			</td>
			<td>
				Device<br />
				<select name="device_id">
					<?php
					$query_devices = 'SELECT device_id, device_name FROM devices WHERE device_type_id=1 OR device_type_id=3';
					
					$table_id_devices = mysqli_query($dbc, $query_devices);
					
					while($row_devices = mysqli_fetch_array($table_id_devices)){
						echo '<option value="' . $row_devices['device_id'] . '"';
						if($row_devices['device_id'] == $_SESSION['default_device_id']){
							echo ' selected="selected"';
						}
						echo '>'. $row_devices['device_name'] . '</option>' . "\n";
					}
					?>
				</select><br />
				Sub Device<br />
				<select name="eyepiece_device_id">
					<option value="0">none</option>
					<?php
					$query_sd = 'SELECT device_id, device_name
										FROM devices
										INNER JOIN devices_eyepiece
										USING(device_id)
										ORDER BY eyepiece_focal_length DESC';
					
					$table_id_sd = mysqli_query($dbc, $query_sd);
					
					while($row_sd = mysqli_fetch_array($table_id_sd)){
						echo '<option value="' . $row_sd['device_id'] . '"';
						echo '>'. $row_sd['device_name'] . '</option>' . "\n";
					}
					?>
				</select><br />
				Filter<br />
				<select name="filter_device_id">
					<option value="0">none</option>
					<?php
					$query_f = 'SELECT device_id, device_name
										FROM devices
										INNER JOIN devices_filter
										USING(device_id)';
															
					$table_id_f = mysqli_query($dbc, $query_f);
					
					while($row_f = mysqli_fetch_array($table_id_f)){
						echo '<option value="' . $row_f['device_id'] . '"';
						echo '>'. $row_f['device_name'] . '</option>' . "\n";
					}
					?>
				</select>
				
			</td>
			<td>
				Level<br />
				<select name="level_id">
					<?php
					$query_levels = 'SELECT level_id, level_short_description FROM observations_levels';
					
					$table_id_levels = mysqli_query($dbc, $query_levels);
					
					while($row_levels = mysqli_fetch_array($table_id_levels)){
						echo '<option value="' . $row_levels['level_id'] . '">Level: ' . $row_levels['level_id'] . ', ' . $row_levels['level_short_description'] . '</option>' . "\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Latitude<br />
				<input id="latitude" type="text" name="latitude" /><br />
				Longitude<br />
				<input id="longitude" type="text" name="longitude" /><br />
				Accuracy<br />
				<input id="accuracy" type="text" name="accuracy" /><br />
				Magnification<br />
				<input type="text" name="magnification" />
			</td>
			<td>Time<br />
			<select name="day">
				<?php
				for($i=1;$i<=31;$i++){
					echo '<option value="' . $i . '"';
					if($i == date('j')){
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>' . "\n";
				}
				?>
			</select>
			<select name="month">
				<?php
				for($i=1;$i<=12;$i++){
					echo '<option value="' . $i . '"';
					if($i == date('n')){
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>' . "\n";
				}
				?>
			</select>
			<select name="year">
				<?php
				for($i=(date('Y')-2);$i<=date('Y');$i++){
					echo '<option value="' . $i . '"';
					if($i == date('Y')){
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>' . "\n";
				}
				?>
			</select> - 
			<select name="hour">
				<?php
				for($i=0;$i<=23;$i++){
					echo '<option value="' . $i . '"';
					if($i == date('G')){
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>';
				}
				?>
			</select>
			<select name="minutes">
				<?php
				for($i=0;$i<=59;$i++){
					echo '<option value="' . $i . '"';
					if($i == date('i')){
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>';
				}
				?>
			</select>
			</td>
			<td>
				Notes<br />
				<textarea rows="10" cols="25" name="notes"></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="submit" name="add_observation" value="add" /></td>
		</tr>
	</table>
</form>
<?php
	}
?>
<h1>Observations</h1>
<?php
require_once('php_scripts/function_observation_list.php');

echo return_observation_list($dbc, '', '', '');
?>