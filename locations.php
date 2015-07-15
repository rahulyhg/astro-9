<h1>Locations</h1>

<!-- GOOGLE MAPS javascript API -->
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/locations_map.js"></script>
<!-- GOOGLE MAPS END -->

<div id="locations_map">


</div>

<div id="locations_map_menu">
<?php
	$query = 'SELECT observation_datetime FROM observations ORDER BY observation_datetime ASC LIMIT 1';

	$table_id = mysqli_query($dbc, $query);
	
	$row = mysqli_fetch_array($table_id);
	
	$first_observation_datetime = $row['observation_datetime'];
	
	$query = 'SELECT observation_datetime FROM observations ORDER BY observation_datetime DESC LIMIT 1';

	$table_id = mysqli_query($dbc, $query);
	
	$row = mysqli_fetch_array($table_id);
	
	$last_observation_datetime = $row['observation_datetime'];
?>
<h4>Date range</h4>
<div class="date_selection">
	<div id="start_date">start date</div>
	<div id="end_date">end date</div>
	<div id="start_date_input" class="active">
		<div class="button r1 c1" id="day_start_plus">+</div>
		<input class="r2 c1" id="day_start" type="text" maxlength="2" value="<?php echo date(j, strtotime($first_observation_datetime)); ?>"/>
		<div class="button r3 c1" id="day_start_min">-</div>
		<span class="ss_1 r2">-</span>
		<div class="button r1 c2" id="month_start_plus">+</div>
		<input class="r2 c2" id="month_start" type="text" maxlength="2" value="<?php echo date(n, strtotime($first_observation_datetime)); ?>"/>
		<div class="button r3 c2" id="month_start_min">-</div>
		<span class="ss_2 r2">-</span>
		<div class="button r1 c3" id="year_start_plus">+</div>
		<input class="r2 c3" id="year_start" type="text" maxlength="4" value="<?php echo date(Y, strtotime($first_observation_datetime)); ?>"/>
		<div class="button r3 c3" id="year_start_min">-</div>
		<div class="button r1 c4" id="hour_start_plus">+</div>
		<input class="r2 c4" id="hour_start" type="text" maxlength="2" value="<?php echo date(G, strtotime($first_observation_datetime)); ?>"/>
		<div class="button r3 c4" id="hour_start_min">-</div>
		<span class="ss_3 r2">:</span>
		<div class="button r1 c5" id="minute_start_plus">+</div>
		<input class="r2 c5" id="minute_start" type="text" maxlength="2" value="<?php echo date(i, strtotime($first_observation_datetime)); ?>"/>
		<div class="button r3 c5" id="minute_start_min">-</div>
	</div>
	<div id="end_date_input" class="hidden">
		<div class="button r1 c1" id="day_end_plus">+</div>
		<input class="r2 c1" id="day_end" type="text" maxlength="2" value="<?php echo date(j, strtotime($last_observation_datetime)); ?>"/>
		<div class="button r3 c1" id="day_end_min">-</div>
		<span class="ss_1 r2">-</span>
		<div class="button r1 c2" id="month_end_plus">+</div>
		<input class="r2 c2" id="month_end" type="text" maxlength="2" value="<?php echo date(n, strtotime($last_observation_datetime)); ?>"/>
		<div class="button r3 c2" id="month_end_min">-</div>
		<span class="ss_2 r2">-</span>
		<div class="button r1 c3" id="year_end_plus">+</div>
		<input class="r2 c3" id="year_end" type="text" maxlength="4" value="<?php echo date(Y, strtotime($last_observation_datetime)); ?>"/>
		<div class="button r3 c3" id="year_end_min">-</div>
		<div class="button r1 c4" id="hour_end_plus">+</div>
		<input class="r2 c4" id="hour_end" type="text" maxlength="2" value="<?php echo date(G, strtotime($last_observation_datetime)); ?>"/>
		<div class="button r3 c4" id="hour_end_min">-</div>
		<span class="ss_3 r2">:</span>
		<div class="button r1 c5" id="minute_end_plus">+</div>
		<input class="r2 c5" id="minute_end" type="text" maxlength="2" value="<?php echo date(i, strtotime($last_observation_datetime)); ?>"/>
		<div class="button r3 c5" id="minute_end_min">-</div>
	</div>
	<div class="observation_counter"><img src="images/update_markers.png" alt="markers" /> <span id="observation_counter">0</span></div>

	<div class="submit_button" id="update_range"><img src="images/update_refresh.png" alt="refresh" /> <span>update</span></div>
</div>

<h4>Icon type</h4>
<p class="icon_type">
	<span id="icon_user">User</span><span id="icon_device">Device</span>
	<span id="icon_object_type">Object type</span><span id="icon_device_type">Device type</span>
</p>


<div class="clear"></div>