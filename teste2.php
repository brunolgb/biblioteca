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
			$data = date('Y-m-d');
			$coneccao_entrega =	mysqli_connect('localhost','root','','biblioteca') or die ("<div class='info info_erro'>Erro ao Entregar</div>");
		// $coneccao_entrega = mysqli_connect('localhost','1119325','biblioteca','1119325') or die("<div class='info info_erro'>Erro ao Entregar</div>");
			mysqli_set_charset($coneccao_entrega);
			
			//sql e query dos livros e array para armazenar os resultados
			$sql_livros = "select id_livro,nome_livro,disponivel from livro";
			$query_livro = mysqli_query($coneccao_entrega,$sql_livros);
			$gLivro = array();

			//sql e query dos alunos e array para armazenar os resultados
			$sql_aluno = "select id_aluno,nome_aluno,ano_aluno,turma from aluno";
			$query_aluno = mysqli_query($coneccao_entrega,$sql_aluno);
			$gAluno = array();

			$sql_emprestado = "SELECT ";
			//Barar de navegação
			include('nav.php');

			function acao($acao,$registro,$bancobiblioteca)
			{
				if ($acao == "ex")
				{
					$data_hoje = date("Y/m/d");
					$sql_entrega = "UPDATE emprestado SET ent='s',data_entrega='".$data_hoje."' WHERE id_emprestado='$registro'";
					// echo $sql_entrega;
					mysqli_query($bancobiblioteca,$sql_entrega) or die ("<div class='info info_erro'>Erro ao Entregar</div>");
				}
				else if($acao == "ed")
				{
					$editRegA = "SELECT id_aluno,nome_aluno from aluno";
					$editRegL = "SELECT id_livro,nome_livro from livro";
					$editarReg = "SELECT id_emprestado,id_aluno,id_livro,data_pegou,data_entrega,ent FROM emprestado";
					$editandoA = mysqli_query($bancobiblioteca,$editRegA);
					$editandoL = mysqli_query($bancobiblioteca,$editRegL);
					$editando = mysqli_query($bancobiblioteca,$editarReg);
					$al_array = array();
					while ($al = mysqli_fetch_array($editandoL))
					{
						$livro_id = $al['id_livro'];
						$livro_nome = $al['nome_livro'];
						$al_array[0][$livro_id] = $livro_nome;
					}
					while ($al = mysqli_fetch_array($editandoA))
					{
						$aluno_id = $al['id_aluno'];
						$aluno_nome = $al['nome_aluno'];
						$al_array[1][$aluno_id] = $aluno_nome;
					}
					while ($edita_busca = mysqli_fetch_array($editando))
					{
						$editEmprestado = $edita_busca['id_emprestado'];
						
						if ($registro == $editEmprestado)
						{
							$editLivro = $edita_busca['id_livro'];
							$editLivro = $al_array[0][$editLivro];
							$editAluno = $edita_busca['id_aluno'];
							$editAluno = $al_array[1][$editAluno];
							$editPegou = $edita_busca['data_pegou'];
							$editEntrega = $edita_busca['data_entrega'];
							$ent = $edita_busca['ent'];
							echo "<script>";
							echo "mudando('$editEmprestado','nome_livro','nome_aluno','data_pegou','data_entrega','$editLivro','$editAluno','$editPegou','$editEntrega','$ent')";
							echo "</script>";
							break;
							
						}
					}
				}
			}
			function emprestados($consulta_emp,$filtro,$bancobiblioteca)
			{
				$id_user = $_SESSION['id_user'];
				$data = date('Y/m/d');
				$data_atual_convertida = strtotime($data);

				//Consultar tabela livros
				$consulta_livro = "select id_livro,nome_livro from livro";
				$query_livroT = mysqli_query($bancobiblioteca,$consulta_livro);

				//Consultar tabela alunos
				$consulta_aluno = "select id_aluno,nome_aluno,ano_aluno,turma,img from aluno";
				$query_alunoT = mysqli_query($bancobiblioteca,$consulta_aluno);

				//Consultar tabela alunos
				$consulta_usuario = "select id_user,nome_user from usuario";
				$query_usuarioT = mysqli_query($bancobiblioteca,$consulta_usuario);
				$livro_aluno = array();
				//Recuperar os emprestimos feitos

				//Trocando as id pelos nomes no array
				while ($emp_livro = mysqli_fetch_array($query_livroT))
				{
					$id_tbl_livro = $emp_livro['id_livro'];
					$nome_tbl_livro = $emp_livro['nome_livro'];
					$livro_aluno[1][$id_tbl_livro] = $nome_tbl_livro;
				}
				while ($emp_aluno = mysqli_fetch_array($query_alunoT))
				{
					$id_tbl_aluno = $emp_aluno['id_aluno'];
					$nome_tbl_aluno = $emp_aluno['nome_aluno'];
					$serie_aluno = $emp_aluno['ano_aluno']." ".$emp_aluno['turma'];
					$img = $emp_aluno['img'];
					$livro_aluno[2][$id_tbl_aluno] = $nome_tbl_aluno;
					$livro_aluno[5][$id_tbl_aluno] = $serie_aluno;
					$livro_aluno[4][$id_tbl_aluno] = $img;
				}
				while ($emp_usuario = mysqli_fetch_array($query_usuarioT))
				{
					$id_tbl_user = $emp_usuario['id_user'];
					$nome_tbl_user = $emp_usuario['nome_user'];
					$livro_aluno[3][$id_tbl_user] = $nome_tbl_user;
				}
// identificando a coluna que é para ser mostrada
				$coluna_ent = " ent='".$consulta_emp."'";
				if($consulta_emp == null or $consulta_emp == 't')
				{
					$coluna_ent = "ent";
				}
// Buscar pelo criterio que o cliente usara 
				if (strpos($filtro, "Aluno"))
				{
					$filtro = strstr($filtro,"|",true);
					$res_filtro = " AND id_aluno LIKE '".$filtro."'";
				}
				else if (strpos($filtro, "Livro"))
				{
					$filtro = strstr($filtro,"|",true);
					$res_filtro = " AND id_livro LIKE '".$filtro."'";
				}

				$consulta_emp_com = "select * from emprestado WHERE $coluna_ent $res_filtro";

				//Consultar tabela emprestados
				$query_empT = mysqli_query($bancobiblioteca,$consulta_emp_com);

				$para_mudar_cont = 0;
				$cont_nome = 1;
				while ($emp_l = mysqli_fetch_array($query_empT))
				{
					$id_emprestado = $emp_l['id_emprestado'];
					$id_livro_emp = $emp_l['id_livro'];
					$id_do_livro = $livro_aluno[1][$id_livro_emp];
					if ($id_do_livro[25] != null)
					{
						$id_do_livro = substr($id_do_livro,0,25)."...";
					}
					$id_aluno = $emp_l['id_aluno'];
					$id_do_aluno = $livro_aluno[2][$id_aluno];
					if ($id_do_aluno[25] != null)
					{
						$id_do_aluno = substr($id_do_aluno,0,25)."...";
					}

					$serie_do_aluno = $livro_aluno[5][$id_aluno];
					$data_pegou = $emp_l['data_pegou'];
					$data_entrega = $emp_l['data_entrega'];
					$entrega = $emp_l['ent'];
					$user_cadastrou_emp = $emp_l['user_cadastrou'];
					$id_do_user = $livro_aluno[3][$user_cadastrou_emp];
					$data_cadastrou_emp = $emp_l['data_cadastrou'];
					$data_db_convertida = strtotime($data_entrega);
					$entre_data = $data_db_convertida - $data_atual_convertida;
					$input = "<input type='checkbox' name='".$cont_nome."' value='".$id_emprestado."' class='cadastrar'>";
					echo "<script>muda('mudaValor','".$cont_nome."');</script>";
					$cont_nome ++;
					$para_mudar_cont ++;
						
					if($entrega =="n")

					{
						if($entre_data >= 604801){
							$status = "<div class='status status_azul' title='Falta mais de uma semana'></div>";
						}
						else if ($entre_data <=604800 and $entre_data >= 86401) {
							$status = "<div class='status status_verde' title='Falta uma semana'></div>";
						}
						else if($entre_data <= 86400 and $entre_data >=0){
							$status = "<div class='status status_roxo' title='Hoje é o ultimo dia para entregar o livro'></div>";
						}
						else if($entre_data < 0){
							$status = "<div class='status status_vermelho' title='A entrega do livro esta atrasada'></div>";
						}

					}
					else if($entrega == "s")
					{
						$status = "<div class='status status_preto' title='Já está entregue'></div>";
						$input = "<input type='checkbox' name='".$cont_nome."' value='".$id_emprestado."' class='cadastrar' title='Você ja entregou esse livro'>";
					}
					
					echo "
					<tr class='mostrar-livros-outros'>
						<td  class='largP'>$id_emprestado</td>
						<td>$id_do_livro</td>
						
						<td>$id_do_aluno</td>
						<td>$serie_do_aluno</td>
						<td>$data_pegou</td>
						<td>$data_entrega</td>
						<td>".$status."</td>
						<td>";
						echo strstr($id_do_user," ",true)."</td>
						<td>$data_cadastrou_emp</td>
						<td>$input</td>
					</tr>
					";
					
				}
				echo "<input type='hidden' name='valor_final' value='".$para_mudar_cont."' id='valor_final'>";
				if ($id_emprestado=="") {
					echo "<tr class='mostrar-livros-outros'>
						<td colspan='11'>Não há registros</td></tr>";
				}

				
			}
		?>
		<section class="corpo">
			<h1>Você está em Emprestimos de Livros <span id="alteracao"></span></h1>
			<form method="post">
				<div class="livros">
					<div id="entregue_input">

						<!-- botão para verificar se é uma alteracao ou um cadastro novo -->
						<input type="hidden" name="alteracao" id="verifAlt" value="">


						<label for="ent">Entregue?</label><br>
						<select name="ent" id="ent">
							<option value="n">Não</option>
							<option value="s">Sim</option>
						</select>
					</div>
					<div class="contCad">
						
						<div>
							<label for="nome_livro">Nome do Livro</label>
							<input type="text" name="nome_livro" id="nome_livro" list="lista_l" placeholder="Digite o nome do livro" required="required">
							<datalist id="lista_l">
								<?php 
								while ($la = mysqli_fetch_array($query_livro))
									{
										$idLivro = $la['id_livro'];
										$nomeLivro = $la['nome_livro'];
										$disponivel = $la['disponivel'];
										$gLivro[$idLivro] = $nomeLivro;
										if ($disponivel == "nao")
										{
											echo "<option value='".$gLivro[$idLivro]."' disabled>".$idLivro."</option>";
										}
										else
										{
											//guardando no arrays
											echo "<option value='".$gLivro[$idLivro]."'>".$idLivro."</option>";
										}
									}

								?>
							</datalist>
						</div>
						<div>
							<label for="nome_aluno">Nome do Aluno</label>
							<input type="text" name="nome_aluno" id="nome_aluno" list="lista_a" placeholder="Digite o nome do aluno"required="required">
							<datalist id="lista_a">
							<?php 
								while ($aa = mysqli_fetch_array($query_aluno))
									{
										$idaluno = $aa['id_aluno'];
										$nomealuno = $aa['nome_aluno'];
										$anoaluno = $aa['ano_aluno'];
										$turma = $aa['turma'];
										//guardando no arrays
										$gAluno[$idaluno] = $nomealuno;
										echo "<option value='".$gAluno[$idaluno]."'>".$anoaluno."". $turma."</option>";
									}
								?>
							</datalist>
						</div>
						<div>
							<label for="data_pegou">Data que pegou</label>
							<input type="date" name="data_pegou" id="data_pegou" required="required">
						</div>
						<div>
							<label for="data_entrega">Data para Entregar</label>
							<input type="date" name="data_entrega" id="data_entrega" required="required">
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
						if (isset($_POST['cadastrar']))
							{
								
								//Pegando a id do livro no array
								$nome_livro = $_POST['nome_livro'];
								//Pegando a id do livro no array
								$m=1;
								while ($m <= $idLivro)
								{
									$nomedolivro = $gLivro[$m];
									if ($nomedolivro == $nome_livro)
									{
										$guardarIdL = $m;
										break;
									}
									$m++;
								}
								//Pegando a id do aluno no array
								$nome_aluno = $_POST['nome_aluno'];
								$n=1;
								while ($n <= $idaluno)
								{
									$nomedoaluno = $gAluno[$n];
									if ($nomedoaluno == $nome_aluno)
									{
										$guardarIdA = $n;
										break;
									}
									$n++;
								}
								$data_pegou = $_POST['data_pegou'];
								$data_entrega = $_POST['data_entrega'];
								$entInput = $_POST['ent'];
								$alteracao = $_POST['alteracao'];
								if ($alteracao == null)
								{
								$comandolivro = "
								INSERT INTO emprestado (id_livro, id_aluno, data_pegou, data_entrega,ent, user_cadastrou, data_cadastrou)
								VALUES ('$guardarIdL', '$guardarIdA', '$data_pegou', '$data_entrega','n', '$id_user', '$data')";
								echo "<span class='info'>Emprestimo realizado com sucesso</span>";
								}
								else
								{
								$comandolivro = "
								UPDATE emprestado SET id_livro = '$guardarIdL', id_aluno ='$guardarIdA', data_pegou='$data_pegou', data_entrega='$data_entrega',ent='$entInput', user_cadastrou= '$id_user', data_cadastrou='$data' WHERE id_emprestado='".$alteracao."'";
								echo "<span class='info'>Alteracação feita com sucesso</span>";
								}
							
								
								// echo $comandolivro;
								mysqli_query($coneccao_entrega,$comandolivro);
								
							}
						?>
					</div>
				</div>
		</section>
		<section class="corpo">
			<div class="livros">
				<fieldset class="mostrar-livros">
					<legend>Lista dos Emprestimos  - Total <span id="mudaValor">---</span></legend>
					<form method="post">
					<section class="pesquisa">
						<?php
							$pesquisar_pronto_a = mysqli_query($coneccao_entrega,"SELECT id_aluno,nome_aluno,ano_aluno,turma from aluno");
							$pesquisar_pronto_l = mysqli_query($coneccao_entrega,"SELECT id_livro,nome_livro from livro");
							$pesquisar_pronto_e = mysqli_query($coneccao_entrega,"SELECT distinct id_livro from emprestado");
							$pesquisar_pronto_ee = mysqli_query($coneccao_entrega,"SELECT distinct id_aluno from emprestado");
							$pesquisar_pronto_d = mysqli_query($coneccao_entrega,"SELECT distinct data_pegou,data_entrega from emprestado");
							
							$buscando = array();
							$cont = 0;
							$cont2 = 0;
							while ($pesquisar_pronto_la = mysqli_fetch_array($pesquisar_pronto_l))
							{
								$pesquisar_idl = $pesquisar_pronto_la['id_livro'];
								$pesquisar_nomel = $pesquisar_pronto_la['nome_livro'];
							
								$buscando[2][$pesquisar_idl] = $pesquisar_nomel;
							}
							while ($pesquisar_pronto_aa = mysqli_fetch_array($pesquisar_pronto_a))
							{
								$pesquisar_ida = $pesquisar_pronto_aa['id_aluno'];
								$pesquisar_nomea = $pesquisar_pronto_aa['nome_aluno'];
							
								$buscando[0][$pesquisar_ida] = $pesquisar_nomea;
								$buscando[1][$pesquisar_ida] = $pesquisar_ida;
							}
						?>
						<input type="text" name="pesquisa_lial" placeholder="Pesquisar Livro ou Aluno" list="pesquisar_opcoes">
						<datalist id="pesquisar_opcoes">
						<?php 
							while ($pesquisar_pronto_earray1 = mysqli_fetch_array($pesquisar_pronto_e))
							{
								$pesquisar_idLivro = $pesquisar_pronto_earray1['id_livro'];

								$resultado_livro = $buscando[2][$pesquisar_idLivro];
								echo "<option value='".$pesquisar_idLivro."| Livro - ".$resultado_livro."'>$cont</option>";
								$cont ++;
							}						
							while ($pesquisar_pronto_earray2 = mysqli_fetch_array($pesquisar_pronto_ee))
							{
								$pesquisar_idAluno = $pesquisar_pronto_earray2['id_aluno'];
								$resultado_aluno = $buscando[0][$pesquisar_idAluno];
								echo "<option value='".$pesquisar_idAluno."| Aluno - ".$resultado_aluno."'>$cont2</option>";
								$cont2 ++;
							}
						?>
						</datalist>
						<select name="listas" id="listas">
							<option value="t" selected="">Todas</option>
							<option value="n">Não entregues</option>
							<option value="s">Entregues</option>
						</select>
						<input type="submit" name="pesq-enviar" value="Buscar" class="btn_verde">

					</section>
					</form>
					<form method="post">
					<table>
					<tr class='mostrar-livros-titulo'>
						<td class="largP">Id</td>
						<td class="largGG">Nome do Livro</td>
						<td class="largGG">Nome do Aluno</td>
						<td class="largP">Ano</td>
						<td class="largPM">Data pegou</td>
						<td class="largPM">Data entregar</td>
						<td>Status</td>
						<td class="largP">Usuário</td>
						<td class="largPM">Data Cadastro</td>
						<td class="largP">Ação</td>
					</div>
					<?php
					$listas = $_POST['listas']; //Recebedo as listas para mostrar
					if (isset($_POST['pesq-enviar']) and $_POST['pesquisa_lial']!=null and $_POST['serieData']==null)
					{
						$pesquisaValor = $_POST['pesquisa_lial'];
					}
					else if (isset($_POST['pesq-enviar']) and $_POST['pesquisa_lial']==null and $_POST['serieData']!=null)
					{
						$pesquisaValor = $_POST['serieData'];
					}

					
					emprestados($listas,$pesquisaValor,$coneccao_entrega);
					?>
					</table>
					<fieldset class="excluir_flutua">
						<legend>Acão</legend>
						<button type="submit" name="excluir_enviar" class="excluir_enviar" id="entregaINPUT">Entregar</button>
						<button type="submit" name="editar_enviar" class="editar_enviar">Editar</button>
						<button type="reset" name="desfazer_enviar" class="desfazer_enviar">Desmarcar</button>
						
						<?php
							if (isset($_POST['excluir_enviar']))
							{
								$acao_fazer = "ex";
								$contagem_campo = 1;
								$qtdReg = 0;
								$valorFinal = $_POST['valor_final'];
								for ($i=1; $i <= $valorFinal; $i++)
								{
									$campo = $_POST[$contagem_campo];
									if ($campo != null)
									{
										$campo_recebido = $campo;
										acao($acao_fazer,$campo_recebido,$coneccao_entrega);
										$qtdReg ++;
									}

									$contagem_campo++;
								}
								echo "<span class='info'>$qtdReg Entregue com sucesso <br>Atualizando...</span>";
								echo "<meta http-equiv='REFRESH' content='1'>";
							}
							else if (isset($_POST['editar_enviar']))
							{
								$acao_fazer = "ed";
								$contagem_campo = 1;
								$qtdReg = 0;
								$valorFinal = $_POST['valor_final'];
								for ($i=1; $i <= $valorFinal; $i++)
								{
									$campo = $_POST[$contagem_campo];
									if ($campo != null)
									{
										$campo_recebido = $campo;
										acao($acao_fazer,$campo_recebido,$coneccao_entrega);
										$qtdReg ++;
									}

									$contagem_campo++;
								}
								
								// echo "<meta http-equiv='REFRESH' content='1'>";
							}
							
						?>
					</fieldset>
					</form>
				</fieldset>

				<div class="icones_info_mae">
					<div class="icones_info">
						<div class='status status_azul'></div><br>
						Falta mais de uma semana
					</div>
					<div class="icones_info">
						<div class='status status_verde'></div><br>
						Falta uma semana
					</div>
					<div class="icones_info">
						<div class='status status_roxo'></div><br>
						Hoje é o ultimo dia para entregar o livro
					</div>
					<div class="icones_info">
						<div class='status status_vermelho'></div><br>
						Esta atrasado
					</div>
					<div class="icones_info">
						<div class='status status_preto'></div><br>
						Entregue
					</div>
				</div>
			</div>
		</section>
		<?php
		mysqli_close($coneccao_entrega);
		include('rodape.php'); ?>
	</body>
</html>