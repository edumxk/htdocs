<?php
	
	
	header('Location: /cargasControl.php');

	require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Controle/cargasControle.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	//redirecionar para cargasControl.php

	if(isset($_GET['rca'])){
		$buscaRca = $_GET['rca'];
	}else{
		$buscaRca = '';
	}

	$ret = CargasControle::getPedidosView($buscaRca);
	$faltas = CargasControle::getFaltas();

	$pedidosSemCarga = $ret['pedidosSemCarga'];
	$rcaSemCarga = $ret['rca'];
	$pedidosComCarga = $ret['pedidosComCarga'];
	$cargas = $ret['cargas'];

	// echo json_encode($cargas);
	$totPesoL = 0; //contador roda pé
	$totPesoM = 0;
	$totPesoP = 0;
	$qtPedCarg = 0;
	$faux = 0;
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Recarrega a cada 5 min. -->
	<meta http-equiv="refresh" content="300"> 

	<title>Lista de RAT's</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


</head>


<body style="background-color: teal;width:100%">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">
				<a href="../../home.php">Home</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Cargas</li>
		</ol>
	</nav>
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-1">
			<div  style="padding-bottom: 20px">
				<button style="width:100px" type="submit" class="btn btn-sm btn-warning" onclick="openNovaCarga()">
					Nova Carga
					<i style="padding-left:10px"class="fas fa-truck"></i>
				</button>
			</div>
		</div>
		<div class="col-md-2">
		</div>
		
		<div class="col-md-1">
			<button style="width:100%" type="submit" class="btn btn-sm btn-success" onclick="location.reload()">
				<i class="fas fa-sync fa-xs"></i>
			</button>
		</div>
		<div class="col-md-4">
		</div>
	</div>
	<div class="div" style="padding:20px">
		<div class="row">
			<div id="semCarga" class="col-md-12" style="padding-bottom: 30px; padding-top: 30px; background-color: white">
				<h4 style="text-align:center">Sem Carga</h4>
				<div class="row" style="padding-bottom:20px">
					<div class="col-md-12">
						<div class="row"style="padding-bottom:10px">
							<div class="col-md-1"><h5>RCA</h5></div>
							<div class="col-md-2">
								<select id="selectRCA" onchange="if (this.selectedIndex)  selectRca(this)" style="width: 100%">
									<option value="">TODOS</option>
									<?php foreach($rcaSemCarga as $r):
										if($buscaRca == $r['nome']):
										?>
											<option value="<?php echo $r['nome']?>" selected><?php echo $r['nome']?></option>
										<?php else:?>
											<option value="<?php echo $r['nome']?>"><?php echo $r['nome']?></option>
										<?php endif;
									endforeach ?>
									
								</select>
							</div>
							<div class="col-sm-1">
								<form action="cargasView.php" method="get">
									<button style="width: 100%" type="submit" name="rca"  class="btn btn-sm btn-success" id="valueRca" value="">BUSCAR</button>
								</form>
							</div>
							<div class="col-sm-5"><h5 class="float-right">Trocar:</h5></div>
							<div class="col-sm-2">
								<select id="selectCarga" class="select" style="width: 100%">
									<option value="" selected>ESCOLHER</option>
								<?php foreach($cargas as $c):?>
									<?php if($c->fechado == "0"):?>
										<option value="<?php echo $c->numcarga?>"><?php echo $c->nome?></option>
									<?php endif?>
								<?php endforeach?>
								</select>
							</div>
							<div class="col-sm-1">
								<button style="width:100%" type="submit" class="btn btn-sm btn-success" onclick="trocaCargas()">
								<i class="fas fa-check fa-xs"></i>
								</button>
							</div>
						</div>
					</div>

				 


					<div class="col-md-12">
						<table style="width: 100%" id="tableSemCarga" class="table_pedidos" >
							<thead style="border: 2px solid darkslategray">
								<tr style="background-color: lightgray">
									<th style="text-align: center; font-size:10px; ">DATA</th>
									<th style="text-align: center; font-size:10px; ">RCA</th>
									<th style="text-align: center; font-size:10px; ">NUMPED</th>
									<th style="text-align: center; font-size:10px; ">#</th>
									<th style="text-align: center; font-size:10px; ">COD</th>
									<th style="text-align: center; font-size:10px">CLIENTE</th>
									<th style="text-align: center; font-size:10px; ">CARGA</th>
									<th style="text-align: center; font-size:10px; ">POS</th>
									<th style="text-align: center; font-size:10px; ">PESO</th>
									<th style="text-align: center; font-size:10px; ">VALOR</th>
									<th style="text-align: center; font-size:10px; ">PRACA</th>
									<th style="text-align: center; font-size:10px; ">CIDADE</th>
									<th style="text-align: center; font-size:10px; ">UF</th>
									<th style="text-align: center; font-size:10px; ">OBS</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($pedidosSemCarga as $p):?>
								<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" >
									<td style="text-align: center"><?php echo $p->data ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->rca ?></td>
									<td style="text-align: right; padding-right: 10px"><?php echo $p->numped ?></td>
									<td style="text-align: center"><input type="checkbox" value="<?php echo $p->numped?>" class="ckbIncluir"></td>
									<td style="text-align: center"><?php echo $p->cod ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->cliente ?></td>
									<td style="text-align: center"><?php echo $p->numcar ?></td>
									<td style="text-align: center" class="pos"><?php echo $p->pos ?></td>
									<td style="text-align: right; padding-right: 10px"><?php echo number_format($p->peso,2,',','.') ?></td>
									<td style="text-align: right; padding-right: 10px"><?php echo $p->valor ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->praca ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->cidade ?></td>
									<td style="text-align: center"><?php echo $p->uf ?></td>
									<td style="text-align: center"><?php echo $p->obs ?></td>
								</tr>
							<?php endforeach?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-12" style="padding-bottom: 10px; background-color: white; width: 100%">
				<div class="row">
					<div class="col-sm-5">
					</div>
					<div class="col-sm-7">
						<h4>Cargas Montadas</h4>
					</div>
					<div class="col-sm-2">
					</div>
					<div class="col-sm-2">
						<h5>CARGA</h5>
					</div>
					<div class="col-sm-2" style="text-align:right">
						<h5>PREVISÃO</h5>
					</div>
					<div class="col-sm-2" style="text-align:right">
						<h5>PESO</h5>
					</div>
					<div class="col-sm-2" style="text-align:right">
						<h5>VALOR</h5>
					</div>
				</div>
				<?php 
					$totPeso = 0;
					$totValor = 0;
					foreach($cargas as $c):?>
				<div class="row" onmouseover="resumoHoverIn(this)" onmouseout="resumoHoverOut(this)">
					<div class="col-sm-2">
					</div>
					<div class="col-sm-2" style="border-bottom: 1px solid black" >
						<b><?php echo $c->nome ?></b>						
					</div>
					<div class="col-sm-2" style="text-align:right; border-bottom: 1px solid black">
						<?php echo $c->dtPrevisao ?>					
					</div>
					<div class="col-sm-2" style="text-align:right; border-bottom: 1px solid black">
						<?php echo number_format($c->peso,2,',','.'); $totPeso+=$c->peso ?> Kg						
					</div>
					<div class="col-sm-2"style="text-align:right; border-bottom: 1px solid black">
						R$ <?php echo number_format($c->valor,2,',','.'); $totValor+=$c->valor ?>						
					</div>
				</div>
				<?php endforeach?>
				<div class="row">

					<div class="col-sm-6">
					</div>
					<div class="col-sm-2" style="text-align:right">
						<b><?php echo number_format($totPeso,2,',','.')?> Kg</b>	
					</div>
					<div class="col-sm-2" style="text-align:right">
						<b>R$ <?php echo number_format($totValor,2,',','.')?></b>
					</div>
				</div>		
			</div>
			<?php foreach($cargas as $c):?>
			<div id="comCargas" class="col-md-12" style="padding-bottom: 10px; background-color: white; border:2px solid black">
				<div  class="cabecaTabela"style="background-color: lightblue">
					<div class="row">
						<?php if($c->fechado == 0):?>
							<div class="col-sm-1" style="text-align:center">
								<button style="width:50%" type="submit" class="btn btn-sm btn-danger"value="<?php echo $c->numcarga?>-<?php echo $c->nome?>" onclick="openDeleteCarga(this)">
									<i class="fas fa-trash"></i>
								</button>
							</div>
						<?php else:?>
							<div class="col-sm-1" style="text-align:center">

							</div>
						<?php endif?>
						<div class="col-md-3">
							<h4 style="text-align:center" class="previsao">Previsao: <?php echo $c->dtPrevisao ?></h4>
						</div>
						<div class="col-md-3">
							<h4 style="text-align:center"><?php echo $c->nome ?></h4>
						</div>
						<div class="col-md-3">
							<h4 style="text-align:center">Peso: <?php echo number_format($c->peso,'2',',','.') ?> kg</h4>
						</div>
						<?php if($c->fechado == 0):?>
							<div class="col-sm-1" style="text-align:center">
								<button style="width:50%" type="submit" class="btn btn-sm btn-warning"value="<?php echo $c->numcarga?>" onclick="openEditCarga(this)">
									<i class="fas fa-edit"></i>
								</button>
							</div>
						<?php else:?>
							<div class="col-sm-1" style="text-align:center">

							</div>
						<?php endif?>
						<div class="col-sm-1" style="text-align:center">
							<button style="width:50%" type="submit" class="btn btn-sm btn-success" onclick="getPendencias('<?php echo $c->numcarga ?>')">
								<i class="fas fa-eye"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="row" style="padding-bottom:20px" >
					<div class="col-md-12">
						<table style="width: 100%" class="table_pedidos" >
							<thead style="border: 2px solid darkslategray">
								<tr style="background-color: lightgray" >
									<th style="text-align: center; font-size:10px; width:6%">DATA</th>
									<th style="text-align: center; font-size:10px; width:8%">RCA</th>
									<th style="text-align: center; font-size:10px; width:6%">NUMPED</th>
									<th style="text-align: left; font-size:10px; width: 3%">COD</th>
									<th style="text-align: center; font-size:10px; width:30%">CLIENTE</th>
									<th style="text-align: center; font-size:10px; width:3%">CONF</th>
									<th style="text-align: center; font-size:10px; width:3%">CARGA</th>
									<th style="text-align: center; font-size:10px; width:2%">POS</th>
									<th style="text-align: center; font-size:10px; width:2%">VER</th>
									<th style="text-align: center; font-size:10px; width:6%">PESO</th>
									<th style="text-align: center; font-size:10px; width:6%">VALOR</th>
									<th style="text-align: center; font-size:10px; width: 12%">PRACA</th>
									<th style="text-align: center; font-size:10px; width: 12%">CIDADE</th>
									<th style="text-align: center; font-size:10px; width: 2%">UF</th>
									<th style="text-align: center; font-size:10px; width: 2%">OBS</th>
									<th style="text-align: center; font-size:10px">#</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($pedidosComCarga as $p):
								if($p->numcarga == $c->numcarga):?>
								<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" >
									<td style="text-align: center"><?php echo $p->data ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->rca ?></td>
									<td style="text-align: right; padding-right: 10px" id="montNumped"><?php echo $p->numped ?></td>
									<td style="text-align: center"><?php echo $p->cod ?></td>
									
									<!-- inicio de impressão de faltas e consumidor final -->
									<?php foreach ($faltas as $f) {
										if ($f->posicaof == 'P' && $f->clientef == $p->cod && $faux ==0){
											if ($p->consumidorfinal == 'S'){ ?>
												<td style="text-align: left; padding-left: 5px"> <button style="width:25px" type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-male fa-lg"></i> </button> <button style="width:35px" type="submit" class="btn btn-sm btn-success" onclick="getFaltasCli2('<?php echo $p->cod ?>')">
									<i class="fas fa-truck fa-lg"></i>
								</button> <?php echo $p->cliente ?> </td>
													<?php $faux = 1; }else{  ?>
														<td style="text-align: left; padding-left: 10px"> <button style="width:35px" type="submit" class="btn btn-sm btn-success" onclick="getFaltasCli2('<?php echo $p->cod ?>')">
									<i class="fas fa-truck fa-lg"></i>
								</button> <?php echo $p->cliente ?> </td>
													<?php $faux = 1; }
											}} if ($faux == 0) {
											if($p->consumidorfinal == 'S'){?>
											<td style="text-align: left; padding-left: 5px"> <button style="width:25px" type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-male fa-lg"></i> </button> <?php echo $p->cliente ?></td>
											<?php }else{ ?>
											<td style="text-align: left; padding-left: 10px"><?php echo $p->cliente ?> </td>
											<?php }}
											$faux = 0; ?>
									<!-- fim de impressão de faltas e consumidor final -->
									
									<td style="text-align: center"><?php echo $p->conferencia ?></td>
									<td style="text-align: center"><?php echo $p->numcar ?></td>
									<td style="text-align: center" class="pos" id="<?php echo $p->numped ?>"><?php echo $p->pos ?></td>
									<td style="text-align: center">
										<button type="submit" style="font-size:5px" class="btn-success btn-xs" onclick="getPendenciasPedidoEspecial('<?php echo $p->numped ?>')">
											<i class="fas fa-eye"></i>
										</button>
									</td>
									<td style="text-align: right; padding-right: 10px"><?php echo $p->peso; $qtPedCarg += 1 ?></td>
									<?php 
									if($p->pos=='B' || $p->pos=='P'){
										$totPesoP+=$p->peso;}
										else{ if($p->pos=='L'){
											$totPesoL+=$p->peso;
										}else{ if($p->pos=='M'){
											$totPesoM+=$p->peso;
										}}
										} ?>
									<td style="text-align: right; padding-right: 10px"><?php echo $p->valor ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->praca ?></td>
									<td style="text-align: left; padding-left: 10px"><?php echo $p->cidade ?></td>
									<td style="text-align: center"><?php echo $p->uf ?></td>
									<td style="text-align: center"><?php echo $p->obs ?></td>
									<td style="text-align: center">
										<?php if($c->fechado == 0):?>
											<button type="submit" style="font-size:5px" class="btn-danger btn-xs"  value="' + iprod['numped'] + '" onclick="setSemCarga(this)">
												<i class="fas fa-trash"></i>
											</button>
										<?php endif?>
									</td>
								</tr>
								<?php endif?>
							<?php endforeach?>
							</tbody>
						</table>
					<!-- Roda pé -->
						<div  class=""style="background-color: lightblue">
							<div class="row">
								<div class="col-md-3">
									<h4 style="text-align:center" class="">Peso Pendente: <?php echo number_format($totPesoP,'2',',','.') ?> kg</h4>
								</div>
								<div class="col-md-3">
									<h4 style="text-align:center" class="">Peso Liberado: <?php echo number_format($totPesoL,'2',',','.') ?> kg</h4>
								</div>
								<div class="col-md-3">
									<h4 style="text-align:center" class="">Peso Montado: <?php echo number_format($totPesoM,'2',',','.') ?> kg</h4>
								</div>
								<div class="col-md-3">
									<h4 style="text-align:right" class="">Pedidos: <?php echo $qtPedCarg; $qtPedCarg = 0;$totPesoL=0; $totPesoP = 0; $totPesoM = 0 ?></h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach ?>
		</div>

		<!-- INICIO MODAL DE INCLUSÃO DE CARGA -->
		<div class="modal" tabindex="-1" role="dialog" id="modalNovaCarga">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Cadastrar Nova Carga</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row d-flex justify-content-center">
							<div class="col-md-6">
								<div class="col-md-12">
									<label for="novoNome">Nome da Carga</label>
									<input id="novoNome" autocomplete="off">
								</div>
								<div class="col-md-12" style="padding-bottom:5px">
									<label for="novoVeiculo">Veiculo</label>
									<input id="novoVeiculo" autocomplete="off">
								</div>
								<div class="col-md-12">
									<div style="padding-bottom:5px">Previsão</div>
									<input style="width:90%" type="date" id="novoPrevisao">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal"
							onClick="setNovaCarga()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->
<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS FALTAS -->
<div class="modal" tabindex="-1" role="dialog" id="modalVerPendenciasfaltas">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Faltas do Cliente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12" style="overflow:auto; height: 400px">
								<table style="width:100%"> 
									<thead>
										<div>
											<h2>Kokar Indústria e Comércio de Tintas</h1>
										</div>
										<tr>
											<th style="width:35px; text-align:center">NFE</th>
											<th style="width:35px; text-align:center">COD</th>
											<th style="padding-left:10px">PRODUTO</th>
											<th style="width:25px; text-align:center">QT</th>
											<th style="width:25px; text-align:center">EST</th>
											<th style="width:55px; text-align:center">DATA</th>
											<th style="width:55px; text-align:center">MOTIVO</th>
										</tr>
									</thead>
									<tbody  id="pendenciasFaltas">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->

		<!-- INICIO MODAL PARA REMOVER CARGA -->
		<div class="modal" tabindex="-1" role="dialog" id="modalDeleteCarga">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Remover Carga</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div id="detele">
						</div>
						<div id="idDelete" hidden>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal"
							onClick="deleteCarga()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->
		<!-- INICIO MODAL PARA EDITAR CARGA -->
		<div class="modal" tabindex="-1" role="dialog" id="modalEditarCarga">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Editar Carga</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row d-flex justify-content-center">
							<div class="col-md-6">
								<div class="col-md-12">
									<a id="editNumcarga" hidden></a>
									<label for="editNome">Nome da Carga</label>
									<input id="editNome" autocomplete="off">
								</div>
								<div class="col-md-12" style="padding-bottom:5px">
									<label for="editVeiculo">Veiculo</label>
									<input id="editVeiculo" autocomplete="off">
								</div>
								<div class="col-md-12">
									<div style="padding-bottom:5px">Previsão</div>
									<input style="width:90%" type="date" id="editPrevisao">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal"
							onClick="editCarga()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->
		<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS -->
		<div class="modal" tabindex="-1" role="dialog" id="modalVerPendencias">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Pendencias da Carga</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12" style="overflow:auto; height: 450px">
								<table style="width:100%"> 
									<thead>
										<tr>
											<th style="width:40px; text-align:center">COD</th>
											<th style="padding-left:10px">PRODUTO</th>
											<th style="width:40px; text-align:center">PEDIDO</th>
											<th style="width:40px; text-align:center">DISP</th>
										</tr>
									</thead>
									<tbody  id="pendencias">

									</tbody>
								</table>
								<div id="pesoTotal" style="text-align:right">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->

		<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS PEDIDOS -->
		<div class="modal" tabindex="-1" role="dialog" id="modalVerPendenciasPedidos">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Pendencias do Pedido</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12" style="overflow:auto; height: 450px">
								<table style="width:100%"> 
									<thead>
										<tr>
											<th style="width:40px; text-align:center">COD</th>
											<th style="padding-left:10px">PRODUTO</th>
											<th style="width:40px; text-align:center">PEDIDO</th>
											<th style="width:40px; text-align:center">DISP</th>
										</tr>
									</thead>
									<tbody  id="pendenciasPedidos">

									</tbody>
								</table>
								<div id="pesoTotal9" style="text-align:right">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->


		<script src="../../recursos/js/jquery.min.js"></script>
		<script src="../../recursos/js/bootstrap.min.js"></script>
		<script src="../../recursos/js/scripts.js"></script>
		<script src="../../recursos/js/Chart.bundle.min.js"></script>
		<script src="../../recursos/js/jquery.tablesorter.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>

<script>
	function openpdf(elm) {
		window.open("recursos/visualizarRat.php?numrat=" + elm);
	}
</script>

<script>
	$(document).ready(function () {

		$.each($('.pos'), function (index, value) {
			value.addEventListener("click", console.log('ok'));
			//console.log($(value).prop("id"));
		});


		$( ".cabecaTabela" ).each(function() {	
			d = $(this).find('.previsao').text();
			s=d.split(" ");
			elm = $(this).find('.previsao');
			if(s[1] !=''){
				$(elm).parent().parent().css("background-color", "yellow")
			}
			prev = corPrevisao(d)
			if(prev <= 0){
				$(elm).parent().parent().css("background-color", "tomato")
			}
		});




		$( ".pos" ).each(function(  ) {
			pos = $(this).text()
			
			if (pos == "L") {
				$(this).css("background-color", "lightgreen");
			}else if(pos == "P"){
				$(this).css("background-color", "yellow");
			}else if(pos == "B"){
				$(this).css("background-color", "red");
			}else if(pos == "M"){
				$(this).css("background-color", "lightblue");
			}

		});
		$(".table_pedidos").tablesorter();


	});


	function selectRca(elm){
		a = $("option:selected", elm).text();
		$("#valueRca").val(a)
	}

	function getPendencias(elm){
		console.log(elm)
		nome = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'getPendencias', 'query': nome },
			success: function (response) {
				console.log(response);
				arr = JSON.parse(response);
				
				body = "";
				$('#pendencias').text("");
				peso = 0;
				arr.forEach(function(t){
					console.log(t)
					peso = peso + parseFloat(t['PESO']);
					body += '<tr>'
							+'<td style="width:40px; text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="padding-left:10px">'+t['DESCRICAO']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QT']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QTDISP']+'</td>'
						+'</tr>';
				})
				$('#pendencias').empty();
				$('#pendencias').append(body);
				$('#pesoTotal').text("Peso da pendencia: "+parseFloat(peso).toFixed(2)+" kg");
				$('#modalVerPendencias').modal('toggle');
			}
		});
	}

	function getPendenciasPedido(elm){
		console.log(elm)
		numped = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'getPendenciasPedido', 'query': numped },
			success: function (response) {
				console.log(response);
				arr = JSON.parse(response);
				
				body = "";
				$('#pendencias').text("");
				arr.forEach(function(t){
					console.log(t)
					body += '<tr>'
							+'<td style="width:40px; text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="padding-left:10px">'+t['DESCRICAO']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QT']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QTDISP']+'</td>'
						+'</tr>';

					
				})
				$('#pendenciasPedidos').empty();
				$('#pendenciasPedidos').append(body);
				$('#modalVerPendenciasPedidos').modal('toggle');
			}
		});
	}

	//MODAL DE PEDIDOS PENDENTES
	function getPendenciasPedidoEspecial(elm){
		console.log(elm)
		numped = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'getPendenciasPedidoEspecial', 'query': numped },
			success: function (response) {
				console.log(response);
				arr = JSON.parse(response);
				body = "";
				peso2 = 0;
				//$('#pendencias').text("");
				arr.forEach(function(t){
					console.log(t)
					peso2 = peso2 + parseFloat(t['PESOP']);
					if(t['DISP']=='S'){
					body += '<tr>'
							+'<td style="width:40px; text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="padding-left:10px;">'+t['DESCRICAO']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QT']+'</td>'
							+'<td style="width:40px; text-align:center">'+t['QTDISP']+'</td>'
						+'</tr>';
					}else{
						body += '<tr>'
							+'<td style="width:40px; color:red; text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="padding-left:10px; color:red">'+t['DESCRICAO']+'</td>'
							+'<td style="width:40px; color:red; text-align:center">'+t['QT']+'</td>'
							+'<td style="width:40px; color:red; text-align:center">'+t['QTDISP']+'</td>'
						+'</tr>';
					}				
				})
				$('#pendenciasPedidos').empty();
				$('#pendenciasPedidos').append(body);
				$('#pesoTotal9').text("Peso da pendencia: "+parseFloat(peso2).toFixed(2)+" kg");
				$('#modalVerPendenciasPedidos').modal('toggle');
			}
		});
	}	
	
</script>

<script>
	function corPrevisao(data){
		d = data.split("/")
		daux = d[0].split(" ")
		strData = d[2]+'/'+d[1]+'/'+daux[1]
		dataPedido = new Date(strData);


		dAtualAux = new Date()
		dAtual = dAtualAux.getFullYear()
		dAtual += "/"+(dAtualAux.getMonth()+1)
		dAtual += "/"+dAtualAux.getDate()
		dataAtual = new Date(dAtual);
		//console.log("atual: "+dAtualFinal)
		//console.log("pedido: "+data)
		return(dataPedido-dataAtual)
	}
</script>

<script>
	//SCRIPT DE AÇÃO COM O BANCO DE DADOS.

	function setSemCarga(elm){
		numped = $(elm).parent().parent().find('#montNumped').text()
		console.log(numped)

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'setSemCarga', 'query': numped },
			success: function (response) {
				//console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function selectRca(elm){
		a = $("option:selected", elm).text();
		$("#valueRca").val(a)
	}

	function trocaCargas(){
		trocaCarga = $('#selectCarga').val();
		arr = [];
		$('#tableSemCarga').find('input[type="checkbox"]:checked').each(function () {
			arr.push($(this).val())
    	});

		//console.log(trocaCarga)
		dataset = { "numcarga": trocaCarga, "pedidos":arr};
		console.log(dataset)

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'setGroupCarga', 'query': dataset },
			success: function (response) {
				//console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function openNovaCarga() {
		$('#modalNovaCarga').modal('toggle');
	}

	function setNovaCarga() {
		nome = $('#novoNome').val();
		veiculo = $('#novoVeiculo').val();
		data = $('#novoPrevisao').val();

		dataset = { "nome": nome, "veiculo": veiculo, "data": data };
		// console.log(dataset)
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'setNovaCarga', 'query': dataset },
			success: function (response) {
				console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function openEditCarga(elm) {
		//console.log($(elm).val())
		$('#modalEditarCarga').modal('toggle');

		numcargaEdit = $(elm).val()

		//console.log(numcargaEdit)

		numcargaEdita = '';
		nomeEdita = '';
		nomeVeiculo = '';
		previsaoEdita = '';
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'getDadoCarga', 'query': numcargaEdit },
			success: function (response) {
				//console.log(response);
				arr = JSON.parse(response);
				numcargaEdita = arr[0]['NUMCARGA']
				nomeEdita = arr[0]['NOME']
				nomeVeiculo = arr[0]['VEICULO']
				previsaoEdita = arr[0]['DTPREVISAO']

				$('#editNumcarga').val(numcargaEdita);
				$('#editNome').val(nomeEdita);
				$('#editVeiculo').val(nomeVeiculo);
				d = previsaoEdita.split("/", 3)
				$('#editPrevisao').val(d[2] + '-' + d[1] + '-' + d[0]);

			}
		});


	}

	function editCarga() {
		numcarga = $('#editNumcarga').val();
		nome = $('#editNome').val();
		veiculo = $('#editVeiculo').val();
		dt = $('#editPrevisao').val();
		dtaux = dt.split("-",3)
		if(dtaux[2] == null || dtaux[1] == null || dtaux[0] == null){
			data = null;
		}else{
			data = dtaux[2]+'/'+dtaux[1]+'/'+dtaux[0]
		}
		
		//console.log(data)
		dataset = { "numcarga": numcarga, "nome": nome, "veiculo": veiculo, "data": data };
		//console.log(dataset)
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'editarCarga', 'query': dataset },
			success: function (response) {
				//console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function openDeleteCarga(elm) {
		console.log($(elm).val)
		$('#modalDeleteCarga').modal('toggle');
		split = elm.value.split("-", 3)
		numcargaDel = split[0]
		nomeDel = split[1]

		$('#detele').text('Excluir Carga:' + nomeDel);
		$('#idDelete').text(numcargaDel);
	}

	function openDeleteCarga(elm) {
		$('#modalDeleteCarga').modal('toggle');
		split = elm.value.split("-", 3)
		numcargaDel = split[0]
		nomeDel = split[1]
		//console.log(numcargaDel+' - '+nomeDel)
		$('#detele').text('Excluir Carga: ' + nomeDel + '?');
		$('#idDelete').text(numcargaDel);
	}

	function deleteCarga() {
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'deleteCarga', 'query': numcargaDel },
			success: function (response) {
				console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function resumoHoverIn(elm){
		$(elm).css('background-color', 'yellow')
	}
	function resumoHoverOut(elm){
		$(elm).css('background-color', 'transparent')
	}


	function listaHoverIn(elm){
		$(elm).css('background-color', 'gold')
	}
	function listaHoverOut(elm){
		$(elm).css('background-color', 'transparent')
	}
		//MODAL DE FALTAS
		function getFaltasCli2(elm){
		cod = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle.php',
			data: { 'action': 'getFaltasCli', 'query': cod },
			success: function (response) {
				arr = JSON.parse(response);
				body = "";			
				arr.forEach(function(t){
					console.log(t)
					if(parseFloat(t['ESTOQUE'])>=parseFloat(t['QTFALTA'])){
						body += '<tr>'
							+'<td style="text-align:center">'+t['NUMNOTA']+'</td>'
							+'<td style="text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="padding-left:10px">'+t['DESCRICAO']+'</td>'
							+'<td style="text-align:center">'+t['QTFALTA']+'</td>'
							+'<td style="text-align:center">'+t['ESTOQUE']+'</td>'
							+'<td style="text-align:center">'+t['DATA']+'</td>'
							+'<td style="text-align:center">'+t['MOTIVO']+'</td>'
							
						+'</tr>';
					}else{
						body += '<tr>'
							+'<td style="color:red; text-align:center">'+t['NUMNOTA']+'</td>'
							+'<td style="color:red; text-align:center">'+t['CODPROD']+'</td>'
							+'<td style="color:red; padding-left:10px">'+t['DESCRICAO']+'</td>'
							+'<td style="color:red; text-align:center">'+t['QTFALTA']+'</td>'
							+'<td style="color:red; text-align:center">'+t['ESTOQUE']+'</td>'
							+'<td style="color:red; text-align:center">'+t['DATA']+'</td>'
							+'<td style="color:red; text-align:center">'+t['MOTIVO']+'</td>'
							
						+'</tr>';
					}
				})
				$('#pendenciasFaltas').empty();
				$('#pendenciasFaltas').append(body);
				$('#modalVerPendenciasfaltas').modal('toggle');
			}
		});
	}
</script>

<script>
     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
</script>

</html>