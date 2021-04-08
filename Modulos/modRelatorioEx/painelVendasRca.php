<?php
	require_once ('model/cliente.php');
	$rca = 15;


	$clientes = Cliente::getClientes($rca);
	$pedidos = Cliente::getPedidos($rca);
	// echo json_encode($clientes);





?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Pedidos de Vendas</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	
</head>


<body style="background-color: teal;">

	<div class="container" style="background-color: white; border-style: solid; border-width: 1px; width: 100%">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Vendas</li>
			</ol>
		</nav>


		<div class="row">
			<div class="col-md-12" style="text-align: center">
				<h1 style="padding-bottom: 5px; padding-top: 5px">Painel de Vendas</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12" style="padding-bottom: 10px">
				<div id="secao" style="width:100%;">Pedidos Pendentes</div>
			</div>
		</div>

		<div class="row" style="padding-bottom: 10px; border-bottom: solid darkgray 2px">
			<div class="col-md-12" style="text-align: center">
				<h3 style="padding-bottom: 5px; padding-top: 5px">DOMINGOS FREITAS</h3>
			</div>
		</div>
		<!-- <div class="row" style="padding-bottom: 10px; border-bottom: solid darkgray 2px">
			<div class="col" style="text-align: center">
				<form action="">
					<button class="btn btn-primary" style="width:100%">CLIENTE</button>
				</form>
			</div>
			<div class="col" style="text-align: center">
				<form action="">
					<button class="btn btn-primary" style="width:100%">DATA</button>
				</form>
			</div>
			<div class="col" style="text-align: center">
				<form action="">
					<button class="btn btn-primary" style="width:100%">CIDADE</button>
				</form>
			</div>
		</div> -->

		<style>
		
			table{
				width: 100%;
				font-size: 11px;
			}
			th, td{
				text-align: center;
			}
			thead th{
				border: solid darkgray 2px;
			}
			tbody td{
				border: solid darkgray 1px
			}
			tbody{
				border: solid darkgray 2px
			}

		</style>

		<?php $valorTotal = 0; $pesoTotal = 0?>
		<?php
			foreach($clientes as $c):
		?>
		<div class="cliente" style="padding-bottom: 10px">
			<div class="row" style="font-weight: 600">
				<div class="col-12">
					<?php echo $c->cod.' - '.$c->cliente?>	
				</div>
				<div class="col-12">
					<?php echo $c->cidade.' - '.$c->uf?>	
				</div>
			</div>

			<div class="pedidos">
				<table>
					<thead>
						<tr>
							<th>Data</th>
							<th>Pedido</th>
							<th>Pos.</th>
							<th>Valor</th>
							<th>Peso</th>
							<th>Obs.</th>
						</tr>
					</thead>
					<tbody>
						<?php $valor = 0; $peso = 0?>
						<?php foreach($pedidos as $p):?>
							<?php if($p->cod == $c->cod):?>
								<tr>
									<td><?php echo $p->data?></td>
									<td><?php echo $p->numped?></td>
									<td><?php echo $p->pos?></td>
									<td><?php echo number_format($p->valor,2,',','.')?></td>
									<td><?php echo number_format($p->peso,2,',','.')?></td>
									<td><?php echo $p->mov?></td>
								</tr>
								<?php $valor += $p->valor; $peso += $p->peso?>
								<?php $valorTotal += $p->valor; $pesoTotal += $p->peso?>
							<?php endif?>
						<?php endforeach?>

					</tbody>
					<tfoot>
						<tr>
							<th colspan=3></th>
							<th style="border: solid darkgray 2px"><?php echo number_format($valor,2,',','.')?></th>
							<th style="border: solid darkgray 2px"><?php echo number_format($peso,2,',','.')?></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<?php endforeach?>

		<div style="display: inline-flex">
			<div style="padding-right:10px">
				<h4>TOTAL: </h4>
			</div>
			<div>
				<h4> R$ <?php echo number_format($valorTotal,2,',','.')?></h4>
			</div>
		</div>

		<div style="display: inline-flex">
			<div style="padding-right:10px">
				<h4>PESO: </h4>
			</div>
			<div>
				<h4> <?php echo number_format($pesoTotal,2,',','.')?> KG</h4>
			</div>
		</div>






	</div>



	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>




</html>