<h1>Pictures</h1>

<?php
require_once('php_scripts/tabular_data_functions.php');
require_once('php_scripts/function_picture_list.php');
?>
<div class="pictures">
<?php
echo return_picture_list($dbc, '', '', '');
?>
</div>

<div class="clear"></div>