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