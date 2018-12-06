<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>teste</title>
</head>
<body>
<?php
echo $_SERVER['PHP_SELF'];
echo "<br>";
echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['HTTP_HOST'];
echo "<br>";
echo $_SERVER['HTTP_REFERER'];
echo "<br>";
echo $_SERVER['REQUEST_TIME'];
echo "<br>";
echo $_SERVER['GATEWAY_INTERFACE']."<br>";
$_SERVER['HTTP_REFERER'];
?>
</body>
</html>