<?php
	require_once 'controle/listaRatControle.php';
	session_start();
	//echo json_encode($_SESSION);
	//echo $_SESSION['codsetor'];

	if(/*$_SESSION['codsetor'] == 0 ||*/$_SESSION['codsetor'] == 1){
		header("location:listaRatDiretoria.php");
	}
	$rat = ListaRatControl::getListaRatGeral();

	//echo json_encode($rat);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Lista de RAT's</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


</head>


<body style="background-color: teal;">


	<div class="header">
		<div class="row">
			<div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
				<img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
			</div>
            <div class="float-md-right">
                <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                    Usuário: <?php echo $_SESSION['nome'] ?>
                </div>
                <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                    Setor: <?php echo $_SESSION['setor']?>
                </div>
            </div>
		</div>
	</div>


	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Lista de RAT's</li>
			</ol>
		</nav>
		<div class="row">


			<div class="col-md-8"><!-- ss-->
				<h1 style="padding-bottom: 20px; padding-top: 20px">Gerência de Chamados
					<small> - Lista de Todas as RAT's</small>
				</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12" id="secao">
					Chamados
				</div>
			</div>


			<div class="col-md-12" style="padding-top: 20px">
				<div class="row">
				<div class="col-md-6" style="padding-bottom: 20px">
						<a class="btn btn-danger" href="listaCliente.php" role="button">Nova RAT</a>
					</div>
					<div class="col-md-3" style="padding-bottom: 20px">
						<a class="btn btn-warning" href="listaRat.php" role="button">RAT's Pendentes</a>
						<a class="btn btn-success" href="listaRatCTD.php" role="button">RAT's Finalizadas</a>
					</div>
					<div class="col-md-3" style="padding-bottom: 20px; allign: right">
						<a class="btn btn-primary float-right" href="relatorio.php" role="button">Resultados</a>
					</div>
				</div>
				
				<div class="col-md-12" style="padding-bottom: 20px">
					<table id="table_id" class="display compact" style="width:100%">
						<thead>
							<tr>
								<th style="text-align: center; width: 20px">RAT</th>
								<th style="text-align: center; width: 60px">Abertura</th>
								<th style="text-align: center; width: 20px">COD</th>
								<th>Cliente</th>
								<th style="text-align: center">Pendencia</th>
								<th style="text-align: center">Status</th>
								<th style="text-align: center">Dias</th>
								<th style="text-align: center; width: 60px">Encerramento</th>
								<th style="text-align: center; width: 60px">Ação</th>
							</tr>
						</thead>
						<tbody>
							<?php
								//$rat=RatControl::getLista();

								if($rat!=null):
									foreach($rat as $r):
							?>
							<tr>
								<td style="text-align: center">
									<?php echo $r->numRat?>
								</td>
								<td style="text-align: center">
									<?php echo $r->dtAbertura?>
								</td>
								<td style="text-align: center">
									<?php echo $r->codCli?>
								</td>
								<td>
									<?php echo $r->cliente?>
								</td>
								<td style="text-align: center">
									<?php echo $r->pendencia?>
								</td>
								<td style="text-align: center">
									<?php echo $r->getStatus()?>
								</td>
								<td style="text-align: center">
									<?php echo ListaRatControl::getDuracao($r->dtAbertura, $r->dtEncerramento)?>
								</td>
								<td style="text-align: center">
									<?php echo $r->dtEncerramento?>
								</td>
								<td style="text-align: center" class="row">


									<!-- <form class="col-sm-1" action="#" method="post"> -->
									<div class="col-sm-1">

										<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $r->numRat?>)">
											<i class="far fa-eye"></i>
										</button>
									</div>
									<!-- </form> -->

								</td>
							</tr>

							<?php endforeach ?>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row" hidden>
				<div class="col-md-12" style="padding-bottom: 10px;"><!-- ss-->
					<div id="secao" class="btn btn-primary" data-toggle="collapse" href="#collapse2" role="button" aria-controls="collapse2"
					 style="width:100%; padding-bottom:30px">Graficos
	
					</div>
				</div>
	
	
				<div class="col-md-12">
					<div class="collapse" id="collapse2">
						<div class="row" style="padding-bottom: 10px; height:250px">
								<div class="col-md-4" >
										<canvas id="myPieChart" height="250"></canvas>
									</div>
									<div class="col-md-4" >
										<canvas id="myChart" height="250"></canvas>
									</div>
									<div class="col-md-4" >
										<canvas id="myLineChart" height="250"></canvas>
									</div>
						
								</div>
						</div>
					</div>
				</div><!-- ss-->
		</div>
	</div>
	<div class="header">

	</div>


	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>

<script>
	$(document).ready(function () {
		$('#table_id').DataTable({
			"lengthMenu": [[50, 100, -1], [50, 100,"Todos"]],
			"order": [[ 0, "asc" ]],
			"bInfo": false,

			"language": {
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoPostFix": "",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
			}
		});
	});
</script>

<script>
	function openpdf(elm){
		window.open("recursos/visualizarRat.php?numrat="+elm);
	}
</script> 


</html>