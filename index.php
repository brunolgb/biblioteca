<?php
session_start();
session_destroy();
session_start();
?>
<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<link rel="shorcut icon" href="arquivos/icone.png">
		<meta charset="utf8">
		<title>Biblioteca Municipal de Comodoro</title>
		<style>
			body{background: url(arquivos/fundo.jpg) no-repeat top;background-size: 100% 100%;}
			.index{
				text-align: center;
			}
		</style>		
	</head>
	<body>
		<header class="header index">
			<img src="arquivos/logo-topo.png" alt=""">
		</header>
		<section class="login">
			<form action="" method="post">
				<h1>Biblioteca Municipal</h1>
				<img src="arquivos/barra-feita.png">
				<input type="text" name="login" placeholder="Insira seu Login" required="required">
				<input type="password" name="senha" placeholder="Insira sua Senha" required="required">
				<input type="submit" value="Entrar" name="entrar">
			</form>
			<?php 
				if (isset($_POST["entrar"])) {
					$login = $_POST["login"];
					$senha = $_POST["senha"];
					$con = mysqli_connect('localhost','root','','biblioteca') or die("conecçao não foi feita");
					// $con = mysqli_connect('localhost','1119325','biblioteca','1119325') or die("conecçao não foi feita");
					
					mysqli_set_charset($con,"utf8");
					$sql = "SELECT * FROM usuario";
					$resul = mysqli_query($con,$sql);
					while ($bbarray = mysqli_fetch_array($resul)) {
						$cpf_user = $bbarray['cpf_user'];
						$senha_user = $bbarray['senha_user'];
						if ($cpf_user == $login & $senha_user == $senha) {
							header("REFRESH: 3; url=home.php");
							$id_user = $bbarray['id_user'];
							$nome_user = $bbarray['nome_user'];
							$cargo_user = $bbarray['cargo_user'];
							$admin = $bbarray['admin'];
							$src_imagem = $bbarray['src_imagem'];
							if ($src_imagem==null) {
								$src_imagem = "padrao.png";
							}
							$_SESSION['id_user']= $id_user;
							$_SESSION['nome_user']=$nome_user;
							$_SESSION['cargo_user']=$cargo_user;
							$_SESSION['admin']=$admin;
							$_SESSION['src_imagem']=$src_imagem;
							echo "<span class='alerta'>Seja bem vindo(a)<br>
									<strong>$nome_user</strong>
									Entrando...</span>";
							break;
						}
					}
					if ($nome_user == "") {
						echo "<span class='alerta'>Usuário ou senha errado<br>
									Entre em contato com o administrador</span>";
						echo "<meta http-equiv='refresh' content='2'> <br>";
					}

				}
			?>
			
		</section>
	</body>
</html>