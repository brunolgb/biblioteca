<?php session_start(); ?>
<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<link rel="shorcut icon" href="arquivos/icone.png">
		<script src="arquivos/estilo.js"></script>
		<title>Biblioteca Municipal de Comodoro</title>
	</head>
	<body>

		<?php
			$id_user = $_SESSION['id_user'];
			$data = date('Y/m/d');
			include('nav.php');
			// montando uma função
			function options ($coluna,$con)
			{
				$comando = "SELECT DISTINCT ".$coluna." FROM livro";
				$exe = mysqli_query($con,$comando);
				while ($a = mysqli_fetch_array($exe))
				{
					$rec = $a[$coluna];
					echo "<option value='".$rec."'>CADASTRADO</option>";
				}
			}
		?>
		<section class="corpo">
			<h1>Cadastro de  Livros</h1>
			
				<div class="livros">
					<form method="post" id="biblioteca" onload="carreFocus('nome_livro')">
					<div class="contCad">
						<!-- categoria dos livros -Educação Infantil e Ensino Fundamental -->
						<div>
							<label for="nome_livro">Nome do Livro<strong>*</strong></label>
							<input type="text" name="nome_livro" id="nome_livro" list="nomeLivroOption">
							<datalist id="nomeLivroOption">
								<?php options("nome_livro",$con_bancobiblioteca); ?>
							</datalist>
						</div>
						<div class="menor100">
							<label for="qtd">QTD<strong>*</strong></label>
							<select name="qtd" id="qtd">
								<?php
								$qtd_cont = 1;
									while ($qtd_cont <= 10)
									{
										echo "<option value='".$qtd_cont."'>".$qtd_cont."</option>";
										$qtd_cont++;	
									}
								?>
							</select>
						</div>
						<div>
							<label for="autor_livro">Autor do Livro</label>
							<input type="text" name="autor_livro" id="autor_livro" list="autorLivroOption">
							<datalist id="autorLivroOption">
								<?php options("autor_livro",$con_bancobiblioteca); ?>
							</datalist>
						</div>
						<div>
							<label for="editora_livro">Editora do Livro</label>
							<input type="text" name="editora_livro" id="editora_livro" list="ediLivroOption">
							<datalist id="ediLivroOption">
								<?php options("editora_livro",$con_bancobiblioteca); ?>
							</datalist>
						</div>
						<div class="menor100">
							<label for="local_livro">Local</label>
							<input type="text" name="local_livro" id="local_livro" maxlength="3">
						</div>
					</div>
					<div class="contCad desce">
						
						<div>
							<label for="descricao">Descrição do Livro</label>
							<textarea name="descricao" id="descricao" cols="30" rows="5"></textarea>
						</div>
					</div>
					<div class="envioLivro">
						<div class="submit">
							<input type="submit" value="Cadastrar" name="cadastrar">
						</div>
						<div class="submit">
							<input type="reset" value="Limpar Campos" name="descartar">
						</div>
					</div>
					</form>
					<div class="acaoCad">
						<?php
						if (isset($_POST['cadastrar'])) {
							
							$nome_livro_db = $_POST['nome_livro'];
							$qtd_livro_db = $_POST['qtd'];
							$autor_livro_db = $_POST['autor_livro'];
							if ($autor_livro_db == "") {
								$autor_livro_db = "N/A";
							}
							$editora_livro_db = $_POST['editora_livro'];
							if ($editora_livro_db == "") {
								$editora_livro_db = "N/A";
							}

							$local_livro_db = $_POST['local_livro'];
							$descricao_db = $_POST['descricao'];
							$comando = "
							insert into livro
							(nome_livro,qtd,autor_livro,editora_livro,local_livro,descricao,disponivel,user_cadastrou,data_cadastro)
							VALUES('$nome_livro_db','$qtd_livro_db','$autor_livro_db','$editora_livro_db','$local_livro_db','$descricao_db','sim','$id_user','$data')";

							// teste para ver se o aluno ja esta cadastrado
							$repetido_livro = "SELECT nome_livro,local_livro from livro";
							$repetido_query = mysqli_query($con_bancobiblioteca,$repetido_livro);

							while ($rep_l = mysqli_fetch_array($repetido_query))
							{
								$rep_nome = $rep_l['nome_livro'];
								if ($nome_livro_db == $rep_nome) 
								{
									$cad_ja_livro = true;
									$rep_nome_guarda = $rep_nome;
									$part = $rep_l['local_livro'];
									echo "<span class='nsucesso'>O Livro <strong>".$rep_nome_guarda."</strong> ja esta cadastrado!<br> Esta na partileira <strong>".$part."</strong></span>";
									break;
								}
							}
							if ($cad_ja_livro == false) {
								mysqli_query($con_bancobiblioteca,$comando) or die("Não foi possivel cadastrar livro");
								echo "<span class='sucesso'>Livro cadastrado com sucesso!</span>";
							}
							mysqli_close($con_bancobiblioteca);
						}
						?>
						
					</div>
				</div>
		</section>
<?php include('rodape.php'); ?>
	</body>
</html>