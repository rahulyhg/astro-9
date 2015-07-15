<?php
require_once('php_scripts/function_astronomical_objects_list.php');
require_once('php_scripts/tabular_data_functions.php');
require_once('php_scripts/login_functions.php');

$constellation_id = mysqli_real_escape_string($dbc, trim($_GET['constellation_id']));

$query = 'SELECT constellation_name, constellation_image_url FROM constellations WHERE constellation_id =' . $constellation_id;

$table_id = mysqli_query($dbc, $query);

$row = mysqli_fetch_array($table_id);

$constellation_image_url = $row['constellation_image_url'];

?>
<h1><?php echo $row['constellation_name']; ?></h1>
<!--<div class="visibility_chart">-->
<?php
/*$query = 'SELECT constellations_visibility.visibility_type_id, visibility_types.visibility_type_name
			FROM constellations_visibility
			INNER JOIN visibility_types
			USING (visibility_type_id)
			WHERE constellation_id = ' . $constellation_id . ' ORDER BY constellations_visibility.month_id ASC';

$table_id = mysqli_query($dbc, $query);

if(mysqli_num_rows($table_id) == 12){
	$counter = 0;
	echo '<div class="chart_back">' . "\n";
	$query_visibility_types = 'SELECT visibility_type_name FROM visibility_types ORDER BY visibility_type_id DESC';
	$table_id_visibility_types = mysqli_query($dbc, $query_visibility_types);
	while($row_visibility_types = mysqli_fetch_array($table_id_visibility_types)){
		echo '<div class="row">' . $row_visibility_types['visibility_type_name'] . '</div>';
	}
	echo '<div class="chart">'. "\n";
	while($row = mysqli_fetch_array($table_id)){
		echo '<div class="month_graph" style="height: ' . $row['visibility_type_id']*20 . '%; left: ' . $counter*33 . 'px"></div>' . "\n";
		$counter ++;
	}
	echo '</div>' . "\n";
	echo '</div>' . "\n";
}
else {
	echo '<p class="na">No visibility chart yet</p>'. "\n";
}*/
?>
<!--</div>-->
<script src="js/utils.js"></script>
<script src="js/obs_graph.js"></script>
<canvas id="observations_graph" width="500" height="250">
	<p>This browser doesn't know Canvas.</p>
</canvas>
<?php


if(!empty($constellation_image_url)){
?>
<div class="constellation_map" style="background-image: url('<?php echo SITE_IMAGES_DIR_FRONT . SITE_IMAGES_CONSTELLATIONS_DIR . $constellation_image_url ?>');"></div>
<!--<canvas class="visibility_map">

</canvas>-->
<?php
}
/*
$img = imagecreatetruecolor(500, 500);

$line_colour = imagecolorallocate($img, 255, 255, 255);
$dot_colour = imagecolorallocate($img, 200, 0, 0);

imageline($img, 0, 0 ,100 ,100, $line_colour);

header("Content-type: image/png");
imagepng($img);

imagedestroy($img);
*/
?>
<h2>Astronomical objects</h2>
<?php

echo return_astronomical_objects_list($dbc, 'constellation', $constellation_id);
?>