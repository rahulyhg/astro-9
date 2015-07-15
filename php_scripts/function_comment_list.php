<?php
function return_comment_list($dbc, $max_comments, $type, $subtype, $id, $select_type, $display_form){
	$return_data;
	
	if(!empty($_POST['submit'])){
		$message =  mysqli_real_escape_string($dbc, stripslashes(trim($_POST['comment'])));
		
		if(!empty($message)){
			$query = 'INSERT INTO comments_pictures (user_id, picture_id, message, comment_date) VALUES (' . $_SESSION['user_id'] . ', ' . $id . ', "' . $message . '", NOW())';
			
			$result = mysqli_query($dbc, $query);
		}
	}
	
	
	$query = 'SELECT users.user_id, users.user_first_name, users.user_surname, users.user_avatar_url, comments_pictures.message';
	if($subtype == 'recent'){
		$query .= ', pictures.picture_id, pictures.picture_name';
	}			
	$query .= ' FROM comments_pictures
				INNER JOIN users
				USING (user_id)
				INNER JOIN pictures
				USING (picture_id)';
	if($select_type == 'user' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE comments_pictures.user_id=' . $id;
	}
	else if($select_type == 'picture' && is_int(intval($id)) && $id > 0){
		$query .= ' WHERE comments_pictures.picture_id=' . $id;
	}
	$query .= ' ORDER BY comment_date DESC LIMIT 0,' . $max_comments;
					
	$table_id = mysqli_query($dbc, $query);
	
	if(mysqli_num_rows($table_id) == 0){
		$return_data .= '<p>No comments yet</p>';
	}
	
	while($row = mysqli_fetch_array($table_id)){
		if($subtype == 'recent'){
			$return_data .= '<div class="comment">' . "\n";
			$return_data .= '<div class="name">' . "\n" . return_user_observation_data($row['user_id'], $row['user_first_name'], $row['user_avatar_url'], '') . "\n" . ' commented on <a href="?p=picture&amp;picture_id=' . $row['picture_id'] . '">' . $row['picture_name'] . '</a></div>';
			$return_data .= '<div class="message">' . "\n";
			
			$return_data .= format_text($row['message'], 180, '');
			/*$text = preg_replace("/\r\n/", "\n", $row['message']);
			
			if(strlen($text) > 180){
				$text = substr($text, 0, 180);
				$text = trim($text) . '..';
			}
			
			$text_array = explode("\n\n", $text);
			
			foreach($text_array as $paragraph){
				$return_data .= '<p>' . "\n";
				$paragraph = str_replace("\n", '<br />', $paragraph);
				$return_data .= trim($paragraph);
				$return_data .= '</p>' . "\n";
			}*/
			$return_data .= '</div>' . "\n" . '</div>' . "\n";
		}
		else {
			$return_data .= '<div class="comment">' . "\n";
			$return_data .= '<div class="name">' . "\n" . return_user_observation_data($row['user_id'], $row['user_first_name'], $row['user_avatar_url'], '') . "\n" . '</div>';
			$return_data .= '<div class="message">' . "\n";
			
			$return_data .= format_text($row['message'], '', '');
			
			$return_data .= '</div>' . "\n" . '</div>' . "\n";
		}
	}
	
	if($display_form == true){
		$return_data .= '<h3>Leave a comment</h3>' . "\n";
		if($result){
			$return_data .= '<p class="notification">Reactie geplaatst!</p>' . "\n";
		}
		$return_data .= '<form class="comment" action="?p=' . $_GET['p'] . '&amp;picture_id=' . $id . '" method="post">' . "\n";
		$return_data .= '<table class="list">' . "\n" . '<tr>' . "\n" . '<td></td>' . "\n" . '<td><textarea rows="10" cols="50" name="comment" ></textarea></td>' . "\n" . '</tr>' . "\n";
		$return_data .= '<tr>' . "\n" . '<td></td>' . "\n" . '<td><input type="submit" name="submit" value="post" /></td>' . "\n" . '</tr>' . "\n" . '</table>';
		$return_data .= '</form>' . "\n";
	}
	
	return $return_data;
}
?>