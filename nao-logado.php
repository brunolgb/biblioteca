<?php
session_start();
$id_user = $_SESSION['id_user'];
if ($id_user == "") {
	header("REFRESH: 1; url=index.php");
	echo "
	<html>
	<head>
		<link rel='stylesheet' href='arquivos/estilo.css'>
		<meta charset='utf8'>	
	</head>
	<body>
		<header class='header'>
			<img src='arquivos/logo-topo.png' alt='>
		</header>
		<section class='login'>
			<h1>Você precisa se logar para continuar a usar o sistema</h1>			
		</section>
	</body>
</html>";
die();
}
?>
