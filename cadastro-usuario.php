<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
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
			<h1>Cadastro de Usuário</h1>
			<form method="post">
				<div class="livros">
					<div class="contCad">
						<!-- categoria dos livros -Educação Infantil e Ensino Fundamental -->
						<div>
							<label for="usuario">Nome do Usuário<strong>*</strong></label>
							<input type="text" name="usuario" id="usuario"  required="required">
						</div>
						<div>
							<label for="cpf">CPF<strong>*</strong></label>
							<input type="text" name="cpf" id="cpf"  required="required">
						</div>
						<div>
							<label for="senha">Senha<strong>*</strong></label>
							<input type="password" name="senha" id="senha"  required="required">
						</div>
						<div>
							<label for="imagem">Imagem de Perfil</label>
							<input type="file" name="imagem" id="imagem">
						</div>					
					</div>
					<div class="contCad desce">
						
						<div>
							<label for="cargo">Cargo<strong>*</strong></label>
							<select name="cargo" id="cargo" required="">
								<option disabled="" selected="">-- Escolha --</option>
								<option value="ti">Técnico de informática</option>
								<option value="bibliotecaria">Bibliotecário</option>
								<option value="coordenador">Coordenador</option>
								<option value="diretor">Diretor</option>
								<option value="secretario">Secretario</option>
								<option value="servico_geral">Serviço gerais</option>
								<option value="cozinheira">Cozinheira</option>
								<option value="inspetor">Inspetor</option>
								<option value="guarda">Guarda</option>
								<option value="professor">Professor</option>
								<option value="aluno">Aluno</option>
							</select>
						</div>
						<div>
							<label for="admin">Administrador<strong>*</strong></label>
							<select name="admin" id="admin" required="">
								<option disabled="" selected="">-- Escolha --</option>
								<option value="sim">Sim</option>
								<option value="nao">Não</option>
							</select>
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
							$usuario = $_POST['usuario'];
							$cpf = $_POST['cpf'];
							$senha = $_POST['senha'];
							$senha2 = $_POST['senha2'];
							$imagem = $_POST['imagem'];
							$cargo = $_POST['cargo'];
							$admin = $_POST['admin'];
							if ($imagem == "") {
								$imagem = "padrao.png";
							}
							
							$com_cadAluno = "
							insert into usuario
							(nome_user,cpf_user,senha_user,src_imagem,cargo_user,admin,data_cadastro)
							VALUES('$usuario','$cpf','$senha','$imagem','$cargo','$admin','$data')";
							mysqli_query($con_bancobiblioteca,$com_cadAluno) or die("<span class='nsucesso'>Usuário não foi cadastrado!<bR> Verifique os dados se não estão repetidos ou incompletos.</span>");
							mysqli_close($con_bancobiblioteca);
							echo "<span class='sucesso'>Usuário cadastrado com sucesso</span>";
						}
						?>
						
					</div>
				</div>
		</section>
		<?php include('todos-usuario.php'); ?>
		<?php include('rodape.php'); ?>
	</body>
</html>