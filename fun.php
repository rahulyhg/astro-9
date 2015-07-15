<h1>Fun</h1>

<h2>Puzzle</h2>
<script src="js/utils.js"></script>
<script src="js/puzzle_jigsaw.js"></script>
<p>
	<form action="#" method="get">
		<select id="picture_select" name="picture">
			<option value="">- select picture -</option>
			<?php
			$query = 'SELECT picture_url, picture_name FROM pictures';
			
			$table_id = mysqli_query($dbc, $query);
			
			while($row = mysqli_fetch_array($table_id)){
				echo '<option value="' . SITE_PICTURES_DIR . $row['picture_url'] . '">' . $row['picture_name'] . '</option>' . "\n";
			}
			?>
		</select>
		Pieces: <input id="pieces_count" type="text" value="25" name="pieces" /> <span id="reset_button" class="button">reset</span>
	</form>
</p>

<div id="puzzle">
	<div id="puzzle_inner">
	
	</div>
</div>
<div class="clear"></div>