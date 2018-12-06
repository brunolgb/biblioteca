<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<title>Biblioteca Municipal de Comodoro</title>
		<script src="arquivos/estilo.js"></script>
	</head>
	<body>
		<?php
		$id_user = $_SESSION['id_user'];
		$data = date('Y/m/d');
		function apagar($apagar_receber,$conexao)
		{
			echo "<meta http-equiv='REFRESH' content='2'>";
			$sql_apagar = "DELETE FROM usuario WHERE id_user=$apagar_receber";
			echo "<div class='info'>O registro $receber foi apagado com sucesso</div>";
			mysqli_query($conexao,$sql_apagar) or die ("<div class='info info_erro'>Erro ao excluir</div>");
		}
		$contador_registro = 0;
		$guarda = array();
		function filtro($sql,$coluna,$criterio,$limites,$conexao)
			{
				// pegando os limites
				if ($limites != null)
				{
					$inicio = strstr($limites,"*",true);
					$final = strstr($limites,"*",false);
					$final = substr($final, 1);
					$limit = " LIMIT $inicio,$final";
				}
				else
				{
					$limit = " LIMIT 0,50";
				}

				// comandos para buscar com os criterios definidos
				if ($sql!=null and $coluna!=null and $criterio!=null)
				{
					$sql_fun = "SELECT * FROM usuario WHERE ".$coluna." LIKE '%".$criterio."%' ORDER BY ".$sql;
				}
				elseif ($sql!=null and $coluna==null and $criterio==null)
				{
					$sql_fun = "SELECT * FROM usuario ORDER BY ".$sql;
				}

				elseif ($sql==null and $coluna!=null and $criterio!=null)
				{
					$sql_fun = "SELECT * FROM usuario WHERE ".$coluna." LIKE '%".$criterio."%'";
				}

				elseif ($sql==null and $coluna==null and $criterio==null)
				{
					$sql_fun = "SELECT * FROM usuario";
				}
				// paginação
				$paginando = mysqli_query($bancobiblioteca,$funcao);
				while ($p = mysqli_fetch_array($paginando)){$resulPaginacao = $p['COUNT(id_user)'];}
				$paginas = (ceil($resulPaginacao/50));
				$inicio = empty($inicio) ? 1 : $inicio;
				$final = (!empty($final)) ? $inicio + 50: 50;
				$resulPaginacao = "| Visualizando de <strong>".$inicio."</strong> á <strong>".$final."</strong> de <strong>".$resulPaginacao."</strong>";
				echo "<script>total('totMudaValor','".$resulPaginacao."');</script>";

				// iniciando a tabela
				
				echo "<div id='paginadores'>";
				$contPages = 1;
				$vinicio = 0;
				for ($i=1; $i <= $paginas; $i++)
				{
					$vfinal = 50;
					echo "<button onclick='enviar(filtro)' name='paginas' value='".$vinicio."*".$vfinal."'>".$contPages."</button>";
					$contPages++;
					$vinicio += 50;
				}
				echo "</div>";
				echo "
				<table class='dbLivro'>
					<tr id='titTable'>
						<td class='largP'>Id</td>
						<td class='largP'>Imagem</td>
						<td class='largG'>Nome do Usuário</td>
						<td class='largM'>CPF</td>
						<td  class='largM'>Senha</td>
						<td class='largG'>Função</td>
						<td class='largP'>Administrador</td>
						<td class='largM'>Dia</td>
						<td class='largP'>Acão</td>
					</tr>";
					mysqli_set_charset($conexao,"utf8");
					//Ver os usuarios						
					$guard_Usuario = array();
					$r = mysqli_query($conexao,$sql_fun) or die("Não foi possivel consultar alunos");
					while ($array_usuario = mysqli_fetch_array($r))
					{
						$id_user = $array_usuario['id_user'];
						$nome_user = $array_usuario['nome_user'];
						$cpf_user = $array_usuario['cpf_user'];
						$senha_user = $array_usuario['senha_user'];
						$src_imagem = $array_usuario['src_imagem'];
						if ($src_imagem==null)
						{
							$src_imagem = "padrao.png";
						}
						$cargo_user = $array_usuario['cargo_user'];
						$admin = $array_usuario['admin'];
						$user_cadastrou = $array_usuario['user_cadastrou'];
						$data_cadastrou = $array_usuario['data_cadastro'];
						$id_userdba = $guard_Usuario[$user_cadastrou];
						$datadb = $alivro['data_cadastro'];
						echo "	<tr>
								<td>".$id_user."</td>
								<td><img src='arquivos/fotos/usuarios/".$src_imagem."' id='perfil_aluno'></td>
								<td>".$nome_user."</td>
								<td>".$cpf_user."</td>
								<td>".$senha_user."</td>
								<td>".$cargo_user."</td>
								<td>".$admin."</td>
								<td>".$data_cadastrou."</td>
								<td>
									<input type='radio' name='excluir' value='".$id_user."' class='btn_radio'>
								</td>
							</tr>";
					echo "<script>muda('mudaValor','".$contador_registro."');</script>";
					}
					echo "</table>";
					$classe = "class='corpo bloco-alteracao'";
				mysqli_close($conexao);
			}

		?>
		<section class="corpo">
			<h1>Todos os usuario</h1>
		<form method="post">
			<fieldset class="mostrar-livros filtro">
				<legend>Criar Filtro</legend>
				<div>
					<label for="criar_relatorio">Organizar por</label><br>
					<select name="sql" id="criar_relatorio">
						<option disabled="" selected="">--Escolha--</option>
						<option value="id_user">Id</option>
						<option value="nome_user">Nome</option>
						<option value="cpf_user">CPF</option>
						<option value="cargo_user">Função</option>
						<option value="admin">Administrador</option>
						<option value="data_cadastro">Dia</option>
					</select>
				</div>
				<div>
					<label for="av_filtro">Buscar</label><br>
					<select name="coluna" id="av_filtro">
						<option disabled="" selected="">--Escolha--</option>
						<option value="id_user">Id</option>
						<option value="nome_user">Nome</option>
						<option value="cpf_user">CPF</option>
						<option value="cargo_user">Função</option>
						<option value="admin">Administrador</option>
						<option value="data_cadastro">Dia</option>
					</select>
				</div>
				<div>
					<label for="buscar">Buscar</label> <br>
					<input type="text" name="criterio" id="buscar" placeholder="Digite o criterio">
				</div>
				<button type="submit" name="filtro-enviar" class="btn_verde">Filtrar</button>
				<button type="reset" name="filtro-resetar" class="btn_vermelho">Desmarcar</button>
			</fieldset>
		</form>
		<!-- Tabela para mostrar o resultado da pesquisa -->
		<form method="post">
		<span class='totReg'>Quantidade de Registro <strong id="mudaValor">----</strong> <span id="totMudaValor">----</span></span>
			<?php
				$limitesEnvio = $_POST['paginas'];
				if (isset($_POST['filtro-enviar']) or !empty($_POST['sql']) or !empty($_POST['coluna']) or !empty($_POST['criterio']))
				{
					$rec_sql = $_POST['sql'];
					$rec_coluna = $_POST['coluna'];
					$rec_criterio = $_POST['criterio'];

					// removendo as antigas sessões
					unset($_SESSION['sqlGuard']);
					unset($_SESSION['colunaGuard']);
					unset($_SESSION['criterioGuard']);

					//guardando valores em sessões
					$_SESSION['sqlGuard'] = $rec_sql;
					$_SESSION['colunaGuard'] = $rec_coluna;
					$_SESSION['criterioGuard'] = $rec_criterio;

					filtro($rec_sql,$rec_coluna,$rec_criterio,$limitesEnvio,$con_bancobiblioteca);
				}
				else
				{
					// removendo as antigas sessões
					unset($_SESSION['sqlGuard']);
					unset($_SESSION['colunaGuard']);
					unset($_SESSION['criterioGuard']);

					//guardando valores em sessões
					$rec_sql = $_SESSION['sqlGuard'];
					$rec_coluna = $_SESSION['colunaGuard'];
					$rec_criterio = $_SESSION['criterioGuard'];

					filtro($rec_sql,$rec_coluna,$rec_criterio,$limitesEnvio,$con_bancobiblioteca);
				}

			?>
		</table>

		<fieldset class="excluir_flutua">
			<legend>Acão</legend>
			<div class="controle_flutua">
				<button type="submit" name="excluir_enviar" class="excluir_enviar">Excluir</button>
				<button name="editar_enviar" class="editar_enviar">Editar</button>
				<button type="reset" name="desfazer_enviar" class="desfazer_enviar">Desmarcar</button>
			</div>
			<?php
			$alteracao_dados_id= array();
				if (isset($_POST['excluir_enviar']) and isset($_POST['excluir'])) {
					$id = $_POST['excluir'];
					apagar($id);
				}
				elseif (isset($_POST['editar_enviar']) and isset($_POST['excluir']))
				{
					
					$id = $_POST['excluir'];
					$con_alte = mysqli_connect('localhost','root','','biblioteca');
					// $con_alte = mysqli_connect('localhost','1119325','biblioteca','1119325');
					mysqli_set_charset($con_alte,"utf8");
					$sql_alteracao = "SELECT * FROM usuario";
					$query_alte = mysqli_query($con_alte,$sql_alteracao);
					while ($ar_alte = mysqli_fetch_array($query_alte))
					{
						$id_user_alt = $ar_alte['id_user'];
						if ($id==$id_user_alt)
						{
							$nome_alt = $ar_alte['nome_user'];
							$cpf_alt = $ar_alte['cpf_user'];
							$senha_alt = $ar_alte['senha_user'];
							$img_alt = $ar_alte['src_imagem'];
							if ($img_alt==null) {
								$img_alt="padrao.png";
							}
							$cargo_alt = $ar_alte['cargo_user'];
							$admin_alt = $ar_alte['admin'];
							break;
						}
					}
					$chamar= "corpo bloco-alteracao ";
					mysqli_close($con_alte);

				}
				elseif(isset($_POST['editar_enviar']) and isset($_POST['excluir'])==null)
				{
					echo "<span class='info'>Selecione um usuário</span>";
				}
			?>
			
		</fieldset>
		</form>
		</div>
		</section>
		<?php echo "<section class='".$chamar."alt_alt' id='muda'>"; ?>

			<form method="post">
				<h1>Dados para fazer alteração</h1>
			<div class="contCad">
				<div>
					<label for="usuario2">Nome do Usuário</label>
					<span><?php echo $nome_alt; ?></span>
					<input type="text" name="usuario2" id="usuario2">
					<input type="hidden" name="id-post" value="<?php echo $_POST['excluir']; ?>">
				</div>
				<div>
					<label for="cpf2">CPF</label>
					<span><?php echo $cpf_alt; ?></span>
					<input type="text" name="cpf2" id="cpf2">
				</div>
				<div>
					<label for="senha2">Senha</label>
					<span><?php echo $senha_alt; ?></span>
					<input type="password" name="senha2" id="senha2">
				</div>
				<div>
					<label for="cargo2">Cargo</label>
					<span><?php echo $cargo_alt; ?></span>
					<select name="cargo2" id="cargo2">
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
			</div>
			<div class="contCad desce">
				
				<div>
					<label for="admin2">Administrador</label>
					<span><?php echo $admin_alt; ?></span>
					<select name="admin2" id="admin2">
						<option disabled="" selected="">-- Escolha --</option>
						<option value="sim">Sim</option>
						<option value="nao">Não</option>
					</select>
				</div>
				<div>
					<label for="img2">Imagem</label>
					<span><?php echo $img_alt; ?></span>
					<input type="file" name="img2" id="img2">
				</div>
				<div>
					<?php echo "<img src='arquivos/fotos/usuarios/".$img_alt."' class='perfil-alteracao'>"; ?>
				</div>
			</div>
			<div class="envioLivro">
				<div class="submit">
					<input type="submit" value="Cadastrar" name="cadastrar2">
				</div>
				<div class="submit">
					<input type="reset" value="Limpar Campos" name="descartar">
				</div>
			</div>
			</form>
			<?php
			
			if (isset($_POST['cadastrar2']))
			{
				$usuario2 = $_POST['usuario2'];
				if ($usuario2!=null) {
					$cusuario = "nome_user='$usuario2',";
					$atual = $cusuario;
				}
				$cpf2 = $_POST['cpf2'];
				if ($cpf2!=null) {
					$ccpf = "cpf_user='$cpf2',";
					$atual = $atual."".$ccpf;
				}
				$senha2 = $_POST['senha2'];
				if ($senha2!=null) {
					$csenha = "senha_user='$senha2',";
					$atual = $atual."".$csenha;
				}
				$src_imagem2 = $_POST['img2'];
				if ($src_imagem2!=null) {
					$csrc_imagem = "src_imagem='$src_imagem2',";
					$atual = $atual."".$csrc_imagem;
				}
				$cargo2 = $_POST['cargo2'];
				if ($cargo2!=null) {
					$ccargo = "cargo_user='$cargo2',";
					$atual = $atual."".$ccargo;
				}
				$admin2 = $_POST['admin2'];
				if ($admin2!=null) {
					$cadmin = "admin='$admin2',";
					$atual = $atual."".$cadmin;
				}
				if ($atual!="") {
					// $cone_alterar_user = mysqli_connect("localhost","root","","biblioteca") or die("erro fatal banco");
					$cone_alterar_user = mysqli_connect('localhost','1119325','biblioteca','1119325');
					$id_post = $_POST['id-post'];
					$atual = "UPDATE usuario SET ".$atual."data_cadastro='".$data."' WHERE id_user='".$id_post."'";
					mysqli_query($cone_alterar_user,$atual) or die("erro fatal consulta");
					mysqli_close($cone_alterar_user);
					echo "<meta http-equiv='refresh' content='1'>";
				}
			}
			?>
			
			<div id="fechar" onclick="nossa();">Fechar</div>
		</section>
	</body>
</html>