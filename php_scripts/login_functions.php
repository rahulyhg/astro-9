<?php
function login_check(){
	if(is_int($_SESSION['user_id'])){
		return true;
	}
	else {
		return false;
	}
}
?>