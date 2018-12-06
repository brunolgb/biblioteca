<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="shorcut icon" href="arquivos/icone.png">
	<link rel="stylesheet" href="arquivos/estilo.css">
	<script src="arquivos/estilo.js"></script>
</head>
<body>
	<?php
		include("nav.php");
		$sessiao_id = $_SESSION['id_user'];
		$perfilConsulta = "SELECT * FROM usuario";
		$perfilConsultando = mysqli_query($con_bancobiblioteca,$perfilConsulta);
		while ($user = mysqli_fetch_array($perfilConsultando))
		{
			$id_usuario = $user['id_user'];
			if ($id_usuario == $sessiao_id)
			{
				$id_usuario = $user['id_user'];
				$nome_usuario = $user['nome_user'];
				$cpf_usuario = $user['cpf_user'];
				$senha_usuario = $user['senha_user'];
				$src_imagem = empty($user['src_imagem']) ? "padrao.png" : $user['src_imagem'];
				$cargo_user = $user['cargo_user'];
				$admin = $user['admin'];
				$data_cadastro = $user['data_cadastro'];
				break;
			}
			
		}
	?>
	<section class="corpo">
		<h1>Você está em Perfil</h1>
		<div class="perfil">
			<figure>
				<img src="arquivos/fotos/usuarios/<?php echo $src_imagem;?>" alt="">
				<figcaption>
					<input type="file" name="foto" value="<?php echo $src_imagem;?>">
				</figcaption>
			</figure>
			<section>
				<form method="post">
					<label for="nome">Nome</label>
					<input type="text" name="nome" id="nome" value="<?php echo $nome_usuario;?>">
					<label for="cpf">CPF</label>
					<input type="text" name="cpf" id="cpf" value="<?php echo $cpf_usuario;?>">
					<label for="senha">Senha</label>
					<input type="text" name="senha" id="senha" value="<?php echo $senha_usuario;?>">
					<label for="cargo">Cargo</label>
					<input type="text" name="cargo" id="cargo" value="<?php echo $cargo_user;?>">
					<label for="admin">Administrador</label>
					<select type="text" name="admin" id="admin" disabled="" value="<?php echo $admin;?>">
						<?php
						if ($admin == "sim")
						{
							echo "<option value='sim' selected=''>Sim</option>";
							echo "<option value='nao'>Não</option>";
						}
						else
						{
							echo "<option value='sim'>Sim</option>";
							echo "<option value='nao' selected=''>Não</option>";
						}
						?>
					</select>
					<input type="submit" name="enviar" class="botao" value="Atualizar Cadastro">
				</form>
				<?php
				if (isset($_POST['enviar']))
				{
					$shell = "UPDATE usuario SET ";
					$comando .= $nome_usuario == $_POST['nome'] ? "" : "nome_user='".$_POST['nome']."'";
					$comando .= $cpf_usuario == $_POST['cpf'] ? "" : "cpf_user='".$_POST['cpf']."'";
					$comando .= $senha_usuario == $_POST['senha'] ? "" : "senha_user='".$_POST['senha']."'";
					$comando .= $cargo_user == $_POST['cargo'] ? "" : "cargo_user='".$_POST['cargo']."'";
					$comando .= $src_imagem == $_POST['foto'] ? "" : "src_imagem='".$_POST['foto']."'";
					if (empty($comando))
					{
						echo "<span class='padraoInfo n'>Você não fez alterações! Os dados são os mesmos.</span>";
					}
					else
					{
						$final = $shell."".$comando." where id_user='".$id_usuario."'";
						mysqli_query($con_bancobiblioteca,$final);
						echo "<span class='padraoInfo s'>Alteração feita com sucesso!</span>";
						echo "<meta http-equiv='refresh' content='1'>";
					}
				}
				
				?>
			</section>
		</div>
	</section>
	<?php include("rodape.php"); ?>
</body>
</html>