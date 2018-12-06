<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<link rel="shorcut icon" href="arquivos/icone.png">
		<title>Biblioteca Municipal de Comodoro</title>
		<script src="arquivos/estilo.js"></script>
	</head>
	<body>
		<?php
		include('nav.php');
			$id_user = $_SESSION['id_user'];
			$data = date('Y/m/d');
		?>
		<section class="corpo">
			<h1>Cadastro de Alunos</h1>
			<form method="post">
				<div class="livros">
					<div class="contCad">
						<!-- categoria dos livros -Educação Infantil e Ensino Fundamental -->
						<div>
							<label for="aluno">Nome do Aluno<strong>*</strong></label>
							<input type="text" name="aluno" id="aluno"  required="required">
						</div>
						<div>
							<label for="nasc">Nascimento<strong>*</strong></label>
							<input type="date" name="nasc" id="nasc"  required="required">
						</div>
						<div>
							<label for="turno">Turno<strong>*</strong></label>
							<select name="turno" id="turno"  required="required">
								<option value="" disabled="disabled" selected="selected">--Escolha--</option>
								<option value="m">Matutino</option>
								<option value="v">Vespertino</option>
								<option value="n">Noturno</option>
							</select>
						</div>
						<div>
							<label for="ano">Ano<strong>*</strong></label>
							<select name="ano" id="ano" class="largPM" required="required">
								<option disabled="disabled" selected="selected">--Escolha--</option>
								<option value="pre">pre</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
							</select>
						</div>
					</div>
					<div class="contCad desce">
						<div>
							<label for="turma">Turma<strong>*</strong></label>
							<select name="turma" id="turma"  required="required">
								<option disabled="disabled" selected="selected">--Escolha--</option>
								<option value="a">a</option>
								<option value="b">b</option>
								<option value="c">c</option>
								<option value="d">d</option>
								<option value="e">e</option>
								<option value="f">f</option>
							</select>
						</div>
						<div>
							<label for="prof">Professor</label>
							<input type="text" name="prof" id="prof">
						</div>
						<div>
							<label for="img">Imagem</label>
							<input type="file" name="img" id="img">
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
							$aluno = $_POST['aluno'];
							$nasc = $_POST['nasc'];
							$turno = $_POST['turno'];
							$ano = $_POST['ano'];
							$turma = $_POST['turma'];
							$prof = $_POST['prof'];
							$img=$_POST['img'];

							if ($img == "")
							{
								$img = "padrao.png";
							}

							$com_cadAluno = "
							insert into aluno
							(nome_aluno,data_nasc,turno,ano_aluno,turma,prof,img,user_cadastrou,data_cadastro)
							VALUES('$aluno','$nasc','$turno','$ano','$turma','$prof','$img','$id_user','$data')";
							// teste para ver se o aluno ja esta cadastrado
							
							$repetido_aluno = "SELECT nome_aluno from aluno";
							$repetido_query = mysqli_query($con_bancobiblioteca,$repetido_aluno);

							while ($rep_a = mysqli_fetch_array($repetido_query))
							{
								$rep_nome = $rep_a['nome_aluno'];
								if ($aluno == $rep_nome) 
								{
									$cad_ja_aluno = true;
									$rep_nome_guarda = $rep_nome;
									echo "<span class='nsucesso'>O aluno <strong>".$rep_nome_guarda."</strong> ja esta cadastrado!</span>";
									break;
								}
							}
							if ($cad_ja_aluno == false) {
								mysqli_query($con_bancobiblioteca,$com_cadAluno) or die("<span class='nsucesso'>Falha na consulta! Entre em contato com o Administrador.</span>");	
								echo "<span class='sucesso'>Aluno cadastrado com sucesso!</span>";
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