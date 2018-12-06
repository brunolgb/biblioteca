<html>
	<head>
		<link rel="stylesheet" href="arquivos/estilo.css">
		<link rel="shorcut icon" href="arquivos/icone.png">
		<title>Biblioteca Municipal de Comodoro</title>
	</head>
	<body style="overflow: hidden;">
		<section class="home">
		<?php include('nav.php');?>
		<div>
		<?php
			$query_empT = mysqli_query($con_bancobiblioteca,"SELECT data_entrega,ent FROM emprestado");
			if (!$query_empT)
			{
				echo "Algo deu errado";
			}
			$home = 1;
			while ($emp_l = mysqli_fetch_array($query_empT))
			{
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
				if($entrega == "n")
				{
					if (($finalDia < 0 and $finalMes == 0) or($finalDia < 0 and $finalMes < 0) or ($finalDia > 0 and $finalMes < 0) or ($finalDia < 0 and $finalMes > 0))
					{
						$status = $home;
						$home++;
					}
					
				}
			}
		?>
			Livros atrasados
			<strong><?php echo $status; ?> </strong>
		</div>
		</section>
	</body>
</html>