<?php
require './cfg/config.php';
if(isset($cfg['csrf']))
{
	session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="Robots" content="noindex">
	<title>Demo skryptu F3Sonda 2</title>
	<style type="text/css">
	html {background-color: #0080c0; font: 13px Verdana,'DejaVu Sans',Helvetica,Arial}
	body {background-color: #a4e1ff; margin: 20px auto; padding: 15px; width: 250px}
	</style>
</head>
<body>
<?php
include 'sonda.php';
echo sonda(isset($_GET['id'])?(int)$_GET['id']:null);
?>
</body>
</html>