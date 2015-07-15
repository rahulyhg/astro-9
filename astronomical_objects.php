<h1>Astronomical objects</h1>
<?php
require_once('php_scripts/function_astronomical_objects_list.php');
require_once('php_scripts/tabular_data_functions.php');
require_once('php_scripts/login_functions.php');

echo return_astronomical_objects_list($dbc, false, false);
?>