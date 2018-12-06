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
			include('nav.php');

			//sql e query dos livros
			$sql_livros = "SELECT id_livro,nome_livro,disponivel FROM livro";
			$query_livro = mysqli_query($con_bancobiblioteca,$sql_livros);

			//sql e query dos alunos e array para armazenar os resultados
			$sql_aluno = "select id_aluno,nome_aluno,ano_aluno,turma from aluno";
			$query_aluno = mysqli_query($con_bancobiblioteca,$sql_aluno);
			$gAluno = array();
			//Barar de navegação
			

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
			function emprestados($consulta_emp,$filtro,$limites,$bancobiblioteca)
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
				$id_user = $_SESSION['id_user'];
				$data = date('Y-m-d');

				//Consultar tabela livros
				$consulta_livro = "select id_livro,nome_livro,qtd from livro";
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
					$qtd_tbl_livro = $emp_livro['qtd'];
					$livro_aluno[8][$id_tbl_livro] = $qtd_tbl_livro;
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
				

				if($consulta_emp == 't' or empty($consulta_emp))
				{
					$coluna_ent = "ent ";
				}
				else
				{
					$coluna_ent = " ent='".$consulta_emp."'";
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

				// verificando o filtro e concatenando
				$consulta_emp_com = "select * from emprestado"; //Concatenar caso tiver valor no filtro
				$funcao = "select COUNT(id_emprestado) from emprestado";
				if (!empty($filtro) or !empty($coluna_ent))
				{
					$consulta_emp_com .= " WHERE $coluna_ent $res_filtro";
					$funcao .= " WHERE $coluna_ent $res_filtro";
				}
				$consulta_emp_com .= $limit;

				// paginação
				$paginando = mysqli_query($bancobiblioteca,$funcao);
				while ($p = mysqli_fetch_array($paginando)){$resulPaginacao = $p['COUNT(id_emprestado)'];}
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
				echo "<table>
						<tr class='mostrar-livros-titulo'>
							<td class='largP'>Id</td>
							<td class='largG'>Nome do Livro</td>
							<td class='largG'>Nome do Aluno</td>
							<td class='largP'>Ano</td>
							<td class='largPM'>Data pegou</td>
							<td class='largPM'>Data entregar</td>
							<td>Status</td>
							<td class='largP'>Usuário</td>
							<td class='largPM'>Data Cadastro</td>
							<td class='largP'>Ação</td>";

				//consultando os emprestados
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

					// alterando data atual para consultar
					$diaAtual = substr($data, 8,2);
					$mesAtual = substr($data, 5,2);
					$anoAtual = substr($data, 0,4);

					// alterando data da entrega para consultar
					$diaEntrega = substr($data_entrega, 8,2);
					$mesEntrega = substr($data_entrega, 5,2);
					$anoEntrega = substr($data_entrega, 0,4);

					//fazendo as contas
					$finalDia = $diaAtual - $diaEntrega;
					$finalMes = $mesAtual - $mesEntrega;
					$finalAno = $anoAtual - $anoEntrega;
					// echo "$finalDia | $finalMes | $finalAno <br>";

					$entrega = $emp_l['ent'];
					$user_cadastrou_emp = $emp_l['user_cadastrou'];
					$id_do_user = $livro_aluno[3][$user_cadastrou_emp];
					$data_cadastrou_emp = $emp_l['data_cadastrou'];

					$input = "<input type='checkbox' name='".$cont_nome."' value='".$id_emprestado."' class='cadastrar'>";
					echo "<script>muda('mudaValor','".$cont_nome."');</script>";
					$cont_nome ++;
					$para_mudar_cont ++;
						
					if($entrega == "n")
					{
						if (($finalDia < -7 and $finalMes == 0) or ($finalDia < -7 and $finalMes < 0))
						{
							$status = "<div class='status status_azul' title='Falta mais de uma semana para entregar'></div>";
						}
						else if (($finalDia < 0 and $finalMes == 0) or ($finalDia < 0 and $finalMes < 0))
						{
							$status = "<div class='status status_verde' title='Falta uma semana para entregar'></div>";
						}
						else if ($finalDia == 0 and $finalMes == 0)
						{
							$status = "<div class='status status_roxo' title='Hoje é o ultimo dia para entregar o livro'></div>";
						}
						else if (($finalDia > 0 and $finalMes == 0) or ($finalDia < 0 and $finalMes > 0))
						{
							$status = "<div class='status status_vermelho' title='A entrega desse livro esta atrasado |  $finalDia'></div>";
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
				echo "</table>";
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
										if ($disponivel == "sim")
										{
											echo "<option value='".$idLivro." | ".$nomeLivro."'></option>";
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
										echo "<option value='".$idaluno." | ".$nomealuno."'>".$anoaluno."". $turma."</option>";
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
						$guardar = array();
						if (isset($_POST['cadastrar']))
							{
								$nome_livro = $_POST['nome_livro'];
								$guardarIdL = strstr($nome_livro," ",true);
								$nome_aluno = $_POST['nome_aluno'];
								$guardarIdA = strstr($nome_aluno," ",true);
								$data_pegou = $_POST['data_pegou'];
								$data_entrega = $_POST['data_entrega'];
								$entInput = $_POST['ent'];
								$alteracao = $_POST['alteracao'];
								if ($alteracao == null)
								{
									$verificarEmprestimo = "SELECT id_livro,ent FROM emprestado WHERE id_livro='".$guardarIdL."'";
									$verificandoEmprestimo = mysqli_query($con_bancobiblioteca,$verificarEmprestimo);

									$verificarLivro = "SELECT id_livro,qtd FROM livro WHERE id_livro='".$guardarIdL."'";
									$verificandoLivro = mysqli_query($con_bancobiblioteca,$verificarLivro);

									// executando a livro
									while ($l = mysqli_fetch_array($verificandoLivro))
									{
										$verifyL = $l['id_livro'];
										$qtdL = $l['qtd'];
									}

									while ($v = mysqli_fetch_array($verificandoEmprestimo))
									{
										$verify = $v['id_livro'];
										$entregando = $v['ent'];
									}

									// teste para ver se possui no banco
									if (empty($verify))
									{
										$informacao = true;
									}
									if (!empty($verify) and $entregando=='s')
									{
										$informacao = true;
									}
									else if (!empty($verify) and $entregando=='n')
									{
										if ($qtdL != 1)
										{
											$informacao = true;
										}
										else
										{
											$informacao = false;
										}
									}
									if ($informacao == true)
									{
										$comandolivro = "INSERT INTO emprestado (id_livro, id_aluno, data_pegou, data_entrega,ent, user_cadastrou, data_cadastrou) VALUES ('$guardarIdL', '$guardarIdA', '$data_pegou', '$data_entrega','n', '$id_user', '$data')";
										$mensagem =  "<span class='info'>Emprestimo realizado com sucesso</span>";
									}
									else
									{
										$mensagem =  "<span class='nsucesso'>Livro já esta emprestado</span>";
									}
								}
								else
								{
									$comandolivro = " UPDATE emprestado SET id_livro = '$guardarIdL', id_aluno ='$guardarIdA', data_pegou='$data_pegou', data_entrega='$data_entrega',ent='$entInput', user_cadastrou= '$id_user', data_cadastrou='$data' WHERE id_emprestado='".$alteracao."'";
									$mensagem = "<span class='info'>Alteracação feita com sucesso</span>";
								}

								if (!empty($comandolivro))
								{
									// echo $comandolivro;
									mysqli_query($con_bancobiblioteca,$comandolivro);
								}
								echo $mensagem;
							}
						?>
					</div>
				</div>
		</section>
		<section class="corpo">
			<div class="todosLivros">
				<fieldset class="mostrar-livros">
					<legend>
						Lista dos Emprestados <strong id="mudaValor">----</strong> <span id="totMudaValor">----</span>
					</legend>
					<form method="post" id='filtros'>
					<section class="pesquisa">
						<?php
							$pesquisar_pronto_a = mysqli_query($con_bancobiblioteca,"SELECT id_aluno,nome_aluno,ano_aluno,turma from aluno");
							$pesquisar_pronto_l = mysqli_query($con_bancobiblioteca,"SELECT id_livro,nome_livro from livro");
							$pesquisar_pronto_e = mysqli_query($con_bancobiblioteca,"SELECT distinct id_livro from emprestado");
							$pesquisar_pronto_ee = mysqli_query($con_bancobiblioteca,"SELECT distinct id_aluno from emprestado");
							$pesquisar_pronto_d = mysqli_query($con_bancobiblioteca,"SELECT distinct data_pegou,data_entrega from emprestado");
							
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
							<option disabled="" selected="">Ver Listas</option>
							<option value="t">Todas</option>
							<option value="n">Não entregues</option>
							<option value="s">Entregues</option>
						</select>
						<input type="submit" name="pesq-enviar" value="Buscar" class="btn_verde">

					</section>
					</form>
					<form method="post">
					<?php
					$limitesEnvio = $_POST['paginas']; // limiter para as paginas
					if (isset($_POST['pesq-enviar']) or !empty($_POST['pesquisa_lial']) or !empty($_POST['listas']))
					{
						$pesquisaValor = $_POST['pesquisa_lial'];
						$listas = $_POST['listas'];

						// removendo as antigas sessões
						unset($_SESSION['pesquisaValor']);
						unset($_SESSION['listas']);

						// guardando
						$_SESSION['pesquisaValor'] = $pesquisaValor;
						$_SESSION['listas'] = $listas;

					}
					else
					{
						// guardando novamente
						$pesquisaValor = $_SESSION['pesquisaValor'];
						$listas = $_SESSION['listas'];
					}
			
					emprestados($listas,$pesquisaValor,$limitesEnvio,$con_bancobiblioteca);
					?>
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
										acao($acao_fazer,$campo_recebido,$con_bancobiblioteca);
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
										acao($acao_fazer,$campo_recebido,$con_bancobiblioteca);
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
			</div>
		</section>
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
		<?php
		mysqli_close($con_bancobiblioteca);
		include('rodape.php'); ?>
	</body>
</html>