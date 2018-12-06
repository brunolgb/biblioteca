<?php session_start();
include('nao-logado.php');
	$id_user_nav = $_SESSION['id_user'];
	$nome_user_nav = $_SESSION['nome_user'];
	$admin_nav = $_SESSION['admin'];
	$src_imagem = $_SESSION['src_imagem'];
	if ($admin_nav == "sim") {
		$cad_user= "<li>
					<a href='cadastro-usuario.php'>Cadastrar Usuario</a>
				</li>";
	}
	else
	{
		$cad_user= null;
	}

	if ($id_user_nav == "1") {
		$teste_user= "
		<li>
			<a href='teste.php'>Teste</a>
		</li>
		<li>
			<a href='teste2.php'>Teste2</a>
		</li>
		";
	}
	else{
		$teste_user= null;
	}

	// conexao ao banco de dados
	$con_bancobiblioteca = mysqli_connect('localhost','root','','biblioteca') or die ("<div class='info info_erro'>Erro ao excluir</div>");
		// $con_bancobiblioteca = mysqli_connect('localhost','1119325','biblioteca','1119325');
	mysqli_set_charset($con_bancobiblioteca,"utf8_general_ci");
?>

<header class="header paginas">
	<img src="arquivos/logo-topo.png" alt="">
	<ul class="nav">
		<li>
			<a href="home.php">Inicio</a>
		</li>
		<li>
			<a>
				livros
			</a>
			<ul class="submenu">
				<li>
					<a href="cadastrar-livros.php">Cadastrar</a>
				</li>
				<li>
					<a href="todos-livros.php">Todos</a>
				</li>
			</ul>
		</li>
		<li>
			<a>
				alunos
			</a>
			<ul class="submenu">
				<li>
					<a href="cadastrar-alunos.php">Cadastrar</a>
				</li>
				<li>
					<a href="todos-alunos.php">Todos</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="emprestimo.php">emprestimos</a>
		</li>
		<li>
			<a href="relatorio.php">
				relatórios
			</a>
		</li>
	</ul>
	<section class="sair">
		<aside>
			<figure>
				<?php echo "<img src='arquivos/fotos/usuarios/$src_imagem'>"?>
				<figcaption><?php echo $nome_user_nav; ?></figcaption>
			</figure>
		</aside>
		<section>
			<ul class="sair_ul">
				<li>
					<a href="perfil.php">Perfil</a>
				</li>
				<?php
					echo $cad_user;
					echo $teste_user;
				?>
				<li>
					<a href="index.php">Sair</a>
				</li>
			</ul>
		</section>
	</section>
</header>