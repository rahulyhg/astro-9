<?php
$picture_id = mysqli_real_escape_string($dbc, trim($_GET['picture_id']));

$query = 'SELECT pictures.picture_name, pictures.picture_url,pictures.picture_description, users.user_first_name, users.user_surname
			FROM pictures
			INNER JOIN users
			USING (user_id)
			WHERE pictures.picture_id =' . $picture_id;

$table_id = mysqli_query($dbc, $query);

$row = mysqli_fetch_array($table_id);

?>
<h1><?php echo $row['picture_name']; ?> <span>by <?php echo $row['user_first_name']; if(isset($_SESSION['user_id'])){ echo ' ' . $row['user_surname'];}?></span></h1>

<img class="picture_full" src="<?php echo SITE_PICTURES_DIR . $row['picture_url']; ?>" alt="<?php echo $row['picture_name']; ?>" />

<h3>Description</h3>
<?php
require_once('php_scripts/tabular_data_functions.php');

if(!empty($row['picture_description'])){
	echo format_text($row['picture_description'], '', 'description');
}
else {
	echo '<p class="description"><i>No description</i></p>' . "\n";
}
?>

<h2>Objects on this Picture</h2>

<?php
require_once('php_scripts/function_astronomical_objects_list.php');

$picture_objects = return_astronomical_objects_list($dbc, 'picture', $picture_id);

if($picture_objects != false){
	echo $picture_objects;
}
else {
	echo '<p>No objects on this picture</p>';
}
?>
<h2>Comments</h2>
<?php
require_once('php_scripts/function_comment_list.php');

if(isset($_SESSION['user_id'])){
	echo return_comment_list($dbc, 10, 'picture', null, $picture_id, 'picture', true);
}
else {
	echo return_comment_list($dbc, 10, 'picture', null, $picture_id, 'picture', false);
}
?>

<h2>Facebook Comments</h2>
<div id="fb-root">
</div>
<script src="http://connect.facebook.net/en_US/all.js#appId=APP_ID&amp;xfbml=1"></script>
<fb:comments href="http://astro.nielsriekert.nl/?p=<?php echo $_GET['p'] . '&amp;picture_id=' . $picture_id ?>" num_posts="20" width="770"></fb:comments>