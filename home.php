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
<h2>#astronomy on Twitter</h2>
<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("http://search.twitter.com/search.atom?q=%23astronomy");

$x = $xmlDoc->getElementsByTagName('entry');
foreach ($x AS $entry){
	$aur = $entry->getElementsByTagName('author');
	foreach ($aur AS $item){
		$aur_name = $item->getElementsByTagName('name');
		$aur_uri = $item->getElementsByTagName('uri');

		foreach ($aur_name AS $item){
			$author_name = $item->nodeValue;
		}
		foreach ($aur_uri AS $item){
			$author_uri = $item->nodeValue;
		}
	}
	$tie = $entry->getElementsByTagName('title');
	foreach ($tie AS $item){
		$tweet = $item->nodeValue;
	}
	echo '<div class="tweet">' . "\n";
	echo '<h4><a href="' . $author_uri . '">' . $author_name . '</a></h4>' . "\n";
	echo '<p>' . $tweet . '</p>';
	echo '</div>' . "\n";
}
?>
<div id="twitter_div"> 
	<ul id="twitter_update_list"></ul> 
	<!--<a href="http://twitter.com/Judobosz" id="twitter-link" style="display:block;text-align:right;">follow me on Twitter</a>--> 
</div>

<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://search.twitter.com/search.json?q=#astro&amp;count=5"></script>
<p class="rss">
	<a href="rss.php">rss</a>
</p>