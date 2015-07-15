<form id="login" action="?p=<?php echo $_GET['p'] ?>" method="post">
	<table>
		<tr>
			<td>Name</td>
			<td><input type="text" name="login_name" <?php if(!empty($_REQUEST['login_nam'])){echo 'value="' . $_REQUEST['login_name'] . '"';} ?> /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password"  /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="login" value="login" /></td>
		</tr>
	</table>
</form>