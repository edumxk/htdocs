<?php
	include 'Controle/vendasControl.php';
	require_once '../../controle/formatador.php';
	session_start();
	
	
    if(!isset($_SESSION)){

            header("location:index.php?msg=failed");
	}
	


	$arr;
	$data = date('d/m/Y', time());
	$dataBusca = date('Y-m-d', time());

  	if(isset($_GET['data'])){
		// echo json_encode($_GET);
		$d = explode('-',$_GET['data']);
		$data =  $d[2].'/'.$d[1].'/'.$d[0];
		$dataBusca = $_GET['data'];
		$arr = VendasControl::getCargasFat($data);
	}else{
		$arr = VendasControl::getCargasFat($data);
	}
	$cargas = $arr['CARGA'];
	$fat = $arr['FATURAMENTO'];
	



	$labels = array();
	$dataset = array();
	
	foreach ($arr['PESOMES'] as $l){
		//echo json_encode($l);
		array_push($labels, $l['DTFAT']);
		array_push($dataset, $l['PESO']);
	}

	$valordia = 0;
	$pesodia = 0;
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
	<link href="recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	



</head>


<body style="background-color: teal;">


	<div class="header">
		<div class="row">
			<div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
				<img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
			</div>
			<div class="col-md-2" style="top: 25px; font-weight: 700; color: white">
	
			</div>
		</div>
	</div>


	<div class="container" style="background-color: white; border-style: solid; border-width: 1px; width: 90%">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Carregamentos</li>
			</ol>
		</nav>
		<div class="row">


			<div class="col-md-8">
				<!-- ss-->
				<h1 style="padding-bottom: 20px; padding-top: 5px">Painel de Vendas
					<small> - Carregamentos</small>
				</h1>
			</div>
			<div class="col-md-4">
				<form action="#" method="GET">
					<input type="date" name="data" value="<?php echo $dataBusca?>">
					<input type="submit" name="busca" value="Buscar">
				</form>
			</div>

		</div>

		<div class="row">
			<div class="col-md-12" style="padding-bottom: 10px;">
				<div id="secao" style="width:100%;">Mensal</div>
			</div>
			<div class="col-md-12">
				<div class="col-md-12">
					<canvas id="mybar1" height="200"></canvas>
				</div>

			</div>



			<!-- Seção do dia -->

			<div class="col-md-12" style="padding-bottom: 10px;">
				<div id="secao">
					<div ><?php echo $data?></div>
				</div>

				<div id="dia">
					<!-- Tabela Cliente Retira -->
					<div class="col-md-12">
						<h3 style="font-weight: 700; text-align: center">Carregamento Avulso</h3>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<h5>Motorista:
										<small> Cliente Retira </small>
									</h5>
								</div>
								<div class="col-md-12">
									<table id="table_id" class="display compact" style="width:100%">
										<thead>
											<tr>
												<th style="text-align: center;">RCA</th>
												<th style="text-align: center;">NF</th>
												<th style="text-align: center;">COD</th>
												<th style="text-align: center;">Cliente</th>
												<th style="text-align: center;">Valor</th>
												<th style="text-align: center;">Peso</th>
												<th style="text-align: center;">Cidade</th>
												<th style="text-align: center;">UF</th>
											</tr>
										</thead>
										<tbody>
										<?php 
											$vltotal = 0; 
											$pesototal = 0;
											for($i=0; $i<count($fat); $i++):
										?>
											<?php if($fat[$i]['NUMCAR'] == 0):?>
											<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$i]['NOME']?></td>
												<td style="text-align: center"><?php echo $fat[$i]['NUMNOTA']?></td>
												<td style="text-align: center"><?php echo $fat[$i]['CODCLI']?></td>
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$i]['CLIENTE']?></td>
												<td style="text-align: right; padding-right:10px"><?php echo number_format($fat[$i]['VLTOTAL'],2,'.',',')?></td>
												<td style="text-align: right; padding-right:10px"><?php echo number_format($fat[$i]['TOTPESO'],2,'.',',')?></td>
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$i]['NOMECIDADE']?></td>
												<td style="text-align: center"><?php echo $fat[$i]['UF']?></td>
											</tr>
											<?php 
												$vltotal += $fat[$i]['VLTOTAL'];
												$valordia += $fat[$i]['VLTOTAL'];
												$pesototal += $fat[$i]['TOTPESO'];
												$pesodia += $fat[$i]['TOTPESO'];
											 endif ?>
										<?php endfor?>
										</tbody>
									</table>
									<div class="row" style="padding-top: 10px; padding-bottom: 10px">
										<div class="col-md-4"></div>
										<div class="col-md-2">
											<b>Valor Total:</b> R$ <?php echo number_format($vltotal,2,",",".");?></div>
										<div class="col-md-2">
											<b>Peso Total:</b> <?php echo number_format($pesototal,2,",",".");?> Kg</div>
									</div>
								</div>
							</div>

						</div>
					</div>



					<?php for($i=0; $i<count($cargas); $i++):?>
						<?php if($cargas[$i]['NUMCAR'] <> 0):?>



					<!-- Tabelas de Carregamentos -->
					<div id="cargas">
						<div class="col-md-12">
							<h3 style="text-align: center; background-color: lightgray;">Carregamento N <?php echo $cargas[$i]['NUMCAR']?></h3>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<h5>Motorista:
											<small> <?php echo $cargas[$i]['NOME']?></small>
										</h5>
									</div>
									<div class="col-md-12">
										<table class="tabela_cargas" style="width:100%">
											<thead>
												<tr>
													<th style="text-align: center;">RCA</th>
													<th style="text-align: center;">NF</th>
													<th style="text-align: center;">COD</th>
													<th style="text-align: center;">Cliente</th>
													<th style="text-align: center;">Valor</th>
													<th style="text-align: center;">Peso</th>
													<th style="text-align: center;">Cidade</th>
													<th style="text-align: center;">UF</th>
												</tr>
											</thead>
											<tbody>
													<?php 
													$vltotal = 0; 
													$pesototal = 0;
													for($c=0; $c<count($fat); $c++):
												?>
												<?php if($fat[$c]['NUMCAR'] == $cargas[$i]['NUMCAR']):?>

												<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$c]['NOME']?></td>
												<td style="text-align: center"><?php echo $fat[$c]['NUMNOTA']?></td>
												<td style="text-align: center"><?php echo $fat[$c]['CODCLI']?></td>
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$c]['CLIENTE']?></td>
												<td style="text-align: right; padding-right:10px"><?php echo number_format($fat[$c]['VLTOTAL'],2,'.',',')?></td>
												<td style="text-align: right; padding-right:10px"><?php echo number_format($fat[$c]['TOTPESO'],2,'.',',')?></td>
												<td style="text-align: left; padding-left:10px"><?php echo $fat[$c]['NOMECIDADE']?></td>
												<td style="text-align: center"><?php echo $fat[$c]['UF']?></td>

												</tr>
												<?php 
												$vltotal += $fat[$c]['VLTOTAL'];
												$valordia += $fat[$c]['VLTOTAL'];
												$pesototal += $fat[$c]['TOTPESO'];
												$pesodia += $fat[$c]['TOTPESO'];
											 endif ?>
										<?php endfor?>

											</tbody>
										</table>
										<div class="row" style="padding-top: 10px; padding-bottom: 10px">
										<div class="col-md-4"></div>
										<div class="col-md-2">
											<b>Valor Total:</b> R$ <?php echo number_format($vltotal,2,",",".");?></div>
										<div class="col-md-2">
											<b>Peso Total:</b> <?php echo number_format($pesototal,2,",",".");?> Kg</div>
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>

						<?php endif ?>
					<?php endfor ?>	
					<!-- Resumo Final -->
					<div class="col-md-12">
						<div class="row" style="padding-top: 20px; padding-bottom: 10px">
							<div class="col-md-2"></div>
							<h5 class="col-md-4">
								<b>Valor Total:</b> R$ <?php echo number_format($valordia,2,",",".");?></h5>
							<h5 class="col-md-4">
								<b>Peso Total:</b> <?php echo number_format($pesodia,2,",",".");?> Kg</h5>
						</div>
					</div>
				</div>

			</div>









			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row" style="padding-bottom: 10px;">

					</div>

				</div>
			</div>


		</div>
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
	var ctx = document.getElementById("mybar1");

	var labels =  <?php echo JSON_ENCODE($labels)?>;
	var dataset = <?php echo JSON_ENCODE($dataset)?>;
	
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels,
			datasets: [{
				label: 'Peso Faturado',
				data: dataset,
				backgroundColor: 'rgba(54, 162, 235, 0.8)',
				borderColor:'rgba(54, 162, 235, 0.8)',
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});

	var ctx = document.getElementById("mybar1");
	ctx.onclick = function(evt){
    var activePoints = myChart.getElementsAtEvent(evt);
    // => activePoints is an array of points on the canvas that are at the same position as the click event.
	var pos = activePoints[0]['_index']
	console.log(labels[pos]);
};
</script>

<script>
	n = new Date();
	y = n.getFullYear();
	m = ("0" + (n.getMonth() + 1)).slice(-2)
	d = ("0" + n.getDate()).slice(-2)
	document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
</script>

<script>
	function listaHoverIn(elm){
		$(elm).css('background-color', 'gold')
	}
	function listaHoverOut(elm){
		$(elm).css('background-color', 'transparent')
	}


</script>


</html>