<h1>Devices</h1>

<?php
require_once('php_scripts/tabular_data_functions.php');
require_once('php_scripts/function_devices_list.php');

echo return_device_list($dbc, '', '');
?>
<div class="clear"></div>