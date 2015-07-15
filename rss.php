<?php header('Contnt-Type: text/xml');?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>';?>
<rss version="2.0">

	<channel>
		<title>Astro Niels Riekert Update Feed</title>
		<link>http://astro.nielsriekert.nl</link>
		<description>Feed for the latest updates form astro.nielsriekert.nl</description>
		<language>nl</language>

<?php
require_once('config.php');
require_once('php_scripts/tabular_data_functions.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
			or die('Error connecting to MySQL server.');

$rss_array = Array();

$query = 'SELECT observations.observation_id, observations.observation_entry_datetime, observations.notes, astronomical_objects.object_id, users.user_first_name, devices.device_id, devices.device_name
			FROM observations
			INNER JOIN users
			USING (user_id)
			INNER JOIN astronomical_objects
			USING (object_id)
			INNER JOIN devices
			USING (device_id)
			ORDER BY observation_entry_datetime DESC';

$table_id = mysqli_query($dbc, $query);

$counter = 0;
while($row = mysqli_fetch_array($table_id)){
	$rss_array[$counter]['pubDate'] = date('r', strtotime($row['observation_entry_datetime']));
	
	$query_on = 'SELECT astronomical_objects.object_id, astronomical_objects.object_name FROM astronomical_objects WHERE astronomical_objects.object_id=' . $row['object_id'];
		
	$table_id_on = mysqli_query($dbc, $query_on);
	
	$row_on = mysqli_fetch_array($table_id_on);

	$object_name =return_astronomical_object_name($dbc, $row_on['object_id'], $row_on['object_name']);

	
	$rss_array[$counter]['title'] = $object_name . ' by ' . $row['user_first_name'] . ' (observation)';
	$rss_array[$counter]['guid'] = 'http://astro.nielsriekert.nl/?p=observation&amp;observation_id=' . $row['observation_id'];
	$rss_array[$counter]['link'] = 'http://astro.nielsriekert.nl/?p=observation&amp;observation_id=' . $row['observation_id'];
	$rss_array[$counter]['description'] = '<a href="http://astro.nielsriekert.nl/?p=device&amp;device_id=' . $row['device_id'] . '">' . $row['device_name'] . '</a><br />' . $row['notes'];
	$rss_array[$counter]['dc:creator'] = $row['user_first_name'];
	
	$counter ++;	
	//array_push($rss_array, $row['observation_entry_datetime'], 'Observation - ' . $row['object_name'] ' by ' . $row['user_first_name'], 'http://astro.nielsriekert.nl/?p=observations', $row['observations_note']); 
}

$query = 'SELECT pictures.picture_id, pictures.picture_name, pictures.picture_description, pictures.picture_entry_datetime, pictures.picture_description, pictures.picture_thumb_url, users.user_first_name
			FROM pictures
			INNER JOIN users
			USING (user_id)
			ORDER BY picture_entry_datetime DESC';

$table_id = mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($table_id)){
	$rss_array[$counter]['pubDate'] = date('r', strtotime($row['picture_entry_datetime']));
	$rss_array[$counter]['title'] = $row['picture_name'] . ' by ' . $row['user_first_name'] . ' (picture)';
	$rss_array[$counter]['guid'] = 'http://astro.nielsriekert.nl/?p=picture&amp;picture_id=' . $row['picture_id'];
	$rss_array[$counter]['link'] = 'http://astro.nielsriekert.nl/?p=picture&amp;picture_id=' . $row['picture_id'];
	$rss_array[$counter]['description'] = '<img src="http://astro.nielsriekert.nl/'. SITE_PICTURES_THUMB_DIR . $row['picture_thumb_url'] . '" /><br />' . $row['picture_description'];
	$rss_array[$counter]['dc:creator'] = $row['user_first_name'];
	
	$counter ++;
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['pubDate']);
    $t2 = strtotime($b['pubDate']);
    return $t2 - $t1;
}    
usort($rss_array, 'date_compare');

foreach($rss_array as $key => $post){
	echo '<item>' . "\n";
	foreach($rss_array[$key] as $item_name => $item_content){
		if($item_name == 'datetime'){
			continue;
		}
		echo '<' . $item_name . '>' . $item_content . '</' . $item_name . '>' . "\n";
	}
	//echo '<title>' . $post['title'] . '</title>' . "\n";
	//echo '<link>' . $post['link'] . '</link>' . "\n";
	//echo '<description>' . $post['description'] . '</description>' . "\n";
	echo '</item>' . "\n";
}

?>
	</channel>
</rss>