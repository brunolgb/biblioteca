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
		function apagar($apagar_receber,$bancobiblioteca)
		{
			echo "<meta http-equiv='REFRESH' content='2'>";
			$sql_apagar = "DELETE FROM aluno WHERE id_aluno=$apagar_receber";
			mysqli_query($bancobiblioteca,$sql_apagar) or die ("<div class='info info_erro'>Erro ao excluir</div>");
			echo "<div class='info'>O registro $apagar_receber foi apagado com sucesso</div>";
		}

		// Variavel para contar os registros
		$contador_registro = 0;

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


				if ($sql==null and $coluna==null and $criterio==null)
				{
					$sql_fun = "SELECT * FROM aluno".$limit;
					$funcao = "SELECT COUNT(id_aluno) FROM aluno";
				}
				else if ($sql!=null AND $coluna==null AND $criterio!=null)
				{
					$sql_fun = "SELECT * FROM aluno WHERE nome_aluno LIKE '%$criterio%' OR data_nasc LIKE '%$criterio%' OR turno LIKE '%$criterio%' OR ano_aluno LIKE '%$criterio%' OR turma LIKE '%$criterio%' OR prof LIKE '%$criterio%'  ORDER BY ".$sql."".$limit;
					$funcao = "SELECT COUNT(id_aluno) FROM aluno WHERE nome_aluno LIKE '%$criterio%' OR data_nasc LIKE '%$criterio%' OR turno LIKE '%$criterio%' OR ano_aluno LIKE '%$criterio%' OR turma LIKE '%$criterio%' OR prof LIKE '%$criterio%' ORDER BY ".$sql;
				}
				else if ($sql==null AND $coluna==null AND $criterio!=null)
				{
					$sql_fun = "SELECT * FROM aluno WHERE nome_aluno LIKE '%$criterio%' OR data_nasc LIKE '%$criterio%' OR turno LIKE '%$criterio%' OR ano_aluno LIKE '%$criterio%' OR turma LIKE '%$criterio%' OR prof LIKE '%$criterio%'".$limit;
					$funcao = "SELECT COUNT(id_aluno) FROM aluno WHERE nome_aluno LIKE '%$criterio%' OR data_nasc LIKE '%$criterio%' OR turno LIKE '%$criterio%' OR ano_aluno LIKE '%$criterio%' OR turma LIKE '%$criterio%' OR prof LIKE '%$criterio%'";
				}
				else if ($sql!=null or $coluna!=null and $criterio==null)
				{
					//Pegando qual option foi selecionado
					if ($sql == null) {
						$sql = "nome_aluno";
					}
					if (strstr($coluna,"*",true)=='a') {
						$coluna = substr($coluna, 2);
						$colunaAno = $coluna[0];
						$colunaTurma = $coluna[1];
						$sql_fun = "SELECT * FROM aluno WHERE ano_aluno='$colunaAno' AND turma='$colunaTurma' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_aluno) FROM aluno WHERE ano_aluno='$colunaAno' AND turma='$colunaTurma' ORDER BY $sql";
					}
					elseif (strstr($coluna,"*",true)=='b') {
						$coluna = substr($coluna, 2);
						$sql_fun = "SELECT * FROM aluno WHERE turno='$coluna' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_aluno) FROM aluno WHERE turno='$coluna' ORDER BY $sql";
					}
					elseif (strstr($coluna,"*",true)=='c') {
						$coluna = substr($coluna, 2);
						$sql_fun = "SELECT * FROM aluno WHERE prof='$coluna' ORDER BY $sql".$limit;
						$funcao = "SELECT COUNT(id_aluno) FROM aluno WHERE prof='$coluna' ORDER BY $sql";
					}
				}
				
				// paginação
				$paginando = mysqli_query($bancobiblioteca,$funcao);
				while ($p = mysqli_fetch_array($paginando)){$resulPaginacao = $p['COUNT(id_aluno)'];}
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
						<td class='largGGE'>Nome do Aluno</td>
						<td class='largM'>Nascimento</td>
						<td>Turno</td>
						<td>ano</td>
						<td class='largP'>Turma</td>
						<td class='largM'>Professor</td>
						<td  class='largP'>usuario</td>
						<td class='largM'>Dia</td>
						<td class='largP'>Acão</td>
					</tr> ";

				//Ver os usuarios						
				$guard_Usuario = array();
				$sql_usuario = "select id_user, nome_user from usuario";
				$resul_usuario = mysqli_query($bancobiblioteca,$sql_usuario);
				
				while ($auser = mysqli_fetch_array($resul_usuario))
				{
					$id_usuario = $auser['id_user'];
					$nome_usuario = $auser['nome_user'];
					$guard_Usuario[$id_usuario]=$nome_usuario;
				}
				$r = mysqli_query($bancobiblioteca,$sql_fun) or die("Não foi possivel consultar alunos");
				while ($array_aluno = mysqli_fetch_array($r))
				{
					$contador_registro ++;
					$id_aluno = $array_aluno['id_aluno'];
					$nome_aluno = $array_aluno['nome_aluno'];
					$nasc_aluno = $array_aluno['data_nasc'];
					$turno = $array_aluno['turno'];
					$ano = $array_aluno['ano_aluno'];
					$turma = $array_aluno['turma'];
					$prof = $array_aluno['prof'];
					if ($prof == null) {
						$prof = "-----";
					}
					$profEsp = substr("$prof",0, 20);
					// $img = $array_aluno['img'];
					$user_cadastrou = $array_aluno['user_cadastrou'];
					$data_cadastrou = $array_aluno['data_cadastro'];
					$id_userdba = $guard_Usuario[$user_cadastrou];
					$id_userdbaMai = strstr($id_userdba, " ", true);;

					$datadb = $alivro['data_cadastro'];
					echo "	<tr>
							<td>".$id_aluno."</td>
						
							<td>".$nome_aluno."</td>
							<td>".$nasc_aluno."</td>
							<td>".$turno."</td>
							<td>".$ano."</td>
							<td>".$turma."</td>
							<td title='".$prof."'>".$profEsp."</td>
							<td class='maiuscula'>".$id_userdbaMai."</td>
							<td>".$data_cadastrou."</td>
							<td>
								<input type='radio' name='excluir' value='".$id_aluno."' class='btn_radio'>
							</td>
						</tr>";
					echo "<script>muda('mudaValor','".$contador_registro."');</script>";
				
				}
				echo "</table>";
				$classe = "class='corpo bloco-alteracao'";
			}
			function ver($cb)
			{
				//comandos para funcionar
				$distinct_anoTurma = "SELECT DISTINCT ano_aluno,turma FROM aluno ORDER BY ano_aluno";
				$distinct_turno = "SELECT DISTINCT turno FROM aluno ORDER BY turno";
				$distinct_prof = "SELECT DISTINCT prof FROM aluno ORDER BY prof";

				//querys
				$query_anoTurma = mysqli_query($cb,$distinct_anoTurma);
				$query_turno = mysqli_query($cb,$distinct_turno);
				$query_prof = mysqli_query($cb,$distinct_prof);

				//começando os optgroup e option
				//ano e turma
				echo "<optgroup label='Ano e Turma'>";
				while ($a = mysqli_fetch_array($query_anoTurma))
				{
					$anoTurma = $a['ano_aluno'];
					$turmaoAno = $a['turma'];
					$tudoAnoTurma = $anoTurma."".$turmaoAno;
					echo "<option value='a*".$tudoAnoTurma."'>".$tudoAnoTurma."</option>";
				}
				echo "</optgroup>";
				//ver os turnos
				echo "<optgroup label='Turno'>";
				while ($b = mysqli_fetch_array($query_turno))
				{
					$turno_option = $b['turno'];
					if ($turno_option == 'v')
					{
						$turnoInteiro = "Vespertino";
					}
					else if($turno_option == 'm')
					{
						$turnoInteiro = "Matutino";
					}
					echo "<option value='b*".$turno_option."'>".$turnoInteiro."</option>";
				}
				echo "<optgroup label='Professor'>";
				while ($c = mysqli_fetch_array($query_prof))
				{
					$profe = $c['prof'];
					$profeInteiro = $profe;
					if ($profe == null)
					{
						$profeInteiro = 'Vazio';
					}
					echo "<option value='c*".$profe."'>".$profeInteiro."</option>";
				}
				echo "</optgroup>";
			}

		?>
		<section class="corpo">
			<h1>Todos os alunos</h1>
		<form method="post" id="filtros">
			<fieldset class="mostrar-livros filtro">
				<legend>Criar Filtro</legend>
				<div>
					<label for="criar_relatorio">Organizar por</label><br>
					<select name="sql" id="criar_relatorio">
						<option disabled="" selected="">--Escolha--</option>
						<option value="id_aluno">Id</option>
						<option value="nome_aluno">Nome</option>
						<option value="data_nasc">Nascimento</option>
						<option value="turno">Turno</option>
						<option value="ano_aluno">Ano</option>
						<option value="turma">Turma</option>
						<option value="prof">Professor</option>
						<option value="user_cadastrou">Usuario</option>
						<option value="data_cadastro">Dia</option>
					</select>
				</div>
				<div>
					<label for="av_filtro">Pesquisa Pronta</label><br>
					<select name="pesqPronta" id="av_filtro">
						<option disabled="" selected="">--Escolha--</option>
						<?php
							ver($con_bancobiblioteca);
						?>
					</select>
				</div>
				<div>
					<label for="buscar_banco">Buscar</label><br>
					<input type="text" name="criterio" id="buscar_banco" placeholder="Buscar">
				</div>
				
				<button type="submit" name="filtro-enviar" class="btn_verde">Filtrar</button>
				<button type="reset" name="filtro-resetar" class="btn_vermelho">Desmarcar</button>
			</fieldset>
		</form>
		<!-- Tabela para mostrar o resultado da pesquisa -->
		<span class='totReg'>Quantidade de Registro <strong id="mudaValor">----</strong> <span id="totMudaValor">----</span></span>
		<form method="post">
		
			<?php
				$limitesEnvio = $_POST['paginas'];
				if (isset($_POST['filtro-enviar']) or !empty($_POST['sql']) or !empty ($_POST['pesqPronta']) or !empty ($_POST['criterio']))
				{
					$rec_sql = $_POST['sql'];
					$rec_coluna = $_POST['pesqPronta'];
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
								apagar($id,$con_bancobiblioteca);
							}
							elseif (isset($_POST['editar_enviar']) and isset($_POST['excluir']))
							{
								
								$id = $_POST['excluir'];
								$con_alte = mysqli_connect('localhost','root','','biblioteca');
								// $con_alte = mysqli_connect('localhost','1119325','biblioteca','1119325');
								$sql_alteracao = "SELECT * FROM aluno";
								$query_alte = mysqli_query($con_alte,$sql_alteracao);
								while ($ar_alte = mysqli_fetch_array($query_alte))
								{
									$id_aluno_alt = $ar_alte['id_aluno'];
									if ($id==$id_aluno_alt)
									{
										$nome_aluno_alt = $ar_alte['nome_aluno'];
										$nasc_aluno_alt = $ar_alte['data_nasc'];
										$turno_alt = $ar_alte['turno'];
										$ano_alt = $ar_alte['ano_aluno'];
										$turma_alt = $ar_alte['turma'];
										$prof_alt = $ar_alte['prof'];
										$img_alt = $ar_alte['img'];
										break;
									}
								}
								$chamar= "corpo bloco-alteracao ";

							}
							elseif(isset($_POST['editar_enviar']) or isset($_POST['excluir_enviar']) and isset($_POST['excluir'])==null)
							{
								echo "<span class='info'>Selecione um aluno</span>";
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

				<!-- categoria dos livros -Educação Infantil e Ensino Fundamental -->
				<div>
					<label for="aluno">Nome do Aluno<strong>*</strong></label>
					<span><?php echo $nome_aluno_alt; ?></span>
					<input type="text" name="aluno" id="aluno">
					<input type="hidden" name="id-post" value="<?php echo $_POST['excluir']; ?>">

					
				</div>
				<div>
					<label for="nasc">Nascimento<strong>*</strong></label>
					<span><?php echo $nasc_aluno_alt; ?></span>
					<input type="date" name="nasc" id="nasc">
				</div>
				<div>
					<label for="turno">Turno<strong>*</strong></label>
					<span><?php echo $turno_alt; ?></span>
					<select name="turno" id="turno">
						<option value="" disabled="disabled" selected="selected">--Escolha--</option>
						<option value="m">Matutino</option>
						<option value="v">Vespertino</option>
						<option value="n">Noturno</option>
					</select>
				</div>
				<div>
					<label for="ano">Ano<strong>*</strong></label>
					<span><?php echo $ano_alt; ?></span>
					<select name="ano" id="ano" class="largPM">
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
					<span><?php echo $turma_alt; ?></span>
					<select name="turma" id="turma"  >
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
					<span><?php echo $prof_alt; ?></span>
					<input type="text" name="prof" id="prof">
				</div>
				<div>
					<label for="img">Imagem</label>
					<span><?php echo $img_alt; ?></span>
					<input type="file" name="img" id="img">
				</div>
				<div>
					<?php echo "<img src='arquivos/fotos/alunos/".$img_alt."'>"; ?>
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
				$aluno = $_POST['aluno'];
				if ($aluno!=null) {
					$caluno = "nome_aluno='$aluno',";
					$atual = $caluno;
				}
				$nasc = $_POST['nasc'];
				if ($nasc!=null) {
					$cnasc = "data_nasc='$nasc',";
					$atual = $atual."".$cnasc;
				}
				$turno = $_POST['turno'];
				if ($turno!=null) {
					$cturno = "turno='$turno',";
					$atual = $atual."".$cturno;
				}
				$ano = $_POST['ano'];
				if ($ano!=null) {
					$cano_aluno = "ano_aluno='$ano',";
					$atual = $atual."".$cano_aluno;
				}
				$turma = $_POST['turma'];
				if ($turma!=null) {
					$cturma = "turma='$turma',";
					$atual = $atual."".$cturma;
				}
				$prof = $_POST['prof'];
				if ($prof!=null) {
					$cprof = "prof='$prof',";
					$atual = $atual."".$cprof;
				}
				$img= $_POST['img'];
				if ($img != null) {
					$cimg = "img='$img'";
					$atual = $atual."".$cimg;
				}
				if ($atual!="") {
					$cone_alterar = mysqli_connect("localhost","root","","biblioteca");
					// $cone_alterar = mysqli_connect('localhost','1119325','biblioteca','1119325');
					$id_post = $_POST['id-post'];
					$atual = "UPDATE aluno SET ".$atual."user_cadastrou='".$id_user."',data_cadastro='".$data."' WHERE id_aluno='".$id_post."'";
					mysqli_query($cone_alterar,$atual);
					echo "<meta http-equiv='refresh' content='1'>";
				}
			}
			?>
			
			<div id="fechar" onclick="nossa();">Fechar</div>
		</section>
		<?php mysqli_close($con_bancobiblioteca); ?>
		<?php include('rodape.php'); ?>
	</body>
</html>