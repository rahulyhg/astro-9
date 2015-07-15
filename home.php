<h2>Recent observations</h2>
<?php
require_once('php_scripts/function_observation_list.php');
require_once('php_scripts/tabular_data_functions.php');

echo return_observation_list($dbc, 3, '', '');
?>
<h2>Recent pictures</h2>
<?php
require_once('php_scripts/function_picture_list.php');
echo return_picture_list($dbc, 3, '', '');
?>
<div class="clear"></div> 
<h2>Recent comments</h2>
<?php
require_once('php_scripts/function_comment_list.php');
echo return_comment_list($dbc, 5, 'picture', 'recent', $picture_id, '', false);
?>
<p class="rss">
	<a href="rss.php">rss</a>
</p>