<?php
session_start();

require_once('config.php');
require_once('php_scripts/tabular_data_functions.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
			or die('Error connecting to MySQL server.');

if(!empty($_POST['login'])){
	$user_login_name = mysqli_real_escape_string($dbc, trim($_POST['login_name']));
	$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
	
	$query_login = 'SELECT user_id, default_device_id FROM users WHERE user_login_name="' . $user_login_name . '" AND user_password = SHA1("' . $user_password . '")';
			
	$table_id_login = mysqli_query($dbc, $query_login);
			
	if(mysqli_num_rows($table_id_login) == 1){
		$row_login = mysqli_fetch_array($table_id_login);
		$_SESSION['user_id'] = $row_login['user_id'];
		$_SESSION['default_device_id'] = $row_login['default_device_id'];
								
		header('location: ?p=observations');
	}
	else {
		$error_message = 'Je naam en wachtwoord komen niet overheen, probeer het nogmaals (naam en wachtwoord zijn hoofdlettergevoelig)';
	}
}

if(!empty($error_message)){
	echo '<p class="error">' . $error_message . '</p>';
}


$page = mysqli_real_escape_string($dbc, trim(preg_replace('/_/', ' ', $_GET['p'])));

if(empty($page)){
	$page = 'home';
}

switch($page){
	case 'user':
		$user_id = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
		$query = 'SELECT user_first_name, user_surname FROM users WHERE user_id="' . $user_id . '"';
		$table_id = mysqli_query($dbc, $query);
		if(mysqli_num_rows($table_id) == 1){
			$row = mysqli_fetch_array($table_id);
			if(isset($_SESSION['user_id'])){
				$subtitle = $row['user_first_name'] . ' ' . $row['user_surname'];
			}
			else {
				$subtitle = $row['user_first_name'];
			}
		}
		break;
	case 'picture':
		$picture_id = mysqli_real_escape_string($dbc, trim($_GET['picture_id']));
		$query = 'SELECT picture_name FROM pictures WHERE picture_id="' . $picture_id . '"';
		$table_id = mysqli_query($dbc, $query);
		if(mysqli_num_rows($table_id) == 1){
			$row = mysqli_fetch_array($table_id);
			$subtitle = $row['picture_name'];
		}
		break;
	case 'device':
		$device_id = mysqli_real_escape_string($dbc, trim($_GET['device_id']));
		$query = 'SELECT device_name FROM devices WHERE device_id="' . $device_id . '"';
		$table_id = mysqli_query($dbc, $query);
		if(mysqli_num_rows($table_id) == 1){
			$row = mysqli_fetch_array($table_id);
			$subtitle = $row['device_name'];
		}
		break;
	case 'constellation':
		$constellation_id = mysqli_real_escape_string($dbc, trim($_GET['constellation_id']));
		$query = 'SELECT constellation_name FROM constellations WHERE constellation_id="' . $constellation_id . '"';
		$table_id = mysqli_query($dbc, $query);
		if(mysqli_num_rows($table_id) == 1){
			$row = mysqli_fetch_array($table_id);
			$subtitle = $row['constellation_name'];
		}
		break;
	case 'astronomical object':
		$object_id = mysqli_real_escape_string($dbc, trim($_GET['object_id']));
		
		$query = 'SELECT astronomical_objects.object_name FROM astronomical_objects WHERE astronomical_objects.object_id=' . $object_id;
		
		$table_id = mysqli_query($dbc, $query);
		
		$row = mysqli_fetch_array($table_id);
		
		$subtitle = return_astronomical_object_name($dbc, $object_id, $row['object_name']);

		break;
}

$query = 'SELECT page_id, title, page, meta_description, meta_keywords FROM pages WHERE title="' . $page . '"';

$table_id = mysqli_query($dbc, $query);

if(mysqli_num_rows($table_id) == 1){
	$row = mysqli_fetch_array($table_id);
	
	$page_id = $row['page_id'];
	if(strtolower($row['title']) != 'home'){
		$page_title = $row['title'];
		if(!empty($subtitle)){
			$page_title .= ' - ' . $subtitle;
		}
	}
	$page_file = $row['page'];
	if(!isset($_SESSION['user_id']) && $page_file == 'locations.php'){// Netter!
		$page_file = 'error.php';
	}
	$page_meta_description = $row['meta_description'];
	$page_meta_keywords = $row['meta_keywords'];
}
else {
	$page_title = 'Error!';	
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<title>Astronomy - Niels Riekert<?php if(!empty($page_title)){ echo ' | ' . $page_title;}?></title>
<meta http-equiv="Content-Language" content="nl" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="<?php echo $page_meta_description; ?>" />
<meta name="keywords" content="<?php echo $page_meta_keywords; ?>" />
<link rel="stylesheet" href="default.css" media="screen" />
<link rel="stylesheet" href="default.css" media="print" />
<link rel="stylesheet" href="print.css" media="print" />
<link rel='stylesheet' media='screen and (max-width: 800px)' href='handheld_800.css' />
<link rel="stylesheet" href="handheld.css" media="handheld" />

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-3807555-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
	<div id="wrapper">
		<div id="menu">
			<ul>
				<li><a href="?p=home">Home</a></li>
				<li><a href="?p=observations">Observations</a></li>
				<li><a href="?p=astronomical_objects">Astronomical objects</a></li>
				<li><a href="?p=pictures">Pictures</a></li>
				<li><a href="?p=devices">Devices</a></li>
				<li<?php 
				if(isset($_SESSION['user_id'])){
				?>><a href="?p=locations">Locations</a><?php;}else{?> class="disabled"><span>Locations</span><?php;} ?></li>
				<li><a href="?p=weather">Weather</a></li>
				<li><a href="?p=fun">Fun</a></li>
				<?php
				if(isset($_SESSION['user_id'])){
				?>
				<li><a href="?p=user&amp;user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
				<?php
				}
				else {
				?>
				<li><a href="?p=login">Login</a></li>
				<?php
				}
				?>
			</ul>
		</div>
		<div id="content">
		<?php
			echo "\n";
			require_once($page_file);
		?>
		</div>
	</div>
</body>
</html>
<?php
if(!empty($dbc)){
	mysqli_close($dbc);
}
?>