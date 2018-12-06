<?php session_start();?>
<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<link rel="shorcut icon" href="arquivos/icone.png">
		<script src="arquivos/estilo.js">
			
		</script>
		<title>Biblioteca Municipal de Comodoro</title>
	</head>
	<body>
		<?php
			$id_user = $_SESSION['id_user'];
			$data = date('Y/m/d');
			include('nav.php');
		function apagar($apagar_receber,$bancobiblioteca)
		{
			echo "<meta http-equiv='REFRESH' content='2'>";
			$sql_apagar = "DELETE FROM livro WHERE id_livro=$apagar_receber";
			echo "<span class='info'>O registro $apagar_receber foi apagado com sucesso</span>";
			mysqli_query($bancobiblioteca,$sql_apagar) or die ("<div class='info info_erro'>Erro ao excluir</div>");
		}
		
		// Variavel para contar os registros
		$contador_registro = 0;
		$guarda = array();
		function filtro($sql,$coluna,$criterio,$limites,$bancobiblioteca)
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
				if ($sql==null and $coluna==null and $criterio==null)
				{
					$sql_fun = "SELECT * FROM livro".$limit;
					$funcao = "SELECT COUNT(id_livro) FROM livro";
				}
				else if ($sql!=null AND $coluna==null AND $criterio!=null)
				{
					$sql_fun = "SELECT * FROM livro WHERE nome_livro LIKE '%$criterio%' OR autor_livro LIKE '%$criterio%' OR editora_livro LIKE '%$criterio%' OR descricao LIKE '%$criterio%' ORDER BY ".$sql."".$limit;;
					$funcao = "SELECT COUNT(id_livro) FROM livro WHERE nome_livro LIKE '%$criterio%' OR autor_livro LIKE '%$criterio%' OR editora_livro LIKE '%$criterio%' OR descricao LIKE '%$criterio%' ORDER BY ".$sql;
				}
				else if ($sql==null AND $coluna==null AND $criterio!=null)
				{
					$sql_fun = "SELECT * FROM livro WHERE nome_livro LIKE '%$criterio%' OR autor_livro LIKE '%$criterio%' OR editora_livro LIKE '%$criterio%' OR descricao LIKE '%$criterio%'".$limit;
					$funcao = "SELECT COUNT(id_livro) FROM livro WHERE nome_livro LIKE '%$criterio%' OR autor_livro LIKE '%$criterio%' OR editora_livro LIKE '%$criterio%' OR descricao LIKE '%$criterio%'";
				}
				else if ($sql!=null or $coluna!=null and $criterio==null)
				{
					//Pegando qual option foi selecionado
					if ($sql == null) {
						$sql = "nome_livro";
					}
					if (strstr($coluna,"*",true)=='a') {
						$coluna = substr($coluna, 2);
						$sql_fun = "SELECT * FROM livro WHERE local_livro='$coluna' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_livro) FROM livro WHERE local_livro='$coluna' ORDER BY $sql";
					}
					elseif (strstr($coluna,"*",true)=='b') {
						$coluna = substr($coluna, 2);
						$sql_fun = "SELECT * FROM livro WHERE disponivel='$coluna' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_livro) FROM livro WHERE disponivel='$coluna' ORDER BY $sql";
					}
					elseif (strstr($coluna,"*",true)=='c') {
						$coluna = substr($coluna, 2);
						$sql_fun = "SELECT * FROM livro WHERE user_cadastrou='$coluna' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_livro) FROM livro WHERE user_cadastrou='$coluna' ORDER BY $sql";
					}
				}

				// paginação
				$paginando = mysqli_query($bancobiblioteca,$funcao);
				while ($p = mysqli_fetch_array($paginando)){$resulPaginacao = $p['COUNT(id_livro)'];}
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
				echo"
				<table class='dbLivro'>
				<tr id='titTable'>
					<td class='largP'>Id</td>
					<td class='largGG'>Nome do Livro</td>
					<td class='largP'>QTD</td>
					<td class='largM'>Autor</td>
					<td class='largM'>Editora</td>
					<td>Local</td>
					<td class='largM'>Descrição</td>
					<td class='largP'>Disp.</td>
					<td>Usuario</td>
					<td  class='largM'>Dia</td>
					<td class='largP' title='Você só pode fazer alteração em um por vez'>Ação</td>
				</tr>";

				//Ver os usuarios
				$com_usuario = "select id_user,nome_user from usuario";
				$resul_usuario = mysqli_query($bancobiblioteca,$com_usuario);
				$posicao = 1;

				while ($auser = mysqli_fetch_array($resul_usuario)){
					$id_usuario = $auser['id_user'];
					$nome_usuario = $auser['nome_user'];
					$guard_Usuario[$posicao]=$nome_usuario;
					$posicao++;
				}
				$r = mysqli_query($bancobiblioteca,$sql_fun) or die("Não foi possivel fazer consulta");
				while ($alivro = mysqli_fetch_array($r))
				{
					$contador_registro ++;
					$id_livrodb = $alivro['id_livro'];
					$nome_livrodb = $alivro['nome_livro'];
					$qtd_livrodb = $alivro['qtd'];
					$autor_livrodb = $alivro['autor_livro'];
					$autor_livrodb = substr($autor_livrodb, 0,17);
					$editora_livrodb = $alivro['editora_livro'];
					$editora_livrodb = substr($editora_livrodb, 0,17);
					$local_livrodb = $alivro['local_livro'];
					$descricaodb = $alivro['descricao'];
					if ($descricaodb == null) {
						$descricaoMODI = "N/A";
					}
					else if($descricaodb[11] != null)
					{
						$descricaoMODI = substr($descricaodb, 0,10)."...";
					}
					$disponivel = $alivro['disponivel'];
					$id_userdb = $alivro['user_cadastrou'];
					$id_userdba = $guard_Usuario[$id_userdb];
					$datadb = $alivro['data_cadastro'];
					echo "	<tr>
							<td>".$id_livrodb."</td>
							<td>".$nome_livrodb."</td>
							<td>".$qtd_livrodb."</td>
							<td>".$autor_livrodb."</td>
							<td>".$editora_livrodb."</td>
							<td>".$local_livrodb."</td>
							<td>".$descricaoMODI."</td>
							<td>".$disponivel."</td>
							<td>";
					echo strstr($id_userdba," ",true);
					echo "</td>
							<td>".$datadb."</td>
							<td>
								<input type='radio' name='excluir' value='".$id_livrodb."' class='btn_radio'>
							</td>
						</tr>";
					echo "<script>muda('mudaValor','".$contador_registro."');</script>";
				}
				echo "</table>";
			}
			function ver($cb)
			{
				//comandos para funcionar
				$distinct_c1 = "SELECT DISTINCT local_livro FROM livro ORDER BY local_livro";
				$distinct_c2 = "SELECT DISTINCT disponivel FROM livro ORDER BY disponivel";
				$distinct_c3 = "SELECT DISTINCT user_cadastrou,id_user,nome_user FROM livro,usuario ORDER BY user_cadastrou";

				//querys
				$execute_q1 = mysqli_query($cb,$distinct_c1);
				$execute_q2 = mysqli_query($cb,$distinct_c2);
				$execute_q3 = mysqli_query($cb,$distinct_c3);

				//começando os optgroup e option
				//ano e turma
				echo "<optgroup label='Locais'>";
				while ($a = mysqli_fetch_array($execute_q1))
				{
					$local_pp = $a['local_livro'];
					if ($local_pp == null)
					{
						$local_ppInteiro = "Vazio";
					}
					else
					{
						$local_ppInteiro = $local_pp;
					}
					echo "<option value='a*".$local_pp."'>".$local_ppInteiro."</option>";
				}
				echo "</optgroup>";
				//ver os turnos
				echo "<optgroup label='Disponivel'>";
				while ($b = mysqli_fetch_array($execute_q2))
				{
					$disponivel_pp = $b['disponivel'];
					echo "<option value='b*".$disponivel_pp."'>".$disponivel_pp."</option>";
				}
				echo "<optgroup label='Usúario'>";
				while ($c = mysqli_fetch_array($execute_q3))
				{
					$user_tbl1 = $c['user_cadastrou'];
					$iduser_tbl2 = $c['id_user'];
					$nomeuser_tbl2 = $c['nome_user'];
					if ($user_tbl1 == $iduser_tbl2)
					{
						echo "<option value='c*".$user_tbl1."'>".$nomeuser_tbl2."</option>";
					}
				}
				echo "</optgroup>";
			}
		?>
		<section class="corpo">
			<h1>Todos Livros</h1>
			<form method="post" id="filtros">
				<fieldset class="mostrar-livros filtro">
					<legend>Criar Filtro</legend>
					<div>
						<label for="criar_relatorio">Organizar por</label><br>
						<select name="sql" id="criar_relatorio">
							<option disabled="" selected="">--Escolha--</option>
							<option value="id_livro">Id</option>
							<option value="nome_livro">Nome</option>
							<option value="autor_livro">Autor</option>
							<option value="editora_livro">Editora</option>
							<option value="local_livro">local</option>
							<option value="descricao">Descrição</option>
							<option value="disponivel">Disponivel</option>
							<option value="user_cadastrou">Usuario</option>
							<option value="data_cadastro">Dia Cadastrado</option>
						</select>
					</div>
					<div>
						<label for="pesqPronta">Pesquisa pronta</label><br>
						<select name="coluna" id="pesqPronta">
							<option disabled="" selected="">--Escolha--</option>
							<?php
							ver($con_bancobiblioteca);
							?>
						</select>
					</div>
					<div>
						<label for="buscar">Buscar</label> <br>
						<input type="text" name="criterio" id="buscar" placeholder="Digite o criterio">
					</div>
					<button type="submit" name="filtro-enviar" class="btn_verde">Filtrar</button>
					<button type="reset" name="filtro-resetar" class="btn_vermelho">Desmarcar</button>
				</fieldset>
			
				<!-- Tabela para mostrar o resultado da pesquisa -->
				<div class="todosLivros">
					<span class='totReg'>Quantidade de Registro <strong id="mudaValor">----</strong> <span id="totMudaValor">----</span></span>
						<?php
							$limitesEnvio = $_POST['paginas'];
							if (isset($_POST['filtro-enviar']) or !empty($_POST['sql']) or !empty ($_POST['coluna']) or !empty ($_POST['criterio']))
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
							else{
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
					<fieldset class="excluir_flutua">
						<legend>Acão</legend>
						<div class="controle_flutua">
							<button type="submit" name="excluir_enviar" class="excluir_enviar">Excluir</button>
							<button name="editar_enviar" class="editar_enviar">Editar</button>
							<button type="reset" name="desfazer_enviar" class="desfazer_enviar">Desmarcar</button>
						</div>
						<?php
						$alteracao_dados_id= array();
							if (isset($_POST['excluir_enviar']) and isset($_POST['excluir']))
							{
								$id = $_POST['excluir'];
								apagar($id,$con_bancobiblioteca);
							}
							else if (isset($_POST['editar_enviar']) and isset($_POST['excluir']))
							{
								$id = $_POST['excluir'];
								$con_alte = mysqli_connect('localhost','root','','biblioteca');
								// $con_alte = mysqli_connect('localhost','1119325','biblioteca','1119325');
								$sql_alteracao = "SELECT * FROM livro WHERE id_livro='".$id."'";
								$query_alte = mysqli_query($con_alte,$sql_alteracao);
								while ($ar_alte = mysqli_fetch_array($query_alte))
								{
									$id_livro_alt = $ar_alte['id_livro'];
									$nome_livro_alt = $ar_alte['nome_livro'];
									$qtd_livro_alt = $ar_alte['qtd'];
									$autor_livro_alt = $ar_alte['autor_livro'];
									$editora_alt = $ar_alte['editora_livro'];
									$local_alt = $ar_alte['local_livro'];
									$descricao_alt = $ar_alte['descricao'];
									$disponivel_alt = $ar_alte['disponivel'];
									break;
								}
								$chamar= "corpo bloco-alteracao ";
								mysqli_close($con_alte);

							}
							else if(isset($_POST['editar_enviar']) or isset($_POST['excluir_enviar']) and isset($_POST['excluir'])==null)
							{
								echo "<span class='info'>Selecione um Livro</span>";
							}
						?>
						
					</fieldset>
				</div>
			</form>
		</section>
		<?php echo "<section class='".$chamar."alt_alt' id='muda'>"; ?>
			<form method="post">
				<h1>Dados para fazer alteração</h1>
			<div class="contCad">

				<!-- categoria dos livros -Educação Infantil e Ensino Fundamental -->
				<div>
					<label for="livro">Nome do Livro</label>
					<span><?php echo $nome_livro_alt; ?></span>
					<input type="text" name="livro" id="livro">
					<input type="hidden" name="id-post" value="<?php echo $_POST['excluir']; ?>">

					
				</div>
				<div>
					<label for="qtd">Quantidade</label>
					<span><?php echo $qtd_livro_alt; ?></span>
					<input type="text" name="qtd" id="qtd">			
				</div>
				<div>
					<label for="autor">Autor do Livro</label>
					<span><?php echo $autor_livro_alt; ?></span>
					<input type="text" name="autor" id="autor">
				</div>
				<div>
					<label for="editora">Editora do Livro</label>
					<span><?php echo $editora_alt; ?></span>
					<input type="text" name="editora" id="editora">
				</div>
				<div>
					<label for="local">Local do Livro</label>
					<span><?php echo $local_alt; ?></span>
					<input type="text" name="local" id="local">
				</div>
			</div>
			<div class="contCad desce">
				<div>
					<label for="descricao">Descrição do Livro</label>
					<span><?php echo $descricao_alt; ?></span>
					<textarea name="descricao" id="descricao" cols="30" rows="10"></textarea>
				</div>
				<div>
					<label for="disponivel">Disponivel?</label>
					<span><?php echo $disponivel_alt; ?></span>
					<select name="disponivel" id="disponivel">
						<option disabled="" selected="">--Escolha--</option>
						<option value="sim">sim</option>
						<option value="nao">nao</option>
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
			<?php
			
			if (isset($_POST['cadastrar']))
			{
				$livro = $_POST['livro'];
				if ($livro!=null) {
					$clivro = "nome_livro='$livro',";
					$atual = $clivro;
				}
				$qtdLivro = $_POST['qtd'];
				if ($qtdLivro!=null) {
					$cqtd = "qtd='$qtdLivro',";
					$atual = $cqtd;
				}
				$autor = $_POST['autor'];
				if ($autor!=null) {
					$cautor = "autor_livro='$autor',";
					$atual = $atual."".$cautor;
				}
				$editora = $_POST['editora'];
				if ($editora!=null) {
					$ceditora = "editora_livro='$editora',";
					$atual = $atual."".$ceditora;
				}
				$local = $_POST['local'];
				if ($local!=null) {
					$clocal = "local_livro='$local',";
					$atual = $atual."".$clocal;
				}
				$descricao = $_POST['descricao'];
				if ($descricao!=null) {
					$cdescricao = "descricao='$descricao',";
					$atual = $atual."".$cdescricao;
				}
				$disponivel = $_POST['disponivel'];
				if ($disponivel!=null) {
					$cdisponivel = "disponivel='$disponivel',";
					$atual = $atual."".$cdisponivel;
				}
				if ($atual!="") {
					$cone_alterar = mysqli_connect("localhost","root","","biblioteca");
					// $cone_alterar = mysqli_connect('localhost','1119325','biblioteca','1119325');
					$id_post = $_POST['id-post'];
					$atual = "UPDATE livro SET ".$atual."user_cadastrou='".$id_user."',data_cadastro='".$data."' WHERE id_livro='".$id_post."'";
					echo $atual;
					mysqli_query($cone_alterar,$atual);
					mysqli_close($cone_alterar);
					echo "<meta http-equiv='refresh' content='1'>";
				}
			}
			?>
			
			<div id="fechar" onclick="nossa();">Fechar</div>
		</section>
		<?php include('rodape.php'); ?>
	</body>
</html>