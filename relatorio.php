<?php session_start(); ?>
<html>
	<head>
		<link rel="shortcut icon" href="arquivos/icone.png">
		<title>Biblioteca Municipal de Comodoro</title>
		<script src="arquivos/estilo.js"></script>
		<link rel="stylesheet" href="arquivos/estilo.css">
	</head>
	<body onload="relatorio()">

		<?php
			include('nav.php');
			$id_user = $_SESSION['id_user'];
			$data = date('Y/m/d');

			$g = array(); // guardando tudo nesse array

			$opcaoBusca = "SELECT distinct(ano_aluno),turma,turno from aluno order by ano_aluno,turno";
			$opcaoExecuta = mysqli_query($con_bancobiblioteca,$opcaoBusca);
			if (!$opcaoExecuta)
			{
				echo "erro";
			}
		?>
		<section class="corpo">
			<h1>Você esta em Relatórios</h1>
			<!-- <div class="controleBotoes">
				<button id="livroLido">Livros Lidos</button>
				<button id="topLivro">Top Livros</button>
			</div> -->
			<div class="relatorio relatorioLivroLido">
				<section class="filtros">
					<h1>Total de Livros lidos por alunos</h1>
					<form method="post">
						<select name="turmas" id="turmas">
							<option disabled="" selected="">-- Escolha a turma--</option>
							<?php
							while ($op = mysqli_fetch_array($opcaoExecuta))
							{
								$anoCompleto = $op['ano_aluno'].$op['turma'];
								switch ($op['turno'])
								{
									case 'v': $anoCompleto .= " - Vespertino"; break;
									case 'm': $anoCompleto .= " - Matutino"; break;
									case 'n': $anoCompleto .= " - Noturno"; break;
								}
								echo "<option>$anoCompleto</option>";
							}
							?>
						</select>
						<input type="submit" name="enviar" value="Pesquisar">
					</form>
					<?php
						if (empty($_POST['turmas']))
						{
							echo "<h3>Selecione uma turma para ver os resultados</h3>";
						}
						else
						{
							echo "<h3>Você esta vendo a turma ".$_POST['turmas']."</h3>";
						}
					?>
				</section>
				
				<?php
				if (isset($_POST['enviar']) and !empty($_POST['turmas']))
				{
					// tabela
					echo "<table class='dbLivro larg50'>";
					echo "<thead>";
					echo "<tr>";
					echo "<td class='larg70 destaque'>Nome do Aluno</td>";
					echo "<td class='destaque'>Turma</td>";
					echo "<td class='destaque'>Total de Livros Lidos</td>";
					echo "</tr>";
					echo "</thead>";
					echo "<tbody>";

					$anoPesquisa = substr($_POST['turmas'], 0,1);
					$turmaPesquisa = substr($_POST['turmas'], 1,1);
					$turnoPesquisa = substr($_POST['turmas'], 5);
					switch ($turnoPesquisa)
					{
						case 'Vespertino': $turnoPesquisa = 'v';break;
						case 'Matutino': $turnoPesquisa = 'm';break;
						case 'Noturno': $turnoPesquisa = 'n';break;
					}

					// comando
					$relatorioAluno = "SELECT * from aluno where ano_aluno='".$anoPesquisa."' and turma='".$turmaPesquisa."' and turno='".$turnoPesquisa."' ORDER BY nome_aluno";
					$buscandoAluno = mysqli_query($con_bancobiblioteca,$relatorioAluno);

					// iniciando a consulta
					while ($ra = mysqli_fetch_array($buscandoAluno))
					{
						$id_aluno = $ra['id_aluno'];
						$nome_aluno = $ra['nome_aluno'];
						$turno = $ra['turno'];
						$turma = $ra['ano_aluno'].$ra['turma'];
						$prof = $ra['prof'];

						// verificando quantos livros este aluno leu
						$relatorioEmprestismo = "SELECT COUNT(*) as totEmprestimoAluno from emprestado where id_aluno=".$id_aluno;
						$buscandoEmprestismo = mysqli_query($con_bancobiblioteca,$relatorioEmprestismo);
						while ($re = mysqli_fetch_array($buscandoEmprestismo))
						{
							$totEmprestimoAluno = $re['totEmprestimoAluno'];
							if ($totEmprestimoAluno != 0)
							{
								$destaque = "class='destaque azul'";
							}
							if ($totEmprestimoAluno == 0)
							{
								$destaque = null;
							}
						}
						echo "<tr>";
						echo "<td ".$destaque.">".$nome_aluno."</td>";
						echo "<td ".$destaque.">".$turma."</td>";
						echo "<td ".$destaque.">".$totEmprestimoAluno."</td>";
						echo "</tr>";
					}
				}
				?>
				</tbody>
				</table>
			</div>
		</section>
	<?php include('rodape.php'); ?>
	</body>
</html>